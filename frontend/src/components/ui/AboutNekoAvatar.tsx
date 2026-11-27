'use client';

import React, { useState } from 'react';
import dynamic from 'next/dynamic';
import { useTheme } from '@/components/Providers';

const CreatsifyNekoCanvas = dynamic(
  () => import('@/components/ui/CreatsifyNekoCanvas').then((m) => m.CreatsifyNekoCanvas),
  { ssr: false }
);

export function AboutNekoAvatar() {
  const { theme } = useTheme();
  const [boopSignal, setBoopSignal] = useState(0);

  return (
    <div className="mb-5 flex items-center gap-3 border-b border-[var(--line)] pb-4">
      <button
        type="button"
        onClick={() => setBoopSignal((n) => n + 1)}
        title="Tap Creatsify Neko"
        className="relative -my-3 -ml-2 h-[108px] w-[104px] shrink-0 overflow-visible border-0 bg-transparent p-0 cursor-pointer"
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
        <strong className="block font-display text-base font-bold text-[var(--text)]">
          Creatsify Studio Team
        </strong>
        <p className="mt-1 text-xs leading-relaxed text-[var(--muted)]">
          Web, Flutter, Brand &amp; Growth specialists collaborating under one roof.
        </p>
      </div>
    </div>
  );
}
