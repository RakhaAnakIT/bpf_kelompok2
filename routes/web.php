<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualPembeliController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('penjualpembeli', PenjualPembeliController::class);

Route::middleware('guest')->group(function () {
    Route::get('daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('daftar', [AuthController::class, 'register'])->name('register.store');
    Route::get('masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('masuk', [AuthController::class, 'login'])->name('login.store');
});

// Keluar (hanya untuk yang sudah login)
Route::post('keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::resource('penjualpembeli', PenjualPembeliController::class);
