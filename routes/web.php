<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\LaporanAtasanController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\KatimjaController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;
/*
|--------------------------------------------------------------------------
| AUTH & LANDING
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('Auth.landing_page');
})->name('landing');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Route OTP (2FA) — hanya bisa diakses setelah tahap 1 login berhasil
Route::get('/otp', [LoginController::class, 'showOtp'])->name('otp.show');
Route::post('/otp', [LoginController::class, 'verifyOtp'])->name('otp.verify');

Route::get('/reset-password', [App\Http\Controllers\ResetPasswordController::class, 'showResetForm'])->name('reset-password');
Route::post('/reset-password', [App\Http\Controllers\ResetPasswordController::class, 'processReset'])->name('reset-password.process');

/*
|--------------------------------------------------------------------------
| REDIRECT BERANDA (LEGACY)
|--------------------------------------------------------------------------
*/
Route::get('/beranda', function () {
    $jabatan = strtolower(trim(session('user_jabatan', '')));

    if (empty($jabatan)) {
        return redirect()->route('login');
    }

    if (
        strpos($jabatan, 'kepala dinas') !== false ||
        strpos($jabatan, 'atasan') !== false
    ) {
        return redirect()->route('beranda.atasan');
    }

    if (
        strpos($jabatan, 'katimja') !== false ||
        strpos($jabatan, 'kepala tim kerja') !== false ||
        strpos($jabatan, 'sekretaris') !== false ||
        strpos($jabatan, 'wakil') !== false
    ) {
        return redirect()->route('beranda.katimja');
    }

    return redirect()->route('beranda.staff');
})->name('beranda');

/*
|--------------------------------------------------------------------------
| ATASAN
|--------------------------------------------------------------------------
*/
Route::prefix('atasan')->group(function () {

    Route::get('/beranda', [AtasanController::class, 'beranda'])
        ->name('beranda.atasan');

    // Laporan
    Route::get('/laporan', [LaporanAtasanController::class, 'index'])
        ->name('laporan.atasan');

    Route::get('/laporan/{id}', [LaporanAtasanController::class, 'show'])
        ->name('laporan.detail');

    Route::post('/laporan/{id}/verifikasi', [LaporanAtasanController::class, 'verifikasi'])
        ->name('laporan.verifikasi');

    Route::delete('/laporan/{id}', [LaporanAtasanController::class, 'destroy'])
        ->name('laporan.hapus');
});

/*
|--------------------------------------------------------------------------
| TUGAS (ATASAN)
|--------------------------------------------------------------------------
*/
Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');

Route::post('/tugas', [TugasController::class, 'store'])->name('tugas.store');

Route::get('/tugas/create', [TugasController::class, 'create'])->name('tugas.create');

Route::get('/tugas/{id}', [TugasController::class, 'show'])->name('tugas.show');

Route::post('/tugas/{id}/approve', [TugasController::class, 'approve'])
    ->name('tugas.approve');

Route::get('/tugas/{id}/lihat', [TugasController::class, 'lihat'])
    ->name('tugas.lihat');

Route::post('/tugas/{id}/respon', [TugasController::class, 'respon'])
    ->name('tugas.respon');

/*
|--------------------------------------------------------------------------
| KATIMJA
|--------------------------------------------------------------------------
*/
Route::prefix('katimja')->group(function () {

    Route::get('/beranda', [KatimjaController::class, 'beranda'])
        ->name('beranda.katimja');

    Route::get('/laporan', [KatimjaController::class, 'laporan'])
        ->name('laporan.katimja');

    Route::get('/tugas', [KatimjaController::class, 'tugas'])
        ->name('tugas.katimja');

    Route::get('/tugas/buat', [KatimjaController::class, 'createTugas'])
        ->name('katimja.tugas.create');

    Route::post('/tugas', [KatimjaController::class, 'storeTugas'])
        ->name('katimja.tugas.store');

    Route::get('/tugas/{id}', [KatimjaController::class, 'showTugas'])
        ->name('katimja.tugas.show');

    Route::post('/tugas/{id}/respon', [KatimjaController::class, 'respon'])
        ->name('katimja.tugas.respon');

    Route::post('/tugas/{id}/assign', [KatimjaController::class, 'assign'])
        ->name('katimja.tugas.assign');

    Route::post('/tugas/{id}/approve', [KatimjaController::class, 'approveTugas'])
        ->name('katimja.tugas.approve');

    Route::post('/laporan/upload', [KatimjaController::class, 'uploadLaporan'])
        ->name('katimja.laporan.upload');

});

/*
|--------------------------------------------------------------------------
| STAFF
|--------------------------------------------------------------------------
*/
Route::prefix('staff')->group(function () {

    Route::get('/beranda', [StaffController::class, 'beranda'])
        ->name('beranda.staff');

    Route::get('/laporan', [StaffController::class, 'laporan'])
        ->name('laporan.staff');

    Route::get('/tugas', [StaffController::class, 'tugas'])
        ->name('tugas.staff');

    Route::get('/tugas/{id}/detail', [StaffController::class, 'detailTugas'])
        ->name('tugas.detail');

    Route::post('/tugas/{id}/upload', [StaffController::class, 'uploadTugas'])
        ->name('tugas.upload');

    Route::post('/upload', [StaffController::class, 'uploadFile'])
        ->name('staff.upload');
});

/*
|--------------------------------------------------------------------------
| GLOBAL SETTINGS
|--------------------------------------------------------------------------
*/
Route::get('/settings', [SettingsController::class, 'index'])
    ->name('settings');

Route::get('/settings/password', [SettingsController::class, 'password'])
    ->name('settings.password');

Route::post('/settings/password', [SettingsController::class, 'updatePassword'])
    ->name('settings.password.update');



Route::get('/notification/read/{id}', [NotificationController::class, 'markAsReadAndRedirect'])->name('notification.read');

// Endpoint JSON untuk polling notifikasi real-time (dipanggil JS setiap 30 detik)
Route::get('/notifikasi/live', [NotificationController::class, 'liveNotifications'])->name('notification.live');

Route::post('/ai/summarize', [App\Http\Controllers\AiSummrizeController::class, 'summarize'])->name('ai.summarize');
Route::post('/ai/summarize-yearly', [App\Http\Controllers\AiSummrizeController::class, 'summarizeYearly'])->name('ai.summarize_yearly');

// Catatan Pribadi (Notepad) Sync Database
Route::get('/user/note', [App\Http\Controllers\NoteController::class, 'getNote'])->name('note.get');
Route::post('/user/note', [App\Http\Controllers\NoteController::class, 'saveNote'])->name('note.save');