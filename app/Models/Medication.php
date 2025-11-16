<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $table = 'medications';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     * Kolom kode_obat, kategori, dan tanggal_kedaluwarsa harus ada di sini.
     */
    protected $fillable = [
        'kode_obat',
        'nama_obat', 
        'kategori',   
        'stok', 
        'harga', 
        'satuan',
        'tanggal_kedaluwarsa',
        'deskripsi',
        // Jika Anda ingin menyimpan 'description' ke kolom 'satuan', pastikan logika controller mendukungnya.
        // Namun, jika ada kolom 'deskripsi' di DB, tambahkan juga di sini.
    ];
    
    // Konversi tipe data untuk kolom harga
    protected $casts = [
        'harga' => 'decimal:2',
    ];
}