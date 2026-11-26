'use client';

import React from 'react';
import Link from 'next/link';
import { whatsappLink } from '@/lib/utils';

interface FooterProps {
  whatsappNumber: string;
  footerProducts: Array<{ slug: string; title: string; price?: string | null }>;
}

export function Footer({ whatsappNumber, footerProducts }: FooterProps) {
  const year = new Date().getFullYear();
  const waHref = whatsappLink(
    whatsappNumber,
    'Hello! I would like to discuss a project or package with Creatsify Digital Agency.'
  );

  return (
    <footer className="om-footer">
      <div className="wrap">
        {/* Pre-Footer Heroic Callout Box (Exact PHP Design) */}
        <div className="om-ft-cta-box">
          <div className="om-ft-cta-left">
            <div className="om-sec-tag dark">
              <span className="om-sec-dot" /> Ready when you are
            </div>
            <h2 className="om-ft-cta-title">Let&apos;s build something your customers will love</h2>
            <p className="om-ft-cta-desc">
              Tell us about your idea &mdash; a new website, a mobile app, a redesign, a brand or a growth campaign &mdash; and we&apos;ll reply with a clear plan, timeline and quote.
            </p>
            <div className="om-ft-proof-pills">
              <span>&#10003; Websites &amp; mobile apps</span>
              <span>&#10003; Brand &amp; UI/UX design</span>
              <span>&#10003; SEO &amp; paid ads</span>
              <span>&#10003; Full code ownership</span>
            </div>
          </div>
          <div className="om-ft-cta-right">
            <Link className="om-btn-gold" href="/contact">
              <svg
                width="15"
                height="15"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="2.5"
              >
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
              </svg>
              <span>Start a project</span>
            </Link>
            <a
              className="om-ft-wa-btn"
              href={waHref}
              target="_blank"
              rel="noopener noreferrer"
            >
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z" />
              </svg>
              <span>Chat on WhatsApp</span>
            </a>
          </div>
        </div>

        {/* Main Multi-Column Agency Footer */}
        <div className="om-ft-main-grid">
          <div className="om-ft-col brand-col">
            <Link
              href="/"
              className="om-ft-brand-head"
              aria-label="Creatsify Home"
              style={{ display: 'inline-block', textDecoration: 'none' }}
            >
              <img
                src="/assets/brand/creatsify-logo-dark.svg"
                height={40}
                alt="Creatsify"
                style={{ height: '40px', width: 'auto', display: 'block' }}
              />
            </Link>
            <p className="om-ft-brand-desc">
              A digital studio for websites, mobile apps, branding and growth. We design, build and market products for businesses worldwide &mdash; with direct access to the people doing the work.
            </p>
            <Link
              href="/reviews"
              className="om-ft-leadership-badge"
              style={{ textDecoration: 'none' }}
            >
              <span className="om-pulse-dot" />
              <span>Read client reviews</span>
            </Link>
            <div className="om-ft-location-pill">
              <svg
                width="14"
                height="14"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="2"
              >
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                <circle cx="12" cy="10" r="3" />
              </svg>
              <span>Working with clients worldwide</span>
            </div>
          </div>

          <div className="om-ft-col">
            <h4 className="om-ft-col-title">Services</h4>
            <ul className="om-ft-links">
              <li><Link href="/services#custom-web-development">Custom web development</Link></li>
              <li><Link href="/services#mobile-app-development">Mobile apps</Link></li>
              <li><Link href="/services#website-redesign-modernization">Website redesign</Link></li>
              <li><Link href="/services#saas-product-engineering">SaaS development</Link></li>
              <li><Link href="/services#search-engine-optimization-seo">SEO</Link></li>
              <li><Link href="/services#social-media-marketing-ads">Social media &amp; ads</Link></li>
              <li><Link href="/services#branding-logo-graphic-design">Branding &amp; logo design</Link></li>
            </ul>
          </div>

          <div className="om-ft-col">
            <h4 className="om-ft-col-title">Ready-made platforms</h4>
            <ul className="om-ft-links">
              {footerProducts.slice(0, 3).map((ftp) => (
                <li key={ftp.slug}>
                  <Link href={`/portfolio/${ftp.slug}`}>
                    {ftp.title}
                    {ftp.price ? (
                      <>
                        {' '}
                        <span className="om-ft-price">{ftp.price}</span>
                      </>
                    ) : null}
                  </Link>
                </li>
              ))}
              <li>
                <Link href="/contact?kind=custom_tailoring&offering=Customize+Ready-Made+Software">
                  Request customization
                </Link>
              </li>
              <li>
                <Link href="/software">Browse all platforms</Link>
              </li>
            </ul>
          </div>

          <div className="om-ft-col">
            <h4 className="om-ft-col-title">Company</h4>
            <ul className="om-ft-links">
              <li><Link href="/portfolio">Our work</Link></li>
              <li><Link href="/packages">Packages &amp; pricing</Link></li>
              <li><Link href="/reviews">Client reviews</Link></li>
              <li><Link href="/about">About Creatsify</Link></li>
              <li><Link href="/blog">Blog</Link></li>
              <li><Link href="/support">Support</Link></li>
              <li><Link href="/contact">Contact us</Link></li>
              <li><Link href="/privacy">Privacy &amp; terms</Link></li>
            </ul>
          </div>
        </div>

        {/* Sub-Footer Bottom Bar */}
        <div className="om-ft-bottom-bar">
          <div
            className="om-ft-copyright"
            style={{ display: 'flex', alignItems: 'center', gap: '14px', flexWrap: 'wrap' }}
          >
            <Link
              href="/"
              aria-label="Creatsify Home"
              style={{ display: 'inline-flex', alignItems: 'center', textDecoration: 'none' }}
            >
              <img
                src="/assets/brand/creatsify-logo-dark.svg"
                height={26}
                alt="Creatsify"
                style={{ height: '26px', width: 'auto', display: 'block' }}
              />
            </Link>
            <span>&copy; {year} Creatsify. All rights reserved.</span>
          </div>
          <div className="om-ft-bottom-right">
            <a className="om-ft-top-btn" href="#top" aria-label="Back to top">
              <span>Back to top</span>
              <svg
                width="12"
                height="12"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="2.5"
              >
                <polyline points="18 15 12 9 6 15" />
              </svg>
            </a>
          </div>
        </div>
      </div>
    </footer>
  );
}
