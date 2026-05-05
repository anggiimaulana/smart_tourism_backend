<?php

namespace App\Filament\Resources\Nongkrongs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class NongkrongForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uid')
                    ->required()
                    ->default('uuid_generate_v4()'),
                TextInput::make('kode')
                    ->required(),
                TextInput::make('id_wisata_ref'),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('wilayah')
                    ->required(),
                TextInput::make('kecamatan'),
                Textarea::make('alamat_lengkap')
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('konsep_suasana'),
                TextInput::make('target_pengunjung'),
                TextInput::make('cocok_untuk'),
                Textarea::make('menu_best_seller')
                    ->columnSpanFull(),
                TextInput::make('harga_menu_min')
                    ->numeric()
                    ->default(0),
                TextInput::make('harga_menu_max')
                    ->numeric()
                    ->default(0),
                TimePicker::make('jam_buka'),
                TimePicker::make('jam_tutup'),
                TextInput::make('kapasitas_orang')
                    ->numeric(),
                TextInput::make('fasilitas'),
                TextInput::make('batas_waktu_duduk'),
                TextInput::make('rating_google')
                    ->numeric(),
                TextInput::make('minimal_order')
                    ->numeric()
                    ->default(0),
                Textarea::make('link_google_maps')
                    ->columnSpanFull(),
                Textarea::make('kontak')
                    ->columnSpanFull(),
                TextInput::make('gambar'),
                Textarea::make('sumber_data')
                    ->columnSpanFull(),
                Textarea::make('catatan')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                TextInput::make('sentimen'),
                TextInput::make('skor_sentimen')
                    ->numeric(),
                TextInput::make('total_ulasan_scraped')
                    ->numeric()
                    ->default(0),
                TextInput::make('total_positif')
                    ->numeric()
                    ->default(0),
                TextInput::make('total_negatif')
                    ->numeric()
                    ->default(0),
                TextInput::make('fts'),
            ]);
    }
}
