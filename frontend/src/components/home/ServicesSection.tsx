'use client';

import React, { useRef, useState } from 'react';
import dynamic from 'next/dynamic';
import Link from 'next/link';
import { AnimatePresence, motion } from 'motion/react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useGSAP } from '@gsap/react';
import {
  ArrowRight,
  ArrowUpRight,
  Check,
  Code2,
  PenTool,
  Smartphone,
  TrendingUp,
} from 'lucide-react';
import type { ServiceItem } from '@/types/creatsify';
import type { NekoExpression, NekoMode } from '@/lib/nekoRobotEngine';
import { useTheme } from '@/components/Providers';
import { SpotlightCard } from '@/components/ui/SpotlightCard';
import { Magnetic } from '@/components/ui/Magnetic';

gsap.registerPlugin(ScrollTrigger, useGSAP);

const CreatsifyNekoCanvas = dynamic(
  () => import('@/components/ui/CreatsifyNekoCanvas').then((m) => m.CreatsifyNekoCanvas),
  { ssr: false }
);

const DISCIPLINE_NEKO: Record<string, { expr: NekoExpression; mode: NekoMode }> = {
  web: { expr: 'excited', mode: 'hover' },
  mobile: { expr: 'joy', mode: 'wave' },
  brand: { expr: 'love', mode: 'hover' },
  growth: { expr: 'think', mode: 'think' },
};

const STUDIO_PILLARS = [
  {
    id: 'web',
    num: '01',
    title: 'Web Platforms & SaaS',
    tagline: 'Zero-bloat Next.js, PHP 8.4 MVC & multi-tenant web applications.',
    metric: '99/100 Core Web Vitals',
    deliverables: [
      'Custom SaaS & Admin Dashboards',
      'E-Commerce & POS Storefronts',
      'Normalized MySQL & REST APIs',
    ],
    stack: ['Next.js', 'PHP 8.4', 'Node.js', 'Tailwind v4', 'MySQL 8'],
    icon: Code2,
  },
  {
    id: 'mobile',
    num: '02',
    title: 'Flutter Mobile Apps',
    tagline: '60fps native-speed iOS & Android apps from one unified Dart codebase.',
    metric: '4.9★ Store Launches',
    deliverables: [
      'iOS & Android Consumer & Fleet Apps',
      'Live GPS Tracking & Push Alerts',
      'App Store & Play Store Publishing',
    ],
    stack: ['Flutter', 'Dart', 'Firebase', 'REST APIs', 'WebSockets'],
    icon: Smartphone,
  },
  {
    id: 'brand',
    num: '03',
    title: 'Brand Identity & UI/UX Redesign',
    tagline: 'Modern interface overhauls and distinct identities built to convert.',
    metric: '+184% Conversion Lift',
    deliverables: [
      'Website UX & CRO Overhauls',
      'Master Vector Logos & Brand Books',
      'Interactive Figma Design Systems',
    ],
    stack: ['Figma', 'Brand Systems', 'CRO Funnels', 'Motion UI'],
    icon: PenTool,
  },
  {
    id: 'growth',
    num: '04',
    title: 'Technical SEO & Paid Growth',
    tagline: 'Compounding organic search rankings paired with tracked ad scale.',
    metric: '3.8x Verified Ad ROAS',
    deliverables: [
      'Technical SEO & JSON-LD Schema',
      'Meta (FB/IG), Google & TikTok Ads',
      'Pixel / CAPI Attribution & Creatives',
    ],
    stack: ['Technical SEO', 'Meta CAPI', 'Google Ads', 'Analytics'],
    icon: TrendingUp,
  },
];

