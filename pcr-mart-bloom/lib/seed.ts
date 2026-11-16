export const KATEGORI = ["Buku Acuan","Modul Praktikum","Jas Lab","Kalkulator Ilmiah","Alat Tulis & Gambar","Aksesori Laptop"] as const;
export const PRODI = ["Teknik Informatika","Sistem Informasi","Teknik Elektronika","Teknik Listrik","Teknik Mekatronika"];
export const KONDISI = ["Baru","Bekas - Seperti Baru","Bekas - Layak Pakai"] as const;
export const LOKASI = ["Kantin PCR","Gedung Utama","Perpustakaan","Lab Komputer","Parkiran Mahasiswa"];
export const METODE = ["Tunai","Transfer Bank","QRIS/E-Wallet"];
export type Status = "TERSEDIA"|"DIBOOKING"|"TERJUAL";
export type TxStatus = "MENUNGGU_KONFIRMASI"|"DIJADWALKAN"|"SELESAI"|"DIBATALKAN";
export interface User { id:string; nim:string; nama:string; email:string; prodi:string; semester:number; no_wa:string; jam_sibuk:string; foto:string; }
export interface Product { id:string; seller_id:string; judul:string; kategori:string; mata_kuliah:string; prodi_terkait:string; edisi:string; harga:number; harga_baru:number; kondisi:string;
  cek:{halaman_lengkap:boolean;tanpa_coretan:boolean;fungsi_normal:boolean;ukuran:string}; deskripsi:string; lokasi:string; fotos:string[]; status:Status; created_at:number; updated_at:number; }
export interface Tx { id:string; product_id:string; buyer_id:string; seller_id:string; proposer_id:string; harga:number; lokasi:string; waktu:string; metode:string; status:TxStatus; catatan:string; }
export interface Msg { id:string; product_id:string; sender_id:string; receiver_id:string; text:string; offer_tx?:string; ts:number; }
export interface Review { id:string; tx_id:string; reviewer_id:string; target_id:string; rating:number; komentar:string; jenis:"ULASAN"|"KELUHAN_BARANG"; ts:number; }
export interface Wish { id:string; user_id:string; kata:string; kategori:string; mk:string; }
export interface Data { users:User[]; products:Product[]; txs:Tx[]; msgs:Msg[]; reviews:Review[]; wishes:Wish[]; me:string; }

const now = Date.now(); const day = 86400000;
const P = (id:string, seller:string, judul:string, kategori:string, mk:string, prodi:string, edisi:string, harga:number, baru:number, kondisi:string,
  cek:Product["cek"], deskripsi:string, lokasi:string, status:Status="TERSEDIA", ago=1): Product =>
  ({ id, seller_id:seller, judul, kategori, mata_kuliah:mk, prodi_terkait:prodi, edisi, harga, harga_baru:baru, kondisi, cek, deskripsi, lokasi, fotos:[], status, created_at:now-ago*day, updated_at:now-ago*day });

