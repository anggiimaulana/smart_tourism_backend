<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Services\SentimentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SentimentController extends BaseApiController
{
    public function __construct(private readonly SentimentService $service) {}

    /**
     * Ringkasan sentimen per wilayah — publik
     * GET /api/v1/sentiment/summary/{wilayah}
     */
    public function summary(string $wilayah, Request $request): JsonResponse
    {
        $wilayah = ucfirst(strtolower($wilayah));

        if (! in_array($wilayah, config('smart_tourism.wilayah'))) {
            return $this->error('Wilayah tidak valid.', 422);
        }

        $tipe = $request->query('tipe_tempat', 'all');
        $result = $this->service->getSummary($wilayah, $tipe);
        return $this->success($result);
    }

    /**
     * Ringkasan sentimen semua wilayah
     * GET /api/v1/sentiment/summary-all
     */
    public function summaryAll(): JsonResponse
    {
        $result = $this->service->getSummaryAll();
        return $this->success($result);
    }

    /**
     * Detail sentimen per tempat (berdasarkan kode)
     * GET /api/v1/sentiment/detail/{kode}
     */
    public function show(string $kode): JsonResponse
    {
        $result = $this->service->getPlaceSummary($kode);
        
        if ($result['summary']['total_ulasan'] === 0) {
            return $this->error('Data sentimen tidak ditemukan.', 404);
        }

        return $this->success($result);
    }

    /**
     * Sync hasil sentimen ke tabel utama per tempat — admin only
     * POST /api/v1/admin/sentiment/sync/{tipe}/{kode}
     */
    public function sync(string $tipe, string $kode): JsonResponse
    {
        if (! in_array($tipe, ['wisata', 'kuliner', 'nongkrong'])) {
            return $this->error('Tipe tidak valid.', 422);
        }

        $result = $this->service->syncSentimen($tipe, $kode);
        return $this->success($result, 'Sinkronisasi sentimen berhasil.');
    }

    /**
     * Sync massal semua data — admin only
     * POST /api/v1/admin/sentiment/sync-all
     */
    public function syncAll(): JsonResponse
    {
        $result = $this->service->syncAll();
        return $this->success($result, 'Semua data berhasil disinkronisasi.');
    }

}
