<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualPembeli;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        
        if (Auth::check() && Auth::user()->role == 'penjual_pembeli') {
            return redirect()->route('penjualpembeli.index');
        }
        if (Auth::check() && Auth::user()->role == 'penjual') {
            return redirect()->route('penjual.index');
        }
        if (Auth::check() && Auth::user()->role == 'pembeli') {
            return redirect()->route('pembeli.index');
        }

        // Barang milik akun yang diblokir admin tidak ditampilkan
        $query = PenjualPembeli::latest()->whereHas('user', function ($q) {
            $q->whereNull('diblokir_pada')
                ->whereNull('hapus_akun')
                ->whereIn('role', ['penjual', 'penjual_pembeli']); // pemilik yang sudah ganti role jadi Pembeli disembunyikan
        });

        if ($request->q) {
            $query->where('nama_barang', 'like', '%' . $request->q . '%');
        }

        $data['dataBarang'] = $query->get();
        return view('Beranda.index', $data);
    }

    public function show(string $id)
    {
        // Penjual & Pembeli dan Penjual memakai halaman detail miliknya sendiri
        if (Auth::check() && Auth::user()->role == 'penjual_pembeli') {
            return redirect()->route('penjualpembeli.show', $id);
        }
        if (Auth::check() && Auth::user()->role == 'pembeli') {
            return redirect()->route('pembeli.show', $id);
        }
        if (Auth::check() && Auth::user()->role == 'penjual') {
            // Penjual hanya boleh melihat barang miliknya sendiri
            $milikSendiri = PenjualPembeli::where('user_id', Auth::id())->find($id);
            if ($milikSendiri) {
                return redirect()->route('penjual.show', $id);
            }
            return redirect()->route('penjual.index');
        }

        $data['dataBarang'] = PenjualPembeli::with('user.ratingPenjual')->findOrFail($id);
        return view('Beranda.show', $data);
    }
}
