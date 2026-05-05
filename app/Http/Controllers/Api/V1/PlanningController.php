<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Planning\PlanningRequest;
use App\Models\PlanningWisata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanningController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $paginator = PlanningWisata::query()
            ->where('user_id', $request->user()->id)
            ->latest('created_at')
            ->paginate(config('smart_tourism.pagination.default', 12));

        return $this->paginated($paginator, 'Daftar planning berhasil diambil.');
    }

    public function store(PlanningRequest $request): JsonResponse
    {
        $planning = PlanningWisata::create(array_merge($request->validated(), [
            'user_id' => $request->user()->id,
        ]));

        return $this->success($planning, 'Planning berhasil dibuat.', 201);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $planning = $this->findForCurrentUser($id, $request);

        if (! $planning) {
            return $this->error('Planning tidak ditemukan.', 404);
        }

        return $this->success($planning, 'Detail planning berhasil diambil.');
    }

    public function update(PlanningRequest $request, string $id): JsonResponse
    {
        $planning = $this->findForCurrentUser($id, $request);

        if (! $planning) {
            return $this->error('Planning tidak ditemukan.', 404);
        }

        $planning->update($request->validated());

        return $this->success($planning->fresh(), 'Planning berhasil diperbarui.');
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $planning = $this->findForCurrentUser($id, $request);

        if (! $planning) {
            return $this->error('Planning tidak ditemukan.', 404);
        }

        $planning->delete();

        return $this->success(null, 'Planning berhasil dihapus.');
    }

    private function findForCurrentUser(string $id, Request $request): ?PlanningWisata
    {
        return PlanningWisata::query()
            ->whereKey($id)
            ->where('user_id', $request->user()->id)
            ->first();
    }
}
