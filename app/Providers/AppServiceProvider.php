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
            $appUrlPath = trim((string) parse_url(config('app.url') ?? '', PHP_URL_PATH), '/');
            $apiPath = trim((string) config('scramble.api_path', 'api/v1'), '/');
            $docsBasePath = trim(implode('/', array_filter([$appUrlPath, $apiPath, 'docs'])), '/');
            $docsJsonPath = trim(implode('/', array_filter([$appUrlPath, $apiPath, 'docs.json'])), '/');

            Scramble::configure()->expose(
                ui: fn(Router $router, $action) => $router->get($docsBasePath, $action)->name('scramble.docs.ui'),
                document: fn(Router $router, $action) => $router->get($docsJsonPath, $action)->name('scramble.docs.document'),
            );
        }
    }
}
