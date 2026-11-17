@extends('layouts.app')

@section('title', 'Daftar - Marketplace Mahasiswa PCR')

@section('content')
    <section class="py-16 lg:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto">
                <div class="rounded-xl border bg-card text-card-foreground shadow">

                    {{-- Judul --}}
                    <div class="flex flex-col space-y-1.5 p-6">
                        <div class="tracking-tight text-2xl font-bold text-foreground">Daftar</div>
                        <p class="text-muted-foreground">Buat akun untuk mulai jual beli kebutuhan kuliah.</p>
                    </div>

                    <div class="p-6 pt-0">
                        {{-- Pesan berhasil --}}
                        @if (session('success'))
                            <div class="mb-4 rounded-md px-3 py-2 text-sm status-tersedia">{{ session('success') }}</div>
                        @endif
                        <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="space-y-2">
                                <label for="name" class="text-sm font-medium text-foreground">Nama Lengkap</label>
                                <input type="text" id="name" name="name"
                                    class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                    placeholder="Contoh: Muhammad Rakha" value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="text-sm text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="nim" class="text-sm font-medium text-foreground">NIM</label>
                                <input type="text" id="nim" name="nim"
                                    class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                    placeholder="Contoh: 2555301129" value="{{ old('nim') }}" required>
                                @error('nim')
                                    <p class="text-sm text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="prodi" class="text-sm font-medium text-foreground">Program Studi</label>
                                <select id="prodi" name="prodi"
                                    class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                    required>
                                    <option value="">-- Pilih Prodi --</option>
                                    <option value="Teknik Informatika" {{ old('prodi') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                                    <option value="Sistem Informasi" {{ old('prodi') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                                    <option value="Teknologi Rekayasa Komputer" {{ old('prodi') == 'Teknologi Rekayasa Komputer' ? 'selected' : '' }}>Teknologi Rekayasa Komputer</option>
                                    <option value="Teknik Elektronika Telekomunikasi" {{ old('prodi') == 'Teknik Elektronika Telekomunikasi' ? 'selected' : '' }}>Teknik Elektronika Telekomunikasi</option>
                                    <option value="Teknologi Rekayasa Jaringan Telekomunikasi" {{ old('prodi') == 'Teknologi Rekayasa Jaringan Telekomunikasi' ? 'selected' : '' }}>Teknologi Rekayasa Jaringan
                                        Telekomunikasi</option>
                                    <option value="Teknik Listrik" {{ old('prodi') == 'Teknik Listrik' ? 'selected' : '' }}>
                                        Teknik Listrik</option>
                                    <option value="Teknik Mesin" {{ old('prodi') == 'Teknik Mesin' ? 'selected' : '' }}>Teknik
                                        Mesin</option>
                                    <option value="Teknik Mekatronika" {{ old('prodi') == 'Teknik Mekatronika' ? 'selected' : '' }}>Teknik Mekatronika</option>
                                    <option value="Teknologi Rekayasa Mekatronika" {{ old('prodi') == 'Teknologi Rekayasa Mekatronika' ? 'selected' : '' }}>Teknologi Rekayasa Mekatronika</option>
                                    <option value="Teknologi Rekayasa Sistem Elektronika" {{ old('prodi') == 'Teknologi Rekayasa Sistem Elektronika' ? 'selected' : '' }}>Teknologi Rekayasa Sistem
                                        Elektronika</option>
                                    <option value="Akuntansi Perpajakan" {{ old('prodi') == 'Akuntansi Perpajakan' ? 'selected' : '' }}>Akuntansi Perpajakan</option>
                                    <option value="Bisnis Digital" {{ old('prodi') == 'Bisnis Digital' ? 'selected' : '' }}>
                                        Bisnis Digital</option>
                                        <option value="Hubungan Masyarakat dan Komunikasi Digital" {{ old('prodi') == 'Hubungan Masyarakat dan Komunikasi Digital' ? 'selected' : '' }}>Hubungan Masyarakat dan Komunikasi Digital</option>
                                        <option value="Animasi" {{ old('prodi') == 'Animasi' ? 'selected' : '' }}>Animasi</option>
                                        <option value="Kecerdasan Buatan dan Robotika" {{ old('prodi') == 'Kecerdasan Buatan dan Robotika' ? 'selected' : '' }}>Kecerdasan Buatan dan Robotika</option>
                                </select>
                                @error('prodi')
                                    <p class="text-sm text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="role" class="text-sm font-medium text-foreground">Daftar Sebagai</label>
                                <select id="role" name="role"
                                    class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                    required>
                                    <option value="">-- Pilih Peran --</option>
                                    <option value="penjual" {{ old('role') == 'penjual' ? 'selected' : '' }}>Penjual (Hanya
                                        menjual barang)</option>
                                    <option value="pembeli" {{ old('role') == 'pembeli' ? 'selected' : '' }}>Pembeli (Hanya
                                        membeli barang)</option>
                                    <option value="penjual_pembeli" {{ old('role') == 'penjual_pembeli' ? 'selected' : '' }}>
                                        Penjual &amp; Pembeli (Bisa menjual dan membeli)</option>
                                </select>
                                @error('role')
                                    <p class="text-sm text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="password" class="text-sm font-medium text-foreground">Password</label>
                                <input type="password" id="password" name="password"
                                    class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                    placeholder="Minimal 6 karakter" required>
                                @error('password')
                                    <p class="text-sm text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="password_confirmation" class="text-sm font-medium text-foreground">Konfirmasi
                                    Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                    placeholder="Ulangi password" required>
                                @error('password_confirmation')
                                    <p class="text-sm text-destructive">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 shadow h-10 rounded-md px-8 w-full bg-primary text-primary-foreground hover:bg-primary/90">Daftar</button>

                            <p class="text-sm text-muted-foreground text-center">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="font-medium text-primary">Masuk di sini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection