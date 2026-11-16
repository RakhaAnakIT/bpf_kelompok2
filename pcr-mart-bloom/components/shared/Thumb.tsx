"use client";
/* eslint-disable @next/next/no-img-element */
import { BookOpen, Calculator, FileText, Laptop, PencilRuler, Shirt, type LucideIcon } from "lucide-react";
import type { Product } from "@/lib/seed";
import { useStore } from "@/lib/store";
import { cn } from "@/lib/utils";

export const ICONS: Record<string, LucideIcon> = {
  "Buku Acuan": BookOpen,
  "Modul Praktikum": FileText,
  "Jas Lab": Shirt,
  "Kalkulator Ilmiah": Calculator,
  "Alat Tulis & Gambar": PencilRuler,
  "Aksesori Laptop": Laptop,
};

/** Foto barang; saat Mode Hemat Kuota aktif / tanpa foto, diganti ikon kategori. */
export default function Thumb({ p, src, className, iconSize = 56 }: { p: Product; src?: string; className?: string; iconSize?: number }) {
  const { lite } = useStore();
  const Icon = ICONS[p.kategori] || BookOpen;
  const img = src ?? p.fotos[0];
  if (lite || !img) {
    return (
      <div className={cn("grid aspect-square w-full place-items-center bg-gradient-to-br from-accent to-muted text-primary", className)}>
        <Icon size={iconSize} strokeWidth={1.5} />
      </div>
    );
  }
  return <img src={img} alt={p.judul} loading="lazy" className={cn("aspect-square w-full object-cover", className)} />;
}
