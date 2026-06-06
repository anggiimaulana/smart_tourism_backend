<?php

namespace App\Filament\Resources\ChatbotSessions;

use App\Filament\Resources\ChatbotSessions\Pages\CreateChatbotSession;
use App\Filament\Resources\ChatbotSessions\Pages\EditChatbotSession;
use App\Filament\Resources\ChatbotSessions\Pages\ListChatbotSessions;
use App\Filament\Resources\ChatbotSessions\Schemas\ChatbotSessionForm;
use App\Filament\Resources\ChatbotSessions\Tables\ChatbotSessionsTable;
use App\Models\ChatbotSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChatbotSessionResource extends Resource
{
    protected static ?string $model = ChatbotSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ChatbotSessionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChatbotSessionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChatbotSessions::route('/'),
            'create' => CreateChatbotSession::route('/create'),
            'edit' => EditChatbotSession::route('/{record}/edit'),
        ];
    }
}
