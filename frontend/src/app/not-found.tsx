import React from 'react';
import Link from 'next/link';
import { ArrowLeft, ArrowUpRight } from 'lucide-react';

export default function NotFound() {
  return (
    <section className="mx-auto flex min-h-[65vh] max-w-[920px] flex-col items-center justify-center px-5 py-24 text-center sm:px-6">
      <p className="font-mono text-sm font-semibold text-[var(--accent)] tabular-nums">404</p>
      <h1 className="mt-3 font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-5xl">
        Page not found
      </h1>
      <p className="mt-4 max-w-md text-[15.5px] leading-relaxed text-[var(--muted)]">
        The page you&apos;re looking for may have moved or no longer exists. Jump back to our studio showcase or reach out directly.
      </p>
      <div className="mt-8 flex flex-wrap items-center justify-center gap-3">
        <Link href="/" className="btn-brand">
          <ArrowLeft className="h-4 w-4" />
          <span>Back to Home</span>
        </Link>
        <Link href="/contact" className="btn-ghost">
          <span>Start a Project</span>
          <ArrowUpRight className="h-4 w-4" />
        </Link>
      </div>
    </section>
  );
}
