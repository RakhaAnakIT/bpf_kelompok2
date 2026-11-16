<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualPembeli;

class PenjualPembeliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dataPenjualPembeli'] = PenjualPembeli::latest()->get();
        return view('PembeliPenjual.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('PembeliPenjual.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        return redirect()->route('penjualpembeli.index')->with('success', 'Barang berhasil diunggah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['dataPenjualPembeli'] = PenjualPembeli::findOrFail($id);
        return view('PembeliPenjual.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barang = PenjualPembeli::findOrFail($id);

        $barang->nama_barang = $request->nama_barang;
        $barang->kategori_barang = $request->kategori_barang;
        $barang->harga = $request->harga;
        $barang->kondisi = $request->kondisi;
        $barang->lokasi = $request->lokasi;
        $barang->deskripsi = $request->deskripsi;
        $barang->status = $request->status;

        if ($request->hasFile('gambar')) {
            $barang->gambar = $request->file('gambar')->store('barang', 'public');
        }

        $barang->save();
        return redirect()->route('penjualpembeli.index')->with('success', 'Perubahan Data Berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barang = PenjualPembeli::findOrFail($id);

        $barang->delete();
        return redirect()->route('penjualpembeli.index')->with('success', 'Data berhasil dihapus');
    }
}