export const seedData = (): Data => ({
  me: "u-dika",
  users: [
    { id:"u-dika", nim:"2555301129", nama:"Dika Pratama", email:"dika.pratama@mahasiswa.pcr.ac.id", prodi:"Teknik Informatika", semester:3, no_wa:"081234567801", jam_sibuk:"Senin-Jumat 08.00-16.00 (jam kuliah)", foto:"" },
    { id:"u-raffli", nim:"2255301045", nama:"Raffli Hidayat", email:"raffli.hidayat@mahasiswa.pcr.ac.id", prodi:"Teknik Informatika", semester:7, no_wa:"081234567802", jam_sibuk:"Sedang jam kuliah - biasanya membalas malam hari", foto:"" },
    { id:"u-salsa", nim:"2355302210", nama:"Salsabila Putri", email:"salsabila.putri@mahasiswa.pcr.ac.id", prodi:"Sistem Informasi", semester:5, no_wa:"081234567803", jam_sibuk:"Selasa & Kamis sibuk, lainnya fast response", foto:"" },
  ],
  products: [
    P("p1","u-raffli","Buku Algoritma & Struktur Data (Cormen)","Buku Acuan","Algoritma dan Struktur Data","Teknik Informatika","Edisi 3, 2009",85000,320000,"Bekas - Seperti Baru",{halaman_lengkap:true,tanpa_coretan:true,fungsi_normal:true,ukuran:"A4"},"Buku acuan mata kuliah Algoritma. Masih mulus, hanya ada highlight tipis di bab 2.","Perpustakaan","TERSEDIA",2),
    P("p2","u-raffli","Modul Praktikum Basis Data Semester 3","Modul Praktikum","Basis Data","Teknik Informatika","2024",20000,35000,"Bekas - Layak Pakai",{halaman_lengkap:true,tanpa_coretan:false,fungsi_normal:true,ukuran:"A4"},"Modul lengkap, ada beberapa coretan jawaban latihan.","Lab Komputer","TERSEDIA",3),
    P("p3","u-raffli","Jas Lab Putih Ukuran L","Jas Lab","Praktikum Elektronika","Teknik Elektronika","-",50000,120000,"Bekas - Seperti Baru",{halaman_lengkap:true,tanpa_coretan:true,fungsi_normal:true,ukuran:"L"},"Dipakai 1 semester, sudah dicuci, tidak ada noda.","Gedung Utama","TERSEDIA",1),
    P("p4","u-salsa","Kalkulator Casio fx-991EX","Kalkulator Ilmiah","Kalkulus & Matematika Diskrit","Teknik Informatika","Classwiz",180000,330000,"Bekas - Seperti Baru",{halaman_lengkap:true,tanpa_coretan:true,fungsi_normal:true,ukuran:"-"},"Fungsi normal semua, baterai solar bagus, cover lengkap.","Kantin PCR","TERSEDIA",2),
    P("p5","u-salsa","Buku Sistem Informasi Manajemen (Laudon)","Buku Acuan","Sistem Informasi Manajemen","Sistem Informasi","Edisi 14, 2016",70000,280000,"Bekas - Layak Pakai",{halaman_lengkap:true,tanpa_coretan:false,fungsi_normal:true,ukuran:"A4"},"Ada coretan pensil di beberapa halaman, isi lengkap.","Perpustakaan","DIBOOKING",4),
    P("p6","u-raffli","Penggaris Teknik + Jangka Set Gambar","Alat Tulis & Gambar","Gambar Teknik","Teknik Mekatronika","-",30000,75000,"Bekas - Layak Pakai",{halaman_lengkap:true,tanpa_coretan:true,fungsi_normal:true,ukuran:"30 cm"},"Satu set lengkap dalam kotak.","Parkiran Mahasiswa","TERSEDIA",5),
    P("p7","u-salsa","Mouse Wireless Logitech M185","Aksesori Laptop","-","Teknik Informatika","-",60000,130000,"Bekas - Seperti Baru",{halaman_lengkap:true,tanpa_coretan:true,fungsi_normal:true,ukuran:"-"},"Receiver lengkap, baterai baru diganti.","Kantin PCR","TERSEDIA",1),
    P("p8","u-raffli","Buku Jaringan Komputer (Tanenbaum)","Buku Acuan","Jaringan Komputer","Teknik Informatika","Edisi 5, 2011",90000,350000,"Bekas - Seperti Baru",{halaman_lengkap:true,tanpa_coretan:true,fungsi_normal:true,ukuran:"A4"},"Hampir tidak pernah dibuka. Cocok untuk persiapan UAS.","Perpustakaan","TERJUAL",8),
    P("p9","u-salsa","Modul Praktikum Pemrograman Web","Modul Praktikum","Pemrograman Web","Sistem Informasi","2025",25000,40000,"Baru",{halaman_lengkap:true,tanpa_coretan:true,fungsi_normal:true,ukuran:"A4"},"Salah beli, belum pernah dipakai.","Lab Komputer","TERSEDIA",1),
    P("p10","u-raffli","Jas Lab Putih Ukuran M","Jas Lab","Praktikum Fisika","Teknik Listrik","-",40000,110000,"Bekas - Layak Pakai",{halaman_lengkap:true,tanpa_coretan:true,fungsi_normal:true,ukuran:"M"},"Ada sedikit bekas noda kecil di lengan.","Gedung Utama","TERSEDIA",6),
    P("p11","u-salsa","Cooling Pad Laptop 15 inci","Aksesori Laptop","-","Teknik Informatika","-",55000,120000,"Bekas - Seperti Baru",{halaman_lengkap:true,tanpa_coretan:true,fungsi_normal:true,ukuran:"15 inci"},"Kipas senyap, kabel USB lengkap.","Parkiran Mahasiswa","TERSEDIA",2),
  ],
  txs: [
    { id:"t1", product_id:"p5", buyer_id:"u-dika", seller_id:"u-salsa", proposer_id:"u-dika", harga:65000, lokasi:"Perpustakaan", waktu:new Date(now+2*day).toISOString().slice(0,16), metode:"Tunai", status:"DIJADWALKAN", catatan:"Sela jam kuliah" },
    { id:"t0", product_id:"p8", buyer_id:"u-dika", seller_id:"u-raffli", proposer_id:"u-dika", harga:85000, lokasi:"Perpustakaan", waktu:new Date(now-3*day).toISOString().slice(0,16), metode:"QRIS/E-Wallet", status:"SELESAI", catatan:"" },
  ],
  msgs: [
    { id:"m1", product_id:"p5", sender_id:"u-dika", receiver_id:"u-salsa", text:"Halo kak, barang ini masih tersedia?", ts:now-4*day },
    { id:"m2", product_id:"p5", sender_id:"u-salsa", receiver_id:"u-dika", text:"Masih kak, bisa COD di perpustakaan.", ts:now-4*day+600000 },
    { id:"m3", product_id:"p5", sender_id:"u-dika", receiver_id:"u-salsa", text:"Kesepakatan COD diajukan", offer_tx:"t1", ts:now-3*day },
    { id:"m4", product_id:"p8", sender_id:"u-dika", receiver_id:"u-raffli", text:"Kesepakatan COD diajukan", offer_tx:"t0", ts:now-5*day },
  ],
  reviews: [
    { id:"r1", tx_id:"t0", reviewer_id:"u-dika", target_id:"u-raffli", rating:5, komentar:"Barang sesuai deskripsi, COD tepat waktu. Recommended!", jenis:"ULASAN", ts:now-2*day },
  ],
  wishes: [ { id:"w1", user_id:"u-dika", kata:"Kalkulus", kategori:"Buku Acuan", mk:"Kalkulus" } ],
});
