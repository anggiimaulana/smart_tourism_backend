<?php

namespace App\Filament\Resources\Nongkrongs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
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
                    ->required()
                    ->placeholder('Contoh: NNG-IDM-001'),
                TextInput::make('id_wisata_ref')
                    ->placeholder('Kode Wisata Referensi (Opsional)'),
                TextInput::make('nama')
                    ->required()
                    ->placeholder('Contoh: Kopi Janji Jiwa'),
                TextInput::make('wilayah')
                    ->required()
                    ->placeholder('Contoh: Majalengka'),
                TextInput::make('kecamatan')
                    ->placeholder('Contoh: Jatiwangi'),
                Textarea::make('alamat_lengkap')
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->numeric()
                    ->placeholder('Contoh: -6.823456'),
                TextInput::make('longitude')
                    ->numeric()
                    ->placeholder('Contoh: 108.223456'),
                TextInput::make('konsep_suasana')
                    ->placeholder('Contoh: Industrial / Cozy / Outdoor'),
                TextInput::make('target_pengunjung')
                    ->placeholder('Contoh: Mahasiswa / Keluarga'),
                TextInput::make('cocok_untuk')
                    ->placeholder('Contoh: Nugas / WFC / Nongkrong Santai'),
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
                FileUpload::make('gambar')
                    ->multiple()
                    ->image()
                    ->directory('nongkrongs')
                    ->columnSpanFull(),
                Textarea::make('sumber_data')
                    ->columnSpanFull(),
                Textarea::make('catatan')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                        'draft' => 'Draft',
                    ])
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
