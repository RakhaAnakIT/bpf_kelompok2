@extends('layouts.app')

@section('title', $dataBarang->nama_barang . ' - Marketplace Mahasiswa PCR')

@section('content')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">


        @if (session('success'))
            <div class="mb-6 rounded-md px-4 py-3 text-sm status-tersedia">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-md px-4 py-3 text-sm kotak-error">{{ session('error') }}</div>
        @endif

        <nav class="mb-8">
            <a href="{{ route('pembeli.index') }}"
                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-sm text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:bg-accent h-9 px-4 py-2 text-muted-foreground hover:text-foreground flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-arrow-left h-4 w-4" aria-hidden="true">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </nav>

        <div class="grid lg:grid-cols-2 gap-12 mb-16">

            <div class="space-y-4">
                <div class="w-full max-w-[500px] mx-auto flex flex-col items-center px-4">
                    <div class="rounded-xl shadow-lg overflow-hidden mb-4 w-full">
                        <img alt="{{ $dataBarang->nama_barang }}" width="600" height="600"
                            class="rounded-xl object-cover w-full h-auto max-h-[500px]"
                            src="{{ $dataBarang->gambar ? asset('storage/' . $dataBarang->gambar) : asset('assets/img/NoImage.jpg') }}"
                            onerror="this.onerror=null;this.src='{{ asset('assets/img/NoImage.jpg') }}'" />
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <span class="text-xs font-semibold px-2 py-1 rounded-full status-{{ $dataBarang->status }}">
                        {{ ucfirst($dataBarang->status) }}
                    </span>
                    <h1 class="text-3xl lg:text-4xl font-bold text-foreground mt-4 mb-2">{{ $dataBarang->nama_barang }}</h1>
                    <span class="text-3xl font-bold text-foreground">Rp
                        {{ number_format($dataBarang->harga, 0, ',', '.') }}</span>
                </div>

                <div class="shrink-0 bg-border h-[1px] w-full"></div>

                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-muted-foreground">Kategori</div>
                        <div class="font-medium text-foreground">{{ $dataBarang->kategori_barang }}</div>
                    </div>
                    <div>
                        <div class="text-muted-foreground">Kondisi</div>
                        <div class="font-medium text-foreground">{{ $dataBarang->kondisi }}</div>
                    </div>
                    <div>
                        <div class="text-muted-foreground">Lokasi Serah Terima</div>
                        <div class="font-medium text-foreground">{{ $dataBarang->lokasi }}</div>
                    </div>
                    <div>
                        <div class="text-muted-foreground">Diunggah</div>
                        <div class="font-medium text-foreground">{{ $dataBarang->created_at->format('d M Y') }}</div>
                    </div>
                </div>

                <div>
                    <div class="text-sm text-muted-foreground mb-2">Deskripsi</div>
                    <p class="text-foreground leading-relaxed">
                        {!! $dataBarang->deskripsi ? nl2br(e($dataBarang->deskripsi)) : '-' !!}
                    </p>
                </div>

                <div class="flex items-center gap-3 rounded-xl border border-border p-4">
                    <div class="p-2 bg-primary/10 rounded-lg text-primary"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user h-4 w-4"
                            aria-hidden="true">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg></div>
                    <div>
                        <div class="text-sm text-muted-foreground">Penjual</div>
                        <div class="font-medium text-foreground">
                            {{ $dataBarang->user->nama }} &bull; {{ $dataBarang->user->prodi }} &bull;
                            @if ($dataBarang->user->ratingPenjual->count() > 0)
                                <span class="text-primary">&#9733;</span>
                                {{ number_format($dataBarang->user->ratingPenjual->avg('bintang'), 1) }}
                                <span class="text-muted-foreground">({{ $dataBarang->user->ratingPenjual->count() }})</span>
                            @else
                                <span class="text-muted-foreground">Belum ada rating</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="shrink-0 bg-border h-[1px] w-full"></div>

                
                    @if ($dataBarang->status == 'tersedia')
                        <a href="{{ route('pesanan.create', $dataBarang->pembeli_penjual_id) }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 shadow h-10 rounded-md px-8 flex-1 bg-primary text-primary-foreground hover:bg-primary/90 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag h-4 w-4" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg> Pesan Barang
                        </a>
                    @elseif ($pesananSaya)
                        <a href="{{ route('pesanan.pembelian') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 shadow h-10 rounded-md px-8 flex-1 border border-input bg-background hover:bg-accent hover:text-accent-foreground w-full">
                            Kamu sudah memesan barang ini &mdash; lihat Pesanan Saya
                        </a>
                    @else
                        <button type="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 shadow h-10 rounded-md px-8 flex-1 border border-input bg-background hover:bg-accent hover:text-accent-foreground w-full" disabled>
                            Barang sudah {{ $dataBarang->status }}
                        </button>
                    @endif
                
            </div>
        </div>
    </div>
@endsection