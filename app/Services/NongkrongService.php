<?php

namespace App\Services;

use App\Models\Nongkrong;
use App\Repositories\Contracts\NongkrongRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class NongkrongService
{
    public function __construct(
        private readonly NongkrongRepositoryInterface $nongkrongRepository
    ) {}

    public function list(array $filters): LengthAwarePaginator
    {
        return $this->nongkrongRepository->paginate($filters);
    }

    public function findByKode(string $kode): ?Nongkrong
    {
        return $this->nongkrongRepository->findByKode($kode);
    }

    public function create(array $data): Nongkrong
    {
        if (empty($data['kode'])) {
            $prefix = match ($data['wilayah'] ?? '') {
                'Indramayu'  => 'NKG-IDM',
                'Cirebon'    => 'NKG-CRB',
                'Majalengka' => 'NKG-MJK',
                'Kuningan'   => 'NKG-KNG',
                default      => 'NKG-XXX',
            };
            $count = Nongkrong::where('wilayah', $data['wilayah'])->count() + 1;
            $data['kode'] = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        return $this->nongkrongRepository->create($data);
    }

    public function update(string $kode, array $data): ?Nongkrong
    {
        return $this->nongkrongRepository->update($kode, $data);
    }

    public function delete(string $kode): bool
    {
        return $this->nongkrongRepository->delete($kode);
    }
}
