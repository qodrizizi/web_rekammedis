<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class RoleController extends Controller
{
    /**
     * READ: Menampilkan daftar semua Hak Akses (Index).
     */
    public function index()
    {
        // Ambil data role dan lakukan pagination
        $roles = Role::latest()->paginate(10); 

        return view('admin.roles', compact('roles'));
    }

    /**
     * CREATE: Menyimpan Hak Akses baru (Store).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:50|unique:roles,role_name',
            'description' => 'nullable|string',
        ]);

        Role::create($validated);
        
        return redirect()->route('admin.roles')->with('success', 'Hak Akses baru berhasil ditambahkan.');
    }

    /**
     * UPDATE: Memperbarui Hak Akses (Update).
     */
    public function update(Request $request, Role $role) 
    {
        // PENTING: Role dasar (Admin, Dokter, Petugas, Pasien) sebaiknya tidak diizinkan diubah namanya.
        // Jika Anda ingin melindungi ID 1-4, tambahkan logika di sini.

        $validated = $request->validate([
            'role_name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('roles', 'role_name')->ignore($role->id),
            ],
            'description' => 'nullable|string',
        ]);
        
        $role->update($validated);

        return redirect()->route('admin.roles')->with('success', 'Hak Akses berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus Hak Akses (Destroy).
     */
    public function destroy(Role $role)
    {
        // PENTING: Larang penghapusan role jika masih ada user yang terikat.
        if ($role->users()->exists()) {
             return redirect()->route('admin.roles')->with('error', 'Gagal menghapus! Masih ada pengguna yang terikat pada Hak Akses ini.');
        }

        $role->delete();

        return redirect()->route('admin.roles')->with('success', 'Hak Akses berhasil dihapus.');
    }
}