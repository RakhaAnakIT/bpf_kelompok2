<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualPembeli;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenjualPembeliController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = PenjualPembeli::latest();

        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        if ($request->q) {
            $query->where('nama_barang', 'like', '%' . $request->q . '%');
        }

        $data['dataPenjualPembeli'] = $query->get();
        return view('PembeliPenjual.index', $data);
    }

    public function barangSaya()
    {
        $data['dataPenjualPembeli'] = PenjualPembeli::where('user_id', Auth::id())->latest()->get();
        return view('PembeliPenjual.barang-saya', $data);
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

        return redirect()->route('penjualpembeli.barangsaya')->with('success', 'Barang berhasil diunggah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['dataPenjualPembeli'] = PenjualPembeli::with('user.ratingPenjual')->findOrFail($id);
        return view('PembeliPenjual.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['dataPenjualPembeli'] = PenjualPembeli::where('user_id', Auth::id())->findOrFail($id);
        return view('PembeliPenjual.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $barang = PenjualPembeli::where('user_id', Auth::id())->findOrFail($id);

        $barang->nama_barang = $request->nama_barang;
        $barang->kategori_barang = $request->kategori_barang;
        $barang->harga = $request->harga;
        $barang->kondisi = $request->kondisi;
        $barang->lokasi = $request->lokasi;
        $barang->deskripsi = $request->deskripsi;
        $barang->status = $request->status;

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $barang->gambar = $request->file('gambar')->store('barang', 'public');
        }

        $barang->save();
        return redirect()->route('penjualpembeli.show', $barang->pembeli_penjual_id)->with('success', 'Perubahan Data Berhasil!');
    }

    public function destroy(string $id)
    {
        $barang = PenjualPembeli::where('user_id', Auth::id())->findOrFail($id);

        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();
        return redirect()->route('penjualpembeli.barangsaya')->with('success', 'Data berhasil dihapus');
    }
}
