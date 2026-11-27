'use client';

import React, { useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useGSAP } from '@gsap/react';
import { SpotlightCard } from '@/components/ui/SpotlightCard';

gsap.registerPlugin(ScrollTrigger, useGSAP);

const STEPS = [
  {
    num: '01',
    title: 'Discover & scope',
    desc: 'We learn your goals, define the scope and agree on fixed milestones and a timeline before any work starts.',
  },
  {
    num: '02',
    title: 'Design',
    desc: 'Our creative team shapes your brand, design system and key screens in Figma, so you can approve the look and feel before development begins.',
  },
  {
    num: '03',
    title: 'Build',
    desc: 'We develop on a private staging server so you can review real progress every week and give feedback as we go.',
  },
  {
    num: '04',
    title: 'Launch & grow',
    desc: 'We deploy to your production server, set up technical SEO and tracking, and \u2014 if you want \u2014 launch search and social campaigns.',
  },
];

export function WorkflowSection() {
  const sectionRef = useRef<HTMLElement>(null);
  const railFillRef = useRef<HTMLDivElement>(null);

  useGSAP(
    () => {
      const mm = gsap.matchMedia();
      mm.add('(prefers-reduced-motion: no-preference)', () => {
        if (!sectionRef.current) return;
        gsap.fromTo(
          sectionRef.current.querySelectorAll('.om-how-card'),
          { opacity: 0, y: 26 },
          {
            opacity: 1,
            y: 0,
            duration: 0.62,
            stagger: 0.1,
            ease: 'power3.out',
            clearProps: 'transform,opacity',
            scrollTrigger: {
              trigger: sectionRef.current.querySelector('.ag-workflow-4grid'),
              start: 'top 85%',
              once: true,
              fastScrollEnd: true,
            },
          }
        );

        if (railFillRef.current) {
          gsap.fromTo(
            railFillRef.current,
            { scaleX: 0 },
            {
              scaleX: 1,
              ease: 'none',
              scrollTrigger: {
                trigger: sectionRef.current,
                start: 'top 76%',
                end: 'bottom 58%',
                scrub: 0.65,
              },
            }
          );
        }
      });
      return () => mm.revert();
    },
    { scope: sectionRef }
  );

  return (
    <section ref={sectionRef} id="how" className="om-how-sec">
      <div className="wrap">
        <div className="om-sec-center">
          <div className="om-sec-tag">
            <span className="om-sec-dot" /> How it works
          </div>
          <h2 className="om-sec-heading">From first call to launch in four steps</h2>
          <p className="om-sec-sub">
            Clear milestones, a live staging link and no guesswork from day one.
          </p>
        </div>

        {/* Scroll-Scrubbed Glowing Studio Progress Rail (Desktop) */}
        <div
          aria-hidden="true"
          className="relative mx-auto mt-10 mb-6 hidden max-w-[920px] items-center justify-between lg:flex"
        >
          <div className="absolute inset-x-4 top-1/2 h-[2px] -translate-y-1/2 rounded-full bg-[var(--line)]" />
          <div
            ref={railFillRef}
            className="absolute inset-x-4 top-1/2 h-[2px] -translate-y-1/2 origin-left rounded-full bg-gradient-to-r from-[#0284C7] via-[#00D2EE] to-[#10B981]"
          />
          {STEPS.map((s) => (
            <span
              key={s.num}
              className="relative z-10 flex h-8 w-8 items-center justify-center rounded-full border border-[var(--accent)]/60 bg-[var(--surface)] font-mono text-[11px] font-bold text-[var(--accent)] shadow-[0_0_16px_rgba(0,210,238,0.22)] tabular-nums"
            >
              {s.num}
            </span>
          ))}
        </div>

        <div className="ag-workflow-4grid">
          {STEPS.map((step) => (
            <SpotlightCard key={step.num} className="om-how-card">
              <div className="relative z-10">
                <span className="om-how-step-num tabular-nums">{step.num}</span>
                <h3>{step.title}</h3>
                <p>{step.desc}</p>
              </div>
            </SpotlightCard>
          ))}
        </div>
      </div>
    </section>
  );
}
