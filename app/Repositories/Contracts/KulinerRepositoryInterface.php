<?php

namespace App\Repositories\Contracts;

use App\Models\Kuliner;
use Illuminate\Pagination\LengthAwarePaginator;

interface KulinerRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function findByKode(string $kode): ?Kuliner;
    public function create(array $data): Kuliner;
    public function update(string $kode, array $data): ?Kuliner;
    public function delete(string $kode): bool;
}
