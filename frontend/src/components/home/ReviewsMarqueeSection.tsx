'use client';

import React, { useRef, useState } from 'react';
import Link from 'next/link';
import type { ReviewItem, ReviewStats } from '@/types/creatsify';
import { resolveAvatarUrl } from '@/lib/utils';

function MarqueeRow({
  reviews,
  rowClass,
  trackClass,
  avatarOffset,
  ariaLabel,
  onDragStateChange,
}: {
  reviews: ReviewItem[];
  rowClass: string;
  trackClass: string;
  avatarOffset: number;
  ariaLabel: string;
  onDragStateChange: (active: boolean) => void;
}) {
  const rowRef = useRef<HTMLDivElement>(null);
  const draggingRef = useRef(false);
  const startXRef = useRef(0);
  const scrollStartRef = useRef(0);

  const onPointerDown = (e: React.PointerEvent<HTMLDivElement>) => {
    const row = rowRef.current;
    if (!row) return;
    draggingRef.current = true;
    row.classList.add('is-dragging');
    onDragStateChange(true);
    startXRef.current = e.clientX;
    scrollStartRef.current = row.scrollLeft;
    row.setPointerCapture(e.pointerId);
  };

  const onPointerMove = (e: React.PointerEvent<HTMLDivElement>) => {
    const row = rowRef.current;
    if (!row || !draggingRef.current) return;
    const walk = (e.clientX - startXRef.current) * 1.4;
    row.scrollLeft = scrollStartRef.current - walk;
  };

  const onPointerUp = (e: React.PointerEvent<HTMLDivElement>) => {
    const row = rowRef.current;
    if (!draggingRef.current) return;
    draggingRef.current = false;
    if (row) {
      row.classList.remove('is-dragging');
      if (row.hasPointerCapture(e.pointerId)) {
        row.releasePointerCapture(e.pointerId);
      }
    }
    setTimeout(() => onDragStateChange(false), 350);
  };

  return (
    <div
      ref={rowRef}
      className={`om-marquee-row ${rowClass}`}
      aria-label={ariaLabel}
      role="region"
      tabIndex={0}
      onPointerDown={onPointerDown}
      onPointerMove={onPointerMove}
      onPointerUp={onPointerUp}
      onPointerCancel={onPointerUp}
    >
      <div className={`om-marquee-track ${trackClass}`}>
        {[0, 1, 2].map((rep) =>
          reviews.map((rev, idx) => {
            const stars = Math.max(1, Math.min(5, Number(rev.rating) || 5));
            const avatarUrl = resolveAvatarUrl(avatarOffset + idx);

            return (
              <article
                key={`${rep}-${rev.id}-${idx}`}
                className="om-rev-card"
                aria-hidden={rep > 0 ? 'true' : undefined}
              >
                <div className="om-rev-header-row">
                  <div className="om-rev-quote-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.324 1.202-4.544 3.284-4.664 5.234.34-.143.76-.235 1.23-.235 1.95 0 3.511 1.562 3.511 3.511 0 1.95-1.562 3.511-3.511 3.511-.703 0-1.354-.213-1.906-.901zm11 0C14.553 16.227 14 15 14 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.324 1.202-4.544 3.284-4.664 5.234.34-.143.76-.235 1.23-.235 1.95 0 3.511 1.562 3.511 3.511 0 1.95-1.562 3.511-3.511 3.511-.703 0-1.354-.213-1.906-.901z" />
                    </svg>
                  </div>
                  <div className="om-rev-top-meta">
                    <div className="om-rev-stars" aria-label={`${stars} out of 5 stars`}>
                      {'\u2605'.repeat(stars)}
                    </div>
                    {rev.outcome_metric && (
                      <span className="om-rev-metric">{rev.outcome_metric}</span>
                    )}
                  </div>
                </div>

                <p className="om-rev-text">&ldquo;{rev.review_text}&rdquo;</p>

                <div className="om-rev-author-row">
                  <img
                    src={avatarUrl}
                    alt={rev.client_name}
                    className="om-rev-avatar"
                    width={44}
                    height={44}
                    loading="eager"
                    draggable={false}
                  />
                  <div className="om-rev-author-info">
                    <strong className="om-rev-name">{rev.client_name}</strong>
                    <span className="om-rev-role">
                      {rev.client_role || 'Verified Client'}
                      {rev.company_name ? ` \u2022 ${rev.company_name}` : ''}
                    </span>
                  </div>
                </div>
              </article>
            );
          })
        )}
      </div>
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
  const [dragActive, setDragActive] = useState(false);

  if (reviews.length === 0) return null;

  const half = Math.ceil(reviews.length / 2);
  const row1 = reviews.slice(0, half);
  const row2 = reviews.slice(half).length > 0 ? reviews.slice(half) : row1;

  return (
    <section id="reviews" className="ag-reviews-sec">
      <div className="wrap">
        <div className="om-sec-center">
          <div className="om-sec-tag">
            <span className="om-sec-dot" /> Client feedback
          </div>
          <h2 className="om-sec-heading">What clients say about working with us</h2>
          <p className="om-sec-sub">
            Honest feedback from founders and teams we&apos;ve built, designed and grown with.
          </p>

          <div
            className="ag-rating-summary-pill"
            style={{ margin: '20px auto 36px', display: 'inline-flex' }}
          >
            <div className="ag-rsp-score">{stats.avg_rating || '4.9'}</div>
            <div className="ag-rsp-meta" style={{ textAlign: 'left' }}>
              <div className="ag-rsp-stars" aria-label="5 out of 5 stars">
                &#9733;&#9733;&#9733;&#9733;&#9733;
              </div>
              <span>
                {stats.total > 0
                  ? `Based on ${stats.total} Verified Reviews`
                  : 'Client Reviews'}
              </span>
            </div>
            <div className="ag-rsp-actions">
              <Link href="/reviews#submit-review" className="om-btn-gold sm">
                <span>Leave a Review &#9733;</span>
              </Link>
              <Link href="/reviews" className="om-btn-glass sm">
                <span>All Reviews</span>
              </Link>
            </div>
          </div>
        </div>

        <div
          className={`om-reviews-marquee-container edge-fade-x${dragActive ? ' js-drag-active' : ''}`}
          id="reviewsMarqueeContainer"
        >
          <MarqueeRow
            reviews={row1}
            rowClass="om-marquee-row-1"
            trackClass="om-marquee-left"
            avatarOffset={0}
            ariaLabel="Client reviews stream row 1"
            onDragStateChange={setDragActive}
          />
          <MarqueeRow
            reviews={row2}
            rowClass="om-marquee-row-2"
            trackClass="om-marquee-right"
            avatarOffset={half}
            ariaLabel="Client reviews stream row 2"
            onDragStateChange={setDragActive}
          />
        </div>
      </div>
    </section>
  );
}
