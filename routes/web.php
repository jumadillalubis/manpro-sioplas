<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\KatimjaController;
use App\Http\Controllers\StaffController;
use App\Models\Staff;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Landing Page
Route::get('/', function () {
    return view('Auth.landing_page');
})->name('landing');

// Login Routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Beranda Routes berdasarkan Jabatan
Route::get('/beranda/atasan', [AtasanController::class, 'beranda'])->name('beranda.atasan');
Route::get('/beranda/katimja', [KatimjaController::class, 'beranda'])->name('beranda.katimja');
Route::get('/beranda/staff', [StaffController::class, 'beranda'])->name('beranda.staff');

// Laporan Routes
Route::get('/laporan/katimja', [KatimjaController::class, 'laporan'])->name('laporan.katimja');

// Legacy route untuk backward compatibility - menggunakan jabatan dari session
Route::get('/beranda', function () {
    $jabatan = strtolower(trim(session('user_jabatan', '')));

    // Jika tidak ada jabatan di session, redirect ke login
    if (empty($jabatan)) {
        return redirect()->route('login');
    }

    // Helper function untuk mapping jabatan ke route (sama seperti di LoginController)
    $getBerandaRouteByJabatan = function ($jabatan) {
        // Prioritas 1: Jabatan untuk Staff
        if (
            strpos($jabatan, 'kepala bidang') !== false ||
            strpos($jabatan, 'pegawai') !== false ||
            strpos($jabatan, 'staff') !== false ||
            strpos($jabatan, 'bidang') !== false
        ) {
            return 'beranda.staff';
        }

        // Jabatan untuk Katimja (prioritas 2, setelah Staff)
        if (
            strpos($jabatan, 'katimja') !== false ||
            strpos($jabatan, 'kaptimja') !== false ||
            strpos($jabatan, 'kepala tim kerja') !== false ||
            strpos($jabatan, 'sekretaris dinas') !== false ||
            strpos($jabatan, 'sekretaris') !== false ||
            strpos($jabatan, 'wakil kepala dinas') !== false ||
            strpos($jabatan, 'wakil kepala') !== false ||
            strpos($jabatan, 'wakil') !== false
        ) {
            return 'beranda.katimja';
        }

        // Jabatan untuk Atasan (prioritas terakhir)
        if (
            strpos($jabatan, 'atasan') !== false ||
            strpos($jabatan, 'kepala dinas') !== false ||
            strpos($jabatan, 'kepala') !== false
        ) {
            return 'beranda.atasan';
        }

        // Default: redirect ke beranda atasan jika jabatan tidak dikenali
        return 'beranda.atasan';
    };

    $route = $getBerandaRouteByJabatan($jabatan);
    return redirect()->route($route);
})->name('beranda');

// Route untuk halaman Beranda Katimja
Route::get('/beranda/katimja', [KatimjaController::class, 'beranda'])->name('beranda.katimja');

// Route untuk halaman Laporan Katimja
Route::get('/laporan/katimja', [KatimjaController::class, 'laporan'])->name('laporan.katimja');

// Route untuk halaman Daftar Tugas Katimja
Route::get('/tugas/katimja', [KatimjaController::class, 'tugas'])->name('tugas.katimja');

// Route untuk melihat detail Tugas Katimja berdasarkan ID
Route::get('/tugas/{id}', [KatimjaController::class, 'showTugas'])->name('tugas.show');

// Route untuk form membuat tugas baru
Route::get('/tugas/buat', [KatimjaController::class, 'createTugas'])->name('tugas.create');

// Route untuk menyimpan tugas baru
Route::post('/tugas', [KatimjaController::class, 'storeTugas'])->name('tugas.store');

// Route untuk menampilkan halaman profil Katimja
Route::get('/profile/katimja', [KatimjaController::class, 'settings'])->name('profile.katimja');

// Route untuk mengganti password
Route::get('/profile/change-password', [KatimjaController::class, 'changePassword'])->name('profile.changePassword');
Route::post('/profile/update-password', [KatimjaController::class, 'updatePassword'])->name('profile.updatePassword');


// Staff
// =========================
// ROUTE STAFF
// =========================
Route::prefix('staff')->group(function () {

    // Halaman utama
    Route::get('/beranda', [StaffController::class, 'beranda'])
        ->name('beranda.staff');

    // Laporan Pegawai
    Route::get('/laporan', [StaffController::class, 'laporan'])
        ->name('laporan.pegawai');
    // web.php
Route::post('/pegawai/upload', [StaffController::class, 'upload'])
    ->name('staff.upload');


    // Daftar Tugas
    Route::get('/tugas', [StaffController::class, 'tugas'])
        ->name('tugas.pegawai');

    // Detail tugas
    Route::get('/tugas/{id}/detail', [StaffController::class, 'detailTugas'])
        ->name('tugas.detail');

    // Upload file tugas
    Route::post('/tugas/{id}/upload', [StaffController::class, 'uploadTugas'])
        ->name('tugas.upload');

    // Upload file umum (indikator/TW)
    Route::post('/upload', [StaffController::class, 'uploadFile'])
        ->name('staff.upload');

    // Settings profil
    Route::get('/settings', [StaffController::class, 'settings'])
        ->name('pegawai.settings');

    // Halaman ganti password
    Route::get('/settings/password', [StaffController::class, 'changePassword'])
        ->name('pegawai.change.password');

    // Aksi update password
    Route::post('/settings/password', [StaffController::class, 'updatePassword'])
        ->name('pegawai.update.password');

});
