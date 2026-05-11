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

        // Panggil FastAPI
        $response = $this->proxy->post('/api/v1/chatbot/ask', [
            'message'       => $data['message'],
            'session_token' => $sessionToken,
            'latitude'      => $data['latitude'] ?? null,
            'longitude'     => $data['longitude'] ?? null,
            'wilayah'       => $data['wilayah'] ?? null,
        ]);

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
            'content' => $response['response'] ?? $response['message'] ?? '',
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
            'session_token' => $sessionToken,
            'response'      => $response['response'] ?? $response['message'] ?? '',
            'data'          => $response['data'] ?? null
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
        return $response['history'] ?? $response['data'] ?? [];
    }
}
