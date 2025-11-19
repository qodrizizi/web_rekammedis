<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DokterController extends Controller
{
    /**
     * READ: Menampilkan daftar semua Dokter (Index).
     */
    public function index()
    {
        // Ambil data dokter dengan relasi user dan pagination
        $doctors = Doctor::with('user')
                            ->latest()
                            ->paginate(10); 

        // Mengembalikan view dokter.blade.php
        return view('admin.dokter', compact('doctors'));
    }

    /**
     * CREATE: Menyimpan data Dokter baru ke database (Store).
     */
    public function store(Request $request)
    {
        $request->validate([
            // Validasi User
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            
            // Validasi Dokter
            'nip' => 'required|string|unique:doctors|max:30',
            'spesialis' => 'required|string|max:100',
            'jadwal_praktek' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Ambil Role ID untuk 'Dokter'
            $doctorRole = Role::where('role_name', 'Dokter')->firstOrFail();

            // 2. Buat entri baru di tabel users
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $doctorRole->id, // Set role_id Dokter (contoh: 2)
            ]);

            // 3. Buat entri baru di tabel doctors
            Doctor::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'spesialis' => $request->spesialis,
                'jadwal_praktek' => $request->jadwal_praktek,
            ]);
        });
        
        return redirect()->route('admin.dokter')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    /**
     * UPDATE: Memperbarui data Dokter di database (Update).
     */
    public function update(Request $request, Doctor $dokter) // Menggunakan $dokter untuk Route Model Binding
    {
        $request->validate([
            // Validasi User (ignore dirinya sendiri)
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($dokter->user_id)],
            'password' => 'nullable|string|min:8|confirmed',

            // Validasi Dokter (ignore dirinya sendiri)
            'nip' => ['required', 'string', 'max:30', Rule::unique('doctors')->ignore($dokter->id)],
            'spesialis' => 'required|string|max:100',
            'jadwal_praktek' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $dokter) {
            
            // 1. Update data User
            $user = $dokter->user;
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            // 2. Update data Doctor
            $dokter->update([
                'nip' => $request->nip,
                'spesialis' => $request->spesialis,
                'jadwal_praktek' => $request->jadwal_praktek,
            ]);
        });

        return redirect()->route('admin.dokter')->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus data Dokter dari database (Destroy).
     */
    public function destroy(Doctor $dokter)
    {
        DB::transaction(function () use ($dokter) {
            $userId = $dokter->user_id;

            // Hapus data Doctor
            $dokter->delete();

            // Hapus data User yang berelasi
            User::destroy($userId);
        });

        return redirect()->route('admin.dokter')->with('success', 'Data dokter berhasil dihapus.');
    }
    public function showJadwal()
    {
        $user = auth()->user();
        
        // Ambil data Dokter yang login (asumsi relasi sudah benar)
        $doctor = Doctor::where('user_id', $user->id)->first();

        // Handle jika data doctor tidak ditemukan
        if (!$doctor) {
            return view('dokter.jadwal')->with('error', 'Data Dokter Anda tidak ditemukan. Hubungi Administrator.');
        }

        // Asumsi 1: Dokter punya relasi ke Clinic/Poliklinik
        // Asumsi 2: Detail jadwal disimpan dalam kolom 'jadwal_praktek' pada tabel 'doctors'
        $clinic = $doctor->clinic; // Jika ada relasi ke tabel 'clinics'
        $jadwalPraktek = $doctor->jadwal_praktek ?? 'Jadwal belum diatur oleh Administrator.'; 

        return view('dokter.jadwal', compact('doctor', 'clinic', 'jadwalPraktek'));
    }
}