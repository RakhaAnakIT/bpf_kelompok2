@extends('layouts.app')

@section('title', 'Kelola Barang - Admin MarketPCR')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <span class="text-xs font-semibold px-2 py-1 rounded-full status-menunggu">Panel Admin</span>
            <h1 class="text-3xl font-bold text-foreground mt-2">Kelola Barang</h1>
            
        </div>
        <div class="flex gap-2 mb-8 overflow-x-auto">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent">Ringkasan</a>
            <a href="{{ route('admin.pengguna') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent">Pengguna</a>
            <a href="{{ route('admin.barang') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground">Barang</a>
            <a href="{{ route('admin.pesanan') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent">Pesanan</a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-md px-4 py-3 text-sm status-tersedia">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-md px-4 py-3 text-sm kotak-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-md px-4 py-3 text-sm kotak-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('admin.barang') }}" method="GET" class="rounded-xl border bg-card text-card-foreground shadow p-4 mb-6 grid sm:grid-cols-3 gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama barang" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border">
            <select name="status" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border">
                <option value="">Semua status</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipesan" {{ request('status') == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                <option value="terjual" {{ request('status') == 'terjual' ? 'selected' : '' }}>Terjual</option>
            </select>
            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Terapkan</button>
        </form>

        <div class="rounded-xl border bg-card text-card-foreground shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr><th class="text-left text-muted-foreground font-medium px-4 py-3">Barang</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Pemilik</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Harga</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Status</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($dataBarang as $item)
                        <tr class="border-b border-border">
                            <td class="px-4 py-3">
                                <a href="{{ route('beranda.show', $item->pembeli_penjual_id) }}" class="font-medium text-foreground hover:text-primary">{{ $item->nama_barang }}</a>
                                <div class="text-xs text-muted-foreground">{{ $item->kategori_barang }} &bull; {{ $item->kondisi }}</div>
                            </td>
                            <td class="px-4 py-3"><a href="{{ route('admin.pengguna.detail', $item->user_id) }}" class="text-primary">{{ $item->user->nama }}</a></td>
                            <td class="px-4 py-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full status-{{ $item->status }}">{{ ucfirst($item->status) }}</span></td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('admin.barang.edit', $item->pembeli_penjual_id) }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent hover:text-accent-foreground">Edit</a>
                                    <form action="{{ route('admin.barang.hapus', $item->pembeli_penjual_id) }}" method="POST" class="flex gap-2"
                                        onsubmit="return confirm('Hapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="text" name="alasan" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border" placeholder="Alasan hapus" required>
                                        <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-destructive text-destructive-foreground hover:bg-destructive/90">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-3" colspan="5"><span class="text-muted-foreground">Tidak ada barang.</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
