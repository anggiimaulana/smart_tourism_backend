<?php

namespace App\Services;

use App\Exceptions\FastApiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class FastApiProxyService
{
    private string $baseUrl;
    private int $timeout;
    private array $defaultHeaders;

    public function __construct()
    {
        $this->baseUrl        = config('smart_tourism.fastapi.base_url');
        $this->timeout        = config('smart_tourism.fastapi.timeout', 30);
        $this->defaultHeaders = [
            'X-Internal-Key' => config('smart_tourism.fastapi.secret_key'),
            'Accept'         => 'application/json',
        ];
    }

    public function get(string $path, array $query = []): array
    {
        try {
            $response = Http::withHeaders($this->defaultHeaders)
                ->timeout($this->timeout)
                ->get($this->baseUrl . $path, $query);

            $response->throw();
            return $response->json() ?? [];
        } catch (RequestException $e) {
            throw new FastApiException(
                'Gagal menghubungi layanan AI: ' . $e->getMessage(),
                $e->response?->status() ?? 502
            );
        } catch (\Exception $e) {
            throw new FastApiException('Layanan AI tidak merespons.', 503);
        }
    }

    public function post(string $path, array $payload = []): array
    {
        try {
            $response = Http::withHeaders($this->defaultHeaders)
                ->timeout($this->timeout)
                ->post($this->baseUrl . $path, $payload);

            $response->throw();
            return $response->json() ?? [];
        } catch (RequestException $e) {
            throw new FastApiException(
                'Gagal menghubungi layanan AI: ' . $e->getMessage(),
                $e->response?->status() ?? 502
            );
        } catch (\Exception $e) {
            throw new FastApiException('Layanan AI tidak merespons.', 503);
        }
    }
}
