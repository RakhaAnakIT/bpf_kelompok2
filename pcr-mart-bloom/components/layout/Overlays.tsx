"use client";

import { useStore } from "@/lib/store";
import { cn } from "@/lib/utils";
import { GitCompare } from "lucide-react";
import Link from "next/link";
import { usePathname } from "next/navigation";

/** Toast notifikasi + bar "Bandingkan" yang melayang. */
export default function Overlays() {
  const { toasts, compare } = useStore();
  const path = usePathname();
  return (
    <>
      {compare.length > 0 && path !== "/bandingkan" && (
        <div className="fixed bottom-4 left-1/2 z-40 flex -translate-x-1/2 items-center gap-3 rounded-full border border-border bg-foreground px-4 py-2 text-sm text-background shadow-xl">
          <GitCompare className="h-4 w-4 text-primary" /> {compare.length} barang dipilih
          <Link href="/bandingkan" className="rounded-full bg-primary px-3 py-1 font-semibold text-primary-foreground">
            Bandingkan
          </Link>
        </div>
      )}
      <div className="fixed right-3 top-20 z-[60] w-72 space-y-2" aria-live="polite">
        {toasts.map((t) => (
          <div
            key={t.id}
            className={cn(
              "animate-in slide-in-from-right rounded-xl px-4 py-3 text-sm shadow-lg",
              t.type === "err" ? "bg-destructive text-destructive-foreground" : "border border-border bg-card text-card-foreground"
            )}
          >
            {t.text}
          </div>
        ))}
      </div>
    </>
  );
}
