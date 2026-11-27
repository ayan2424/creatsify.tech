'use client';

import React, { useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useGSAP } from '@gsap/react';

gsap.registerPlugin(ScrollTrigger, useGSAP);

interface ScrollRevealProps {
  children: React.ReactNode;
  className?: string;
  y?: number;
  x?: number;
  delay?: number;
  duration?: number;
  staggerSelector?: string;
  stagger?: number;
}

export function ScrollReveal({
  children,
  className = '',
  y = 24,
  x = 0,
  delay = 0,
  duration = 0.65,
  staggerSelector,
  stagger = 0.08,
}: ScrollRevealProps) {
  const containerRef = useRef<HTMLDivElement>(null);

  useGSAP(
    () => {
      const el = containerRef.current;
      if (!el) return;

      const mm = gsap.matchMedia();
      mm.add('(prefers-reduced-motion: no-preference)', () => {
        const targets = staggerSelector ? el.querySelectorAll(staggerSelector) : el;
        if (staggerSelector && (targets as NodeListOf<Element>).length === 0) return;

        gsap.fromTo(
          targets,
          { opacity: 0, y, x },
          {
            opacity: 1,
            y: 0,
            x: 0,
            duration,
            delay,
            stagger: staggerSelector ? stagger : 0,
            ease: 'power3.out',
            clearProps: 'transform,opacity',
            scrollTrigger: {
              trigger: el,
              start: 'top 88%',
              once: true,
              fastScrollEnd: true,
            },
          }
        );
      });

      return () => mm.revert();
    },
    { scope: containerRef }
  );

  return (
    <div ref={containerRef} className={className}>
      {children}
    </div>
  );
}
