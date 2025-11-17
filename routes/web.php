<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// ==================== REDIRECT ROOT ====================
Route::get('/', function () {
    return redirect('/login');
});

// ==================== GUEST ROUTES (Login & Register) ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    
    Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// ==================== LOGOUT (Authenticated Users Only) ====================
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Data Pasien
    Route::resource('pasien', PasienController::class)->names([
        'index' => 'admin.pasien',
        'create' => 'admin.pasien.create',
        'store' => 'admin.pasien.store',
        'show' => 'admin.pasien.show',
        'edit' => 'admin.pasien.edit',
        'update' => 'admin.pasien.update',
        'destroy' => 'admin.pasien.destroy',
    ]);
    
    // Rekam Medis
    Route::resource('rekam_medis', RekamMedisController::class)->names([
        'index' => 'admin.rekam_medis',
        'store' => 'admin.rekam_medis.store',
        'update' => 'admin.rekam_medis.update',
        'destroy' => 'admin.rekam_medis.destroy',
    ])->parameters([
        'rekam_medis' => 'rekam_medi'
    ])->except(['create', 'show', 'edit']);
    
    // Pendaftaran
    Route::resource('pendaftaran', PendaftaranController::class)->names([
        'index' => 'admin.pendaftaran',
        'store' => 'admin.pendaftaran.store',
        'update' => 'admin.pendaftaran.update',
        'destroy' => 'admin.pendaftaran.destroy',
    ])->parameters([
        'pendaftaran' => 'pendaftaran'
    ])->except(['create', 'show', 'edit']);
    
    Route::put('pendaftaran/{pendaftaran}/status', [PendaftaranController::class, 'updateStatus'])->name('admin.pendaftaran.update-status');
    
    // Data Dokter
    Route::resource('dokter', DokterController::class)->names([
        'index' => 'admin.dokter',
        'store' => 'admin.dokter.store',
        'update' => 'admin.dokter.update',
        'destroy' => 'admin.dokter.destroy',
    ])->except(['create', 'show', 'edit']);
    
    // Data Obat
    Route::get('obat', [MedicationController::class, 'index'])->name('admin.obat');
    Route::post('obat', [MedicationController::class, 'store'])->name('admin.obat.store');
    Route::put('obat/{medication}', [MedicationController::class, 'update'])->name('admin.obat.update');
    Route::delete('obat/{medication}', [MedicationController::class, 'destroy'])->name('admin.obat.destroy');
    
    // Poliklinik
    Route::resource('poliklinik', PoliklinikController::class)->names([
        'index' => 'admin.poliklinik',
        'store' => 'admin.poliklinik.store',
        'update' => 'admin.poliklinik.update',
        'destroy' => 'admin.poliklinik.destroy',
    ])->except(['create', 'show', 'edit']);
    
    // Data Role
    Route::resource('roles', RoleController::class)->names([
        'index' => 'admin.roles',
        'store' => 'admin.roles.store',
        'update' => 'admin.roles.update',
        'destroy' => 'admin.roles.destroy',
    ])->except(['create', 'show', 'edit']);
    
    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('admin.laporan');
    Route::get('laporan/export', [LaporanController::class, 'export'])->name('admin.laporan.export');
});

// ==================== DOKTER ROUTES ====================
Route::middleware(['auth'])->prefix('dokter')->group(function () {
    
    Route::get('/dashboard', fn() => view('dokter.dashboard'))->name('dokter.dashboard');
    
    Route::get('/pasien', fn() => view('dokter.pasien'))->name('dokter.pasien');
    
    Route::get('/rekam_medis', fn() => view('dokter.rekam_medis'))->name('dokter.rekam_medis');
    
    Route::get('/laporan', fn() => view('dokter.laporan'))->name('dokter.laporan');
});

// ==================== PETUGAS ROUTES ====================
Route::middleware(['auth'])->prefix('petugas')->group(function () {
    
    Route::get('/dashboard', fn() => view('petugas.dashboard'))->name('petugas.dashboard');
    
    Route::get('/pendaftaran', fn() => view('petugas.pendaftaran'))->name('petugas.pendaftaran');
    
    Route::get('/pasien', fn() => view('petugas.pasien'))->name('petugas.pasien');
    
    Route::get('/obat', fn() => view('petugas.obat'))->name('petugas.obat');
    
    Route::get('/resep', fn() => view('petugas.resep'))->name('petugas.resep');
});

// ==================== PASIEN ROUTES ====================
Route::middleware(['auth'])->prefix('pasien')->name('pasien.')->group(function () {
    
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/profil', [PatientController::class, 'profile'])->name('profil');
    Route::post('/profil', [PatientController::class, 'storeProfile'])->name('profil.store');
    
    Route::get('/rekam_medis', [PatientController::class, 'medicalRecord'])->name('rekam_medis');
    
    Route::get('/konsultasi', [ConsultationController::class, 'index'])->name('konsultasi');
    Route::post('/konsultasi/tatap-muka', [ConsultationController::class, 'storeTatapMuka'])->name('konsultasi.store.tm');
    Route::post('/konsultasi/online', [ConsultationController::class, 'storeOnline'])->name('konsultasi.store.online');
});