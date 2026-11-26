'use client';

import React, { useRef } from 'react';

interface SpotlightCardProps extends React.HTMLAttributes<HTMLDivElement> {
  children: React.ReactNode;
  as?: 'div' | 'article';
  active?: boolean;
}

export function SpotlightCard({
  children,
  as = 'div',
  active = false,
  className = '',
  onMouseMove,
  ...rest
}: SpotlightCardProps) {
  const ref = useRef<HTMLDivElement>(null);

  const handleMove = (e: React.MouseEvent<HTMLDivElement>) => {
    const el = ref.current;
    if (el) {
      const rect = el.getBoundingClientRect();
      el.style.setProperty('--mx', `${e.clientX - rect.left}px`);
      el.style.setProperty('--my', `${e.clientY - rect.top}px`);
    }
    onMouseMove?.(e);
  };

  const Comp = as;

  return (
    <Comp
      ref={ref}
      onMouseMove={handleMove}
      data-active={active ? 'true' : undefined}
      className={`spotlight-card ${className}`}
      {...rest}
    >
      {children}
    </Comp>
  );
}
