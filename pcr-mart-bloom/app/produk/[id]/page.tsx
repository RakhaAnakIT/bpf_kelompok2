"use client";
/* eslint-disable @next/next/no-img-element */

import ProductCard from "@/components/home/ProductCard";
import StatusBadge from "@/components/shared/StatusBadge";
import Thumb from "@/components/shared/Thumb";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Separator } from "@/components/ui/separator";
import { useStore } from "@/lib/store";
import { avgRating, promoText, rp } from "@/lib/util";
import { cn } from "@/lib/utils";
import { ArrowLeft, BadgeCheck, Check, Clock, Copy, GitCompare, Handshake, MapPin, MessageCircle, ShieldCheck, Star } from "lucide-react";
import Link from "next/link";
import { useParams } from "next/navigation";
import { useState } from "react";

export default function Detail() {
  const { id } = useParams<{ id: string }>();
  const { d, me, toast, compare, toggleCompare } = useStore();
  const [img, setImg] = useState(0);
  const p = d.products.find((x) => x.id === id);

  if (!p) {
    return (
      <div className="container mx-auto px-4 py-24 text-center">
        <div className="mb-4 text-6xl">📦</div>
        <h1 className="mb-2 text-2xl font-bold">Barang tidak ditemukan</h1>
        <p className="mb-6 text-muted-foreground">Barang mungkin sudah dihapus oleh penjualnya.</p>
        <Button asChild>
          <Link href="/">Kembali ke Katalog</Link>
        </Button>
      </div>
    );
  }

  const s = d.users.find((u) => u.id === p.seller_id)!;
  const rating = avgRating(d.reviews, s.id);
  const nRev = d.reviews.filter((r) => r.target_id === s.id && r.jenis === "ULASAN").length;
  const hemat = p.harga_baru - p.harga;
  const own = p.seller_id === me.id;
  const picked = compare.includes(p.id);
  const related = d.products.filter((x) => x.id !== p.id && x.kategori === p.kategori && x.status !== "TERJUAL").slice(0, 4);
  const yn = (b: boolean) => (b ? "Ya" : "Tidak");
  const rows: [string, string][] = [
    ["Kategori", p.kategori],
    ["Mata Kuliah", p.mata_kuliah],
    ["Prodi Terkait", p.prodi_terkait],
    ["Edisi / Tahun", p.edisi],
    ["Kondisi", p.kondisi],
    ["Halaman / komponen lengkap", yn(p.cek.halaman_lengkap)],
    ["Tanpa coretan / noda", yn(p.cek.tanpa_coretan)],
    ["Berfungsi normal", yn(p.cek.fungsi_normal)],
    ["Ukuran", p.cek.ukuran || "-"],
    ["Lokasi COD", p.lokasi],
  ];

  const copyPromo = async () => {
    try {
      await navigator.clipboard.writeText(promoText(p, s));
      toast("Format promosi WA disalin. Tempel di grup angkatan!");
    } catch {
      toast("Gagal menyalin. Browser tidak mengizinkan akses clipboard.", "err");
    }
  };

  return (
    <div className="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
      <nav className="mb-8">
        <Button variant="ghost" asChild className="text-muted-foreground hover:text-foreground">
          <Link href="/" className="flex items-center gap-2">
            <ArrowLeft className="h-4 w-4" /> Kembali ke Katalog
          </Link>
        </Button>
      </nav>

      <div className="mb-16 grid gap-12 lg:grid-cols-2">
        <div className="mx-auto w-full max-w-[500px] space-y-4">
          <div className="overflow-hidden rounded-xl shadow-lg">
            <Thumb p={p} src={p.fotos[img]} iconSize={120} />
          </div>
          {p.fotos.length > 1 && (
            <div className="flex gap-2">
              {p.fotos.map((f, i) => (
                <button
                  key={i}
                  onClick={() => setImg(i)}
                  aria-label={`Foto ${i + 1}`}
                  className={cn("h-16 w-16 overflow-hidden rounded-lg border-2", i === img ? "border-primary" : "border-border")}
                >
                  <img src={f} alt="" className="h-full w-full object-cover" />
                </button>
              ))}
            </div>
          )}
        </div>

        <div className="space-y-6">
          <div className="space-y-3">
            <StatusBadge s={p.status} />
            <h1 className="text-3xl font-bold text-foreground lg:text-4xl">{p.judul}</h1>
            <div className="flex items-center gap-2">
              <div className="flex items-center gap-1">
                {[...Array(5)].map((_, i) => (
                  <Star key={i} className={cn("h-4 w-4", i < Math.round(rating) ? "fill-primary text-primary" : "text-muted-foreground/40")} />
                ))}
              </div>
              <span className="text-sm text-muted-foreground">
                {rating ? `(${rating.toFixed(1)}) • ${nRev} ulasan penjual` : "Penjual baru"}
              </span>
            </div>
          </div>

          <div className="space-y-1">
            <span className="text-3xl font-bold text-foreground">{rp(p.harga)}</span>
            <p className="text-sm text-muted-foreground">
              Harga baru <span className="line-through">{rp(p.harga_baru)}</span> — kamu hemat{" "}
              <b className="text-green-700">
                {rp(hemat)} ({Math.round((hemat / p.harga_baru) * 100)}%)
              </b>
            </p>
          </div>

          <p className="leading-relaxed text-muted-foreground">{p.deskripsi}</p>

          <Separator />

          <div className="flex flex-col gap-4 sm:flex-row">
            {p.status === "TERJUAL" ? (
              <Button size="lg" className="flex-1" disabled>
                Barang sudah terjual
              </Button>
            ) : own ? (
              <Button size="lg" className="flex-1" asChild>
                <Link href={`/jual?edit=${p.id}`}>Edit barang ini</Link>
              </Button>
            ) : (
              <Button size="lg" className="flex-1" asChild>
                <Link href={`/chat?p=${p.id}&u=${s.id}`}>
                  <MessageCircle /> Chat Penjual
                </Link>
              </Button>
            )}
            <Button size="lg" variant="outline" className="flex-1" onClick={() => toggleCompare(p.id)}>
              {picked ? <Check /> : <GitCompare />} {picked ? "Dipilih untuk dibandingkan" : "Bandingkan"}
            </Button>
          </div>
          <Button variant="ghost" size="sm" className="text-muted-foreground hover:text-foreground" onClick={copyPromo}>
            <Copy className="mr-1 h-4 w-4" /> Salin Format Promosi WA
          </Button>

          <Card>
            <CardContent className="flex items-center gap-4 p-5">
              <div className="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-primary text-lg font-bold text-primary-foreground">
                {s.nama[0]}
              </div>
              <div className="min-w-0 flex-1">
                <p className="flex items-center gap-1 font-semibold">
                  {s.nama} <BadgeCheck className="h-4 w-4 text-blue-600" aria-label="NIM terverifikasi" />
                </p>
                <p className="text-xs text-muted-foreground">
                  {s.prodi} • Sem {s.semester} • NIM {s.nim}
                </p>
                <p className="mt-1 flex items-center gap-1 text-xs text-amber-700">
                  <Clock className="h-3.5 w-3.5" /> {s.jam_sibuk}
                </p>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>

      <Card className="mb-16">
        <CardContent className="grid gap-8 p-8 md:grid-cols-3">
          {[
            { icon: MapPin, title: `COD di ${p.lokasi}`, desc: "Titik temu disepakati lewat Kartu Kesepakatan COD." },
            { icon: Handshake, title: "Bayar saat Bertemu", desc: "Tunai, transfer bank, atau QRIS/e-wallet." },
            { icon: ShieldCheck, title: "Ulasan & Keluhan", desc: "Beri ulasan atau laporkan jika barang tidak sesuai." },
          ].map((ft) => (
            <div key={ft.title} className="flex items-start gap-4">
              <div className="rounded-lg bg-primary/10 p-3">
                <ft.icon className="h-6 w-6 text-primary" />
              </div>
              <div>
                <h2 className="mb-1 font-semibold text-foreground">{ft.title}</h2>
                <p className="text-sm text-muted-foreground">{ft.desc}</p>
              </div>
            </div>
          ))}
        </CardContent>
      </Card>

      <section className="mb-16">
        <h2 className="mb-6 text-2xl font-bold">Spesifikasi</h2>
        <Card>
          <CardContent className="p-0">
            <table className="w-full text-sm">
              <tbody>
                {rows.map(([k, v]) => (
                  <tr key={k} className="border-b border-border last:border-0">
                    <td className="w-1/2 px-6 py-3 text-muted-foreground">{k}</td>
                    <td className="px-6 py-3 font-medium">{v}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </CardContent>
        </Card>
      </section>

      {related.length > 0 && (
        <section>
          <h2 className="mb-6 text-2xl font-bold">Barang Serupa</h2>
          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {related.map((r) => (
              <ProductCard key={r.id} p={r} />
            ))}
          </div>
        </section>
      )}
    </div>
  );
}
