<?php

namespace App\Filament\Resources\Kuliners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KulinersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uid')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kode')
                    ->searchable(),
                TextColumn::make('id_wisata_terdekat')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('wilayah'),
                TextColumn::make('kecamatan')
                    ->searchable(),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jenis_tempat')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kategori_menu_utama')
                    ->searchable(),
                IconColumn::make('makanan_khas_daerah')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nama_makanan_khas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('harga_menu_min')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_menu_max')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jam_buka')
                    ->time()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('jam_tutup')
                    ->time()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('kapasitas_orang')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fasilitas')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('sertifikat_halal')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rating_google')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_ulasan_google')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('gambar')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status'),
                TextColumn::make('sentimen'),
                TextColumn::make('skor_sentimen')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_ulasan_scraped')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_positif')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_negatif')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fts')
                    ->toggleable(isToggledHiddenByDefault: true),
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
