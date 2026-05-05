<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class CheckFastApiHealth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek health setiap 60 detik, cache hasilnya
        $isHealthy = Cache::remember('fastapi_health', 60, function () {
            try {
                $response = Http::timeout(3)->get(
                    config('smart_tourism.fastapi.base_url') . '/health'
                );
                return $response->successful();
            } catch (\Exception) {
                return false;
            }
        });

        if (! $isHealthy) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan AI sedang tidak tersedia. Silakan coba beberapa saat lagi.',
                'data'    => null,
            ], 503);
        }

        return $next($request);
    }
}
