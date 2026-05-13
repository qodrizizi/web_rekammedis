<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $dokterAhmad = User::where('email', 'dokter@puskesmas.local')->first();
        $dokterSiti = User::where('email', 'siti@puskesmas.local')->first();

        DB::table('doctors')->insert([
            [
                'user_id' => $dokterAhmad->id,
                'nip' => '198501012010011001',
                'spesialis' => 'Dokter Umum',
                'jadwal_praktek' => 'Senin - Rabu, 08:00 - 14:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $dokterSiti->id,
                'nip' => '199005122015022001',
                'spesialis' => 'Dokter Gigi',
                'jadwal_praktek' => 'Kamis - Sabtu, 08:00 - 14:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
