import React from 'react';
import Link from 'next/link';
import { ArrowLeft, Home } from 'lucide-react';

export default function NotFound() {
  return (
    <div className="mx-auto flex min-h-[62vh] max-w-xl flex-col items-center justify-center px-5 py-20 text-center">
      <span className="rounded-full border border-[var(--line-strong)] bg-[var(--surface)] px-3.5 py-1 font-mono text-xs font-semibold text-[var(--accent)]">
        404 · Page not found
      </span>
      <h1 className="mt-5 font-display text-3xl font-extrabold text-[var(--text)] sm:text-4xl">
        This page doesn&apos;t exist
      </h1>
      <p className="mt-3 text-sm leading-relaxed text-[var(--muted)]">
        The link may have moved or the URL was mistyped. Jump back to the studio homepage or browse our shipped portfolio.
      </p>
      <div className="mt-7 flex flex-wrap justify-center gap-3">
        <Link href="/" className="btn-brand">
          <Home className="h-4 w-4" />
          <span>Return Home</span>
        </Link>
        <Link href="/portfolio" className="btn-ghost">
          <ArrowLeft className="h-4 w-4" />
          <span>Explore Work</span>
        </Link>
      </div>
    </div>
  );
}
