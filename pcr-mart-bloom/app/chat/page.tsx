"use client";

import StatusBadge from "@/components/shared/StatusBadge";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { FieldError, Label } from "@/components/ui/label";
import { NativeSelect } from "@/components/ui/native-select";
import { Textarea } from "@/components/ui/textarea";
import { LOKASI, METODE, type Review, type Tx } from "@/lib/seed";
import { useStore } from "@/lib/store";
import { fmtTime, rp, uid } from "@/lib/util";
import { cn } from "@/lib/utils";
import { ArrowLeft, CalendarPlus, Clock, ClipboardList, MessageCircle, Send, Star } from "lucide-react";
import Link from "next/link";
import { useRouter, useSearchParams } from "next/navigation";
import { Suspense, useEffect, useRef, useState } from "react";

const QUICK = [
  "Halo kak, barang ini masih tersedia?",
  "Bisa COD di kampus di sela jam kuliah?",
  "Boleh minta foto detail bagian dalamnya?",
  "Apakah harga masih bisa nego?",
];

type DealFields = Pick<Tx, "harga" | "lokasi" | "waktu" | "metode" | "catatan">;

function DealForm({ init, onSubmit, onCancel }: { init: DealFields; onSubmit: (f: DealFields) => void; onCancel: () => void }) {
  const [f, setF] = useState({ ...init, harga: String(init.harga) });
  const [err, setErr] = useState("");
  const s = (k: keyof typeof f, v: string) => setF((x) => ({ ...x, [k]: v }));
  const go = () => {
    if (!(+f.harga > 0)) return setErr("Harga deal harus diisi dengan angka ya.");
    if (!f.waktu) return setErr("Pilih tanggal & jam COD dulu ya.");
    if (new Date(f.waktu).getTime() < Date.now()) return setErr("Waktu COD tidak boleh di masa lalu.");
    onSubmit({ ...f, harga: +f.harga });
  };
  return (
    <div className="space-y-3 rounded-xl border border-border bg-muted p-4 text-foreground">
      <div className="grid grid-cols-2 gap-3">
        <div><Label>Harga Deal</Label><Input type="number" min={0} value={f.harga} onChange={(e) => s("harga", e.target.value)} /></div>
        <div>
          <Label>Metode Bayar</Label>
          <NativeSelect value={f.metode} onChange={(e) => s("metode", e.target.value)}>{METODE.map((m) => <option key={m}>{m}</option>)}</NativeSelect>
        </div>
        <div>
          <Label>Titik Temu</Label>
          <NativeSelect value={f.lokasi} onChange={(e) => s("lokasi", e.target.value)}>{LOKASI.map((m) => <option key={m}>{m}</option>)}</NativeSelect>
        </div>
        <div><Label>Tanggal & Jam</Label><Input type="datetime-local" value={f.waktu} onChange={(e) => s("waktu", e.target.value)} /></div>
      </div>
      <Input placeholder="Catatan (mis. setelah kelas jam 12)" value={f.catatan} onChange={(e) => s("catatan", e.target.value)} />
      <FieldError>{err}</FieldError>
      <div className="flex justify-end gap-2">
        <Button variant="outline" size="sm" onClick={onCancel}>Batal</Button>
        <Button size="sm" onClick={go}>Kirim</Button>
      </div>
    </div>
  );
}

