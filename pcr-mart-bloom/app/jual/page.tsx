"use client";
/* eslint-disable @next/next/no-img-element */

import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { FieldError, Label } from "@/components/ui/label";
import { NativeSelect } from "@/components/ui/native-select";
import { Textarea } from "@/components/ui/textarea";
import { KATEGORI, KONDISI, LOKASI, PRODI, type Product } from "@/lib/seed";
import { useStore } from "@/lib/store";
import { rp, suggestPrice } from "@/lib/util";
import { ImagePlus, Lightbulb, X } from "lucide-react";
import { useRouter, useSearchParams } from "next/navigation";
import { Suspense, useEffect, useState } from "react";
import { z } from "zod";

const schema = z.object({
  judul: z.string().trim().min(5, "Judul minimal 5 karakter ya."),
  kategori: z.string().min(1, "Pilih kategori dulu ya."),
  mata_kuliah: z.string().trim().min(1, "Isi mata kuliah terkait (atau '-')."),
  prodi_terkait: z.string().min(1, "Pilih program studi."),
  edisi: z.string().trim().min(1, "Isi edisi/tahun (atau '-')."),
  kondisi: z.string().min(1, "Pilih kondisi barang."),
  harga: z.coerce.number({ invalid_type_error: "Harga harus berupa angka." }).min(1000, "Harga minimal Rp 1.000."),
  harga_baru: z.coerce.number({ invalid_type_error: "Isi harga baru sebagai pembanding." }).min(1000, "Harga baru minimal Rp 1.000."),
  lokasi: z.string().min(1, "Pilih lokasi COD."),
  deskripsi: z.string().trim().min(10, "Deskripsi minimal 10 karakter agar pembeli yakin."),
}).refine((v) => v.harga <= v.harga_baru, { path: ["harga"], message: "Harga jual tidak boleh melebihi harga baru." });

type FormState = Record<keyof z.input<typeof schema>, string>;
const EMPTY: FormState = { judul: "", kategori: "", mata_kuliah: "", prodi_terkait: "", edisi: "", kondisi: "", harga: "", harga_baru: "", lokasi: "", deskripsi: "" };
const CEK_LABEL = [
  ["halaman_lengkap", "Halaman / komponen lengkap"],
  ["tanpa_coretan", "Tanpa coretan / noda"],
  ["fungsi_normal", "Berfungsi normal"],
] as const;

