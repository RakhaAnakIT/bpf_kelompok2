import { cn } from "@/lib/utils";

const STATUS_CLS: Record<string, string> = {
  TERSEDIA: "bg-green-100 text-green-800",
  DIBOOKING: "bg-amber-100 text-amber-800",
  TERJUAL: "bg-red-100 text-red-700",
  MENUNGGU_KONFIRMASI: "bg-amber-100 text-amber-800",
  DIJADWALKAN: "bg-blue-100 text-blue-800",
  SELESAI: "bg-green-100 text-green-800",
  DIBATALKAN: "bg-muted text-muted-foreground",
};

export default function StatusBadge({ s, className }: { s: string; className?: string }) {
  return (
    <span className={cn("inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold tracking-wide", STATUS_CLS[s], className)}>
      {s.replace("_", " ")}
    </span>
  );
}
