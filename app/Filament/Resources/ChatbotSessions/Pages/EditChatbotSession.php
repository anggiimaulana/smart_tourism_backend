<?php

namespace App\Filament\Resources\ChatbotSessions\Pages;

use App\Filament\Resources\ChatbotSessions\ChatbotSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChatbotSession extends EditRecord
{
    protected static string $resource = ChatbotSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
