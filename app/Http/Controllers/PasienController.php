<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PasienController extends Controller
{
    /**
     * READ: Menampilkan daftar semua pasien (Index).
     * VIEW: admin.pasien
     */
    public function index()
    {
        $patients = Patient::with('user')
                            ->latest()
                            ->paginate(10); 

        // Mengembalikan view utama daftar pasien: resources/views/admin/pasien.blade.php
        return view('admin.pasien', compact('patients'));
    }

    /**
     * CREATE: Menampilkan form untuk membuat pasien baru.
     * VIEW: admin.pasien.create
     */
    public function create()
    {
        // Mengembalikan view untuk form tambah: resources/views/admin/pasien/create.blade.php
        return view('admin.pasien.create'); 
    }

    /**
     * CREATE: Menyimpan data pasien baru ke database (Store).
     */
    public function store(Request $request)
    {
        $request->validate([
            // ... (validasi seperti sebelumnya)
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'required|string|unique:patients|max:20',
            'no_bpjs' => 'nullable|string|max:30',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'gol_darah' => 'nullable|string|max:3',
            'no_hp' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            $patientRole = Role::where('role_name', 'Pasien')->firstOrFail();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $patientRole->id,
            ]);

            Patient::create([
                'user_id' => $user->id,
                'nik' => $request->nik,
                'no_bpjs' => $request->no_bpjs,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'gol_darah' => $request->gol_darah,
                'no_hp' => $request->no_hp,
            ]);
        });
        
        return redirect()->route('admin.pasien')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    /**
     * READ: Menampilkan detail satu pasien (Show).
     * VIEW: admin.pasien.show
     */
    public function show(Patient $pasien) // Menggunakan $pasien untuk consistency route
    {
        $pasien->load('user');
        
        // Mengembalikan view untuk detail: resources/views/admin/pasien/show.blade.php
        return view('admin.pasien.show', compact('pasien')); 
    }

    /**
     * UPDATE: Menampilkan form untuk mengedit pasien (Edit).
     * VIEW: admin.pasien.edit
     */
    public function edit(Patient $pasien) // Menggunakan $pasien
    {
        $pasien->load('user');
        
        // Mengembalikan view untuk form edit: resources/views/admin/pasien/edit.blade.php
        return view('admin.pasien.edit', compact('pasien')); 
    }

    /**
     * UPDATE: Memperbarui data pasien di database (Update).
     */
    public function update(Request $request, Patient $pasien) // Menggunakan $pasien
    {
        $request->validate([
            // ... (validasi seperti sebelumnya, perhatikan ignore)
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($pasien->user_id)],
            'password' => 'nullable|string|min:8|confirmed',
            'nik' => ['required', 'string', 'max:20', Rule::unique('patients')->ignore($pasien->id)],
            'no_bpjs' => 'nullable|string|max:30',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'gol_darah' => 'nullable|string|max:3',
            'no_hp' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, $pasien) {
            
            $user = $pasien->user;
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $pasien->update([
                'nik' => $request->nik,
                'no_bpjs' => $request->no_bpjs,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'gol_darah' => $request->gol_darah,
                'no_hp' => $request->no_hp,
            ]);
        });

        return redirect()->route('admin.pasien')->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus data pasien dari database (Destroy).
     */
    public function destroy(Patient $pasien) // Menggunakan $pasien
    {
        DB::transaction(function () use ($pasien) {
            $userId = $pasien->user_id;

            $pasien->delete();

            User::destroy($userId);
        });

        return redirect()->route('admin.pasien')->with('success', 'Data pasien berhasil dihapus.');
    }
}