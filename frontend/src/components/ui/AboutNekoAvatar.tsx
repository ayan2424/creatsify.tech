'use client';

import React, { useState } from 'react';
import dynamic from 'next/dynamic';
import { Sparkles } from 'lucide-react';
import { useTheme } from '@/components/Providers';

const CreatsifyNekoCanvas = dynamic(
  () => import('@/components/ui/CreatsifyNekoCanvas').then((m) => m.CreatsifyNekoCanvas),
  { ssr: false }
);

export function AboutNekoAvatar() {
  const { theme } = useTheme();
  const [boopSignal, setBoopSignal] = useState(0);

  return (
    <div className="mb-6 flex items-center gap-4 rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] p-3.5">
      <button
        type="button"
        onClick={() => setBoopSignal((n) => n + 1)}
        title="Tap to boop Creatsify Neko"
        className="relative h-20 w-20 shrink-0 overflow-hidden rounded-xl border border-[var(--accent)]/35 bg-[radial-gradient(circle_at_50%_35%,rgba(0,210,238,0.18),rgba(10,16,34,0.88))] cursor-pointer"
      >
        <CreatsifyNekoCanvas
          variant="card-avatar"
          mode="wave"
          expressionOverride="joy"
          isDark={theme === 'dark'}
          boopSignal={boopSignal}
          lazyMount={true}
          globalPointer={true}
          className="h-full w-full"
          ariaLabel="Creatsify Neko studio bust portrait"
        />
      </button>
      <div className="min-w-0">
        <div className="flex items-center gap-1.5 font-display text-sm font-bold text-[var(--text)]">
          <span>Meet Creatsify Neko</span>
          <Sparkles className="h-3.5 w-3.5 text-[var(--accent)]" />
        </div>
        <p className="mt-1 text-xs leading-relaxed text-[var(--muted)]">
          Our studio mascot representing unified Web, Flutter, Brand &amp; Growth engineering. Tap to say hi.
        </p>
      </div>
    </div>
  );
}
