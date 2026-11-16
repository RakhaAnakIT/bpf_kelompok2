import Footer from "@/components/layout/Footer";
import Header from "@/components/layout/Header";
import Overlays from "@/components/layout/Overlays";
import ReadyGate from "@/components/layout/ReadyGate";
import { StoreProvider } from "@/lib/store";
import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";

const inter = Inter({
  variable: "--font-inter",
  subsets: ["latin"],
  display: "swap",
});

export const metadata: Metadata = {
  title: "PCR Mart - Marketplace Mahasiswa PCR",
  description: "Jual beli kebutuhan kuliah antarmahasiswa Politeknik Caltex Riau. COD di kampus, di sela jam kuliah.",
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="id">
      <body className={`${inter.className} flex min-h-screen flex-col antialiased`}>
        <StoreProvider>
          <Header />
          <main className="flex-grow">
            <ReadyGate>{children}</ReadyGate>
          </main>
          <Footer />
          <Overlays />
        </StoreProvider>
      </body>
    </html>
  );
}
