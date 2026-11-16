import { Product, Review, User } from "./seed";
export const rp = (n:number) => "Rp " + Math.round(n).toLocaleString("id-ID");
export const uid = (p:string) => p + "-" + Math.random().toString(36).slice(2, 9);
export const fmtTime = (ts:number|string) => new Date(ts).toLocaleString("id-ID", { day:"numeric", month:"short", hour:"2-digit", minute:"2-digit" });
export function suggestPrice(baru:number, kondisi:string) {
  const f: Record<string,[number,number]> = { "Baru":[0.85,0.95], "Bekas - Seperti Baru":[0.6,0.75], "Bekas - Layak Pakai":[0.35,0.55] };
  const [a,b] = f[kondisi] || [0.4,0.6];
  const r = (x:number) => Math.round(x/5000)*5000;
  return [r(baru*a), r(baru*b)];
}
export const avgRating = (reviews:Review[], uid:string) => {
  const r = reviews.filter(x => x.target_id===uid && x.jenis==="ULASAN");
  return r.length ? r.reduce((s,x)=>s+x.rating,0)/r.length : 0;
};
export const promoText = (p:Product, seller:User) =>
  `📚 *DIJUAL - ${p.judul}*\n🎓 Mata kuliah: ${p.mata_kuliah}\n📖 ${p.edisi} | ${p.kondisi}\n💰 ${rp(p.harga)} (baru ${rp(p.harga_baru)})\n📍 COD: ${p.lokasi}\n👤 ${seller.nama} (${seller.prodi})\n\nChat di PCR Mart ya!`;
