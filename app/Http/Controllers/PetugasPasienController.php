<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
class PetugasPasienController extends Controller
{
    /**
     * Menampilkan daftar semua pasien (untuk Admin/Petugas).
     */
    public function index()
    {
        // Ambil data pasien dengan relasi user-nya, di-paginate
        $patients = Patient::with('user')
            ->latest('id') // Urutkan berdasarkan ID terbaru
            ->paginate(10); // Ambil 10 data per halaman

        return view('petugas.pasien', compact('patients'));
    }

    /**
     * Menyimpan data pasien baru (dari modal).
     */
    public function store(Request $request)
    {
        // Validasi data (termasuk validasi password)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'nik' => 'required|string|max:20|unique:patients,nik',
            'no_bpjs' => 'nullable|string|max:30',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'gol_darah' => 'nullable|string|max:3',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'required|string',
        ]);

        // Gunakan DB Transaction agar aman
        try {
            DB::beginTransaction();

            // 1. Buat Akun User (untuk login)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => 4, // 4 = Pasien (sesuai db_webrekamedis.sql)
            ]);

            // 2. Buat Data Pasien (profil)
            Patient::create([
                'user_id' => $user->id,
                'nik' => $validated['nik'],
                'no_bpjs' => $validated['no_bpjs'],
                'alamat' => $validated['alamat'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'gol_darah' => $validated['gol_darah'],
                'no_hp' => $validated['no_hp'],
            ]);

            DB::commit();

            return redirect()->route('petugas.pasien')->with('success', 'Pasien baru (' . $validated['name'] . ') berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Kirim error kembali ke form (modal akan terbuka otomatis)
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Update data pasien (dari modal edit).
     */
    public function update(Request $request, $id) // $id di sini adalah patient->id
    {
        $patient = Patient::findOrFail($id);
        $user = User::findOrFail($patient->user_id);

        // Validasi data (email unik, tapi abaikan email user saat ini)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Ini memberitahu validator untuk mengabaikan user ID saat ini
                Rule::unique('users')->ignore($user->id), 
            ],
            'password' => ['nullable', 'confirmed', Password::min(8)], // Password opsional
            'nik' => [
                'required',
                'string',
                'max:20',
                // Ini memberitahu validator untuk mengabaikan patient ID saat ini
                Rule::unique('patients')->ignore($patient->id), 
            ],
            'no_bpjs' => 'nullable|string|max:30',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'gol_darah' => 'nullable|string|max:3',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update User
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            // Hanya update password jika diisi
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            // 2. Update Pasien
            $patient->update([
                'nik' => $validated['nik'],
                'no_bpjs' => $validated['no_bpjs'],
                'alamat' => $validated['alamat'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'gol_darah' => $validated['gol_darah'],
                'no_hp' => $validated['no_hp'],
            ]);

            DB::commit();

            return redirect()->route('petugas.pasien')->with('success', 'Data pasien (' . $validated['name'] . ') berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Hapus data pasien (dan akun user-nya).
     */
    public function destroy($id) // $id di sini adalah patient->id
    {
        try {
            $patient = Patient::findOrFail($id);
            $userName = $patient->user->name;
            
            // Hapus user-nya (ini akan otomatis menghapus data pasien jika 
            // Anda mengatur 'onDelete('cascade')' di foreign key patient.user_id)
            // Jika tidak, kita hapus manual keduanya
            
            $user = User::find($patient->user_id);
            
            // Hapus pasien dulu
            $patient->delete();
            // Hapus user
            if ($user) {
                $user->delete();
            }

            return redirect()->route('petugas.pasien')->with('success', 'Pasien (' . $userName . ') dan akun login-nya telah dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }
}