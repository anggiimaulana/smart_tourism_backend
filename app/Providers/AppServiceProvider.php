<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Override Sanctum agar tokenable_id bisa UUID (string)
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        // Override default Scramble routes: /api/v1/docs dan /api/v1/docs.json
        if (class_exists(Scramble::class)) {
            $apiPath = trim((string) config('scramble.api_path', 'api/v1'), '/');

            Scramble::configure()->expose(
                ui: fn(Router $router, $action) => $router->get("{$apiPath}/docs", $action)->name('scramble.docs.ui'),
                document: fn(Router $router, $action) => $router->get("{$apiPath}/docs.json", $action)->name('scramble.docs.document'),
            );
        }
    }
}
