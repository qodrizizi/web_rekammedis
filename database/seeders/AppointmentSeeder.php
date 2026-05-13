<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Clinic;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patient1 = Patient::first();
        $patient2 = Patient::skip(1)->first();
        $doctor1 = Doctor::first();
        $clinic1 = Clinic::first();
        $clinic2 = Clinic::skip(1)->first();

        DB::table('appointments')->insert([
            [
                'patient_id' => $patient1->id,
                'doctor_id' => $doctor1->id,
                'clinic_id' => $clinic1->id,
                'tanggal_kunjungan' => now()->format('Y-m-d'),
                'jam_kunjungan' => '09:00:00',
                'status' => 'selesai',
                'keluhan' => 'Demam dan pusing sejak kemarin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'patient_id' => $patient2->id,
                'doctor_id' => $doctor1->id,
                'clinic_id' => $clinic1->id,
                'tanggal_kunjungan' => now()->addDay()->format('Y-m-d'),
                'jam_kunjungan' => '10:30:00',
                'status' => 'menunggu',
                'keluhan' => 'Batuk berdahak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
