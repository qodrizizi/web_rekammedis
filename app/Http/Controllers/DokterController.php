<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Models\Role;
use App\Models\Clinic; // <<< DITAMBAHKAN
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
        // Ambil data Poli untuk dropdown filter dan modal
        $clinics = Clinic::all(); 

        // Ambil data dokter dengan relasi user, clinic, dan pagination
        $doctors = Doctor::with(['user', 'clinic']) // <<< DITAMBAHKAN RELASI CLINIC
                            ->latest()
                            ->paginate(10); 

        // Mengembalikan view dokter.blade.php
        return view('admin.dokter', compact('doctors', 'clinics')); // <<< KIRIM DATA CLINICS
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
            'clinic_id' => 'required|exists:clinics,id', // <<< GANTI DARI SPESIALIS
            'schedule_data' => 'required|json', // <<< JADWAL JSON DARI JS
        ]);

        DB::transaction(function () use ($request) {
            // Ambil data Poli untuk diisi ke kolom 'spesialis' (untuk tampilan)
            $clinic = Clinic::findOrFail($request->clinic_id);
            $doctorRole = Role::where('role_name', 'Dokter')->firstOrFail();

            // 1. Buat entri baru di tabel users
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $doctorRole->id, 
            ]);

            // 2. Buat entri baru di tabel doctors
            Doctor::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'spesialis' => $clinic->nama_poli, // <<< ISI DENGAN NAMA POLI
                'clinic_id' => $request->clinic_id, // <<< ISI DENGAN ID POLI
                'jadwal_praktek' => $request->schedule_data, // <<< SIMPAN JSON JADWAL
            ]);
        });
        
        return redirect()->route('admin.dokter')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    /**
     * UPDATE: Memperbarui data Dokter di database (Update).
     */
    public function update(Request $request, Doctor $dokter) 
    {
        $request->validate([
            // Validasi User (ignore dirinya sendiri)
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($dokter->user_id)],
            'password' => 'nullable|string|min:8|confirmed',

            // Validasi Dokter (ignore dirinya sendiri)
            'nip' => ['required', 'string', 'max:30', Rule::unique('doctors')->ignore($dokter->id)],
            'clinic_id' => 'required|exists:clinics,id', // <<< GANTI DARI SPESIALIS
            'schedule_data' => 'required|json', // <<< JADWAL JSON DARI JS
        ]);

        DB::transaction(function () use ($request, $dokter) {
            
            // Ambil data Poli yang baru
            $clinic = Clinic::findOrFail($request->clinic_id);

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
                'spesialis' => $clinic->nama_poli, // <<< UPDATE NAMA POLI
                'clinic_id' => $request->clinic_id, // <<< UPDATE ID POLI
                'jadwal_praktek' => $request->schedule_data, // <<< UPDATE JSON JADWAL
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
            $dokter->delete();
            User::destroy($userId);
        });

        return redirect()->route('admin.dokter')->with('success', 'Data dokter berhasil dihapus.');
    }
    
    // Method showJadwal tetap dipertahankan
    public function showJadwal()
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            return view('dokter.jadwal')->with('error', 'Data Dokter Anda tidak ditemukan. Hubungi Administrator.');
        }

        $clinic = $doctor->clinic; 
        $jadwalPraktek = $doctor->jadwal_praktek ?? 'Jadwal belum diatur oleh Administrator.'; 

        return view('dokter.jadwal', compact('doctor', 'clinic', 'jadwalPraktek'));
    }
}