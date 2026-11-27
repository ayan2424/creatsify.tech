'use client';

import React, { useRef } from 'react';
import dynamic from 'next/dynamic';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useGSAP } from '@gsap/react';
import { Code2, MoveHorizontal, PenTool, Smartphone, TrendingUp } from 'lucide-react';
import { useTheme } from '@/components/Providers';
import { SpotlightCard } from '@/components/ui/SpotlightCard';

gsap.registerPlugin(ScrollTrigger, useGSAP);

const CreatsifyNekoCanvas = dynamic(
  () => import('@/components/ui/CreatsifyNekoCanvas').then((m) => m.CreatsifyNekoCanvas),
  { ssr: false }
);

const ORBIT_PILLARS = [
  { icon: Code2, label: 'Web & SaaS', detail: 'Next.js · PHP 8.4 · MySQL' },
  { icon: Smartphone, label: 'Flutter Mobile', detail: 'iOS & Android · 60fps Dart' },
  { icon: PenTool, label: 'Brand & UI/UX', detail: 'Figma Systems · CRO' },
  { icon: TrendingUp, label: 'SEO & Paid Ads', detail: 'Schema · Meta & Google' },
];

const STRENGTHS = [
  {
    num: '01',
    title: 'Build, design and market together',
    desc: 'Developers, designers and marketers share one plan, so your site, brand and campaigns reinforce each other instead of pulling apart.',
  },
  {
    num: '02',
    title: 'Hand-built, not template-built',
    desc: 'No heavy themes or page-builder plugins. We write clean, purposeful code so your site loads quickly and stays easy to maintain.',
  },
  {
    num: '03',
    title: 'Talk to the people who build it',
    desc: 'No account-manager middle layer. You speak directly with the developers and designers working on your project.',
  },
  {
    num: '04',
    title: 'You own everything',
    desc: 'Full source code and commercial rights are yours at handover, with clear milestone pricing and a live staging link so you can follow progress every week.',
  },
];

