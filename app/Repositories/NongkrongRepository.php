<?php

namespace App\Repositories;

use App\Models\Nongkrong;
use App\Repositories\Contracts\NongkrongRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class NongkrongRepository implements NongkrongRepositoryInterface
{
    private array $listColumns = [
        'id', 'kode', 'nama', 'wilayah', 'kecamatan',
        'alamat_lengkap', 'latitude', 'longitude',
        'konsep_suasana', 'target_pengunjung', 'cocok_untuk',
        'harga_menu_min', 'harga_menu_max',
        'jam_buka', 'jam_tutup', 'kapasitas_orang',
        'fasilitas', 'batas_waktu_duduk',
        'rating_google', 'minimal_order',
        'link_google_maps', 'kontak', 'gambar',
        'status', 'sentimen', 'skor_sentimen',
    ];

    private array $detailColumns = [
        'id', 'kode', 'id_wisata_ref', 'nama', 'wilayah', 'kecamatan',
        'alamat_lengkap', 'latitude', 'longitude',
        'konsep_suasana', 'target_pengunjung', 'cocok_untuk',
        'menu_best_seller', 'harga_menu_min', 'harga_menu_max',
        'jam_buka', 'jam_tutup', 'kapasitas_orang',
        'fasilitas', 'batas_waktu_duduk',
        'rating_google', 'minimal_order',
        'link_google_maps', 'kontak', 'gambar',
        'sumber_data', 'catatan', 'status',
        'sentimen', 'skor_sentimen',
        'total_ulasan_scraped', 'total_positif', 'total_negatif',
        'created_at', 'updated_at',
    ];

    public function paginate(array $filters): LengthAwarePaginator
    {
        $cacheKey = 'nongkrong_list_' . md5(json_encode($filters));

        return Cache::remember($cacheKey, config('smart_tourism.cache_ttl.nongkrong_list', 3600), function () use ($filters) {
            $query = Nongkrong::select($this->listColumns)->aktif();

            if (! empty($filters['wilayah']))
                $query->byWilayah($filters['wilayah']);

            if (! empty($filters['tipe']))
                $query->where('cocok_untuk', $filters['tipe']);

            if (! empty($filters['sentimen']))
                $query->bySentimen($filters['sentimen']);

            if (! empty($filters['q']))
                $query->where('nama', 'ilike', '%' . $filters['q'] . '%');

            // PostgreSQL TEXT[] array containment check
            if (! empty($filters['ada_wifi']))
                $query->whereRaw("fasilitas @> ARRAY['wifi']::TEXT[]");

            if (! empty($filters['ada_colokan']))
                $query->whereRaw("fasilitas @> ARRAY['colokan']::TEXT[]");

            match ($filters['sort'] ?? 'rating') {
                'terbaru' => $query->latest('created_at'),
                'nama'    => $query->orderBy('nama'),
                default   => $query->popular(),
            };

            return $query->paginate(
                $filters['per_page'] ?? config('smart_tourism.pagination.default', 12)
            );
        });
    }

    public function findByKode(string $kode): ?Nongkrong
    {
        return Cache::remember("nongkrong_detail_{$kode}", config('smart_tourism.cache_ttl.nongkrong_detail', 7200), function () use ($kode) {
            return Nongkrong::with('wisataRef')
                         ->select($this->detailColumns)
                         ->where('kode', $kode)
                         ->where('status', 'aktif')
                         ->first();
        });
    }

    public function create(array $data): Nongkrong
    {
        $nongkrong = Nongkrong::create($data);
        $this->clearCache($nongkrong->kode);
        return $nongkrong;
    }

    public function update(string $kode, array $data): ?Nongkrong
    {
        $nongkrong = Nongkrong::where('kode', $kode)->first();
        if (! $nongkrong) return null;

        $nongkrong->update($data);
        $this->clearCache($kode);

        return $nongkrong->fresh($this->detailColumns);
    }

    public function delete(string $kode): bool
    {
        $deleted = Nongkrong::where('kode', $kode)->delete();
        $this->clearCache($kode);
        return (bool) $deleted;
    }

    private function clearCache(string $kode): void
    {
        Cache::forget("nongkrong_detail_{$kode}");
    }
}
