<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Appointment;

class MedicalRecordSeeder extends Seeder
{
    public function run(): void
    {
        $patient = Patient::first();
        $doctor = Doctor::first();
        $clinic = Clinic::first();
        $appointment = Appointment::where('status', 'selesai')->first();

        DB::table('medical_records')->insert([
            [
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'clinic_id' => $clinic->id,
                'appointment_id' => $appointment ? $appointment->id : null,
                'tanggal_periksa' => now()->format('Y-m-d'),
                'keluhan' => 'Demam dan pusing sejak kemarin',
                'diagnosa' => 'Common Cold (Flu Ringan)',
                'tindakan' => 'Pemberian obat penurun panas dan vitamin',
                'catatan_dokter' => 'Istirahat cukup dan minum air putih yang banyak',
                'status_pengambilan_obat' => 'menunggu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
