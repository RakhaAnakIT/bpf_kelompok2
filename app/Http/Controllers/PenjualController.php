<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualPembeli;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenjualController extends Controller
{
    /**
     * Halaman utama Penjual: hanya barang miliknya sendiri (Barang Saya).
     */
    public function index(Request $request)
    {
        $query = PenjualPembeli::where('user_id', Auth::id())->latest();

        // Pencarian dari kotak "Cari barang..." (mencari di barang milik sendiri)
        if ($request->q) {
            $query->where('nama_barang', 'like', '%' . $request->q . '%');
        }

        $data['dataPenjualPembeli'] = $query->get();
        return view('Penjual.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Penjual.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data['user_id'] = Auth::id();
        $data['nama_barang'] = $request->nama_barang;
        $data['kategori_barang'] = $request->kategori_barang;
        $data['harga'] = $request->harga;
        $data['kondisi'] = $request->kondisi;
        $data['lokasi'] = $request->lokasi;
        $data['deskripsi'] = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        PenjualPembeli::create($data);

        return redirect()->route('penjual.index')->with('success', 'Barang berhasil diunggah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['dataPenjualPembeli'] = PenjualPembeli::with('user.ratingPenjual')->where('user_id', Auth::id())->findOrFail($id);
        return view('Penjual.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = PenjualPembeli::where('user_id', Auth::id())->findOrFail($id);

        // Barang yang sedang dipesan tidak boleh diubah/dihapus
        if ($barang->status == 'dipesan') {
            return redirect()->route('penjual.show', $id)->with('error', 'Barang sedang dipesan, tidak bisa diubah atau dihapus sampai pesanan selesai atau dibatalkan.');
        }

        $data['dataPenjualPembeli'] = $barang;
        return view('Penjual.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barang = PenjualPembeli::where('user_id', Auth::id())->findOrFail($id);

        // Barang yang sedang dipesan tidak boleh diubah/dihapus
        if ($barang->status == 'dipesan') {
            return redirect()->route('penjual.show', $id)->with('error', 'Barang sedang dipesan, tidak bisa diubah atau dihapus sampai pesanan selesai atau dibatalkan.');
        }

        $barang->nama_barang = $request->nama_barang;
        $barang->kategori_barang = $request->kategori_barang;
        $barang->harga = $request->harga;
        $barang->kondisi = $request->kondisi;
        $barang->lokasi = $request->lokasi;
        $barang->deskripsi = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $barang->gambar = $request->file('gambar')->store('barang', 'public');
        }

        $barang->save();
        return redirect()->route('penjual.show', $barang->pembeli_penjual_id)->with('success', 'Perubahan Data Berhasil!');
    }

    public function destroy(string $id)
    {
        $barang = PenjualPembeli::where('user_id', Auth::id())->findOrFail($id);

        // Barang yang sedang dipesan tidak boleh diubah/dihapus
        if ($barang->status == 'dipesan') {
            return redirect()->route('penjual.show', $id)->with('error', 'Barang sedang dipesan, tidak bisa diubah atau dihapus sampai pesanan selesai atau dibatalkan.');
        }

        // Barang yang sudah terjual tercatat di riwayat pembeli, jadi tidak boleh dihapus
        $pernahTerjual = Pesanan::where('barang_id', $id)->where('status', 'selesai')->exists();
        if ($pernahTerjual) {
            return redirect()->route('penjual.show', $id)->with('error', 'Barang ini sudah terjual dan tercatat di riwayat transaksi, jadi tidak bisa dihapus.');
        }

        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();
        return redirect()->route('penjual.index')->with('success', 'Data berhasil dihapus');
    }
}
