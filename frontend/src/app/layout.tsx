import type { Metadata, Viewport } from 'next';
import './globals.css';
import { Providers } from '@/components/Providers';
import { Header } from '@/components/Header';
import { Footer } from '@/components/Footer';
import { LiveChatWidget } from '@/components/LiveChatWidget';
import { ScrollProgress } from '@/components/ScrollProgress';
import { AmbientCursorHalo } from '@/components/ui/AmbientCursorHalo';
import { getBootstrap } from '@/lib/api';

export const viewport: Viewport = {
  width: 'device-width',
  initialScale: 1,
  viewportFit: 'cover',
  themeColor: [
    { media: '(prefers-color-scheme: light)', color: '#F6F9FE' },
    { media: '(prefers-color-scheme: dark)', color: '#07080A' },
  ],
};

export async function generateMetadata(): Promise<Metadata> {
  const boot = await getBootstrap();
  const baseUrl = process.env.NEXT_PUBLIC_SITE_URL || 'https://creatsify.tech';

  return {
    metadataBase: new URL(baseUrl),
    title: {
      default: boot.site.title,
      template: `%s | ${boot.site.name}`,
    },
    description: boot.site.meta_description,
    applicationName: boot.site.name,
    authors: [{ name: 'Creatsify Digital Agency' }],
    robots: { index: true, follow: true },
    openGraph: {
      type: 'website',
      siteName: boot.site.name,
      title: boot.site.title,
      description: boot.site.meta_description,
      url: baseUrl,
      images: [{ url: '/assets/brand/og-image.png', width: 1200, height: 630, alt: 'Creatsify Digital Agency' }],
    },
    twitter: {
      card: 'summary_large_image',
      title: boot.site.title,
      description: boot.site.meta_description,
      images: ['/assets/brand/og-image.png'],
    },
    icons: {
      icon: [
        { url: '/favicon.ico', sizes: '32x32' },
        { url: '/favicon.svg', type: 'image/svg+xml' },
      ],
      apple: '/apple-touch-icon.png',
    },
    manifest: '/site.webmanifest',
  };
}

const THEME_INIT_SCRIPT = `(function(){try{var t=localStorage.getItem('creatsify-theme')||localStorage.getItem('creatsify_theme')||(window.matchMedia('(prefers-color-scheme: light)').matches?'light':'dark');document.documentElement.dataset.theme=t;document.documentElement.setAttribute('data-theme',t);}catch(e){document.documentElement.dataset.theme='dark';}})();`;

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const boot = await getBootstrap();

  return (
    <html lang="en" data-theme="dark" suppressHydrationWarning>
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        <link
          href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Sora:wght@500;600;700;800&display=swap"
          rel="stylesheet"
        />
        <link rel="preload" href="/assets/fonts/sora-400.woff2" as="font" type="font/woff2" crossOrigin="anonymous" />
        <link rel="preload" href="/assets/fonts/sora-700.woff2" as="font" type="font/woff2" crossOrigin="anonymous" />
        <link rel="stylesheet" href="/php-design.css" />
        <script dangerouslySetInnerHTML={{ __html: THEME_INIT_SCRIPT }} />
      </head>
      <body className="min-h-screen antialiased">
        <div className="bg" aria-hidden="true" />
        <div className="vg" aria-hidden="true" />
        <AmbientCursorHalo />
        <Providers>
          <ScrollProgress />
          <Header />
          <main id="top" className="relative">
            {children}
          </main>
          <Footer
            whatsappNumber={boot.site.whatsapp_number}
            footerProducts={boot.footer_products}
          />
          <LiveChatWidget />
        </Providers>
      </body>
    </html>
  );
}
