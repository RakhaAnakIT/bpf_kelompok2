@extends('layouts.app')

@section('title', 'Ringkasan Marketplace - Admin MarketPCR')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <span class="text-xs font-semibold px-2 py-1 rounded-full status-menunggu">Panel Admin</span>
            <h1 class="text-3xl font-bold text-foreground mt-2">Ringkasan Marketplace</h1>
            <p class="text-muted-foreground mt-2">Pantau semua yang terjadi di MarketPCR.</p>
        </div>
        <div class="flex gap-2 mb-8 overflow-x-auto">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground">Ringkasan</a>
            <a href="{{ route('admin.pengguna') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent">Pengguna</a>
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

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="rounded-xl border bg-card text-card-foreground shadow p-6">
                <div class="text-xs font-semibold text-muted-foreground uppercase">Pengguna</div>
                <div class="text-3xl font-bold text-foreground mt-2">{{ $jumlahUser }}</div>
                <div class="text-sm text-muted-foreground mt-1">Penjual {{ $userPerRole['penjual'] ?? 0 }} &bull; Pembeli {{ $userPerRole['pembeli'] ?? 0 }} &bull; P&amp;P {{ $userPerRole['penjual_pembeli'] ?? 0 }}</div>
            </div>
            <div class="rounded-xl border bg-card text-card-foreground shadow p-6">
                <div class="text-xs font-semibold text-muted-foreground uppercase">Barang</div>
                <div class="text-3xl font-bold text-foreground mt-2">{{ $barangPerStatus->sum() }}</div>
                <div class="text-sm text-muted-foreground mt-1">Tersedia {{ $barangPerStatus['tersedia'] ?? 0 }} &bull; Dipesan {{ $barangPerStatus['dipesan'] ?? 0 }} &bull; Terjual {{ $barangPerStatus['terjual'] ?? 0 }}</div>
            </div>
            <div class="rounded-xl border bg-card text-card-foreground shadow p-6">
                <div class="text-xs font-semibold text-muted-foreground uppercase">Pesanan Berjalan</div>
                <div class="text-3xl font-bold text-foreground mt-2">{{ ($pesananPerStatus['menunggu'] ?? 0) + ($pesananPerStatus['dijadwalkan'] ?? 0) }}</div>
                <div class="text-sm text-muted-foreground mt-1">Menunggu {{ $pesananPerStatus['menunggu'] ?? 0 }} &bull; Dijadwalkan {{ $pesananPerStatus['dijadwalkan'] ?? 0 }}</div>
            </div>
            <div class="rounded-xl border bg-card text-card-foreground shadow p-6">
                <div class="text-xs font-semibold text-muted-foreground uppercase">Akun Diblokir</div>
                <div class="text-3xl font-bold text-foreground mt-2">{{ $userDiblokir }}</div>
                <div class="text-sm text-muted-foreground mt-1">Pesanan selesai: {{ $pesananPerStatus['selesai'] ?? 0 }}</div>
            </div>
        </div>

        {{-- Pesanan macet --}}
        <div class="rounded-xl border bg-card text-card-foreground shadow p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-foreground">Pesanan Macet ({{ $pesananMacet->count() }})</h2>
                <a href="{{ route('admin.pesanan', ['status' => 'macet']) }}" class="text-sm font-medium text-primary">Lihat semua</a>
            </div>
            <p class="text-sm text-muted-foreground mb-4">Pesanan yang sudah dijadwalkan lebih dari 3 hari tetapi belum selesai.</p>
            @forelse ($pesananMacet as $item)
                <a href="{{ route('admin.pesanan.detail', $item->pesanan_id) }}" class="block border-t border-border py-3 hover:bg-gray-50">
                    <div class="font-medium text-foreground">{{ $item->barang->nama_barang ?? '-' }}</div>
                    <div class="text-sm text-muted-foreground">{{ $item->pembeli->nama }} &rarr; {{ $item->penjual->nama }} &bull; jadwal {{ $item->waktu_ketemu->format('d M Y, H:i') }}</div>
                </a>
            @empty
                <p class="text-sm text-muted-foreground">Tidak ada pesanan macet. 👍</p>
            @endforelse
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            <div class="rounded-xl border bg-card text-card-foreground shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-foreground">Pesanan Terbaru</h2>
                    <a href="{{ route('admin.pesanan') }}" class="text-sm font-medium text-primary">Lihat semua</a>
                </div>
                @forelse ($pesananTerbaru as $item)
                    <a href="{{ route('admin.pesanan.detail', $item->pesanan_id) }}" class="flex items-start justify-between gap-2 border-t border-border py-3 hover:bg-gray-50">
                        <div>
                            <div class="font-medium text-foreground">{{ $item->barang->nama_barang ?? '-' }}</div>
                            <div class="text-sm text-muted-foreground">{{ $item->pembeli->nama }} &rarr; {{ $item->penjual->nama }}</div>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full status-{{ $item->status }}">{{ $labelStatus[$item->status] }}</span>
                    </a>
                @empty
                    <p class="text-sm text-muted-foreground">Belum ada pesanan.</p>
                @endforelse
            </div>
            <div class="rounded-xl border bg-card text-card-foreground shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-foreground">Pengguna Baru</h2>
                    <a href="{{ route('admin.pengguna') }}" class="text-sm font-medium text-primary">Kelola</a>
                </div>
                @forelse ($userTerbaru as $item)
                    <a href="{{ route('admin.pengguna.detail', $item->id) }}" class="flex items-start justify-between gap-2 border-t border-border py-3 hover:bg-gray-50">
                        <div>
                            <div class="font-medium text-foreground">{{ $item->nama }}</div>
                            <div class="text-sm text-muted-foreground">{{ $item->nim }} &bull; {{ $item->prodi }}</div>
                        </div>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full status-menunggu">{{ $item->labelRole() }}</span>
                    </a>
                @empty
                    <p class="text-sm text-muted-foreground">Belum ada pengguna.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
