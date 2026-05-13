<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role_id', 1)->first(); // Admin

        DB::table('activity_logs')->insert([
            [
                'user_id' => $user->id,
                'aksi' => 'Login',
                'deskripsi' => 'User admin melakukan login ke sistem',
                'waktu' => now(),
            ],
            [
                'user_id' => $user->id,
                'aksi' => 'Tambah Data',
                'deskripsi' => 'Menambahkan data obat baru: Paracetamol',
                'waktu' => now(),
            ],
        ]);
    }
}
