<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Dr. Ahmad',
                'email' => 'dokter@puskesmas.local',
                'password' => Hash::make('dokter123'),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dr. Siti',
                'email' => 'siti@puskesmas.local',
                'password' => Hash::make('dokter123'),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Petugas Lina',
                'email' => 'petugas@puskesmas.local',
                'password' => Hash::make('petugas123'),
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Petugas Budi',
                'email' => 'budi@puskesmas.local',
                'password' => Hash::make('petugas123'),
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pasien Budi',
                'email' => 'pasien@puskesmas.local',
                'password' => Hash::make('pasien123'),
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pasien Ani',
                'email' => 'ani@puskesmas.local',
                'password' => Hash::make('pasien123'),
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pasien Iwan',
                'email' => 'iwan@puskesmas.local',
                'password' => Hash::make('pasien123'),
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
