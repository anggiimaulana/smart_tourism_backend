<?php

namespace App\Services;

use App\Models\Wisata;
use App\Repositories\Contracts\WisataRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class WisataService
{
    public function __construct(
        private readonly WisataRepositoryInterface $wisataRepository
    ) {}

    public function list(array $filters): LengthAwarePaginator
    {
        return $this->wisataRepository->paginate($filters);
    }

    public function findByKode(string $kode): ?Wisata
    {
        return $this->wisataRepository->findByKode($kode);
    }

    public function create(array $data): Wisata
    {
        // Generate kode jika tidak disediakan
        if (empty($data['kode'])) {
            $prefix = match ($data['wilayah'] ?? '') {
                'Indramayu'  => 'WIS-IDM',
                'Cirebon'    => 'WIS-CRB',
                'Majalengka' => 'WIS-MJK',
                'Kuningan'   => 'WIS-KNG',
                default      => 'WIS-XXX',
            };
            // Logic ini tetap di service karena butuh query count (bisa dipindah ke repo jika mau lebih strict)
            $count = Wisata::where('wilayah', $data['wilayah'])->count() + 1;
            $data['kode'] = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        $data['status'] = $data['status'] ?? 'draft';
        return $this->wisataRepository->create($data);
    }

    public function update(string $kode, array $data): ?Wisata
    {
        return $this->wisataRepository->update($kode, $data);
    }

    public function delete(string $kode): bool
    {
        return $this->wisataRepository->delete($kode);
    }
}
