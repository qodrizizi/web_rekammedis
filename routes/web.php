<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PatientController;
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard masing-masing role
Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->middleware('auth')->name('admin.dashboard');
Route::get('/admin/pasien', fn() => view('admin.pasien'))->middleware('auth')->name('admin.pasien');
Route::get('/admin/rekam_medis', fn() => view('admin.rekam_medis'))->middleware('auth')->name('admin.rekam_medis');
Route::get('/admin/pendaftaran', fn() => view('admin.pendaftaran'))->middleware('auth')->name('admin.pendaftaran');
Route::get('/admin/dokter', fn() => view('admin.dokter'))->middleware('auth')->name('admin.dokter');
Route::get('/admin/obat', fn() => view('admin.obat'))->middleware('auth')->name('admin.obat');
Route::get('/admin/laporan', fn() => view('admin.laporan'))->middleware('auth')->name('admin.laporan');

Route::get('/dokter/dashboard', fn() => view('dokter.dashboard'))->middleware('auth')->name('dokter.dashboard');
Route::get('/dokter/pasien', fn() => view('dokter.pasien'))->middleware('auth')->name('dokter.pasien');
Route::get('/dokter/rekam_medis', fn() => view('dokter.rekam_medis'))->middleware('auth')->name('dokter.rekam_medis');
Route::get('/dokter/laporan', fn() => view('dokter.laporan'))->middleware('auth')->name('dokter.laporan');

Route::get('/petugas/dashboard', fn() => view('petugas.dashboard'))->middleware('auth')->name('petugas.dashboard');
Route::get('/petugas/pasien', fn() => view('petugas.pasien'))->middleware('auth')->name('petugas.pasien');
Route::get('/petugas/pendaftaran', fn() => view('petugas.pendaftaran'))->middleware('auth')->name('petugas.pendaftaran');
Route::get('/petugas/obat', fn() => view('petugas.obat'))->middleware('auth')->name('petugas.obat');
Route::get('/petugas/resep', fn() => view('petugas.resep'))->middleware('auth')->name('petugas.resep');

Route::middleware(['auth'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

    Route::get('/rekam_medis', [PatientController::class, 'medicalRecord'])->name('rekam_medis');
    
    Route::get('/profil', [PatientController::class, 'profile'])->name('profil');
    Route::post('/profil', [PatientController::class, 'storeProfile'])->name('profil.store');

    Route::get('/konsultasi', [ConsultationController::class, 'index'])->name('konsultasi');
    Route::post('/konsultasi/tatap-muka', [ConsultationController::class, 'storeTatapMuka'])->name('konsultasi.store.tm');
    Route::post('/konsultasi/online', [ConsultationController::class, 'storeOnline'])->name('konsultasi.store.online');
    
});