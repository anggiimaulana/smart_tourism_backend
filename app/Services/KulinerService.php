<?php

namespace App\Services;

use App\Models\Kuliner;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class KulinerService
{
    private array $listColumns = [
        'id',
        'kode',
        'nama',
        'wilayah',
        'kecamatan',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'jenis_tempat',
        'kategori_menu_utama',
        'harga_menu_min',
        'harga_menu_max',
        'jam_buka',
        'jam_tutup',
        'kapasitas_orang',
        'fasilitas',
        'sertifikat_halal',
        'rating_google',
        'jumlah_ulasan_google',
        'link_google_maps',
        'kontak',
        'gambar',
        'status',
        'sentimen',
        'skor_sentimen',
    ];

    private array $detailColumns = [
        'id',
        'kode',
        'id_wisata_terdekat',
        'nama',
        'wilayah',
        'kecamatan',
        'alamat_lengkap',
        'latitude',
        'longitude',
        'jenis_tempat',
        'kategori_menu_utama',
        'menu_unggulan',
        'makanan_khas_daerah',
        'nama_makanan_khas',
        'harga_menu_min',
        'harga_menu_max',
        'jam_buka',
        'jam_tutup',
        'kapasitas_orang',
        'fasilitas',
        'sertifikat_halal',
        'rating_google',
        'jumlah_ulasan_google',
        'link_google_maps',
        'kontak',
        'gambar',
        'sumber_data',
        'catatan',
        'status',
        'sentimen',
        'skor_sentimen',
        'total_ulasan_scraped',
        'total_positif',
        'total_negatif',
        'created_at',
        'updated_at',
    ];

    public function list(array $filters): LengthAwarePaginator
    {
        $cacheKey = 'kuliner_list_' . md5(json_encode($filters));

        return Cache::remember($cacheKey, config('smart_tourism.cache_ttl.kuliner_list', 3600), function () use ($filters) {
            $query = Kuliner::select($this->listColumns)->aktif();

            if (! empty($filters['wilayah'])) {
                $query->byWilayah($filters['wilayah']);
            }

            if (! empty($filters['jenis'])) {
                $query->where('jenis_tempat', $filters['jenis']);
            }

            if (! empty($filters['sentimen'])) {
                $query->bySentimen($filters['sentimen']);
            }

            if (! empty($filters['q'])) {
                $query->where('nama', 'ilike', '%' . $filters['q'] . '%');
            }

            match ($filters['sort'] ?? 'rating') {
                'terbaru' => $query->latest('created_at'),
                'nama' => $query->orderBy('nama'),
                default => $query->popular(),
            };

            return $query->paginate(
                $filters['per_page'] ?? config('smart_tourism.pagination.default', 12)
            );
        });
    }

    public function findByKode(string $kode): ?Kuliner
    {
        return Cache::remember("kuliner_detail_{$kode}", config('smart_tourism.cache_ttl.kuliner_detail', 7200), function () use ($kode) {
            return Kuliner::select($this->detailColumns)
                ->where('kode', $kode)
                ->where('status', 'aktif')
                ->first();
        });
    }

    public function create(array $data): Kuliner
    {
        $data['kode'] = $data['kode'] ?? 'KUL-' . strtoupper(Str::random(6));

        $kuliner = Kuliner::create($data);
        Cache::forget("kuliner_detail_{$kuliner->kode}");

        return $kuliner;
    }

    public function update(string $kode, array $data): ?Kuliner
    {
        $kuliner = Kuliner::where('kode', $kode)->first();

        if (! $kuliner) {
            return null;
        }

        $kuliner->update($data);
        Cache::forget("kuliner_detail_{$kode}");

        return $kuliner->fresh($this->detailColumns);
    }

    public function delete(string $kode): bool
    {
        $deleted = Kuliner::where('kode', $kode)->delete();
        Cache::forget("kuliner_detail_{$kode}");

        return (bool) $deleted;
    }
}
