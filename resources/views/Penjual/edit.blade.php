@extends('layouts.app')

@section('title', 'Edit Barang - Marketplace Mahasiswa PCR')

@section('content')
    <section class="py-16 lg:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto">
                <div class="rounded-xl border bg-card text-card-foreground shadow">

                    <div class="flex flex-col space-y-1.5 p-6">
                        <div class="tracking-tight text-2xl font-bold text-foreground">Edit Barang</div>
                        <p class="text-muted-foreground">
                            Ubah informasi barang lalu klik Simpan Perubahan.
                        </p>
                    </div>

                    <div class="p-6 pt-0">
                        <form action="{{ route('penjual.update', $dataPenjualPembeli->pembeli_penjual_id) }}"
                            method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="space-y-2">
                                <label for="nama_barang" class="text-sm font-medium text-foreground">Nama Barang</label>
                                <input type="text" id="nama_barang" name="nama_barang"
                                    class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                    value="{{ old('nama_barang', $dataPenjualPembeli->nama_barang) }}" required>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label for="kategori_barang"
                                        class="text-sm font-medium text-foreground">Kategori</label>
                                    <select id="kategori_barang" name="kategori_barang"
                                        class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                        required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Buku" {{ old('kategori_barang', $dataPenjualPembeli->kategori_barang) == 'Buku' ? 'selected' : '' }}>Buku</option>
                                        <option value="Modul" {{ old('kategori_barang', $dataPenjualPembeli->kategori_barang) == 'Modul' ? 'selected' : '' }}>Modul
                                        </option>
                                        <option value="Alat Tulis" {{ old('kategori_barang', $dataPenjualPembeli->kategori_barang) == 'Alat Tulis' ? 'selected' : '' }}>Alat
                                            Tulis</option>
                                        <option value="Kalkulator" {{ old('kategori_barang', $dataPenjualPembeli->kategori_barang) == 'Kalkulator' ? 'selected' : '' }}>
                                            Kalkulator</option>
                                        <option value="Perlengkapan Lab" {{ old('kategori_barang', $dataPenjualPembeli->kategori_barang) == 'Perlengkapan Lab' ? 'selected' : '' }}>
                                            Perlengkapan Lab</option>
                                        <option value="Perabotan Kos" {{ old('kategori_barang', $dataPenjualPembeli->kategori_barang) == 'Perabotan Kos' ? 'selected' : '' }}>
                                            Perabotan Kos</option>
                                        <option value="Laptop" {{ old('kategori_barang', $dataPenjualPembeli->kategori_barang) == 'Laptop' ? 'selected' : '' }}>Laptop
                                        </option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label for="kondisi" class="text-sm font-medium text-foreground">Kondisi</label>
                                    <select id="kondisi" name="kondisi"
                                        class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                        required>
                                        <option value="">-- Pilih Kondisi --</option>
                                        <option value="Baru" {{ old('kondisi', $dataPenjualPembeli->kondisi) == 'Baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="Bekas baik" {{ old('kondisi', $dataPenjualPembeli->kondisi) == 'Bekas baik' ? 'selected' : '' }}>Bekas baik</option>
                                        <option value="Bekas" {{ old('kondisi', $dataPenjualPembeli->kondisi) == 'Bekas' ? 'selected' : '' }}>Bekas</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label for="harga" class="text-sm font-medium text-foreground">Harga (Rp)</label>
                                    <input type="number" id="harga" name="harga"
                                        class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                        value="{{ old('harga', $dataPenjualPembeli->harga) }}" required>
                                </div>
                                <div class="space-y-2">
                                    <label for="lokasi" class="text-sm font-medium text-foreground">Lokasi Serah
                                        Terima</label>
                                    <input type="text" id="lokasi" name="lokasi"
                                        class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border"
                                        value="{{ old('lokasi', $dataPenjualPembeli->lokasi) }}" required>
                                </div>
                            </div>


                            <div class="space-y-2">
                                <label for="deskripsi" class="text-sm font-medium text-foreground">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" rows="5"
                                    class="flex min-h-[60px] w-full rounded-md border px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border resize-none"
                                    >{{ old('deskripsi', $dataPenjualPembeli->deskripsi) }}</textarea>
                            </div>

                            <div class="space-y-2">
                                <label for="gambar" class="text-sm font-medium text-foreground">Foto Barang</label>
                                @if ($dataPenjualPembeli->gambar)
                                    <img src="{{ asset('storage/' . $dataPenjualPembeli->gambar) }}" alt="Foto saat ini"
                                        class="w-24 h-24 object-cover rounded-md border border-border">
                                @endif
                                <p class="text-xs text-muted-foreground">Kosongkan jika tidak mau mengganti foto.</p>
                                <input type="file" id="gambar" name="gambar" accept="image/*"
                                    class="flex h-9 w-full rounded-md border px-3 py-1 text-base shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm bg-background border-border">
                            </div>

                            <div class="flex gap-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 shadow h-10 rounded-md px-8 w-full sm:w-auto bg-primary text-primary-foreground hover:bg-primary/90">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-send h-4 w-4" aria-hidden="true">
                                        <path
                                            d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z">
                                        </path>
                                        <path d="m21.854 2.147-10.94 10.939"></path>
                                    </svg>
                                    Simpan Perubahan
                                </button>
                                <a href="{{ route('penjual.show', $dataPenjualPembeli->pembeli_penjual_id) }}"
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 shadow h-10 rounded-md px-8 w-full sm:w-auto border border-border bg-background hover:bg-gray-100">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection