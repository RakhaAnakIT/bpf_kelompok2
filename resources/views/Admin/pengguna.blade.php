@extends('layouts.app')

@section('title', 'Kelola Pengguna - Admin MarketPCR')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <span class="text-xs font-semibold px-2 py-1 rounded-full status-menunggu">Panel Admin</span>
            <h1 class="text-3xl font-bold text-foreground mt-2">Kelola Pengguna</h1>
            
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

        <form action="{{ route('admin.pengguna') }}" method="GET" class="rounded-xl border bg-card text-card-foreground shadow p-4 mb-6 grid sm:grid-cols-4 gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIM" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border">
            <select name="role" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border">
                <option value="">Semua role</option>
                <option value="penjual" {{ request('role') == 'penjual' ? 'selected' : '' }}>Penjual</option>
                <option value="pembeli" {{ request('role') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                <option value="penjual_pembeli" {{ request('role') == 'penjual_pembeli' ? 'selected' : '' }}>Penjual &amp; Pembeli</option>
            </select>
            <select name="status" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring md:text-sm bg-background border-border">
                <option value="">Semua status</option>
                <option value="diblokir" {{ request('status') == 'diblokir' ? 'selected' : '' }}>Diblokir</option>
            </select>
            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Terapkan</button>
        </form>

        <div class="rounded-xl border bg-card text-card-foreground shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border">
                    <tr>
                        <th class="text-left text-muted-foreground font-medium px-4 py-3">Nama</th>
                        <th class="text-left text-muted-foreground font-medium px-4 py-3">NIM</th>
                        <th class="text-left text-muted-foreground font-medium px-4 py-3">Prodi</th>
                        <th class="text-left text-muted-foreground font-medium px-4 py-3">Role</th>
                        <th class="text-left text-muted-foreground font-medium px-4 py-3">Status</th>
                        <th class="text-left text-muted-foreground font-medium px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dataPengguna as $item)
                        <tr class="border-b border-border">
                            <td class="px-4 py-3"><span class="font-medium text-foreground">{{ $item->nama }}</span></td>
                            <td class="px-4 py-3">{{ $item->nim }}</td>
                            <td class="px-4 py-3">{{ $item->prodi }}</td>
                            <td class="px-4 py-3">{{ $item->labelRole() }}</td>
                            <td class="px-4 py-3">
                                @if ($item->hapus_akun)
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full status-dibatalkan">Akun dihapus</span>
                                @elseif ($item->diblokir_pada)
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full status-dibatalkan">Diblokir</span>
                                @else
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full status-selesai">Aktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3"><a href="{{ route('admin.pengguna.detail', $item->id) }}" class="font-medium text-primary">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-3" colspan="6"><span class="text-muted-foreground">Tidak ada pengguna.</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
