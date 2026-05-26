<?php

namespace Tests\Feature;

use App\Services\FastApiProxyService;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatbotTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*' => Http::response(['status' => 'ok'], 200),
        ]);
    }

    public static function fastapiResponseProvider(): array
    {
        return [
            'nested data payload' => [
                [
                    'success' => true,
                    'message' => 'OK',
                    'data' => [
                        'session_token' => 'session-123',
                        'answer' => 'Halo! Berikut rekomendasi wisata di Ciayumajakuning.',
                        'wilayah_terdeteksi' => 'Ciayumajakuning',
                        'referensi' => [
                            [
                                'nama' => 'Taman Rekreasi Buyut Banjar',
                                'tipe' => 'wisata',
                                'wilayah' => 'Indramayu',
                                'link_maps' => 'https://maps.example/1',
                            ],
                        ],
                        'messages_count' => 2,
                    ],
                ],
                'session-123',
                'Halo! Berikut rekomendasi wisata di Ciayumajakuning.',
                'Ciayumajakuning',
                2,
                'Taman Rekreasi Buyut Banjar',
            ],
        ];
    }

    #[DataProvider('fastapiResponseProvider')]
    public function test_chatbot_ask_accepts_multiple_fastapi_response_shapes(
        array $mockResponse,
        string $expectedSessionToken,
        string $expectedAnswer,
        string $expectedWilayah,
        int $expectedMessagesCount,
        ?string $expectedReferensiName
    ): void {
        $this->mock(FastApiProxyService::class, function ($mock) use ($mockResponse) {
            $mock->shouldReceive('post')
                ->once()
                ->withAnyArgs()
                ->andReturn($mockResponse);
        });

        $response = $this->postJson('/api/v1/chatbot/ask', [
            'message' => 'Rekomendasikan wisata terkenal di Indramayu',
            'session_token' => null,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.session_token', $expectedSessionToken)
            ->assertJsonPath('data.answer', $expectedAnswer)
            ->assertJsonPath('data.wilayah_terdeteksi', $expectedWilayah)
            ->assertJsonPath('data.messages_count', $expectedMessagesCount);

        $response->assertJsonPath('data.referensi.0.nama', $expectedReferensiName);
    }

    public function test_chatbot_ask_uses_nested_fastapi_data_payload(): void
    {
        $mockResponse = [
            'success' => true,
            'message' => 'OK',
            'data' => [
                'session_token' => 'session-123',
                'answer' => 'Halo! Berikut rekomendasi wisata di Ciayumajakuning.',
                'wilayah_terdeteksi' => 'Ciayumajakuning',
                'referensi' => [
                    [
                        'nama' => 'Taman Rekreasi Buyut Banjar',
                        'tipe' => 'wisata',
                        'wilayah' => 'Indramayu',
                        'link_maps' => 'https://maps.example/1',
                    ],
                ],
                'messages_count' => 2,
            ],
        ];

        $this->mock(FastApiProxyService::class, function ($mock) use ($mockResponse) {
            $mock->shouldReceive('post')
                ->once()
                ->withAnyArgs()
                ->andReturn($mockResponse);
        });

        $response = $this->postJson('/api/v1/chatbot/ask', [
            'message' => 'Rekomendasikan wisata terkenal di Indramayu',
            'session_token' => null,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.session_token', 'session-123')
            ->assertJsonPath('data.answer', 'Halo! Berikut rekomendasi wisata di Ciayumajakuning.')
            ->assertJsonPath('data.wilayah_terdeteksi', 'Ciayumajakuning')
            ->assertJsonPath('data.messages_count', 2)
            ->assertJsonPath('data.referensi.0.nama', 'Taman Rekreasi Buyut Banjar');
    }

    public function test_chatbot_ask_rejects_non_nested_fastapi_payload(): void
    {
        $this->mock(FastApiProxyService::class, function ($mock) {
            $mock->shouldReceive('post')
                ->once()
                ->withAnyArgs()
                ->andReturn([
                    'session_token' => 'session-456',
                    'answer' => 'Halo! Berikut rekomendasi wisata di Cirebon.',
                    'wilayah_terdeteksi' => 'Cirebon',
                    'referensi' => [],
                    'messages_count' => 4,
                ]);
        });

        $response = $this->postJson('/api/v1/chatbot/ask', [
            'message' => 'Rekomendasikan wisata terkenal di Indramayu',
            'session_token' => null,
        ]);

        $response->assertStatus(500)
            ->assertJsonPath('success', false)
            ->assertJsonPath('data', null);
    }
}
