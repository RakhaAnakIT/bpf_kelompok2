<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'rating';
    protected $primaryKey = 'rating_id';
    protected $fillable = [
        'pemberi_id',
        'penerima_id',
        'peran_penerima',
        'bintang',
        'ulasan',
        'pesanan_id',
    ];

    // User yang memberi rating
    public function pemberi()
    {
        return $this->belongsTo(User::class, 'pemberi_id', 'id');
    }

    // User yang menerima rating
    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id', 'id');
    }
}
