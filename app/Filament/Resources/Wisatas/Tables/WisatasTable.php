<?php

namespace App\Filament\Resources\Wisatas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WisatasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uid'),
                TextColumn::make('kode')
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
                TextColumn::make('kategori_utama'),
                TextColumn::make('sub_kategori')
                    ->searchable(),
                TextColumn::make('jenis_tempat')
                    ->searchable(),
                TextColumn::make('harga_tiket_min')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('harga_tiket_max')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('gratis')
                    ->boolean(),
                TextColumn::make('jam_buka')
                    ->time()
                    ->sortable(),
                TextColumn::make('jam_tutup')
                    ->time()
                    ->sortable(),
                TextColumn::make('hari_libur_operasional')
                    ->searchable(),
                TextColumn::make('estimasi_durasi_jam')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fasilitas'),
                TextColumn::make('aksesibilitas')
                    ->searchable(),
                TextColumn::make('moda_transportasi')
                    ->searchable(),
                TextColumn::make('rating_google')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_ulasan_google')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('gambar'),
                TextColumn::make('diinput_oleh')
                    ->searchable(),
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
