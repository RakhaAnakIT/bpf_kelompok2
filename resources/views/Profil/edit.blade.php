@extends('layouts.app')

@section('title', 'Edit Profil - Marketplace Mahasiswa PCR')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-3xl">
        <a href="{{ route('profil.show') }}" class="text-sm text-muted-foreground hover:text-foreground">&larr; Kembali ke Profil Saya</a>
        <h1 class="text-3xl font-bold text-foreground mt-2 mb-6">Edit Profil</h1>
        @if (session('success'))
            <div class="mb-6 rounded-md px-4 py-3 text-sm status-tersedia">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-md px-4 py-3 text-sm kotak-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-md px-4 py-3 text-sm kotak-error">{{ $errors->first() }}</div>
        @endif

        {{-- 1. Foto & data diri --}}
        <div class="rounded-xl border bg-card text-card-foreground shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-foreground mb-4">Foto &amp; Data Diri</h2>
            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-4">
                    <span id="preview-foto">
                @if ($dataUser->foto_profil)
                    <img src="{{ asset('storage/' . $dataUser->foto_profil) }}" alt="Foto profil" class="avatar avatar-besar">
                @else
                    <span class="avatar avatar-inisial avatar-besar">{{ $dataUser->inisial() }}</span>
                @endif
                    </span>
                    <div class="flex-1 space-y-2">
                        <label for="foto_profil" class="text-sm font-medium text-foreground">Foto Profil (maks. 2 MB)</label>
                        <input type="file" id="foto_profil" name="foto_profil" accept="image/*" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground md:text-sm bg-background border-border">
                        @if ($dataUser->foto_profil)
                            <label class="text-sm text-muted-foreground flex items-center gap-2">
                                <input type="checkbox" name="hapus_foto" value="1"> Hapus foto profil
                            </label>
                        @endif
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="nama" class="text-sm font-medium text-foreground">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" value="{{ old('nama', $dataUser->nama) }}" required>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-foreground">NIM (tidak bisa diubah)</label>
                        <input type="text" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" value="{{ $dataUser->nim }}" disabled>
                    </div>
                    <div class="space-y-2">
                        <label for="no_hp" class="text-sm font-medium text-foreground">No WhatsApp</label>
                        <input type="tel" id="no_hp" name="no_hp" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" value="{{ old('no_hp', $dataUser->no_hp) }}" placeholder="Contoh: 081234567890" required>
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="prodi" class="text-sm font-medium text-foreground">Program Studi</label>
                    <select id="prodi" name="prodi" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" required>
                                <option value="Teknik Informatika" {{ old('prodi', $dataUser->prodi) == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                                <option value="Sistem Informasi" {{ old('prodi', $dataUser->prodi) == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                                <option value="Teknologi Rekayasa Komputer" {{ old('prodi', $dataUser->prodi) == 'Teknologi Rekayasa Komputer' ? 'selected' : '' }}>Teknologi Rekayasa Komputer</option>
                                <option value="Teknik Elektronika Telekomunikasi" {{ old('prodi', $dataUser->prodi) == 'Teknik Elektronika Telekomunikasi' ? 'selected' : '' }}>Teknik Elektronika Telekomunikasi</option>
                                <option value="Teknologi Rekayasa Jaringan Telekomunikasi" {{ old('prodi', $dataUser->prodi) == 'Teknologi Rekayasa Jaringan Telekomunikasi' ? 'selected' : '' }}>Teknologi Rekayasa Jaringan Telekomunikasi</option>
                                <option value="Teknik Listrik" {{ old('prodi', $dataUser->prodi) == 'Teknik Listrik' ? 'selected' : '' }}>Teknik Listrik</option>
                                <option value="Teknik Mesin" {{ old('prodi', $dataUser->prodi) == 'Teknik Mesin' ? 'selected' : '' }}>Teknik Mesin</option>
                                <option value="Teknik Mekatronika" {{ old('prodi', $dataUser->prodi) == 'Teknik Mekatronika' ? 'selected' : '' }}>Teknik Mekatronika</option>
                                <option value="Teknologi Rekayasa Mekatronika" {{ old('prodi', $dataUser->prodi) == 'Teknologi Rekayasa Mekatronika' ? 'selected' : '' }}>Teknologi Rekayasa Mekatronika</option>
                                <option value="Teknologi Rekayasa Sistem Elektronika" {{ old('prodi', $dataUser->prodi) == 'Teknologi Rekayasa Sistem Elektronika' ? 'selected' : '' }}>Teknologi Rekayasa Sistem Elektronika</option>
                                <option value="Akuntansi Perpajakan" {{ old('prodi', $dataUser->prodi) == 'Akuntansi Perpajakan' ? 'selected' : '' }}>Akuntansi Perpajakan</option>
                                <option value="Bisnis Digital" {{ old('prodi', $dataUser->prodi) == 'Bisnis Digital' ? 'selected' : '' }}>Bisnis Digital</option>
                                <option value="Hubungan Masyarakat dan Komunikasi Digital" {{ old('prodi', $dataUser->prodi) == 'Hubungan Masyarakat dan Komunikasi Digital' ? 'selected' : '' }}>Hubungan Masyarakat dan Komunikasi Digital</option>
                                <option value="Animasi" {{ old('prodi', $dataUser->prodi) == 'Animasi' ? 'selected' : '' }}>Animasi</option>
                                <option value="Kecerdasan Buatan dan Robotika" {{ old('prodi', $dataUser->prodi) == 'Kecerdasan Buatan dan Robotika' ? 'selected' : '' }}>Kecerdasan Buatan dan Robotika</option>
                        @if (!in_array($dataUser->prodi, ['Teknik Informatika', 'Sistem Informasi', 'Teknologi Rekayasa Komputer', 'Teknik Elektronika Telekomunikasi', 'Teknologi Rekayasa Jaringan Telekomunikasi', 'Teknik Listrik', 'Teknik Mesin', 'Teknik Mekatronika', 'Teknologi Rekayasa Mekatronika', 'Teknologi Rekayasa Sistem Elektronika', 'Akuntansi Perpajakan', 'Bisnis Digital', 'Hubungan Masyarakat dan Komunikasi Digital', 'Animasi', 'Kecerdasan Buatan dan Robotika']))
                            <option value="{{ $dataUser->prodi }}" selected>{{ $dataUser->prodi }}</option>
                        @endif
                    </select>
                </div>

                @if ($dataUser->role != 'admin')
                    <label class="flex items-start gap-3 rounded-md border border-border p-4">
                        <input type="checkbox" name="profil_publik" value="1" {{ old('profil_publik', $dataUser->profil_publik) ? 'checked' : '' }}>
                        <span>
                            <span class="text-sm font-medium text-foreground">Profil Publik</span>
                            <span class="block text-sm text-muted-foreground">
                                Kalau aktif, user lain bisa melihat ulasan dan barang lain yang kamu jual. Kalau dimatikan (privat),
                                yang tampil hanya nama, prodi, dan rating. Rating tetap tampil di barang yang kamu jual.
                            </span>
                        </span>
                    </label>
                @endif

                <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Simpan Data Diri</button>
            </form>
        </div>

        {{-- 2. Ganti password --}}
        <div class="rounded-xl border bg-card text-card-foreground shadow p-6 mb-6">
            <h2 class="text-xl font-semibold text-foreground mb-4">Ganti Password</h2>
            <form action="{{ route('profil.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label for="password_lama" class="text-sm font-medium text-foreground">Password Lama</label>
                    <input type="password" id="password_lama" name="password_lama" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" required>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium text-foreground">Password Baru</label>
                        <input type="password" id="password" name="password" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" placeholder="Minimal 8 karakter" required>
                    </div>
                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-medium text-foreground">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" required>
                    </div>
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90">Ganti Password</button>
            </form>
        </div>

        @if ($dataUser->role != 'admin')
            {{-- 3. Ganti role --}}
            <div class="rounded-xl border bg-card text-card-foreground shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-foreground mb-2">Ganti Role</h2>
                <p class="text-sm text-muted-foreground mb-4">
                    Role sekarang: <b>{{ $dataUser->labelRole() }}</b>. Barang dan riwayat pesanan tidak hilang saat ganti role.
                    Kalau berhenti menjual (jadi Pembeli), barangmu disembunyikan dari katalog sampai kamu kembali menjadi penjual.
                </p>
                <form action="{{ route('profil.role') }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                    @csrf
                    @method('PUT')
                    <select name="role" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border">
                        <option value="penjual" {{ $dataUser->role == 'penjual' ? 'selected' : '' }}>Penjual (hanya menjual)</option>
                        <option value="pembeli" {{ $dataUser->role == 'pembeli' ? 'selected' : '' }}>Pembeli (hanya membeli)</option>
                        <option value="penjual_pembeli" {{ $dataUser->role == 'penjual_pembeli' ? 'selected' : '' }}>Penjual &amp; Pembeli</option>
                    </select>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-primary text-primary-foreground hover:bg-primary/90" onclick="return confirm('Ganti role akunmu?')">Ganti Role</button>
                </form>
            </div>

            {{-- 4. Hapus akun --}}
            <div class="rounded-xl border bg-card text-card-foreground shadow zona-bahaya p-6">
                <h2 class="text-xl font-semibold text-destructive mb-2">Hapus Akun</h2>
                <p class="text-sm text-muted-foreground mb-4">
                    Akun tidak bisa dipakai lagi dan barang yang masih dijual akan dihapus. Riwayat pesanan dan rating
                    tetap tersimpan untuk pengguna lain dengan nama "Akun dihapus". Pastikan tidak ada pesanan yang masih berjalan.
                </p>
                <form action="{{ route('profil.destroy') }}" method="POST" class="flex flex-col sm:flex-row gap-2"
                    onsubmit="return confirm('Yakin menghapus akun? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <input type="password" name="password_konfirmasi" class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border" placeholder="Masukkan password untuk konfirmasi" required>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors shadow h-9 rounded-md px-4 bg-destructive text-destructive-foreground hover:bg-destructive/90">Hapus Akun Saya</button>
                </form>
            </div>
        @endif
    </div>

    <script>
        // Pratinjau foto profil sebelum disimpan
        document.getElementById('foto_profil').addEventListener('change', function () {
            if (!this.files[0]) return;
            document.getElementById('preview-foto').innerHTML =
                '<img src="' + URL.createObjectURL(this.files[0]) + '" alt="Pratinjau" class="avatar avatar-besar">';
        });
    </script>
@endsection
