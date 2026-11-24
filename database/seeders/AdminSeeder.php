<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('nim', 'admin')->first();

        if (!$admin) {
            $data['nama']     = 'Admin MarketPCR';
            $data['nim']      = 'admin';
            $data['prodi']    = 'Pengelola MarketPCR';
            $data['role']     = 'admin';
            $data['password'] = Hash::make('admin12345');

            User::create($data);
        }
    }
}
