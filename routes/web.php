<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    FoundationController,
    SeriesController,
    StokController,
    DistributionController,
    GuestController,
    ProfilController,
    LaporanController,
    FoundationDashboardController,
    UserController
};

// =====================
// PUBLIC / GUEST ROUTES
// =====================
Route::get('/', [GuestController::class, 'index'])->name('guest.index');
Route::get('/info/pondok/{pondok}', [GuestController::class, 'pondokDetail'])->name('guest.pondok');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil (semua user)
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');

    // Laporan / Grafik (semua role bisa lihat)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/grafik', [LaporanController::class, 'grafikBulanan'])->name('laporan.grafik');
    Route::get('/laporan/pdf/{seri}', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');

    // Pondok dashboard (khusus role pondok)
    Route::get('/pondok-ku', [FoundationDashboardController::class, 'index'])
        ->middleware('role:pondok')
        ->name('pondok.dashboard');

    // Pondok
    Route::resource('pondok', FoundationController::class)->except(['show']);

    // Stok Beras
    Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
    Route::get('/stok/tambah', [StokController::class, 'create'])->name('stok.create');
    Route::post('/stok', [StokController::class, 'store'])->name('stok.store');
    Route::delete('/stok/{stok}', [StokController::class, 'destroy'])->name('stok.destroy');

    // Seri & Jadwal
    Route::resource('seri', SeriesController::class);
    Route::get('/seri/{seri}/jadwal', [SeriesController::class, 'jadwal'])->name('seri.jadwal');
    Route::post('/seri/{seri}/jadwal', [SeriesController::class, 'storeJadwal'])->name('seri.jadwal.store');
    Route::post('/seri/{seri}/aktifkan', [SeriesController::class, 'aktivasi'])->name('seri.aktifkan');
    Route::post('/seri/{seri}/selesai', [SeriesController::class, 'selesai'])->name('seri.selesai');
    Route::post('/seri/{seri}/aktifkan_lagi', [SeriesController::class, 'aktifkan_lagi'])->name('seri.aktifkan_lagi');

    // Aktivitas Penyaluran
    Route::get('/aktivitas', [DistributionController::class, 'index'])->name('aktivitas.index');
    Route::get('/aktivitas/tambah', [DistributionController::class, 'create'])->name('aktivitas.create');
    Route::post('/aktivitas', [DistributionController::class, 'store'])->name('aktivitas.store');
    Route::get('/aktivitas/{aktivitas}', [DistributionController::class, 'show'])->name('aktivitas.show');

    Route::middleware('role:admin')->group(function () {
        // Manajemen User
        Route::resource('user', UserController::class);
        Route::post('/user/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
        Route::post('/user/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('user.toggle-active');
    });
});