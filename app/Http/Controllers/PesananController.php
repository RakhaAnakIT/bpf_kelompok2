<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\PenjualPembeli;
use App\Models\Notifikasi;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    /**
     * Halaman Konfirmasi Pesanan (diisi pembeli sebelum memesan).
     */
    public function create(string $id)
    {
        $barang = PenjualPembeli::with('user')->findOrFail($id);

        if ($barang->user_id == Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa memesan barang milikmu sendiri.');
        }
        if ($barang->status != 'tersedia') {
            return back()->with('error', 'Barang ini sudah ' . $barang->status . '.');
        }
        if ($barang->user->diblokir_pada) {
            return back()->with('error', 'Penjual barang ini sedang diblokir admin.');
        }

        $data['dataBarang'] = $barang;
        return view('Pesanan.create', $data);
    }

    /**
     * Simpan pesanan + kunci barang (status barang otomatis menjadi "dipesan").
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'lokasi_ketemu' => 'required|max:100',
            'waktu_ketemu'  => 'required|date|after:now',
            'cara_bayar'    => 'required|in:tunai,transfer,ewallet',
            'catatan'       => 'nullable|max:300',
        ], [
            'waktu_ketemu.after' => 'Waktu bertemu harus setelah waktu sekarang.',
        ]);

        $barang = PenjualPembeli::with('user')->findOrFail($id);

        if ($barang->user->diblokir_pada) {
            return back()->with('error', 'Penjual barang ini sedang diblokir admin.');
        }

        // Kunci barang: hanya berhasil kalau barang masih "tersedia" dan bukan milik sendiri.
        // Kalau ada 2 pembeli memesan bersamaan, hanya 1 yang berhasil.
        $terkunci = PenjualPembeli::where('pembeli_penjual_id', $id)
            ->where('status', 'tersedia')
            ->where('user_id', '!=', Auth::id())
            ->update(['status' => 'dipesan']);

        if (!$terkunci) {
            return redirect()->route('pesanan.pembelian')->with('error', 'Maaf, barang ini sudah dipesan orang lain atau tidak bisa dipesan.');
        }

        $data['barang_id']     = $barang->pembeli_penjual_id;
        $data['pembeli_id']    = Auth::id();
        $data['penjual_id']    = $barang->user_id;
        $data['harga']         = $barang->harga;
        $data['lokasi_ketemu'] = $request->lokasi_ketemu;
        $data['waktu_ketemu']  = $request->waktu_ketemu;
        $data['cara_bayar']    = $request->cara_bayar;
        $data['catatan']       = $request->catatan;

        Pesanan::create($data);

        // Notifikasi ke penjual
        Notifikasi::kirim(
            $barang->user_id,
            'Pesanan baru',
            Auth::user()->nama . ' memesan "' . $barang->nama_barang . '". Segera terima atau tolak pesanan ini.',
            route('pesanan.penjualan')
        );

        return redirect()->route('pesanan.pembelian')->with('success', 'Pesanan terkirim! Tunggu penjual menerima pesananmu.');
    }

    /**
     * Pesanan Saya: barang yang dipesan user ini (sebagai pembeli).
     */
    public function pembelian()
    {
        $data['dataPesanan'] = Pesanan::with('barang', 'penjual.ratingPenjual', 'rating')->where('pembeli_id', Auth::id())->latest()->get();
        return view('Pesanan.pembelian', $data);
    }

    /**
     * Pesanan Masuk: pesanan untuk barang milik user ini (sebagai penjual).
     */
    public function penjualan()
    {
        $data['dataPesanan'] = Pesanan::with('barang', 'pembeli.ratingPembeli', 'rating')->where('penjual_id', Auth::id())->latest()->get();
        return view('Pesanan.penjualan', $data);
    }

    /**
     * Penjual menerima pesanan.
     */
    public function terima(string $id)
    {
        $pesanan = Pesanan::where('penjual_id', Auth::id())->where('status', 'menunggu')->findOrFail($id);

        $pesanan->status = 'dijadwalkan';
        $pesanan->save();

        // Notifikasi ke pembeli
        Notifikasi::kirim(
            $pesanan->pembeli_id,
            'Pesanan diterima',
            Auth::user()->nama . ' menerima pesananmu untuk "' . $pesanan->barang->nama_barang . '". Hubungi penjual via WhatsApp untuk memastikan jadwal bertemu.',
            route('pesanan.pembelian')
        );

        return back()->with('success', 'Pesanan diterima. Silakan hubungi pembeli untuk memastikan jadwal bertemu.');
    }

    /**
     * Penjual menolak pesanan -> barang kembali tersedia.
     */
    public function tolak(string $id)
    {
        $pesanan = Pesanan::where('penjual_id', Auth::id())->where('status', 'menunggu')->findOrFail($id);

        $pesanan->status = 'ditolak';
        $pesanan->save();

        // Notifikasi ke pembeli
        Notifikasi::kirim(
            $pesanan->pembeli_id,
            'Pesanan ditolak',
            Auth::user()->nama . ' menolak pesananmu untuk "' . $pesanan->barang->nama_barang . '".',
            route('pesanan.pembelian')
        );

        $this->ubahStatusBarang($pesanan->barang_id, 'tersedia');

        return back()->with('success', 'Pesanan ditolak. Barang kembali tersedia.');
    }

    /**
     * Pembeli atau penjual membatalkan pesanan (alasan wajib) -> barang kembali tersedia.
     */
    public function batal(Request $request, string $id)
    {
        $request->validate([
            'alasan_batal' => 'required|max:255',
        ], [
            'alasan_batal.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $pesanan = $this->pesananMilikSaya($id);

        if (!in_array($pesanan->status, ['menunggu', 'dijadwalkan'])) {
            return back()->with('error', 'Pesanan ini sudah tidak bisa dibatalkan.');
        }

        $pesanan->status          = 'dibatalkan';
        $pesanan->alasan_batal    = $request->alasan_batal;
        $pesanan->dibatalkan_oleh = Auth::id();
        $pesanan->save();

        // Notifikasi ke pihak lainnya
        $this->kirimKePihakLain(
            $pesanan,
            'Pesanan dibatalkan',
            Auth::user()->nama . ' membatalkan pesanan "' . $pesanan->barang->nama_barang . '". Alasan: ' . $request->alasan_batal
        );

        $this->ubahStatusBarang($pesanan->barang_id, 'tersedia');

        return back()->with('success', 'Pesanan dibatalkan. Barang kembali tersedia.');
    }

    /**
     * Konfirmasi "Transaksi Selesai". Kalau pembeli DAN penjual sudah konfirmasi,
     * pesanan selesai dan barang otomatis "terjual".
     */
    public function selesai(string $id)
    {
        $pesanan = $this->pesananMilikSaya($id);

        if ($pesanan->status != 'dijadwalkan') {
            return back()->with('error', 'Pesanan ini belum bisa diselesaikan.');
        }

        if ($pesanan->pembeli_id == Auth::id()) {
            $pesanan->selesai_pembeli = true;
        } else {
            $pesanan->selesai_penjual = true;
        }

        if ($pesanan->selesai_pembeli && $pesanan->selesai_penjual) {
            $pesanan->status = 'selesai';
            $this->ubahStatusBarang($pesanan->barang_id, 'terjual');
            $pesan = 'Transaksi selesai! Barang sudah terjual.';

            // Notifikasi ke kedua pihak
            Notifikasi::kirim($pesanan->pembeli_id, 'Transaksi selesai', 'Transaksi "' . $pesanan->barang->nama_barang . '" sudah selesai. Jangan lupa beri rating untuk penjual.', route('pesanan.pembelian'));
            Notifikasi::kirim($pesanan->penjual_id, 'Transaksi selesai', 'Transaksi "' . $pesanan->barang->nama_barang . '" sudah selesai. Jangan lupa beri rating untuk pembeli.', route('pesanan.penjualan'));
        } else {
            $pesan = 'Konfirmasimu tersimpan. Menunggu konfirmasi dari pihak lain.';

            // Notifikasi ke pihak lainnya
            $this->kirimKePihakLain(
                $pesanan,
                'Menunggu konfirmasimu',
                Auth::user()->nama . ' sudah mengonfirmasi transaksi "' . $pesanan->barang->nama_barang . '" selesai. Konfirmasi juga kalau sudah beres.'
            );
        }

        $pesanan->save();

        return back()->with('success', $pesan);
    }

    /**
     * Beri rating setelah pesanan selesai.
     * Pembeli menilai penjual, penjual menilai pembeli. Masing-masing hanya 1x per pesanan.
     */
    public function rating(Request $request, string $id)
    {
        $request->validate([
            'bintang' => 'required|integer|min:1|max:5',
            'ulasan'  => 'nullable|max:300',
        ], [
            'bintang.required' => 'Pilih jumlah bintang terlebih dahulu.',
        ]);

        $pesanan = $this->pesananMilikSaya($id);

        if ($pesanan->status != 'selesai') {
            return back()->with('error', 'Rating hanya bisa diberikan setelah transaksi selesai.');
        }

        $sudahRating = Rating::where('pesanan_id', $pesanan->pesanan_id)->where('pemberi_id', Auth::id())->exists();
        if ($sudahRating) {
            return back()->with('error', 'Kamu sudah memberi rating untuk pesanan ini.');
        }

        // Kalau saya pembeli -> yang dinilai penjual, dan sebaliknya
        if ($pesanan->pembeli_id == Auth::id()) {
            $penerimaId    = $pesanan->penjual_id;
            $peranPenerima = 'penjual';
            $link          = route('pesanan.penjualan');
        } else {
            $penerimaId    = $pesanan->pembeli_id;
            $peranPenerima = 'pembeli';
            $link          = route('pesanan.pembelian');
        }

        $data['pemberi_id']     = Auth::id();
        $data['penerima_id']    = $penerimaId;
        $data['peran_penerima'] = $peranPenerima;
        $data['bintang']        = $request->bintang;
        $data['ulasan']         = $request->ulasan;
        $data['pesanan_id']     = $pesanan->pesanan_id;

        Rating::create($data);

        // Notifikasi ke yang dinilai
        Notifikasi::kirim(
            $penerimaId,
            'Kamu mendapat rating',
            Auth::user()->nama . ' memberimu ' . $request->bintang . ' bintang untuk transaksi "' . $pesanan->barang->nama_barang . '".',
            $link
        );

        return back()->with('success', 'Terima kasih, rating berhasil dikirim!');
    }

    /**
     * Ambil pesanan di mana user ini adalah pembeli atau penjualnya.
     */
    private function pesananMilikSaya(string $id)
    {
        return Pesanan::where(function ($query) {
            $query->where('pembeli_id', Auth::id())->orWhere('penjual_id', Auth::id());
        })->findOrFail($id);
    }

    /**
     * Kirim notifikasi ke pihak lain dari pesanan (kalau saya pembeli -> ke penjual, dan sebaliknya).
     */
    private function kirimKePihakLain($pesanan, $judul, $pesan)
    {
        if ($pesanan->pembeli_id == Auth::id()) {
            Notifikasi::kirim($pesanan->penjual_id, $judul, $pesan, route('pesanan.penjualan'));
        } else {
            Notifikasi::kirim($pesanan->pembeli_id, $judul, $pesan, route('pesanan.pembelian'));
        }
    }

    /**
     * Ubah status barang.
     */
    private function ubahStatusBarang($barangId, $status)
    {
        $barang = PenjualPembeli::find($barangId);
        if ($barang) {
            $barang->status = $status;
            $barang->save();
        }
    }
}
