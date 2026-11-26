'use client';

import React, { useMemo, useState } from 'react';
import Link from 'next/link';
import type { PackageItem } from '@/types/creatsify';

interface PackagesAndEstimatorProps {
  packagesByTrack: Record<string, PackageItem[]>;
  onSelectOffering?: (offering: string, note?: string) => void;
  showEstimator?: boolean;
  compact?: boolean;
  showCompareButton?: boolean;
}

const TRACK_TABS = [
  { key: 'dev', label: 'Web, app & SaaS' },
  { key: 'design', label: 'Brand & redesign' },
  { key: 'growth', label: 'SEO & paid ads' },
];

const BUNDLE_OPTIONS = [
  { id: 'web', value: 'Custom Website / Redesign', label: 'Website build or redesign', price: 350, days: 7, defaultChecked: true },
  { id: 'mobile', value: 'Flutter Mobile App (iOS & Android)', label: 'Mobile app (Flutter)', price: 750, days: 14, defaultChecked: false },
  { id: 'brand', value: 'Logo & Complete Brand Identity', label: 'Logo & brand identity', price: 150, days: 3, defaultChecked: true },
  { id: 'seo', value: 'Technical & On-Page SEO', label: 'SEO setup', price: 220, days: 5, defaultChecked: false },
  { id: 'ads', value: 'Meta & Google Paid Ads Setup', label: 'Social & paid ads', price: 250, days: 4, defaultChecked: false },
];

