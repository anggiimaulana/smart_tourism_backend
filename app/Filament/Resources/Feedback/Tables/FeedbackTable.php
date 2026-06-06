<?php

namespace App\Filament\Resources\Feedback\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FeedbackTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->default('Guest')
                    ->searchable(),
                TextColumn::make('feature')
                    ->label('Fitur')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'chatbot' => 'info',
                        'planning' => 'warning',
                        'recommendation' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'success',
                        -1 => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Sangat Membantu (+1)',
                        -1 => 'Kurang Membantu (-1)',
                        default => (string) $state,
                    })
                    ->sortable(),
                TextColumn::make('context.page')
                    ->label('Konteks Page')
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
