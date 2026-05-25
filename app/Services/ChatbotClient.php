<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Simple HTTP client wrapper to call the FastAPI chatbot endpoint.
 * Use this from controllers or other services after authenticating the user.
 * It forwards `X-User-Id` header (server-to-server) so FastAPI can associate sessions.
 */
class ChatbotClient
{
    protected string $baseUrl;

    public function __construct()
    {
        // configure in config/services.php -> 'chatbot' => ['url' => env('CHATBOT_API')]
        $this->baseUrl = (string) (
            config('services.chatbot.url')
            ?? config('smart_tourism.fastapi.base_url')
            ?? 'http://127.0.0.1:8001'
        );
    }

    /**
     * Ask chatbot on behalf of a user (or anonymous if $userId is null).
     * @param string|null $userId UUID of authenticated user (server-side)
     * @param array $payload Associative array: ['message' => 'text', 'session_token' => '...']
     */
    public function ask(?string $userId, array $payload)
    {
        $headers = [];
        if ($userId) {
            // Always set header server-to-server; do NOT accept this header from client input
            $headers['X-User-Id'] = $userId;
        }

        $response = Http::withHeaders($headers)
            ->timeout(10)
            ->post($this->baseUrl . '/api/v1/chatbot/ask', $payload);

        return $response;
    }
}