function Form() {
  const { d, saveProduct, me, toast } = useStore();
  const router = useRouter();
  const editId = useSearchParams().get("edit");
  const [f, setF] = useState<FormState>(EMPTY);
  const [cek, setCek] = useState<Product["cek"]>({ halaman_lengkap: true, tanpa_coretan: true, fungsi_normal: true, ukuran: "" });
  const [fotos, setFotos] = useState<string[]>([]);
  const [touched, setTouched] = useState<Partial<Record<keyof FormState, boolean>>>({});

  useEffect(() => {
    const p = d.products.find((x) => x.id === editId && x.seller_id === me.id);
    if (!p) return;
    setF({
      judul: p.judul, kategori: p.kategori, mata_kuliah: p.mata_kuliah, prodi_terkait: p.prodi_terkait, edisi: p.edisi,
      kondisi: p.kondisi, harga: String(p.harga), harga_baru: String(p.harga_baru), lokasi: p.lokasi, deskripsi: p.deskripsi,
    });
    setCek(p.cek);
    setFotos(p.fotos);
  }, [editId]); // eslint-disable-line react-hooks/exhaustive-deps

  const set = (k: keyof FormState, v: string) => {
    setF((s) => ({ ...s, [k]: v }));
    setTouched((t) => ({ ...t, [k]: true }));
  };

  const res = schema.safeParse(f);
  const errs: Partial<Record<keyof FormState, string>> = {};
  if (!res.success) res.error.issues.forEach((i) => { const k = i.path[0] as keyof FormState; errs[k] ||= i.message; });
  const err = (k: keyof FormState) => (touched[k] ? errs[k] : undefined);
  const sug = f.kondisi && +f.harga_baru > 0 ? suggestPrice(+f.harga_baru, f.kondisi) : null;

  const addFile = (files: FileList | null) => {
    if (!files) return;
    Array.from(files).slice(0, 4 - fotos.length).forEach((file) => {
      if (file.size > 800_000) return toast("Foto maksimal 800 KB per file agar ringan.", "err");
      const r = new FileReader();
      r.onload = () => setFotos((x) => (x.length < 4 ? [...x, r.result as string] : x));
      r.readAsDataURL(file);
    });
  };

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    setTouched(Object.fromEntries(Object.keys(EMPTY).map((k) => [k, true])));
    if (!res.success) return toast("Masih ada kolom yang perlu dilengkapi.", "err");
    saveProduct({ ...res.data, cek, fotos, ...(editId ? { id: editId } : {}) });
    setTimeout(() => router.push("/lapak"), 400);
  };

  const select = (k: keyof FormState, opts: readonly string[]) => (
    <NativeSelect id={k} value={f[k]} onChange={(e) => set(k, e.target.value)} aria-invalid={!!err(k)}>
      <option value="">Pilih...</option>
      {opts.map((o) => <option key={o}>{o}</option>)}
    </NativeSelect>
  );

  return (
    <div className="container mx-auto px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
      <Card className="mx-auto max-w-2xl">
        <CardHeader>
          <CardTitle className="text-2xl">{editId ? "Edit Barang" : "Jual Barang Baru"}</CardTitle>
          <CardDescription>Isi detail selengkap mungkin supaya pembeli cepat yakin.</CardDescription>
        </CardHeader>
        <CardContent>
          <form onSubmit={submit} className="space-y-5" noValidate>
            <div>
              <Label htmlFor="judul">Judul Barang</Label>
              <Input id="judul" value={f.judul} onChange={(e) => set("judul", e.target.value)} placeholder="mis. Buku Algoritma & Struktur Data (Cormen)" />
              <FieldError>{err("judul")}</FieldError>
            </div>

            <div className="grid gap-4 sm:grid-cols-2">
              <div><Label htmlFor="kategori">Kategori</Label>{select("kategori", KATEGORI)}<FieldError>{err("kategori")}</FieldError></div>
              <div><Label htmlFor="prodi_terkait">Program Studi Terkait</Label>{select("prodi_terkait", PRODI)}<FieldError>{err("prodi_terkait")}</FieldError></div>
              <div>
                <Label htmlFor="mata_kuliah">Mata Kuliah</Label>
                <Input id="mata_kuliah" value={f.mata_kuliah} onChange={(e) => set("mata_kuliah", e.target.value)} />
                <FieldError>{err("mata_kuliah")}</FieldError>
              </div>
              <div>
                <Label htmlFor="edisi">Edisi / Tahun</Label>
                <Input id="edisi" value={f.edisi} onChange={(e) => set("edisi", e.target.value)} />
                <FieldError>{err("edisi")}</FieldError>
              </div>
              <div><Label htmlFor="kondisi">Kondisi</Label>{select("kondisi", KONDISI)}<FieldError>{err("kondisi")}</FieldError></div>
              <div><Label htmlFor="lokasi">Lokasi COD</Label>{select("lokasi", LOKASI)}<FieldError>{err("lokasi")}</FieldError></div>
              <div>
                <Label htmlFor="harga_baru">Harga Baru (referensi)</Label>
                <Input id="harga_baru" type="number" min={0} value={f.harga_baru} onChange={(e) => set("harga_baru", e.target.value)} />
                <FieldError>{err("harga_baru")}</FieldError>
              </div>
              <div>
                <Label htmlFor="harga">Harga Jual</Label>
                <Input id="harga" type="number" min={0} value={f.harga} onChange={(e) => set("harga", e.target.value)} />
                <FieldError>{err("harga")}</FieldError>
              </div>
            </div>

            {sug && (
              <div className="flex gap-2 rounded-xl border border-primary/30 bg-accent p-3 text-sm text-accent-foreground">
                <Lightbulb className="mt-0.5 h-4 w-4 shrink-0" />
                <p>
                  Saran harga wajar ({f.kondisi}): <b>{rp(sug[0])} – {rp(sug[1])}</b>
                  {+f.harga > 0 &&
                    (+f.harga > sug[1] * 1.15
                      ? " — harga kamu agak di atas rata-rata, pembeli mungkin menawar."
                      : +f.harga < sug[0] * 0.85
                        ? " — harga kamu cukup murah, cepat laku!"
                        : " — harga kamu wajar ✅")}
                </p>
              </div>
            )}

            <fieldset className="space-y-3 rounded-xl border border-border p-4">
              <legend className="px-1 text-sm font-medium">Checklist Kondisi Fisik</legend>
              {CEK_LABEL.map(([k, l]) => (
                <label key={k} className="flex cursor-pointer items-center gap-2 text-sm">
                  <input type="checkbox" className="accent-[var(--primary)]" checked={cek[k]} onChange={(e) => setCek((c) => ({ ...c, [k]: e.target.checked }))} />
                  {l}
                </label>
              ))}
              <div>
                <Label htmlFor="ukuran">Ukuran (A4 / L / 15 inci / -)</Label>
                <Input id="ukuran" value={cek.ukuran} onChange={(e) => setCek((c) => ({ ...c, ukuran: e.target.value }))} />
              </div>
            </fieldset>

            <div>
              <Label htmlFor="deskripsi">Deskripsi</Label>
              <Textarea id="deskripsi" rows={4} value={f.deskripsi} onChange={(e) => set("deskripsi", e.target.value)} />
              <FieldError>{err("deskripsi")}</FieldError>
            </div>

            <div>
              <Label>Foto (maks. 4, opsional, ≤ 800 KB)</Label>
              <div className="flex flex-wrap gap-3">
                {fotos.map((x, i) => (
                  <div key={i} className="relative">
                    <img src={x} alt={`Foto ${i + 1}`} className="h-20 w-20 rounded-lg object-cover" />
                    <button
                      type="button"
                      onClick={() => setFotos(fotos.filter((_, j) => j !== i))}
                      className="absolute -right-2 -top-2 grid h-6 w-6 place-items-center rounded-full bg-destructive text-white shadow"
                      aria-label="Hapus foto"
                    >
                      <X className="h-3.5 w-3.5" />
                    </button>
                  </div>
                ))}
                {fotos.length < 4 && (
                  <label className="grid h-20 w-20 cursor-pointer place-items-center rounded-lg border-2 border-dashed border-border text-muted-foreground transition-colors hover:border-primary hover:text-primary">
                    <ImagePlus className="h-6 w-6" />
                    <input type="file" accept="image/*" multiple className="sr-only" onChange={(e) => { addFile(e.target.files); e.target.value = ""; }} />
                  </label>
                )}
              </div>
            </div>

            <div className="flex justify-end gap-2 pt-2">
              <Button type="button" variant="outline" onClick={() => router.back()}>Batal</Button>
              <Button type="submit">{editId ? "Simpan Perubahan" : "Publikasikan"}</Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  );
}

export default function Page() {
  return (
    <Suspense>
      <Form />
    </Suspense>
  );
}
