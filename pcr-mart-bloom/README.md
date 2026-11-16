# PCR Mart — Marketplace Mahasiswa Politeknik Caltex Riau

Proyek Semester BPF Kelompok 2. Versi 2 ini dibangun ulang di atas template
**Bloom E-Commerce** (Next.js 16 + React 19 + Tailwind CSS v4 + komponen shadcn/ui).
Data masih disimpan di `localStorage` (prototipe tanpa backend).

## Menjalankan
1. Pasang Node.js 20.9+ (https://nodejs.org)
2. `npm install`
3. `npm run dev` lalu buka http://localhost:3000
4. Reset data: menu **Akun → Reset Data ke Awal**

## Fitur
- Katalog dengan pencarian, filter (kategori, prodi, kondisi, lokasi COD, rentang harga) dan urutan
- Detail barang, spesifikasi, info penjual, barang serupa, salin format promosi WA
- Jual / edit barang dengan validasi Zod, saran harga wajar, checklist kondisi, unggah foto
- Lapak Saya: ubah status (TERSEDIA / DIBOOKING / TERJUAL), edit, hapus
- Bandingkan hingga 3 barang
- Chat per barang + Kartu Kesepakatan COD (setujui, ajukan ulang, selesai, batal) + ulasan/keluhan
- Akun: ganti akun simulasi, profil, wishlist dengan notifikasi barang cocok, riwayat transaksi
- Mode Hemat Kuota (gambar diganti ikon ringan)

## Akun simulasi
Ganti di menu **Akun**: Dika (pembeli, sem 3), Raffli (penjual, sem 7), Salsa (penjual).
Alur uji: Dika → buka barang → Chat → Jadwal COD → ganti ke Raffli → Setujui → Konfirmasi selesai → ganti ke Dika → beri ulasan.

## Struktur
```
app/            halaman (/, /produk/[id], /jual, /lapak, /bandingkan, /chat, /akun)
components/
  layout/       Header, Footer, toast & bar bandingkan (gaya Bloom)
  home/         ProductCard
  shared/       StatusBadge, Thumb
  ui/           komponen shadcn/ui dari template Bloom
lib/            seed.ts (data awal & tipe), store.tsx (state global), util.ts, utils.ts
```

## Lisensi template
Template UI © [Bloomtpl](https://github.com/bloomtpl), lisensi MIT, didistribusikan oleh [ThemeWagon](https://themewagon.com).
