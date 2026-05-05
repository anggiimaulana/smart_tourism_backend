<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Services\FastApiProxyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SentimentController extends BaseApiController
{
    public function __construct(private readonly FastApiProxyService $proxy) {}

    /**
     * Ringkasan sentimen per wilayah — publik
     * GET /api/v1/sentiment/summary/{wilayah}
     */
    public function summary(string $wilayah): JsonResponse
    {
        // Validasi wilayah
        if (! in_array($wilayah, config('smart_tourism.wilayah'))) {
            return $this->error('Wilayah tidak valid.', 422);
        }

        // TODO: proxy ke FastAPI atau query langsung ke sentiment_results
        $result = $this->proxy->get("/api/v1/sentiment/summary/{$wilayah}");
        return $this->success($result);
    }

    /**
     * Predict sentimen satu teks — admin only
     * POST /api/v1/admin/sentiment/predict
     */
    public function predict(Request $request): JsonResponse
    {
        $request->validate([
            'teks'       => 'required|string|min:3|max:1000',
            'model_used' => 'nullable|string|in:indobert,naive_bayes,svm,decision_tree',
        ]);

        // TODO: proxy ke FastAPI /api/v1/sentiment/predict
        $result = $this->proxy->post('/api/v1/sentiment/predict', $request->only('teks', 'model_used'));
        return $this->success($result);
    }

    /**
     * Sync hasil sentimen ke tabel utama — admin only
     * POST /api/v1/admin/sentiment/sync/{tipe}/{kode}
     */
    public function sync(string $tipe, string $kode): JsonResponse
    {
        if (! in_array($tipe, ['wisata', 'kuliner', 'nongkrong'])) {
            return $this->error('Tipe tidak valid.', 422);
        }

        // TODO: proxy ke FastAPI /api/v1/sentiment/sync/{tipe}/{kode}
        $result = $this->proxy->post("/api/v1/sentiment/sync/{$tipe}/{$kode}");
        return $this->success($result, 'Sinkronisasi sentimen berhasil.');
    }
}
