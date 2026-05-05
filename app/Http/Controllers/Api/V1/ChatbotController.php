<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Chatbot\AskChatbotRequest;
use App\Services\FastApiProxyService;
use Illuminate\Http\JsonResponse;

class ChatbotController extends BaseApiController
{
    public function __construct(private readonly FastApiProxyService $proxy) {}

    public function ask(AskChatbotRequest $request): JsonResponse
    {
        $result = $this->proxy->post('/api/v1/chatbot/ask', $request->validated());
        return $this->success($result);
    }

    public function history(string $token): JsonResponse
    {
        $result = $this->proxy->get("/api/v1/chatbot/history/{$token}");
        return $this->success($result);
    }
}
