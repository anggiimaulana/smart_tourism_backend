<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Wisata\StoreWisataRequest;
use App\Http\Requests\Wisata\UpdateWisataRequest;
use App\Http\Resources\WisataResource;
use App\Services\WisataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WisataController extends BaseApiController
{
    public function __construct(
        private readonly WisataService $wisataService,
        private readonly \App\Services\FastApiProxyService $proxy
    ) {}

    public function index(Request $request): JsonResponse
    {
        // Normalisasi wilayah agar tidak case-sensitive (majalengka -> Majalengka)
        if ($request->has('wilayah')) {
            $request->merge([
                'wilayah' => ucfirst(strtolower($request->wilayah))
            ]);
        }

        $filters = $request->validate([
            'wilayah'        => 'nullable|string|in:Indramayu,Cirebon,Majalengka,Kuningan',
            'kategori_utama' => 'nullable|string|in:Alam,Buatan,Budaya,Religi,Petualangan,Edukasi,Lainnya',
            'sentimen'       => 'nullable|string|in:positif,negatif,netral',
            'gratis'         => 'nullable|boolean',
            'sort'           => 'nullable|string|in:rating,terbaru,nama',
            'per_page'       => 'nullable|integer|min:1|max:50',
            'q'              => 'nullable|string|max:100',
        ]);

        $paginator = $this->wisataService->list($filters);
        return $this->paginated(
            $paginator->through(fn($w) => new WisataResource($w))
        );
    }

    public function show(string $kode): JsonResponse
    {
        $wisata = $this->wisataService->findByKode($kode);

        if (! $wisata) {
            return $this->success(null, 'Wisata tidak ditemukan.', 200, [], false);
        }

        if ($user = auth('sanctum')->user()) {
            try {
                $this->proxy->post('/api/v1/recommendation/history', [
                    'user_id'     => (string) $user->id,
                    'tipe_tempat' => 'wisata',
                    'tempat_kode' => $kode,
                    'aksi'        => 'klik',
                ]);
            } catch (\Exception $e) {
                // Silently fail to not break the response
            }
        }

        return $this->success(new WisataResource($wisata));
    }

    public function store(StoreWisataRequest $request): JsonResponse
    {
        $wisata = $this->wisataService->create($request->validated());
        return $this->success(new WisataResource($wisata), 'Wisata berhasil ditambahkan.', 201);
    }

    public function update(UpdateWisataRequest $request, string $kode): JsonResponse
    {
        $wisata = $this->wisataService->update($kode, $request->validated());

        if (! $wisata) {
            return $this->success(null, 'Wisata tidak ditemukan.', 200, [], false);
        }

        return $this->success(new WisataResource($wisata), 'Wisata berhasil diperbarui.');
    }

    public function destroy(string $kode): JsonResponse
    {
        $deleted = $this->wisataService->delete($kode);

        if (! $deleted) {
            return $this->success(null, 'Wisata tidak ditemukan.', 200, [], false);
        }

        return $this->success(null, 'Wisata berhasil dihapus.');
    }
}
