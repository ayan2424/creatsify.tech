'use client';

import React, { useRef } from 'react';
import Link from 'next/link';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useGSAP } from '@gsap/react';
import type { ProjectItem } from '@/types/creatsify';
import { hasValidLiveUrl } from '@/lib/utils';
import { SpotlightCard } from '@/components/ui/SpotlightCard';

gsap.registerPlugin(ScrollTrigger, useGSAP);

interface ReadyMadeSoftwareProps {
  products: ProjectItem[];
  onSelectOffering?: (offering: string, note?: string) => void;
  showBrowseAll?: boolean;
  limit?: number;
}

export function ReadyMadeSoftwareSection({
  products,
  onSelectOffering,
  showBrowseAll = true,
  limit = 6,
}: ReadyMadeSoftwareProps) {
  const sectionRef = useRef<HTMLElement>(null);
  const visible = limit ? products.slice(0, limit) : products;

  useGSAP(
    () => {
      const mm = gsap.matchMedia();
      mm.add('(prefers-reduced-motion: no-preference)', () => {
        if (!sectionRef.current) return;
        const cards = sectionRef.current.querySelectorAll('.om-prod-card');
        if (cards.length === 0) return;
        gsap.fromTo(
          cards,
          { opacity: 0, y: 28 },
          {
            opacity: 1,
            y: 0,
            duration: 0.65,
            stagger: 0.09,
            ease: 'power3.out',
            clearProps: 'transform,opacity',
            scrollTrigger: {
              trigger: sectionRef.current.querySelector('.om-prod-grid'),
              start: 'top 86%',
              once: true,
              fastScrollEnd: true,
            },
          }
        );
      });
      return () => mm.revert();
    },
    { scope: sectionRef }
  );

  if (visible.length === 0) return null;

  return (
    <section ref={sectionRef} id="products" className="om-dark-showcase">
      <div className="wrap">
        <div className="om-showcase-header">
          <div className="om-sec-tag dark">
            <span className="om-sec-dot" /> Ready-made platforms
          </div>
          <h2 className="om-sec-heading light">Launch in days with a ready-made platform</h2>
          <p className="om-sec-sub light">
            Alongside custom builds, we offer tested commercial software.{' '}
            <b>License a platform directly on Gumroad</b> with full source code, or{' '}
            <b>have our team customize it</b> for your business.
          </p>
        </div>

        <div className="om-prod-grid">
          {visible.map((pr) => {
            const showLive = hasValidLiveUrl(pr.live_url);
            const priceStr = pr.price || '';
            const techList = pr.tech_list || [];

            return (
              <SpotlightCard as="article" key={pr.slug} className="om-prod-card">
                <div className="relative z-10 flex flex-1 flex-col">
                  <div className="om-prod-media-frame">
                    <div className="om-prod-bar">
                      <div className="om-prod-dots">
                        <span className="dot d-red" />
                        <span className="dot d-yellow" />
                        <span className="dot d-green" />
                      </div>
                      <span className="om-prod-badge-price">
                        {priceStr !== ''
                          ? `Direct: ${priceStr} \u2022 Or Customize`
                          : 'Custom quote available'}
                      </span>
                    </div>

                    {pr.image_resolved ? (
                      <img
                        src={pr.image_resolved}
                        width={1376}
                        height={768}
                        loading="eager"
                        alt={pr.title}
                        className="om-prod-img"
                      />
                    ) : (
                      <div className="om-prod-cover">
                        <div className="om-prod-cover-meta">
                          <span className="om-prod-cover-badge">Ready to deploy</span>
                          <b>{pr.title} Architecture</b>
                        </div>
                      </div>
                    )}
                  </div>

                  <div className="om-prod-body">
                    <div className="om-prod-status-row">
                      <span className="om-prod-pill">
                        {pr.subcategory || 'Commercial Script'}
                      </span>
                      <span className="om-prod-rights">&#10003; Full source code</span>
                    </div>

                    <h3 className="om-prod-title">{pr.title}</h3>
                    <p className="om-prod-desc">{pr.summary}</p>

                    {techList.length > 0 && (
                      <div className="om-prod-chips">
                        {techList.map((t) => (
                          <span key={t}>{t}</span>
                        ))}
                      </div>
                    )}

                    <div className="om-prod-actions">
                      {pr.gumroad_url && (
                        <a
                          className="om-btn-gold"
                          href={pr.gumroad_url}
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 0 1-8 0" />
                          </svg>
                          <span>
                            {priceStr !== '' ? `Buy License (${priceStr})` : 'Buy License'}
                          </span>
                        </a>
                      )}

                      {onSelectOffering ? (
                        <button
                          type="button"
                          onClick={() =>
                            onSelectOffering(
                              `Customize ${pr.title} For My Business`,
                              `Hello Creatsify! I would like to customize ${pr.title} for my business. Please share details.`
                            )
                          }
                          className="om-btn-glass light"
                          style={{ cursor: 'pointer' }}
                        >
                          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                          </svg>
                          <span>Customize for me</span>
                        </button>
                      ) : (
                        <Link
                          className="om-btn-glass light"
                          href={`/contact?kind=software_presale&offering=${encodeURIComponent(
                            `Customize ${pr.title} For My Business`
                          )}`}
                        >
                          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                          </svg>
                          <span>Customize for me</span>
                        </Link>
                      )}

                      {showLive ? (
                        <a
                          className="om-btn-glass light"
                          href={pr.live_url!}
                          target="_blank"
                          rel="noopener noreferrer"
                        >
                          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                            <polyline points="15 3 21 3 21 9" />
                            <line x1="10" y1="14" x2="21" y2="3" />
                          </svg>
                          <span>Live Demo</span>
                        </a>
                      ) : (
                        <Link className="om-btn-glass light" href={`/portfolio/${pr.slug}`}>
                          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                          </svg>
                          <span>View details</span>
                        </Link>
                      )}
                    </div>
                  </div>
                </div>
              </SpotlightCard>
            );
          })}
        </div>

        {showBrowseAll && (
          <div style={{ textAlign: 'center', marginTop: '40px' }}>
            <Link
              href="/software"
              className="om-btn-glass light"
              style={{ display: 'inline-flex', padding: '12px 28px' }}
            >
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
              </svg>
              <span>Browse all platforms</span>
            </Link>
          </div>
        )}
      </div>
    </section>
  );
}
