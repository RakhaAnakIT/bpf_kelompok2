<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama', 'nim', 'prodi', 'no_hp', 'role', 'diblokir_pada', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'diblokir_pada' => 'datetime',
        ];
    }

    /**
     * Rating yang diterima user ini sebagai penjual.
     */
    public function ratingPenjual()
    {
        return $this->hasMany(Rating::class, 'penerima_id', 'id')->where('peran_penerima', 'penjual');
    }

    /**
     * Rating yang diterima user ini sebagai pembeli.
     */
    public function ratingPembeli()
    {
        return $this->hasMany(Rating::class, 'penerima_id', 'id')->where('peran_penerima', 'pembeli');
    }

    /**
     * Link chat WhatsApp ke user ini, dengan pesan yang sudah terisi.
     */
    public function linkWhatsapp($pesan)
    {
        return 'https://wa.me/' . $this->no_hp . '?text=' . urlencode($pesan);
    }

    /**
     * Nama role yang enak dibaca. Contoh: penjual_pembeli -> Penjual & Pembeli
     */
    public function labelRole()
    {
        $label = [
            'penjual'         => 'Penjual',
            'pembeli'         => 'Pembeli',
            'penjual_pembeli' => 'Penjual & Pembeli',
            'admin'           => 'Admin',
        ];

        return $label[$this->role] ?? $this->role;
    }
}
