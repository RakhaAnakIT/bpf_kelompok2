<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PenjualPembeli;
use App\Models\Pesanan;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilController extends Controller
{
    /**
     * Profil Saya (Read).
     */
    public function show()
    {
        $id = Auth::id();

        $data['dataUser']         = Auth::user();
        $data['jumlahBarang']     = PenjualPembeli::where('user_id', $id)->count();
        $data['transaksiSelesai'] = Pesanan::where('status', 'selesai')
            ->where(function ($q) use ($id) {
                $q->where('pembeli_id', $id)->orWhere('penjual_id', $id);
            })->count();
        $data['ratingPenjual'] = Rating::with('pemberi')->where('penerima_id', $id)->where('peran_penerima', 'penjual')->orderBy('rating_id', 'desc')->get();
        $data['ratingPembeli'] = Rating::with('pemberi')->where('penerima_id', $id)->where('peran_penerima', 'pembeli')->orderBy('rating_id', 'desc')->get();

        return view('Profil.show', $data);
    }

    /**
     * Form Edit Profil.
     */
    public function edit()
    {
        $data['dataUser'] = Auth::user();
        return view('Profil.edit', $data);
    }

    /**
     * Simpan data diri, foto profil, dan pilihan publik/privat (Update).
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama'        => 'required|max:255',
            'prodi'       => 'required',
            'no_hp'       => ['required', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'foto_profil' => 'nullable|image|max:2048',
        ], [
            'no_hp.regex'       => 'No WhatsApp hanya boleh berisi angka (9-20 digit).',
            'foto_profil.image' => 'Foto profil harus berupa gambar.',
            'foto_profil.max'   => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        $user = User::findOrFail(Auth::id());

        $user->nama          = $request->nama;
        $user->prodi         = $request->prodi;
        $user->no_hp         = $this->rapikanNoHp($request->no_hp);
        $user->profil_publik = $request->has('profil_publik');

        // Hapus foto lama kalau diminta, atau kalau diganti foto baru
        if ($request->has('hapus_foto') || $request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $user->foto_profil = null;
        }
        if ($request->hasFile('foto_profil')) {
            $user->foto_profil = $request->file('foto_profil')->store('profil', 'public');
        }

        $user->save();

        return redirect()->route('profil.show')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Ganti password (wajib memasukkan password lama).
     */
    public function ubahPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password'      => 'required|min:8|confirmed',
        ], [
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sama.',
        ]);

        $user = User::findOrFail(Auth::id());

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama salah.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diganti.');
    }

    /**
     * Ganti role sendiri (bukan untuk admin).
     */
    public function ubahRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:penjual,pembeli,penjual_pembeli',
        ]);

        $user = User::findOrFail(Auth::id());

        if ($user->role == 'admin') {
            return back()->with('error', 'Role admin tidak bisa diganti.');
        }

        // Berhenti menjual -> tidak boleh masih ada pesanan masuk yang berjalan
        if ($request->role == 'pembeli') {
            $masihJualan = Pesanan::where('penjual_id', $user->id)->whereIn('status', ['menunggu', 'dijadwalkan'])->exists();
            if ($masihJualan) {
                return back()->with('error', 'Selesaikan atau batalkan dulu pesanan masuk yang sedang berjalan.');
            }
        }

        // Berhenti membeli -> tidak boleh masih ada pesanan yang sedang dipesan
        if ($request->role == 'penjual') {
            $masihBelanja = Pesanan::where('pembeli_id', $user->id)->whereIn('status', ['menunggu', 'dijadwalkan'])->exists();
            if ($masihBelanja) {
                return back()->with('error', 'Selesaikan atau batalkan dulu pesanan yang sedang kamu pesan.');
            }
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->route('profil.show')->with('success', 'Role berhasil diganti menjadi ' . $user->labelRole() . '.');
    }

    /**
     * Hapus akun sendiri (Delete). Akun dianonimkan supaya riwayat pihak lain tetap utuh.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password_konfirmasi' => 'required',
        ], [
            'password_konfirmasi.required' => 'Masukkan password untuk menghapus akun.',
        ]);

        $user = User::findOrFail(Auth::id());

        if ($user->role == 'admin') {
            return back()->with('error', 'Akun admin tidak bisa dihapus.');
        }
        if (!Hash::check($request->password_konfirmasi, $user->password)) {
            return back()->with('error', 'Password salah. Akun tidak dihapus.');
        }

        $pesananAktif = Pesanan::whereIn('status', ['menunggu', 'dijadwalkan'])
            ->where(function ($q) use ($user) {
                $q->where('pembeli_id', $user->id)->orWhere('penjual_id', $user->id);
            })->exists();

        if ($pesananAktif) {
            return back()->with('error', 'Masih ada pesanan yang berjalan. Selesaikan atau batalkan dulu sebelum menghapus akun.');
        }

        // Hapus barang yang masih dijual (barang terjual tetap disimpan untuk riwayat)
        $barangTersedia = PenjualPembeli::where('user_id', $user->id)->where('status', 'tersedia')->get();
        foreach ($barangTersedia as $barang) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $barang->delete();
        }

        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // Anonimkan akun
        $user->nama          = 'Akun dihapus';
        $user->nim           = 'dihapus-' . $user->id;   // NIM asli bisa dipakai daftar lagi
        $user->no_hp         = null;
        $user->foto_profil   = null;
        $user->profil_publik = false;
        $user->password      = Hash::make(Str::random(40));
        $user->hapus_akun    = now();
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda')->with('success', 'Akun berhasil dihapus. Terima kasih sudah menggunakan MarketPCR.');
    }

    /**
     * Halaman publik profil user (bisa dilihat siapa saja).
     */
    public function halamanPublik(string $id)
    {
        $user = User::where('role', '!=', 'admin')->whereNull('hapus_akun')->findOrFail($id);

        $data['dataUser']      = $user;
        $data['ratingPenjual'] = Rating::with('pemberi')->where('penerima_id', $id)->where('peran_penerima', 'penjual')->orderBy('rating_id', 'desc')->get();
        $data['ratingPembeli'] = Rating::where('penerima_id', $id)->where('peran_penerima', 'pembeli')->get();
        $data['dataBarang']    = PenjualPembeli::where('user_id', $id)->where('status', 'tersedia')->latest()->get();

        // Detail (ulasan & barang) hanya tampil kalau profil publik, milik sendiri, atau dilihat admin
        $data['bolehLihat'] = $user->profil_publik
            || Auth::id() == $user->id
            || (Auth::check() && Auth::user()->role == 'admin');

        return view('Profil.halaman_publik', $data);
    }

    /**
     * Rapikan No WhatsApp ke format 62xxxx.
     */
    private function rapikanNoHp($noHp)
    {
        $noHp = preg_replace('/[^0-9]/', '', $noHp);

        if (substr($noHp, 0, 1) == '0') {
            $noHp = '62' . substr($noHp, 1);
        } elseif (substr($noHp, 0, 1) == '8') {
            $noHp = '62' . $noHp;
        }

        return $noHp;
    }
}
