<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalPlacesWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Wisata', \App\Models\Wisata::count())
                ->description('Tempat wisata terdaftar')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success'),
            Stat::make('Total Kuliner', \App\Models\Kuliner::count())
                ->description('Restoran & tempat makan')
                ->descriptionIcon('heroicon-m-cake')
                ->color('warning'),
            Stat::make('Total Nongkrong', \App\Models\Nongkrong::count())
                ->description('Cafe & tempat hangout')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('primary'),
        ];
    }
}
