<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\RekamMedisController;

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
Route::resource('admin/pasien', PasienController::class)->names([
    'index' => 'admin.pasien',
    'create' => 'admin.pasien.create',
    'store' => 'admin.pasien.store',
    'show' => 'admin.pasien.show',
    'edit' => 'admin.pasien.edit',
    'update' => 'admin.pasien.update',
    'destroy' => 'admin.pasien.destroy',
]);
Route::resource('admin/rekam_medis', RekamMedisController::class)->names([
    'index' => 'admin.rekam_medis',
    'store' => 'admin.rekam_medis.store',
    'update' => 'admin.rekam_medis.update',
    'destroy' => 'admin.rekam_medis.destroy',
])->parameters([
    'rekam-medis' => 'rekam_medi' // Custom parameter name agar cocok dengan Controller
])->except(['create', 'show', 'edit']);

Route::get('/admin/pendaftaran', fn() => view('admin.pendaftaran'))->middleware('auth')->name('admin.pendaftaran');
Route::resource('admin/dokter', DokterController::class)->names([
    'index' => 'admin.dokter',
    'store' => 'admin.dokter.store',
    'update' => 'admin.dokter.update',
    'destroy' => 'admin.dokter.destroy',
])->except(['create', 'show', 'edit']); 
// READ (Index)
Route::get('/admin/obat', [MedicationController::class, 'index'])
    ->middleware('auth')
    ->name('admin.obat');

// CREATE (Store)
Route::post('/admin/obat', [MedicationController::class, 'store'])
    ->middleware('auth')
    ->name('admin.obat.store');

// UPDATE (Update)
Route::put('/admin/obat/{medication}', [MedicationController::class, 'update'])
    ->middleware('auth')
    ->name('admin.obat.update');
    
// DELETE (Destroy)
Route::delete('/admin/obat/{medication}', [MedicationController::class, 'destroy'])
    ->middleware('auth')
    ->name('admin.obat.destroy');

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