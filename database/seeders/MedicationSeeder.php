<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('medications')->insert([
            [
                'kode_obat' => 'OBT001',
                'nama_obat' => 'Paracetamol 500mg',
                'kategori' => 'Analgetik',
                'satuan' => 'Tablet',
                'stok' => 100,
                'harga' => 5000,
                'tanggal_kedaluwarsa' => '2026-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_obat' => 'OBT002',
                'nama_obat' => 'Amoxicillin 500mg',
                'kategori' => 'Antibiotik',
                'satuan' => 'Kaplet',
                'stok' => 50,
                'harga' => 15000,
                'tanggal_kedaluwarsa' => '2025-10-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_obat' => 'OBT003',
                'nama_obat' => 'Cetirizine 10mg',
                'kategori' => 'Antihistamin',
                'satuan' => 'Tablet',
                'stok' => 80,
                'harga' => 8000,
                'tanggal_kedaluwarsa' => '2026-06-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_obat' => 'OBT004',
                'nama_obat' => 'Antasida Doen',
                'kategori' => 'Antasida',
                'satuan' => 'Tablet',
                'stok' => 120,
                'harga' => 4000,
                'tanggal_kedaluwarsa' => '2026-03-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
