<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualPembeli;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class PembeliController extends Controller
{
    /**
     * Beranda Pembeli: semua barang yang sedang dijual.
     */
    public function index(Request $request)
    {
        // Barang milik akun yang diblokir admin tidak ditampilkan
        $query = PenjualPembeli::latest()->whereHas('user', function ($q) {
            $q->whereNull('diblokir_pada');
        });

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

        // Apakah user ini sedang memesan barang ini?
        $data['pesananSaya'] = Pesanan::where('barang_id', $id)
            ->where('pembeli_id', Auth::id())
            ->whereIn('status', ['menunggu', 'dijadwalkan'])
            ->first();

        return view('Pembeli.show', $data);
    }
}
