"use client";

import StatusBadge from "@/components/shared/StatusBadge";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { NativeSelect } from "@/components/ui/native-select";
import { KATEGORI, PRODI } from "@/lib/seed";
import { useStore } from "@/lib/store";
import { avgRating, fmtTime, rp, uid } from "@/lib/util";
import { cn } from "@/lib/utils";
import { Bell, RotateCcw, Star, Trash2 } from "lucide-react";
import Link from "next/link";
import { useState } from "react";

export default function Akun() {
  const { d, me, act, switchUser, reset, toast } = useStore();
  const profile = () => ({ nama: me.nama, prodi: me.prodi, semester: me.semester, no_wa: me.no_wa, jam_sibuk: me.jam_sibuk });
  const [f, setF] = useState(profile);
  const [w, setW] = useState({ kata: "", kategori: "" });
  const [last, setLast] = useState(me.id);
  const [confirmReset, setConfirmReset] = useState(false);

  // isi ulang form saat akun simulasi diganti
  if (last !== me.id) {
    setLast(me.id);
    setF(profile());
  }

  const save = () => {
    if (f.nama.trim().length < 3) return toast("Nama minimal 3 karakter.", "err");
    if (!/^08\d{8,12}$/.test(f.no_wa)) return toast("Nomor WA tidak valid (contoh: 081234567890).", "err");
    if (!(f.semester >= 1 && f.semester <= 14)) return toast("Semester harus antara 1 dan 14.", "err");
    act((n) => { Object.assign(n.users.find((u) => u.id === n.me)!, { ...f, nama: f.nama.trim() }); }, "Profil berhasil diperbarui.");
  };

  const addWish = () => {
    if (w.kata.trim().length < 2) return toast("Isi kata kunci minimal 2 huruf.", "err");
    act((n) => { n.wishes.push({ id: uid("w"), user_id: n.me, kata: w.kata.trim(), kategori: w.kategori, mk: w.kata.trim() }); }, "Ditambahkan ke wishlist.");
    setW({ kata: "", kategori: "" });
  };

  const reviews = d.reviews.filter((r) => r.target_id === me.id);
  const rating = avgRating(d.reviews, me.id);
  const history = d.txs.filter((t) => t.buyer_id === me.id || t.seller_id === me.id);
  const wishes = d.wishes.filter((x) => x.user_id === me.id);

  return (
    <div className="container mx-auto px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
      <div className="mb-8 flex items-center gap-4">
        <div className="grid h-16 w-16 place-items-center rounded-full bg-primary text-2xl font-bold text-primary-foreground">{me.nama[0]}</div>
        <div>
          <h1 className="text-3xl font-bold">{me.nama}</h1>
          <p className="text-sm text-muted-foreground">NIM {me.nim} • {me.email}</p>
          <p className="flex items-center gap-1 text-sm">
            <Star className="h-4 w-4 fill-primary text-primary" /> {rating ? rating.toFixed(1) : "Belum ada rating"} ({reviews.filter((r) => r.jenis === "ULASAN").length} ulasan)
          </p>
        </div>
      </div>

      <div className="grid gap-6 lg:grid-cols-2">
        <div className="space-y-6">
          <Card>
            <CardHeader><CardTitle>Ganti Akun Simulasi</CardTitle></CardHeader>
            <CardContent>
              <div className="flex flex-wrap gap-2">
                {d.users.map((u) => (
                  <Button key={u.id} variant={u.id === me.id ? "default" : "outline"} onClick={() => switchUser(u.id)}>
                    {u.nama.split(" ")[0]} (Sem {u.semester})
                  </Button>
                ))}
              </div>
              <p className="mt-3 text-xs text-muted-foreground">Dika = pembeli semester 3 • Raffli = penjual tingkat atas • Salsa = penjual lain</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader><CardTitle>Profil</CardTitle></CardHeader>
            <CardContent className="space-y-4">
              <div><Label htmlFor="nama">Nama</Label><Input id="nama" value={f.nama} onChange={(e) => setF({ ...f, nama: e.target.value })} /></div>
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <Label htmlFor="prodi">Prodi</Label>
                  <NativeSelect id="prodi" value={f.prodi} onChange={(e) => setF({ ...f, prodi: e.target.value })}>
                    {PRODI.map((p) => <option key={p}>{p}</option>)}
                  </NativeSelect>
                </div>
                <div>
                  <Label htmlFor="semester">Semester</Label>
                  <Input id="semester" type="number" min={1} max={14} value={f.semester} onChange={(e) => setF({ ...f, semester: +e.target.value })} />
                </div>
              </div>
              <div><Label htmlFor="wa">No. WhatsApp</Label><Input id="wa" inputMode="tel" value={f.no_wa} onChange={(e) => setF({ ...f, no_wa: e.target.value })} /></div>
              <div><Label htmlFor="sibuk">Jam sibuk kuliah / status balas chat</Label><Input id="sibuk" value={f.jam_sibuk} onChange={(e) => setF({ ...f, jam_sibuk: e.target.value })} /></div>
              <Button onClick={save}>Simpan Profil</Button>
            </CardContent>
          </Card>

          {confirmReset ? (
            <Card className="border-destructive/40">
              <CardContent className="flex flex-wrap items-center justify-between gap-3 p-4">
                <p className="text-sm">Kembalikan semua data ke kondisi awal?</p>
                <div className="flex gap-2">
                  <Button variant="outline" size="sm" onClick={() => setConfirmReset(false)}>Batal</Button>
                  <Button variant="destructive" size="sm" onClick={() => { reset(); setConfirmReset(false); }}>Ya, Reset</Button>
                </div>
              </CardContent>
            </Card>
          ) : (
            <Button variant="outline" className="w-full text-destructive hover:text-destructive" onClick={() => setConfirmReset(true)}>
              <RotateCcw /> Reset Data ke Awal
            </Button>
          )}
        </div>

        <div className="space-y-6">
          <Card>
            <CardHeader><CardTitle>Wishlist / Barang Dicari</CardTitle></CardHeader>
            <CardContent>
              <form className="flex flex-col gap-2 sm:flex-row" onSubmit={(e) => { e.preventDefault(); addWish(); }}>
                <Input placeholder="Kata kunci / mata kuliah" value={w.kata} onChange={(e) => setW({ ...w, kata: e.target.value })} />
                <div className="sm:w-44">
                  <NativeSelect value={w.kategori} onChange={(e) => setW({ ...w, kategori: e.target.value })} aria-label="Kategori">
                    <option value="">Semua kategori</option>
                    {KATEGORI.map((k) => <option key={k}>{k}</option>)}
                  </NativeSelect>
                </div>
                <Button type="submit">Tambah</Button>
              </form>
              <ul className="mt-4 space-y-2">
                {wishes.map((x) => {
                  const kw = x.kata.toLowerCase();
                  const hit = d.products.filter(
                    (p) => p.status === "TERSEDIA" && (p.judul.toLowerCase().includes(kw) || p.mata_kuliah.toLowerCase().includes(kw)) && (!x.kategori || p.kategori === x.kategori)
                  );
                  return (
                    <li key={x.id} className="flex items-start justify-between gap-2 rounded-xl border border-border p-3 text-sm">
                      <div>
                        <b>{x.kata}</b> {x.kategori && <span className="text-muted-foreground">• {x.kategori}</span>}
                        <div className="mt-1 text-xs">
                          {hit.length ? (
                            <span className="flex flex-wrap items-center gap-1 text-green-700">
                              <Bell className="h-3.5 w-3.5" /> {hit.length} barang cocok:
                              {hit.map((h) => <Link key={h.id} href={`/produk/${h.id}`} className="underline">{h.judul.length > 24 ? h.judul.slice(0, 24) + "…" : h.judul}</Link>)}
                            </span>
                          ) : (
                            <span className="text-muted-foreground">Belum ada barang yang cocok</span>
                          )}
                        </div>
                      </div>
                      <button onClick={() => act((n) => { n.wishes = n.wishes.filter((y) => y.id !== x.id); }, "Dihapus dari wishlist.")} aria-label="Hapus">
                        <Trash2 className="h-4 w-4 text-destructive" />
                      </button>
                    </li>
                  );
                })}
                {wishes.length === 0 && <li className="text-sm text-muted-foreground">Belum ada incaran.</li>}
              </ul>
            </CardContent>
          </Card>

          <Card>
            <CardHeader><CardTitle>Riwayat Transaksi</CardTitle></CardHeader>
            <CardContent>
              {history.length === 0 && <p className="text-sm text-muted-foreground">Belum ada transaksi.</p>}
              {history.map((t) => (
                <div key={t.id} className="flex items-center justify-between gap-3 border-b border-border py-3 text-sm last:border-0">
                  <div className="min-w-0">
                    <p className="truncate font-medium">{d.products.find((p) => p.id === t.product_id)?.judul ?? "(barang dihapus)"}</p>
                    <p className="text-xs text-muted-foreground">{rp(t.harga)} • {me.id === t.buyer_id ? "beli" : "jual"} • {fmtTime(t.waktu)}</p>
                  </div>
                  <StatusBadge s={t.status} />
                </div>
              ))}
            </CardContent>
          </Card>

          <Card>
            <CardHeader><CardTitle>Ulasan & Keluhan Diterima</CardTitle></CardHeader>
            <CardContent>
              {reviews.length === 0 && <p className="text-sm text-muted-foreground">Belum ada ulasan.</p>}
              {reviews.map((r) => (
                <div key={r.id} className="border-b border-border py-3 text-sm last:border-0">
                  <div className="flex items-center gap-2">
                    <span className="flex">{[1, 2, 3, 4, 5].map((n) => <Star key={n} className={cn("h-3.5 w-3.5", n <= r.rating ? "fill-primary text-primary" : "text-muted-foreground/40")} />)}</span>
                    <span className="text-xs text-muted-foreground">
                      {r.jenis === "ULASAN" ? "Ulasan" : "Keluhan barang"} dari {d.users.find((u) => u.id === r.reviewer_id)?.nama}
                    </span>
                  </div>
                  <p className="mt-1">{r.komentar}</p>
                </div>
              ))}
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  );
}
