<?php

namespace App\Filament\Resources\LlmConfigs;

use App\Filament\Resources\LlmConfigs\Pages\CreateLlmConfig;
use App\Filament\Resources\LlmConfigs\Pages\EditLlmConfig;
use App\Filament\Resources\LlmConfigs\Pages\ListLlmConfigs;
use App\Filament\Resources\LlmConfigs\Schemas\LlmConfigForm;
use App\Filament\Resources\LlmConfigs\Tables\LlmConfigsTable;
use App\Models\LlmConfig;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LlmConfigResource extends Resource
{
    protected static ?string $model = LlmConfig::class;

    protected static ?string $navigationLabel = 'LLM Config';

    protected static string | \UnitEnum | null $navigationGroup = 'Pengaturan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return LlmConfigForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LlmConfigsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLlmConfigs::route('/'),
            'create' => CreateLlmConfig::route('/create'),
            'edit'   => EditLlmConfig::route('/{record}/edit'),
        ];
    }
}