export function WhyCreatsifySection() {
  const { theme } = useTheme();
  const sectionRef = useRef<HTMLElement>(null);

  useGSAP(
    () => {
      const mm = gsap.matchMedia();
      mm.add('(prefers-reduced-motion: no-preference)', () => {
        if (!sectionRef.current) return;
        gsap.fromTo(
          sectionRef.current.querySelectorAll('.ag-strength-card'),
          { opacity: 0, y: 26 },
          {
            opacity: 1,
            y: 0,
            duration: 0.62,
            stagger: 0.08,
            ease: 'power3.out',
            clearProps: 'transform,opacity',
            scrollTrigger: {
              trigger: sectionRef.current.querySelector('.ag-strength-grid'),
              start: 'top 86%',
              once: true,
              fastScrollEnd: true,
            },
          }
        );

        gsap.fromTo(
          sectionRef.current.querySelectorAll('.ag-compare-table tbody tr'),
          { opacity: 0, x: -14 },
          {
            opacity: 1,
            x: 0,
            duration: 0.5,
            stagger: 0.07,
            ease: 'power2.out',
            clearProps: 'transform,opacity',
            scrollTrigger: {
              trigger: sectionRef.current.querySelector('.ag-compare-wrap'),
              start: 'top 86%',
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
    <section ref={sectionRef} id="why-us" className="om-agency-sec">
      <div className="wrap">
        <div className="om-sec-center">
          <div className="om-sec-tag">
            <span className="om-sec-dot" /> Why Creatsify
          </div>
          <h2 className="om-sec-heading">One team. Zero hand-off headaches.</h2>
          <p className="om-sec-sub">
            When developers, designers and marketers work together, your product, your brand and your growth all pull in the same direction.
          </p>
        </div>

        <div className="ag-strength-grid" style={{ marginTop: '40px' }}>
          {STRENGTHS.map((s) => (
            <SpotlightCard key={s.num} className="ag-strength-card group">
              <div className="relative z-10">
                <span className="ag-str-num transition-colors duration-200 group-hover:text-[var(--accent)]">
                  {s.num}
                </span>
                <h3>{s.title}</h3>
                <p>{s.desc}</p>
              </div>
            </SpotlightCard>
          ))}
        </div>

        {/* Full-Stack Orbit 3D Showcase: Unified Studio Discipline Rings */}
        <SpotlightCard className="mt-10 rounded-2xl p-6 sm:p-8">
          <div className="relative z-10 grid items-center gap-6 lg:grid-cols-12">
            <div className="relative flex flex-col items-center justify-center lg:col-span-5">
              <div className="relative h-[265px] w-full max-w-[340px] sm:h-[295px]">
                <CreatsifyNekoCanvas
                  variant="full-stack-orbit"
                  mode="hover"
                  isDark={theme === 'dark'}
                  lazyMount={true}
                  globalPointer={true}
                  interactive={true}
                  className="h-full w-full"
                  ariaLabel="Interactive Full-Stack Orbit 3D Creatsify Neko with rotating tech rings"
                />
                <div className="robot-pedestal-glow !bottom-1 !h-9 !w-44" aria-hidden="true" />
              </div>
              <span className="mt-1 inline-flex items-center gap-1.5 font-mono text-[11px] font-medium text-[var(--muted)]">
                <MoveHorizontal className="h-3.5 w-3.5 text-[var(--accent)]" />
                <span>Drag to rotate orbital stack &middot; Tap to boop</span>
              </span>
            </div>

            <div className="lg:col-span-7">
              <h3 className="font-display text-2xl font-bold tracking-tight text-[var(--text)] sm:text-3xl">
                Every discipline orbiting one shared roadmap.
              </h3>
              <p className="mt-3 max-w-xl text-[15px] leading-relaxed text-[var(--muted)]">
                Instead of splitting your website, mobile app, brand identity, and ad campaigns across four separate vendors, our senior team builds and ships them as one synchronized system.
              </p>
              <div className="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                {ORBIT_PILLARS.map((item) => {
                  const Icon = item.icon;
                  return (
                    <div
                      key={item.label}
                      className="flex items-center gap-3 rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-4 py-3"
                    >
                      <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[var(--accent-soft)] text-[var(--accent)]">
                        <Icon className="h-4 w-4" />
                      </span>
                      <div className="min-w-0">
                        <strong className="block font-display text-sm font-bold text-[var(--text)]">
                          {item.label}
                        </strong>
                        <span className="block truncate font-mono text-[11px] text-[var(--muted)]">
                          {item.detail}
                        </span>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          </div>
        </SpotlightCard>

        <div className="ag-compare-wrap" style={{ marginTop: '44px' }}>
          <div
            className="ag-compare-table-scroll"
            tabIndex={0}
            role="region"
            aria-label="Comparison table"
          >
            <table className="ag-compare-table">
              <thead>
                <tr>
                  <th>What you get</th>
                  <th className="highlight-col">
                    <span className="ag-cmp-badge">Creatsify</span>
                    Creatsify Digital Agency
                  </th>
                  <th>Large agencies</th>
                  <th>Separate freelancers</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><b>Scope</b></td>
                  <td className="highlight-col">
                    &#10003; <b>Web, mobile, brand, SEO &amp; ads in one team</b>
                  </td>
                  <td>Separate departments, slower hand-offs</td>
                  <td>One skill each &mdash; you coordinate the rest</td>
                </tr>
                <tr>
                  <td><b>Build quality</b></td>
                  <td className="highlight-col">
                    &#10003; <b>Custom-built and performance-focused</b>
                  </td>
                  <td>Work is often passed to junior staff</td>
                  <td>Often relies on templates and plugins</td>
                </tr>
                <tr>
                  <td><b>Delivery</b></td>
                  <td className="highlight-col">
                    &#10003; <b>Short sprints, usually days to weeks</b>
                  </td>
                  <td>Long timelines with many meetings</td>
                  <td>Timelines can be hard to predict</td>
                </tr>
                <tr>
                  <td><b>Pricing</b></td>
                  <td className="highlight-col">
                    &#10003; <b>Clear packages and fixed milestones</b>
                  </td>
                  <td>Often high monthly retainers</td>
                  <td>Scope changes can add surprise costs</td>
                </tr>
                <tr>
                  <td><b>Ownership</b></td>
                  <td className="highlight-col">
                    &#10003; <b>Full source code and IP handed over</b>
                  </td>
                  <td>Often locked in to the agency</td>
                  <td>Hand-over quality varies</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  );
}
