<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    
    protected $table = 'doctors';

    protected $fillable = [
        'user_id',
        'clinic_id', // <<< INI YANG BARU DITAMBAHKAN 
        'nip', 
        'spesialis', 
        'jadwal_praktek'
    ];

    // Relasi ke tabel Users untuk mendapatkan nama dokter
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function medicalRecords()
    {
        return $this->hasMany(\App\Models\MedicalRecord::class, 'doctor_id');
    }
    // Relasi ke appointments (jika diperlukan di masa depan)
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function clinic() // <<< INI YANG HARUS DITAMBAHKAN
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }
}