@extends('layouts.app')

@section('title', 'Profil Saya - Marketplace Mahasiswa PCR')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-4xl">
        @if (session('success'))
            <div class="mb-6 rounded-md px-4 py-3 text-sm status-tersedia">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-md px-4 py-3 text-sm kotak-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-md px-4 py-3 text-sm kotak-error">{{ $errors->first() }}</div>
        @endif

        {{-- Kartu profil --}}
        <div class="rounded-xl border bg-card text-card-foreground shadow p-6 mb-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                @if ($dataUser->foto_profil)
                    <img src="{{ asset('storage/' . $dataUser->foto_profil) }}" alt="Foto profil" class="avatar avatar-besar">
                @else
                    <span class="avatar avatar-inisial avatar-besar">{{ $dataUser->inisial() }}</span>
                @endif
                <div class="flex-1 space-y-1 text-center sm:text-left">
                    <div class="text-2xl font-bold text-foreground">{{ $dataUser->nama }}</div>
                    <div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full status-menunggu">{{ $dataUser->labelRole() }}</span>
                        @if ($dataUser->role != 'admin')
                            @if ($dataUser->profil_publik)
                                <span class="text-xs font-semibold px-2 py-1 rounded-full status-selesai">Profil Publik</span>
                            @else
                                <span class="text-xs font-semibold px-2 py-1 rounded-full status-dibatalkan">Profil Privat</span>
                            @endif
                        @endif
                    </div>
                    <div class="text-muted-foreground pt-2">NIM {{ $dataUser->nim }} &bull; {{ $dataUser->prodi }}</div>
                    <div class="text-muted-foreground">WhatsApp: {{ $dataUser->no_hp ?? '-' }}</div>
                    <div class="text-muted-foreground">Bergabung {{ $dataUser->created_at->format('d M Y') }}</div>
                </div>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('profil.edit') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Edit Profil</a>
                    @if ($dataUser->role != 'admin')
                        <a href="{{ route('profil.publik', $dataUser->id) }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent hover:text-accent-foreground">Lihat Halaman Publik</a>
                    @endif
                </div>
            </div>
        </div>

        @if ($dataUser->role != 'admin')
            {{-- Statistik --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-xl border bg-card text-card-foreground shadow p-4">
                    <div class="text-xs font-semibold text-muted-foreground uppercase">Barang Dijual</div>
                    <div class="text-2xl font-bold text-foreground mt-1">{{ $jumlahBarang }}</div>
                </div>
                <div class="rounded-xl border bg-card text-card-foreground shadow p-4">
                    <div class="text-xs font-semibold text-muted-foreground uppercase">Transaksi Selesai</div>
                    <div class="text-2xl font-bold text-foreground mt-1">{{ $transaksiSelesai }}</div>
                </div>
                <div class="rounded-xl border bg-card text-card-foreground shadow p-4">
                <div class="text-xs font-semibold text-muted-foreground uppercase">Rating sebagai Penjual</div>
                <div class="text-2xl font-bold text-foreground mt-1">
                    @if ($ratingPenjual->count() > 0)
                        <span class="text-primary">&#9733;</span> {{ number_format($ratingPenjual->avg('bintang'), 1) }}
                        <span class="text-sm font-normal text-muted-foreground">({{ $ratingPenjual->count() }} ulasan)</span>
                    @else
                        <span class="text-sm font-normal text-muted-foreground">Belum ada rating</span>
                    @endif
                </div>
            </div>
                <div class="rounded-xl border bg-card text-card-foreground shadow p-4">
                <div class="text-xs font-semibold text-muted-foreground uppercase">Rating sebagai Pembeli</div>
                <div class="text-2xl font-bold text-foreground mt-1">
                    @if ($ratingPembeli->count() > 0)
                        <span class="text-primary">&#9733;</span> {{ number_format($ratingPembeli->avg('bintang'), 1) }}
                        <span class="text-sm font-normal text-muted-foreground">({{ $ratingPembeli->count() }} ulasan)</span>
                    @else
                        <span class="text-sm font-normal text-muted-foreground">Belum ada rating</span>
                    @endif
                </div>
            </div>
            </div>

        <h2 class="text-xl font-semibold text-foreground mb-4 mt-8">Ulasan sebagai Penjual ({{ $ratingPenjual->count() }})</h2>
        <div class="space-y-3">
            @forelse ($ratingPenjual as $item)
                <div class="rounded-xl border bg-card text-card-foreground shadow p-4">
                    <div class="flex items-center justify-between gap-2 text-sm">
                        <span class="text-primary">{{ str_repeat('★', $item->bintang) }}{{ str_repeat('☆', 5 - $item->bintang) }}</span>
                        <span class="text-xs text-muted-foreground">{{ $item->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="text-sm text-muted-foreground mt-1">dari {{ $item->pemberi->nama ?? '-' }}</div>
                    @if ($item->ulasan)
                        <div class="text-sm text-foreground mt-1">"{{ $item->ulasan }}"</div>
                    @endif
                </div>
            @empty
                <p class="text-muted-foreground">Belum ada ulasan.</p>
            @endforelse
        </div>
        <h2 class="text-xl font-semibold text-foreground mb-4 mt-8">Ulasan sebagai Pembeli ({{ $ratingPembeli->count() }})</h2>
        <div class="space-y-3">
            @forelse ($ratingPembeli as $item)
                <div class="rounded-xl border bg-card text-card-foreground shadow p-4">
                    <div class="flex items-center justify-between gap-2 text-sm">
                        <span class="text-primary">{{ str_repeat('★', $item->bintang) }}{{ str_repeat('☆', 5 - $item->bintang) }}</span>
                        <span class="text-xs text-muted-foreground">{{ $item->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="text-sm text-muted-foreground mt-1">dari {{ $item->pemberi->nama ?? '-' }}</div>
                    @if ($item->ulasan)
                        <div class="text-sm text-foreground mt-1">"{{ $item->ulasan }}"</div>
                    @endif
                </div>
            @empty
                <p class="text-muted-foreground">Belum ada ulasan.</p>
            @endforelse
        </div>
        @endif
    </div>
@endsection
