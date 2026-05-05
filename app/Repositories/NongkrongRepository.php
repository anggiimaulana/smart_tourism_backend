<?php

namespace App\Repositories;

use App\Models\Nongkrong;
use App\Repositories\Contracts\NongkrongRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class NongkrongRepository implements NongkrongRepositoryInterface
{
    private array $listColumns = [
        'kode',
        'nama',
        'wilayah',
        'tipe',
        'gambar',
        'rating',
        'sentimen',
        'sentimen_positif_pct',
        'alamat',
        'range_harga',
        'ada_wifi',
        'ada_colokan',
    ];

    private array $detailColumns = [
        'kode',
        'nama',
        'wilayah',
        'tipe',
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
        'fasilitas',
        'ada_wifi',
        'ada_colokan',
    ];

    public function paginate(array $filters): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            [],
            0,
            $filters['per_page'] ?? 12,
            1,
            ['path' => '/api/v1/nongkrong']
        );
    }

    public function findByKode(string $kode): ?Nongkrong
    {
        return null;
    }

    public function create(array $data): Nongkrong
    {
        return new Nongkrong($data);
    }

    public function update(string $kode, array $data): ?Nongkrong
    {
        return new Nongkrong(array_merge(['kode' => $kode], $data));
    }

    public function delete(string $kode): bool
    {
        return false;
    }
}
