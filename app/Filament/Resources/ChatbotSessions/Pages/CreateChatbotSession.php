<?php

namespace App\Filament\Resources\ChatbotSessions\Pages;

use App\Filament\Resources\ChatbotSessions\ChatbotSessionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChatbotSession extends CreateRecord
{
    protected static string $resource = ChatbotSessionResource::class;
}