export function ServicesSection({
  onSelectOffering,
}: {
  services?: ServiceItem[];
  onSelectOffering?: (offering: string) => void;
}) {
  const { theme } = useTheme();
  const [activeId, setActiveId] = useState<string>('web');
  const [avatarBoop, setAvatarBoop] = useState(0);
  const sectionRef = useRef<HTMLElement>(null);
  const active = STUDIO_PILLARS.find((p) => p.id === activeId) || STUDIO_PILLARS[0];
  const ActiveIcon = active.icon;
  const activeNeko = DISCIPLINE_NEKO[active.id] || DISCIPLINE_NEKO.web;

  useGSAP(
    () => {
      const mm = gsap.matchMedia();
      mm.add('(prefers-reduced-motion: no-preference)', () => {
        if (!sectionRef.current) return;
        gsap.fromTo(
          sectionRef.current.querySelectorAll('.svc-track-row'),
          { opacity: 0, x: -18 },
          {
            opacity: 1,
            x: 0,
            duration: 0.55,
            stagger: 0.08,
            ease: 'power3.out',
            clearProps: 'transform,opacity',
            scrollTrigger: {
              trigger: sectionRef.current,
              start: 'top 80%',
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

  return (
    <section
      ref={sectionRef}
      id="services"
      className="border-y border-[var(--line)] bg-[var(--bg-subtle)] py-16 lg:py-24"
    >
      <div className="mx-auto max-w-[1220px] px-5 sm:px-6">
        <div className="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
          <div>
            <h2 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-4xl">
              Four disciplines. One senior team.
            </h2>
            <p className="mt-2 text-[15px] text-[var(--muted)]">
              Hover or tap a capability to inspect deliverables — no agencies to coordinate.
            </p>
          </div>
          <Link
            href="/services"
            className="inline-flex shrink-0 items-center gap-1.5 font-display text-sm font-bold text-[var(--accent)] hover:underline"
          >
            <span>All 8 capabilities</span>
            <ArrowRight className="h-4 w-4" />
          </Link>
        </div>

        {/* Clean Split-Index — Replaces 2,200px 8-Box Wall */}
        <div className="mt-10 grid gap-8 lg:grid-cols-12 lg:items-center">
          {/* Left: Architectural Hairline Track List */}
          <div className="divide-y divide-[var(--line)] border-y border-[var(--line)] lg:col-span-6">
            {STUDIO_PILLARS.map((pillar) => {
              const isActive = pillar.id === activeId;
              return (
                <button
                  key={pillar.id}
                  type="button"
                  onClick={() => setActiveId(pillar.id)}
                  onMouseEnter={() => setActiveId(pillar.id)}
                  className={`svc-track-row group relative flex w-full items-center justify-between gap-4 py-5 pl-4 text-left transition-colors cursor-pointer ${
                    isActive ? 'text-[var(--text)]' : 'text-[var(--muted)] hover:text-[var(--text)]'
                  }`}
                >
                  {isActive && (
                    <motion.span
                      layoutId="service-active-bar"
                      transition={{ type: 'spring', stiffness: 420, damping: 32 }}
                      className="absolute bottom-3 left-0 top-3 w-[2.5px] rounded-full bg-[var(--accent)]"
                    />
                  )}
                  <div className="flex items-baseline gap-4">
                    <span
                      className={`font-mono text-xs font-semibold tabular-nums transition-colors ${
                        isActive ? 'text-[var(--accent)]' : 'opacity-60'
                      }`}
                    >
                      {pillar.num}
                    </span>
                    <span className="font-display text-xl font-bold tracking-tight sm:text-2xl">
                      {pillar.title}
                    </span>
                  </div>
                  <ArrowRight
                    className={`h-5 w-5 shrink-0 transition-transform duration-200 ${
                      isActive
                        ? 'translate-x-0 text-[var(--accent)] opacity-100'
                        : '-translate-x-2 opacity-0 group-hover:translate-x-0 group-hover:opacity-60'
                    }`}
                  />
                </button>
              );
            })}
          </div>

          {/* Right: Morphing Spotlight Focus Theater + Live Card Avatar Bust */}
          <div className="lg:col-span-6">
            <SpotlightCard active className="rounded-2xl p-7 sm:p-8">
              <div className="relative z-10">
                {/* Persistent Neko Card Avatar Bust Header (never remounts WebGL on tab switch) */}
                <div className="mb-5 flex items-center justify-between gap-4 border-b border-[var(--line)] pb-5">
                  <div className="flex items-center gap-3.5">
                    <button
                      type="button"
                      onClick={() => setAvatarBoop((n) => n + 1)}
                      title="Tap Neko Avatar"
                      className="relative h-[78px] w-[78px] shrink-0 overflow-hidden rounded-2xl border border-[var(--accent)]/35 bg-[radial-gradient(circle_at_50%_35%,rgba(0,210,238,0.18),rgba(7,25,94,0.88))] cursor-pointer"
                    >
                      <CreatsifyNekoCanvas
                        variant="card-avatar"
                        mode={activeNeko.mode}
                        expressionOverride={activeNeko.expr}
                        isDark={theme === 'dark'}
                        boopSignal={avatarBoop}
                        lazyMount={true}
                        globalPointer={true}
                        className="h-full w-full"
                        ariaLabel="Creatsify Neko discipline bust portrait"
                      />
                    </button>
                    <div>
                      <div className="flex items-center gap-2">
                        <span className="flex h-7 w-7 items-center justify-center rounded-lg bg-[var(--accent-soft)] text-[var(--accent)]">
                          <ActiveIcon className="h-4 w-4" />
                        </span>
                        <span className="font-mono text-xs font-semibold text-[var(--muted)] tabular-nums">
                          {active.num} / 04
                        </span>
                      </div>
                      <h3 className="mt-1.5 font-display text-xl font-bold text-[var(--text)] sm:text-2xl">
                        {active.title}
                      </h3>
                    </div>
                  </div>
                  <span className="hidden shrink-0 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 font-mono text-xs font-semibold text-[var(--emerald)] tabular-nums sm:inline-flex">
                    {active.metric}
                  </span>
                </div>

                <AnimatePresence mode="wait">
                  <motion.div
                    key={active.id}
                    initial={{ opacity: 0, y: 10 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -8 }}
                    transition={{ duration: 0.22, ease: [0.16, 1, 0.3, 1] }}
                  >
                    <div className="mb-3 sm:hidden">
                      <span className="inline-flex rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 font-mono text-xs font-semibold text-[var(--emerald)] tabular-nums">
                        {active.metric}
                      </span>
                    </div>

                    <p className="text-[15px] leading-relaxed text-[var(--muted)]">
                      {active.tagline}
                    </p>

                    <ul className="mt-5 space-y-2.5 border-t border-[var(--line)] pt-5 text-sm font-medium text-[var(--text)]">
                      {active.deliverables.map((item) => (
                        <li key={item} className="flex items-center gap-2.5">
                          <Check className="h-4 w-4 shrink-0 text-[var(--emerald)]" />
                          <span>{item}</span>
                        </li>
                      ))}
                    </ul>

                    <div className="mt-5 flex flex-wrap gap-1.5">
                      {active.stack.map((tech) => (
                        <span
                          key={tech}
                          className="rounded-md border border-[var(--line)] bg-[var(--bg-subtle)] px-2.5 py-1 font-mono text-[11px] font-medium text-[var(--muted)]"
                        >
                          {tech}
                        </span>
                      ))}
                    </div>

                    <div className="mt-6 flex flex-wrap items-center gap-3">
                      <Magnetic strength={0.22}>
                        {onSelectOffering ? (
                          <button
                            type="button"
                            onClick={() => onSelectOffering(active.title)}
                            className="btn-brand"
                          >
                            <span>Start this project</span>
                            <ArrowUpRight className="h-4 w-4" />
                          </button>
                        ) : (
                          <Link
                            href={`/contact?offering=${encodeURIComponent(active.title)}`}
                            className="btn-brand"
                          >
                            <span>Start this project</span>
                            <ArrowUpRight className="h-4 w-4" />
                          </Link>
                        )}
                      </Magnetic>
                      <Link href="/services" className="btn-ghost">
                        <span>Full scope</span>
                      </Link>
                    </div>
                  </motion.div>
                </AnimatePresence>
              </div>
            </SpotlightCard>
          </div>
        </div>
      </div>
    </section>
  );
}
