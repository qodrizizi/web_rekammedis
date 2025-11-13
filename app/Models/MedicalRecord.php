<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    // Nama tabel sesuai dengan skema database
    protected $table = 'medical_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'clinic_id',
        'appointment_id',
        'tanggal_periksa',
        'keluhan',
        'diagnosa',
        'tindakan',
        'catatan_dokter',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_periksa' => 'date',
    ];

    // --- RELATIONS ---

    /**
     * Relasi many-to-one ke Patient.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relasi many-to-one ke Doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Relasi many-to-one ke Clinic.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    
    /**
     * Relasi many-to-one ke Appointment (opsional, karena appointment_id boleh NULL).
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    
    /**
     * Relasi one-to-many ke Prescriptions (Resep Obat).
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}