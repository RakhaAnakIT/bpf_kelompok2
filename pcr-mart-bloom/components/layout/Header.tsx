"use client";

import { useStore } from "@/lib/store";
import { cn } from "@/lib/utils";
import { Bell, GitCompare, Leaf, Menu, PlusCircle, Search, User, X } from "lucide-react";
import Link from "next/link";
import { usePathname, useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { Button } from "../ui/button";

const navItems = [
  { href: "/", label: "Beranda" },
  { href: "/lapak", label: "Lapak Saya" },
  { href: "/chat", label: "Chat" },
  { href: "/bandingkan", label: "Bandingkan" },
];

export default function Header() {
  const { lite, setLite, q, setQ, compare, me, d } = useStore();
  const [isMobileOpen, setIsMobileOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [isSearchOpen, setIsSearchOpen] = useState(false);
  const pathname = usePathname();
  const router = useRouter();

  // jumlah tawaran COD yang menunggu persetujuan akun aktif
  const unread = d.txs.filter(
    (t) =>
      t.status === "MENUNGGU_KONFIRMASI" &&
      t.proposer_id !== me.id &&
      (t.buyer_id === me.id || t.seller_id === me.id)
  ).length;

  useEffect(() => {
    const onScroll = () => setIsScrolled(window.scrollY > 10);
    window.addEventListener("scroll", onScroll);
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    setIsMobileOpen(false);
  }, [pathname]);

  const isActive = (href: string) => (href === "/" ? pathname === "/" : pathname.startsWith(href));

  const onSearch = (value: string) => {
    setQ(value);
    if (pathname !== "/") router.push("/");
  };

  // dipanggil sebagai fungsi (bukan <Komponen/>) agar input tidak kehilangan fokus saat mengetik
  const searchBox = (autoFocus?: boolean) => (
    <form className="relative w-full" onSubmit={(e) => e.preventDefault()} role="search">
      <input
        type="search"
        placeholder="Cari barang atau mata kuliah..."
        value={q}
        onChange={(e) => onSearch(e.target.value)}
        autoFocus={autoFocus}
        className="w-full rounded-full border border-gray-300 py-2 pl-10 pr-4 text-sm transition-all focus:border-transparent focus:outline-none focus:ring-2 focus:ring-primary"
        aria-label="Cari barang"
      />
      <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
    </form>
  );

  return (
    <header
      className={cn(
        "sticky top-0 z-50 border-b border-gray-200 transition-all duration-300",
        isScrolled ? "bg-white/95 shadow-lg backdrop-blur-xl" : "bg-white/80 shadow-sm backdrop-blur-md"
      )}
    >
      <div className="container mx-auto px-4 py-4 sm:px-6">
        <div className="flex items-center justify-between gap-3">
          <div className="flex items-center space-x-6 lg:space-x-10">
            <Link href="/" className="whitespace-nowrap text-2xl font-semibold tracking-tight text-gray-900" aria-label="PCR Mart - Beranda">
              PCR<span className="text-primary">MART</span>
            </Link>
            <nav className="hidden items-center space-x-1 md:flex" aria-label="Navigasi utama">
              {navItems.map(({ href, label }) => (
                <Link
                  key={href}
                  href={href}
                  aria-current={isActive(href) ? "page" : undefined}
                  className={cn(
                    "relative rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200",
                    isActive(href) ? "bg-orange-100 shadow-md" : "text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                  )}
                >
                  {label}
                  {href === "/bandingkan" && compare.length > 0 && (
                    <span className="ml-1 rounded-full bg-primary px-1.5 text-[10px] font-bold text-white">{compare.length}</span>
                  )}
                </Link>
              ))}
            </nav>
          </div>

          <div className="mx-4 hidden max-w-md flex-1 xl:flex">
            {searchBox()}
          </div>

          <div className="flex items-center space-x-1 sm:space-x-2">
            <button
              onClick={() => setIsSearchOpen((v) => !v)}
              className="rounded-full p-2 transition-colors hover:bg-gray-100 xl:hidden"
              aria-label="Cari"
            >
              <Search className="h-5 w-5 text-gray-700" />
            </button>

            <button
              onClick={() => setLite(!lite)}
              aria-pressed={lite}
              title="Mode Hemat Kuota: gambar diganti ikon ringan"
              className={cn(
                "flex items-center gap-1 rounded-full px-2.5 py-2 text-xs font-medium transition-colors",
                lite ? "bg-green-600 text-white" : "text-gray-700 hover:bg-gray-100"
              )}
            >
              <Leaf className="h-4 w-4" />
              <span className="hidden lg:inline">Hemat Kuota{lite ? " ON" : ""}</span>
            </button>

            <Link href="/chat" className="relative rounded-full p-2 transition-colors hover:bg-gray-100" aria-label={`Notifikasi, ${unread} tawaran menunggu`}>
              <Bell className="h-5 w-5 text-gray-700" />
              {unread > 0 && (
                <span className="absolute -right-0.5 -top-0.5 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-primary px-1 text-xs font-bold text-white">
                  {unread}
                </span>
              )}
            </Link>

            <Link href="/bandingkan" className="relative rounded-full p-2 transition-colors hover:bg-gray-100 md:hidden" aria-label="Bandingkan">
              <GitCompare className="h-5 w-5 text-gray-700" />
              {compare.length > 0 && (
                <span className="absolute -right-0.5 -top-0.5 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-primary px-1 text-xs font-bold text-white">
                  {compare.length}
                </span>
              )}
            </Link>

            <div className="hidden items-center space-x-2 sm:flex">
              <Button variant="ghost" size="sm" asChild>
                <Link href="/akun">
                  <User /> {me.nama.split(" ")[0]}
                </Link>
              </Button>
              <Button size="sm" asChild>
                <Link href="/jual">
                  <PlusCircle /> Jual
                </Link>
              </Button>
            </div>

            <button
              onClick={() => setIsMobileOpen((v) => !v)}
              className="rounded-full p-2 transition-colors hover:bg-gray-100 md:hidden"
              aria-label="Buka menu navigasi"
              aria-expanded={isMobileOpen}
            >
              {isMobileOpen ? <X className="h-6 w-6 text-gray-700" /> : <Menu className="h-6 w-6 text-gray-700" />}
            </button>
          </div>
        </div>

        {isSearchOpen && (
          <div className="mt-4 animate-in slide-in-from-top duration-200 xl:hidden">
            {searchBox(true)}
          </div>
        )}

        {isMobileOpen && (
          <nav className="mt-4 animate-in slide-in-from-top duration-200 md:hidden" aria-label="Navigasi seluler">
            <div className="flex flex-col space-y-2 border-b border-gray-200 pb-4">
              {[...navItems, { href: "/akun", label: "Akun" }].map(({ href, label }) => (
                <Link
                  key={href}
                  href={href}
                  aria-current={isActive(href) ? "page" : undefined}
                  className={cn(
                    "rounded-lg px-3 py-2 text-sm font-medium transition-all",
                    isActive(href) ? "bg-orange-100" : "text-gray-700 hover:bg-gray-50 hover:text-gray-900"
                  )}
                >
                  {label}
                </Link>
              ))}
            </div>
            <div className="pt-4">
              <Button className="w-full" asChild>
                <Link href="/jual">
                  <PlusCircle /> Jual Barang
                </Link>
              </Button>
            </div>
          </nav>
        )}
      </div>
    </header>
  );
}
