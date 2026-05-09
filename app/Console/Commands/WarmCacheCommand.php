<?php

namespace App\Console\Commands;

use App\Services\WisataService;
use App\Services\KulinerService;
use App\Services\NongkrongService;
use Illuminate\Console\Command;

class WarmCacheCommand extends Command
{
    protected $signature   = 'cache:warm';
    protected $description = 'Pre-cache data populer untuk setiap wilayah';

    public function __construct(
        private readonly WisataService $wisataService,
        private readonly KulinerService $kulinerService,
        private readonly NongkrongService $nongkrongService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $wilayahs = config('smart_tourism.wilayah', []);

        $this->info('Memulai pemanasan cache...');

        foreach ($wilayahs as $wilayah) {
            $this->line("Pemanasan data untuk wilayah: {$wilayah}");

            $filters = ['wilayah' => $wilayah, 'per_page' => 12];

            $this->wisataService->list($filters);
            $this->kulinerService->list($filters);
            $this->nongkrongService->list($filters);

            $this->info("  ✓ {$wilayah} done.");
        }

        $this->info('Pemanasan cache selesai.');
        return Command::SUCCESS;
    }
}
