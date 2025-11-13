<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    // Nama tabel sesuai dengan skema database
    protected $table = 'patients';

    /**
     * The attributes that are mass assignable.
     * Field-field yang bisa diisi menggunakan mass assignment.
     * Berdasarkan tabel 'patients' di db_webrekamedis.sql.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nik',
        'no_bpjs',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'gol_darah',
        'no_hp',
    ];

    /**
     * The attributes that should be cast.
     * Mengkonversi tipe data tertentu secara otomatis.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // --- RELATIONS ---

    /**
     * Relasi one-to-one ke User.
     * Untuk mendapatkan data dasar user (nama, email).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi one-to-many ke Appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Relasi one-to-many ke MedicalRecords.
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}