function DealCard({ t }: { t: Tx }) {
  const { d, me, dealAction, act } = useStore();
  const [mode, setMode] = useState<"" | "resched" | "cancel" | "review">("");
  const [alasan, setAlasan] = useState("");
  const [rv, setRv] = useState<{ rating: number; komentar: string; jenis: Review["jenis"] }>({ rating: 5, komentar: "", jenis: "ULASAN" });
  const mineProposal = t.proposer_id === me.id;
  const reviewed = d.reviews.some((r) => r.tx_id === t.id && r.reviewer_id === me.id);
  const target = me.id === t.buyer_id ? t.seller_id : t.buyer_id;
  const active = t.status === "MENUNGGU_KONFIRMASI" || t.status === "DIJADWALKAN";

  const sendReview = () => {
    act(
      (n) => { n.reviews.push({ id: uid("r"), tx_id: t.id, reviewer_id: me.id, target_id: target, rating: rv.rating, komentar: rv.komentar.trim(), jenis: rv.jenis, ts: Date.now() }); },
      rv.jenis === "ULASAN" ? "Terima kasih, ulasanmu terkirim!" : "Laporan keluhan terkirim."
    );
    setMode("");
  };

  return (
    <div className="w-full min-w-[260px] space-y-2 rounded-xl border border-primary/30 bg-card p-4 text-sm text-card-foreground">
      <div className="flex items-center justify-between gap-2">
        <b className="flex items-center gap-1.5"><ClipboardList className="h-4 w-4 text-primary" /> Kesepakatan COD</b>
        <StatusBadge s={t.status} />
      </div>
      <p>💰 {rp(t.harga)} • {t.metode}</p>
      <p>📍 {t.lokasi} • 🕒 {fmtTime(t.waktu)}</p>
      {t.catatan && <p className="text-muted-foreground">“{t.catatan}”</p>}

      {mode === "" && (
        <div className="flex flex-wrap gap-2 pt-1">
          {t.status === "MENUNGGU_KONFIRMASI" && !mineProposal && (
            <>
              <Button size="sm" onClick={() => dealAction(t.id, "approve")}>Setujui Jadwal</Button>
              <Button size="sm" variant="outline" onClick={() => setMode("resched")}>Ajukan Ulang</Button>
            </>
          )}
          {t.status === "MENUNGGU_KONFIRMASI" && mineProposal && <p className="text-xs text-muted-foreground">Menunggu persetujuan pihak lain…</p>}
          {t.status === "DIJADWALKAN" && <Button size="sm" onClick={() => dealAction(t.id, "complete")}>Konfirmasi Serah Terima Selesai</Button>}
          {t.status === "SELESAI" && me.id === t.buyer_id && !reviewed && (
            <Button size="sm" variant="outline" onClick={() => setMode("review")}><Star /> Beri Ulasan / Laporkan Keluhan</Button>
          )}
        </div>
      )}
      {active && mode === "" && (
        <button className="text-xs text-destructive underline" onClick={() => setMode("cancel")}>Batalkan transaksi</button>
      )}

      {mode === "resched" && (
        <DealForm
          init={t}
          onCancel={() => setMode("")}
          onSubmit={(f) => { dealAction(t.id, "resched", f); setMode(""); }}
        />
      )}
      {mode === "cancel" && (
        <div className="space-y-2">
          <Input placeholder="Alasan pembatalan (wajib)" value={alasan} onChange={(e) => setAlasan(e.target.value)} />
          <div className="flex gap-2">
            <Button size="sm" variant="outline" onClick={() => setMode("")}>Kembali</Button>
            <Button size="sm" variant="destructive" disabled={alasan.trim().length < 3} onClick={() => { dealAction(t.id, "cancel", alasan.trim()); setMode(""); }}>
              Batalkan
            </Button>
          </div>
        </div>
      )}
      {mode === "review" && (
        <div className="space-y-2">
          <NativeSelect value={rv.jenis} onChange={(e) => setRv({ ...rv, jenis: e.target.value as Review["jenis"] })}>
            <option value="ULASAN">Ulasan penjual</option>
            <option value="KELUHAN_BARANG">Keluhan: barang tidak sesuai deskripsi</option>
          </NativeSelect>
          <div className="flex gap-1">
            {[1, 2, 3, 4, 5].map((n) => (
              <button key={n} onClick={() => setRv({ ...rv, rating: n })} aria-label={`${n} bintang`}>
                <Star className={cn("h-6 w-6", n <= rv.rating ? "fill-primary text-primary" : "text-muted-foreground/40")} />
              </button>
            ))}
          </div>
          <Textarea rows={2} placeholder="Ceritakan pengalamanmu…" value={rv.komentar} onChange={(e) => setRv({ ...rv, komentar: e.target.value })} />
          <div className="flex gap-2">
            <Button size="sm" variant="outline" onClick={() => setMode("")}>Batal</Button>
            <Button size="sm" disabled={rv.komentar.trim().length < 5} onClick={sendReview}>Kirim</Button>
          </div>
        </div>
      )}
    </div>
  );
}

