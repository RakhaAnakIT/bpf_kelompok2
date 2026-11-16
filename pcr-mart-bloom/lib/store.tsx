"use client";
import { createContext, useContext, useEffect, useState, ReactNode, useCallback } from "react";
import { Data, seedData, Product, Status, Tx, User } from "./seed";
import { uid } from "./util";

const KEY = "pcr-mart-v1";
export interface Toast { id:number; text:string; type:"ok"|"err"|"info"; }

interface Ctx {
  d: Data; ready: boolean; me: User; busy: boolean;
  lite: boolean; setLite:(v:boolean)=>void;
  q: string; setQ:(v:string)=>void;
  compare: string[]; toggleCompare:(id:string)=>void; clearCompare:()=>void;
  toasts: Toast[]; toast:(text:string, type?:Toast["type"])=>void;
  act:(fn:(d:Data)=>void, msg?:string)=>Promise<void>;
  switchUser:(id:string)=>void; reset:()=>void;
  saveProduct:(p:Partial<Product>&{id?:string})=>string;
  setStatus:(id:string, s:Status)=>void; delProduct:(id:string)=>void;
  sendMsg:(pid:string,to:string,text:string)=>void;
  createDeal:(pid:string,to:string,f:Pick<Tx,"harga"|"lokasi"|"waktu"|"metode"|"catatan">)=>void;
  dealAction:(txId:string, a:"approve"|"complete"|"cancel"|"resched", extra?:any)=>void;
}
const C = createContext<Ctx>(null as any);
export const useStore = () => useContext(C);

export function StoreProvider({ children }: { children: ReactNode }) {
  const [d, setD] = useState<Data>(seedData());
  const [ready, setReady] = useState(false);
  const [busy, setBusy] = useState(false);
  const [lite, setLiteS] = useState(false);
  const [q, setQ] = useState("");
  const [compare, setCompare] = useState<string[]>([]);
  const [toasts, setToasts] = useState<Toast[]>([]);

  useEffect(() => {
    try { const raw = localStorage.getItem(KEY); if (raw) setD(JSON.parse(raw)); } catch {}
    setLiteS(localStorage.getItem("pcr-lite") === "1");
    setReady(true);
  }, []);
  useEffect(() => { if (ready) localStorage.setItem(KEY, JSON.stringify(d)); }, [d, ready]);

  const toast = useCallback((text:string, type:Toast["type"]="ok") => {
    const id = Date.now() + Math.random();
    setToasts(t => [...t, { id, text, type }]);
    setTimeout(() => setToasts(t => t.filter(x => x.id !== id)), 3500);
  }, []);
  const setLite = (v:boolean) => { setLiteS(v); localStorage.setItem("pcr-lite", v ? "1":"0"); toast(v ? "Mode Hemat Kuota aktif. Gambar diganti ikon ringan." : "Mode Hemat Kuota dimatikan."); };

  // simulasi delay API, lalu mutasi state secara immutable (deep clone sederhana)
  const act = useCallback(async (fn:(d:Data)=>void, msg?:string) => {
    setBusy(true);
    await new Promise(r => setTimeout(r, 250));
    setD(prev => { const n: Data = JSON.parse(JSON.stringify(prev)); fn(n); return n; });
    setBusy(false);
    if (msg) toast(msg);
  }, [toast]);

  const me = d.users.find(u => u.id === d.me) || d.users[0];
  const toggleCompare = (id:string) => setCompare(c => c.includes(id) ? c.filter(x=>x!==id) : c.length>=3 ? (toast("Maksimal 3 barang untuk dibandingkan.","err"), c) : [...c,id]);

  const saveProduct: Ctx["saveProduct"] = (p) => {
    const id = p.id || uid("p");
    act(n => {
      const i = n.products.findIndex(x => x.id === id);
      if (i >= 0) n.products[i] = { ...n.products[i], ...p, updated_at: Date.now() } as Product;
      else n.products.unshift({ ...(p as Product), id, seller_id: n.me, status:"TERSEDIA", created_at: Date.now(), updated_at: Date.now() });
    }, p.id ? "Barang berhasil diperbarui." : "Barang berhasil dipublikasikan ke katalog!");
    return id;
  };
  const setStatus = (id:string, s:Status) => act(n => { const p = n.products.find(x=>x.id===id); if (p) p.status = s; },
    s==="TERJUAL" ? "Status barang diubah menjadi TERJUAL. Barang dikunci dari katalog." : `Status barang diubah menjadi ${s}.`);
  const delProduct = (id:string) => act(n => { n.products = n.products.filter(x=>x.id!==id); }, "Barang dihapus dari lapak.");

  const sendMsg = (pid:string,to:string,text:string) => act(n => { n.msgs.push({ id:uid("m"), product_id:pid, sender_id:n.me, receiver_id:to, text, ts:Date.now() }); });
  const createDeal: Ctx["createDeal"] = (pid,to,f) => {
    const p = d.products.find(x=>x.id===pid)!;
    act(n => {
      const buyer = p.seller_id === n.me ? to : n.me;
      const id = uid("t");
      n.txs.push({ id, product_id:pid, buyer_id:buyer, seller_id:p.seller_id, proposer_id:n.me, status:"MENUNGGU_KONFIRMASI", ...f });
      n.msgs.push({ id:uid("m"), product_id:pid, sender_id:n.me, receiver_id:to, text:"Kesepakatan COD diajukan", offer_tx:id, ts:Date.now() });
    }, "Tawaran jadwal COD terkirim. Menunggu persetujuan.");
  };
  const dealAction: Ctx["dealAction"] = (txId, a, extra) => act(n => {
    const t = n.txs.find(x=>x.id===txId)!; const p = n.products.find(x=>x.id===t.product_id);
    const other = n.me === t.buyer_id ? t.seller_id : t.buyer_id;
    const note = (text:string) => n.msgs.push({ id:uid("m"), product_id:t.product_id, sender_id:n.me, receiver_id:other, text, ts:Date.now() });
    if (a==="approve") { t.status="DIJADWALKAN"; if (p) p.status="DIBOOKING"; note("Jadwal disetujui ✅"); }
    if (a==="complete") { t.status="SELESAI"; if (p) p.status="TERJUAL"; note("Serah terima selesai 🎉"); }
    if (a==="cancel") { t.status="DIBATALKAN"; if (p) p.status="TERSEDIA"; note("Transaksi dibatalkan. Alasan: " + extra); }
    if (a==="resched") { Object.assign(t, extra, { proposer_id:n.me, status:"MENUNGGU_KONFIRMASI" }); note("Mengajukan ulang jadwal COD"); n.msgs[n.msgs.length-1].offer_tx = t.id; }
  }, { approve:"Jadwal disetujui. Barang berstatus DIBOOKING.", complete:"Serah terima selesai. Barang berstatus TERJUAL.", cancel:"Transaksi dibatalkan. Barang kembali TERSEDIA.", resched:"Jadwal baru diajukan." }[a]);

  const switchUser = (id:string) => { setD(p => ({ ...p, me:id })); toast("Akun diganti ke " + d.users.find(u=>u.id===id)?.nama); };
  const reset = () => { setD(seedData()); setCompare([]); toast("Data dikembalikan ke awal."); };

  return <C.Provider value={{ d, ready, me, busy, lite, setLite, q, setQ, compare, toggleCompare, clearCompare:()=>setCompare([]), toasts, toast, act, switchUser, reset, saveProduct, setStatus, delProduct, sendMsg, createDeal, dealAction }}>{children}</C.Provider>;
}
