<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['role_name' => 'Admin', 'description' => 'Administrator sistem'],
            ['role_name' => 'Dokter', 'description' => 'Tenaga medis puskesmas'],
            ['role_name' => 'Petugas', 'description' => 'Petugas pendaftaran & laporan'],
            ['role_name' => 'Pasien', 'description' => 'Pengguna layanan kesehatan'],
        ]);
    }
}
