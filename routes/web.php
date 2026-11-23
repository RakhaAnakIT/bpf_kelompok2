<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PenjualPembeliController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\PembeliController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\AuthController;

// Beranda umum (tamu): halaman pertama saat website dibuka
Route::get('/marketpcr', [BerandaController::class, 'index'])->name('beranda');
Route::get('barang/{id}', [BerandaController::class, 'show'])->name('beranda.show');

Route::get('/', function () {
    return redirect()->route('beranda');
});

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
Route::middleware(['auth', 'role:penjual_pembeli'])->group(function () {
    Route::get('barang-saya', [PenjualPembeliController::class, 'barangSaya'])->name('penjualpembeli.barangsaya');
    Route::resource('penjualpembeli', PenjualPembeliController::class);
});

// Penjual
Route::middleware(['auth', 'role:penjual'])->group(function () {
    Route::resource('penjual', PenjualController::class);
});

// Pembeli
Route::middleware(['auth', 'role:pembeli'])->group(function () {
    Route::resource('pembeli', PembeliController::class)->only(['index', 'show']);
});

// Pesanan
Route::middleware('auth')->group(function () {
    // Yang bisa membeli: Pembeli dan Penjual & Pembeli
    Route::middleware('role:pembeli,penjual_pembeli')->group(function () {
        Route::get('barang/{id}/pesan', [PesananController::class, 'create'])->name('pesanan.create');
        Route::post('barang/{id}/pesan', [PesananController::class, 'store'])->name('pesanan.store');
        Route::get('pesanan-saya', [PesananController::class, 'pembelian'])->name('pesanan.pembelian');
    });

    // Yang bisa menjual: Penjual dan Penjual & Pembeli
    Route::middleware('role:penjual,penjual_pembeli')->group(function () {
        Route::get('pesanan-masuk', [PesananController::class, 'penjualan'])->name('pesanan.penjualan');
        Route::post('pesanan/{id}/terima', [PesananController::class, 'terima'])->name('pesanan.terima');
        Route::post('pesanan/{id}/tolak', [PesananController::class, 'tolak'])->name('pesanan.tolak');
    });

    // Pembeli atau penjual dari pesanan tersebut
    Route::post('pesanan/{id}/batal', [PesananController::class, 'batal'])->name('pesanan.batal');
    Route::post('pesanan/{id}/selesai', [PesananController::class, 'selesai'])->name('pesanan.selesai');
});

// Notifikasi (semua yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('notifikasi/jumlah', [NotifikasiController::class, 'jumlah'])->name('notifikasi.jumlah');
    Route::post('notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('notifikasi.bacasemua');
    Route::get('notifikasi/{id}', [NotifikasiController::class, 'buka'])->name('notifikasi.buka');
});
