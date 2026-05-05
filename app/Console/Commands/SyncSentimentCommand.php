<?php

namespace App\Console\Commands;

use App\Services\FastApiProxyService;
use Illuminate\Console\Command;

class SyncSentimentCommand extends Command
{
    protected $signature   = 'sentiment:sync {tipe=wisata : wisata|kuliner|nongkrong}';
    protected $description = 'Sinkronisasi hasil sentimen dari FastAPI ke database';

    public function handle(FastApiProxyService $proxy): int
    {
        $tipe = $this->argument('tipe');

        $this->info("Memulai sinkronisasi sentimen untuk tipe: {$tipe}");

        // TODO: ambil semua kode dari DB → loop → panggil proxy->post(sync)
        // Contoh:
        // $kodes = \App\Models\Wisata::pluck('kode');
        // foreach ($kodes as $kode) {
        //     $proxy->post("/api/v1/sentiment/sync/{$tipe}/{$kode}");
        //     $this->line("  ✓ {$kode}");
        // }

        $this->info('Sinkronisasi selesai.');
        return Command::SUCCESS;
    }
}