function ChatInner() {
  const { d, me, sendMsg, createDeal } = useStore();
  const sp = useSearchParams();
  const router = useRouter();
  const pid = sp.get("p");
  const otherId = sp.get("u");
  const [text, setText] = useState("");
  const [deal, setDeal] = useState(false);
  const bottom = useRef<HTMLDivElement>(null);

  // satu thread = kombinasi (barang, lawan bicara), urut dari pesan terbaru
  const threads = Array.from(
    new Map(
      d.msgs
        .filter((m) => m.sender_id === me.id || m.receiver_id === me.id)
        .sort((a, b) => b.ts - a.ts)
        .map((m) => {
          const o = m.sender_id === me.id ? m.receiver_id : m.sender_id;
          return [m.product_id + "|" + o, { pid: m.product_id, o, last: m }] as const;
        })
        .reverse()
    ).values()
  ).sort((a, b) => b.last.ts - a.last.ts);

  const p = d.products.find((x) => x.id === pid);
  const other = d.users.find((u) => u.id === otherId);
  const msgs = d.msgs
    .filter((m) => m.product_id === pid && ((m.sender_id === me.id && m.receiver_id === otherId) || (m.sender_id === otherId && m.receiver_id === me.id)))
    .sort((a, b) => a.ts - b.ts);

  useEffect(() => {
    bottom.current?.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }, [msgs.length]);

  const send = (t: string) => {
    if (!t.trim() || !p || !other) return;
    sendMsg(p.id, other.id, t.trim());
    setText("");
  };

  return (
    <div className="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
      <h1 className="mb-6 text-3xl font-bold">Chat & Jadwal COD</h1>
      <div className="grid gap-4 md:h-[72vh] md:grid-cols-3">
        <Card className={cn("overflow-y-auto", pid && "hidden md:block")}>
          {threads.length === 0 && (
            <div className="flex flex-col items-center p-8 text-center text-sm text-muted-foreground">
              <MessageCircle className="mb-2 h-8 w-8" />
              Belum ada percakapan. Buka barang lalu tekan “Chat Penjual”.
            </div>
          )}
          {threads.map((t) => {
            const pr = d.products.find((x) => x.id === t.pid);
            const u = d.users.find((x) => x.id === t.o);
            const on = t.pid === pid && t.o === otherId;
            return (
              <Link
                key={t.pid + t.o}
                href={`/chat?p=${t.pid}&u=${t.o}`}
                className={cn("flex gap-3 border-b border-border p-4 transition-colors hover:bg-muted", on && "bg-accent")}
              >
                <div className="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-primary font-bold text-primary-foreground">{u?.nama[0]}</div>
                <div className="min-w-0">
                  <p className="text-sm font-semibold">{u?.nama}</p>
                  <p className="truncate text-xs text-muted-foreground">{pr?.judul ?? "(barang dihapus)"}</p>
                  <p className="truncate text-xs text-muted-foreground/80">{t.last.text}</p>
                </div>
              </Link>
            );
          })}
        </Card>

        <Card className={cn("flex min-h-[65vh] flex-col overflow-hidden md:col-span-2 md:min-h-0", !pid && "hidden md:flex")}>
          {!p || !other ? (
            <p className="m-auto p-6 text-muted-foreground">Pilih percakapan di samping.</p>
          ) : (
            <>
              <div className="flex items-center gap-3 border-b border-border p-4">
                <button className="md:hidden" onClick={() => router.push("/chat")} aria-label="Kembali"><ArrowLeft className="h-5 w-5" /></button>
                <div className="min-w-0 flex-1">
                  <p className="text-sm font-bold">{other.nama}</p>
                  <p className="flex items-center gap-1 text-xs text-amber-700"><Clock className="h-3 w-3" /> {other.jam_sibuk}</p>
                </div>
                <Link href={`/produk/${p.id}`} className="max-w-[40%] truncate text-xs text-primary hover:underline">{p.judul}</Link>
              </div>

              <div className="flex-1 space-y-3 overflow-y-auto bg-muted/50 p-4">
                {msgs.length === 0 && <p className="text-center text-xs text-muted-foreground">Mulai percakapan dengan template cepat di bawah.</p>}
                {msgs.map((m) => {
                  const mine = m.sender_id === me.id;
                  const t = m.offer_tx ? d.txs.find((x) => x.id === m.offer_tx) : null;
                  return (
                    <div key={m.id} className={cn("flex", mine && "justify-end")}>
                      {t ? (
                        <div className="max-w-[90%]">
                          <DealCard t={t} />
                          <p className={cn("mt-0.5 text-[10px] text-muted-foreground", mine && "text-right")}>{fmtTime(m.ts)}</p>
                        </div>
                      ) : (
                        <div className={cn("max-w-[85%] rounded-2xl px-4 py-2 text-sm", mine ? "bg-primary text-primary-foreground" : "border border-border bg-card")}>
                          {m.text}
                          <p className="mt-0.5 text-[10px] opacity-60">{fmtTime(m.ts)}</p>
                        </div>
                      )}
                    </div>
                  );
                })}
                <div ref={bottom} />
              </div>

              <div className="space-y-3 border-t border-border p-4">
                {deal && (
                  <DealForm
                    init={{ harga: p.harga, lokasi: p.lokasi, waktu: "", metode: "Tunai", catatan: "" }}
                    onCancel={() => setDeal(false)}
                    onSubmit={(f) => { createDeal(p.id, other.id, f); setDeal(false); }}
                  />
                )}
                <div className="flex gap-2 overflow-x-auto pb-1">
                  {QUICK.map((q) => (
                    <button key={q} onClick={() => send(q)} className="whitespace-nowrap rounded-full border border-border px-3 py-1 text-xs transition-colors hover:border-primary hover:bg-accent">
                      {q}
                    </button>
                  ))}
                </div>
                <form className="flex gap-2" onSubmit={(e) => { e.preventDefault(); send(text); }}>
                  <Button type="button" variant="outline" disabled={p.status === "TERJUAL"} onClick={() => setDeal(true)} title="Buat Jadwal COD">
                    <CalendarPlus /> <span className="hidden sm:inline">Jadwal COD</span>
                  </Button>
                  <Input value={text} placeholder="Tulis pesan…" onChange={(e) => setText(e.target.value)} />
                  <Button type="submit" size="icon" aria-label="Kirim"><Send /></Button>
                </form>
              </div>
            </>
          )}
        </Card>
      </div>
    </div>
  );
}

export default function Page() {
  return (
    <Suspense>
      <ChatInner />
    </Suspense>
  );
}
