<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

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
                Textarea::make('password_hash')
                    ->required()
                    ->columnSpanFull()
                    ->placeholder('Masukkan hash password (bcrypt)'),
                TextInput::make('role')
                    ->required()
                    ->default('pengunjung')
                    ->placeholder('Contoh: admin / pengunjung'),
                Textarea::make('avatar_url')
                    ->columnSpanFull()
                    ->placeholder('Contoh: https://example.com/avatar.jpg'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
