<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\DoctorPatientController;
use App\Http\Controllers\DoctorPemeriksaanController;
use App\Http\Controllers\DoctorReportController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PetugasMedicationController;
use App\Http\Controllers\PetugasPasienController;
use App\Http\Controllers\PetugasResepController;
use App\Http\Controllers\PetugasDashboardController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== ROOT REDIRECT ====================
Route::get('/', fn() => redirect('/login'));

// ==================== AUTHENTICATION ====================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    
    // Register
    Route::get('/register', [AuthController::class, 'registerPage'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ==================== ADMIN PANEL ====================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Data Pasien
    Route::resource('pasien', PasienController::class)->names([
        'index' => 'pasien',
        'create' => 'pasien.create',
        'store' => 'pasien.store',
        'show' => 'pasien.show',
        'edit' => 'pasien.edit',
        'update' => 'pasien.update',
        'destroy' => 'pasien.destroy',
    ]);
    
    // Data Dokter
    Route::resource('dokter', DokterController::class)
        ->except(['create', 'show', 'edit'])
        ->names([
            'index' => 'dokter',
            'store' => 'dokter.store',
            'update' => 'dokter.update',
            'destroy' => 'dokter.destroy',
        ]);
    
    // Data Obat
    Route::controller(MedicationController::class)->group(function () {
        Route::get('obat', 'index')->name('obat');
        Route::post('obat', 'store')->name('obat.store');
        Route::put('obat/{medication}', 'update')->name('obat.update');
        Route::delete('obat/{medication}', 'destroy')->name('obat.destroy');
    });
    
    // Poliklinik
    Route::resource('poliklinik', PoliklinikController::class)
        ->except(['create', 'show', 'edit'])
        ->names([
            'index' => 'poliklinik',
            'store' => 'poliklinik.store',
            'update' => 'poliklinik.update',
            'destroy' => 'poliklinik.destroy',
        ]);
    
    // Rekam Medis
    Route::resource('rekam_medis', RekamMedisController::class)
        ->parameters(['rekam_medis' => 'rekam_medi'])
        ->except(['create', 'show', 'edit'])
        ->names([
            'index' => 'rekam_medis',
            'store' => 'rekam_medis.store',
            'update' => 'rekam_medis.update',
            'destroy' => 'rekam_medis.destroy',
        ]);
    
    // Pendaftaran
    Route::resource('pendaftaran', PendaftaranController::class)
        ->except(['create', 'show', 'edit'])
        ->names([
            'index' => 'pendaftaran',
            'store' => 'pendaftaran.store',
            'update' => 'pendaftaran.update',
            'destroy' => 'pendaftaran.destroy',
        ]);
    Route::put('pendaftaran/{pendaftaran}/status', [PendaftaranController::class, 'updateStatus'])
        ->name('pendaftaran.update-status');
    
    // Data Role
    Route::resource('roles', RoleController::class)
        ->except(['create', 'show', 'edit'])
        ->names([
            'index' => 'roles',
            'store' => 'roles.store',
            'update' => 'roles.update',
            'destroy' => 'roles.destroy',
        ]);
    
    // Laporan
    Route::controller(LaporanController::class)->prefix('laporan')->name('laporan')->group(function () {
        Route::get('/', 'index');
        Route::get('/export', 'export')->name('.export');
    });
});

// ==================== DOKTER PANEL ====================
Route::middleware(['auth'])->prefix('dokter')->name('dokter.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    
    // Data Pasien
    Route::get('/pasien', [DoctorPatientController::class, 'index'])->name('pasien');
    Route::get('/pasien/{id}/history', [DoctorPatientController::class, 'getPatientHistory'])->name('pasien.history');
    
    // Pemeriksaan
    Route::controller(DoctorPemeriksaanController::class)->group(function () {
        Route::get('/pemeriksaan', 'pemeriksaan')->name('pemeriksaan');
        Route::get('/periksa/{id}', 'periksa')->name('periksa');
        Route::post('/periksa/{id}', 'store')->name('periksa.store');
    });
    
    // Simpan Hasil Pemeriksaan
    Route::post('/simpan-pemeriksaan', [RekamMedisController::class, 'storeDoctor'])->name('simpan-pemeriksaan');
    
    // Rekam Medis
    Route::get('/rekam_medis', [RekamMedisController::class, 'indexDoctorRecord'])->name('rekam_medis');
    
    // Jadwal
    Route::get('/jadwal', [DokterController::class, 'showJadwal'])->name('jadwal');
    
    // Laporan
    Route::controller(DoctorReportController::class)->prefix('laporan')->name('laporan')->group(function () {
        Route::get('/', 'index');
        Route::get('/export', 'exportPdf')->name('.export');
    });
});

// ==================== PETUGAS PANEL ====================
Route::middleware(['auth'])->prefix('petugas')->name('petugas.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    
    // Pendaftaran
    Route::controller(PendaftaranController::class)->prefix('pendaftaran')->name('pendaftaran')->group(function () {
        Route::get('/', 'index');
        Route::post('/baru', 'storeNewPatientRegistration')->name('.store.new');
        Route::post('/kunjungan', 'storeOldPatientRegistration')->name('.store.old');
    });
    
    // Data Pasien
    Route::resource('pasien', PetugasPasienController::class)
        ->except(['create', 'show', 'edit'])
        ->names([
            'index' => 'pasien',
            'store' => 'pasien.store',
            'update' => 'pasien.update',
            'destroy' => 'pasien.destroy',
        ]);
    
    // Data Obat
    Route::controller(PetugasMedicationController::class)->group(function () {
        Route::get('obat', 'index')->name('obat');
        Route::post('obat', 'store')->name('obat.store');
        Route::put('obat/{medication}', 'update')->name('obat.update');
        Route::delete('obat/{medication}', 'destroy')->name('obat.destroy');
    });
    
    // Resep
    Route::controller(PetugasResepController::class)->prefix('resep')->name('resep')->group(function () {
        Route::get('/', 'index');
        Route::post('/{id}/proses', 'process')->name('.process');
        Route::post('/{id}/selesai', 'complete')->name('.complete');
    });
    
    // API Routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/doctors-by-clinic/{clinic_id}', [PendaftaranController::class, 'getDoctorsByClinic'])
            ->name('doctors.by_clinic');
        Route::get('/search-patient', [PendaftaranController::class, 'searchPatient'])
            ->name('search.patient');
    });
});

// ==================== PASIEN PANEL ====================
Route::middleware(['auth'])->prefix('pasien')->name('pasien.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    
    // Profil
    Route::controller(PatientController::class)->prefix('profil')->name('profil')->group(function () {
        Route::get('/', 'profile');
        Route::post('/', 'storeProfile')->name('.store');
    });
    
    // Rekam Medis
    Route::get('/rekam_medis', [PatientController::class, 'medicalRecord'])->name('rekam_medis');
    
    // Konsultasi
    Route::controller(ConsultationController::class)->prefix('konsultasi')->name('konsultasi')->group(function () {
        Route::get('/', 'index');
        Route::post('/tatap-muka', 'storeTatapMuka')->name('.store.tm');
        Route::post('/online', 'storeOnline')->name('.store.online');
    });
});