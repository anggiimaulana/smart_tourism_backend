<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Nongkrong\StoreNongkrongRequest;
use App\Http\Requests\Nongkrong\UpdateNongkrongRequest;
use App\Http\Resources\NongkrongResource;
use App\Services\NongkrongService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NongkrongController extends BaseApiController
{
    public function __construct(private readonly NongkrongService $nongkrongService) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'wilayah'     => 'nullable|string|in:Cirebon,Indramayu,Majalengka,Kuningan',
            'tipe'        => 'nullable|string|max:100',
            'ada_wifi'    => 'nullable|boolean',
            'ada_colokan' => 'nullable|boolean',
            'sentimen'    => 'nullable|string|in:positif,negatif,belum_dianalisis',
            'sort'        => 'nullable|string|in:rating,terbaru,nama',
            'per_page'    => 'nullable|integer|min:1|max:50',
            'q'           => 'nullable|string|max:100',
        ]);

        // TODO: $this->nongkrongService->list($filters)
        $paginator = $this->nongkrongService->list($filters);
        return $this->paginated(
            $paginator->through(fn($n) => new NongkrongResource($n))
        );
    }

    public function show(string $kode): JsonResponse
    {
        // TODO: $this->nongkrongService->findByKode($kode)
        $nongkrong = $this->nongkrongService->findByKode($kode);

        if (! $nongkrong) {
            return $this->error('Tempat nongkrong tidak ditemukan.', 404);
        }

        return $this->success(new NongkrongResource($nongkrong));
    }

    public function store(StoreNongkrongRequest $request): JsonResponse
    {
        // TODO: $this->nongkrongService->create($request->validated())
        $nongkrong = $this->nongkrongService->create($request->validated());
        return $this->success(new NongkrongResource($nongkrong), 'Tempat nongkrong berhasil ditambahkan.', 201);
    }

    public function update(UpdateNongkrongRequest $request, string $kode): JsonResponse
    {
        // TODO: $this->nongkrongService->update($kode, $request->validated())
        $nongkrong = $this->nongkrongService->update($kode, $request->validated());

        if (! $nongkrong) {
            return $this->error('Tempat nongkrong tidak ditemukan.', 404);
        }

        return $this->success(new NongkrongResource($nongkrong), 'Tempat nongkrong berhasil diperbarui.');
    }

    public function destroy(string $kode): JsonResponse
    {
        // TODO: $this->nongkrongService->delete($kode)
        $deleted = $this->nongkrongService->delete($kode);

        if (! $deleted) {
            return $this->error('Tempat nongkrong tidak ditemukan.', 404);
        }

        return $this->success(null, 'Tempat nongkrong berhasil dihapus.');
    }
}
