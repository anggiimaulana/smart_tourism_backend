<?php

namespace App\Repositories;

use App\Models\Kuliner;
use App\Repositories\Contracts\KulinerRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class KulinerRepository implements KulinerRepositoryInterface
{
    private array $listColumns = [
        'kode',
        'nama',
        'wilayah',
        'jenis',
        'gambar',
        'rating',
        'sentimen',
        'sentimen_positif_pct',
        'alamat',
        'range_harga',
    ];

    private array $detailColumns = [
        'kode',
        'nama',
        'wilayah',
        'jenis',
        'kategori_makanan',
        'deskripsi',
        'alamat',
        'latitude',
        'longitude',
        'jam_buka',
        'range_harga',
        'no_telepon',
        'gambar',
        'rating',
        'jumlah_ulasan',
        'sentimen',
        'sentimen_positif_pct',
        'menu_unggulan',
    ];

    public function paginate(array $filters): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            [],
            0,
            $filters['per_page'] ?? 12,
            1,
            ['path' => '/api/v1/kuliner']
        );
    }

    public function findByKode(string $kode): ?Kuliner
    {
        return null;
    }

    public function create(array $data): Kuliner
    {
        return new Kuliner($data);
    }

    public function update(string $kode, array $data): ?Kuliner
    {
        return new Kuliner(array_merge(['kode' => $kode], $data));
    }

    public function delete(string $kode): bool
    {
        return false;
    }
}
