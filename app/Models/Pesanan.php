<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'pesanan_id';
    protected $fillable = [
        'barang_id',
        'pembeli_id',
        'penjual_id',
        'harga',
        'lokasi_ketemu',
        'waktu_ketemu',
        'cara_bayar',
        'catatan',
        'status',
        'selesai_pembeli',
        'selesai_penjual',
        'alasan_batal',
        'dibatalkan_oleh',
    ];

    protected $casts = [
        'waktu_ketemu' => 'datetime',
    ];

    // Barang yang dipesan
    public function barang()
    {
        return $this->belongsTo(PenjualPembeli::class, 'barang_id', 'pembeli_penjual_id');
    }

    // User yang memesan
    public function pembeli()
    {
        return $this->belongsTo(User::class, 'pembeli_id', 'id');
    }

    // User pemilik barang
    public function penjual()
    {
        return $this->belongsTo(User::class, 'penjual_id', 'id');
    }
}
