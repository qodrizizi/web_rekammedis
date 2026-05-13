<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    // 🔹 Tampilkan halaman login
    public function loginPage()
    {
        return view('auth.login');
    }

    // 🔹 Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Redirect sesuai role
            switch ($user->role_id) {
                case 1: return redirect()->route('admin.dashboard');
                case 2: return redirect()->route('dokter.dashboard');
                case 3: return redirect()->route('petugas.dashboard');
                case 4: return redirect()->route('pasien.dashboard');
                case 5: return redirect()->route('admin.dashboard'); // Superadmin ke dashboard admin
                default: return redirect()->route('login');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // 🔹 Tampilkan halaman register
    public function registerPage()
    {
        return view('auth.register');
    }

    // 🔹 Proses register pasien baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 4, // Pasien
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // 🔹 Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
