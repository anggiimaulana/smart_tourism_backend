<?php

namespace App\Filament\Resources\Kuliners;

use App\Filament\Resources\Kuliners\Pages\CreateKuliner;
use App\Filament\Resources\Kuliners\Pages\EditKuliner;
use App\Filament\Resources\Kuliners\Pages\ListKuliners;
use App\Filament\Resources\Kuliners\Schemas\KulinerForm;
use App\Filament\Resources\Kuliners\Tables\KulinersTable;
use App\Models\Kuliner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KulinerResource extends Resource
{
    protected static ?string $model = Kuliner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return KulinerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KulinersTable::configure($table);
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
            'index' => ListKuliners::route('/'),
            'create' => CreateKuliner::route('/create'),
            'edit' => EditKuliner::route('/{record}/edit'),
        ];
    }
}
