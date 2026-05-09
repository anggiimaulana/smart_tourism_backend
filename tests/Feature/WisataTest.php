<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wisata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WisataTest extends TestCase
{
    public function test_wisata_listing_returns_paginated_data(): void
    {
        $response = $this->getJson('/api/v1/wisata');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page'
                ]
            ]);
    }

    public function test_wisata_show_returns_detail_data(): void
    {
        $wisata = Wisata::factory()->make(['kode' => 'WIS-TEST-001']);
        
        $this->mock(\App\Services\WisataService::class, function ($mock) use ($wisata) {
            $mock->shouldReceive('findByKode')->with('WIS-TEST-001')->andReturn($wisata);
        });
        
        $response = $this->getJson('/api/v1/wisata/WIS-TEST-001');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.kode', 'WIS-TEST-001');
    }

    public function test_admin_can_store_wisata(): void
    {
        $admin = User::factory()->make(['role' => 'admin']);
        
        $payload = [
            'nama' => 'Wisata Test',
            'wilayah' => 'Indramayu',
            'kategori_utama' => 'Alam',
            'deskripsi' => 'Deskripsi test',
            'lokasi_koordinat' => ['lat' => 0, 'lng' => 0]
        ];

        $response = $this->actingAs($admin)
            ->postJson('/api/v1/admin/wisata', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);
    }
}
