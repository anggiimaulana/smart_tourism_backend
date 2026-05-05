<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Kuliner\StoreKulinerRequest;
use App\Http\Requests\Kuliner\UpdateKulinerRequest;
use App\Http\Resources\KulinerResource;
use App\Services\KulinerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KulinerController extends BaseApiController
{
    public function __construct(private readonly KulinerService $kulinerService) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'wilayah'  => 'nullable|string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'jenis'    => 'nullable|string|max:100',
            'sentimen' => 'nullable|string|in:positif,negatif,belum_dianalisis',
            'sort'     => 'nullable|string|in:rating,terbaru,nama',
            'per_page' => 'nullable|integer|min:1|max:50',
            'q'        => 'nullable|string|max:100',
        ]);

        // TODO: $this->kulinerService->list($filters)
        $paginator = $this->kulinerService->list($filters);
        return $this->paginated(
            $paginator->through(fn($k) => new KulinerResource($k))
        );
    }

    public function show(string $kode): JsonResponse
    {
        // TODO: $this->kulinerService->findByKode($kode)
        $kuliner = $this->kulinerService->findByKode($kode);

        if (! $kuliner) {
            return $this->error('Kuliner tidak ditemukan.', 404);
        }

        return $this->success(new KulinerResource($kuliner));
    }

    public function store(StoreKulinerRequest $request): JsonResponse
    {
        // TODO: $this->kulinerService->create($request->validated())
        $kuliner = $this->kulinerService->create($request->validated());
        return $this->success(new KulinerResource($kuliner), 'Kuliner berhasil ditambahkan.', 201);
    }

    public function update(UpdateKulinerRequest $request, string $kode): JsonResponse
    {
        // TODO: $this->kulinerService->update($kode, $request->validated())
        $kuliner = $this->kulinerService->update($kode, $request->validated());

        if (! $kuliner) {
            return $this->error('Kuliner tidak ditemukan.', 404);
        }

        return $this->success(new KulinerResource($kuliner), 'Kuliner berhasil diperbarui.');
    }

    public function destroy(string $kode): JsonResponse
    {
        // TODO: $this->kulinerService->delete($kode)
        $deleted = $this->kulinerService->delete($kode);

        if (! $deleted) {
            return $this->error('Kuliner tidak ditemukan.', 404);
        }

        return $this->success(null, 'Kuliner berhasil dihapus.');
    }
}
