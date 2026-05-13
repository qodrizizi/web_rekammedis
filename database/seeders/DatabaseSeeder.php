<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AdminUserSeeder::class,
            ClinicSeeder::class,
            MedicationSeeder::class,
            PatientSeeder::class,
            DoctorSeeder::class,
            AppointmentSeeder::class,
            MedicalRecordSeeder::class,
            PrescriptionSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}
