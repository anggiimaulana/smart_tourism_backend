<?php

namespace App\Repositories;

use App\Models\Wisata;
use App\Repositories\Contracts\WisataRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class WisataRepository implements WisataRepositoryInterface
{
    // Kolom untuk list — ringan, tidak pakai select *
    private array $listColumns = [
        'id', 'kode', 'nama', 'wilayah', 'kecamatan',
        'kategori_utama', 'jenis_tempat',
        'gambar',                   // TEXT[] — gambar_utama diambil dari gambar[0] di Resource
        'rating_google', 'jumlah_ulasan_google',
        'sentimen', 'skor_sentimen',
        'alamat_lengkap',
        'harga_tiket_min', 'harga_tiket_max', 'gratis',
        'jam_buka', 'jam_tutup',
        'latitude', 'longitude',
        'link_google_maps',
        'status',
    ];

    // Kolom untuk detail — lengkap
    private array $detailColumns = [
        'id', 'kode', 'nama', 'wilayah', 'kecamatan',
        'kategori_utama', 'sub_kategori', 'jenis_tempat',
        'deskripsi', 'alamat_lengkap',
        'latitude', 'longitude', 'link_google_maps',
        'jam_buka', 'jam_tutup', 'hari_libur_operasional',
        'estimasi_durasi_jam',
        'harga_tiket_min', 'harga_tiket_max', 'gratis',
        'fasilitas', 'aksesibilitas', 'moda_transportasi',
        'rating_google', 'jumlah_ulasan_google',
        'link_instagram', 'link_website', 'kontak',
        'gambar',
        'sentimen', 'skor_sentimen',
        'total_ulasan_scraped', 'total_positif', 'total_negatif',
        'status',
    ];

    public function paginate(array $filters): LengthAwarePaginator
    {
        $cacheKey = 'wisata_list_' . md5(json_encode($filters));

        return Cache::remember($cacheKey, config('smart_tourism.cache_ttl.wisata_list', 3600), function () use ($filters) {
            $query = Wisata::select($this->listColumns)->aktif();

            if (! empty($filters['wilayah']))
                $query->byWilayah($filters['wilayah']);

            if (! empty($filters['kategori_utama']))
                $query->byKategori($filters['kategori_utama']);

            if (! empty($filters['sentimen']))
                $query->bySentimen($filters['sentimen']);

            if (! empty($filters['q']))
                $query->where('nama', 'ilike', "%{$filters['q']}%");

            if (! empty($filters['gratis']))
                $query->where('gratis', true);

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

    public function findByKode(string $kode): ?Wisata
    {
        return Cache::remember("wisata_detail_{$kode}", config('smart_tourism.cache_ttl.wisata_detail', 7200), function () use ($kode) {
            return Wisata::with(['kuliners', 'nongkrongs'])
                         ->select($this->detailColumns)
                         ->where('kode', $kode)
                         ->where('status', 'aktif')
                         ->first();
        });
    }

    public function create(array $data): Wisata
    {
        $wisata = Wisata::create($data);
        $this->clearCache($wisata->kode);
        return $wisata;
    }

    public function update(string $kode, array $data): ?Wisata
    {
        $wisata = Wisata::where('kode', $kode)->first();
        if (! $wisata) return null;

        $wisata->update($data);
        $this->clearCache($kode);

        return $wisata->fresh($this->detailColumns);
    }

    public function delete(string $kode): bool
    {
        $deleted = Wisata::where('kode', $kode)->delete();
        $this->clearCache($kode);
        return (bool) $deleted;
    }

    private function clearCache(string $kode): void
    {
        Cache::forget("wisata_detail_{$kode}");
        // Clear list cache might be complex due to many filters, 
        // usually we use tags but Laravel's file cache doesn't support them.
        // For simplicity in this dev stage, we might just clear detail.
    }
}
