<?php

namespace App\Services;

use App\Models\Kuliner;
use App\Repositories\Contracts\KulinerRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class KulinerService
{
    public function __construct(
        private readonly KulinerRepositoryInterface $kulinerRepository
    ) {}

    public function list(array $filters): LengthAwarePaginator
    {
        return $this->kulinerRepository->paginate($filters);
    }

    public function findByKode(string $kode): ?Kuliner
    {
        return $this->kulinerRepository->findByKode($kode);
    }

    public function create(array $data): Kuliner
    {
        if (empty($data['kode'])) {
            $prefix = match ($data['wilayah'] ?? '') {
                'Indramayu'  => 'KUL-IDM',
                'Cirebon'    => 'KUL-CRB',
                'Majalengka' => 'KUL-MJK',
                'Kuningan'   => 'KUL-KNG',
                default      => 'KUL-XXX',
            };
            $count = Kuliner::where('wilayah', $data['wilayah'])->count() + 1;
            $data['kode'] = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        return $this->kulinerRepository->create($data);
    }

    public function update(string $kode, array $data): ?Kuliner
    {
        return $this->kulinerRepository->update($kode, $data);
    }

    public function delete(string $kode): bool
    {
        return $this->kulinerRepository->delete($kode);
    }
}
