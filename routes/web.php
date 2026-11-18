<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PenjualPembeliController;
use App\Http\Controllers\AuthController;

// Beranda umum (tamu): halaman pertama saat website dibuka
Route::get('/marketpcr', [BerandaController::class, 'index'])->name('beranda');
Route::get('barang/{id}', [BerandaController::class, 'show'])->name('beranda.show');

// Daftar & Masuk (hanya untuk yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('daftar', [AuthController::class, 'register'])->name('register.store');
    Route::get('masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('masuk', [AuthController::class, 'login'])->name('login.store');
});

// Keluar (hanya untuk yang sudah login)
Route::post('keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Penjual & Pembeli
// (role penjual sementara ikut di sini untuk Barang Saya, sampai halaman /penjual dibuat)
Route::middleware(['auth', 'role:penjual,penjual_pembeli'])->group(function () {
    Route::get('barang-saya', [PenjualPembeliController::class, 'barangSaya'])->name('penjualpembeli.barangsaya');
    Route::resource('penjualpembeli', PenjualPembeliController::class);
});
