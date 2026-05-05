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
            $payload['user_id'] = $request->user()->id;
        }

        $result = $this->proxy->post('/api/v1/recommendation/', $payload);
        return $this->success($result);
    }

    public function planning(PlanningRequest $request): JsonResponse
    {
        $result = $this->proxy->post('/api/v1/recommendation/planning', $request->validated());
        return $this->success($result);
    }

    public function trackHistory(Request $request): JsonResponse
    {
        $request->validate([
            'tipe_tempat' => 'required|string|in:wisata,kuliner,nongkrong',
            'tempat_kode' => 'required|string|max:20',
            'aksi'        => 'required|string|in:klik,kunjungan,simpan,rating',
            'rating_user' => 'nullable|numeric|min:1|max:5',
        ]);

        $this->proxy->post('/api/v1/recommendation/history', array_merge(
            $request->only('tipe_tempat', 'tempat_kode', 'aksi', 'rating_user'),
            ['user_id' => $request->user()->id]
        ));

        return $this->success(null, 'Riwayat berhasil disimpan.');
    }
}
