<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseApiController
{
    public function __construct(private readonly AuthService $authService) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());
        return $this->success($result, 'Registrasi berhasil.', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (! $result) {
            return $this->error('Email atau password salah.', 401);
        }

        return $this->success($result, 'Login berhasil.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Logout berhasil.');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        return $this->success([
            'id'         => $user->id,
            'nama'       => $user->nama,            // 'nama' bukan 'name'
            'email'      => $user->email,
            'role'       => $user->role,
            'avatar_url' => $user->avatar_url,      // 'avatar_url' bukan 'avatar'
            'is_active'  => $user->is_active,
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'nama'      => 'sometimes|string|min:2|max:150',
            'avatar_url' => 'sometimes|url|max:500',
        ]);

        $request->user()->update($request->only('nama', 'avatar_url'));
        return $this->success(null, 'Profil berhasil diperbarui.');
    }
}
