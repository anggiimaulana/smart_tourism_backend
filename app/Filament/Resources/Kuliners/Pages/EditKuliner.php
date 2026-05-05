<?php

namespace App\Filament\Resources\Kuliners\Pages;

use App\Filament\Resources\Kuliners\KulinerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKuliner extends EditRecord
{
    protected static string $resource = KulinerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
