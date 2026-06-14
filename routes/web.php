<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Switcher Bahasa (ID/EN) — bisa diakses guest maupun user login
Route::post('/locale/{locale}', [LocaleController::class, 'switch'])
    ->name('locale.switch')
    ->whereIn('locale', ['id', 'en']);

// Halaman verifikasi QR Code (publik, untuk petugas scan)
Route::get('/verifikasi/{reservasi}', [\App\Http\Controllers\VerifikasiController::class, 'show'])->name('verifikasi.show');

// Admin Login Routes (terpisah dari Breeze auth)
Route::get('/admin/login', [\App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('admin.login')->middleware('guest');
Route::post('/admin/login', [\App\Http\Controllers\Auth\AdminLoginController::class, 'login'])->name('admin.login.post')->middleware('guest');
Route::post('/admin/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('admin.logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [\App\Http\Controllers\CalendarController::class, 'events'])->name('calendar.events');

    // Log Pemakaian Ruangan (SSC & Logistik)
    Route::middleware('role:ssc,logistik')->group(function () {
        Route::get('/log-ruangan', [\App\Http\Controllers\LogRuanganController::class, 'index'])->name('log.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes khusus Mahasiswa
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('ruangan', [\App\Http\Controllers\Mahasiswa\RuanganController::class, 'index'])->name('ruangan.index');
        Route::get('ruangan/{ruangan}', [\App\Http\Controllers\Mahasiswa\RuanganController::class, 'show'])->name('ruangan.show');

        Route::resource('reservasi', \App\Http\Controllers\Mahasiswa\ReservasiController::class)
            ->only(['index', 'create', 'store', 'show']);
    });

    // Routes khusus SSC
    Route::middleware('role:ssc')->prefix('ssc')->name('ssc.')->group(function () {
        Route::get('approval', [\App\Http\Controllers\SSC\ApprovalController::class, 'index'])->name('approval.index');
        Route::get('approval/{reservasi}', [\App\Http\Controllers\SSC\ApprovalController::class, 'show'])->name('approval.show');
        Route::post('approval/{reservasi}/process', [\App\Http\Controllers\SSC\ApprovalController::class, 'process'])->name('approval.process');
    });

    // Routes khusus Logistik
    Route::middleware('role:logistik')->prefix('logistik')->name('logistik.')->group(function () {
        Route::resource('ruangan', \App\Http\Controllers\Logistik\RuanganController::class);

        Route::resource('jadwal', \App\Http\Controllers\Logistik\JadwalAkademikController::class)
            ->parameters(['jadwal' => 'jadwal']);

        Route::get('approval', [\App\Http\Controllers\Logistik\ApprovalController::class, 'index'])->name('approval.index');
        Route::get('approval/{reservasi}', [\App\Http\Controllers\Logistik\ApprovalController::class, 'show'])->name('approval.show');
        Route::post('approval/{reservasi}/process', [\App\Http\Controllers\Logistik\ApprovalController::class, 'process'])->name('approval.process');
    });
});

require __DIR__.'/auth.php';
