'use client';

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { AnimatePresence, motion } from 'motion/react';
import { Menu, MessageSquare, Moon, Sun, X, ChevronRight, ArrowUpRight } from 'lucide-react';
import { useTheme } from './Providers';

const NAV_ITEMS = [
  { href: '/', label: 'Home' },
  { href: '/services', label: 'Services' },
  { href: '/packages', label: 'Packages' },
  { href: '/portfolio', label: 'Work' },
  { href: '/software', label: 'Products', highlight: true },
  { href: '/reviews', label: 'Reviews' },
  { href: '/about', label: 'About' },
];

export function Header() {
  const pathname = usePathname() || '/';
  const { theme, toggleTheme } = useTheme();
  const [scrolled, setScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 14);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  useEffect(() => {
    setMobileOpen(false);
  }, [pathname]);

  useEffect(() => {
    if (!mobileOpen) return;
    const onKeyDown = (e: KeyboardEvent) => {
      if (e.key === 'Escape') setMobileOpen(false);
    };
    document.body.style.overflow = 'hidden';
    window.addEventListener('keydown', onKeyDown);
    return () => {
      document.body.style.overflow = '';
      window.removeEventListener('keydown', onKeyDown);
    };
  }, [mobileOpen]);

  const isActive = (href: string) => {
    if (href === '/') return pathname === '/';
    if (href === '/portfolio') return pathname.startsWith('/portfolio') || pathname.startsWith('/work');
    return pathname.startsWith(href);
  };

  return (
    <>
      <header
        className={`sticky top-0 z-50 w-full transition-[background-color,border-color,backdrop-filter] duration-200 ${
          scrolled
            ? 'border-b border-[var(--line)] bg-[color-mix(in_srgb,var(--bg)_84%,transparent)] backdrop-blur-xl'
            : 'border-b border-transparent bg-transparent'
        }`}
      >
        <div className="mx-auto flex h-[74px] max-w-[1220px] items-center justify-between gap-4 px-5 sm:px-6">
          <Link href="/" className="flex shrink-0 items-center" aria-label="Creatsify Home">
            <img
              src={theme === 'light' ? '/assets/brand/creatsify-logo-light.svg' : '/assets/brand/creatsify-logo-dark.svg'}
              alt="Creatsify"
              className="h-9 w-auto select-none sm:h-10"
              width={156}
              height={40}
            />
          </Link>

          <nav aria-label="Main navigation" className="hidden items-center gap-1 lg:flex">
            {NAV_ITEMS.map((item) => {
              const active = isActive(item.href);
              return (
                <Link
                  key={item.href}
                  href={item.href}
                  aria-current={active ? 'page' : undefined}
                  className={`relative rounded-full px-3.5 py-2 text-[13.5px] font-medium transition-colors duration-150 ${
                    active ? 'text-[var(--text)]' : 'text-[var(--muted)] hover:text-[var(--text)]'
                  }`}
                >
                  {active && (
                    <motion.span
                      layoutId="nav-active-pill"
                      transition={{ type: 'spring', stiffness: 420, damping: 34 }}
                      className="absolute inset-0 -z-10 rounded-full border border-[var(--line-strong)] bg-[var(--surface)]"
                    />
                  )}
                  <span className="inline-flex items-center gap-1.5">
                    {item.label}
                    {item.highlight && (
                      <span
                        className="h-1.5 w-1.5 rounded-full bg-[var(--accent)]"
                        aria-hidden="true"
                      />
                    )}
                  </span>
                </Link>
              );
            })}
          </nav>

          <div className="flex items-center gap-2.5">
            <button
              type="button"
              onClick={toggleTheme}
              aria-label={`Switch to ${theme === 'dark' ? 'light' : 'dark'} theme`}
              className="flex h-10 w-10 items-center justify-center rounded-full border border-[var(--line)] bg-[var(--surface)] text-[var(--text)] transition-[transform,border-color,background-color] duration-150 hover:border-[var(--line-strong)] hover:bg-[var(--surface-hover)] active:scale-95 cursor-pointer"
            >
              {theme === 'dark' ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
            </button>

            <Link
              href="/contact"
              className="btn-brand hidden !px-4 !py-2.5 !text-[13px] sm:inline-flex"
            >
              <MessageSquare className="h-3.5 w-3.5" />
              <span>Start a project</span>
            </Link>

            <button
              type="button"
              onClick={() => setMobileOpen(true)}
              aria-label="Open navigation menu"
              aria-expanded={mobileOpen}
              className="flex h-10 w-10 items-center justify-center rounded-full border border-[var(--line)] bg-[var(--surface)] text-[var(--text)] transition-[transform,background-color] duration-150 active:scale-95 lg:hidden cursor-pointer"
            >
              <Menu className="h-5 w-5" />
            </button>
          </div>
        </div>
      </header>

      <AnimatePresence>
        {mobileOpen && (
          <div className="fixed inset-0 z-[80] lg:hidden" role="dialog" aria-modal="true" aria-label="Mobile navigation">
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              transition={{ duration: 0.2 }}
              onClick={() => setMobileOpen(false)}
              className="absolute inset-0 bg-black/70 backdrop-blur-sm"
            />
            <motion.aside
              initial={{ x: '100%' }}
              animate={{ x: 0 }}
              exit={{ x: '100%' }}
              transition={{ duration: 0.28, ease: [0.32, 0.72, 0, 1] }}
              className="absolute inset-y-0 right-0 flex w-full max-w-[330px] flex-col border-l border-[var(--line)] bg-[var(--bg)] p-6"
            >
              <div className="flex items-center justify-between border-b border-[var(--line)] pb-6">
                <img
                  src={theme === 'light' ? '/assets/brand/creatsify-logo-light.svg' : '/assets/brand/creatsify-logo-dark.svg'}
                  alt="Creatsify"
                  className="h-8 w-auto"
                />
                <button
                  type="button"
                  onClick={() => setMobileOpen(false)}
                  aria-label="Close navigation menu"
                  className="flex h-9 w-9 items-center justify-center rounded-full border border-[var(--line)] bg-[var(--surface)] text-[var(--text)] active:scale-95 cursor-pointer"
                >
                  <X className="h-4 w-4" />
                </button>
              </div>

              <nav className="mt-5 flex flex-1 flex-col gap-1 overflow-y-auto" aria-label="Mobile menu">
                {[...NAV_ITEMS, { href: '/contact', label: 'Contact' }].map((item) => {
                  const active = isActive(item.href);
                  return (
                    <Link
                      key={item.href}
                      href={item.href}
                      className={`flex items-center justify-between rounded-xl px-4 py-3 text-[15px] font-medium transition-colors ${
                        active
                          ? 'border border-[var(--line-strong)] bg-[var(--surface)] text-[var(--accent)]'
                          : 'text-[var(--text)] hover:bg-[var(--surface)]'
                      }`}
                    >
                      <span>{item.label}</span>
                      <ChevronRight className="h-4 w-4 opacity-50" />
                    </Link>
                  );
                })}

                <div className="mt-4 flex flex-wrap gap-3 border-t border-[var(--line)] px-2 pt-4 text-xs font-medium text-[var(--muted)]">
                  <Link href="/blog" className="hover:text-[var(--text)]">Blog</Link>
                  <Link href="/support" className="hover:text-[var(--text)]">Support</Link>
                  <Link href="/privacy" className="hover:text-[var(--text)]">Privacy</Link>
                </div>
              </nav>

              <div className="mt-6 border-t border-[var(--line)] pt-5">
                <Link href="/contact" className="btn-brand w-full">
                  <span>Start a project</span>
                  <ArrowUpRight className="h-4 w-4" />
                </Link>
                <p className="mt-3 text-center text-xs text-[var(--muted)]">
                  Web · Flutter Apps · Branding · SEO &amp; Ads
                </p>
              </div>
            </motion.aside>
          </div>
        )}
      </AnimatePresence>
    </>
  );
}
