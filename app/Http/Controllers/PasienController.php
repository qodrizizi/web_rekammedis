<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient; 

class PasienController extends Controller
{
    /**
     * Menampilkan daftar semua pasien.
     * Route Name: 'admin.pasien.index'
     */
    public function index()
    {
        // Ambil data pasien (contoh)
        $patients = Patient::all();

        // ⚠️ PENTING: Ubah jalur view menjadi 'admin.pasien' 
        // Ini akan memuat: resources/views/admin/pasien.blade.php
        return view('admin.pasien', compact('patients')); 
    }

    // ... metode CRUD lainnya
}