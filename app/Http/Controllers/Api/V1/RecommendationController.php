<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Recommendation\RecommendationRequest;
use App\Http\Requests\Recommendation\PlanningRequest;
use App\Services\FastApiProxyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendationController extends BaseApiController
{
    public function __construct(private readonly FastApiProxyService $proxy) {}

    public function index(RecommendationRequest $request): JsonResponse
    {
        $payload = $request->validated();

        // Inject user_id dari token jika login
        if ($request->user()) {
            $payload['user_id'] = (string) $request->user()->id;
        }

        // Mapping ke field FastAPI
        if (isset($payload['limit'])) {
            $payload['jumlah'] = $payload['limit'];
        }

        $result = $this->proxy->post('/api/v1/recommendation/', $payload);
        return $this->success($result);
    }

    public function planning(PlanningRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // Mapping ke field FastAPI
        $payload = [
            'wilayah'          => $data['wilayah'],
            'jumlah_hari'      => $data['jumlah_hari'],
            'jumlah_orang'     => $data['jumlah_orang'] ?? 1,
            'budget_total'     => isset($data['budget']) ? (int) $data['budget'] : null,
            'preferensi'       => $data['kategori_preferensi'] ?? null,
            'tanggal_mulai'    => $data['tanggal_mulai'] ?? null,
            'catatan_tambahan' => $data['catatan'] ?? null,
        ];

        if ($request->user()) {
            $payload['user_id'] = (string) $request->user()->id;
        }

        $result = $this->proxy->post('/api/v1/recommendation/planning', $payload);
        return $this->success($result, 'Rencana berhasil dibuat.', 201);
    }

    public function trackHistory(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tipe_tempat'  => 'required|string|in:wisata,kuliner,nongkrong',
            'tempat_kode'  => 'required|string|max:20',
            'aksi'         => 'required|string|in:klik,kunjungi,simpan,rating,share',
            'nilai_rating' => 'nullable|numeric|min:1|max:5',
            'durasi_detik' => 'nullable|integer|min:0',
        ]);

        $this->proxy->post('/api/v1/recommendation/history', array_merge($data, [
            'user_id' => (string) $request->user()->id
        ]));

        return $this->success($data, 'Riwayat berhasil disimpan.', 201);
    }

    /**
     * Lihat daftar riwayat aktivitas user (verifikasi)
     */
    public function getHistory(Request $request): JsonResponse
    {
        $history = \App\Models\UserHistory::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return $this->success($history, 'Daftar riwayat aktivitas Anda.');
    }
}
