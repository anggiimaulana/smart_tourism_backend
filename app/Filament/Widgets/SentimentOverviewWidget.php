<?php

namespace App\Filament\Widgets;

use App\Models\Wisata;
use App\Models\Kuliner;
use App\Models\Nongkrong;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SentimentOverviewWidget extends ChartWidget
{
    protected ?string $heading = 'Distribusi Sentimen Global';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $wisata = Wisata::select('sentimen', DB::raw('count(*) as count'))->groupBy('sentimen')->pluck('count', 'sentimen')->toArray();
        $kuliner = Kuliner::select('sentimen', DB::raw('count(*) as count'))->groupBy('sentimen')->pluck('count', 'sentimen')->toArray();
        $nongkrong = Nongkrong::select('sentimen', DB::raw('count(*) as count'))->groupBy('sentimen')->pluck('count', 'sentimen')->toArray();

        $positif = ($wisata['positif'] ?? 0) + ($kuliner['positif'] ?? 0) + ($nongkrong['positif'] ?? 0);
        $negatif = ($wisata['negatif'] ?? 0) + ($kuliner['negatif'] ?? 0) + ($nongkrong['negatif'] ?? 0);
        $netral = ($wisata['netral'] ?? 0) + ($kuliner['netral'] ?? 0) + ($nongkrong['netral'] ?? 0);

        return [
            'datasets' => [
                [
                    'label' => 'Sentimen',
                    'data' => [$positif, $negatif, $netral],
                    'backgroundColor' => ['#10b981', '#ef4444', '#64748b'],
                ],
            ],
            'labels' => ['Positif', 'Negatif', 'Netral'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
