<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $start    = microtime(true);
        $response = $next($request);
        $duration = round((microtime(true) - $start) * 1000, 2);

        if (app()->environment('production') || $duration > 1000) {
            Log::channel('api')->info('API Request', [
                'method'   => $request->method(),
                'url'      => $request->fullUrl(),
                'user_id'  => $request->user()?->id,
                'status'   => $response->getStatusCode(),
                'duration' => "{$duration}ms",
                'ip'       => $request->ip(),
            ]);
        }

        return $response;
    }
}
