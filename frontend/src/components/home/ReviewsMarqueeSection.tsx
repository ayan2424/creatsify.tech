'use client';

import React, { useEffect, useRef } from 'react';
import Link from 'next/link';
import { ArrowUpRight, PenLine, Quote, Star } from 'lucide-react';
import type { ReviewItem, ReviewStats } from '@/types/creatsify';
import { resolveAvatarUrl } from '@/lib/utils';

interface DraggableRowProps {
  reviews: ReviewItem[];
  direction: 'left' | 'right';
  avatarOffset: number;
  ariaLabel: string;
}

function DraggableMarqueeRow({
  reviews,
  direction,
  avatarOffset,
  ariaLabel,
}: DraggableRowProps) {
  const scrollerRef = useRef<HTMLDivElement>(null);
  const draggingRef = useRef(false);
  const hoveredRef = useRef(false);
  const startXRef = useRef(0);
  const startScrollLeftRef = useRef(0);

  // Smooth infinite drift paused cleanly during hover, focus, or user mouse/touch drag
  useEffect(() => {
    const el = scrollerRef.current;
    if (!el) return;
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduced) return;

    // Position rightward track in middle copy so dragging left/right both loop seamlessly
    const singleSetWidth = () => el.scrollWidth / 3;
    if (direction === 'right') {
      el.scrollLeft = singleSetWidth();
    }

    let rafId = 0;
    const speed = direction === 'left' ? 0.55 : -0.55;

    const tick = () => {
      if (!draggingRef.current && !hoveredRef.current) {
        el.scrollLeft += speed;
        const unit = singleSetWidth();
        if (unit > 0) {
          if (el.scrollLeft >= unit * 2) {
            el.scrollLeft -= unit;
          } else if (el.scrollLeft <= 1) {
            el.scrollLeft += unit;
          }
        }
      }
      rafId = requestAnimationFrame(tick);
    };

    rafId = requestAnimationFrame(tick);
    return () => cancelAnimationFrame(rafId);
  }, [direction, reviews.length]);

  const onPointerDown = (e: React.PointerEvent<HTMLDivElement>) => {
    const el = scrollerRef.current;
    if (!el) return;
    draggingRef.current = true;
    startXRef.current = e.clientX;
    startScrollLeftRef.current = el.scrollLeft;
    el.setPointerCapture(e.pointerId);
  };

  const onPointerMove = (e: React.PointerEvent<HTMLDivElement>) => {
    const el = scrollerRef.current;
    if (!el || !draggingRef.current) return;
    const delta = e.clientX - startXRef.current;
    el.scrollLeft = startScrollLeftRef.current - delta * 1.2;
  };

  const onPointerUp = (e: React.PointerEvent<HTMLDivElement>) => {
    const el = scrollerRef.current;
    draggingRef.current = false;
    if (el && el.hasPointerCapture(e.pointerId)) {
      el.releasePointerCapture(e.pointerId);
    }
  };

  const tripled = [...reviews, ...reviews, ...reviews];

  return (
    <div
      ref={scrollerRef}
      role="region"
      tabIndex={0}
      aria-label={ariaLabel}
      onMouseEnter={() => {
        hoveredRef.current = true;
      }}
      onMouseLeave={() => {
        hoveredRef.current = false;
        draggingRef.current = false;
      }}
      onPointerDown={onPointerDown}
      onPointerMove={onPointerMove}
      onPointerUp={onPointerUp}
      onPointerCancel={onPointerUp}
      className="no-scrollbar flex cursor-grab select-none gap-4 overflow-x-auto px-4 py-2 active:cursor-grabbing touch-pan-x"
    >
      {tripled.map((rev, i) => {
        const originalIdx = i % reviews.length;
        const isClone = i >= reviews.length;
        const stars = Math.max(1, Math.min(5, Number(rev.rating) || 5));

        return (
          <article
            key={`${rev.id}-${i}`}
            aria-hidden={isClone ? 'true' : undefined}
            className="flex w-[320px] shrink-0 flex-col justify-between rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-6 transition-colors hover:border-[var(--line-strong)] sm:w-[370px]"
          >
            <div>
              <div className="flex items-center justify-between gap-2">
                <div className="flex items-center gap-0.5 text-amber-400" aria-label={`${stars} out of 5 stars`}>
                  {Array.from({ length: stars }).map((_, sIdx) => (
                    <Star key={sIdx} className="h-3.5 w-3.5 fill-amber-400" />
                  ))}
                </div>
                {rev.outcome_metric && (
                  <span className="rounded-full bg-[var(--accent-soft)] px-2.5 py-0.5 font-mono text-[11px] font-semibold text-[var(--accent)]">
                    {rev.outcome_metric}
                  </span>
                )}
              </div>

              <div className="mt-4 flex items-start gap-2">
                <Quote className="mt-1 h-4 w-4 shrink-0 text-[var(--accent)] opacity-50" />
                <p className="text-[14px] leading-relaxed text-[var(--text)]">
                  {rev.review_text}
                </p>
              </div>
            </div>

            <div className="mt-5 flex items-center gap-3 border-t border-[var(--line)] pt-4">
              <img
                src={resolveAvatarUrl(avatarOffset + originalIdx)}
                alt={rev.client_name}
                width={40}
                height={40}
                loading="lazy"
                draggable={false}
                className="h-10 w-10 rounded-full border border-[var(--line-strong)] object-cover"
              />
              <div className="min-w-0">
                <strong className="block truncate font-display text-sm font-bold text-[var(--text)]">
                  {rev.client_name}
                </strong>
                <span className="block truncate text-xs text-[var(--muted)]">
                  {rev.client_role || 'Verified Client'}
                  {rev.company_name ? ` · ${rev.company_name}` : ''}
                </span>
              </div>
            </div>
          </article>
        );
      })}
    </div>
  );
}

