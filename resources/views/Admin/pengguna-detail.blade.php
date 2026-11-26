@extends('layouts.app')

@section('title', 'Detail Pengguna - Admin MarketPCR')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <span class="text-xs font-semibold px-2 py-1 rounded-full status-menunggu">Panel Admin</span>
            <h1 class="text-3xl font-bold text-foreground mt-2">Detail Pengguna</h1>
            
        </div>
        <div class="flex gap-2 mb-8 overflow-x-auto">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent">Ringkasan</a>
            <a href="{{ route('admin.pengguna') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground">Pengguna</a>
            <a href="{{ route('admin.barang') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent">Barang</a>
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

    @php
        $labelStatus = ['menunggu' => 'Menunggu', 'dijadwalkan' => 'Dijadwalkan', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak', 'dibatalkan' => 'Dibatalkan'];
    @endphp

        <div class="rounded-xl border bg-card text-card-foreground shadow p-6 mb-8">
            <div class="grid lg:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <div class="text-2xl font-bold text-foreground">{{ $dataPengguna->nama }}</div>
                    <div class="text-muted-foreground">NIM {{ $dataPengguna->nim }} &bull; {{ $dataPengguna->prodi }}</div>
                    <div class="text-muted-foreground">WhatsApp: {{ $dataPengguna->no_hp ?? '-' }}</div>
                    <div class="text-muted-foreground">Bergabung {{ $dataPengguna->created_at->format('d M Y') }}</div>
                    <div class="pt-2">
                        @if ($dataPengguna->hapus_akun)
                            <span class="text-xs font-semibold px-2 py-1 rounded-full status-dibatalkan">Akun dihapus {{ $dataPengguna->hapus_akun->format('d M Y') }}</span>
                        @elseif ($dataPengguna->diblokir_pada)
                            <span class="text-xs font-semibold px-2 py-1 rounded-full status-dibatalkan">Diblokir sejak {{ $dataPengguna->diblokir_pada->format('d M Y, H:i') }}</span>
                        @else
                            <span class="text-xs font-semibold px-2 py-1 rounded-full status-selesai">Aktif</span>
                        @endif
                    </div>
                </div>
                <div class="space-y-4">
                    {{-- Ubah role --}}
                    <form action="{{ route('admin.pengguna.role', $dataPengguna->id) }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                        @csrf
                        <select name="role" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border">
                            <option value="penjual" {{ $dataPengguna->role == 'penjual' ? 'selected' : '' }}>Penjual</option>
                            <option value="pembeli" {{ $dataPengguna->role == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                            <option value="penjual_pembeli" {{ $dataPengguna->role == 'penjual_pembeli' ? 'selected' : '' }}>Penjual &amp; Pembeli</option>
                        </select>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Ubah Role</button>
                    </form>

                    {{-- Blokir / buka blokir --}}
                    @if ($dataPengguna->diblokir_pada)
                        <form action="{{ route('admin.pengguna.bukablokir', $dataPengguna->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent hover:text-accent-foreground">Buka Blokir</button>
                        </form>
                    @else
                        <form action="{{ route('admin.pengguna.blokir', $dataPengguna->id) }}" method="POST" class="flex flex-col sm:flex-row gap-2"
                            onsubmit="return confirm('Blokir akun ini? Pesanan yang sedang berjalan akan dibatalkan.')">
                            @csrf
                            <input type="text" name="alasan" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border" placeholder="Alasan blokir (wajib)" required>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-destructive text-destructive-foreground hover:bg-destructive/90">Blokir</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <h2 class="text-xl font-semibold text-foreground mb-4">Barang Miliknya ({{ $dataBarang->count() }})</h2>
        <div class="mb-8">
        <div class="rounded-xl border bg-card text-card-foreground shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr><th class="text-left text-muted-foreground font-medium px-4 py-3">Nama Barang</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Harga</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Status</th><th class="text-left text-muted-foreground font-medium px-4 py-3"></th></tr>
                </thead>
                <tbody>
                    @forelse ($dataBarang as $item)
                        <tr class="border-b border-border">
                            <td class="px-4 py-3">{{ $item->nama_barang }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full status-{{ $item->status }}">{{ ucfirst($item->status) }}</span></td>
                            <td class="px-4 py-3"><a href="{{ route('admin.barang.edit', $item->pembeli_penjual_id) }}" class="font-medium text-primary">Kelola</a></td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-3" colspan="4"><span class="text-muted-foreground">Belum ada barang.</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>

        <h2 class="text-xl font-semibold text-foreground mb-4">Pesanan sebagai Pembeli ({{ $pesananPembeli->count() }})</h2>
        <div class="mb-8">
        <div class="rounded-xl border bg-card text-card-foreground shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr><th class="text-left text-muted-foreground font-medium px-4 py-3">Barang</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Penjual</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Harga</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Status</th><th class="text-left text-muted-foreground font-medium px-4 py-3"></th></tr>
                </thead>
                <tbody>
                    @forelse ($pesananPembeli as $item)
                        <tr class="border-b border-border">
                            <td class="px-4 py-3">{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->penjual->nama }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full status-{{ $item->status }}">{{ $labelStatus[$item->status] }}</span></td>
                            <td class="px-4 py-3"><a href="{{ route('admin.pesanan.detail', $item->pesanan_id) }}" class="font-medium text-primary">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-3" colspan="5"><span class="text-muted-foreground">Belum ada.</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>

        <h2 class="text-xl font-semibold text-foreground mb-4">Pesanan sebagai Penjual ({{ $pesananPenjual->count() }})</h2>
        <div class="mb-8">
        <div class="rounded-xl border bg-card text-card-foreground shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr><th class="text-left text-muted-foreground font-medium px-4 py-3">Barang</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Pembeli</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Harga</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Status</th><th class="text-left text-muted-foreground font-medium px-4 py-3"></th></tr>
                </thead>
                <tbody>
                    @forelse ($pesananPenjual as $item)
                        <tr class="border-b border-border">
                            <td class="px-4 py-3">{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->pembeli->nama }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full status-{{ $item->status }}">{{ $labelStatus[$item->status] }}</span></td>
                            <td class="px-4 py-3"><a href="{{ route('admin.pesanan.detail', $item->pesanan_id) }}" class="font-medium text-primary">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-3" colspan="5"><span class="text-muted-foreground">Belum ada.</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>

        <h2 class="text-xl font-semibold text-foreground mb-4">Rating yang Diterima ({{ $ratingDiterima->count() }})</h2>
        <div class="space-y-3">
            @forelse ($ratingDiterima as $item)
                <div class="rounded-xl border bg-card text-card-foreground shadow p-4 flex items-start justify-between gap-4">
                    <div class="text-sm">
                        <span class="text-primary">{{ str_repeat('★', $item->bintang) }}{{ str_repeat('☆', 5 - $item->bintang) }}</span>
                        <span class="text-muted-foreground">sebagai {{ $item->peran_penerima }}, dari {{ $item->pemberi->nama ?? '-' }}</span>
                        @if ($item->ulasan) <div class="mt-1">"{{ $item->ulasan }}"</div> @endif
                    </div>
                    <form action="{{ route('admin.rating.hapus', $item->rating_id) }}" method="POST" onsubmit="return confirm('Hapus rating ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent hover:text-accent-foreground">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="text-muted-foreground">Belum ada rating.</p>
            @endforelse
        </div>
    </div>
@endsection
