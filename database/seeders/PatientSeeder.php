<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $pasienBudi = User::where('email', 'pasien@puskesmas.local')->first();
        $pasienAni = User::where('email', 'ani@puskesmas.local')->first();
        $pasienIwan = User::where('email', 'iwan@puskesmas.local')->first();

        DB::table('patients')->insert([
            [
                'user_id' => $pasienBudi->id,
                'nik' => '1234567890123456',
                'no_bpjs' => '000123456789',
                'alamat' => 'Jl. Merdeka No. 10, Medan',
                'tanggal_lahir' => '1990-05-15',
                'jenis_kelamin' => 'L',
                'gol_darah' => 'O',
                'no_hp' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $pasienAni->id,
                'nik' => '1234567890123457',
                'no_bpjs' => '000123456790',
                'alamat' => 'Jl. Bunga No. 5, Deli Serdang',
                'tanggal_lahir' => '1995-08-20',
                'jenis_kelamin' => 'P',
                'gol_darah' => 'A',
                'no_hp' => '081234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $pasienIwan->id,
                'nik' => '1234567890123458',
                'no_bpjs' => null,
                'alamat' => 'Jl. Pahlawan No. 12, Binjai',
                'tanggal_lahir' => '1988-12-10',
                'jenis_kelamin' => 'L',
                'gol_darah' => 'B',
                'no_hp' => '081234567892',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
