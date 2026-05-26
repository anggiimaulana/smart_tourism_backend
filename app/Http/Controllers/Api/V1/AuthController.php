<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\UserPreferences;
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
        $rules = [
            'nama'       => 'sometimes|string|min:2|max:150',
            'avatar_url' => 'sometimes|url|max:500',
            'avatar'     => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        $validated = $request->validate($rules);

        $data = [];

        if ($request->filled('nama')) {
            $data['nama'] = $request->nama;
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_url'] = url("storage/{$path}");
        } elseif ($request->filled('avatar_url')) {
            $data['avatar_url'] = $request->avatar_url;
        }

        if (! empty($data)) {
            $request->user()->update($data);
        }

        return $this->success([
            'nama'       => $request->user()->fresh()->nama,
            'avatar_url' => $request->user()->fresh()->avatar_url,
        ], 'Profil berhasil diperbarui.');
    }

    public function getPreferences(Request $request): JsonResponse
    {
        $preferences = $request->user()->preferences;

        return $this->success([
            'kategori_favorit' => $preferences?->kategori_favorit ?? [],
            'wilayah_favorit'  => $preferences?->wilayah_favorit ?? [],
            'budget_min'       => $preferences?->budget_min,
            'budget_max'       => $preferences?->budget_max,
            'tipe_wisata'      => $preferences?->tipe_wisata ?? [],
        ]);
    }

    public function updatePreferences(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori_favorit'   => 'sometimes|array',
            'kategori_favorit.*' => 'string|max:100',
            'wilayah_favorit'    => 'sometimes',
            'budget_min'         => 'sometimes|nullable|integer|min:0',
            'budget_max'         => 'sometimes|nullable|integer|min:0',
            'tipe_wisata'        => 'sometimes|array',
            'tipe_wisata.*'      => 'string|max:100',
        ]);

        $wilayahFavorit = $validated['wilayah_favorit'] ?? null;
        if (is_string($wilayahFavorit)) {
            $wilayahFavorit = array_values(array_filter(array_map('trim', explode(',', $wilayahFavorit))));
        }

        $preferences = UserPreferences::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'kategori_favorit' => $validated['kategori_favorit'] ?? [],
                'wilayah_favorit'  => $wilayahFavorit ?? [],
                'budget_min'       => $validated['budget_min'] ?? null,
                'budget_max'       => $validated['budget_max'] ?? null,
                'tipe_wisata'      => $validated['tipe_wisata'] ?? [],
            ]
        );

        return $this->success([
            'kategori_favorit' => $preferences->kategori_favorit ?? [],
            'wilayah_favorit'  => $preferences->wilayah_favorit ?? [],
            'budget_min'       => $preferences->budget_min,
            'budget_max'       => $preferences->budget_max,
            'tipe_wisata'      => $preferences->tipe_wisata ?? [],
        ], 'Preferensi berhasil diperbarui.');
    }
}
