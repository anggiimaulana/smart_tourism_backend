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
                $status  = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                $message = app()->environment('production')
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
