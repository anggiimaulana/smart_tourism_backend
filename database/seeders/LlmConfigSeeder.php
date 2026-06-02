<?php

namespace Database\Seeders;

use App\Models\LlmConfig;
use Illuminate\Database\Seeder;

class LlmConfigSeeder extends Seeder
{
    public function run(): void
    {
        LlmConfig::create([
            'provider'  => 'openai',
            'base_url'  => 'https://openrouter.ai/api/v1',
            'api_key'   => env('OPENAI_API_KEY', ''),
            'model'     => env('OPENAI_MODEL', 'meta-llama/llama-3.3-70b-instruct'),
            'is_active' => true,
        ]);

        LlmConfig::create([
            'provider'  => 'groq',
            'base_url'  => null,
            'api_key'   => env('GROQ_API_KEY', ''),
            'model'     => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
            'is_active' => false,
        ]);

        LlmConfig::create([
            'provider'  => 'gemini',
            'base_url'  => null,
            'api_key'   => env('GEMINI_API_KEY', ''),
            'model'     => 'gemini-2.0-flash',
            'is_active' => false,
        ]);
    }
}
