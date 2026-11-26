import type { Metadata, Viewport } from 'next';
import { JetBrains_Mono, Plus_Jakarta_Sans, Sora } from 'next/font/google';
import './globals.css';
import { Providers } from '@/components/Providers';
import { Header } from '@/components/Header';
import { Footer } from '@/components/Footer';
import { LiveChatWidget } from '@/components/LiveChatWidget';
import { ScrollProgress } from '@/components/ScrollProgress';
import { getBootstrap } from '@/lib/api';

const sora = Sora({
  subsets: ['latin'],
  weight: ['500', '600', '700', '800'],
  variable: '--font-sora',
  display: 'swap',
});

const jakarta = Plus_Jakarta_Sans({
  subsets: ['latin'],
  weight: ['400', '500', '600', '700'],
  variable: '--font-jakarta',
  display: 'swap',
});

const jetbrains = JetBrains_Mono({
  subsets: ['latin'],
  weight: ['400', '500', '600'],
  variable: '--font-jetbrains',
  display: 'swap',
});

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
    <html
      lang="en"
      data-theme="dark"
      suppressHydrationWarning
      className={`${sora.variable} ${jakarta.variable} ${jetbrains.variable}`}
    >
      <head>
        <script dangerouslySetInnerHTML={{ __html: THEME_INIT_SCRIPT }} />
      </head>
      <body className="min-h-screen antialiased">
        <div className="creatsify-ambient-bg" aria-hidden="true" />
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
