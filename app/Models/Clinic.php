<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;
    
    // Sesuaikan dengan nama tabel di database
    protected $table = 'clinics';

    // Field yang boleh diisi (mass assignable)
    protected $fillable = [
        'nama_poli', 
        'deskripsi'
    ];

    // Relasi ke appointments (jika diperlukan di masa depan)
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}