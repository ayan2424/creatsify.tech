'use client';

import React, { useState } from 'react';
import { motion } from 'motion/react';
import { CheckCircle2, Loader2, Quote, Send, Star } from 'lucide-react';
import type { ReviewItem, ReviewStats } from '@/types/creatsify';
import { fetchClientCsrfToken } from '@/lib/api';
import { resolveAvatarUrl } from '@/lib/utils';

interface ReviewsClientProps {
  initialReviews: ReviewItem[];
  categories: string[];
  stats: ReviewStats;
}

export function ReviewsClient({ initialReviews, categories, stats }: ReviewsClientProps) {
  const [activeCat, setActiveCat] = useState<string>('all');
  const [reviews, setReviews] = useState<ReviewItem[]>(initialReviews);

  // Review submission state
  const [clientName, setClientName] = useState('');
  const [clientEmail, setClientEmail] = useState('');
  const [clientRole, setClientRole] = useState('');
  const [companyName, setCompanyName] = useState('');
  const [serviceCategory, setServiceCategory] = useState(categories[0] || 'Web Development');
  const [rating, setRating] = useState<number>(5);
  const [reviewTitle, setReviewTitle] = useState('');
  const [reviewText, setReviewText] = useState('');
  const [outcomeMetric, setOutcomeMetric] = useState('');
  const [honeypot, setHoneypot] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [feedback, setFeedback] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

  const visibleReviews =
    activeCat === 'all'
      ? reviews
      : reviews.filter((r) => r.service_category === activeCat);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (submitting) return;
    setSubmitting(true);
    setFeedback(null);

    try {
      const csrf = await fetchClientCsrfToken();
      const res = await fetch('/api/v1/reviews', {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-Token': csrf,
        },
        body: JSON.stringify({
          _csrf_token: csrf,
          _hp_check: honeypot,
          client_name: clientName,
          client_email: clientEmail,
          client_role: clientRole,
          company_name: companyName,
          service_category: serviceCategory,
          rating,
          review_title: reviewTitle,
          review_text: reviewText,
          outcome_metric: outcomeMetric,
        }),
      });
      const json = await res.json();
      if (res.ok && json?.success) {
        setFeedback({ type: 'success', text: json.message });
        if (json.status === 'approved') {
          setReviews((prev) => [
            {
              id: json.id || Date.now(),
              client_name: clientName,
              client_role: clientRole || 'Verified Client',
              company_name: companyName,
              service_category: serviceCategory,
              rating,
              review_title: reviewTitle,
              review_text: reviewText,
              outcome_metric: outcomeMetric || 'Verified Delivery',
            },
            ...prev,
          ]);
        }
        setClientName('');
        setClientEmail('');
        setClientRole('');
        setCompanyName('');
        setReviewTitle('');
        setReviewText('');
        setOutcomeMetric('');
      } else {
        setFeedback({
          type: 'error',
          text: json?.error || 'Could not submit review. Please check all required fields.',
        });
      }
    } catch {
      setFeedback({
        type: 'error',
        text: 'Network error submitting review. Please try again shortly.',
      });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="mx-auto max-w-[1220px] px-5 py-14 sm:px-6 lg:py-20">
      {/* Header + Verified Rating Summary */}
      <div className="grid gap-8 lg:grid-cols-12 lg:items-center">
        <div className="lg:col-span-7">
          <h1 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-5xl">
            Verified client reviews &amp; real outcomes
          </h1>
          <p className="mt-4 text-base leading-relaxed text-[var(--muted)] sm:text-lg">
            Unfiltered feedback from startup founders, e-commerce teams, and commercial software buyers across web engineering, Flutter apps, branding, and paid growth.
          </p>
          <div className="mt-6">
            <a href="#submit-review" className="btn-brand">
              <span>Share your experience</span>
            </a>
          </div>
        </div>

        <div className="rounded-2xl border border-[var(--line-strong)] bg-[var(--surface)] p-6 lg:col-span-5">
          <div className="flex items-center gap-4">
            <span className="font-display text-5xl font-extrabold text-[var(--text)] tabular-nums">
              {stats.avg_rating}
            </span>
            <div>
              <div className="flex items-center gap-0.5 text-amber-400">
                {[0, 1, 2, 3, 4].map((i) => (
                  <Star key={i} className="h-4 w-4 fill-amber-400" />
                ))}
              </div>
              <p className="mt-1 text-xs font-medium text-[var(--muted)] tabular-nums">
                Based on {stats.total} verified client reviews
              </p>
            </div>
          </div>

          <div className="mt-5 space-y-2">
            {[5, 4, 3, 2, 1].map((star) => {
              const dist = stats.distribution?.[star] || { count: 0, percent: 0 };
              return (
                <div key={star} className="flex items-center gap-3 text-xs">
                  <span className="w-8 font-mono font-semibold text-[var(--muted)] tabular-nums">
                    {star}★
                  </span>
                  <div className="h-2 flex-1 overflow-hidden rounded-full bg-[var(--bg-subtle)]">
                    <div
                      className="h-full rounded-full bg-[var(--accent)]"
                      style={{ width: `${dist.percent}%` }}
                    />
                  </div>
                  <span className="w-8 text-right font-mono text-[var(--muted)] tabular-nums">
                    {dist.count}
                  </span>
                </div>
              );
            })}
          </div>
        </div>
      </div>

      {/* Category Filter Pills */}
      <div className="mt-12 flex flex-wrap gap-2" role="group" aria-label="Filter reviews by category">
        {['all', ...categories].map((cat) => {
          const active = activeCat === cat;
          return (
            <button
              key={cat}
              type="button"
              onClick={() => setActiveCat(cat)}
              aria-pressed={active}
              className={`relative rounded-full px-4 py-2 text-xs font-semibold transition-colors cursor-pointer sm:text-[13px] ${
                active
                  ? 'text-[var(--on-accent)]'
                  : 'border border-[var(--line)] bg-[var(--surface)] text-[var(--muted)] hover:text-[var(--text)]'
              }`}
            >
              {active && (
                <motion.span
                  layoutId="review-cat-pill"
                  transition={{ type: 'spring', stiffness: 420, damping: 32 }}
                  className="absolute inset-0 -z-0 rounded-full bg-[var(--accent)]"
                />
              )}
              <span className="relative z-10">{cat === 'all' ? 'All Categories' : cat}</span>
            </button>
          );
        })}
      </div>

      {/* Reviews Grid */}
      <div id="reviews-grid" className="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        {visibleReviews.map((rev, idx) => (
          <article
            key={rev.id}
            className="flex flex-col justify-between rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-6"
          >
            <div>
              <div className="flex items-center justify-between gap-2">
                <div className="flex items-center gap-0.5 text-amber-400">
                  {Array.from({ length: Math.max(1, Math.min(5, Number(rev.rating) || 5)) }).map(
                    (_, i) => (
                      <Star key={i} className="h-3.5 w-3.5 fill-amber-400" />
                    )
                  )}
                </div>
                {rev.outcome_metric && (
                  <span className="rounded-full bg-[var(--accent-soft)] px-2.5 py-0.5 font-mono text-[11px] font-semibold text-[var(--accent)]">
                    {rev.outcome_metric}
                  </span>
                )}
              </div>

              <h2 className="mt-4 font-display text-base font-bold text-[var(--text)]">
                {rev.review_title}
              </h2>

              <div className="mt-2.5 flex items-start gap-2">
                <Quote className="mt-1 h-4 w-4 shrink-0 text-[var(--accent)] opacity-45" />
                <p className="text-sm leading-relaxed text-[var(--muted)]">{rev.review_text}</p>
              </div>
            </div>

            <div className="mt-5 flex items-center gap-3 border-t border-[var(--line)] pt-4">
              <img
                src={resolveAvatarUrl(idx)}
                alt={rev.client_name}
                width={40}
                height={40}
                loading="lazy"
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
        ))}
      </div>

      {/* Submit Review Form */}
      <section
        id="submit-review"
        className="mx-auto mt-20 max-w-3xl scroll-mt-24 rounded-2xl border border-[var(--line-strong)] bg-[var(--surface)] p-6 sm:p-8"
      >
        <h2 className="font-display text-2xl font-bold text-[var(--text)]">
          Worked with Creatsify? Leave a verified review
        </h2>
        <p className="mt-2 text-sm text-[var(--muted)]">
          Your honest feedback helps other founders understand how we deliver.
        </p>

        {feedback && (
          <div
            role="status"
            className={`mt-5 flex items-start gap-2.5 rounded-xl border p-4 text-sm ${
              feedback.type === 'success'
                ? 'border-emerald-500/35 bg-emerald-500/10 text-emerald-500'
                : 'border-rose-500/35 bg-rose-500/10 text-rose-400'
            }`}
          >
            {feedback.type === 'success' && <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0" />}
            <span>{feedback.text}</span>
          </div>
        )}

        <form onSubmit={handleSubmit} className="mt-6 space-y-4">
          <input
            type="text"
            name="_hp_check"
            value={honeypot}
            onChange={(e) => setHoneypot(e.target.value)}
            className="hidden"
            tabIndex={-1}
            autoComplete="off"
          />

          {/* Interactive Star Picker */}
          <div>
            <span className="block text-xs font-semibold text-[var(--text)]">Your rating</span>
            <div className="mt-2 flex items-center gap-1.5">
              {[1, 2, 3, 4, 5].map((star) => (
                <button
                  key={star}
                  type="button"
                  onClick={() => setRating(star)}
                  aria-label={`Rate ${star} out of 5 stars`}
                  className="rounded-lg p-1.5 transition-transform hover:scale-110 active:scale-95 cursor-pointer"
                >
                  <Star
                    className={`h-6 w-6 ${
                      star <= rating
                        ? 'fill-amber-400 text-amber-400'
                        : 'text-[var(--muted)] opacity-40'
                    }`}
                  />
                </button>
              ))}
              <span className="ml-2 font-mono text-xs font-semibold text-[var(--muted)] tabular-nums">
                {rating}.0 / 5.0
              </span>
            </div>
          </div>

          <div className="grid gap-4 sm:grid-cols-2">
            <label className="block text-xs font-semibold text-[var(--text)]">
              Your name *
              <input
                type="text"
                required
                value={clientName}
                onChange={(e) => setClientName(e.target.value)}
                placeholder="Alex Morgan"
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
            <label className="block text-xs font-semibold text-[var(--text)]">
              Work email (private)
              <input
                type="email"
                value={clientEmail}
                onChange={(e) => setClientEmail(e.target.value)}
                placeholder="alex@company.com"
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
          </div>

          <div className="grid gap-4 sm:grid-cols-2">
            <label className="block text-xs font-semibold text-[var(--text)]">
              Role / Title
              <input
                type="text"
                value={clientRole}
                onChange={(e) => setClientRole(e.target.value)}
                placeholder="Founder & CEO"
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
            <label className="block text-xs font-semibold text-[var(--text)]">
              Company / Brand
              <input
                type="text"
                value={companyName}
                onChange={(e) => setCompanyName(e.target.value)}
                placeholder="Acme Logistics"
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
          </div>

          <div className="grid gap-4 sm:grid-cols-2">
            <label className="block text-xs font-semibold text-[var(--text)]">
              Service Category
              <select
                value={serviceCategory}
                onChange={(e) => setServiceCategory(e.target.value)}
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              >
                {categories.map((c) => (
                  <option key={c} value={c}>
                    {c}
                  </option>
                ))}
              </select>
            </label>
            <label className="block text-xs font-semibold text-[var(--text)]">
              Key Result / Outcome (optional)
              <input
                type="text"
                value={outcomeMetric}
                onChange={(e) => setOutcomeMetric(e.target.value)}
                placeholder="e.g., +150% Conversion Lift or 10-Day Launch"
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
          </div>

          <label className="block text-xs font-semibold text-[var(--text)]">
            Headline *
            <input
              type="text"
              required
              minLength={4}
              value={reviewTitle}
              onChange={(e) => setReviewTitle(e.target.value)}
              placeholder="Fast, clean engineering and clear communication"
              className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
            />
          </label>

          <label className="block text-xs font-semibold text-[var(--text)]">
            Your detailed review *
            <textarea
              rows={4}
              required
              minLength={15}
              value={reviewText}
              onChange={(e) => setReviewText(e.target.value)}
              placeholder="Share what we built together and how the launch performed..."
              className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
            />
          </label>

          <button type="submit" disabled={submitting} className="btn-brand w-full sm:w-auto">
            {submitting ? (
              <>
                <Loader2 className="h-4 w-4 animate-spin" />
                <span>Submitting review...</span>
              </>
            ) : (
              <>
                <Send className="h-4 w-4" />
                <span>Submit Verified Review</span>
              </>
            )}
          </button>
        </form>
      </section>
    </div>
  );
}
