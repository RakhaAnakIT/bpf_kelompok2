"use client";

import Thumb from "@/components/shared/Thumb";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import type { Product } from "@/lib/seed";
import { useStore } from "@/lib/store";
import { avgRating, rp } from "@/lib/util";
import { Check, X } from "lucide-react";
import Link from "next/link";

export default function Compare() {
  const { d, compare, toggleCompare, clearCompare } = useStore();
  const items = compare.map((id) => d.products.find((p) => p.id === id)).filter((p): p is Product => !!p);

  if (items.length < 2) {
    return (
      <div className="container mx-auto flex flex-col items-center px-4 py-24 text-center">
        <div className="mb-4 text-6xl">⚖️</div>
        <h1 className="mb-2 text-2xl font-bold">Pilih minimal 2 barang</h1>
        <p className="mb-6 max-w-md text-muted-foreground">
          Tekan tombol “Bandingkan” di kartu barang (maksimal 3) untuk membandingkan harga, kondisi, dan rating penjualnya.
        </p>
        <Button asChild>
          <Link href="/">Ke Katalog</Link>
        </Button>
      </div>
    );
  }

  const min = Math.min(...items.map((i) => i.harga));
  const yn = (b: boolean) => (b ? <Check className="h-4 w-4 text-green-600" /> : <X className="h-4 w-4 text-destructive" />);
  const rows: [string, (p: Product) => React.ReactNode][] = [
    ["Harga", (p) => <b className="text-lg">{rp(p.harga)}</b>],
    ["Selisih dari termurah", (p) => (p.harga === min ? <span className="font-semibold text-green-700">Termurah</span> : "+ " + rp(p.harga - min))],
    ["Hemat vs baru", (p) => rp(p.harga_baru - p.harga)],
    ["Edisi / Tahun", (p) => p.edisi],
    ["Kondisi", (p) => p.kondisi],
    ["Lengkap", (p) => yn(p.cek.halaman_lengkap)],
    ["Tanpa coretan", (p) => yn(p.cek.tanpa_coretan)],
    ["Fungsi normal", (p) => yn(p.cek.fungsi_normal)],
    ["Lokasi COD", (p) => p.lokasi],
    ["Rating penjual", (p) => { const r = avgRating(d.reviews, p.seller_id); return r ? `⭐ ${r.toFixed(1)}` : "Baru"; }],
  ];

  return (
    <div className="container mx-auto px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
      <div className="mb-8 flex items-end justify-between">
        <div>
          <h1 className="text-3xl font-bold">Bandingkan Barang</h1>
          <p className="text-muted-foreground">{items.length} barang dipilih</p>
        </div>
        <Button variant="outline" onClick={clearCompare}>Kosongkan</Button>
      </div>
      <Card className="overflow-x-auto">
        <table className="w-full min-w-[640px] text-sm">
          <thead>
            <tr>
              <th className="w-40 p-4" />
              {items.map((p) => (
                <th key={p.id} className="p-4 text-left align-top font-normal">
                  <div className="overflow-hidden rounded-lg">
                    <Thumb p={p} className="max-h-40" iconSize={44} />
                  </div>
                  <Link href={`/produk/${p.id}`} className="mt-2 block font-semibold hover:text-primary">{p.judul}</Link>
                  <button onClick={() => toggleCompare(p.id)} className="text-xs text-destructive hover:underline">Hapus</button>
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {rows.map(([label, fn]) => (
              <tr key={label} className="border-t border-border">
                <td className="p-4 font-medium text-muted-foreground">{label}</td>
                {items.map((p) => <td key={p.id} className="p-4">{fn(p)}</td>)}
              </tr>
            ))}
          </tbody>
        </table>
      </Card>
    </div>
  );
}
