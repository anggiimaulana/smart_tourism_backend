<?php

namespace App\Filament\Widgets;

use App\Models\UserHistory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopPlacesChartWidget extends ChartWidget
{
    protected ?string $heading = 'Tempat Paling Sering Diklik (Terfavorit)';
    protected ?string $pollingInterval = '30s';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get top 5 tempat by count from user_history
        $topPlaces = UserHistory::select('tempat_kode', DB::raw('count(*) as total'))
            ->whereNotNull('tempat_kode')
            ->groupBy('tempat_kode')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Klik / Kunjungan',
                    'data' => $topPlaces->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#8b5cf6'
                    ],
                ],
            ],
            'labels' => $topPlaces->pluck('tempat_kode')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
