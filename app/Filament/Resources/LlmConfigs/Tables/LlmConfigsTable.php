<?php

namespace App\Filament\Resources\LlmConfigs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LlmConfigsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('provider')
                    ->label('Provider')
                    ->searchable(),
                TextColumn::make('model')
                    ->label('Model')
                    ->searchable(),
                TextColumn::make('base_url')
                    ->label('Base URL')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('provider')
                    ->options([
                        'openai' => 'OpenAI',
                        'groq'   => 'Groq',
                        'gemini' => 'Gemini',
                    ]),
                \Filament\Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
