<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|max:255',
            'nim'      => 'required|max:20|unique:users,nim',
            'prodi'    => 'required',
            'no_hp'    => ['required', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'role'     => 'required|in:penjual,pembeli,penjual_pembeli',
            'password' => 'required|min:8|confirmed',
        ], [
            'nim.unique'         => 'NIM sudah terdaftar, silakan masuk.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min'       => 'Password minimal 8 karakter.',
            'no_hp.regex'        => 'No WhatsApp hanya boleh berisi angka (9-20 digit).',
        ]);

        $data['nama']     = $request->nama;
        $data['nim']      = $request->nim;
        $data['prodi']    = $request->prodi;
        $data['no_hp']    = $this->rapikanNoHp($request->no_hp);
        $data['role']     = $request->role;
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil, silakan masuk.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nim'      => 'required',
            'password' => 'required',
        ]);

        $credentials['nim']      = $request->nim;
        $credentials['password'] = $request->password;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Akun yang diblokir admin tidak boleh masuk
            if (Auth::user()->diblokir_pada) {
                Auth::logout();
                return back()->withErrors(['nim' => 'Akunmu diblokir oleh admin.'])->onlyInput('nim');
            }

            $pesan = 'Selamat datang, ' . Auth::user()->nama . '!';

            // Admin ke Panel Admin
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', $pesan);
            }

            // Arahkan sesuai role
            if (Auth::user()->role == 'penjual_pembeli') {
                return redirect()->route('penjualpembeli.index')->with('success', $pesan);
            }
            if (Auth::user()->role == 'penjual') {
                return redirect()->route('penjual.index')->with('success', $pesan);
            }

            // Pembeli
            return redirect()->route('pembeli.index')->with('success', $pesan);
        }

        return back()->withErrors(['nim' => 'NIM atau password salah.'])->onlyInput('nim');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }

    /**
     * Rapikan No WhatsApp ke format 62xxxx (untuk link wa.me).
     * Contoh: 0812-3456-789 -> 628123456789
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
