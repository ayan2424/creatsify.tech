'use client';

import React, { useEffect, useRef } from 'react';

export function AmbientCursorHalo() {
  const haloRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (window.matchMedia('(hover: none), (pointer: coarse), (prefers-reduced-motion: reduce)').matches) {
      return;
    }

    const el = haloRef.current;
    if (!el) return;

    let targetX = -400;
    let targetY = -400;
    let curX = -400;
    let curY = -400;
    let visible = false;
    let rafId = 0;

    const onMove = (e: PointerEvent) => {
      targetX = e.clientX;
      targetY = e.clientY;
      if (!visible) {
        visible = true;
        curX = targetX;
        curY = targetY;
        el.style.opacity = '1';
      }
    };

    const onLeave = () => {
      visible = false;
      el.style.opacity = '0';
    };

    const tick = () => {
      if (visible) {
        curX += (targetX - curX) * 0.14;
        curY += (targetY - curY) * 0.14;
        el.style.transform = `translate3d(${curX - 180}px, ${curY - 180}px, 0)`;
      }
      rafId = requestAnimationFrame(tick);
    };

    window.addEventListener('pointermove', onMove, { passive: true });
    document.addEventListener('mouseleave', onLeave);
    rafId = requestAnimationFrame(tick);

    return () => {
      cancelAnimationFrame(rafId);
      window.removeEventListener('pointermove', onMove);
      document.removeEventListener('mouseleave', onLeave);
    };
  }, []);

  return (
    <div
      ref={haloRef}
      aria-hidden="true"
      className="ambient-cursor-halo"
    />
  );
}
