<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Admin Menus
            ['name' => 'Data Pasien (Admin)', 'slug' => 'admin_pasien', 'group' => 'Admin'],
            ['name' => 'Data Dokter (Admin)', 'slug' => 'admin_dokter', 'group' => 'Admin'],
            ['name' => 'Data Obat (Admin)', 'slug' => 'admin_obat', 'group' => 'Admin'],
            ['name' => 'Poliklinik (Admin)', 'slug' => 'admin_poliklinik', 'group' => 'Admin'],
            ['name' => 'Pendaftaran (Admin)', 'slug' => 'admin_pendaftaran', 'group' => 'Admin'],
            ['name' => 'Rekam Medis (Admin)', 'slug' => 'admin_rekam_medis', 'group' => 'Admin'],
            ['name' => 'Manajemen Role (Admin)', 'slug' => 'admin_roles', 'group' => 'Admin'],
            ['name' => 'Laporan (Admin)', 'slug' => 'admin_laporan', 'group' => 'Admin'],

            // Dokter Menus
            ['name' => 'Data Pasien (Dokter)', 'slug' => 'dokter_pasien', 'group' => 'Dokter'],
            ['name' => 'Data Jadwal (Dokter)', 'slug' => 'dokter_jadwal', 'group' => 'Dokter'],
            ['name' => 'Rekam Medis (Dokter)', 'slug' => 'dokter_rekam_medis', 'group' => 'Dokter'],
            ['name' => 'Laporan (Dokter)', 'slug' => 'dokter_laporan', 'group' => 'Dokter'],

            // Petugas Menus
            ['name' => 'Pendaftaran (Petugas)', 'slug' => 'petugas_pendaftaran', 'group' => 'Petugas'],
            ['name' => 'Data Pasien (Petugas)', 'slug' => 'petugas_pasien', 'group' => 'Petugas'],
            ['name' => 'Data Obat (Petugas)', 'slug' => 'petugas_obat', 'group' => 'Petugas'],
            ['name' => 'Resep Obat (Petugas)', 'slug' => 'petugas_resep', 'group' => 'Petugas'],

            // Pasien Menus
            ['name' => 'Profil Saya (Pasien)', 'slug' => 'pasien_profil', 'group' => 'Pasien'],
            ['name' => 'Riwayat Medis (Pasien)', 'slug' => 'pasien_rekam_medis', 'group' => 'Pasien'],
            ['name' => 'Jadwal Konsultasi (Pasien)', 'slug' => 'pasien_konsultasi', 'group' => 'Pasien'],
        ];

        foreach ($permissions as $perm) {
            \App\Models\Permission::updateOrCreate(['slug' => $perm['slug']], $perm);
        }

        // Assign default permissions to roles
        $adminRole = \App\Models\Role::where('role_name', 'Admin')->first();
        if ($adminRole) {
            $adminPerms = \App\Models\Permission::where('group', 'Admin')->pluck('id');
            $adminRole->permissions()->sync($adminPerms);
        }

        $dokterRole = \App\Models\Role::where('role_name', 'Dokter')->first();
        if ($dokterRole) {
            $dokterPerms = \App\Models\Permission::where('group', 'Dokter')->pluck('id');
            $dokterRole->permissions()->sync($dokterPerms);
        }

        $petugasRole = \App\Models\Role::where('role_name', 'Petugas')->first();
        if ($petugasRole) {
            $petugasPerms = \App\Models\Permission::where('group', 'Petugas')->pluck('id');
            $petugasRole->permissions()->sync($petugasPerms);
        }

        $pasienRole = \App\Models\Role::where('role_name', 'Pasien')->first();
        if ($pasienRole) {
            $pasienPerms = \App\Models\Permission::where('group', 'Pasien')->pluck('id');
            $pasienRole->permissions()->sync($pasienPerms);
        }
    }
}
