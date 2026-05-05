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
                TextColumn::make('uid'),
                TextColumn::make('kode')
                    ->searchable(),
                TextColumn::make('id_wisata_terdekat')
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
                TextColumn::make('jenis_tempat'),
                TextColumn::make('kategori_menu_utama')
                    ->searchable(),
                IconColumn::make('makanan_khas_daerah')
                    ->boolean(),
                TextColumn::make('nama_makanan_khas')
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
                IconColumn::make('sertifikat_halal')
                    ->boolean(),
                TextColumn::make('rating_google')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_ulasan_google')
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
