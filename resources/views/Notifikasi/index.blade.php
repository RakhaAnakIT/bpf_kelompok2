@extends('layouts.app')

@section('title', 'Notifikasi - Marketplace Mahasiswa PCR')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-3xl mx-auto">

            {{-- Pesan berhasil --}}
            @if (session('success'))
                <div class="mb-6 rounded-md px-4 py-3 text-sm status-tersedia">{{ session('success') }}</div>
            @endif

            <div class="flex items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">Notifikasi</h1>
                    <p class="text-muted-foreground mt-2">Kabar terbaru tentang pesanan kamu.</p>
                </div>
                @if ($dataNotifikasi->whereNull('dibaca_pada')->count() > 0)
                    <form action="{{ route('notifikasi.bacasemua') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 border border-input bg-background hover:bg-accent hover:text-accent-foreground">Tandai
                            semua dibaca</button>
                    </form>
                @endif
            </div>

            <div class="space-y-3">
                @forelse ($dataNotifikasi as $item)
                    <a href="{{ route('notifikasi.buka', $item->notifikasi_id) }}"
                        class="block rounded-xl border p-4 transition-colors hover:bg-gray-50 {{ $item->dibaca_pada ? 'bg-card border-border' : 'notif-baru' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <div class="font-semibold text-foreground">
                                    @if (!$item->dibaca_pada)
                                        <span class="titik-baru"></span>
                                    @endif
                                    {{ $item->judul }}
                                </div>
                                <div class="text-sm text-muted-foreground">{{ $item->pesan }}</div>
                            </div>
                            <div class="text-xs text-muted-foreground whitespace-nowrap">
                                {{ $item->created_at->locale('id')->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="text-6xl mb-4">&#128276;</div>
                        <h3 class="text-xl font-semibold text-foreground mb-2">Belum ada notifikasi</h3>
                        <p class="text-muted-foreground">Kabar tentang pesanan akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection