<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'patient_id', // Asumsi ID pasien diambil dari user yang login
        'doctor_id',
        'clinic_id',
        'tanggal_kunjungan',
        'jam_kunjungan',
        'status', // Default 'menunggu'
        'keluhan',
    ];

    // Casts
    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'jam_kunjungan' => 'datetime:H:i:s', // Bisa disesuaikan
    ];
}