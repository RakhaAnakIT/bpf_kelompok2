<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Marketplace Mahasiswa PCR')</title>
    <meta name="description" content="MARKETPCR - marketplace jual beli kebutuhan kuliah antar mahasiswa Politeknik Caltex Riau." />
    <link rel="stylesheet" href="{{ asset('css/bloom.css') }}" />
</head>

<body class="inter_7b064e0d-module__MOT0tq__className antialiased flex flex-col min-h-screen">
    @php
        // Halaman "Beranda" sesuai siapa yang membuka
        $isAdmin = auth()->check() && auth()->user()->role == 'admin';

        if ($isAdmin) {
            $home = route('admin.dashboard');
        } elseif (auth()->check() && auth()->user()->role == 'penjual_pembeli') {
            $home = route('penjualpembeli.index');
        } elseif (auth()->check() && auth()->user()->role == 'penjual') {
            $home = route('penjual.index');
        } elseif (auth()->check() && auth()->user()->role == 'pembeli') {
            $home = route('pembeli.index');
        } else {
            $home = route('beranda'); // tamu
        }

        // Tujuan kotak "Cari barang..." (admin mencari di Kelola Barang)
        $cari = $isAdmin ? route('admin.barang') : $home;

        // Link "Barang Saya" dan "Jual Barang" sesuai role
        if (auth()->check() && auth()->user()->role == 'penjual') {
            $barangSaya = route('penjual.index');
            $jualBarang = route('penjual.create');
        } else {
            $barangSaya = route('penjualpembeli.barangsaya');
            $jualBarang = route('penjualpembeli.create'); // tamu akan diarahkan ke Masuk
        }

        // Jumlah notifikasi yang belum dibaca
        $notifBaru = 0;
        if (auth()->check()) {
            $notifBaru = \App\Models\Notifikasi::where('user_id', auth()->id())->whereNull('dibaca_pada')->count();
        }

        // Jumlah pesanan baru yang menunggu diterima (untuk penjual)
        $pesananBaru = 0;
        if (auth()->check() && in_array(auth()->user()->role, ['penjual', 'penjual_pembeli'])) {
            $pesananBaru = \App\Models\Pesanan::where('penjual_id', auth()->id())->where('status', 'menunggu')->count();
        }
    @endphp
    <header
        class="sticky top-0 z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm"
        id="site-header">
        <div class="container mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-8 lg:space-x-12">
                    <a class="text-2xl tracking-tight text-gray-900 hover:text-gray-700 transition-colors"
                        aria-label="Beranda" href="{{ $home }}">MARKET<span
                            class="text-primary">PCR</span></a>
                    <nav class="hidden md:flex items-center space-x-1" role="navigation" aria-label="Main navigation">
                        @if (!auth()->check() || in_array(auth()->user()->role, ['pembeli', 'penjual_pembeli']))
                            <a class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('beranda', 'penjualpembeli.index', 'pembeli.*') ? 'bg-orange-100 shadow-md' : '' }}"
                                href="{{ $home }}">Beranda</a>
                        @endif
                        @auth
                            @if (auth()->user()->role == 'admin')
                                <a class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-100 shadow-md' : '' }}" href="{{ route('admin.dashboard') }}">Ringkasan</a>
                                <a class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('admin.pengguna*') ? 'bg-orange-100 shadow-md' : '' }}" href="{{ route('admin.pengguna') }}">Pengguna</a>
                                <a class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('admin.barang*') ? 'bg-orange-100 shadow-md' : '' }}" href="{{ route('admin.barang') }}">Barang</a>
                                <a class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('admin.pesanan*') ? 'bg-orange-100 shadow-md' : '' }}" href="{{ route('admin.pesanan') }}">Pesanan</a>
                                <a class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('beranda') ? 'bg-orange-100 shadow-md' : '' }}" href="{{ route('beranda') }}">Katalog</a>
                            @endif
                            @if (in_array(auth()->user()->role, ['penjual', 'penjual_pembeli']))
                                <a class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('penjualpembeli.barangsaya', 'penjual.*') ? 'bg-orange-100 shadow-md' : '' }}"
                                    href="{{ $barangSaya }}">Barang Saya</a>
                            @endif
                            @if (in_array(auth()->user()->role, ['pembeli', 'penjual_pembeli']))
                                <a class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('pesanan.pembelian') ? 'bg-orange-100 shadow-md' : '' }}"
                                    href="{{ route('pesanan.pembelian') }}">Pesanan Saya</a>
                            @endif
                            @if (in_array(auth()->user()->role, ['penjual', 'penjual_pembeli']))
                                <a class="relative py-2 px-4 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ request()->routeIs('pesanan.penjualan') ? 'bg-orange-100 shadow-md' : '' }}"
                                    href="{{ route('pesanan.penjualan') }}">Pesanan Masuk
                                    @if ($pesananBaru > 0)<span class="badge-angka">{{ $pesananBaru }}</span>@endif
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>
                <div class="hidden lg:flex flex-1 max-w-md mx-4 xl:mx-8">
                    <form class="relative w-full" action="{{ $cari }}" method="GET">
                        <input type="search" name="q" placeholder="Cari barang..."
                            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all"
                            aria-label="Cari barang" value="{{ request('q') }}" /><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"
                            aria-hidden="true">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg>
                    </form>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <button class="lg:hidden p-2 rounded-full hover:bg-gray-100 transition-colors" aria-label="Search"
                        id="btn-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search h-5 w-5 text-gray-700" aria-hidden="true">
                            <path d="m21 21-4.34-4.34"></path>
                            <circle cx="11" cy="11" r="8"></circle>
                        </svg></button><button class="md:hidden p-2 rounded-full hover:bg-gray-100 transition-colors"
                        aria-label="Toggle navigation menu" aria-expanded="true" id="btn-menu">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x h-6 w-6 text-gray-700" aria-hidden="true">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg></button>@if (!auth()->check() || in_array(auth()->user()->role, ['penjual', 'penjual_pembeli']))
                    <a href="{{ $jualBarang }}"
                        class="hidden sm:inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus h-4 w-4" aria-hidden="true">
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg>
                        Jual Barang
                    </a>
                    @endif
                    {{-- Lonceng notifikasi --}}
                    @auth
                        <a href="{{ route('notifikasi.index') }}" class="relative p-2 rounded-full hover:bg-gray-100 transition-colors" aria-label="Notifikasi">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell h-6 w-6 text-gray-700" aria-hidden="true"><path d="M10.268 21a2 2 0 0 0 3.464 0"></path><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path></svg>
                            <span id="angka-notif" class="badge-angka badge-lonceng" @if ($notifBaru == 0) style="display:none" @endif>{{ $notifBaru }}</span>
                        </a>
                    @endauth
                    <div class="hidden sm:flex items-center space-x-2">
                        @auth
                            {{-- Foto profil + nama: klik ke Profil Saya --}}
                            <a href="{{ route('profil.show') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-gray-100 transition-colors" title="Profil Saya">
                                @if (auth()->user()->foto_profil)
                                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Foto profil" class="avatar">
                                @else
                                    <span class="avatar avatar-inisial">{{ auth()->user()->inisial() }}</span>
                                @endif
                                <div class="hidden 2xl:block text-left leading-tight">
                                    <div class="text-sm font-medium text-foreground">{{ auth()->user()->nama }}</div>
                                    <div class="text-xs text-muted-foreground">{{ auth()->user()->labelRole() }}</div>
                                </div>
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium transition-colors hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-sm">Keluar</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium transition-colors hover:bg-accent hover:text-accent-foreground h-8 rounded-md px-3 text-sm">Masuk</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium transition-colors bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-sm">Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="lg:hidden mt-4 animate-in slide-in-from-top duration-200 hidden" id="mobile-search">
                <form class="relative" action="{{ $cari }}" method="GET">
                    <input name="q" placeholder="Cari barang..."
                        class="w-full pl-10 pr-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                        aria-label="Cari barang" type="search" value="{{ request('q') }}" /><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400"
                        aria-hidden="true">
                        <path d="m21 21-4.34-4.34"></path>
                        <circle cx="11" cy="11" r="8"></circle>
                    </svg>
                </form>
            </div>
            <nav class="md:hidden mt-4 animate-in slide-in-from-top duration-200 hidden" role="navigation"
                aria-label="Mobile navigation" id="mobile-menu">
                <div class="flex flex-col space-y-3 pb-4 border-b border-gray-200">
                    @if (!auth()->check() || in_array(auth()->user()->role, ['pembeli', 'penjual_pembeli']))
                        <a class="text-sm font-medium py-2 px-3 rounded-lg transition-all text-gray-700 hover:text-gray-900 hover:bg-gray-50" href="{{ $home }}">Beranda</a>
                    @endif
                    @auth
                        @if (auth()->user()->role == 'admin')
                                <a class="text-sm font-medium py-2 px-3 rounded-lg transition-all text-gray-700 hover:text-gray-900 hover:bg-gray-50" href="{{ route('admin.dashboard') }}">Ringkasan</a>
                                <a class="text-sm font-medium py-2 px-3 rounded-lg transition-all text-gray-700 hover:text-gray-900 hover:bg-gray-50" href="{{ route('admin.pengguna') }}">Pengguna</a>
                                <a class="text-sm font-medium py-2 px-3 rounded-lg transition-all text-gray-700 hover:text-gray-900 hover:bg-gray-50" href="{{ route('admin.barang') }}">Barang</a>
                                <a class="text-sm font-medium py-2 px-3 rounded-lg transition-all text-gray-700 hover:text-gray-900 hover:bg-gray-50" href="{{ route('admin.pesanan') }}">Pesanan</a>
                                <a class="text-sm font-medium py-2 px-3 rounded-lg transition-all text-gray-700 hover:text-gray-900 hover:bg-gray-50" href="{{ route('beranda') }}">Katalog</a>
                            @endif
                        @if (in_array(auth()->user()->role, ['penjual', 'penjual_pembeli']))
                            <a class="text-sm font-medium py-2 px-3 rounded-lg transition-all text-gray-700 hover:text-gray-900 hover:bg-gray-50" href="{{ $barangSaya }}">Barang Saya</a>
                        @endif
                        @if (in_array(auth()->user()->role, ['pembeli', 'penjual_pembeli']))
                            <a class="text-sm font-medium py-2 px-3 rounded-lg transition-all text-gray-700 hover:text-gray-900 hover:bg-gray-50" href="{{ route('pesanan.pembelian') }}">Pesanan Saya</a>
                        @endif
                        @if (in_array(auth()->user()->role, ['penjual', 'penjual_pembeli']))
                            <a class="text-sm font-medium py-2 px-3 rounded-lg transition-all text-gray-700 hover:text-gray-900 hover:bg-gray-50" href="{{ route('pesanan.penjualan') }}">Pesanan Masuk
                                @if ($pesananBaru > 0)<span class="badge-angka">{{ $pesananBaru }}</span>@endif
                            </a>
                        @endif
                    @endauth
                    @if (!auth()->check() || in_array(auth()->user()->role, ['penjual', 'penjual_pembeli']))
                        <a class="inline-flex items-center justify-center gap-2 inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-8 rounded-md px-3 text-sm"
                        href="{{ $jualBarang }}"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus h-4 w-4"
                            aria-hidden="true">
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg> Jual Barang</a>
                    @endif
                </div>
                <div class="flex flex-col space-y-3 pt-4 sm:hidden">
                    @auth
                        <a href="{{ route('profil.show') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50">
                            @if (auth()->user()->foto_profil)
                                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Foto profil" class="avatar">
                                @else
                                    <span class="avatar avatar-inisial">{{ auth()->user()->inisial() }}</span>
                                @endif
                            <span class="text-sm text-foreground">
                                {{ auth()->user()->nama }}
                                <span class="text-muted-foreground">({{ auth()->user()->labelRole() }})</span>
                            </span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm font-medium transition-colors border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-full text-sm">Keluar</button>
                        </form>
                    @else
                        <a class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm font-medium transition-colors border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-full text-sm" href="{{ route('login') }}">Masuk</a>
                        <a class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm font-medium transition-colors bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full text-sm" href="{{ route('register') }}">Daftar</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>
    <main class="flex-grow">
        @yield('content')
    </main>
    {{-- Template tampilan: BloomShop oleh Bloomtpl (github.com/bloomtpl), didistribusikan ThemeWagon, lisensi MIT --}}
    <footer class="bg-background border-t border-border">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-12 grid gap-8 md:grid-cols-2">
                {{-- Tentang MarketPCR --}}
                <div class="space-y-4">
                    <a href="{{ $home }}" class="text-2xl tracking-tight text-gray-900 hover:text-gray-700 transition-colors">MARKET<span class="text-primary">PCR</span></a>
                    <p class="text-muted-foreground max-w-md">
                        Cari buku, modul, kalkulator, jas lab, dan perlengkapan kuliah lainnya? Cuman di MARKETPCR Saja!
                        Sesama mahasiswa PCR udah pasti Trusted Loh!
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-4 w-4 text-primary" aria-hidden="true"> <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"> </path> <circle cx="12" cy="10" r="3"></circle> </svg>
                            <span>Jl. Umban Sari (Patin) No. 1, Rumbai, Pekanbaru, Riau 28265</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone h-4 w-4 text-primary" aria-hidden="true"> <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"> </path> </svg>
                            <span>+62 812-7654-3210</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail h-4 w-4 text-primary" aria-hidden="true"> <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path> <rect x="2" y="4" width="20" height="16" rx="2"></rect> </svg>
                            <span>marketpcr@pcr.ac.id</span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="https://github.com/RakhaAnakIT/bpf_kelompok2" target="_blank" rel="noopener noreferrer" aria-label="GitHub" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 w-10 rounded-full bg-muted hover:bg-primary hover:text-primary-foreground transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-github h-4 w-4" aria-hidden="true"> <path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"> </path> <path d="M9 18c-4.51 2-5-2-7-2"></path> </svg></a>
                        <a href="#" aria-label="Instagram" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 w-10 rounded-full bg-muted hover:bg-primary hover:text-primary-foreground transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram h-4 w-4" aria-hidden="true"> <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect> <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path> <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line> </svg></a>
                    </div>
                </div>

                {{-- Layanan Customer --}}
                <div class="md:justify-self-end">
                    <h4 class="text-sm font-semibold text-foreground mb-4 uppercase tracking-wider">Layanan Customer</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('kontak') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Kontak Kami</a></li>
                    </ul>
                </div>
            </div>

            <div class="py-6 border-t border-border text-sm text-muted-foreground">
                &copy; {{ date('Y') }} MARKETPCR. All Rights Reserved.
            </div>
        </div>
    </footer>
    <script src="{{ asset('assets/js/bloom.js') }}"></script>
    @auth
        <script>
            // Perbarui angka notifikasi setiap 30 detik tanpa perlu refresh halaman
            setInterval(function () {
                fetch('{{ route('notifikasi.jumlah') }}')
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        var angka = document.getElementById('angka-notif');
                        if (!angka) return;
                        angka.textContent = data.jumlah;
                        angka.style.display = data.jumlah > 0 ? 'inline-flex' : 'none';
                    })
                    .catch(function () {});
            }, 30000);
        </script>
    @endauth
</body>
</html> 