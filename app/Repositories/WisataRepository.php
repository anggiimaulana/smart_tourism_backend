<?php

namespace App\Repositories;

use App\Models\Wisata;
use App\Repositories\Contracts\WisataRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class WisataRepository implements WisataRepositoryInterface
{
    // Kolom yang ditampilkan di list — TIDAK BOLEH select *
    private array $listColumns = [
        'kode',
        'nama',
        'wilayah',
        'kategori',
        'gambar',
        'rating',
        'sentimen',
        'sentimen_positif_pct',
        'alamat',
        'harga_tiket',
    ];

    private array $detailColumns = [
        'kode',
        'nama',
        'wilayah',
        'kategori',
        'deskripsi',
        'alamat',
        'latitude',
        'longitude',
        'jam_buka',
        'harga_tiket',
        'no_telepon',
        'website',
        'gambar',
        'rating',
        'jumlah_ulasan',
        'sentimen',
        'sentimen_positif_pct',
        'fasilitas',
    ];

    public function paginate(array $filters): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            [],
            0,
            $filters['per_page'] ?? 12,
            1,
            ['path' => '/api/v1/wisata']
        );
    }

    public function findByKode(string $kode): ?Wisata
    {
        return null;
    }

    public function create(array $data): Wisata
    {
        return new Wisata($data);
    }

    public function update(string $kode, array $data): ?Wisata
    {
        return new Wisata(array_merge(['kode' => $kode], $data));
    }

    public function delete(string $kode): bool
    {
        return false;
    }
}
