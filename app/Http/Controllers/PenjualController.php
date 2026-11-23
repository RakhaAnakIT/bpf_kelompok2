<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualPembeli;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenjualController extends Controller
{
    /**
     * Halaman utama Penjual: hanya barang miliknya sendiri (Barang Saya).
     */
    public function index()
    {
        $data['dataPenjualPembeli'] = PenjualPembeli::where('user_id', Auth::id())->latest()->get();
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

        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();
        return redirect()->route('penjual.index')->with('success', 'Data berhasil dihapus');
    }
}
