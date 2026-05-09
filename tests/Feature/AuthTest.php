<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_user_can_login_with_correct_credentials(): void
    {
        $mockResult = [
            'user' => ['id' => '1', 'nama' => 'Test', 'email' => 'test@example.com'],
            'token' => 'fake_token',
            'token_type' => 'Bearer'
        ];

        $this->mock(\App\Services\AuthService::class, function ($mock) use ($mockResult) {
            $mock->shouldReceive('login')->once()->andReturn($mockResult);
        });

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'admin123'
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'token'
                ]
            ]);
    }

    public function test_login_fails_with_incorrect_password(): void
    {
        $this->mock(\App\Services\AuthService::class, function ($mock) {
            $mock->shouldReceive('login')->once()->andReturn(null);
        });

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrong_password'
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('success', false);
    }

    public function test_me_endpoint_returns_user_data(): void
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
            ->getJson('/api/v1/auth/me');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', $user->email);
    }
}
