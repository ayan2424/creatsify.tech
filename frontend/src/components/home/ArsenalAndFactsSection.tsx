'use client';

import React from 'react';

const ARSENAL_STACK = [
  {
    label: 'PHP 8.4 MVC',
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <polyline points="16 18 22 12 16 6" />
        <polyline points="8 6 2 12 8 18" />
      </svg>
    ),
  },
  {
    label: 'Next.js & Node.js',
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
      </svg>
    ),
  },
  {
    label: 'Flutter & Dart',
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <rect x="5" y="2" width="14" height="20" rx="2" ry="2" />
        <line x1="12" y1="18" x2="12.01" y2="18" />
      </svg>
    ),
  },
  {
    label: 'Tailwind CSS',
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <path d="M4 4h16v16H4z" />
        <path d="M9 9h6v6H9z" />
      </svg>
    ),
  },
  {
    label: 'MySQL 8 & Redis',
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <ellipse cx="12" cy="5" rx="9" ry="3" />
        <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3" />
        <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5" />
      </svg>
    ),
  },
  {
    label: 'Technical & Local SEO',
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
      </svg>
    ),
  },
  {
    label: 'Meta, Google & TikTok Ads',
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
        <polyline points="17 6 23 6 23 12" />
      </svg>
    ),
  },
  {
    label: 'Logo & brand identity',
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <path d="M12 19l7-7 3 3-7 7-3-3z" />
        <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
      </svg>
    ),
  },
  {
    label: 'Figma Systems',
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <rect x="3" y="3" width="18" height="18" rx="2" />
        <line x1="3" y1="9" x2="21" y2="9" />
        <line x1="9" y1="21" x2="9" y2="9" />
      </svg>
    ),
  },
  {
    label: 'REST & Webhooks',
    icon: (
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <polygon points="12 2 2 7 12 12 22 7 12 2" />
        <polyline points="2 17 12 22 22 17" />
        <polyline points="2 12 12 17 22 12" />
      </svg>
    ),
  },
];

export function ArsenalAndFactsSection({ avgRating }: { avgRating?: string }) {
  return (
    <>
      <div className="om-partners-bar ag-arsenal-section">
        <div className="ag-arsenal-marquee" aria-label="Core Technology & Marketing Stack">
          <div className="ag-arsenal-track">
            {[...ARSENAL_STACK, ...ARSENAL_STACK].map((item, idx) => (
              <div
                key={`${item.label}-${idx}`}
                className="om-partner-item"
                aria-hidden={idx >= ARSENAL_STACK.length ? 'true' : undefined}
              >
                {item.icon}
                <span>{item.label}</span>
              </div>
            ))}
          </div>
        </div>
      </div>

      <div className="wrap om-facts-wrap">
        <div className="om-facts-bar">
          <div className="om-fact-item">
            <b>50+</b>
            <span>Projects delivered</span>
          </div>
          {avgRating && (
            <div className="om-fact-item">
              <b>{avgRating}&#9733;</b>
              <span>Average client rating</span>
            </div>
          )}
          <div className="om-fact-item">
            <b>15+</b>
            <span>Technologies we use</span>
          </div>
          <div className="om-fact-item">
            <b>100%</b>
            <span>Code &amp; IP ownership</span>
          </div>
        </div>
      </div>
    </>
  );
}
