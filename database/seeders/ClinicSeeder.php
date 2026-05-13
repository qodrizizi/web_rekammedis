<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClinicSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clinics')->insert([
            ['nama_poli' => 'Poli Umum', 'deskripsi' => 'Layanan pemeriksaan umum dan pengobatan dasar', 'created_at' => now(), 'updated_at' => now()],
            ['nama_poli' => 'Poli Gigi', 'deskripsi' => 'Layanan kesehatan gigi dan mulut', 'created_at' => now(), 'updated_at' => now()],
            ['nama_poli' => 'Poli KIA', 'deskripsi' => 'Layanan Kesehatan Ibu dan Anak', 'created_at' => now(), 'updated_at' => now()],
            ['nama_poli' => 'Poli Lansia', 'deskripsi' => 'Layanan pemeriksaan khusus untuk lansia', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
