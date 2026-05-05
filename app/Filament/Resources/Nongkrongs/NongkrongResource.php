<?php

namespace App\Filament\Resources\Nongkrongs;

use App\Filament\Resources\Nongkrongs\Pages\CreateNongkrong;
use App\Filament\Resources\Nongkrongs\Pages\EditNongkrong;
use App\Filament\Resources\Nongkrongs\Pages\ListNongkrongs;
use App\Filament\Resources\Nongkrongs\Schemas\NongkrongForm;
use App\Filament\Resources\Nongkrongs\Tables\NongkrongsTable;
use App\Models\Nongkrong;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NongkrongResource extends Resource
{
    protected static ?string $model = Nongkrong::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return NongkrongForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NongkrongsTable::configure($table);
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
            'index' => ListNongkrongs::route('/'),
            'create' => CreateNongkrong::route('/create'),
            'edit' => EditNongkrong::route('/{record}/edit'),
        ];
    }
}
