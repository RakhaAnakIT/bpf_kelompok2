<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualPembeliController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('penjualpembeli', PenjualPembeliController::class);