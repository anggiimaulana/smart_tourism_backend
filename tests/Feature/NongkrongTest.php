<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\NongkrongService;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NongkrongTest extends TestCase
{
    public function test_nongkrong_listing_returns_paginated_data(): void
    {
        $item = new \App\Models\Nongkrong([
            'kode' => 'NGR-001',
            'nama' => 'Cafe Klasik',
            'wilayah' => 'Cirebon',
            'kecamatan' => 'Kesambi',
            'konsep_suasana' => 'Vintage, Tenang',
            'alamat_lengkap' => 'Jl. Dr. Cipto, Cirebon',
            'latitude' => -6.7153,
            'longitude' => 108.5553,
            'link_google_maps' => 'https://maps.google.com',
            'jam_buka' => '10:00',
            'jam_tutup' => '23:00',
            'harga_menu_min' => 15000,
            'harga_menu_max' => 60000,
            'rating_google' => 4.7,
            'jumlah_ulasan_google' => 1500,
            'gambar' => ['https://example.com/nongkrong.jpg'],
            'sentimen' => 'positif',
            'skor_sentimen' => 0.88,
            'status' => 'aktif',
        ]);

        $paginator = new LengthAwarePaginator([$item], 1, 12, 1, [
            'path' => url('/api/v1/nongkrong'),
        ]);

        $this->mock(NongkrongService::class, function ($mock) use ($paginator) {
            $mock->shouldReceive('list')
                ->once()
                ->andReturn($paginator);
        });

        $response = $this->getJson('/api/v1/nongkrong');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.kode', 'NGR-001')
            ->assertJsonPath('data.0.nama', 'Cafe Klasik');
    }

    public function test_nongkrong_show_returns_detail_data(): void
    {
        $item = new \App\Models\Nongkrong([
            'kode' => 'NGR-001',
            'nama' => 'Cafe Klasik',
            'wilayah' => 'Cirebon',
            'kecamatan' => 'Kesambi',
            'konsep_suasana' => 'Vintage, Tenang',
            'target_pengunjung' => 'Mahasiswa, Pekerja',
            'cocok_untuk' => 'Nugas, Meeting santai',
            'menu_best_seller' => 'Kopi Gula Aren, Waffle',
            'alamat_lengkap' => 'Jl. Dr. Cipto, Cirebon',
            'latitude' => -6.7153,
            'longitude' => 108.5553,
            'link_google_maps' => 'https://maps.google.com',
            'jam_buka' => '10:00',
            'jam_tutup' => '23:00',
            'harga_menu_min' => 15000,
            'harga_menu_max' => 60000,
            'minimal_order' => 20000,
            'kapasitas_orang' => 50,
            'batas_waktu_duduk' => '2 Jam saat ramai',
            'fasilitas' => ['wifi cepat', 'colokan banyak', 'AC'],
            'rating_google' => 4.7,
            'jumlah_ulasan_google' => 1500,
            'gambar' => ['https://example.com/nongkrong.jpg'],
            'sentimen' => 'positif',
            'skor_sentimen' => 0.88,
            'total_positif' => 45,
            'total_negatif' => 1,
            'kontak' => '08123456789',
            'catatan' => 'Sering penuh saat sore hari',
            'status' => 'aktif',
        ]);

        $this->mock(NongkrongService::class, function ($mock) use ($item) {
            $mock->shouldReceive('findByKode')
                ->once()
                ->with('NGR-001')
                ->andReturn($item);
        });

        $response = $this->getJson('/api/v1/nongkrong/NGR-001');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.kode', 'NGR-001')
            ->assertJsonPath('data.fasilitas', ['wifi cepat', 'colokan banyak', 'AC']);
    }

    public function test_admin_can_store_nongkrong(): void
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
            'nama' => 'Kedai Senja',
            'wilayah' => 'Indramayu',
            'konsep_suasana' => 'Outdoor',
        ];

        $item = new \App\Models\Nongkrong(array_merge($payload, ['kode' => 'NGR-002', 'status' => 'draft']));

        $this->mock(NongkrongService::class, function ($mock) use ($payload, $item) {
            $mock->shouldReceive('create')
                ->once()
                ->with($payload)
                ->andReturn($item);
        });

        $response = $this->postJson('/api/v1/admin/nongkrong', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.kode', 'NGR-002');
    }
}
