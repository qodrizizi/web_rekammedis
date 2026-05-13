<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'role_name' => 'Admin', 'description' => 'Administrator sistem'],
            ['id' => 2, 'role_name' => 'Dokter', 'description' => 'Tenaga medis puskesmas'],
            ['id' => 3, 'role_name' => 'Petugas', 'description' => 'Petugas pendaftaran & laporan'],
            ['id' => 4, 'role_name' => 'Pasien', 'description' => 'Pengguna layanan kesehatan'],
            ['id' => 5, 'role_name' => 'Superadmin', 'description' => 'Akses penuh ke seluruh menu'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['id' => $role['id']], $role);
        }
    }
}
