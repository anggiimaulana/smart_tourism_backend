<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Chatbot\AskChatbotRequest;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;

class ChatbotController extends BaseApiController
{
    public function __construct(private readonly ChatbotService $service) {}

    public function ask(AskChatbotRequest $request): JsonResponse
    {
        $userId = auth('sanctum')->id();
        $result = $this->service->ask($request->validated(), $userId);
        return $this->success($result);
    }

    public function history(string $token): JsonResponse
    {
        $result = $this->service->getHistory($token);
        return $this->success($result);
    }

        public function destroy(string $token): JsonResponse
    {
        $result = $this->service->clearHistory($token);
        return $this->success($result['data'] ?? [], $result['message'] ?? 'Berhasil');
    }
}
