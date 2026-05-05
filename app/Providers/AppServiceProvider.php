<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Override Sanctum agar tokenable_id bisa UUID (string)
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
