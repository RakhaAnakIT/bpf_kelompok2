<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama', 'nim', 'prodi', 'role', 'password'])]
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
}
