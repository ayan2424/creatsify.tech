'use client';

import React, { useMemo, useState } from 'react';
import Link from 'next/link';
import { motion } from 'motion/react';
import { ArrowUpRight, Check, Clock, Sparkles } from 'lucide-react';
import type { PackageItem } from '@/types/creatsify';

interface PackagesAndEstimatorProps {
  packagesByTrack: Record<string, PackageItem[]>;
  onSelectOffering?: (offering: string, note?: string) => void;
  showCompareLink?: boolean;
}

const TRACK_TABS = [
  { key: 'dev', label: 'Web, app & SaaS' },
  { key: 'design', label: 'Brand & redesign' },
  { key: 'growth', label: 'SEO & paid ads' },
];

const BUNDLE_OPTIONS = [
  { id: 'web', label: 'Website build or redesign', value: 'Custom Website / Redesign', price: 350, days: 7, defaultChecked: true },
  { id: 'mobile', label: 'Mobile app (Flutter iOS & Android)', value: 'Flutter Mobile App (iOS & Android)', price: 750, days: 14, defaultChecked: false },
  { id: 'brand', label: 'Logo & complete brand identity', value: 'Logo & Complete Brand Identity', price: 150, days: 3, defaultChecked: true },
  { id: 'seo', label: 'Technical & On-Page SEO setup', value: 'Technical & On-Page SEO', price: 220, days: 5, defaultChecked: false },
  { id: 'ads', label: 'Meta & Google Paid Ads setup', value: 'Meta & Google Paid Ads Setup', price: 250, days: 4, defaultChecked: false },
];

