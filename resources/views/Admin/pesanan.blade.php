@extends('layouts.app')

@section('title', 'Pantau Pesanan - Admin MarketPCR')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <span class="text-xs font-semibold px-2 py-1 rounded-full status-menunggu">Panel Admin</span>
            <h1 class="text-3xl font-bold text-foreground mt-2">Pantau Pesanan</h1>
            
        </div>
        <div class="flex gap-2 mb-8 overflow-x-auto">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent">Ringkasan</a>
            <a href="{{ route('admin.pengguna') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent">Pengguna</a>
            <a href="{{ route('admin.barang') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent">Barang</a>
            <a href="{{ route('admin.pesanan') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground">Pesanan</a>
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

        <form action="{{ route('admin.pesanan') }}" method="GET" class="rounded-xl border bg-card text-card-foreground shadow p-4 mb-6 flex flex-col sm:flex-row gap-3">
            <select name="status" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border">
                <option value="">Semua status</option>
                <option value="macet" {{ request('status') == 'macet' ? 'selected' : '' }}>Macet (dijadwalkan &gt; 3 hari)</option>
                @foreach ($labelStatus as $kode => $label)
                    <option value="{{ $kode }}" {{ request('status') == $kode ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Terapkan</button>
        </form>

        <div class="rounded-xl border bg-card text-card-foreground shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr><th class="text-left text-muted-foreground font-medium px-4 py-3">#</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Barang</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Pembeli &rarr; Penjual</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Jadwal</th><th class="text-left text-muted-foreground font-medium px-4 py-3">Status</th><th class="text-left text-muted-foreground font-medium px-4 py-3"></th></tr>
                </thead>
                <tbody>
                    @forelse ($dataPesanan as $item)
                        <tr class="border-b border-border">
                            <td class="px-4 py-3">{{ $item->pesanan_id }}</td>
                            <td class="px-4 py-3">{{ $item->barang->nama_barang ?? '-' }}<div class="text-xs text-muted-foreground">Rp {{ number_format($item->harga, 0, ',', '.') }}</div></td>
                            <td class="px-4 py-3">{{ $item->pembeli->nama }} &rarr; {{ $item->penjual->nama }}</td>
                            <td class="px-4 py-3">{{ $item->waktu_ketemu->format('d M Y, H:i') }}<div class="text-xs text-muted-foreground">{{ $item->lokasi_ketemu }}</div></td>
                            <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full status-{{ $item->status }}">{{ $labelStatus[$item->status] }}</span></td>
                            <td class="px-4 py-3"><a href="{{ route('admin.pesanan.detail', $item->pesanan_id) }}" class="font-medium text-primary">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-3" colspan="6"><span class="text-muted-foreground">Tidak ada pesanan.</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
