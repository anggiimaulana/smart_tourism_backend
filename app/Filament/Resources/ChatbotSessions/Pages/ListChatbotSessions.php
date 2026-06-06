<?php

namespace App\Filament\Resources\ChatbotSessions\Pages;

use App\Filament\Resources\ChatbotSessions\ChatbotSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChatbotSessions extends ListRecords
{
    protected static string $resource = ChatbotSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
