<?php

namespace App\Filament\Resources\ChatbotSessions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChatbotSessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Session ID')
                    ->searchable()
                    ->limit(8),
                TextColumn::make('user.name')
                    ->label('User')
                    ->default('Guest')
                    ->searchable(),
                TextColumn::make('messages')
                    ->label('Total Pesan')
                    ->getStateUsing(fn ($record) => count($record->messages ?? []))
                    ->badge(),
                TextColumn::make('wilayah_terdeteksi')
                    ->label('Wilayah')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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
