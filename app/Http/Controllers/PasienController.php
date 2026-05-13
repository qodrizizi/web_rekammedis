<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PasienController extends Controller
{
    /**
     * READ: Menampilkan daftar semua pasien.
     */
    public function index(Request $request)
    {
        $query = Patient::with('user');

        // Filter Pencarian (Nama, NIK, atau BPJS)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('no_bpjs', 'like', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Jenis Kelamin
        if ($request->filled('gender')) {
            $query->where('jenis_kelamin', $request->gender);
        }

        // Filter Golongan Darah
        if ($request->filled('blood_type')) {
            $query->where('gol_darah', $request->blood_type);
        }

        $patients = $query->latest('id')->paginate(10)->withQueryString();

        return view('shared.pasien', compact('patients'));
    }

    /**
     * CREATE: Menyimpan data pasien baru ke database.
     */
    public function store(Request $request)
    {
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

        try {
            DB::beginTransaction();

            $patientRole = Role::where('role_name', 'Pasien')->firstOrFail();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $patientRole->id,
            ]);

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

            return redirect()->back()->with('success', 'Pasien baru (' . $validated['name'] . ') berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * UPDATE: Memperbarui data pasien di database.
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $user = User::findOrFail($patient->user_id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id), 
            ],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'nik' => [
                'required', 'string', 'max:20',
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

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

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

            return redirect()->back()->with('success', 'Data pasien (' . $validated['name'] . ') berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * DELETE: Menghapus data pasien.
     */
    public function destroy($id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $userName = $patient->user->name;
            
            $user = User::find($patient->user_id);
            
            $patient->delete();
            if ($user) {
                $user->delete();
            }

            return redirect()->back()->with('success', 'Pasien (' . $userName . ') telah dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }
}