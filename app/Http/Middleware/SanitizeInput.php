<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    private array $except = ['password', 'password_confirmation'];

    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->except($this->except);
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                $value = strip_tags(trim($value));
            }
        });
        $request->merge($input);

        return $next($request);
    }
}
