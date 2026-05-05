<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class SentimentOverviewWidget extends ChartWidget
{
    protected ?string $heading = 'Sentiment Overview Widget';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
