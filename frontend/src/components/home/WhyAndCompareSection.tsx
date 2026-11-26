'use client';

import React from 'react';
import { Check } from 'lucide-react';

const ADVANTAGES = [
  {
    num: '01',
    title: 'Build, design and market together',
    body: 'Engineers, designers, and growth strategists share one sprint plan, so your website, brand identity, and ad funnels reinforce each other.',
  },
  {
    num: '02',
    title: 'Hand-built, not template-built',
    body: 'Zero bloated page builders or fragile themes. Clean, maintainable code engineered for sub-second speed and top Core Web Vitals.',
  },
  {
    num: '03',
    title: 'Talk directly to the builders',
    body: 'No account-manager telephone game. Speak directly with the senior developers and designers shipping your product.',
  },
  {
    num: '04',
    title: '100% ownership on handover',
    body: 'Every repository, database schema, and Figma source file belongs exclusively to you — delivered in clear milestones with weekly staging links.',
  },
];

const COMPARE_ROWS = [
  {
    axis: 'Scope',
    creatsify: 'Web, mobile, brand, SEO & ads in one senior team',
    agencies: 'Siloed departments and slow hand-offs',
    freelancers: 'One skill each — you manage coordination',
  },
  {
    axis: 'Build quality',
    creatsify: 'Custom-architected, clean code & fast performance',
    agencies: 'Frequently delegated to junior staff',
    freelancers: 'Often stitched from templates and plugins',
  },
  {
    axis: 'Delivery pace',
    creatsify: 'Focused weekly sprints with live staging links',
    agencies: 'Long discovery phases and bloated meetings',
    freelancers: 'Unpredictable timelines and availability',
  },
  {
    axis: 'Pricing',
    creatsify: 'Transparent packages and fixed milestone scopes',
    agencies: 'High ongoing retainers with opaque markups',
    freelancers: 'Scope creep triggers surprise hourly charges',
  },
  {
    axis: 'Ownership',
    creatsify: '100% unlocked source code and commercial IP',
    agencies: 'Proprietary lock-in or licensing restrictions',
    freelancers: 'Inconsistent handover and documentation',
  },
];

export function WhyAndCompareSection() {
  return (
    <section id="why-us" className="border-y border-[var(--line)] bg-[var(--bg-subtle)] py-20 lg:py-28">
      <div className="mx-auto max-w-[1220px] px-5 sm:px-6">
        <div className="max-w-2xl">
          <h2 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-4xl">
            One senior team. Zero hand-off headaches.
          </h2>
          <p className="mt-3 text-[15.5px] leading-relaxed text-[var(--muted)]">
            When engineering, visual craft, and acquisition operate under one roof, every launch ships faster and converts harder.
          </p>
        </div>

        <div className="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {ADVANTAGES.map((adv) => (
            <div
              key={adv.num}
              className="rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-6 transition-colors hover:border-[var(--line-strong)]"
            >
              <span className="font-mono text-xs font-semibold text-[var(--accent)] tabular-nums">
                {adv.num}
              </span>
              <h3 className="mt-3 font-display text-lg font-bold text-[var(--text)]">
                {adv.title}
              </h3>
              <p className="mt-2 text-sm leading-relaxed text-[var(--muted)]">{adv.body}</p>
            </div>
          ))}
        </div>

        {/* Comparison Matrix */}
        <div className="mt-12 overflow-hidden rounded-2xl border border-[var(--line)] bg-[var(--surface)]">
          <div className="overflow-x-auto">
            <table className="w-full min-w-[680px] border-collapse text-left text-sm">
              <thead>
                <tr className="border-b border-[var(--line)] text-xs uppercase tracking-wider text-[var(--muted)]">
                  <th className="px-6 py-4 font-semibold">What you get</th>
                  <th className="bg-[var(--accent-soft)] px-6 py-4 font-display font-bold text-[var(--accent)]">
                    Creatsify Studio
                  </th>
                  <th className="px-6 py-4 font-semibold">Large agencies</th>
                  <th className="px-6 py-4 font-semibold">Separate freelancers</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-[var(--line)]">
                {COMPARE_ROWS.map((row) => (
                  <tr key={row.axis}>
                    <td className="px-6 py-4 font-semibold text-[var(--text)]">{row.axis}</td>
                    <td className="bg-[var(--accent-soft)]/50 px-6 py-4 font-medium text-[var(--text)]">
                      <span className="inline-flex items-start gap-2">
                        <Check className="mt-0.5 h-4 w-4 shrink-0 text-[var(--emerald)]" />
                        <span>{row.creatsify}</span>
                      </span>
                    </td>
                    <td className="px-6 py-4 text-[var(--muted)]">{row.agencies}</td>
                    <td className="px-6 py-4 text-[var(--muted)]">{row.freelancers}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  );
}
