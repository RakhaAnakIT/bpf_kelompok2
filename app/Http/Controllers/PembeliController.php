<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualPembeli;

class PembeliController extends Controller
{
    /**
     * Beranda Pembeli: semua barang yang sedang dijual.
     */
    public function index(Request $request)
    {
        $query = PenjualPembeli::latest();

        // Pencarian dari kotak "Cari barang..."
        if ($request->q) {
            $query->where('nama_barang', 'like', '%' . $request->q . '%');
        }

        $data['dataBarang'] = $query->get();
        return view('Pembeli.index', $data);
    }

    /**
     * Detail barang untuk Pembeli.
     */
    public function show(string $id)
    {
        $data['dataBarang'] = PenjualPembeli::with('user.ratingPenjual')->findOrFail($id);
        return view('Pembeli.show', $data);
    }
}
