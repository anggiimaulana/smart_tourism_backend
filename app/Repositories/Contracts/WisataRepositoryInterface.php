<?php

namespace App\Repositories\Contracts;

use App\Models\Wisata;
use Illuminate\Pagination\LengthAwarePaginator;

interface WisataRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function findByKode(string $kode): ?Wisata;
    public function create(array $data): Wisata;
    public function update(string $kode, array $data): ?Wisata;
    public function delete(string $kode): bool;
}
