"use client";

import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import StatusBadge from "@/components/shared/StatusBadge";
import Thumb from "@/components/shared/Thumb";
import type { Product } from "@/lib/seed";
import { useStore } from "@/lib/store";
import { rp } from "@/lib/util";
import { cn } from "@/lib/utils";
import { Check, Eye, GitCompare, MapPin } from "lucide-react";
import Link from "next/link";

export default function ProductCard({ p, actions }: { p: Product; actions?: React.ReactNode }) {
  const { compare, toggleCompare } = useStore();
  const picked = compare.includes(p.id);
  const diskon = p.harga_baru > 0 ? Math.round(((p.harga_baru - p.harga) / p.harga_baru) * 100) : 0;

  return (
    <Card
      className={cn(
        "group flex flex-col overflow-hidden border-border bg-card transition-all duration-300 hover:-translate-y-1 hover:shadow-lg",
        p.status === "TERJUAL" && "opacity-70"
      )}
    >
      <div className="relative overflow-hidden">
        <StatusBadge s={p.status} className="absolute left-3 top-3 z-10 shadow-sm" />
        {diskon > 0 && (
          <span className="absolute bottom-3 left-3 z-10 rounded-full bg-primary px-2 py-0.5 text-[11px] font-bold text-primary-foreground shadow-sm">
            Hemat {diskon}%
          </span>
        )}

        {!actions && (
          <Button
            variant="ghost"
            size="icon"
            aria-pressed={picked}
            aria-label={picked ? "Batal bandingkan" : "Bandingkan"}
            title={picked ? "Batal bandingkan" : "Bandingkan"}
            onClick={() => toggleCompare(p.id)}
            className={cn(
              "absolute right-3 top-3 z-10 rounded-full bg-background/80 backdrop-blur-sm transition-all duration-200 hover:bg-background md:opacity-0 md:group-hover:opacity-100",
              picked && "bg-primary text-primary-foreground hover:bg-primary/90 md:opacity-100"
            )}
          >
            {picked ? <Check className="h-4 w-4" /> : <GitCompare className="h-4 w-4" />}
          </Button>
        )}

        <Link href={`/produk/${p.id}`} className="relative block">
          <Thumb p={p} className="transition-transform duration-300 group-hover:scale-105" />
          <div className="absolute inset-0 hidden items-center justify-center bg-background/60 opacity-0 transition-opacity duration-300 group-hover:opacity-100 md:flex">
            <span className="inline-flex items-center gap-2 rounded-sm bg-primary px-3 py-1.5 text-xs font-medium text-primary-foreground shadow">
              <Eye className="h-4 w-4" /> Lihat Detail
            </span>
          </div>
        </Link>
      </div>

      <CardContent className="flex flex-1 flex-col gap-2 p-4">
        <Link href={`/produk/${p.id}`}>
          <h2 className="line-clamp-2 font-semibold leading-snug text-foreground transition-colors hover:text-primary">{p.judul}</h2>
        </Link>
        <p className="text-xs text-muted-foreground">
          {p.kategori} • {p.kondisi}
        </p>
        <div className="flex flex-wrap items-baseline gap-2">
          <span className="text-lg font-bold text-foreground">{rp(p.harga)}</span>
          <span className="text-xs text-muted-foreground line-through">{rp(p.harga_baru)}</span>
        </div>
        <p className="flex items-center gap-1 text-xs text-muted-foreground">
          <MapPin className="h-3.5 w-3.5 text-primary" /> COD {p.lokasi}
        </p>
        <div className="mt-auto pt-2">
          {actions ?? (
            <Button
              variant={picked ? "default" : "outline"}
              size="sm"
              className="w-full"
              onClick={() => toggleCompare(p.id)}
            >
              {picked ? <Check /> : <GitCompare />}
              {picked ? "Dipilih untuk dibandingkan" : "Bandingkan"}
            </Button>
          )}
        </div>
      </CardContent>
    </Card>
  );
}
