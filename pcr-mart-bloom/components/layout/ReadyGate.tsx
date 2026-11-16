"use client";

import { useStore } from "@/lib/store";

/** Menunggu data localStorage dimuat agar tidak terjadi hydration mismatch. */
export default function ReadyGate({ children }: { children: React.ReactNode }) {
  const { ready } = useStore();
  if (!ready) {
    return (
      <div className="flex min-h-[50vh] items-center justify-center gap-3 text-muted-foreground">
        <div className="h-5 w-5 animate-spin rounded-full border-2 border-primary border-t-transparent" />
        Memuat...
      </div>
    );
  }
  return <>{children}</>;
}
