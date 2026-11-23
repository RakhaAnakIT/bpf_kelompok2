<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'notifikasi_id';
    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'link',
        'dibaca_pada',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public static function kirim($userId, $judul, $pesan, $link = null)
    {
        $data['user_id'] = $userId;
        $data['judul']   = $judul;
        $data['pesan']   = $pesan;
        $data['link']    = $link;

        Notifikasi::create($data);
    }
}
