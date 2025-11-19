<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    /**
     * Relasi ke MedicalRecord (Rekam Medis)
     * Prescription adalah 'detail' dari satu MedicalRecord
     */
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    /**
     * Relasi ke Medication (Obat)
     * Setiap baris resep merujuk ke satu jenis obat
     */
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}