'use client';

import React, { useEffect, useState } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { useTheme } from './Providers';
import { Magnetic } from '@/components/ui/Magnetic';

const NAV_ITEMS = [
  { href: '/', label: 'Home' },
  { href: '/services', label: 'Services' },
  { href: '/packages', label: 'Packages' },
  { href: '/portfolio', label: 'Work' },
  { href: '/software', label: 'Products', hot: true },
  { href: '/reviews', label: 'Reviews' },
  { href: '/about', label: 'About' },
];

export function Header() {
  const pathname = usePathname() || '/';
  const { toggleTheme } = useTheme();
  const [mobileOpen, setMobileOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 20);
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
    if (href === '/portfolio') {
      return pathname.startsWith('/portfolio') || pathname.startsWith('/work');
    }
    return pathname.startsWith(href);
  };

  return (
    <>
      <header className={scrolled ? 'scrolled' : undefined}>
        <div className="wrap bar">
          <Link href="/" className="brand-link" aria-label="Creatsify Home">
            <img
              className="brand-logo logo-dark"
              src="/assets/brand/creatsify-logo-dark.svg"
              alt="Creatsify"
              height={40}
            />
            <img
              className="brand-logo logo-light"
              src="/assets/brand/creatsify-logo-light.svg"
              alt="Creatsify"
              height={40}
            />
          </Link>

          <nav id="nav" aria-label="Main">
            {NAV_ITEMS.map((item) => {
              const active = isActive(item.href);
              const cls = [item.hot ? 'nav-hot' : '', active ? 'act' : '']
                .filter(Boolean)
                .join(' ');
              return (
                <Link
                  key={item.href}
                  href={item.href}
                  className={cls || undefined}
                  aria-current={active ? 'page' : undefined}
                >
                  {item.label}
                </Link>
              );
            })}
          </nav>

          <div className="header-actions">
            <button
              className="btn icon"
              id="theme"
              type="button"
              onClick={toggleTheme}
              aria-label="Switch light or dark theme"
            >
              <svg
                className="icon-sun"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
                strokeLinejoin="round"
                aria-hidden="true"
              >
                <circle cx="12" cy="12" r="5" />
                <line x1="12" y1="1" x2="12" y2="3" />
                <line x1="12" y1="21" x2="12" y2="23" />
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                <line x1="1" y1="12" x2="3" y2="12" />
                <line x1="21" y1="12" x2="23" y2="12" />
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
              </svg>
              <svg
                className="icon-moon"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
                strokeLinejoin="round"
                aria-hidden="true"
              >
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
              </svg>
            </button>

            <Magnetic strength={0.22}>
              <Link
                className={`btn p desktop-cta${pathname.startsWith('/contact') ? ' act' : ''}`}
                href="/contact"
              >
                <svg
                  width="14"
                  height="14"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2.2"
                  className="btn-lead-icon"
                >
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                <span>Start a project</span>
              </Link>
            </Magnetic>

            <button
              className="btn icon mobile-menu-toggle"
              id="mobileMenuBtn"
              type="button"
              onClick={() => setMobileOpen((prev) => !prev)}
              aria-label="Toggle navigation menu"
              aria-expanded={mobileOpen}
            >
              {!mobileOpen ? (
                <svg
                  className="icon-menu-bars"
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                >
                  <line x1="3" y1="12" x2="21" y2="12" />
                  <line x1="3" y1="6" x2="21" y2="6" />
                  <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
              ) : (
                <svg
                  className="icon-menu-cross"
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  strokeWidth="2"
                >
                  <line x1="18" y1="6" x2="6" y2="18" />
                  <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
              )}
            </button>
          </div>
        </div>
      </header>

      <div
        className={`mobile-drawer-backdrop${mobileOpen ? ' open' : ''}`}
        id="mobileDrawerBackdrop"
        onClick={() => setMobileOpen(false)}
      />
      <div
        className={`mobile-drawer-sheet${mobileOpen ? ' open' : ''}`}
        id="mobileDrawer"
        aria-hidden={!mobileOpen}
      >
        <div className="mobile-drawer-top">
          <Link href="/" className="brand-link" aria-label="Creatsify Home">
            <img
              className="brand-logo logo-dark"
              src="/assets/brand/creatsify-logo-dark.svg"
              alt="Creatsify"
              height={36}
            />
            <img
              className="brand-logo logo-light"
              src="/assets/brand/creatsify-logo-light.svg"
              alt="Creatsify"
              height={36}
            />
          </Link>
          <button
            type="button"
            className="btn icon mobile-drawer-close-btn"
            id="mobileDrawerClose"
            onClick={() => setMobileOpen(false)}
            aria-label="Close navigation menu"
          >
            <svg
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
            >
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>

        <nav className="mobile-drawer-links" aria-label="Mobile">
          {[...NAV_ITEMS, { href: '/contact', label: 'Contact' }].map((item) => {
            const active = isActive(item.href);
            return (
              <Link
                key={item.href}
                href={item.href}
                className={`mobile-link${active ? ' act' : ''}`}
              >
                <span>{item.label}</span>
                <span className="mobile-link-arrow">
                  <svg
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2.2"
                  >
                    <polyline points="9 18 15 12 9 6" />
                  </svg>
                </span>
              </Link>
            );
          })}
          <div className="mobile-more">
            <Link href="/blog">Blog</Link>
            <Link href="/support">Support</Link>
            <Link href="/privacy">Privacy</Link>
          </div>
        </nav>

        <div className="mobile-drawer-bottom">
          <Link
            className="om-btn-gold"
            href="/contact"
            style={{ width: '100%', justifyContent: 'center' }}
          >
            <svg
              width="14"
              height="14"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2.5"
            >
              <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
            </svg>
            <span>Start a project</span>
          </Link>
          <div
            style={{
              marginTop: '14px',
              textAlign: 'center',
              fontSize: '12px',
              color: 'var(--muted)',
            }}
          >
            Web &bull; Mobile Apps &bull; Branding &bull; SEO &amp; Ads
          </div>
        </div>
      </div>
    </>
  );
}
