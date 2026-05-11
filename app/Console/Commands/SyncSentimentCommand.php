<?php

namespace App\Console\Commands;

use App\Models\Kuliner;
use App\Models\Nongkrong;
use App\Models\Wisata;
use Illuminate\Console\Command;

class SyncSentimentCommand extends Command
{
    protected $signature   = 'sentiment:sync {tipe=wisata : wisata|kuliner|nongkrong}';
    protected $description = 'Sinkronisasi hasil sentimen dari FastAPI ke database';

    public function handle(\App\Services\SentimentService $service): int
    {
        $tipe = $this->argument('tipe');

        $this->info("Memulai sinkronisasi sentimen untuk tipe: {$tipe}");

        $model = match ($tipe) {
            'wisata'    => Wisata::class,
            'kuliner'   => Kuliner::class,
            'nongkrong' => Nongkrong::class,
            default     => null,
        };

        if (! $model) {
            $this->error("Tipe '{$tipe}' tidak valid.");
            return Command::FAILURE;
        }

        $kodes = $model::pluck('kode');
        $bar = $this->output->createProgressBar(count($kodes));
        $bar->start();

        foreach ($kodes as $kode) {
            try {
                // Panggil service untuk sinkronisasi
                $service->syncSentimen($tipe, $kode);
            } catch (\Exception $e) {
                $this->error("\nError sync {$kode}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nSinkronisasi {$tipe} selesai.");
        return Command::SUCCESS;
    }
}
