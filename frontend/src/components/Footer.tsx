'use client';

import React from 'react';
import Link from 'next/link';
import { ArrowUp, ArrowUpRight, Check, Globe, MessageCircle } from 'lucide-react';
import { useTheme } from './Providers';
import { whatsappLink } from '@/lib/utils';

interface FooterProps {
  whatsappNumber: string;
  footerProducts: Array<{
    slug: string;
    title: string;
    price?: string | null;
  }>;
}

export function Footer({ whatsappNumber, footerProducts }: FooterProps) {
  const { theme } = useTheme();
  const waHref = whatsappLink(
    whatsappNumber,
    'Hello! I would like to discuss a project or package with Creatsify Digital Agency.'
  );

  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  return (
    <footer className="relative mt-24 border-t border-[var(--line)] bg-[var(--bg-subtle)]">
      <div className="mx-auto max-w-[1220px] px-5 py-16 sm:px-6 lg:py-20">
        {/* Pre-Footer Conversion Callout */}
        <div className="relative overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[var(--surface)] p-8 sm:p-10 lg:p-12">
          <div
            aria-hidden="true"
            className="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full opacity-20 blur-3xl"
            style={{ background: 'radial-gradient(circle, var(--accent) 0%, transparent 70%)' }}
          />
          <div className="relative flex flex-col justify-between gap-8 lg:flex-row lg:items-center">
            <div className="max-w-2xl">
              <h2 className="text-2xl font-bold tracking-tight text-[var(--text)] sm:text-3xl lg:text-[34px]">
                Let&apos;s build something your customers will love
              </h2>
              <p className="mt-3 text-[15px] leading-relaxed text-[var(--muted)]">
                Tell us about your idea — a custom web platform, a Flutter mobile app, a high-converting redesign, brand identity, or growth campaign — and we&apos;ll reply within 24 hours with a clear plan, timeline, and quote.
              </p>
              <div className="mt-5 flex flex-wrap gap-x-5 gap-y-2 text-[13px] font-medium text-[var(--text)]">
                {['Websites & mobile apps', 'Brand & UI/UX design', 'SEO & paid ads', '100% code ownership'].map(
                  (item) => (
                    <span key={item} className="inline-flex items-center gap-1.5">
                      <Check className="h-3.5 w-3.5 text-[var(--emerald)]" />
                      {item}
                    </span>
                  )
                )}
              </div>
            </div>

            <div className="flex flex-col gap-3 sm:flex-row lg:min-w-[220px] lg:flex-col">
              <Link href="/contact" className="btn-brand w-full">
                <span>Start a project</span>
                <ArrowUpRight className="h-4 w-4" />
              </Link>
              <a
                href={waHref}
                target="_blank"
                rel="noopener noreferrer"
                className="btn-ghost w-full !border-emerald-500/35 !text-emerald-500 hover:!bg-emerald-500/10"
              >
                <MessageCircle className="h-4 w-4" />
                <span>Chat on WhatsApp</span>
              </a>
            </div>
          </div>
        </div>

        {/* Multi-column footer directory */}
        <div className="mt-16 grid gap-10 sm:grid-cols-2 lg:grid-cols-12">
          <div className="lg:col-span-4">
            <Link href="/" className="inline-block" aria-label="Creatsify Home">
              <img
                src={theme === 'light' ? '/assets/brand/creatsify-logo-light.svg' : '/assets/brand/creatsify-logo-dark.svg'}
                alt="Creatsify"
                className="h-9 w-auto"
              />
            </Link>
            <p className="mt-4 max-w-sm text-sm leading-relaxed text-[var(--muted)]">
              A senior digital engineering, branding, and growth studio. We build custom software, Flutter apps, and commercial platforms for ambitious businesses worldwide.
            </p>
            <div className="mt-5 flex flex-wrap items-center gap-3">
              <Link
                href="/reviews"
                className="inline-flex items-center gap-2 rounded-full border border-[var(--line)] bg-[var(--surface)] px-3.5 py-1.5 text-xs font-medium text-[var(--text)] transition-colors hover:border-[var(--accent)]"
              >
                <span className="h-2 w-2 rounded-full bg-[var(--emerald)]" />
                Read verified client reviews
              </Link>
              <span className="inline-flex items-center gap-1.5 text-xs text-[var(--muted)]">
                <Globe className="h-3.5 w-3.5" />
                Worldwide delivery
              </span>
            </div>
          </div>

          <div className="lg:col-span-3">
            <h3 className="text-sm font-semibold text-[var(--text)]">Services</h3>
            <ul className="mt-4 space-y-2.5 text-sm text-[var(--muted)]">
              {[
                ['Custom web development', '/services#custom-web-development'],
                ['Mobile apps (Flutter)', '/services#mobile-app-development'],
                ['Website redesign', '/services#website-redesign-modernization'],
                ['SaaS engineering', '/services#saas-product-engineering'],
                ['Technical SEO', '/services#search-engine-optimization-seo'],
                ['Social media & ads', '/services#social-media-marketing-ads'],
                ['Branding & logos', '/services#branding-logo-graphic-design'],
              ].map(([label, href]) => (
                <li key={href}>
                  <Link href={href} className="transition-colors hover:text-[var(--text)]">
                    {label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div className="lg:col-span-3">
            <h3 className="text-sm font-semibold text-[var(--text)]">Ready-made platforms</h3>
            <ul className="mt-4 space-y-2.5 text-sm text-[var(--muted)]">
              {footerProducts.map((prod) => (
                <li key={prod.slug}>
                  <Link
                    href={`/portfolio/${prod.slug}`}
                    className="inline-flex items-center gap-2 transition-colors hover:text-[var(--text)]"
                  >
                    <span>{prod.title}</span>
                    {prod.price && (
                      <span className="rounded-md bg-[var(--accent-soft)] px-1.5 py-0.5 font-mono text-[11px] font-semibold text-[var(--accent)] tabular-nums">
                        {prod.price}
                      </span>
                    )}
                  </Link>
                </li>
              ))}
              <li>
                <Link
                  href="/contact?kind=software_presale&offering=Customize+Ready-Made+Software"
                  className="transition-colors hover:text-[var(--text)]"
                >
                  Request customization
                </Link>
              </li>
              <li>
                <Link href="/software" className="font-medium text-[var(--accent)] hover:underline">
                  Browse all platforms →
                </Link>
              </li>
            </ul>
          </div>

          <div className="lg:col-span-2">
            <h3 className="text-sm font-semibold text-[var(--text)]">Company</h3>
            <ul className="mt-4 space-y-2.5 text-sm text-[var(--muted)]">
              {[
                ['Our work', '/portfolio'],
                ['Packages & pricing', '/packages'],
                ['Client reviews', '/reviews'],
                ['About Creatsify', '/about'],
                ['Blog', '/blog'],
                ['Script support', '/support'],
                ['Contact us', '/contact'],
                ['Privacy & terms', '/privacy'],
              ].map(([label, href]) => (
                <li key={href}>
                  <Link href={href} className="transition-colors hover:text-[var(--text)]">
                    {label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        </div>

        {/* Bottom Copyright Bar */}
        <div className="mt-14 flex flex-col items-center justify-between gap-4 border-t border-[var(--line)] pt-7 text-xs text-[var(--muted)] sm:flex-row">
          <p>&copy; {new Date().getFullYear()} Creatsify Digital Agency. All rights reserved.</p>
          <button
            type="button"
            onClick={scrollToTop}
            className="inline-flex items-center gap-1.5 rounded-full border border-[var(--line)] bg-[var(--surface)] px-3.5 py-1.5 text-xs font-medium text-[var(--text)] transition-colors hover:border-[var(--accent)] active:scale-95 cursor-pointer"
          >
            <span>Back to top</span>
            <ArrowUp className="h-3.5 w-3.5" />
          </button>
        </div>
      </div>
    </footer>
  );
}
