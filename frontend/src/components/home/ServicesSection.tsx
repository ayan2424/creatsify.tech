'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { motion, AnimatePresence } from 'motion/react';
import {
  ArrowUpRight,
  Code2,
  Layers,
  Layout,
  PenTool,
  Search,
  ShoppingBag,
  Smartphone,
  TrendingUp,
} from 'lucide-react';
import type { ServiceItem } from '@/types/creatsify';

type FilterKey = 'all' | 'engineering' | 'creative' | 'growth';

const FILTER_TABS: Array<{ key: FilterKey; label: string }> = [
  { key: 'all', label: 'All capabilities' },
  { key: 'engineering', label: 'Web & Mobile Apps' },
  { key: 'creative', label: 'Branding & Redesign' },
  { key: 'growth', label: 'SEO & Paid Ads' },
];

const ICON_MAP: Record<string, React.ComponentType<{ className?: string }>> = {
  code: Code2,
  smartphone: Smartphone,
  layout: Layout,
  layers: Layers,
  search: Search,
  'trending-up': TrendingUp,
  'pen-tool': PenTool,
  'shopping-bag': ShoppingBag,
};

function trackForSlug(slug: string): FilterKey {
  if (
    slug.includes('branding') ||
    slug.includes('redesign') ||
    slug.includes('logo')
  ) {
    return 'creative';
  }
  if (slug.includes('seo') || slug.includes('marketing') || slug.includes('ads')) {
    return 'growth';
  }
  return 'engineering';
}

export function ServicesSection({
  services,
  onSelectOffering,
}: {
  services: ServiceItem[];
  onSelectOffering?: (offering: string) => void;
}) {
  const [filter, setFilter] = useState<FilterKey>('all');

  const visible = services.filter((svc) =>
    filter === 'all' ? true : trackForSlug(svc.slug) === filter
  );

  return (
    <section id="services" className="py-20 lg:py-28">
      <div className="mx-auto max-w-[1220px] px-5 sm:px-6">
        <div className="flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
          <div className="max-w-2xl">
            <h2 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-4xl">
              Everything you need to launch and grow online
            </h2>
            <p className="mt-3 text-[15.5px] leading-relaxed text-[var(--muted)]">
              Work with one unified engineering, design, and growth team — so your codebase, brand identity, and acquisition funnel reinforce each other without hand-off delays.
            </p>
          </div>

          <div
            role="group"
            aria-label="Filter agency capabilities"
            className="flex flex-wrap gap-1.5 rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-1.5"
          >
            {FILTER_TABS.map((tab) => {
              const active = filter === tab.key;
              return (
                <button
                  key={tab.key}
                  type="button"
                  onClick={() => setFilter(tab.key)}
                  aria-pressed={active}
                  className={`relative rounded-xl px-3.5 py-2 text-xs font-semibold transition-colors cursor-pointer sm:text-[13px] ${
                    active ? 'text-[var(--on-accent)]' : 'text-[var(--muted)] hover:text-[var(--text)]'
                  }`}
                >
                  {active && (
                    <motion.span
                      layoutId="service-filter-pill"
                      transition={{ type: 'spring', stiffness: 420, damping: 32 }}
                      className="absolute inset-0 -z-0 rounded-xl bg-[var(--accent)]"
                    />
                  )}
                  <span className="relative z-10">{tab.label}</span>
                </button>
              );
            })}
          </div>
        </div>

        {/* Asymmetric Editorial Bento Grid (avoids uniform 3-column template) */}
        <motion.div layout className="mt-10 grid gap-5 md:grid-cols-12">
          <AnimatePresence mode="popLayout">
            {visible.map((svc, idx) => {
              const Icon = ICON_MAP[svc.icon || 'code'] || Code2;
              const deliverables = (svc.deliverables || '')
                .split(',')
                .map((d) => d.trim())
                .filter(Boolean)
                .slice(0, 4);

              // Asymmetric bento spans when viewing all capabilities
              const spanClass =
                filter === 'all'
                  ? idx === 0 || idx === 3
                    ? 'md:col-span-7'
                    : idx === 1 || idx === 2
                    ? 'md:col-span-5'
                    : 'md:col-span-6'
                  : 'md:col-span-6';

              return (
                <motion.article
                  layout
                  key={svc.slug}
                  initial={{ opacity: 0, y: 12, scale: 0.98 }}
                  animate={{ opacity: 1, y: 0, scale: 1 }}
                  exit={{ opacity: 0, scale: 0.96 }}
                  transition={{ duration: 0.28, ease: [0.16, 1, 0.3, 1] }}
                  className={`group flex flex-col justify-between rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-6 transition-[border-color,background-color] duration-200 hover:border-[var(--line-strong)] hover:bg-[var(--surface-elevated)] sm:p-7 ${spanClass}`}
                >
                  <div>
                    <div className="flex items-center justify-between gap-3">
                      <div className="flex h-11 w-11 items-center justify-center rounded-xl border border-[var(--line-strong)] bg-[var(--bg-subtle)] text-[var(--accent)]">
                        <Icon className="h-5 w-5" />
                      </div>
                      <span className="font-mono text-xs font-medium text-[var(--muted)] tabular-nums">
                        {String(idx + 1).padStart(2, '0')}
                      </span>
                    </div>

                    <h3 className="mt-5 font-display text-xl font-bold text-[var(--text)]">
                      {svc.title}
                    </h3>
                    <p className="mt-2.5 text-[14.5px] leading-relaxed text-[var(--muted)]">
                      {svc.short_description}
                    </p>

                    {deliverables.length > 0 && (
                      <div className="mt-5 flex flex-wrap gap-1.5">
                        {deliverables.map((item) => (
                          <span
                            key={item}
                            className="rounded-lg border border-[var(--line)] bg-[var(--bg-subtle)] px-2.5 py-1 text-[12px] font-medium text-[var(--muted)]"
                          >
                            {item}
                          </span>
                        ))}
                      </div>
                    )}
                  </div>

                  <div className="mt-6 flex items-center justify-between border-t border-[var(--line)] pt-4">
                    {onSelectOffering ? (
                      <button
                        type="button"
                        onClick={() => onSelectOffering(svc.title)}
                        className="inline-flex items-center gap-1.5 font-display text-[13.5px] font-bold text-[var(--accent)] transition-transform group-hover:translate-x-0.5 cursor-pointer"
                      >
                        <span>Plan this project</span>
                        <ArrowUpRight className="h-4 w-4" />
                      </button>
                    ) : (
                      <Link
                        href={`/contact?offering=${encodeURIComponent(svc.title)}`}
                        className="inline-flex items-center gap-1.5 font-display text-[13.5px] font-bold text-[var(--accent)] transition-transform group-hover:translate-x-0.5"
                      >
                        <span>Plan this project</span>
                        <ArrowUpRight className="h-4 w-4" />
                      </Link>
                    )}

                    <Link
                      href={`/services#${svc.slug}`}
                      className="text-xs font-medium text-[var(--muted)] hover:text-[var(--text)]"
                    >
                      Scope details
                    </Link>
                  </div>
                </motion.article>
              );
            })}
          </AnimatePresence>
        </motion.div>
      </div>
    </section>
  );
}
