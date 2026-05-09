<?php

namespace Tests\Feature;

use App\Services\FastApiProxyService;
use Tests\TestCase;

class SentimentTest extends TestCase
{
    public function test_sentiment_summary_returns_aggregated_data(): void
    {
        $mockResponse = [
            'success' => true,
            'message' => 'Ringkasan sentimen',
            'data' => [
                'wilayah' => 'Cirebon',
                'total_tempat' => 120,
                'distribusi' => [
                    'positif' => 80,
                    'netral' => 20,
                    'negatif' => 20
                ]
            ]
        ];

        // Mem-bypass auth/role untuk test endpoint publik
        $this->mock(FastApiProxyService::class, function ($mock) use ($mockResponse) {
            $mock->shouldReceive('get')
                ->once()
                ->withAnyArgs()
                ->andReturn($mockResponse);
        });

        $response = $this->getJson('/api/v1/sentiment/summary/Cirebon');

        $response->assertOk()
            ->assertJsonPath('success', true);
    }
}
