<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Daftar notifikasi milik user yang login.
     */
    public function index()
    {
        $data['dataNotifikasi'] = Notifikasi::where('user_id', Auth::id())->orderBy('notifikasi_id', 'desc')->take(50)->get();
        return view('Notifikasi.index', $data);
    }

    /**
     * Buka notifikasi: tandai sudah dibaca, lalu pindah ke halaman tujuan.
     */
    public function buka(string $id)
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())->findOrFail($id);

        if (!$notifikasi->dibaca_pada) {
            $notifikasi->dibaca_pada = now();
            $notifikasi->save();
        }

        return redirect($notifikasi->link ?? route('notifikasi.index'));
    }

    /**
     * Tandai semua notifikasi sudah dibaca.
     */
    public function bacaSemua()
    {
        Notifikasi::where('user_id', Auth::id())->whereNull('dibaca_pada')->update(['dibaca_pada' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    /**
     * Jumlah notifikasi belum dibaca (dipakai JavaScript untuk memperbarui angka di lonceng).
     */
    public function jumlah()
    {
        $jumlah = Notifikasi::where('user_id', Auth::id())->whereNull('dibaca_pada')->count();
        return response()->json(['jumlah' => $jumlah]);
    }
}
