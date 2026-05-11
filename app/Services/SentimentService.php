<?php

namespace App\Services;

use App\Models\SentimentResult;
use App\Models\Wisata;
use App\Models\Kuliner;
use App\Models\Nongkrong;
use Illuminate\Support\Facades\DB;

class SentimentService
{
    /**
     * Ringkasan sentimen per wilayah
     */
    public function getSummary(string $wilayah, string $tipeTempat = 'all'): array
    {
        $query = SentimentResult::query();

        // Join dengan tabel tempat untuk filter wilayah
        // Karena sentiment_results hanya punya tempat_kode
        // Kita perlu join ke tabel yang sesuai
        
        // Namun cara lebih simpel: Ambil semua kode dari wilayah tersebut dulu
        $kodes = [];
        if ($tipeTempat === 'all' || $tipeTempat === 'wisata') {
            $kodes = array_merge($kodes, Wisata::where('wilayah', $wilayah)->pluck('kode')->toArray());
        }
        if ($tipeTempat === 'all' || $tipeTempat === 'kuliner') {
            $kodes = array_merge($kodes, Kuliner::where('wilayah', $wilayah)->pluck('kode')->toArray());
        }
        if ($tipeTempat === 'all' || $tipeTempat === 'nongkrong') {
            $kodes = array_merge($kodes, Nongkrong::where('wilayah', $wilayah)->pluck('kode')->toArray());
        }

        $stats = SentimentResult::whereIn('tempat_kode', $kodes)
            ->select(
                DB::raw('count(*) as total_ulasan'),
                DB::raw("count(*) FILTER (WHERE sentimen = 'positif') as positif"),
                DB::raw("count(*) FILTER (WHERE sentimen = 'negatif') as negatif"),
                DB::raw("count(*) FILTER (WHERE sentimen = 'netral') as netral"),
                DB::raw('avg(confidence) as avg_confidence')
            )
            ->first();

        $total = (int) $stats->total_ulasan;
        
        return [
            'wilayah' => $wilayah,
            'tipe' => $tipeTempat,
            'total_ulasan' => $total,
            'positif' => (int) $stats->positif,
            'negatif' => (int) $stats->negatif,
            'netral' => (int) $stats->netral,
            'persentase_positif' => $total > 0 ? round(($stats->positif / $total) * 100, 2) : 0,
            'avg_confidence' => round($stats->avg_confidence ?? 0, 4)
        ];
    }

    /**
     * Ringkasan sentimen semua wilayah
     */
    public function getSummaryAll(): array
    {
        $wilayahs = config('smart_tourism.wilayah');
        $results = [];

        foreach ($wilayahs as $w) {
            $results[] = $this->getSummary($w);
        }

        return $results;
    }

    /**
     * Sinkronisasi sentimen ke tabel utama per tempat
     */
    public function syncSentimen(string $tipe, string $kode): array
    {
        $stats = SentimentResult::where('tempat_kode', $kode)
            ->select(
                DB::raw('count(*) as total'),
                DB::raw("count(*) FILTER (WHERE sentimen = 'positif') as positif"),
                DB::raw("count(*) FILTER (WHERE sentimen = 'negatif') as negatif"),
                DB::raw('avg(confidence) as avg_score')
            )
            ->first();

        if ($stats->total == 0) {
            return ['status' => 'no_data', 'kode' => $kode];
        }

        $label = 'netral';
        if ($stats->positif > $stats->negatif) $label = 'positif';
        if ($stats->negatif > $stats->positif) $label = 'negatif';

        $dataUpdate = [
            'sentimen' => $label,
            'skor_sentimen' => $stats->avg_score,
            'total_ulasan_scraped' => $stats->total,
            'total_positif' => $stats->positif,
            'total_negatif' => $stats->negatif,
        ];

        switch ($tipe) {
            case 'wisata': Wisata::where('kode', $kode)->update($dataUpdate); break;
            case 'kuliner': Kuliner::where('kode', $kode)->update($dataUpdate); break;
            case 'nongkrong': Nongkrong::where('kode', $kode)->update($dataUpdate); break;
        }

        return array_merge(['kode' => $kode, 'tipe' => $tipe], $dataUpdate);
    }

    /**
     * Sinkronisasi massal semua tempat
     */
    public function syncAll(): array
    {
        $wisatas = Wisata::pluck('kode')->toArray();
        $kuliners = Kuliner::pluck('kode')->toArray();
        $nongkrongs = Nongkrong::pluck('kode')->toArray();

        $count = 0;
        foreach ($wisatas as $k) { $this->syncSentimen('wisata', $k); $count++; }
        foreach ($kuliners as $k) { $this->syncSentimen('kuliner', $k); $count++; }
        foreach ($nongkrongs as $k) { $this->syncSentimen('nongkrong', $k); $count++; }

        return ['total_synced' => $count];
    }

    /**
     * Detail sentimen untuk satu tempat (untuk halaman detail)
     */
    public function getPlaceSummary(string $kode): array
    {
        $stats = SentimentResult::where('tempat_kode', $kode)
            ->select(
                DB::raw('count(*) as total_ulasan'),
                DB::raw("count(*) FILTER (WHERE sentimen = 'positif') as positif"),
                DB::raw("count(*) FILTER (WHERE sentimen = 'negatif') as negatif"),
                DB::raw("count(*) FILTER (WHERE sentimen = 'netral') as netral"),
                DB::raw('avg(confidence) as avg_confidence')
            )
            ->first();

        $reviews = SentimentResult::where('tempat_kode', $kode)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['ulasan_asli', 'sentimen', 'confidence', 'created_at']);

        return [
            'kode' => $kode,
            'summary' => [
                'total_ulasan' => (int) $stats->total_ulasan,
                'positif' => (int) $stats->positif,
                'negatif' => (int) $stats->negatif,
                'netral' => (int) $stats->netral,
                'skor_rata_rata' => round($stats->avg_confidence ?? 0, 4),
            ],
            'recent_reviews' => $reviews
        ];
    }

}
