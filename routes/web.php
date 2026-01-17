<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\LaporanAtasanController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\KatimjaController;
use App\Http\Controllers\StaffController;

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

//Beranda Atasan
Route::get('/beranda-atasan', [AtasanController::class, 'beranda'])->name('beranda.atasan');

// Laporan Atasan
Route::prefix('atasan')->group(function () {
    Route::get('/laporan', [LaporanAtasanController::class, 'index'])->name('laporan.atasan');
    Route::get('/laporan/{id}', [LaporanAtasanController::class, 'show'])->name('laporan.detail');
    Route::post('/laporan/{id}/verifikasi', [LaporanAtasanController::class, 'verifikasi'])->name('laporan.verifikasi');
    Route::delete('/laporan/{id}', [LaporanAtasanController::class, 'destroy'])->name('laporan.hapus');
});

// Notif Atasan
Route::get('/notifikasi-atasan', [AtasanController::class, 'notifikasi'])->name('notifikasi.atasan');

// halaman settings Atasan
    Route::get('/atasan/settings', [AtasanController::class, 'settings'])
        ->name('atasan.settings');

    // halaman ubah password Atasan
    Route::get('/atasan/ubah-password', [AtasanController::class, 'ubahPassword'])
        ->name('atasan.ubah.password');

    // proses update password Atasan
    Route::post('/atasan/update-password', [AtasanController::class, 'updatePassword'])
        ->name('atasan.update.password');

    // tugas atasan
Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
Route::get('/tugas/create', function () {
    return view('Atasan.tugas_create');
})->name('tugas.create');
Route::get('/tugas/{id}', [TugasController::class, 'show'])->name('tugas.show');
Route::post('/tugas/{id}/approve', [TugasController::class, 'approve'])->name('tugas.approve');

Route::get('/tugas/{id}/lihat', [TugasController::class, 'lihat'])
    ->name('tugas.lihat');

Route::post('/tugas/{id}/respon', [TugasController::class, 'respon'])
    ->name('tugas.respon');

        
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
    $getBerandaRouteByJabatan = function($jabatan) {
        // Prioritas 1: Jabatan untuk Staff
        if (strpos($jabatan, 'kepala bidang') !== false || 
            strpos($jabatan, 'pegawai') !== false ||
            strpos($jabatan, 'staff') !== false ||
            strpos($jabatan, 'bidang') !== false) {
            return 'beranda.staff';
        }
        
        // Jabatan untuk Katimja (prioritas 2, setelah Staff)
        if (strpos($jabatan, 'katimja') !== false || 
            strpos($jabatan, 'kaptimja') !== false ||
            strpos($jabatan, 'kepala tim kerja') !== false ||
            strpos($jabatan, 'sekretaris dinas') !== false ||
            strpos($jabatan, 'sekretaris') !== false ||
            strpos($jabatan, 'wakil kepala dinas') !== false ||
            strpos($jabatan, 'wakil kepala') !== false ||
            strpos($jabatan, 'wakil') !== false) {
            return 'beranda.katimja';
        }
        
        // Jabatan untuk Atasan (prioritas terakhir)
        if (strpos($jabatan, 'atasan') !== false || 
            strpos($jabatan, 'kepala dinas') !== false ||
            strpos($jabatan, 'kepala') !== false) {
            return 'beranda.atasan';
        }
        
        // Default: redirect ke beranda atasan jika jabatan tidak dikenali
        return 'beranda.atasan';
    };
    
    $route = $getBerandaRouteByJabatan($jabatan);
    return redirect()->route($route);
})->name('beranda');