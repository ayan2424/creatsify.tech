'use client';

import React, { useEffect, useRef, useState } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

interface AnimatedCounterProps {
  value: number;
  decimals?: number;
  prefix?: string;
  suffix?: string;
  mode?: 'scroll' | 'reactive';
  duration?: number;
  formatLocale?: boolean;
  className?: string;
}

export function AnimatedCounter({
  value,
  decimals = 0,
  prefix = '',
  suffix = '',
  mode = 'scroll',
  duration = 1.1,
  formatLocale = false,
  className = '',
}: AnimatedCounterProps) {
  const spanRef = useRef<HTMLSpanElement>(null);
  const proxyRef = useRef({ n: mode === 'reactive' ? value : 0 });
  const [display, setDisplay] = useState<string>(() => {
    const formatted = formatLocale
      ? value.toLocaleString(undefined, {
          minimumFractionDigits: decimals,
          maximumFractionDigits: decimals,
        })
      : value.toFixed(decimals);
    return `${prefix}${formatted}${suffix}`;
  });

  const formatNum = (n: number) => {
    const rounded = decimals > 0 ? Number(n.toFixed(decimals)) : Math.round(n);
    const body = formatLocale
      ? rounded.toLocaleString(undefined, {
          minimumFractionDigits: decimals,
          maximumFractionDigits: decimals,
        })
      : rounded.toFixed(decimals);
    return `${prefix}${body}${suffix}`;
  };

  useEffect(() => {
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduced) {
      proxyRef.current.n = value;
      setDisplay(formatNum(value));
      return;
    }

    if (mode === 'reactive') {
      const tween = gsap.to(proxyRef.current, {
        n: value,
        duration: 0.42,
        ease: 'power3.out',
        overwrite: true,
        onUpdate: () => setDisplay(formatNum(proxyRef.current.n)),
      });
      return () => {
        tween.kill();
      };
    }

    const el = spanRef.current;
    if (!el) return;

    proxyRef.current.n = 0;
    const tween = gsap.to(proxyRef.current, {
      n: value,
      duration,
      ease: 'power3.out',
      paused: true,
      onUpdate: () => setDisplay(formatNum(proxyRef.current.n)),
    });

    const st = ScrollTrigger.create({
      trigger: el,
      start: 'top 90%',
      once: true,
      onEnter: () => tween.play(),
    });

    return () => {
      st.kill();
      tween.kill();
    };
  }, [value, decimals, prefix, suffix, mode, duration, formatLocale]);

  return (
    <span ref={spanRef} className={`tabular-nums ${className}`}>
      {display}
    </span>
  );
}
