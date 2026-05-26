<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required()
                    ->placeholder('Contoh: Budi Santoso'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->placeholder('Contoh: budi@example.com'),
                TextInput::make('password_hash')
                    ->label('Ganti Password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->placeholder('Kosongkan jika tidak ingin mengubah password')
                    ->minLength(8)
                    ->regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/')
                    ->validationMessages([
                        'regex' => 'Password harus mengandung minimal 1 huruf besar, 1 huruf kecil, dan 1 angka.',
                    ]),
                Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'pengunjung' => 'Pengunjung',
                    ])
                    ->required()
                    ->default('pengunjung'),
                FileUpload::make('avatar_url')
                    ->image()
                    ->directory('avatars')
                    ->columnSpanFull()
                    ->label('Avatar'),
                Toggle::make('is_active')
                    ->required()
                    ->default(true)
                    ->hidden(fn (string $context): bool => $context === 'create'),
            ]);
    }
}
