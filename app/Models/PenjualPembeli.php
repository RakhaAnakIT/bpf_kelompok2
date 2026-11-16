<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualPembeli extends Model
{
    protected $table = 'pembeli_penjual';
    protected $primaryKey = 'pembeli_penjual_id';
    protected $fillable = [
        'nama_barang',
        'kategori_barang',
        'harga',
        'kondisi',
        'lokasi',
        'deskripsi',
        'gambar',
        'status',
    ];
}
