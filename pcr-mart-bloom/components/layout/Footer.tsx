import { ArrowRight, Heart, Instagram, Mail, MapPin, MessageCircle, Phone } from "lucide-react";
import Link from "next/link";
import { Button } from "../ui/button";
import { Separator } from "../ui/separator";

const footerSections = [
  {
    title: "Belanja",
    links: [
      { href: "/", label: "Semua Barang" },
      { href: "/bandingkan", label: "Bandingkan Barang" },
      { href: "/akun", label: "Wishlist" },
    ],
  },
  {
    title: "Jualan",
    links: [
      { href: "/jual", label: "Jual Barang" },
      { href: "/lapak", label: "Lapak Saya" },
      { href: "/chat", label: "Jadwal COD" },
    ],
  },
  {
    title: "Akun",
    links: [
      { href: "/akun", label: "Profil" },
      { href: "/akun", label: "Riwayat Transaksi" },
      { href: "/akun", label: "Ulasan" },
    ],
  },
];

export default function Footer() {
  return (
    <footer className="mt-16 border-t border-border bg-background">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="border-b border-border py-12">
          <div className="mx-auto max-w-2xl text-center">
            <h3 className="mb-4 text-2xl font-bold text-foreground">Barang incaranmu belum ada?</h3>
            <p className="mb-6 text-muted-foreground">
              Tambahkan ke wishlist. PCR Mart akan menandai barang yang cocok begitu ada mahasiswa yang menjualnya.
            </p>
            <Button asChild>
              <Link href="/akun">
                Atur Wishlist <ArrowRight className="h-4 w-4" />
              </Link>
            </Button>
          </div>
        </div>

        <div className="py-12">
          <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-5">
            <div className="lg:col-span-2">
              <Link href="/" className="text-2xl font-semibold tracking-tight text-gray-900" aria-label="PCR Mart - Beranda">
                PCR<span className="text-primary">MART</span>
              </Link>
              <p className="mb-6 mt-2 max-w-sm text-muted-foreground">
                Marketplace jual beli kebutuhan kuliah antarmahasiswa Politeknik Caltex Riau. COD di kampus, di sela jam kuliah.
              </p>
              <div className="space-y-3 text-sm text-muted-foreground">
                <div className="flex items-center gap-3">
                  <MapPin className="h-4 w-4 text-primary" />
                  <span>Politeknik Caltex Riau, Jl. Umbansari No. 1, Rumbai, Pekanbaru</span>
                </div>
                <div className="flex items-center gap-3">
                  <Phone className="h-4 w-4 text-primary" />
                  <span>Prototipe — Proyek Semester BPF Kelompok 2</span>
                </div>
                <div className="flex items-center gap-3">
                  <Mail className="h-4 w-4 text-primary" />
                  <span>Akun simulasi memakai email @mahasiswa.pcr.ac.id</span>
                </div>
              </div>
              <div className="mt-6 flex gap-3">
                {[
                  { href: "/chat", icon: MessageCircle, label: "Chat" },
                  { href: "#", icon: Instagram, label: "Instagram" },
                ].map(({ href, icon: Icon, label }) => (
                  <Button
                    key={label}
                    variant="ghost"
                    size="icon"
                    asChild
                    className="h-10 w-10 rounded-full bg-muted transition-colors hover:bg-primary hover:text-primary-foreground"
                  >
                    <Link href={href} aria-label={label}>
                      <Icon className="h-4 w-4" />
                    </Link>
                  </Button>
                ))}
              </div>
            </div>

            {footerSections.map((section) => (
              <div key={section.title}>
                <h4 className="mb-4 text-sm font-semibold uppercase tracking-wider text-foreground">{section.title}</h4>
                <ul className="space-y-3">
                  {section.links.map((link) => (
                    <li key={link.label}>
                      <Link href={link.href} className="inline-block text-sm text-muted-foreground transition-colors hover:text-foreground">
                        {link.label}
                      </Link>
                    </li>
                  ))}
                </ul>
              </div>
            ))}
          </div>
        </div>

        <Separator className="my-2" />

        <div className="flex flex-col items-center justify-between gap-2 py-6 text-sm text-muted-foreground md:flex-row">
          <div className="flex items-center gap-2">
            <span>© {new Date().getFullYear()} PCR Mart. Dibuat dengan</span>
            <Heart className="h-4 w-4 fill-current text-red-500" />
            <span>oleh Kelompok 2.</span>
          </div>
          <p>
            Template UI:{" "}
            <a href="https://github.com/bloomtpl" target="_blank" rel="noopener noreferrer" className="font-bold transition-colors hover:text-primary">
              Bloomtpl
            </a>{" "}
            • Distributed by{" "}
            <a href="https://themewagon.com" target="_blank" rel="noopener noreferrer" className="font-bold transition-colors hover:text-primary">
              ThemeWagon
            </a>{" "}
            (MIT)
          </p>
        </div>
      </div>
    </footer>
  );
}
