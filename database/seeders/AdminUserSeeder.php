<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Biasa
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@puskesmas.local'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Superadmin
        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@puskesmas.local'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('superadmin123'),
                'role_id' => 5, // ID untuk Superadmin
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
