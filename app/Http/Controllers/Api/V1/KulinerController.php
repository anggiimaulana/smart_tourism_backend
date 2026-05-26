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
    public function __construct(
        private readonly KulinerService $kulinerService,
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
            'jenis'        => 'nullable|string|in:Restoran,Warung,Cafe,Kedai,Food Court,Angkringan,Lainnya',
            'sentimen'     => 'nullable|string|in:positif,negatif,netral',
            'sort'         => 'nullable|string|in:rating,terbaru,nama',
            'per_page'     => 'nullable|integer|min:1|max:50',
            'q'            => 'nullable|string|max:100',
        ]);

        $paginator = $this->kulinerService->list($filters);
        return $this->paginated(
            $paginator->through(fn($k) => new KulinerResource($k))
        );
    }

    public function show(string $kode): JsonResponse
    {
        $kuliner = $this->kulinerService->findByKode($kode);

        if (! $kuliner) {
            return $this->error('Data kuliner tidak ditemukan.', 404);
        }

        if ($user = auth('sanctum')->user()) {
            try {
                $this->proxy->post('/api/v1/recommendation/history', [
                    'user_id'     => (string) $user->id,
                    'tipe_tempat' => 'kuliner',
                    'tempat_kode' => $kode,
                    'aksi'        => 'klik',
                ]);
            } catch (\Exception $e) {
                // Silently fail
            }
        }

        return $this->success(new KulinerResource($kuliner));
    }

    public function store(StoreKulinerRequest $request): JsonResponse
    {
        $kuliner = $this->kulinerService->create($request->validated());
        return $this->success(new KulinerResource($kuliner), 'Data kuliner berhasil ditambahkan.', 201);
    }

    public function update(UpdateKulinerRequest $request, string $kode): JsonResponse
    {
        $kuliner = $this->kulinerService->update($kode, $request->validated());

        if (! $kuliner) {
            return $this->error('Data kuliner tidak ditemukan.', 404);
        }

        return $this->success(new KulinerResource($kuliner), 'Data kuliner berhasil diperbarui.');
    }

    public function destroy(string $kode): JsonResponse
    {
        $deleted = $this->kulinerService->delete($kode);

        if (! $deleted) {
            return $this->error('Data kuliner tidak ditemukan.', 404);
        }

        return $this->success(null, 'Data kuliner berhasil dihapus.');
    }
}
