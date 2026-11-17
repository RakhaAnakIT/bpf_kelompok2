<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualPembeliController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('penjualpembeli.index');
});

Route::middleware('guest')->group(function () {
    Route::get('daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('daftar', [AuthController::class, 'register'])->name('register.store');
    Route::get('masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('masuk', [AuthController::class, 'login'])->name('login.store');
});

Route::post('keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:penjual,penjual_pembeli'])->group(function () {
    Route::get('barang-saya', [PenjualPembeliController::class, 'barangSaya'])->name('penjualpembeli.barangsaya');
    Route::resource('penjualpembeli', PenjualPembeliController::class)->except(['index', 'show']);
});

Route::resource('penjualpembeli', PenjualPembeliController::class)->only(['index', 'show']);
