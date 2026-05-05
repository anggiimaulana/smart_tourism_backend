<?php

namespace App\Filament\Resources\Nongkrongs\Pages;

use App\Filament\Resources\Nongkrongs\NongkrongResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNongkrong extends EditRecord
{
    protected static string $resource = NongkrongResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
