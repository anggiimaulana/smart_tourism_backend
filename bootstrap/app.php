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
        // Semua exception dikembalikan sebagai JSON
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson()) {
                // Handle Authentication Exception (401)
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Silakan login terlebih dahulu untuk mengakses layanan ini.',
                        'data'    => null,
                    ], 401);
                }

                $status = 500;
                if ($e instanceof \App\Exceptions\FastApiException || $e instanceof \App\Exceptions\AiServiceException) {
                    $status = method_exists($e, 'getHttpStatus') ? $e->getHttpStatus() : 502;
                } elseif (method_exists($e, 'getStatusCode')) {
                    $status = $e->getStatusCode();
                }

                $message = app()->environment('production') && !($e instanceof \App\Exceptions\FastApiException || $e instanceof \App\Exceptions\AiServiceException)
                    ? 'Terjadi kesalahan pada server.'
                    : $e->getMessage();

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'data'    => null,
                ], $status);
            }
        });
    })->create();
