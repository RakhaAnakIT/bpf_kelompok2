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
            'role'     => 'required|in:penjual,pembeli,penjual_pembeli',
            'password' => 'required|min:6|confirmed',
        ], [
            'nim.unique'         => 'NIM sudah terdaftar, silakan masuk.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $data['nama']     = $request->nama;
        $data['nim']      = $request->nim;
        $data['prodi']    = $request->prodi;
        $data['role']     = $request->role;
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil, silakan masuk.');
    }

    /**
     * Tampilkan form Masuk.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses Masuk dengan NIM dan Password.
     * Role dan prodi otomatis terbaca dari database.
     */
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

            return redirect()->route('penjualpembeli.index')
                ->with('success', 'Selamat datang, ' . Auth::user()->nama . '!');
        }

        return back()->withErrors(['nim' => 'NIM atau password salah.'])->onlyInput('nim');
    }

    /**
     * Keluar.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }
}
