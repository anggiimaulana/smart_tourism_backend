<?php

namespace App\Console\Commands;

use App\Services\WisataService;
use App\Services\KulinerService;
use App\Services\NongkrongService;
use Illuminate\Console\Command;

class WarmCacheCommand extends Command
{
    protected $signature   = 'cache:warm';
    protected $description = 'Pre-cache data populer untuk performa awal';

    public function handle(
        WisataService $wisataService,
        KulinerService $kulinerService,
        NongkrongService $nongkrongService
    ): int {
        $this->info('Memulai warming cache...');

        // TODO: panggil service->list([]) untuk tiap wilayah agar hasil di-cache
        foreach (config('smart_tourism.wilayah') as $wilayah) {
            $wisataService->list(['wilayah' => $wilayah]);
            $kulinerService->list(['wilayah' => $wilayah]);
            $nongkrongService->list(['wilayah' => $wilayah]);
            $this->line("  ✓ Cache untuk wilayah {$wilayah} selesai.");
        }

        $this->info('Cache warming selesai.');
        return Command::SUCCESS;
    }
}
