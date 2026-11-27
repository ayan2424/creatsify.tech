'use client';

import React, { useState } from 'react';
import dynamic from 'next/dynamic';
import { useTheme } from '@/components/Providers';

const CreatsifyNekoCanvas = dynamic(
  () => import('@/components/ui/CreatsifyNekoCanvas').then((m) => m.CreatsifyNekoCanvas),
  { ssr: false }
);

export function NotFoundNeko() {
  const { theme } = useTheme();
  const [boopSignal, setBoopSignal] = useState(0);

  return (
    <div
      onClick={() => setBoopSignal((n) => n + 1)}
      className="relative mb-2 h-[200px] w-[200px] cursor-pointer"
      title="Tap Neko"
    >
      <CreatsifyNekoCanvas
        variant="mini-corner"
        mode="think"
        isDark={theme === 'dark'}
        boopSignal={boopSignal}
        globalPointer={true}
        interactive={true}
        className="h-full w-full"
        ariaLabel="Curious Creatsify Chibi Neko Robot on 404 page"
      />
    </div>
  );
}
