<?php

namespace App\Services;

use App\Models\ChatbotSession;
use Illuminate\Support\Str;

class ChatbotService
{
    public function __construct(private readonly FastApiProxyService $proxy) {}

    /**
     * Kirim pesan ke chatbot dan simpan ke database
     */
    public function ask(array $data, ?string $userId = null): array
    {
        $sessionToken = $data['session_token'] ?? (string) Str::uuid();

        // Panggil FastAPI — sertakan X-User-Id header hanya jika kita punya user authenticated
        $extraHeaders = [];
        if ($userId) {
            $extraHeaders['X-User-Id'] = $userId;
        }

        $response = $this->proxy->post('/api/v1/chatbot/ask', [
            'message'       => $data['message'],
            'session_token' => $sessionToken,
            'latitude'      => $data['latitude'] ?? null,
            'longitude'     => $data['longitude'] ?? null,
            'wilayah'       => $data['wilayah'] ?? null,
        ], $extraHeaders);

        // Simpan atau update session di DB Laravel
        $session = ChatbotSession::firstOrNew(['session_token' => $sessionToken]);

        $messages = $session->messages ?? [];
        $messages[] = [
            'role'    => 'user',
            'content' => $data['message'],
            'time'    => now()->toIso8601String()
        ];
        $messages[] = [
            'role'    => 'assistant',
            'content' => $response['data']['answer'] ?? $response['answer'] ?? $response['message'] ?? '',
            'time'    => now()->toIso8601String()
        ];

        $session->fill([
            'user_id'            => $userId,
            'messages'           => $messages,
            'latitude'           => $data['latitude'] ?? $session->latitude,
            'longitude'          => $data['longitude'] ?? $session->longitude,
            'wilayah_terdeteksi' => $data['wilayah'] ?? $session->wilayah_terdeteksi,
        ]);
        $session->save();

        return [
            'session_token'      => $sessionToken,
            'answer'             => $response['data']['answer'] ?? $response['answer'] ?? $response['message'] ?? '',
            'wilayah_terdeteksi' => $response['data']['wilayah_terdeteksi'] ?? $response['wilayah_terdeteksi'] ?? null,
            'referensi'          => $response['data']['referensi'] ?? $response['referensi'] ?? [],
            'messages_count'     => $response['data']['messages_count'] ?? $response['messages_count'] ?? 0,
        ];
    }

    /**
     * Ambil riwayat dari DB Laravel (fallback ke FastAPI jika kosong)
     */
    public function getHistory(string $token): array
    {
        $session = ChatbotSession::where('session_token', $token)->first();

        if ($session) {
            return $session->messages;
        }

        // Jika tidak ada di DB, coba ambil dari FastAPI
        $response = $this->proxy->get("/api/v1/chatbot/history/{$token}");
        return $response['data']['messages'] ?? $response['history'] ?? $response['data'] ?? [];
    }
}
