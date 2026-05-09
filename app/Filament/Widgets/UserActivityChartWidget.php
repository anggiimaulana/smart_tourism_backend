<?php

namespace App\Filament\Widgets;

use App\Models\UserHistory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserActivityChartWidget extends ChartWidget
{
    protected ?string $heading = 'Aktivitas Pengguna (7 Hari Terakhir)';
    protected ?string $pollingInterval = '30s';
    protected static ?int $sort = 3;
    
    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $query = UserHistory::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc');

        if ($activeFilter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($activeFilter === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->subDays(6), Carbon::now()]);
        } elseif ($activeFilter === 'month') {
            $query->whereBetween('created_at', [Carbon::now()->subDays(29), Carbon::now()]);
        } elseif ($activeFilter === 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Aktivitas',
                    'data' => $data->pluck('total')->toArray(),
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d M'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
