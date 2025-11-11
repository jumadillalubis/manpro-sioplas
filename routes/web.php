<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\KatimjaController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ResetPwController;

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


Route::post('/reset-password', [ResetPwController::class, 'reset']);

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