<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Kolom 'nama' dan 'password_hash' sesuai schema
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@smarttourism.id'],
            [
                'nama'          => 'Super Admin',
                'password_hash' => Hash::make('admin123456'),
                'role'          => 'admin',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        );

        // Akun demo pengunjung
        DB::table('users')->updateOrInsert(
            ['email' => 'user@smarttourism.id'],
            [
                'nama'          => 'User Demo',
                'password_hash' => Hash::make('user123456'),
                'role'          => 'pengunjung',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        );
    }
}
