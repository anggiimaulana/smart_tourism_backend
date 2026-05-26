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
    public function __construct(
        private readonly NongkrongService $nongkrongService,
        private readonly \App\Services\FastApiProxyService $proxy
    ) {}

    public function index(Request $request): JsonResponse
    {
        // Normalisasi wilayah agar tidak case-sensitive
        if ($request->has('wilayah')) {
            $request->merge([
                'wilayah' => ucfirst(strtolower($request->wilayah))
            ]);
        }

        $filters = $request->validate([
            'wilayah'      => 'nullable|string|in:Indramayu,Cirebon,Majalengka,Kuningan',
            'tipe'         => 'nullable|string|max:100',
            'sentimen'     => 'nullable|string|in:positif,negatif,netral',
            'ada_wifi'     => 'nullable|boolean',
            'ada_colokan'  => 'nullable|boolean',
            'sort'         => 'nullable|string|in:rating,terbaru,nama',
            'per_page'     => 'nullable|integer|min:1|max:50',
            'q'            => 'nullable|string|max:100',
        ]);

        $paginator = $this->nongkrongService->list($filters);
        return $this->paginated(
            $paginator->through(fn($n) => new NongkrongResource($n))
        );
    }

    public function show(string $kode): JsonResponse
    {
        $nongkrong = $this->nongkrongService->findByKode($kode);

        if (! $nongkrong) {
            return $this->error('Tempat nongkrong tidak ditemukan.', 404);
        }

        if ($user = auth('sanctum')->user()) {
            try {
                $this->proxy->post('/api/v1/recommendation/history', [
                    'user_id'     => (string) $user->id,
                    'tipe_tempat' => 'nongkrong',
                    'tempat_kode' => $kode,
                    'aksi'        => 'klik',
                ]);
            } catch (\Exception $e) {
                // Silently fail
            }
        }

        return $this->success(new NongkrongResource($nongkrong));
    }

    public function store(StoreNongkrongRequest $request): JsonResponse
    {
        $nongkrong = $this->nongkrongService->create($request->validated());
        return $this->success(new NongkrongResource($nongkrong), 'Tempat nongkrong berhasil ditambahkan.', 201);
    }

    public function update(UpdateNongkrongRequest $request, string $kode): JsonResponse
    {
        $nongkrong = $this->nongkrongService->update($kode, $request->validated());

        if (! $nongkrong) {
            return $this->error('Tempat nongkrong tidak ditemukan.', 404);
        }

        return $this->success(new NongkrongResource($nongkrong), 'Tempat nongkrong berhasil diperbarui.');
    }

    public function destroy(string $kode): JsonResponse
    {
        $deleted = $this->nongkrongService->delete($kode);

        if (! $deleted) {
            return $this->error('Tempat nongkrong tidak ditemukan.', 404);
        }

        return $this->success(null, 'Tempat nongkrong berhasil dihapus.');
    }
}
