<?php

namespace App\Services;

use App\Exceptions\FastApiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class FastApiProxyService
{
    private string $baseUrl;
    private int $timeout;
    private array $defaultHeaders;

    public function __construct()
    {
        $this->baseUrl        = (string) (config('smart_tourism.fastapi.base_url') ?? 'http://127.0.0.1:8001');
        $this->timeout        = (int) (config('smart_tourism.fastapi.timeout') ?? 30);
        $this->defaultHeaders = [
            'X-Internal-Key' => config('smart_tourism.fastapi.secret_key'),
            'Accept'         => 'application/json',
        ];
    }

    public function get(string $path, array $query = [], array $extraHeaders = []): array
    {
        try {
            $headers = array_merge($this->defaultHeaders, $extraHeaders);
            $response = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->get($this->baseUrl . $path, $query);

            $response->throw();
            return $response->json() ?? [];
        } catch (RequestException $e) {
            $this->handleRequestException($e, $path);
        } catch (\Exception $e) {
            throw new FastApiException('Layanan AI sedang tidak tersedia. Silakan coba beberapa saat lagi.', 503);
        }
    }

    public function post(string $path, array $payload = [], array $extraHeaders = []): array
    {
        try {
            $headers = array_merge($this->defaultHeaders, $extraHeaders);
            $response = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->post($this->baseUrl . $path, $payload);

            $response->throw();
            return $response->json() ?? [];
        } catch (RequestException $e) {
            $this->handleRequestException($e, $path);
        } catch (\Exception $e) {
            throw new FastApiException('Layanan AI sedang tidak tersedia. Silakan coba beberapa saat lagi.', 503);
        }
    }

    /**
     * Handle request exception dengan pesan yang mudah dimengerti.
     */
    private function handleRequestException(RequestException $e, string $path): never
    {
        $status = $e->response?->status() ?? 502;
        $body   = $e->response?->json() ?? [];

        // Jika FastAPI return format standar {success, message}, gunakan message-nya
        if (isset($body['message']) && is_string($body['message'])) {
            $message = $body['message'];
        } else {
            // Pesan default berdasarkan status code
            $message = match (true) {
                $status === 422 => 'Data yang dikirim tidak valid. Periksa kembali format request.',
                $status === 404 => 'Endpoint AI tidak ditemukan. Hubungi administrator.',
                $status === 401 => 'Autentikasi ke layanan AI gagal.',
                $status >= 500  => 'Layanan AI mengalami gangguan. Silakan coba beberapa saat lagi.',
                default         => 'Gagal menghubungi layanan AI.',
            };
        }

        Log::warning("FastAPI Error [{$status}] {$path}", [
            'status'  => $status,
            'body'    => $body,
            'message' => $e->getMessage(),
        ]);

        throw new FastApiException($message, $status);
    }
}
