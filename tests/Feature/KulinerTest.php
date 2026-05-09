<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\KulinerService;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class KulinerTest extends TestCase
{
    public function test_kuliner_listing_returns_paginated_data(): void
    {
        $item = new \App\Models\Kuliner([
            'kode' => 'KUL-001',
            'nama' => 'Empal Gentong Mang Darma',
            'wilayah' => 'Cirebon',
            'kecamatan' => 'Kejaksan',
            'jenis_tempat' => 'Restoran',
            'kategori_menu_utama' => 'Makanan Berat',
            'alamat_lengkap' => 'Jl. Slamet Riyadi, Cirebon',
            'latitude' => -6.7053,
            'longitude' => 108.5553,
            'link_google_maps' => 'https://maps.google.com',
            'jam_buka' => '07:00',
            'jam_tutup' => '21:00',
            'harga_menu_min' => 25000,
            'harga_menu_max' => 50000,
            'sertifikat_halal' => true,
            'rating_google' => 4.6,
            'jumlah_ulasan_google' => 3200,
            'gambar' => ['https://example.com/kuliner.jpg'],
            'sentimen' => 'positif',
            'skor_sentimen' => 0.85,
            'status' => 'aktif',
        ]);

        $paginator = new LengthAwarePaginator([$item], 1, 12, 1, [
            'path' => url('/api/v1/kuliner'),
        ]);

        $this->mock(KulinerService::class, function ($mock) use ($paginator) {
            $mock->shouldReceive('list')
                ->once()
                ->andReturn($paginator);
        });

        $response = $this->getJson('/api/v1/kuliner');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.kode', 'KUL-001')
            ->assertJsonPath('data.0.nama', 'Empal Gentong Mang Darma');
    }

    public function test_kuliner_show_returns_detail_data(): void
    {
        $item = new \App\Models\Kuliner([
            'kode' => 'KUL-001',
            'nama' => 'Empal Gentong Mang Darma',
            'wilayah' => 'Cirebon',
            'kecamatan' => 'Kejaksan',
            'jenis_tempat' => 'Restoran',
            'kategori_menu_utama' => 'Makanan Berat',
            'menu_unggulan' => 'Empal Gentong, Sate Kambing',
            'makanan_khas_daerah' => true,
            'nama_makanan_khas' => 'Empal Gentong',
            'alamat_lengkap' => 'Jl. Slamet Riyadi, Cirebon',
            'latitude' => -6.7053,
            'longitude' => 108.5553,
            'link_google_maps' => 'https://maps.google.com',
            'jam_buka' => '07:00',
            'jam_tutup' => '21:00',
            'harga_menu_min' => 25000,
            'harga_menu_max' => 50000,
            'kapasitas_orang' => 100,
            'sertifikat_halal' => true,
            'fasilitas' => ['toilet', 'parkir luas'],
            'rating_google' => 4.6,
            'jumlah_ulasan_google' => 3200,
            'gambar' => ['https://example.com/kuliner.jpg'],
            'sentimen' => 'positif',
            'skor_sentimen' => 0.85,
            'total_positif' => 50,
            'total_negatif' => 2,
            'kontak' => '08123456789',
            'catatan' => 'Sangat ramai saat jam makan siang',
            'status' => 'aktif',
        ]);

        $this->mock(KulinerService::class, function ($mock) use ($item) {
            $mock->shouldReceive('findByKode')
                ->once()
                ->with('KUL-001')
                ->andReturn($item);
        });

        $response = $this->getJson('/api/v1/kuliner/KUL-001');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.kode', 'KUL-001')
            ->assertJsonPath('data.nama_makanan_khas', 'Empal Gentong');
    }

    public function test_admin_can_store_kuliner(): void
    {
        $user = new User([
            'id' => 'admin-1',
            'nama' => 'Admin User',
            'email' => 'admin@smarttourism.id',
            'role' => 'admin',
            'is_active' => true,
        ]);

        Sanctum::actingAs($user);

        $payload = [
            'nama' => 'Nasi Jamblang Ibu Nur',
            'wilayah' => 'Cirebon',
            'jenis_tempat' => 'Warung',
        ];

        $item = new \App\Models\Kuliner(array_merge($payload, ['kode' => 'KUL-002', 'status' => 'draft']));

        $this->mock(KulinerService::class, function ($mock) use ($payload, $item) {
            $mock->shouldReceive('create')
                ->once()
                ->with($payload)
                ->andReturn($item);
        });

        $response = $this->postJson('/api/v1/admin/kuliner', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.kode', 'KUL-002');
    }
}
