<?php

namespace Tests\Feature;

use App\Services\FastApiProxyService;
use Tests\TestCase;

class RecommendationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Http::fake([
            '*/health' => \Illuminate\Support\Facades\Http::response(['status' => 'ok'], 200),
        ]);
    }

    public function test_recommendation_index_proxies_to_fastapi_successfully(): void
    {
        $mockResponse = [
            'status' => 'success',
            'data' => [
                ['kode' => 'WIS-001', 'nama' => 'Pantai Kejawanan', 'skor' => 0.95],
                ['kode' => 'WIS-002', 'nama' => 'Keraton Kasepuhan', 'skor' => 0.90]
            ]
        ];

        $this->mock(FastApiProxyService::class, function ($mock) use ($mockResponse) {
            $mock->shouldReceive('post')
                ->once()
                ->withAnyArgs()
                ->andReturn($mockResponse);
        });

        $response = $this->postJson('/api/v1/recommendation', [
            'mode' => 'explore',
            'wilayah' => 'Cirebon',
            'tipe' => 'wisata',
            'limit' => 5
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.data.0.kode', 'WIS-001');
    }

    public function test_recommendation_planning_proxies_successfully(): void
    {
        $mockResponse = [
            'status' => 'success',
            'data' => [
                'hari_1' => [
                    ['kode' => 'WIS-001', 'nama' => 'Pantai Kejawanan', 'waktu' => '09:00']
                ]
            ]
        ];

        $this->mock(FastApiProxyService::class, function ($mock) use ($mockResponse) {
            $mock->shouldReceive('post')
                ->once()
                ->withAnyArgs()
                ->andReturn($mockResponse);
        });

        $response = $this->postJson('/api/v1/recommendation/planning', [
            'wilayah' => ['Cirebon'],
            'jumlah_hari' => 1,
            'budget_maksimal' => 500000,
            'kategori_preferensi' => ['Alam']
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.data.hari_1.0.kode', 'WIS-001');
    }
}
