<?php

namespace App\Filament\Resources\Nongkrongs\Pages;

use App\Filament\Resources\Nongkrongs\NongkrongResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNongkrongs extends ListRecords
{
    protected static string $resource = NongkrongResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
