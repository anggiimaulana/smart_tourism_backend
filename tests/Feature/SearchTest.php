<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SearchTest extends TestCase
{
    public function test_global_search_returns_valid_format(): void
    {
        $mockResults = [
            (object) [
                'kode' => 'WIS-001',
                'nama' => 'Pantai Kejawanan',
                'tipe' => 'wisata',
                'wilayah' => 'Cirebon',
                'kecamatan' => 'Kejawanan',
                'alamat_lengkap' => 'Cirebon',
                'gambar' => '{"https://example.com/a.jpg"}',
                'harga_min' => 0,
                'harga_max' => 10000,
                'jam_buka' => '08:00',
                'jam_tutup' => '17:00',
                'rating_google' => 4.5,
                'sentimen' => 'positif',
                'skor_sentimen' => 0.82,
                'link_google_maps' => 'https://maps.google.com',
                'rank' => 0.99,
            ],
            (object) [
                'kode' => 'KUL-001',
                'nama' => 'Warung Pantai',
                'tipe' => 'kuliner',
                'wilayah' => 'Cirebon',
                'kecamatan' => 'Kejawanan',
                'alamat_lengkap' => 'Cirebon',
                'gambar' => '{"https://example.com/b.jpg"}',
                'harga_min' => 15000,
                'harga_max' => 30000,
                'jam_buka' => '09:00',
                'jam_tutup' => '20:00',
                'rating_google' => 4.2,
                'sentimen' => 'positif',
                'skor_sentimen' => 0.70,
                'link_google_maps' => 'https://maps.google.com',
                'rank' => 0.80,
            ]
        ];

        DB::shouldReceive('select')
            ->once()
            ->andReturn($mockResults);

        $response = $this->getJson('/api/v1/search?q=pantai');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.query', 'pantai')
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('data.0.nama', 'Pantai Kejawanan')
            ->assertJsonPath('data.1.nama', 'Warung Pantai');
    }
}
