'use client';

import React, { useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useGSAP } from '@gsap/react';
import { SpotlightCard } from '@/components/ui/SpotlightCard';

gsap.registerPlugin(ScrollTrigger, useGSAP);

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
