<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@smarttourism.id'],
            [
                'nama'          => 'Super Admin',
                'password_hash' => Hash::make('admin123456'),
                'role'          => 'admin',
                'is_active'     => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@smarttourism.id'],
            [
                'nama'          => 'User Demo',
                'password_hash' => Hash::make('user123456'),
                'role'          => 'pengunjung',
                'is_active'     => true,
            ]
        );
    }
}
