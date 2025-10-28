<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AtasanController;

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

Route::get('/logins', [LoginController::class, 'index']);

Route::get('/', [AtasanController::class, 'landing'])->name('landing');
Route::get('/login', [AtasanController::class, 'showLogin'])->name('login');
Route::post('/login', [AtasanController::class, 'authenticate'])->name('login.post');

Route::get('/beranda', [AtasanController::class, 'beranda'])->name('beranda');

Route::get('/logout', [AtasanController::class, 'logout'])->name('logout');