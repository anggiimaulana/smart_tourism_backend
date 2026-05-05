<?php

namespace App\Filament\Resources\Wisatas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WisataForm
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
                TextInput::make('kategori_utama'),
                TextInput::make('sub_kategori'),
                TextInput::make('jenis_tempat'),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
                TextInput::make('harga_tiket_min')
                    ->numeric()
                    ->default(0),
                TextInput::make('harga_tiket_max')
                    ->numeric()
                    ->default(0),
                Toggle::make('gratis'),
                TimePicker::make('jam_buka'),
                TimePicker::make('jam_tutup'),
                TextInput::make('hari_libur_operasional'),
                TextInput::make('estimasi_durasi_jam')
                    ->numeric(),
                TextInput::make('fasilitas'),
                TextInput::make('aksesibilitas'),
                TextInput::make('moda_transportasi'),
                TextInput::make('rating_google')
                    ->numeric(),
                TextInput::make('jumlah_ulasan_google')
                    ->numeric()
                    ->default(0),
                Textarea::make('link_google_maps')
                    ->columnSpanFull(),
                Textarea::make('link_instagram')
                    ->columnSpanFull(),
                Textarea::make('link_website')
                    ->columnSpanFull(),
                Textarea::make('kontak')
                    ->columnSpanFull(),
                TextInput::make('gambar'),
                Textarea::make('sumber_data')
                    ->columnSpanFull(),
                TextInput::make('diinput_oleh'),
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
