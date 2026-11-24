@extends('layouts.app')

@section('title', 'Masuk - Marketplace Mahasiswa PCR')

@section('content')
    <section class="py-16 lg:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto">
                <div class="rounded-xl border bg-card text-card-foreground shadow">

                    {{-- Judul --}}
                    <div class="flex flex-col space-y-1.5 p-6">
                        <div class="tracking-tight text-2xl font-bold text-foreground">Masuk</div>
                        <p class="text-muted-foreground">Masuk dengan NIM dan password yang sudah kamu daftarkan.</p>
                    </div>

                    <div class="p-6 pt-0">
                        {{-- Pesan berhasil --}}
                        @if (session('success'))
                            <div class="mb-4 rounded-md px-3 py-2 text-sm status-tersedia">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="mb-4 rounded-md px-3 py-2 text-sm kotak-error">{{ session('error') }}</div>
                        @endif
                        <form action="{{ route('login.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="space-y-2">
                                <label for="nim" class="text-sm font-medium text-foreground">NIM</label>
                                <input type="text" id="nim" name="nim"
                                    class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                    placeholder="Masukkan NIM" value="{{ old('nim') }}" required>
                                @error('nim')
                                    <p class="text-sm text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="password" class="text-sm font-medium text-foreground">Password</label>
                                <input type="password" id="password" name="password"
                                    class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                    placeholder="Masukkan password" required>
                                @error('password')
                                    <p class="text-sm text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-10 rounded-md px-8 w-full bg-primary text-primary-foreground hover:bg-primary/90">Masuk</button>

                            <p class="text-sm text-muted-foreground text-center">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="font-medium text-primary">Daftar di sini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection