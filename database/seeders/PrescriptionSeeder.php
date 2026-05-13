<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MedicalRecord;
use App\Models\Medication;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $record = MedicalRecord::first();
        $medication1 = Medication::where('kode_obat', 'OBT001')->first();
        $medication2 = Medication::where('kode_obat', 'OBT003')->first();

        DB::table('prescriptions')->insert([
            [
                'medical_record_id' => $record->id,
                'medication_id' => $medication1->id,
                'jumlah' => 10,
                'aturan_pakai' => '3 x 1 tablet sesudah makan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'medical_record_id' => $record->id,
                'medication_id' => $medication2->id,
                'jumlah' => 5,
                'aturan_pakai' => '1 x 1 tablet malam hari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