export function ReviewsMarqueeSection({
  reviews,
  stats,
}: {
  reviews: ReviewItem[];
  stats: ReviewStats;
}) {
  if (reviews.length === 0) return null;

  const half = Math.ceil(reviews.length / 2);
  const row1 = reviews.slice(0, half);
  const row2 = reviews.slice(half).length > 0 ? reviews.slice(half) : row1;

  return (
    <section id="reviews" className="border-y border-[var(--line)] bg-[var(--bg-subtle)] py-20 lg:py-28">
      <div className="mx-auto max-w-[1220px] px-5 sm:px-6">
        <div className="mx-auto max-w-2xl text-center">
          <h2 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-4xl">
            What clients say about working with us
          </h2>
          <p className="mt-3 text-[15.5px] leading-relaxed text-[var(--muted)]">
            Drag or swipe either row to read verified feedback from founders and engineering leaders worldwide.
          </p>

          <div className="mt-6 inline-flex flex-wrap items-center justify-center gap-4 rounded-2xl border border-[var(--line-strong)] bg-[var(--surface)] px-5 py-3.5">
            <span className="font-display text-3xl font-extrabold text-[var(--text)] tabular-nums">
              {stats.avg_rating}
            </span>
            <div className="text-left">
              <div className="flex items-center gap-0.5 text-amber-400">
                {[0, 1, 2, 3, 4].map((i) => (
                  <Star key={i} className="h-3.5 w-3.5 fill-amber-400" />
                ))}
              </div>
              <span className="text-xs font-medium text-[var(--muted)] tabular-nums">
                Based on {stats.total}+ Verified Reviews
              </span>
            </div>
            <div className="flex items-center gap-2">
              <Link href="/reviews#submit-review" className="btn-brand !px-3.5 !py-2 !text-xs">
                <PenLine className="h-3.5 w-3.5" />
                <span>Leave a Review</span>
              </Link>
              <Link href="/reviews" className="btn-ghost !px-3.5 !py-2 !text-xs">
                <span>All Reviews</span>
              </Link>
            </div>
          </div>
        </div>
      </div>

      {/* Dual-Row Draggable / Swipeable Marquee */}
      <div className="relative mt-10 space-y-4">
        <DraggableMarqueeRow
          reviews={row1}
          direction="left"
          avatarOffset={0}
          ariaLabel="Client reviews row 1 — drag or swipe horizontally"
        />
        <DraggableMarqueeRow
          reviews={row2}
          direction="right"
          avatarOffset={half}
          ariaLabel="Client reviews row 2 — drag or swipe horizontally"
        />
      </div>

      <div className="mt-10 flex flex-wrap justify-center gap-3 px-5">
        <Link href="/reviews" className="btn-brand">
          <span>Read all {stats.total} reviews</span>
          <ArrowUpRight className="h-4 w-4" />
        </Link>
        <Link href="/reviews#submit-review" className="btn-ghost">
          <span>Worked with us? Share feedback</span>
        </Link>
      </div>
    </section>
  );
}
