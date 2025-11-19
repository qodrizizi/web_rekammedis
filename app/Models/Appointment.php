<?php
// app/Models/Appointment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    
    protected $table = 'appointments';

    protected $fillable = [
        'patient_id', 
        'doctor_id', 
        'clinic_id', 
        'tanggal_kunjungan',
        'jam_kunjungan',
        'status', // enum('menunggu','disetujui','selesai','batal')
        'keluhan',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    /**
     * Relasi ke Medical Record. Digunakan untuk mengecek apakah janji temu sudah diproses dokter.
     */
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class, 'appointment_id');
    }
}