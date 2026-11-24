@extends('layouts.app')

@section('title', 'Detail Pesanan - Admin MarketPCR')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <span class="text-xs font-semibold px-2 py-1 rounded-full status-menunggu">Panel Admin</span>
            <h1 class="text-3xl font-bold text-foreground mt-2">Detail Pesanan</h1>
            
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

    @php
        $labelBayar = ['tunai' => 'Tunai', 'transfer' => 'Transfer bank', 'ewallet' => 'E-wallet'];
    @endphp
        <div class="rounded-xl border bg-card text-card-foreground shadow p-6 mb-6">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <div class="text-sm text-muted-foreground">Pesanan #{{ $dataPesanan->pesanan_id }} &bull; dibuat {{ $dataPesanan->created_at->format('d M Y, H:i') }}</div>
                    <div class="text-2xl font-bold text-foreground">{{ $dataPesanan->barang->nama_barang ?? '-' }}</div>
                    <div class="text-lg font-bold text-foreground">Rp {{ number_format($dataPesanan->harga, 0, ',', '.') }}</div>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-full status-{{ $dataPesanan->status }}">{{ $labelStatus[$dataPesanan->status] }}</span>
            </div>
            <div class="grid sm:grid-cols-2 gap-4 text-sm">
                <div><div class="text-muted-foreground">Pembeli</div><div class="font-medium text-foreground"><a href="{{ route('admin.pengguna.detail', $dataPesanan->pembeli_id) }}" class="text-primary">{{ $dataPesanan->pembeli->nama }}</a> ({{ $dataPesanan->pembeli->nim }})</div></div>
                <div><div class="text-muted-foreground">Penjual</div><div class="font-medium text-foreground"><a href="{{ route('admin.pengguna.detail', $dataPesanan->penjual_id) }}" class="text-primary">{{ $dataPesanan->penjual->nama }}</a> ({{ $dataPesanan->penjual->nim }})</div></div>
                <div><div class="text-muted-foreground">Lokasi bertemu</div><div class="font-medium text-foreground">{{ $dataPesanan->lokasi_ketemu }}</div></div>
                <div><div class="text-muted-foreground">Jadwal bertemu</div><div class="font-medium text-foreground">{{ $dataPesanan->waktu_ketemu->format('d M Y, H:i') }}</div></div>
                <div><div class="text-muted-foreground">Cara bayar</div><div class="font-medium text-foreground">{{ $labelBayar[$dataPesanan->cara_bayar] }}</div></div>
                <div><div class="text-muted-foreground">Konfirmasi selesai</div><div class="font-medium text-foreground">Pembeli: {{ $dataPesanan->selesai_pembeli ? 'Sudah' : 'Belum' }} &bull; Penjual: {{ $dataPesanan->selesai_penjual ? 'Sudah' : 'Belum' }}</div></div>
            </div>
            @if ($dataPesanan->catatan)
                <div class="mt-4 rounded-md px-3 py-2 text-sm bg-muted">Catatan pembeli: {{ $dataPesanan->catatan }}</div>
            @endif
            @if ($dataPesanan->alasan_batal)
                <div class="mt-4 text-sm text-muted-foreground">Alasan batal: {{ $dataPesanan->alasan_batal }}</div>
            @endif
            @if ($dataPesanan->catatan_admin)
                <div class="mt-4 rounded-md px-3 py-2 text-sm status-menunggu">Catatan admin: {{ $dataPesanan->catatan_admin }}</div>
            @endif
        </div>

        {{-- Tindakan admin --}}
        @if (in_array($dataPesanan->status, ['menunggu', 'dijadwalkan']))
            <div class="rounded-xl border bg-card text-card-foreground shadow p-6 mb-6 space-y-4">
                <h2 class="text-xl font-semibold text-foreground">Tindakan Admin</h2>
                <form action="{{ route('admin.pesanan.batal', $dataPesanan->pesanan_id) }}" method="POST" class="flex flex-col sm:flex-row gap-2"
                    onsubmit="return confirm('Batalkan pesanan ini? Barang akan kembali tersedia.')">
                    @csrf
                    <input type="text" name="alasan" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border" placeholder="Alasan membatalkan (wajib)" required>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-destructive text-destructive-foreground hover:bg-destructive/90">Batalkan Pesanan</button>
                </form>
                @if ($dataPesanan->status == 'dijadwalkan')
                    <form action="{{ route('admin.pesanan.selesai', $dataPesanan->pesanan_id) }}" method="POST" class="flex flex-col sm:flex-row gap-2"
                        onsubmit="return confirm('Selesaikan paksa pesanan ini? Barang akan tercatat terjual.')">
                        @csrf
                        <input type="text" name="alasan" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border" placeholder="Alasan menyelesaikan (wajib)" required>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Selesaikan Paksa</button>
                    </form>
                @endif
            </div>
        @endif

        {{-- Rating pada pesanan ini --}}
        @if ($dataPesanan->rating->count() > 0)
            <h2 class="text-xl font-semibold text-foreground mb-4">Rating</h2>
            <div class="space-y-3">
                @foreach ($dataPesanan->rating as $item)
                    <div class="rounded-xl border bg-card text-card-foreground shadow p-4 flex items-start justify-between gap-4">
                        <div class="text-sm">
                            <span class="text-primary">{{ str_repeat('★', $item->bintang) }}{{ str_repeat('☆', 5 - $item->bintang) }}</span>
                            <span class="text-muted-foreground">dari {{ $item->pemberi->nama ?? '-' }} untuk {{ $item->peran_penerima }}</span>
                            @if ($item->ulasan) <div class="mt-1">"{{ $item->ulasan }}"</div> @endif
                        </div>
                        <form action="{{ route('admin.rating.hapus', $item->rating_id) }}" method="POST" onsubmit="return confirm('Hapus rating ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent hover:text-accent-foreground">Hapus</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
