<?php

namespace App\Filament\Resources\LlmConfigs\Pages;

use App\Filament\Resources\LlmConfigs\LlmConfigResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLlmConfigs extends ListRecords
{
    protected static string $resource = LlmConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
