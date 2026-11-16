"use client";

import ProductCard from "@/components/home/ProductCard";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { NativeSelect } from "@/components/ui/native-select";
import { KATEGORI, KONDISI, LOKASI, PRODI } from "@/lib/seed";
import { useStore } from "@/lib/store";
import { BadgeCheck, MapPin, PiggyBank } from "lucide-react";
import { useMemo, useState } from "react";

type Filter = { kat: string; prodi: string; kond: string; lok: string; min: string; max: string; only: boolean; sort: string };
const INIT: Filter = { kat: "", prodi: "", kond: "", lok: "", min: "", max: "", only: true, sort: "baru" };

const FEATURES = [
  { icon: MapPin, title: "COD di Kampus", desc: "Ketemu di kantin, perpustakaan, atau lab — di sela jam kuliah." },
  { icon: BadgeCheck, title: "Sesama Mahasiswa PCR", desc: "Penjual teridentifikasi dengan NIM & email kampus." },
  { icon: PiggyBank, title: "Lebih Hemat", desc: "Barang bekas layak pakai dengan saran harga wajar." },
];

export default function Home() {
  const { d, q } = useStore();
  const [f, setF] = useState<Filter>(INIT);
  const set = <K extends keyof Filter>(k: K, v: Filter[K]) => setF((s) => ({ ...s, [k]: v }));

  const list = useMemo(() => {
    const t = q.toLowerCase().trim();
    const r = d.products.filter(
      (p) =>
        (!t || p.judul.toLowerCase().includes(t) || p.mata_kuliah.toLowerCase().includes(t)) &&
        (!f.kat || p.kategori === f.kat) &&
        (!f.prodi || p.prodi_terkait === f.prodi) &&
        (!f.kond || p.kondisi === f.kond) &&
        (!f.lok || p.lokasi === f.lok) &&
        (!f.min || p.harga >= +f.min) &&
        (!f.max || p.harga <= +f.max) &&
        (!f.only || p.status !== "TERJUAL")
    );
    return [...r].sort((a, b) =>
      f.sort === "murah" ? a.harga - b.harga : f.sort === "mahal" ? b.harga - a.harga : b.created_at - a.created_at
    );
  }, [d.products, q, f]);

  const selects: { k: "kat" | "prodi" | "kond" | "lok"; ph: string; opts: readonly string[] }[] = [
    { k: "kat", ph: "Semua Kategori", opts: KATEGORI },
    { k: "prodi", ph: "Semua Prodi", opts: PRODI },
    { k: "kond", ph: "Semua Kondisi", opts: KONDISI },
    { k: "lok", ph: "Semua Lokasi COD", opts: LOKASI },
  ];

  return (
    <div className="min-h-screen bg-background px-4 py-8 sm:py-12 lg:px-8 lg:py-16">
      <div className="mx-auto mb-12 space-y-3 text-center">
        <h1 className="text-balance text-4xl font-semibold tracking-tight text-primary lg:leading-[1.1] xl:text-5xl xl:tracking-tighter">
          Kebutuhan Kuliah, dari Sesama Mahasiswa
        </h1>
        <p className="mx-auto max-w-3xl text-balance text-base text-foreground sm:text-lg">
          Buku acuan, modul praktikum, jas lab, kalkulator ilmiah — cari langsung dari kakak tingkat di Politeknik Caltex Riau.
        </p>
      </div>

      <div className="mx-auto mb-10 grid max-w-7xl gap-4 md:grid-cols-3">
        {FEATURES.map((ft) => (
          <div key={ft.title} className="flex items-start gap-4 rounded-xl border border-border bg-card p-5">
            <div className="rounded-lg bg-primary/10 p-3">
              <ft.icon className="h-6 w-6 text-primary" />
            </div>
            <div>
              <h2 className="mb-1 font-semibold text-foreground">{ft.title}</h2>
              <p className="text-sm text-muted-foreground">{ft.desc}</p>
            </div>
          </div>
        ))}
      </div>

      <Card className="mx-auto mb-6 max-w-7xl">
        <CardContent className="grid grid-cols-2 gap-3 p-4 md:grid-cols-4">
          {selects.map(({ k, ph, opts }) => (
            <NativeSelect key={k} value={f[k]} onChange={(e) => set(k, e.target.value)} aria-label={ph}>
              <option value="">{ph}</option>
              {opts.map((o) => (
                <option key={o}>{o}</option>
              ))}
            </NativeSelect>
          ))}
          <Input type="number" min={0} placeholder="Harga min" value={f.min} onChange={(e) => set("min", e.target.value)} aria-label="Harga minimum" />
          <Input type="number" min={0} placeholder="Harga maks" value={f.max} onChange={(e) => set("max", e.target.value)} aria-label="Harga maksimum" />
          <NativeSelect value={f.sort} onChange={(e) => set("sort", e.target.value)} aria-label="Urutkan">
            <option value="baru">Terbaru</option>
            <option value="murah">Termurah</option>
            <option value="mahal">Termahal</option>
          </NativeSelect>
          <label className="flex cursor-pointer items-center gap-2 text-sm">
            <input type="checkbox" className="accent-[var(--primary)]" checked={f.only} onChange={(e) => set("only", e.target.checked)} />
            Hanya yang tersedia
          </label>
        </CardContent>
      </Card>

      <div className="mx-auto mb-4 flex max-w-7xl items-center justify-between text-sm text-muted-foreground">
        <span>
          {list.length} barang ditemukan{q && <> untuk “<b className="text-foreground">{q}</b>”</>}
        </span>
        {JSON.stringify(f) !== JSON.stringify(INIT) && (
          <button className="text-primary hover:underline" onClick={() => setF(INIT)}>
            Reset filter
          </button>
        )}
      </div>

      <div className="mx-auto grid max-w-7xl grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {list.length > 0 ? (
          list.map((p) => <ProductCard key={p.id} p={p} />)
        ) : (
          <div className="col-span-full flex flex-col items-center justify-center py-16 text-center">
            <div className="mb-4 text-6xl">🔍</div>
            <h3 className="mb-2 text-xl font-semibold text-foreground">Belum ada barang yang cocok</h3>
            <p className="text-muted-foreground">Coba ubah filter, atau tambahkan ke Wishlist di menu Akun.</p>
          </div>
        )}
      </div>
    </div>
  );
}
