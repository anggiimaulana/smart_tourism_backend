<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        // Middleware global untuk semua API
        $middleware->api(prepend: [
            \App\Http\Middleware\ForceJsonResponse::class,
            \App\Http\Middleware\SanitizeInput::class,
            \App\Http\Middleware\LogApiRequest::class,
        ]);

        // Alias middleware
        $middleware->alias([
            'role'           => \App\Http\Middleware\RoleMiddleware::class,
            'fastapi.health' => \App\Http\Middleware\CheckFastApiHealth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Semua exception dikembalikan sebagai JSON yang mudah dimengerti
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {

                // 401 — Authentication
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Autentikasi diperlukan. Silakan login terlebih dahulu.',
                        'data'    => null,
                    ], 401);
                }

                // 403 — Authorization
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
                    || $e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengakses resource ini.',
                        'data'    => null,
                    ], 403);
                }

                // 404 — Not Found (Model atau Route)
                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $model = class_basename($e->getModel());
                    return response()->json([
                        'success' => false,
                        'message' => "Data {$model} tidak ditemukan.",
                        'data'    => null,
                    ], 404);
                }
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Endpoint tidak ditemukan. Periksa kembali URL yang diminta.',
                        'data'    => null,
                    ], 404);
                }

                // 405 — Method Not Allowed
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Method HTTP tidak diizinkan untuk endpoint ini.',
                        'data'    => null,
                    ], 405);
                }

                // 422 — Validation Error
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data yang dikirim tidak valid. Periksa kembali format request.',
                        'errors'  => $e->errors(),
                        'data'    => null,
                    ], 422);
                }

                // 429 — Too Many Requests
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Terlalu banyak permintaan. Silakan coba beberapa saat lagi.',
                        'data'    => null,
                    ], 429);
                }

                // Custom Exceptions (FastAPI proxy, AI service)
                if ($e instanceof \App\Exceptions\FastApiException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menghubungi layanan AI: ' . $e->getMessage(),
                        'data'    => null,
                    ], $e->getHttpStatus());
                }
                if ($e instanceof \App\Exceptions\AiServiceException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Layanan AI sedang tidak tersedia. Silakan coba beberapa saat lagi.',
                        'data'    => null,
                    ], $e->getHttpStatus());
                }

                // 500 — Fallback untuk semua error lainnya
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                $message = app()->environment('production')
                    ? 'Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.'
                    : $e->getMessage();

                \Log::error('API Error', [
                    'url'       => $request->fullUrl(),
                    'method'    => $request->method(),
                    'exception' => get_class($e),
                    'message'   => $e->getMessage(),
                    'trace'     => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'data'    => null,
                ], $status);
            }
        });
    })->create();
