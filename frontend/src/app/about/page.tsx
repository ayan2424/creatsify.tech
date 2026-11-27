import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';
import { ArrowUpRight, Check, Star } from 'lucide-react';
import { getBootstrap } from '@/lib/api';
import { AboutNekoAvatar } from '@/components/ui/AboutNekoAvatar';

export const revalidate = 60;

export const metadata: Metadata = {
  title: 'About Creatsify Digital Agency',
  description:
    'Meet the unified engineering, design, and growth powerhouse behind Creatsify — building custom web platforms, Flutter apps, brands, and commercial software.',
  alternates: { canonical: '/about' },
};

export default async function AboutPage() {
  const boot = await getBootstrap();
  const avg = boot.review_stats.avg_rating || '4.9';
  const total = boot.review_stats.total || 8;

  return (
    <div className="mx-auto max-w-[1220px] px-5 py-14 sm:px-6 lg:py-20">
      <header className="max-w-3xl">
        <h1 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-5xl">
          Engineering, Brand Craft &amp; Growth Under One Roof
        </h1>
        <p className="mt-4 text-base leading-relaxed text-[var(--muted)] sm:text-lg">
          Creatsify is a full-service software engineering, UI/UX branding, and digital growth studio. We help ambitious businesses ship high-speed web platforms, cross-platform Flutter apps, distinctive visual identities, and measurable SEO &amp; paid acquisition systems.
        </p>
      </header>

      <div className="mt-12 grid gap-8 lg:grid-cols-12">
        <div className="space-y-6 rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-7 sm:p-8 lg:col-span-7">
          <h2 className="font-display text-2xl font-bold text-[var(--text)]">
            Our Non-Negotiable Standards
          </h2>
          <p className="text-[15px] leading-relaxed text-[var(--muted)]">
            Most companies stall because their developer, designer, and marketer operate in disconnected silos. At Creatsify, engineers, designers, and growth strategists collaborate from day one so every architectural choice converts visitors into revenue.
          </p>

          <div className="space-y-3.5 pt-2">
            {[
              [
                'Modern Multi-Stack Engineering',
                'PHP 8.4 MVC, Next.js, Node.js, MySQL 8, and 60fps Flutter & Dart mobile apps engineered for speed and longevity.',
              ],
              [
                'Distinctive Brand Identity & UI/UX Craft',
                'Vector logos, dark/light design systems, CRO website redesigns, and cohesive creative kits that earn immediate trust.',
              ],
              [
                'Measurable SEO & Paid Social ROAS',
                'Technical JSON-LD schema and Core Web Vitals optimization paired with pixel/CAPI tracked Meta, Google, and TikTok ads.',
              ],
              [
                '100% Ownership & Ready-Made Acceleration',
                'Custom builds and tailored white-label platforms alike come with 100% unlocked source code and commercial IP rights.',
              ],
            ].map(([title, desc]) => (
              <div
                key={title}
                className="rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] p-4"
              >
                <strong className="flex items-center gap-2 font-display text-sm font-bold text-[var(--text)]">
                  <Check className="h-4 w-4 text-[var(--emerald)]" />
                  {title}
                </strong>
                <p className="mt-1.5 text-sm leading-relaxed text-[var(--muted)]">{desc}</p>
              </div>
            ))}
          </div>
        </div>

        <aside className="rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-7 sm:p-8 lg:col-span-5">
          <AboutNekoAvatar />
          <h2 className="font-display text-xl font-bold text-[var(--text)]">
            Four Integrated Practice Divisions
          </h2>
          <p className="mt-2 text-sm text-[var(--muted)]">
            Senior-led execution across engineering, mobile, brand craft, and growth with zero agency bloat.
          </p>

          <div className="mt-6 space-y-3">
            {[
              ['01', 'Web & SaaS Engineering', 'PHP 8.4 · Next.js · Node.js · Tailwind · MySQL'],
              ['02', 'Mobile App Engineering', 'Flutter & Dart · iOS & Android Native Speed'],
              ['03', 'Brand, Logo & UI/UX Studio', 'Identity Systems · Figma · CRO Redesigns'],
              ['04', 'SEO & Paid Social Growth Desk', 'Technical SEO · Meta/IG Ads · Google PPC'],
            ].map(([num, title, sub]) => (
              <div
                key={num}
                className="flex items-center gap-3.5 rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] p-4"
              >
                <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[var(--accent-soft)] font-mono text-xs font-bold text-[var(--accent)] tabular-nums">
                  {num}
                </span>
                <div>
                  <strong className="block font-display text-sm font-bold text-[var(--text)]">
                    {title}
                  </strong>
                  <span className="text-xs font-medium text-[var(--muted)]">{sub}</span>
                </div>
              </div>
            ))}
          </div>

          <div className="mt-7 space-y-3">
            <Link href="/packages" className="btn-brand w-full">
              <span>Explore Service Packages</span>
              <ArrowUpRight className="h-4 w-4" />
            </Link>
            <Link href="/reviews" className="btn-ghost w-full">
              <Star className="h-4 w-4 fill-amber-400 text-amber-400" />
              <span className="tabular-nums">
                {avg}/5 Client Reviews ({total}+)
              </span>
            </Link>
          </div>
        </aside>
      </div>
    </div>
  );
}
