<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PastikanAkunAktif
{
    /**
     * Kalau akun yang sedang login diblokir admin, langsung dikeluarkan.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->diblokir_pada) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akunmu diblokir oleh admin. Hubungi admin MarketPCR untuk informasi lebih lanjut.');
        }

        if (Auth::check() && Auth::user()->hapus_akun) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun ini sudah dihapus.');
        }

        return $next($request);
    }
}
