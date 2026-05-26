<?php

namespace App\Services;

use App\Models\ChatbotSession;
use Illuminate\Support\Str;

class ChatbotService
{
    private const VALID_WILAYAH = ['Indramayu', 'Cirebon', 'Majalengka', 'Kuningan'];

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

        $payload = $this->extractDataPayload($response);

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
            'content' => $payload['answer'],
            'time'    => now()->toIso8601String()
        ];

        $sessionWilayah = $this->normalizeWilayahForDb(
            $payload['wilayah_terdeteksi'] ?? null,
            $data['wilayah'] ?? $session->wilayah_terdeteksi
        );

        $session->fill([
            'user_id'            => $userId,
            'messages'           => $messages,
            'latitude'           => $data['latitude'] ?? $session->latitude,
            'longitude'          => $data['longitude'] ?? $session->longitude,
            'wilayah_terdeteksi' => $sessionWilayah,
        ]);
        $session->save();

        return [
            'session_token'      => $payload['session_token'] ?? $sessionToken,
            'answer'             => $payload['answer'],
            'wilayah_terdeteksi' => $payload['wilayah_terdeteksi'] ?? null,
            'referensi'          => $payload['referensi'] ?? [],
            'messages_count'     => $payload['messages_count'] ?? 0,
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
        $payload = $this->extractDataPayload($response);
        return $payload['messages'] ?? [];
    }

    /**
     * FastAPI BaseResponse adalah sumber kebenaran; data harus ada.
     */
    private function extractDataPayload(array $response): array
    {
        if (!isset($response['data']) || !is_array($response['data'])) {
            throw new \RuntimeException('Format respons FastAPI tidak valid: field data wajib ada.');
        }

        return $response['data'];
    }

    private function normalizeWilayahForDb(mixed $candidate, mixed $fallback): ?string
    {
        if (is_string($candidate) && in_array($candidate, self::VALID_WILAYAH, true)) {
            return $candidate;
        }

        if (is_string($fallback) && in_array($fallback, self::VALID_WILAYAH, true)) {
            return $fallback;
        }

        return null;
    }
}
