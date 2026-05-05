<?php

namespace App\Filament\Resources\Nongkrongs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NongkrongsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uid'),
                TextColumn::make('kode')
                    ->searchable(),
                TextColumn::make('id_wisata_ref')
                    ->searchable(),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('wilayah'),
                TextColumn::make('kecamatan')
                    ->searchable(),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('konsep_suasana')
                    ->searchable(),
                TextColumn::make('target_pengunjung')
                    ->searchable(),
                TextColumn::make('cocok_untuk')
                    ->searchable(),
                TextColumn::make('harga_menu_min')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_menu_max')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jam_buka')
                    ->time()
                    ->sortable(),
                TextColumn::make('jam_tutup')
                    ->time()
                    ->sortable(),
                TextColumn::make('kapasitas_orang')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fasilitas'),
                TextColumn::make('batas_waktu_duduk')
                    ->searchable(),
                TextColumn::make('rating_google')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('minimal_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gambar'),
                TextColumn::make('status'),
                TextColumn::make('sentimen'),
                TextColumn::make('skor_sentimen')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_ulasan_scraped')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_positif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_negatif')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fts'),
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
