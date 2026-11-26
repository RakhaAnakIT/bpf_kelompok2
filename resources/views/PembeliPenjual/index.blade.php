@extends('layouts.app')

@section('title', 'Beranda - Marketplace Mahasiswa PCR')

@section('content')
    <div class="bg-background px-4 py-8 sm:py-12 lg:py-16 lg:px-8 min-h-screen">
        @if (session('success'))
            <div class="max-w-7xl mx-auto mb-6 rounded-md px-4 py-3 text-sm status-tersedia">{{ session('success') }}</div>
        @endif
        <div class="text-center mx-auto mb-18 space-y-3">
            <h1
                class="text-primary leading-tighter text-4xl font-semibold tracking-tight text-balance lg:leading-[1.1] lg:font-semibold xl:text-5xl xl:tracking-tighter">
                Kebutuhan Kuliah, dari Mahasiswa untuk Mahasiswa
            </h1>
            <p class="text-foreground text-base max-w-3xl mx-auto text-balance sm:text-lg">
                Cari buku, modul, kalkulator, jas lab, dan perlengkapan kuliah lainnya? Cuman di MARKETPCR Saja! Sesama mahasiswa PCR udah pasti Trusted Loh!
            </p>
        </div>
        {{-- Hasil pencarian --}}
        @if (request('q'))
            <div class="max-w-7xl mx-auto mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-2 rounded-md px-4 py-3 text-sm bg-muted">
                <span>Hasil pencarian "<b>{{ request('q') }}</b>": {{ $dataPenjualPembeli->count() }} barang</span>
                <a href="{{ url()->current() }}" class="font-medium text-primary">&times; Hapus pencarian</a>
            </div>
        @endif
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 max-w-7xl mx-auto">
            @forelse ($dataPenjualPembeli as $item)
                <div
                    class="rounded-xl border text-card-foreground shadow group overflow-hidden bg-card border-border hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="relative overflow-hidden">
                        <button
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:text-accent-foreground h-9 w-9 absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-all duration-200 bg-background/80 backdrop-blur-sm hover:bg-background"
                            name="Like Button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-heart h-4 w-4" aria-hidden="true" name="Like Icon">
                                <path
                                    d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5">
                                </path>
                            </svg></button><a class="block relative" href="{{ route('penjualpembeli.show', $item->pembeli_penjual_id) }}">
                            <div class="aspect-square overflow-hidden bg-muted">
                                <img alt="{{ $item->nama_barang }}" loading="lazy" width="400" height="400"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('assets/img/NoImage.jpg') }}"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/img/NoImage.jpg') }}'" />
                            </div>
                            <span
                                class="absolute top-3 left-3 z-10 text-xs font-semibold px-2 py-1 rounded-full status-{{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>
                            <div
                                class="absolute inset-0 bg-background/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                                <button
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 shadow h-8 rounded-md px-3 text-xs bg-primary text-primary-foreground hover:bg-primary/90">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-eye h-4 w-4 mr-2" aria-hidden="true">
                                        <path
                                            d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                        </path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>Lihat Detail
                                </button>
                            </div>
                        </a>
                    </div>
                    <div class="p-4 space-y-3">
                        <a href="{{ route('penjualpembeli.show', $item->pembeli_penjual_id) }}">
                            <h2 class="font-semibold text-foreground line-clamp-2 hover:text-primary transition-colors">
                                {{ $item->nama_barang }}
                            </h2>
                        </a>
                        <p class="text-sm text-muted-foreground">{{ $item->kategori_barang }} &bull; {{ $item->kondisi }} &bull;
                            {{ $item->lokasi }}</p>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold text-foreground">Rp
                                {{ number_format($item->harga, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('penjualpembeli.show', $item->pembeli_penjual_id) }}"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 shadow h-9 px-4 py-2 w-full transition-all duration-300 bg-primary text-primary-foreground hover:bg-primary/90">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-eye h-4 w-4" aria-hidden="true">
                                <path
                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                </path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg> Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                    <div class="text-6xl mb-4">📦</div>
                    <h3 class="text-xl font-semibold text-foreground mb-2">Belum ada barang</h3>
                    <p class="text-muted-foreground mb-4">Jadilah mahasiswa yang pertama menjual barang kuliahmu!</p>
                    @if (!auth()->check() || auth()->user()->role != 'pembeli')
                    <a href="{{ route('penjualpembeli.create') }}"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 shadow h-9 px-4 py-2  transition-all duration-300 bg-primary text-primary-foreground hover:bg-primary/90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus h-4 w-4" aria-hidden="true">
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg> Jual Barang
                    </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
@endsection