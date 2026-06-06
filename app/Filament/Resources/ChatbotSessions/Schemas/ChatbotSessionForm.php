<?php

namespace App\Filament\Resources\ChatbotSessions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ChatbotSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'id'),
                TextInput::make('messages')
                    ->required()
                    ->default('[]'),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('wilayah_terdeteksi'),
            ]);
    }
}