export function PackagesAndEstimatorSection({
  packagesByTrack,
  onSelectOffering,
  showEstimator = true,
  showCompareButton = true,
}: PackagesAndEstimatorProps) {
  const [activeTrack, setActiveTrack] = useState<string>('dev');
  const [selectedIds, setSelectedIds] = useState<string[]>(
    BUNDLE_OPTIONS.filter((o) => o.defaultChecked).map((o) => o.id)
  );

  const trackPackages = packagesByTrack[activeTrack] || [];

  const bundleSummary = useMemo(() => {
    const picked = BUNDLE_OPTIONS.filter((o) => selectedIds.includes(o.id));
    const rawTotal = picked.reduce((sum, item) => sum + item.price, 0);
    let maxDays = 5;
    picked.forEach((item) => {
      if (item.days > maxDays) maxDays = item.days;
    });
    const discountApplied = picked.length > 1;
    const finalTotal = discountApplied ? Math.round(rawTotal * 0.85) : rawTotal;
    const totalDays = picked.length > 0 ? maxDays + (discountApplied ? 3 : 0) : 0;
    return { picked, rawTotal, finalTotal, totalDays, discountApplied };
  }, [selectedIds]);

  const toggleOption = (id: string) => {
    setSelectedIds((prev) =>
      prev.includes(id) ? prev.filter((item) => item !== id) : [...prev, id]
    );
  };

  const handleRequestBundle = () => {
    if (bundleSummary.picked.length === 0) return;
    const names = bundleSummary.picked.map((p) => p.value);
    const summaryStr = `Custom Agency Bundle ($${bundleSummary.finalTotal}): ${names.join(' + ')}`;
    const msg = `I would like to lock in this Custom Multi-Service Bundle (Estimated $${bundleSummary.finalTotal}):\n- ${names.join('\n- ')}`;
    if (onSelectOffering) {
      onSelectOffering(summaryStr, msg);
    } else {
      window.location.href = `/contact?offering=${encodeURIComponent(summaryStr)}`;
    }
  };

  return (
    <section id="packages" className="ag-packages-sec">
      <div className="wrap">
        <div className="om-sec-center">
          <div className="om-sec-tag">
            <span className="om-sec-dot" /> Transparent pricing
          </div>
          <h2 className="om-sec-heading">Clear packages for every stage</h2>
          <p className="om-sec-sub">
            Pick a ready-to-start package, or build a custom bundle with the estimator below.
          </p>

          <div
            className="om-filter-bar ag-pkg-tabs"
            style={{ marginTop: '26px' }}
            role="group"
            aria-label="Service Package Tracks"
          >
            {TRACK_TABS.map((tab) => {
              const active = activeTrack === tab.key;
              return (
                <button
                  key={tab.key}
                  type="button"
                  onClick={() => setActiveTrack(tab.key)}
                  className={`om-filter-pill${active ? ' active' : ''}`}
                  aria-pressed={active}
                >
                  {tab.label}
                </button>
              );
            })}
          </div>
        </div>

        <div className="ag-pkg-grid active" data-pkg-panel={activeTrack}>
          {trackPackages.map((pkg) => {
            const isPop = Boolean(pkg.is_popular);
            const selectVal = `${pkg.title} (${pkg.price_label})`;
            const cycle = pkg.billing_cycle ? `/${pkg.billing_cycle.replace(/^\//, '')}` : '';

            return (
              <article
                key={pkg.slug || pkg.id}
                className={`ag-pkg-card${isPop ? ' popular' : ''}`}
              >
                {isPop && (
                  <div className="ag-pkg-pop-ribbon">
                    {(pkg.tier_badge || 'MOST POPULAR').toUpperCase()}
                  </div>
                )}

                <div className="ag-pkg-head">
                  <div className="ag-pkg-top-meta">
                    <span className="ag-pkg-tier-cat">{activeTrack.toUpperCase()} SPRINT</span>
                    {pkg.delivery_time && (
                      <span className="ag-pkg-delivery-pill">
                        <svg
                          width="11"
                          height="11"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          strokeWidth="2.5"
                          style={{ marginRight: '4px', verticalAlign: '-1px', display: 'inline-block' }}
                        >
                          <circle cx="12" cy="12" r="10" />
                          <polyline points="12 6 12 12 16 14" />
                        </svg>
                        {pkg.delivery_time}
                      </span>
                    )}
                  </div>
                  <h3 className="ag-pkg-tier">{pkg.title}</h3>
                  <div className="ag-pkg-price">
                    {pkg.price_label}
                    <span>{cycle}</span>
                  </div>
                  {pkg.subtitle && <p className="ag-pkg-sub">{pkg.subtitle}</p>}
                </div>

                <ul className="ag-pkg-list">
                  {pkg.features_list.map((fItem) => (
                    <li key={fItem}>&#10003; {fItem}</li>
                  ))}
                </ul>

                {onSelectOffering ? (
                  <button
                    type="button"
                    onClick={() =>
                      onSelectOffering(
                        selectVal,
                        `Hello Creatsify! I am interested in: ${selectVal}. Please share the next steps.`
                      )
                    }
                    className={isPop ? 'om-btn-gold' : 'om-btn-glass'}
                    style={{ width: '100%', justifyContent: 'center', marginTop: 'auto', cursor: 'pointer' }}
                  >
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                      <polyline points="20 6 9 17 4 12" />
                    </svg>
                    <span>{pkg.cta_text || 'Select Package'}</span>
                  </button>
                ) : (
                  <Link
                    href={`/contact?offering=${encodeURIComponent(selectVal)}`}
                    className={isPop ? 'om-btn-gold' : 'om-btn-glass'}
                    style={{ width: '100%', justifyContent: 'center', marginTop: 'auto' }}
                  >
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                      <polyline points="20 6 9 17 4 12" />
                    </svg>
                    <span>{pkg.cta_text || 'Select Package'}</span>
                  </Link>
                )}
              </article>
            );
          })}
        </div>

        {showCompareButton && (
          <div style={{ textAlign: 'center', marginTop: '28px' }}>
            <Link href="/packages" className="om-btn-glass">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
              </svg>
              <span>Compare full package details</span>
            </Link>
          </div>
        )}

        {showEstimator && (
          <div className="ag-scope-estimator" id="scopeEstimator">
            <div className="ag-se-left">
              <div className="om-sec-tag" style={{ marginBottom: '8px' }}>
                <span className="om-sec-dot" /> Bundle builder
              </div>
              <h3>Need more than one service?</h3>
              <p>
                Choose what you need and get an instant estimate, with an automatic{' '}
                <b>15% bundle discount</b>.
              </p>
              <div className="ag-se-options">
                {BUNDLE_OPTIONS.map((opt) => {
                  const checked = selectedIds.includes(opt.id);
                  return (
                    <label key={opt.id} className="ag-se-check">
                      <input
                        type="checkbox"
                        checked={checked}
                        onChange={() => toggleOption(opt.id)}
                      />
                      <span className="ag-se-label">
                        <span>{opt.label}</span> <b>+${opt.price}</b>
                      </span>
                    </label>
                  );
                })}
              </div>
            </div>

            <div className="ag-se-right">
              <span className="ag-se-badge">
                {bundleSummary.discountApplied
                  ? '15% bundle saving applied'
                  : 'Select 2+ for 15% off'}
              </span>
              <div className="ag-se-total-label">Estimated investment</div>
              <div className="ag-se-total-price" id="seTotalPrice">
                {bundleSummary.finalTotal > 0
                  ? `$${bundleSummary.finalTotal.toLocaleString()}`
                  : 'Select Services'}
              </div>
              <div className="ag-se-timeline" id="seTimeline">
                {bundleSummary.picked.length > 0
                  ? `Est. Kickoff & Delivery: ~${bundleSummary.totalDays} Days`
                  : 'Check at least one service above'}
              </div>
              <button
                type="button"
                className="om-btn-gold"
                id="seLockBtn"
                onClick={handleRequestBundle}
                disabled={bundleSummary.picked.length === 0}
                style={{
                  width: '100%',
                  justifyContent: 'center',
                  border: 'none',
                  cursor: 'pointer',
                  marginTop: '14px',
                }}
              >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                  <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>
                <span>Request this bundle</span>
              </button>
            </div>
          </div>
        )}
      </div>
    </section>
  );
}
