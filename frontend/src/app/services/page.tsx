import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';
import { ArrowUpRight, Check } from 'lucide-react';
import { getServicesData } from '@/lib/api';

export const revalidate = 60;

export const metadata: Metadata = {
  title: 'Services — Web, Mobile Apps, SaaS, Branding & SEO',
  description:
    'Explore Creatsify’s 8 core engineering, design, and growth divisions — from custom PHP/Next.js apps and Flutter mobile apps to SEO and paid acquisition.',
  alternates: { canonical: '/services' },
};

export default async function ServicesPage() {
  const services = await getServicesData();

  return (
    <div className="mx-auto max-w-[1220px] px-5 py-14 sm:px-6 lg:py-20">
      <header className="max-w-3xl">
        <h1 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-5xl">
          Full-spectrum engineering, design &amp; growth services
        </h1>
        <p className="mt-4 text-base leading-relaxed text-[var(--muted)] sm:text-lg">
          Every engagement is led by senior builders with fixed milestones, transparent staging links, and 100% source code ownership on delivery.
        </p>
      </header>

      <div className="mt-12 grid gap-6 lg:grid-cols-2">
        {services.map((svc, idx) => {
          const deliverables = (svc.deliverables || '')
            .split(',')
            .map((d) => d.trim())
            .filter(Boolean);

          return (
            <article
              key={svc.slug}
              id={svc.slug}
              className="scroll-mt-24 flex flex-col justify-between rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-7 transition-colors hover:border-[var(--line-strong)]"
            >
              <div>
                <div className="flex items-baseline gap-3">
                  <span className="font-mono text-sm font-semibold text-[var(--accent)] tabular-nums">
                    {String(idx + 1).padStart(2, '0')}
                  </span>
                  <h2 className="font-display text-2xl font-bold text-[var(--text)]">
                    {svc.title}
                  </h2>
                </div>
                <p className="mt-3 text-[15px] leading-relaxed text-[var(--muted)]">
                  {svc.full_description || svc.short_description}
                </p>

                {deliverables.length > 0 && (
                  <ul className="mt-5 space-y-2 border-t border-[var(--line)] pt-4 text-sm text-[var(--text)]">
                    {deliverables.map((item) => (
                      <li key={item} className="flex items-start gap-2.5">
                        <Check className="mt-0.5 h-4 w-4 shrink-0 text-[var(--emerald)]" />
                        <span>{item}</span>
                      </li>
                    ))}
                  </ul>
                )}
              </div>

              <div className="mt-7 flex flex-wrap gap-3 pt-2">
                <Link
                  href={`/contact?offering=${encodeURIComponent(svc.title)}`}
                  className="btn-brand"
                >
                  <span>Start {svc.title.split(' ')[0]} Project</span>
                  <ArrowUpRight className="h-4 w-4" />
                </Link>
                <Link href="/packages" className="btn-ghost">
                  <span>View Packages</span>
                </Link>
              </div>
            </article>
          );
        })}
      </div>
    </div>
  );
}
