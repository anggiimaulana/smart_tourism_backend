<?php

namespace Tests\Unit;

use App\Exceptions\FastApiException;
use App\Services\FastApiProxyService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FastApiProxyServiceTest extends TestCase
{
    public function test_get_request_returns_json_on_success(): void
    {
        Http::fake([
            '*/api/v1/test*' => Http::response(['status' => 'success'], 200)
        ]);

        $service = new FastApiProxyService();
        $response = $service->get('/api/v1/test');

        $this->assertEquals(['status' => 'success'], $response);
    }

    public function test_post_request_returns_json_on_success(): void
    {
        Http::fake([
            '*/api/v1/test*' => Http::response(['status' => 'created'], 201)
        ]);

        $service = new FastApiProxyService();
        $response = $service->post('/api/v1/test', ['data' => 'test']);

        $this->assertEquals(['status' => 'created'], $response);
    }

    public function test_throws_exception_on_server_error(): void
    {
        Http::fake([
            '*/api/v1/test*' => Http::response(['error' => 'internal'], 500)
        ]);

        try {
            $service = new FastApiProxyService();
            $service->get('/api/v1/test');
            $this->fail('Expected FastApiException was not thrown.');
        } catch (FastApiException $e) {
            $this->assertEquals(500, $e->getHttpStatus());
        }
    }

    public function test_throws_exception_on_timeout(): void
    {
        Http::fake(function () {
            throw new \Illuminate\Http\Client\ConnectionException('Connection Timeout');
        });

        try {
            $service = new FastApiProxyService();
            $service->post('/api/v1/test');
            $this->fail('Expected FastApiException was not thrown.');
        } catch (FastApiException $e) {
            $this->assertEquals(503, $e->getHttpStatus());
        }
    }
}
