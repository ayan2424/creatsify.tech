'use client';

import React, { useState, useRef, useEffect } from 'react';
import dynamic from 'next/dynamic';
import Link from 'next/link';
import { motion, useMotionValue, useSpring, useTransform } from 'motion/react';
import {
  ArrowUpRight,
  Check,
  Code2,
  FolderKanban,
  MessageCircle,
  Monitor,
  PenTool,
  Star,
  TrendingUp,
} from 'lucide-react';
import { useTheme } from '@/components/Providers';
import { whatsappLink } from '@/lib/utils';

const HeroAmbientCanvas = dynamic(
  () => import('./HeroAmbientCanvas').then((m) => m.HeroAmbientCanvas),
  { ssr: false }
);

interface HeroSectionProps {
  avgRating: string;
  totalReviews: number;
  whatsappNumber: string;
}

const DIVISIONS = [
  {
    id: 'engineering',
    title: 'Web & Mobile App Engineering',
    metric: '99/100 Speed',
    metricTone: 'emerald',
    stack: 'PHP 8.4 · Node.js · Next.js · Flutter & Dart · MySQL',
    detail: 'Zero-bloat custom architectures with live staging previews and 100% unlocked source code.',
    icon: Monitor,
  },
  {
    id: 'brand',
    title: 'Brand Identity & UI/UX Redesign',
    metric: '+184% Lift',
    metricTone: 'cyan',
    stack: 'Vector Logos · Brand Systems · Figma UI/UX · CRO',
    detail: 'Complete interface modernizations engineered for sub-second speed and higher buyer conversion.',
    icon: PenTool,
  },
  {
    id: 'growth',
    title: 'Technical SEO & Paid Social Ads',
    metric: '3.8x Avg ROAS',
    metricTone: 'amber',
    stack: 'Google Top-3 SEO · Meta FB/IG · Google PPC · Funnels',
    detail: 'Structured JSON-LD schema, Core Web Vitals tuning, and pixel/CAPI tracked ad campaigns.',
    icon: TrendingUp,
  },
] as const;

