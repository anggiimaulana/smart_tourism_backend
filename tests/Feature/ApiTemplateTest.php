<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AuthService;
use App\Services\WisataService;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTemplateTest extends TestCase
{
    public function test_login_route_returns_token_payload(): void
    {
        $this->mock(AuthService::class, function ($mock) {
            $mock->shouldReceive('login')
                ->once()
                ->with([
                    'email' => 'user@smarttourism.id',
                    'password' => 'password123',
                ])
                ->andReturn([
                    'user' => [
                        'id' => 'user-1',
                        'nama' => 'User Demo',
                        'email' => 'user@smarttourism.id',
                        'role' => 'pengunjung',
                        'avatar_url' => null,
                    ],
                    'token' => 'token-123',
                    'token_type' => 'Bearer',
                ]);
        });

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'user@smarttourism.id',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.token', 'token-123');
    }

    public function test_public_wisata_listing_returns_paginated_data(): void
    {
        $item = (object) [
            'kode' => 'WIS-001',
            'nama' => 'Pantai Kejawanan',
            'wilayah' => 'Cirebon',
            'kecamatan' => 'Kejawanan',
            'kategori_utama' => 'Alam',
            'sub_kategori' => 'Pantai',
            'jenis_tempat' => 'Wisata Alam',
            'deskripsi' => 'Pantai publik di Cirebon.',
            'alamat_lengkap' => 'Cirebon, Jawa Barat',
            'latitude' => -6.7301,
            'longitude' => 108.5594,
            'link_google_maps' => 'https://maps.google.com',
            'jam_buka' => '08:00',
            'jam_tutup' => '17:00',
            'hari_libur_operasional' => null,
            'estimasi_durasi_jam' => 2,
            'gratis' => true,
            'harga_tiket_min' => 0,
            'harga_tiket_max' => 10000,
            'rating_google' => 4.5,
            'jumlah_ulasan_google' => 1200,
            'fasilitas' => ['toilet', 'parkir'],
            'gambar' => ['https://example.com/wisata.jpg'],
            'sentimen' => 'positif',
            'skor_sentimen' => 0.82,
            'total_ulasan_scraped' => 50,
            'total_positif' => 40,
            'total_negatif' => 5,
            'kontak' => '08123456789',
            'link_instagram' => null,
            'link_website' => null,
            'status' => 'aktif',
        ];

        $paginator = new LengthAwarePaginator([$item], 1, 12, 1, [
            'path' => url('/api/v1/wisata'),
        ]);

        $this->mock(WisataService::class, function ($mock) use ($paginator) {
            $mock->shouldReceive('list')
                ->once()
                ->andReturn($paginator);
        });

        $response = $this->getJson('/api/v1/wisata');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.kode', 'WIS-001')
            ->assertJsonPath('data.0.nama', 'Pantai Kejawanan');
    }

    public function test_admin_route_rejects_non_admin_user(): void
    {
        $user = new User([
            'id' => 'user-2',
            'nama' => 'User Demo',
            'email' => 'user@smarttourism.id',
            'role' => 'pengunjung',
            'avatar_url' => null,
            'is_active' => true,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/admin/wisata', []);

        $response->assertStatus(403)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Anda tidak memiliki akses ke resource ini.');
    }
}