export function PackagesAndEstimatorSection({
  packagesByTrack,
  onSelectOffering,
  showCompareLink = true,
}: PackagesAndEstimatorProps) {
  const [activeTrack, setActiveTrack] = useState<string>('dev');
  const [selectedIds, setSelectedIds] = useState<string[]>(
    BUNDLE_OPTIONS.filter((o) => o.defaultChecked).map((o) => o.id)
  );

  const trackPackages = packagesByTrack[activeTrack] || [];

  const bundleSummary = useMemo(() => {
    const picked = BUNDLE_OPTIONS.filter((o) => selectedIds.includes(o.id));
    const rawTotal = picked.reduce((sum, item) => sum + item.price, 0);
    const totalDays = picked.reduce((sum, item) => sum + item.days, 0);
    const discountApplied = picked.length >= 2;
    const finalTotal = discountApplied ? Math.round(rawTotal * 0.85) : rawTotal;
    return { picked, rawTotal, finalTotal, totalDays, discountApplied };
  }, [selectedIds]);

  const toggleOption = (id: string) => {
    setSelectedIds((prev) =>
      prev.includes(id) ? prev.filter((item) => item !== id) : [...prev, id]
    );
  };

  const handleRequestBundle = () => {
    const names = bundleSummary.picked.map((p) => p.label).join(', ');
    const summaryText = `Custom Bundle Request: ${names || 'Custom Scope'} (Estimated $${bundleSummary.finalTotal}, ~${bundleSummary.totalDays} days).`;
    if (onSelectOffering) {
      onSelectOffering('Custom Web Development', summaryText);
    } else {
      window.location.href = `/contact?offering=${encodeURIComponent(
        `Custom Bundle ($${bundleSummary.finalTotal})`
      )}`;
    }
  };

  return (
    <section id="packages" className="py-20 lg:py-28">
      <div className="mx-auto max-w-[1220px] px-5 sm:px-6">
        <div className="mx-auto max-w-2xl text-center">
          <h2 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-4xl">
            Clear packages for every stage
          </h2>
          <p className="mt-3 text-[15.5px] leading-relaxed text-[var(--muted)]">
            Pick a ready-to-start sprint package, or combine multiple disciplines below for an automatic 15% bundle saving.
          </p>

          <div
            role="group"
            aria-label="Service package tracks"
            className="mt-7 inline-flex flex-wrap justify-center gap-1.5 rounded-full border border-[var(--line)] bg-[var(--surface)] p-1.5"
          >
            {TRACK_TABS.map((tab) => {
              const active = activeTrack === tab.key;
              return (
                <button
                  key={tab.key}
                  type="button"
                  onClick={() => setActiveTrack(tab.key)}
                  aria-pressed={active}
                  className={`relative rounded-full px-4 py-2 text-[13px] font-semibold transition-colors cursor-pointer ${
                    active ? 'text-[var(--on-accent)]' : 'text-[var(--muted)] hover:text-[var(--text)]'
                  }`}
                >
                  {active && (
                    <motion.span
                      layoutId="pkg-track-pill"
                      transition={{ type: 'spring', stiffness: 420, damping: 32 }}
                      className="absolute inset-0 -z-0 rounded-full bg-[var(--accent)]"
                    />
                  )}
                  <span className="relative z-10">{tab.label}</span>
                </button>
              );
            })}
          </div>
        </div>

        {/* Package Cards */}
        <div className="mt-10 grid gap-6 lg:grid-cols-3">
          {trackPackages.map((pkg) => {
            const selectVal = `${pkg.title} (${pkg.price_label})`;
            return (
              <article
                key={pkg.slug || pkg.id}
                className={`relative flex flex-col rounded-2xl border p-7 transition-colors ${
                  pkg.is_popular
                    ? 'border-[var(--accent)] bg-[var(--surface-elevated)]'
                    : 'border-[var(--line)] bg-[var(--surface)] hover:border-[var(--line-strong)]'
                }`}
              >
                <div className="flex items-center justify-between gap-2">
                  <span className="font-mono text-[11px] font-semibold uppercase tracking-wider text-[var(--accent)]">
                    {pkg.is_popular ? pkg.tier_badge || 'Most popular' : `${activeTrack} sprint`}
                  </span>
                  {pkg.delivery_time && (
                    <span className="inline-flex items-center gap-1 rounded-full border border-[var(--line)] bg-[var(--bg-subtle)] px-2.5 py-1 text-[11.5px] font-medium text-[var(--muted)] tabular-nums">
                      <Clock className="h-3 w-3" />
                      {pkg.delivery_time}
                    </span>
                  )}
                </div>

                <h3 className="mt-4 font-display text-xl font-bold text-[var(--text)]">
                  {pkg.title}
                </h3>

                <div className="mt-3 flex items-baseline gap-1">
                  <span className="font-display text-4xl font-extrabold text-[var(--text)] tabular-nums">
                    {pkg.price_label}
                  </span>
                  {pkg.billing_cycle && (
                    <span className="text-sm font-medium text-[var(--muted)]">
                      /{pkg.billing_cycle.replace(/^\//, '')}
                    </span>
                  )}
                </div>

                {pkg.subtitle && (
                  <p className="mt-2.5 text-sm leading-relaxed text-[var(--muted)]">
                    {pkg.subtitle}
                  </p>
                )}

                <ul className="mt-6 flex-1 space-y-2.5 border-t border-[var(--line)] pt-5 text-sm text-[var(--text)]">
                  {pkg.features_list.map((feat) => (
                    <li key={feat} className="flex items-start gap-2.5">
                      <Check className="mt-0.5 h-4 w-4 shrink-0 text-[var(--emerald)]" />
                      <span>{feat}</span>
                    </li>
                  ))}
                </ul>

                <div className="mt-7">
                  {onSelectOffering ? (
                    <button
                      type="button"
                      onClick={() => onSelectOffering(selectVal)}
                      className={`${pkg.is_popular ? 'btn-brand' : 'btn-ghost'} w-full`}
                    >
                      <span>{pkg.cta_text || 'Select Package'}</span>
                      <ArrowUpRight className="h-4 w-4" />
                    </button>
                  ) : (
                    <Link
                      href={`/contact?offering=${encodeURIComponent(selectVal)}`}
                      className={`${pkg.is_popular ? 'btn-brand' : 'btn-ghost'} w-full`}
                    >
                      <span>{pkg.cta_text || 'Select Package'}</span>
                      <ArrowUpRight className="h-4 w-4" />
                    </Link>
                  )}
                </div>
              </article>
            );
          })}
        </div>

        {showCompareLink && (
          <div className="mt-8 text-center">
            <Link href="/packages" className="btn-ghost">
              <span>Compare all package tracks</span>
              <ArrowUpRight className="h-4 w-4" />
            </Link>
          </div>
        )}

        {/* Interactive Scope & Bundle Estimator */}
        <div className="mt-14 grid gap-8 rounded-2xl border border-[var(--line-strong)] bg-[var(--surface)] p-6 sm:p-8 lg:grid-cols-12 lg:items-center lg:p-10">
          <div className="lg:col-span-7">
            <h3 className="font-display text-2xl font-bold text-[var(--text)]">
              Need more than one service? Build a custom bundle
            </h3>
            <p className="mt-2 text-sm text-[var(--muted)]">
              Select two or more deliverables below to unlock an automatic 15% multi-discipline bundle discount.
            </p>

            <div className="mt-5 grid gap-2.5 sm:grid-cols-2">
              {BUNDLE_OPTIONS.map((opt) => {
                const checked = selectedIds.includes(opt.id);
                return (
                  <button
                    key={opt.id}
                    type="button"
                    onClick={() => toggleOption(opt.id)}
                    aria-pressed={checked}
                    className={`flex items-center justify-between gap-3 rounded-xl border px-4 py-3 text-left text-sm transition-colors cursor-pointer ${
                      checked
                        ? 'border-[var(--accent)] bg-[var(--accent-soft)] text-[var(--text)]'
                        : 'border-[var(--line)] bg-[var(--bg-subtle)] text-[var(--muted)] hover:border-[var(--line-strong)] hover:text-[var(--text)]'
                    }`}
                  >
                    <span className="flex items-center gap-2.5 font-medium">
                      <span
                        className={`flex h-4 w-4 shrink-0 items-center justify-center rounded border ${
                          checked
                            ? 'border-[var(--accent)] bg-[var(--accent)] text-[var(--on-accent)]'
                            : 'border-[var(--line-strong)]'
                        }`}
                      >
                        {checked && <Check className="h-3 w-3" />}
                      </span>
                      <span>{opt.label}</span>
                    </span>
                    <span className="font-mono text-xs font-semibold tabular-nums">
                      +${opt.price}
                    </span>
                  </button>
                );
              })}
            </div>
          </div>

          <div className="rounded-2xl border border-[var(--line)] bg-[var(--bg-subtle)] p-6 lg:col-span-5">
            <div className="flex items-center justify-between">
              <span className="text-xs font-semibold uppercase tracking-wider text-[var(--muted)]">
                Estimated investment
              </span>
              {bundleSummary.discountApplied && (
                <span className="inline-flex items-center gap-1 rounded-full bg-emerald-500/15 px-2.5 py-1 text-[11px] font-semibold text-emerald-500">
                  <Sparkles className="h-3 w-3" />
                  15% bundle saving
                </span>
              )}
            </div>

            <div className="mt-3 flex items-baseline gap-3">
              <span className="font-display text-4xl font-extrabold text-[var(--text)] tabular-nums">
                ${bundleSummary.finalTotal}
              </span>
              {bundleSummary.discountApplied && (
                <span className="font-mono text-sm text-[var(--muted)] line-through tabular-nums">
                  ${bundleSummary.rawTotal}
                </span>
              )}
            </div>

            <p className="mt-1.5 font-mono text-xs text-[var(--muted)] tabular-nums">
              {bundleSummary.totalDays > 0
                ? `Estimated delivery: ~${bundleSummary.totalDays} days`
                : 'Select at least one service to estimate'}
            </p>

            <button
              type="button"
              onClick={handleRequestBundle}
              disabled={bundleSummary.picked.length === 0}
              className="btn-brand mt-6 w-full disabled:opacity-40"
            >
              <span>Request this bundle</span>
              <ArrowUpRight className="h-4 w-4" />
            </button>
          </div>
        </div>
      </div>
    </section>
  );
}
