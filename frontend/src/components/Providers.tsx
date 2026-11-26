'use client';

import React, { createContext, useContext, useEffect, useState, useCallback } from 'react';
import { usePathname } from 'next/navigation';
import { MotionConfig } from 'motion/react';
import Lenis from 'lenis';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

type Theme = 'dark' | 'light';

interface ThemeContextValue {
  theme: Theme;
  toggleTheme: () => void;
  setTheme: (next: Theme) => void;
}

const ThemeContext = createContext<ThemeContextValue>({
  theme: 'dark',
  toggleTheme: () => {},
  setTheme: () => {},
});

export function useTheme() {
  return useContext(ThemeContext);
}

export function Providers({ children }: { children: React.ReactNode }) {
  const [theme, setThemeState] = useState<Theme>('dark');
  const pathname = usePathname();

  useEffect(() => {
    try {
      const stored =
        (localStorage.getItem('creatsify-theme') as Theme | null) ||
        (localStorage.getItem('creatsify_theme') as Theme | null);
      const initial: Theme =
        stored === 'light' || stored === 'dark'
          ? stored
          : window.matchMedia('(prefers-color-scheme: light)').matches
          ? 'light'
          : 'dark';
      setThemeState(initial);
      document.documentElement.dataset.theme = initial;
      document.documentElement.setAttribute('data-theme', initial);
    } catch {
      document.documentElement.dataset.theme = 'dark';
    }
  }, []);

  const setTheme = useCallback((next: Theme) => {
    setThemeState(next);
    document.documentElement.dataset.theme = next;
    document.documentElement.setAttribute('data-theme', next);
    try {
      localStorage.setItem('creatsify-theme', next);
      localStorage.setItem('creatsify_theme', next);
    } catch {
      // Storage unavailable
    }
  }, []);

  const toggleTheme = useCallback(() => {
    setThemeState((prev) => {
      const next: Theme = prev === 'dark' ? 'light' : 'dark';
      document.documentElement.dataset.theme = next;
      document.documentElement.setAttribute('data-theme', next);
      try {
        localStorage.setItem('creatsify-theme', next);
        localStorage.setItem('creatsify_theme', next);
      } catch {
        // Storage unavailable
      }
      return next;
    });
  }, []);

  // Lenis smooth scrolling synced to GSAP ScrollTrigger (respecting prefers-reduced-motion)
  useEffect(() => {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) return;

    const lenis = new Lenis({
      duration: 1.05,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      orientation: 'vertical',
      gestureOrientation: 'vertical',
      smoothWheel: true,
      touchMultiplier: 1.5,
    });

    lenis.on('scroll', ScrollTrigger.update);

    const tick = (time: number) => {
      lenis.raf(time * 1000);
    };
    gsap.ticker.add(tick);
    gsap.ticker.lagSmoothing(0);

    return () => {
      gsap.ticker.remove(tick);
      lenis.destroy();
    };
  }, []);

  // Scroll to top cleanly on route change & fire live analytics hit to PHP backend
  useEffect(() => {
    if (!pathname) return;
    fetch('/api/v1/analytics/hit', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify({
        path: pathname,
        referrer: typeof document !== 'undefined' ? document.referrer : '',
      }),
      keepalive: true,
    }).catch(() => {});
  }, [pathname]);

  return (
    <ThemeContext.Provider value={{ theme, toggleTheme, setTheme }}>
      <MotionConfig reducedMotion="user">{children}</MotionConfig>
    </ThemeContext.Provider>
  );
}
