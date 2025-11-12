<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@puskesmas.local',
            'password' => Hash::make('admin123'), // password default
            'role_id' => 1, // Role: Admin
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
