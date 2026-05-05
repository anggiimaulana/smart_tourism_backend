<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'nama'          => $data['nama'],       // Kolom 'nama', bukan 'name'
            'email'         => $data['email'],
            'password_hash' => Hash::make($data['password']),  // Kolom 'password_hash'
            'role'          => 'pengunjung',
            'is_active'     => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'       => $this->formatUser($user),
            'token'      => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function login(array $credentials): ?array
    {
        // Select kolom spesifik — tidak pakai select *
        $user = User::where('email', $credentials['email'])
            ->where('is_active', true)
            ->select('id', 'nama', 'email', 'password_hash', 'role', 'avatar_url')
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password_hash)) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'       => $this->formatUser($user),
            'token'      => $token,
            'token_type' => 'Bearer',
        ];
    }

    private function formatUser(User $user): array
    {
        return [
            'id'         => $user->id,
            'nama'       => $user->nama,
            'email'      => $user->email,
            'role'       => $user->role,
            'avatar_url' => $user->avatar_url,
        ];
    }
}
