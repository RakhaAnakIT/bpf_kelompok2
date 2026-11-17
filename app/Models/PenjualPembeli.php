<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualPembeli extends Model
{
    protected $table = 'pembeli_penjual';
    protected $primaryKey = 'pembeli_penjual_id';
    protected $fillable = [
        'user_id',
        'nama_barang',
        'kategori_barang',
        'harga',
        'kondisi',
        'lokasi',
        'deskripsi',
        'gambar',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
