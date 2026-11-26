'use client';

import React, { useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useGSAP } from '@gsap/react';

gsap.registerPlugin(ScrollTrigger, useGSAP);

const STEPS = [
  {
    num: '01',
    title: 'Discover & scope',
    body: 'We audit your goals, lock technical and CRO requirements, and confirm fixed milestones and delivery dates before any code starts.',
  },
  {
    num: '02',
    title: 'Brand & UI/UX craft',
    body: 'Our design team shapes your brand tokens, responsive design system, and key interactive screens so you approve the exact feel upfront.',
  },
  {
    num: '03',
    title: 'High-velocity build',
    body: 'We engineer your web app, Flutter mobile app, or commercial portal on a private staging server with weekly live reviews.',
  },
  {
    num: '04',
    title: 'Launch, SEO & growth',
    body: 'We deploy to your production VPS, verify Core Web Vitals and structured schema, and launch tracked search & social acquisition.',
  },
];

export function WorkflowSection() {
  const containerRef = useRef<HTMLDivElement>(null);

  useGSAP(
    () => {
      const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (reduced) return;

      gsap.from('.workflow-step-card', {
        y: 22,
        opacity: 0,
        duration: 0.65,
        stagger: 0.1,
        ease: 'power3.out',
        clearProps: 'transform,opacity',
        scrollTrigger: {
          trigger: containerRef.current,
          start: 'top 80%',
          once: true,
        },
      });

      gsap.fromTo(
        '.workflow-progress-line',
        { scaleX: 0 },
        {
          scaleX: 1,
          duration: 1.0,
          ease: 'power3.out',
          scrollTrigger: {
            trigger: containerRef.current,
            start: 'top 76%',
            once: true,
          },
        }
      );
    },
    { scope: containerRef }
  );

  return (
    <section id="how" ref={containerRef} className="py-20 lg:py-28">
      <div className="mx-auto max-w-[1220px] px-5 sm:px-6">
        <div className="mx-auto max-w-2xl text-center">
          <h2 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-4xl">
            From first call to launch in four steps
          </h2>
          <p className="mt-3 text-[15.5px] leading-relaxed text-[var(--muted)]">
            Clear sprint milestones, live staging previews, and complete transparency from day one.
          </p>
        </div>

        <div className="relative mt-12">
          {/* Desktop Animated Progress Track */}
          <div
            aria-hidden="true"
            className="pointer-events-none absolute left-0 right-0 top-9 hidden h-px bg-[var(--line)] lg:block"
          >
            <div className="workflow-progress-line h-full w-full origin-left bg-gradient-to-r from-[#0284C7] via-[#00D2EE] to-[#10B981]" />
          </div>

          <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            {STEPS.map((step) => (
              <div
                key={step.num}
                className="workflow-step-card relative rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-6"
              >
                <div className="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-[var(--line-strong)] bg-[var(--bg-subtle)] font-mono text-sm font-bold text-[var(--accent)] tabular-nums">
                  {step.num}
                </div>
                <h3 className="mt-5 font-display text-lg font-bold text-[var(--text)]">
                  {step.title}
                </h3>
                <p className="mt-2 text-sm leading-relaxed text-[var(--muted)]">{step.body}</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
}
