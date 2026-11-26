@extends('layouts.app')

@section('title', $dataUser->nama . ' - Marketplace Mahasiswa PCR')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-4xl">
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
                    <div><span class="text-xs font-semibold px-2 py-1 rounded-full status-menunggu">{{ $dataUser->labelRole() }}</span></div>
                    <div class="text-muted-foreground pt-2">{{ $dataUser->prodi }}</div>
                    <div class="text-muted-foreground">Bergabung {{ $dataUser->created_at->format('d M Y') }}</div>
                </div>
                @if (auth()->id() == $dataUser->id)
                    <a href="{{ route('profil.edit') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent hover:text-accent-foreground">Edit Profil</a>
                @endif
            </div>
        </div>

        {{-- Rating selalu tampil, walaupun profil privat --}}
        <div class="grid sm:grid-cols-2 gap-4 mb-6">
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

        @if ($bolehLihat)
            @if (!$dataUser->profil_publik)
                <div class="mb-6 rounded-md px-4 py-3 text-sm status-menunggu">
                    Profil ini privat. Kamu bisa melihat detailnya karena {{ auth()->id() == $dataUser->id ? 'ini profilmu sendiri' : 'kamu admin' }}.
                </div>
            @endif

            {{-- Barang yang sedang dijual --}}
            <h2 class="text-xl font-semibold text-foreground mb-4">Barang yang Sedang Dijual ({{ $dataBarang->count() }})</h2>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">
                @forelse ($dataBarang as $item)
                    <a href="{{ route('beranda.show', $item->pembeli_penjual_id) }}" class="rounded-xl border bg-card text-card-foreground shadow overflow-hidden hover:shadow-lg transition-all">
                        <div class="aspect-square overflow-hidden bg-muted">
                            <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('assets/img/NoImage.jpg') }}"
                                alt="{{ $item->nama_barang }}" class="w-full h-full object-cover"
                                onerror="this.onerror=null;this.src='{{ asset('assets/img/NoImage.jpg') }}'">
                        </div>
                        <div class="p-4">
                            <div class="font-semibold text-foreground line-clamp-2">{{ $item->nama_barang }}</div>
                            <div class="font-bold text-foreground">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                        </div>
                    </a>
                @empty
                    <p class="text-muted-foreground">Tidak ada barang yang sedang dijual.</p>
                @endforelse
            </div>

        <h2 class="text-xl font-semibold text-foreground mb-4 ">Ulasan sebagai Penjual ({{ $ratingPenjual->count() }})</h2>
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
        @else
            <div class="rounded-xl border bg-card text-card-foreground shadow p-8 text-center">
                <div class="text-5xl mb-3">&#128274;</div>
                <h3 class="text-xl font-semibold text-foreground mb-1">Profil ini privat</h3>
                <p class="text-muted-foreground">Pemilik akun memilih untuk tidak menampilkan detail profilnya.</p>
            </div>
        @endif
    </div>
@endsection
