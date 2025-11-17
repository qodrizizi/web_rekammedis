<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    
    // Nonaktifkan default timestamp karena tabel Anda menggunakan kolom 'waktu'
    public $timestamps = false; 

    protected $table = 'activity_logs';
    
    protected $fillable = [
        'user_id', 
        'aksi', 
        'deskripsi', 
        'waktu' // Waktu diisi secara manual atau oleh database default
    ];

    /**
     * Relasi ke Model User (Pengguna yang melakukan aktivitas).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}