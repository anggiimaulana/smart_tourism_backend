<?php

namespace App\Filament\Resources\LlmConfigs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LlmConfigForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('provider')
                    ->label('Provider')
                    ->options([
                        'openai' => 'OpenAI (OpenRouter)',
                        'groq'   => 'Groq',
                        'gemini' => 'Google Gemini',
                    ])
                    ->required(),
                TextInput::make('base_url')
                    ->label('Base URL')
                    ->placeholder('https://openrouter.ai/api/v1')
                    ->helperText('Kosongkan jika pakai default provider'),
                TextInput::make('api_key')
                    ->label('API Key')
                    ->password()
                    ->revealable()
                    ->placeholder('sk-... atau AI...'),
                TextInput::make('model')
                    ->label('Model')
                    ->placeholder('meta-llama/llama-3.3-70b-instruct')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Hanya satu konfigurasi yang bisa aktif'),
            ]);
    }
}
