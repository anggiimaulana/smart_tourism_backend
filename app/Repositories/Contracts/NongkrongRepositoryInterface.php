<?php

namespace App\Repositories\Contracts;

use App\Models\Nongkrong;
use Illuminate\Pagination\LengthAwarePaginator;

interface NongkrongRepositoryInterface
{
    public function paginate(array $filters): LengthAwarePaginator;
    public function findByKode(string $kode): ?Nongkrong;
    public function create(array $data): Nongkrong;
    public function update(string $kode, array $data): ?Nongkrong;
    public function delete(string $kode): bool;
}