export function HeroSection({ avgRating, totalReviews, whatsappNumber }: HeroSectionProps) {
  const { theme } = useTheme();
  const [activeDiv, setActiveDiv] = useState<string>('engineering');
  const [enableWebGL, setEnableWebGL] = useState(false);
  const stageRef = useRef<HTMLDivElement>(null);

  const mouseX = useMotionValue(0);
  const mouseY = useMotionValue(0);
  const rotateX = useSpring(useTransform(mouseY, [-0.5, 0.5], [4.5, -4.5]), {
    stiffness: 180,
    damping: 22,
  });
  const rotateY = useSpring(useTransform(mouseX, [-0.5, 0.5], [-5.5, 5.5]), {
    stiffness: 180,
    damping: 22,
  });

  useEffect(() => {
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const desktop = window.matchMedia('(min-width: 1024px)').matches;
    if (!reduced && desktop) {
      setEnableWebGL(true);
    }
  }, []);

  const handleMouseMove = (e: React.MouseEvent<HTMLDivElement>) => {
    if (!stageRef.current) return;
    const rect = stageRef.current.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;
    mouseX.set(x);
    mouseY.set(y);
  };

  const handleMouseLeave = () => {
    mouseX.set(0);
    mouseY.set(0);
  };

  const waHref = whatsappLink(
    whatsappNumber,
    'Hello! I would like to discuss a project or service package with Creatsify Digital Agency.'
  );

  return (
    <section id="hero" className="relative overflow-x-clip pb-16 pt-10 sm:pb-20 sm:pt-14 lg:pb-24 lg:pt-16">
      <div className="mx-auto grid max-w-[1220px] items-center gap-12 px-5 sm:px-6 lg:grid-cols-12 lg:gap-10">
        {/* Left Column: High-Authority Value Proposition */}
        <motion.div
          initial={{ opacity: 0, y: 14 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.55, ease: [0.16, 1, 0.3, 1] }}
          className="lg:col-span-6"
        >
          <h1 className="font-display text-[32px] font-extrabold leading-[1.1] tracking-[-0.03em] text-[var(--text)] sm:text-[42px] lg:text-[48px]">
            High-Velocity Engineering, Distinctive Brands &amp; Scaled Growth
          </h1>

          <p className="mt-5 max-w-[56ch] text-[15.5px] leading-[1.68] text-[var(--muted)] sm:text-[16.5px]">
            We craft high-performance web platforms, cross-platform Flutter mobile apps, conversion-engineered website redesigns, and profitable SEO &amp; paid acquisition engines for ambitious businesses worldwide.
          </p>

          {/* CTA Buttons */}
          <div className="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">
            <a href="#contact" className="btn-brand w-full sm:w-auto">
              <span>Start Your Project</span>
              <ArrowUpRight className="h-4 w-4" />
            </a>

            <a href="#work" className="btn-ghost w-full sm:w-auto">
              <FolderKanban className="h-4 w-4 text-[var(--accent)]" />
              <span>Explore Case Studies</span>
            </a>

            <a
              href={waHref}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex w-full items-center justify-center gap-2 rounded-full border border-emerald-500/35 bg-emerald-500/10 px-4 py-3 font-display text-[13.5px] font-semibold text-emerald-500 transition-[transform,background-color] duration-150 hover:bg-emerald-500/18 active:scale-[0.97] sm:w-auto"
            >
              <MessageCircle className="h-4 w-4" />
              <span>Chat on WhatsApp</span>
            </a>
          </div>

          {/* Proof Bar */}
          <div className="mt-8 grid grid-cols-1 gap-2.5 sm:grid-cols-2">
            {[
              { icon: Star, text: `${avgRating}/5.0 Rating (${totalReviews}+ Verified)`, accent: true },
              { icon: Check, text: '100% Source Code & IP Ownership', accent: false },
              { icon: Check, text: 'Fixed Milestones & Rapid Sprints', accent: false },
              { icon: Check, text: 'Direct Senior Technical Access', accent: false },
            ].map((item) => {
              const Icon = item.icon;
              return (
                <div
                  key={item.text}
                  className="flex items-center gap-2.5 rounded-xl border border-[var(--line)] bg-[var(--surface)] px-3.5 py-2.5 text-[13px] font-medium text-[var(--text)]"
                >
                  <Icon
                    className={`h-3.5 w-3.5 shrink-0 ${
                      item.accent ? 'fill-amber-400 text-amber-400' : 'text-[var(--emerald)]'
                    }`}
                  />
                  <span className="tabular-nums">{item.text}</span>
                </div>
              );
            })}
          </div>
        </motion.div>

        {/* Right Column: Interactive Agency Command Stage + WebGL Depth */}
        <div
          ref={stageRef}
          onMouseMove={handleMouseMove}
          onMouseLeave={handleMouseLeave}
          className="relative lg:col-span-6"
          style={{ perspective: 1100 }}
        >
          {enableWebGL && <HeroAmbientCanvas isDark={theme === 'dark'} />}

          <motion.div
            initial={{ opacity: 0, scale: 0.96, y: 16 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.08, ease: [0.16, 1, 0.3, 1] }}
            style={{ rotateX, rotateY, transformStyle: 'preserve-3d' }}
            className="relative overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[#0b0e16] text-slate-100 shadow-[0_28px_72px_-18px_rgba(0,0,0,0.68)]"
          >
            {/* macOS Studio Window Bar */}
            <div className="flex items-center justify-between gap-3 border-b border-white/10 bg-[#080a10] px-4 py-3">
              <div className="flex items-center gap-1.5" aria-hidden="true">
                <span className="h-2.5 w-2.5 rounded-full bg-[#ff5f56]" />
                <span className="h-2.5 w-2.5 rounded-full bg-[#ffbd2e]" />
                <span className="h-2.5 w-2.5 rounded-full bg-[#27c93f]" />
              </div>

              <div className="flex items-center gap-1.5 rounded-full border border-white/10 bg-white/5 px-3 py-1 font-mono text-[11px] text-slate-300">
                <Code2 className="h-3 w-3 text-cyan-400" />
                <span>creatsify.tech / studio</span>
              </div>

              <span className="hidden items-center gap-1.5 text-[11px] font-semibold text-emerald-400 sm:inline-flex">
                <span className="h-1.5 w-1.5 rounded-full bg-emerald-400" />
                Unified Studio
              </span>
            </div>

            {/* Interactive 3-Division Showcase */}
            <div className="space-y-2.5 p-4 sm:p-5">
              {DIVISIONS.map((div) => {
                const Icon = div.icon;
                const active = activeDiv === div.id;
                const badgeClass =
                  div.metricTone === 'emerald'
                    ? 'bg-emerald-500/15 text-emerald-300 border-emerald-500/25'
                    : div.metricTone === 'cyan'
                    ? 'bg-cyan-400/15 text-cyan-300 border-cyan-400/25'
                    : 'bg-amber-500/15 text-amber-300 border-amber-500/25';

                return (
                  <button
                    key={div.id}
                    type="button"
                    onClick={() => setActiveDiv(div.id)}
                    onMouseEnter={() => setActiveDiv(div.id)}
                    className={`group relative w-full rounded-xl border p-3.5 text-left transition-colors duration-200 cursor-pointer ${
                      active
                        ? 'border-cyan-400/40 bg-white/[0.06]'
                        : 'border-white/8 bg-white/[0.02] hover:bg-white/[0.04]'
                    }`}
                  >
                    <div className="flex items-start gap-3.5">
                      <div
                        className={`flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border transition-colors ${
                          active
                            ? 'border-cyan-400/40 bg-cyan-400/15 text-cyan-300'
                            : 'border-white/10 bg-white/5 text-slate-300'
                        }`}
                      >
                        <Icon className="h-4 w-4" />
                      </div>
                      <div className="min-w-0 flex-1">
                        <div className="flex flex-wrap items-center justify-between gap-2">
                          <strong className="font-display text-[14px] font-bold text-white">
                            {div.title}
                          </strong>
                          <span
                            className={`rounded-full border px-2.5 py-0.5 font-mono text-[11px] font-semibold tabular-nums ${badgeClass}`}
                          >
                            {div.metric}
                          </span>
                        </div>
                        <p className="mt-1 text-[12px] text-slate-400">{div.stack}</p>
                        {active && (
                          <motion.p
                            initial={{ opacity: 0, height: 0 }}
                            animate={{ opacity: 1, height: 'auto' }}
                            transition={{ duration: 0.2, ease: [0.16, 1, 0.3, 1] }}
                            className="mt-2 border-t border-white/10 pt-2 text-[12px] leading-relaxed text-slate-300"
                          >
                            {div.detail}
                          </motion.p>
                        )}
                      </div>
                    </div>
                  </button>
                );
              })}

              {/* Studio Telemetry Strip */}
              <div className="grid grid-cols-3 gap-2 rounded-xl border border-white/10 bg-[#07090f] p-3 text-center">
                <div>
                  <div className="font-display text-base font-extrabold text-white tabular-nums">
                    100%
                  </div>
                  <div className="text-[11px] text-slate-400">Code ownership</div>
                </div>
                <div className="border-x border-white/10">
                  <div className="font-display text-base font-extrabold text-cyan-300 tabular-nums">
                    24h
                  </div>
                  <div className="text-[11px] text-slate-400">Reply time</div>
                </div>
                <div>
                  <div className="font-display text-base font-extrabold text-emerald-400 tabular-nums">
                    Fixed
                  </div>
                  <div className="text-[11px] text-slate-400">Milestones</div>
                </div>
              </div>

              {/* Footer Stack Chips */}
              <div className="flex flex-wrap items-center justify-between gap-3 pt-1">
                <div className="flex flex-wrap gap-1.5">
                  {['Next.js', 'PHP 8.4', 'Flutter', 'Tailwind', 'SEO & Ads'].map((chip) => (
                    <span
                      key={chip}
                      className="rounded-md border border-white/10 bg-white/5 px-2.5 py-1 font-mono text-[11px] text-slate-300"
                    >
                      {chip}
                    </span>
                  ))}
                </div>
                <Link
                  href="/portfolio"
                  className="inline-flex items-center gap-1 font-display text-xs font-bold text-cyan-300 hover:text-cyan-200"
                >
                  <span>Case Studies</span>
                  <ArrowUpRight className="h-3.5 w-3.5" />
                </Link>
              </div>
            </div>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
