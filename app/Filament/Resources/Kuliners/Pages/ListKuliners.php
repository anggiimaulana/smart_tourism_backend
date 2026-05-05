<?php

namespace App\Filament\Resources\Kuliners\Pages;

use App\Filament\Resources\Kuliners\KulinerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKuliners extends ListRecords
{
    protected static string $resource = KulinerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
