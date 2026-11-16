"use client";

import ProductCard from "@/components/home/ProductCard";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { NativeSelect } from "@/components/ui/native-select";
import type { Status } from "@/lib/seed";
import { useStore } from "@/lib/store";
import { Pencil, PlusCircle, Trash2 } from "lucide-react";
import Link from "next/link";
import { useState } from "react";

export default function Lapak() {
  const { d, me, setStatus, delProduct } = useStore();
  const [del, setDel] = useState<string | null>(null);
  const mine = d.products.filter((p) => p.seller_id === me.id);
  const count = (s: Status) => mine.filter((p) => p.status === s).length;

  return (
    <div className="container mx-auto px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
      <div className="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
          <h1 className="text-3xl font-bold">Lapak Saya</h1>
          <p className="text-muted-foreground">
            {mine.length} barang • {count("TERSEDIA")} tersedia • {count("DIBOOKING")} dibooking • {count("TERJUAL")} terjual
          </p>
        </div>
        <Button asChild>
          <Link href="/jual">
            <PlusCircle /> Tambah Barang
          </Link>
        </Button>
      </div>

      {mine.length === 0 ? (
        <div className="flex flex-col items-center py-16 text-center">
          <div className="mb-4 text-6xl">🏪</div>
          <h3 className="mb-2 text-xl font-semibold">Lapakmu masih kosong</h3>
          <p className="mb-6 text-muted-foreground">Punya buku atau jas lab yang sudah tidak terpakai? Jual ke adik tingkat.</p>
          <Button asChild>
            <Link href="/jual">Jual barang pertamamu</Link>
          </Button>
        </div>
      ) : (
        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          {mine.map((p) => (
            <ProductCard
              key={p.id}
              p={p}
              actions={
                <div className="space-y-2">
                  <NativeSelect value={p.status} onChange={(e) => setStatus(p.id, e.target.value as Status)} aria-label="Ubah status">
                    <option>TERSEDIA</option>
                    <option>DIBOOKING</option>
                    <option>TERJUAL</option>
                  </NativeSelect>
                  <div className="flex gap-2">
                    <Button variant="outline" size="sm" className="flex-1" asChild>
                      <Link href={`/jual?edit=${p.id}`}>
                        <Pencil /> Edit
                      </Link>
                    </Button>
                    <Button variant="outline" size="sm" className="text-destructive hover:text-destructive" onClick={() => setDel(p.id)} aria-label="Hapus">
                      <Trash2 />
                    </Button>
                  </div>
                </div>
              }
            />
          ))}
        </div>
      )}

      {del && (
        <div className="fixed inset-0 z-[70] grid place-items-center bg-black/50 p-4" role="dialog" aria-modal="true" onClick={() => setDel(null)}>
          <Card className="w-full max-w-sm" onClick={(e) => e.stopPropagation()}>
            <CardContent className="space-y-3 p-6">
              <p className="text-lg font-bold">Yakin ingin menghapus barang ini?</p>
              <p className="text-sm text-muted-foreground">Barang akan hilang dari katalog dan tidak bisa dikembalikan.</p>
              <div className="flex justify-end gap-2 pt-2">
                <Button variant="outline" onClick={() => setDel(null)}>Batal</Button>
                <Button variant="destructive" onClick={() => { delProduct(del); setDel(null); }}>Ya, Hapus</Button>
              </div>
            </CardContent>
          </Card>
        </div>
      )}
    </div>
  );
}
