@extends('layouts.app')

@section('title', 'Konfirmasi Pesanan - Marketplace Mahasiswa PCR')

@section('content')
    <section class="py-16 lg:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto space-y-6">

        {{-- Pesan berhasil / gagal --}}
        @if (session('success'))
            <div class="mb-6 rounded-md px-4 py-3 text-sm status-tersedia">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-md px-4 py-3 text-sm kotak-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-md px-4 py-3 text-sm kotak-error">{{ $errors->first() }}</div>
        @endif

                {{-- Ringkasan barang --}}
                <div class="rounded-xl border bg-card text-card-foreground shadow p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <img src="{{ $dataBarang->gambar ? asset('storage/' . $dataBarang->gambar) : asset('assets/img/NoImage.jpg') }}"
                            alt="{{ $dataBarang->nama_barang }}" class="w-24 h-24 rounded-md object-cover border border-border"
                            onerror="this.onerror=null;this.src='{{ asset('assets/img/NoImage.jpg') }}'">
                        <div class="space-y-1">
                            <div class="text-sm text-muted-foreground">Barang yang dipesan</div>
                            <div class="text-xl font-bold text-foreground">{{ $dataBarang->nama_barang }}</div>
                            <div class="text-lg font-bold text-foreground">Rp {{ number_format($dataBarang->harga, 0, ',', '.') }}</div>
                            <div class="text-sm text-muted-foreground">
                                {{ $dataBarang->kategori_barang }} &bull; {{ $dataBarang->kondisi }} &bull;
                                Penjual: {{ $dataBarang->user->nama }} ({{ $dataBarang->user->prodi }})
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form konfirmasi --}}
                <div class="rounded-xl border bg-card text-card-foreground shadow">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <div class="tracking-tight text-2xl font-bold text-foreground">Konfirmasi Pesanan</div>
                        <p class="text-muted-foreground">
                            Tentukan rencana bertemu. Pembayaran dilakukan langsung saat bertemu, bukan lewat website.
                        </p>
                    </div>

                    <div class="p-6 pt-0">
                        <form action="{{ route('pesanan.store', $dataBarang->pembeli_penjual_id) }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label for="lokasi_ketemu" class="text-sm font-medium text-foreground">Lokasi Bertemu</label>
                                    <input type="text" id="lokasi_ketemu" name="lokasi_ketemu" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                        value="{{ old('lokasi_ketemu', $dataBarang->lokasi) }}" placeholder="Contoh: Kantin PCR" required>
                                </div>
                                <div class="space-y-2">
                                    <label for="waktu_ketemu" class="text-sm font-medium text-foreground">Tanggal &amp; Jam Bertemu</label>
                                    <input type="datetime-local" id="waktu_ketemu" name="waktu_ketemu" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                        value="{{ old('waktu_ketemu') }}" min="{{ now()->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="cara_bayar" class="text-sm font-medium text-foreground">Cara Bayar Saat Bertemu</label>
                                <select id="cara_bayar" name="cara_bayar" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" required>
                                    <option value="tunai" {{ old('cara_bayar') == 'tunai' ? 'selected' : '' }}>Tunai (bayar di tempat)</option>
                                    <option value="transfer" {{ old('cara_bayar') == 'transfer' ? 'selected' : '' }}>Transfer bank</option>
                                    <option value="ewallet" {{ old('cara_bayar') == 'ewallet' ? 'selected' : '' }}>E-wallet (Dana, OVO, GoPay, dll)</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="catatan" class="text-sm font-medium text-foreground">Catatan untuk Penjual (opsional)</label>
                                <textarea id="catatan" name="catatan" rows="3" class="flex min-h-[60px] w-full rounded-md border px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border resize-none"
                                    placeholder="Contoh: Saya pakai baju biru, bisa jam 1 siang?">{{ old('catatan') }}</textarea>
                            </div>

                            <div class="rounded-md px-4 py-3 text-sm status-menunggu">
                                Setelah dikirim, barang langsung <b>dikunci untukmu</b> (status: Dipesan) sampai penjual menerima atau menolak.
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Kirim Pesanan</button>
                                <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent hover:text-accent-foreground">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
