@extends('layouts.app')

@section('title', 'Pesanan Masuk - Marketplace Mahasiswa PCR')

@section('content')
    @php
        $labelStatus = [
            'menunggu'    => 'Menunggu penjual',
            'dijadwalkan' => 'Dijadwalkan',
            'selesai'     => 'Selesai',
            'ditolak'     => 'Ditolak',
            'dibatalkan'  => 'Dibatalkan',
        ];
        $labelBayar = [
            'tunai'    => 'Tunai',
            'transfer' => 'Transfer bank',
            'ewallet'  => 'E-wallet',
        ];
        $berjalan = $dataPesanan->whereIn('status', ['menunggu', 'dijadwalkan']);
        $riwayat  = $dataPesanan->whereIn('status', ['selesai', 'ditolak', 'dibatalkan']);
    @endphp

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-4xl mx-auto">

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

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-foreground">Pesanan Masuk</h1>
                <p class="text-muted-foreground mt-2">Pesanan dari pembeli untuk barang yang kamu jual.</p>
            </div>

            {{-- Sedang berjalan --}}
            <h2 class="text-xl font-semibold text-foreground mb-4">Sedang Berjalan ({{ $berjalan->count() }})</h2>
            <div class="space-y-4 mb-12">
                @forelse ($berjalan as $item)
                <div class="rounded-xl border bg-card text-card-foreground shadow p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <img src="{{ $item->barang->gambar ? asset('storage/' . $item->barang->gambar) : asset('assets/img/NoImage.jpg') }}"
                            alt="{{ $item->barang->nama_barang }}" class="w-24 h-24 rounded-md object-cover border border-border"
                            onerror="this.onerror=null;this.src='{{ asset('assets/img/NoImage.jpg') }}'">

                        <div class="flex-1 space-y-3">
                            {{-- Nama barang & status --}}
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-semibold text-foreground">{{ $item->barang->nama_barang }}</div>
                                    <div class="text-lg font-bold text-foreground">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full status-{{ $item->status }}">
                                    {{ $labelStatus[$item->status] }}
                                </span>
                            </div>

                            {{-- Rincian --}}
                            <div class="grid sm:grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="text-muted-foreground">Pembeli:</span> {{ $item->pembeli->nama }} ({{ $item->pembeli->prodi }})
                                    @if ($item->pembeli->ratingPembeli->count() > 0)
                                        &bull; <span class="text-primary">&#9733;</span> {{ number_format($item->pembeli->ratingPembeli->avg('bintang'), 1) }}
                                        <span class="text-muted-foreground">({{ $item->pembeli->ratingPembeli->count() }})</span>
                                    @endif
                                </div>
                                <div><span class="text-muted-foreground">Cara bayar:</span> {{ $labelBayar[$item->cara_bayar] }}</div>
                                <div><span class="text-muted-foreground">Lokasi:</span> {{ $item->lokasi_ketemu }}</div>
                                <div><span class="text-muted-foreground">Waktu:</span> {{ $item->waktu_ketemu->format('d M Y, H:i') }}</div>
                            </div>

                            @if ($item->catatan)
                                <div class="rounded-md px-3 py-2 text-sm bg-muted">Catatan: {{ $item->catatan }}</div>
                            @endif

                            @if ($item->status == 'dibatalkan')
                                <div class="text-sm text-muted-foreground">
                                    Dibatalkan oleh {{ $item->dibatalkan_oleh == auth()->id() ? 'kamu' : ($item->dibatalkan_oleh == $item->pembeli_id ? 'pembeli' : 'Admin') }}.
                                    Alasan: {{ $item->alasan_batal }}
                                </div>
                            @endif

                            @if ($item->catatan_admin)
                                <div class="rounded-md px-3 py-2 text-sm status-menunggu">Catatan admin: {{ $item->catatan_admin }}</div>
                            @endif

                            {{-- Rating (setelah transaksi selesai) --}}
                            @if ($item->status == 'selesai')
                                @php
                                    $ratingSaya  = $item->rating->where('pemberi_id', auth()->id())->first();
                                    $ratingLawan = $item->rating->where('pemberi_id', '!=', auth()->id())->first();
                                @endphp
                                <div class="rounded-md border border-border p-4 space-y-3">
                                    @if ($ratingSaya)
                                        <div class="text-sm">
                                            <span class="text-muted-foreground">Rating kamu untuk pembeli:</span>
                                            <span class="text-primary">{{ str_repeat('★', $ratingSaya->bintang) }}{{ str_repeat('☆', 5 - $ratingSaya->bintang) }}</span>
                                            @if ($ratingSaya->ulasan) &mdash; "{{ $ratingSaya->ulasan }}" @endif
                                        </div>
                                    @else
                                        <form action="{{ route('pesanan.rating', $item->pesanan_id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            <div class="text-sm font-medium text-foreground">Beri rating untuk pembeli {{ $item->pembeli->nama }}</div>
                                            <div class="bintang-input">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <input type="radio" id="bintang-{{ $item->pesanan_id }}-{{ $i }}" name="bintang" value="{{ $i }}">
                                                    <label for="bintang-{{ $item->pesanan_id }}-{{ $i }}" title="{{ $i }} bintang">&#9733;</label>
                                                @endfor
                                            </div>
                                            <textarea name="ulasan" rows="2" class="flex min-h-[60px] w-full rounded-md border px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border resize-none" placeholder="Ulasan singkat (opsional)"></textarea>
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Kirim Rating</button>
                                        </form>
                                    @endif

                                    @if ($ratingLawan)
                                        <div class="text-sm">
                                            <span class="text-muted-foreground">Rating dari pembeli untukmu:</span>
                                            <span class="text-primary">{{ str_repeat('★', $ratingLawan->bintang) }}{{ str_repeat('☆', 5 - $ratingLawan->bintang) }}</span>
                                            @if ($ratingLawan->ulasan) &mdash; "{{ $ratingLawan->ulasan }}" @endif
                                        </div>
                                    @else
                                        <div class="text-sm text-muted-foreground">Pembeli belum memberi rating.</div>
                                    @endif
                                </div>
                            @endif

                            {{-- Info konfirmasi selesai --}}
                            @if ($item->status == 'dijadwalkan' && $item->selesai_penjual)
                                <div class="text-sm text-muted-foreground">Kamu sudah konfirmasi selesai. Menunggu konfirmasi pembeli.</div>
                            @elseif ($item->status == 'dijadwalkan' && $item->selesai_pembeli)
                                <div class="text-sm text-muted-foreground">Pembeli sudah konfirmasi selesai. Giliranmu!</div>
                            @endif

                            {{-- Tombol aksi --}}
                            @if (in_array($item->status, ['menunggu', 'dijadwalkan']))
                                <div class="flex flex-col sm:flex-row gap-2">
                                    @if (in_array($item->status, ['menunggu', 'dijadwalkan']) && $item->pembeli->no_hp)
                                        <a href="{{ $item->pembeli->linkWhatsapp('Halo ' . $item->pembeli->nama . ', saya ' . auth()->user()->nama . ' (penjual) dari MarketPCR. Soal pesanan ' . $item->barang->nama_barang . ' (Rp ' . number_format($item->harga, 0, ',', '.') . '), rencana ketemu di ' . $item->lokasi_ketemu . ', ' . $item->waktu_ketemu->format('d M Y H:i') . '.') }}" target="_blank" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 tombol-wa">Hubungi Pembeli via WhatsApp</a>
                                    @endif

                                    @if ($item->status == 'menunggu')
                                        <form action="{{ route('pesanan.terima', $item->pesanan_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Terima Pesanan</button>
                                        </form>
                                        <form action="{{ route('pesanan.tolak', $item->pesanan_id) }}" method="POST"
                                            onsubmit="return confirm('Tolak pesanan ini? Barang akan kembali tersedia.')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent hover:text-accent-foreground">Tolak</button>
                                        </form>
                                    @endif
                                    @if ($item->status == 'dijadwalkan' && !$item->selesai_penjual)
                                        <form action="{{ route('pesanan.selesai', $item->pesanan_id) }}" method="POST"
                                            onsubmit="return confirm('Sudah bertemu, barang sesuai, dan pembayaran beres?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Transaksi Selesai</button>
                                        </form>
                                    @endif
                                </div>

                                {{-- Batalkan (alasan wajib) --}}
                                <form action="{{ route('pesanan.batal', $item->pesanan_id) }}" method="POST" class="flex flex-col sm:flex-row gap-2"
                                    onsubmit="return confirm('Yakin membatalkan pesanan ini?')">
                                    @csrf
                                    <input type="text" name="alasan_batal" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" placeholder="Alasan batal (wajib diisi)" required>
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 bg-destructive text-destructive-foreground hover:bg-destructive/90">Batalkan</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-muted-foreground">Belum ada pesanan masuk.</p>
                @endforelse
            </div>

            {{-- Riwayat --}}
            <h2 class="text-xl font-semibold text-foreground mb-4">Riwayat ({{ $riwayat->count() }})</h2>
            <div class="space-y-4">
                @forelse ($riwayat as $item)
                <div class="rounded-xl border bg-card text-card-foreground shadow p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <img src="{{ $item->barang->gambar ? asset('storage/' . $item->barang->gambar) : asset('assets/img/NoImage.jpg') }}"
                            alt="{{ $item->barang->nama_barang }}" class="w-24 h-24 rounded-md object-cover border border-border"
                            onerror="this.onerror=null;this.src='{{ asset('assets/img/NoImage.jpg') }}'">

                        <div class="flex-1 space-y-3">
                            {{-- Nama barang & status --}}
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-semibold text-foreground">{{ $item->barang->nama_barang }}</div>
                                    <div class="text-lg font-bold text-foreground">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                </div>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full status-{{ $item->status }}">
                                    {{ $labelStatus[$item->status] }}
                                </span>
                            </div>

                            {{-- Rincian --}}
                            <div class="grid sm:grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="text-muted-foreground">Pembeli:</span> {{ $item->pembeli->nama }} ({{ $item->pembeli->prodi }})
                                    @if ($item->pembeli->ratingPembeli->count() > 0)
                                        &bull; <span class="text-primary">&#9733;</span> {{ number_format($item->pembeli->ratingPembeli->avg('bintang'), 1) }}
                                        <span class="text-muted-foreground">({{ $item->pembeli->ratingPembeli->count() }})</span>
                                    @endif
                                </div>
                                <div><span class="text-muted-foreground">Cara bayar:</span> {{ $labelBayar[$item->cara_bayar] }}</div>
                                <div><span class="text-muted-foreground">Lokasi:</span> {{ $item->lokasi_ketemu }}</div>
                                <div><span class="text-muted-foreground">Waktu:</span> {{ $item->waktu_ketemu->format('d M Y, H:i') }}</div>
                            </div>

                            @if ($item->catatan)
                                <div class="rounded-md px-3 py-2 text-sm bg-muted">Catatan: {{ $item->catatan }}</div>
                            @endif

                            @if ($item->status == 'dibatalkan')
                                <div class="text-sm text-muted-foreground">
                                    Dibatalkan oleh {{ $item->dibatalkan_oleh == auth()->id() ? 'kamu' : ($item->dibatalkan_oleh == $item->pembeli_id ? 'pembeli' : 'Admin') }}.
                                    Alasan: {{ $item->alasan_batal }}
                                </div>
                            @endif

                            @if ($item->catatan_admin)
                                <div class="rounded-md px-3 py-2 text-sm status-menunggu">Catatan admin: {{ $item->catatan_admin }}</div>
                            @endif

                            {{-- Rating (setelah transaksi selesai) --}}
                            @if ($item->status == 'selesai')
                                @php
                                    $ratingSaya  = $item->rating->where('pemberi_id', auth()->id())->first();
                                    $ratingLawan = $item->rating->where('pemberi_id', '!=', auth()->id())->first();
                                @endphp
                                <div class="rounded-md border border-border p-4 space-y-3">
                                    @if ($ratingSaya)
                                        <div class="text-sm">
                                            <span class="text-muted-foreground">Rating kamu untuk pembeli:</span>
                                            <span class="text-primary">{{ str_repeat('★', $ratingSaya->bintang) }}{{ str_repeat('☆', 5 - $ratingSaya->bintang) }}</span>
                                            @if ($ratingSaya->ulasan) &mdash; "{{ $ratingSaya->ulasan }}" @endif
                                        </div>
                                    @else
                                        <form action="{{ route('pesanan.rating', $item->pesanan_id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            <div class="text-sm font-medium text-foreground">Beri rating untuk pembeli {{ $item->pembeli->nama }}</div>
                                            <div class="bintang-input">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <input type="radio" id="bintang-{{ $item->pesanan_id }}-{{ $i }}" name="bintang" value="{{ $i }}">
                                                    <label for="bintang-{{ $item->pesanan_id }}-{{ $i }}" title="{{ $i }} bintang">&#9733;</label>
                                                @endfor
                                            </div>
                                            <textarea name="ulasan" rows="2" class="flex min-h-[60px] w-full rounded-md border px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border resize-none" placeholder="Ulasan singkat (opsional)"></textarea>
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Kirim Rating</button>
                                        </form>
                                    @endif

                                    @if ($ratingLawan)
                                        <div class="text-sm">
                                            <span class="text-muted-foreground">Rating dari pembeli untukmu:</span>
                                            <span class="text-primary">{{ str_repeat('★', $ratingLawan->bintang) }}{{ str_repeat('☆', 5 - $ratingLawan->bintang) }}</span>
                                            @if ($ratingLawan->ulasan) &mdash; "{{ $ratingLawan->ulasan }}" @endif
                                        </div>
                                    @else
                                        <div class="text-sm text-muted-foreground">Pembeli belum memberi rating.</div>
                                    @endif
                                </div>
                            @endif

                            {{-- Info konfirmasi selesai --}}
                            @if ($item->status == 'dijadwalkan' && $item->selesai_penjual)
                                <div class="text-sm text-muted-foreground">Kamu sudah konfirmasi selesai. Menunggu konfirmasi pembeli.</div>
                            @elseif ($item->status == 'dijadwalkan' && $item->selesai_pembeli)
                                <div class="text-sm text-muted-foreground">Pembeli sudah konfirmasi selesai. Giliranmu!</div>
                            @endif

                            {{-- Tombol aksi --}}
                            @if (in_array($item->status, ['menunggu', 'dijadwalkan']))
                                <div class="flex flex-col sm:flex-row gap-2">
                                    @if (in_array($item->status, ['menunggu', 'dijadwalkan']) && $item->pembeli->no_hp)
                                        <a href="{{ $item->pembeli->linkWhatsapp('Halo ' . $item->pembeli->nama . ', saya ' . auth()->user()->nama . ' (penjual) dari MarketPCR. Soal pesanan ' . $item->barang->nama_barang . ' (Rp ' . number_format($item->harga, 0, ',', '.') . '), rencana ketemu di ' . $item->lokasi_ketemu . ', ' . $item->waktu_ketemu->format('d M Y H:i') . '.') }}" target="_blank" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 tombol-wa">Hubungi Pembeli via WhatsApp</a>
                                    @endif

                                    @if ($item->status == 'menunggu')
                                        <form action="{{ route('pesanan.terima', $item->pesanan_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Terima Pesanan</button>
                                        </form>
                                        <form action="{{ route('pesanan.tolak', $item->pesanan_id) }}" method="POST"
                                            onsubmit="return confirm('Tolak pesanan ini? Barang akan kembali tersedia.')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent hover:text-accent-foreground">Tolak</button>
                                        </form>
                                    @endif
                                    @if ($item->status == 'dijadwalkan' && !$item->selesai_penjual)
                                        <form action="{{ route('pesanan.selesai', $item->pesanan_id) }}" method="POST"
                                            onsubmit="return confirm('Sudah bertemu, barang sesuai, dan pembayaran beres?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Transaksi Selesai</button>
                                        </form>
                                    @endif
                                </div>

                                {{-- Batalkan (alasan wajib) --}}
                                <form action="{{ route('pesanan.batal', $item->pesanan_id) }}" method="POST" class="flex flex-col sm:flex-row gap-2"
                                    onsubmit="return confirm('Yakin membatalkan pesanan ini?')">
                                    @csrf
                                    <input type="text" name="alasan_batal" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" placeholder="Alasan batal (wajib diisi)" required>
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-9 rounded-md px-4 bg-destructive text-destructive-foreground hover:bg-destructive/90">Batalkan</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-muted-foreground">Belum ada riwayat.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
