'use client';

import React from 'react';
import {
  Code2,
  Database,
  Layers,
  LayoutGrid,
  PenTool,
  Search,
  Smartphone,
  TrendingUp,
} from 'lucide-react';

const ARSENAL_ITEMS = [
  { label: 'PHP 8.4 MVC', icon: Code2 },
  { label: 'Next.js & Node.js', icon: Layers },
  { label: 'Flutter & Dart', icon: Smartphone },
  { label: 'Tailwind CSS v4', icon: LayoutGrid },
  { label: 'MySQL 8 & Redis', icon: Database },
  { label: 'Technical & Local SEO', icon: Search },
  { label: 'Meta, Google & TikTok Ads', icon: TrendingUp },
  { label: 'Logo & Brand Identity', icon: PenTool },
];

export function ArsenalAndProof({ avgRating }: { avgRating: string }) {
  return (
    <div className="space-y-12">
      {/* Seamless Tech & Creative Arsenal Marquee */}
      <div className="relative overflow-hidden border-y border-[var(--line)] bg-[var(--bg-subtle)] py-4">
        <div
          aria-hidden="true"
          className="pointer-events-none absolute inset-y-0 left-0 z-10 w-20 bg-gradient-to-r from-[var(--bg-subtle)] to-transparent"
        />
        <div
          aria-hidden="true"
          className="pointer-events-none absolute inset-y-0 right-0 z-10 w-20 bg-gradient-to-l from-[var(--bg-subtle)] to-transparent"
        />
        <div className="arsenal-track">
          {[0, 1].map((copy) => (
            <div
              key={copy}
              aria-hidden={copy === 1 ? 'true' : undefined}
              className="flex shrink-0 items-center gap-8 pr-8"
            >
              {ARSENAL_ITEMS.map((item) => {
                const Icon = item.icon;
                return (
                  <div
                    key={`${copy}-${item.label}`}
                    className="inline-flex items-center gap-2.5 text-[13.5px] font-medium text-[var(--muted)]"
                  >
                    <Icon className="h-4 w-4 text-[var(--accent)]" />
                    <span className="whitespace-nowrap">{item.label}</span>
                  </div>
                );
              })}
            </div>
          ))}
        </div>
      </div>

      {/* Studio Proof Strip */}
      <div className="mx-auto max-w-[1220px] px-5 sm:px-6">
        <div className="grid grid-cols-2 gap-4 rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-6 sm:grid-cols-4 sm:p-8">
          {[
            { value: '50+', label: 'Projects delivered' },
            { value: `${avgRating}★`, label: 'Average client rating' },
            { value: '15+', label: 'Core technologies' },
            { value: '100%', label: 'Code & IP ownership' },
          ].map((stat, idx) => (
            <div
              key={stat.label}
              className={`flex flex-col justify-center ${
                idx > 0 ? 'sm:border-l sm:border-[var(--line)] sm:pl-6' : ''
              }`}
            >
              <span className="font-display text-2xl font-extrabold text-[var(--text)] tabular-nums sm:text-3xl">
                {stat.value}
              </span>
              <span className="mt-1 text-xs font-medium text-[var(--muted)] sm:text-[13px]">
                {stat.label}
              </span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
