<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\WisataRepositoryInterface;
use App\Repositories\Contracts\KulinerRepositoryInterface;
use App\Repositories\Contracts\NongkrongRepositoryInterface;
use App\Repositories\WisataRepository;
use App\Repositories\KulinerRepository;
use App\Repositories\NongkrongRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WisataRepositoryInterface::class,   WisataRepository::class);
        $this->app->bind(KulinerRepositoryInterface::class,  KulinerRepository::class);
        $this->app->bind(NongkrongRepositoryInterface::class, NongkrongRepository::class);
    }
}
