<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PenjualPembeli;
use App\Models\Pesanan;
use App\Models\Rating;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Ringkasan seluruh marketplace.
     */
    public function dashboard()
    {
        $data['jumlahUser']    = User::where('role', '!=', 'admin')->count();
        $data['userPerRole']   = User::where('role', '!=', 'admin')->get()->groupBy('role')->map->count();
        $data['userDiblokir']  = User::whereNotNull('diblokir_pada')->count();
        $data['barangPerStatus']  = PenjualPembeli::get()->groupBy('status')->map->count();
        $data['pesananPerStatus'] = Pesanan::get()->groupBy('status')->map->count();

        // Pesanan "macet": sudah dijadwalkan lebih dari 3 hari tapi belum selesai
        $data['pesananMacet'] = Pesanan::with('barang', 'pembeli', 'penjual')
            ->where('status', 'dijadwalkan')
            ->where('updated_at', '<', now()->subDays(3))
            ->get();

        $data['pesananTerbaru'] = Pesanan::with('barang', 'pembeli', 'penjual')->orderBy('pesanan_id', 'desc')->take(5)->get();
        $data['userTerbaru']    = User::where('role', '!=', 'admin')->orderBy('id', 'desc')->take(5)->get();

        return view('Admin.dashboard', $data);
    }

    // ===================== PENGGUNA =====================

    /**
     * Daftar semua pengguna (bisa dicari & difilter role).
     */
    public function pengguna(Request $request)
    {
        $query = User::where('role', '!=', 'admin')->orderBy('id', 'desc');

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->q . '%')->orWhere('nim', 'like', '%' . $request->q . '%');
            });
        }
        if ($request->role) {
            $query->where('role', $request->role);
        }
        if ($request->status == 'diblokir') {
            $query->whereNotNull('diblokir_pada');
        }

        $data['dataPengguna'] = $query->get();
        return view('Admin.pengguna', $data);
    }

    /**
     * Detail satu pengguna: barangnya, pesanannya (sebagai pembeli & penjual), dan ratingnya.
     */
    public function penggunaDetail(string $id)
    {
        $user = User::where('role', '!=', 'admin')->findOrFail($id);

        $data['dataPengguna']    = $user;
        $data['dataBarang']      = PenjualPembeli::where('user_id', $id)->latest()->get();
        $data['pesananPembeli']  = Pesanan::with('barang', 'penjual')->where('pembeli_id', $id)->orderBy('pesanan_id', 'desc')->get();
        $data['pesananPenjual']  = Pesanan::with('barang', 'pembeli')->where('penjual_id', $id)->orderBy('pesanan_id', 'desc')->get();
        $data['ratingDiterima']  = Rating::with('pemberi')->where('penerima_id', $id)->orderBy('rating_id', 'desc')->get();

        return view('Admin.pengguna-detail', $data);
    }

    /**
     * Ubah role pengguna.
     */
    public function ubahRole(Request $request, string $id)
    {
        $request->validate([
            'role' => 'required|in:penjual,pembeli,penjual_pembeli',
        ]);

        $user = User::where('role', '!=', 'admin')->findOrFail($id);
        $user->role = $request->role;
        $user->save();

        Notifikasi::kirim($user->id, 'Role akun diubah', 'Admin mengubah role akunmu menjadi ' . $user->labelRole() . '.');

        return back()->with('success', 'Role ' . $user->nama . ' diubah menjadi ' . $user->labelRole() . '.');
    }

    /**
     * Blokir pengguna. Pesanan yang masih berjalan otomatis dibatalkan.
     */
    public function blokir(Request $request, string $id)
    {
        $request->validate([
            'alasan' => 'required|max:255',
        ], [
            'alasan.required' => 'Alasan blokir wajib diisi.',
        ]);

        $user = User::where('role', '!=', 'admin')->findOrFail($id);
        $user->diblokir_pada = now();
        $user->save();

        // Batalkan semua pesanan berjalan yang melibatkan user ini
        $pesananAktif = Pesanan::whereIn('status', ['menunggu', 'dijadwalkan'])
            ->where(function ($q) use ($id) {
                $q->where('pembeli_id', $id)->orWhere('penjual_id', $id);
            })->get();

        foreach ($pesananAktif as $pesanan) {
            $this->batalkanOlehAdmin($pesanan, 'Akun ' . $user->nama . ' diblokir admin. Alasan: ' . $request->alasan);
        }

        return back()->with('success', $user->nama . ' diblokir. ' . $pesananAktif->count() . ' pesanan berjalan dibatalkan.');
    }

    /**
     * Buka blokir pengguna.
     */
    public function bukaBlokir(string $id)
    {
        $user = User::where('role', '!=', 'admin')->findOrFail($id);
        $user->diblokir_pada = null;
        $user->save();

        Notifikasi::kirim($user->id, 'Akun dibuka kembali', 'Admin membuka blokir akunmu. Kamu bisa menggunakan MarketPCR lagi.');

        return back()->with('success', 'Blokir ' . $user->nama . ' dibuka.');
    }

    /**
     * Hapus rating yang tidak pantas.
     */
    public function hapusRating(string $id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();

        Notifikasi::kirim($rating->pemberi_id, 'Rating dihapus admin', 'Rating/ulasan yang kamu berikan dihapus admin karena melanggar aturan.');

        return back()->with('success', 'Rating dihapus.');
    }

    // ===================== BARANG =====================

    /**
     * Semua barang dari semua pengguna.
     */
    public function barang(Request $request)
    {
        $query = PenjualPembeli::with('user')->latest();

        if ($request->q) {
            $query->where('nama_barang', 'like', '%' . $request->q . '%');
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data['dataBarang'] = $query->get();
        return view('Admin.barang', $data);
    }

    /**
     * Form edit barang milik siapa pun.
     */
    public function barangEdit(string $id)
    {
        $data['dataPenjualPembeli'] = PenjualPembeli::with('user')->findOrFail($id);
        return view('Admin.barang-edit', $data);
    }

    /**
     * Simpan perubahan barang (status tetap diatur otomatis oleh sistem).
     */
    public function barangUpdate(Request $request, string $id)
    {
        $barang = PenjualPembeli::findOrFail($id);

        $barang->nama_barang     = $request->nama_barang;
        $barang->kategori_barang = $request->kategori_barang;
        $barang->harga           = $request->harga;
        $barang->kondisi         = $request->kondisi;
        $barang->lokasi          = $request->lokasi;
        $barang->deskripsi       = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $barang->gambar = $request->file('gambar')->store('barang', 'public');
        }

        $barang->save();

        Notifikasi::kirim($barang->user_id, 'Barang diubah admin', 'Admin mengubah data barang "' . $barang->nama_barang . '".');

        return redirect()->route('admin.barang')->with('success', 'Barang "' . $barang->nama_barang . '" berhasil diubah.');
    }

    /**
     * Hapus barang milik siapa pun (alasan wajib). Pesanan berjalan dibatalkan dulu.
     */
    public function barangHapus(Request $request, string $id)
    {
        $request->validate([
            'alasan' => 'required|max:255',
        ], [
            'alasan.required' => 'Alasan hapus wajib diisi.',
        ]);

        $barang = PenjualPembeli::findOrFail($id);

        $pesananAktif = Pesanan::where('barang_id', $id)->whereIn('status', ['menunggu', 'dijadwalkan'])->get();
        foreach ($pesananAktif as $pesanan) {
            $this->batalkanOlehAdmin($pesanan, 'Barang dihapus admin. Alasan: ' . $request->alasan);
        }

        Notifikasi::kirim($barang->user_id, 'Barang dihapus admin', 'Barang "' . $barang->nama_barang . '" dihapus admin. Alasan: ' . $request->alasan);

        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }
        $barang->delete();

        return back()->with('success', 'Barang dihapus.');
    }

    // ===================== PESANAN =====================

    /**
     * Semua pesanan (bisa difilter status / macet).
     */
    public function pesanan(Request $request)
    {
        $query = Pesanan::with('barang', 'pembeli', 'penjual')->orderBy('pesanan_id', 'desc');

        if ($request->status == 'macet') {
            $query->where('status', 'dijadwalkan')->where('updated_at', '<', now()->subDays(3));
        } elseif ($request->status) {
            $query->where('status', $request->status);
        }

        $data['dataPesanan'] = $query->get();
        return view('Admin.pesanan', $data);
    }

    /**
     * Detail lengkap satu pesanan.
     */
    public function pesananDetail(string $id)
    {
        $data['dataPesanan'] = Pesanan::with('barang', 'pembeli', 'penjual', 'rating.pemberi')->findOrFail($id);
        return view('Admin.pesanan-detail', $data);
    }

    /**
     * Admin membatalkan pesanan bermasalah.
     */
    public function pesananBatal(Request $request, string $id)
    {
        $request->validate([
            'alasan' => 'required|max:255',
        ], [
            'alasan.required' => 'Alasan wajib diisi.',
        ]);

        $pesanan = Pesanan::whereIn('status', ['menunggu', 'dijadwalkan'])->findOrFail($id);
        $this->batalkanOlehAdmin($pesanan, $request->alasan);

        return back()->with('success', 'Pesanan dibatalkan. Barang kembali tersedia.');
    }

    /**
     * Admin menyelesaikan paksa pesanan (misalnya salah satu pihak lupa konfirmasi).
     */
    public function pesananSelesai(Request $request, string $id)
    {
        $request->validate([
            'alasan' => 'required|max:255',
        ], [
            'alasan.required' => 'Alasan wajib diisi.',
        ]);

        $pesanan = Pesanan::with('barang')->where('status', 'dijadwalkan')->findOrFail($id);

        $pesanan->status          = 'selesai';
        $pesanan->selesai_pembeli = true;
        $pesanan->selesai_penjual = true;
        $pesanan->catatan_admin   = 'Diselesaikan admin: ' . $request->alasan;
        $pesanan->save();

        $this->ubahStatusBarang($pesanan->barang_id, 'terjual');

        $pesan = 'Admin menyelesaikan transaksi "' . $pesanan->barang->nama_barang . '". Alasan: ' . $request->alasan;
        Notifikasi::kirim($pesanan->pembeli_id, 'Transaksi diselesaikan admin', $pesan, route('pesanan.pembelian'));
        Notifikasi::kirim($pesanan->penjual_id, 'Transaksi diselesaikan admin', $pesan, route('pesanan.penjualan'));

        return back()->with('success', 'Pesanan diselesaikan. Barang tercatat terjual.');
    }

    private function batalkanOlehAdmin($pesanan, $alasan)
    {
        $pesanan->status          = 'dibatalkan';
        $pesanan->alasan_batal    = $alasan;
        $pesanan->dibatalkan_oleh = Auth::id();
        $pesanan->save();

        $this->ubahStatusBarang($pesanan->barang_id, 'tersedia');

        $namaBarang = $pesanan->barang ? $pesanan->barang->nama_barang : 'barang';
        $pesan = 'Admin membatalkan pesanan "' . $namaBarang . '". Alasan: ' . $alasan;
        Notifikasi::kirim($pesanan->pembeli_id, 'Pesanan dibatalkan admin', $pesan, route('pesanan.pembelian'));
        Notifikasi::kirim($pesanan->penjual_id, 'Pesanan dibatalkan admin', $pesan, route('pesanan.penjualan'));
    }

    private function ubahStatusBarang($barangId, $status)
    {
        $barang = PenjualPembeli::find($barangId);
        if ($barang) {
            $barang->status = $status;
            $barang->save();
        }
    }
}
