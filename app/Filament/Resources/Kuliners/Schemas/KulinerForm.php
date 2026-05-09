<?php

namespace App\Filament\Resources\Kuliners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KulinerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uid')
                    ->required()
                    ->default('uuid_generate_v4()'),
                TextInput::make('kode')
                    ->required()
                    ->placeholder('Contoh: KUL-IDM-001'),
                TextInput::make('id_wisata_terdekat')
                    ->placeholder('Kode Wisata Terdekat (Opsional)'),
                TextInput::make('nama')
                    ->required()
                    ->placeholder('Contoh: Empal Gentong H. Apud'),
                TextInput::make('wilayah')
                    ->required()
                    ->placeholder('Contoh: Cirebon'),
                TextInput::make('kecamatan')
                    ->placeholder('Contoh: Tengah Tani'),
                Textarea::make('alamat_lengkap')
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->numeric()
                    ->placeholder('Contoh: -6.712345'),
                TextInput::make('longitude')
                    ->numeric()
                    ->placeholder('Contoh: 108.512345'),
                TextInput::make('jenis_tempat')
                    ->placeholder('Contoh: Restoran / Rumah Makan / Cafe'),
                TextInput::make('kategori_menu_utama')
                    ->placeholder('Contoh: Olahan Daging / Seafood / Tradisional'),
                Textarea::make('menu_unggulan')
                    ->columnSpanFull(),
                Toggle::make('makanan_khas_daerah'),
                TextInput::make('nama_makanan_khas'),
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
                Toggle::make('sertifikat_halal'),
                TextInput::make('rating_google')
                    ->numeric(),
                TextInput::make('jumlah_ulasan_google')
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
