import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import {
  ArrowLeft,
  ArrowRight,
  Check,
  ExternalLink,
  MessageCircle,
  ShoppingBag,
  Star,
} from 'lucide-react';
import { getBootstrap, getProjectBySlug } from '@/lib/api';
import { hasValidLiveUrl, whatsappLink } from '@/lib/utils';

export const revalidate = 60;

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  const { project } = await getProjectBySlug(slug);
  if (!project) {
    return { title: 'Project Not Found' };
  }
  return {
    title: `${project.title} — Case Study`,
    description: project.summary,
    alternates: { canonical: `/portfolio/${project.slug}` },
    openGraph: {
      title: `${project.title} | Creatsify Case Study`,
      description: project.summary,
      images: project.image_resolved ? [{ url: project.image_resolved }] : undefined,
    },
  };
}

export default async function ProjectDetailPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const [{ project, client_review: review, related }, boot] = await Promise.all([
    getProjectBySlug(slug),
    getBootstrap(),
  ]);

  if (!project) {
    notFound();
  }

  const showLive = hasValidLiveUrl(project.live_url);
  const waHref = whatsappLink(
    boot.site.whatsapp_number,
    `Hello! I am interested in licensing or building something similar to ${project.title} with Creatsify.`
  );

  return (
    <article className="pb-16 pt-10 lg:pt-14">
      <div className="mx-auto max-w-[1160px] px-5 sm:px-6">
        <Link
          href="/portfolio"
          className="inline-flex items-center gap-2 rounded-full border border-[var(--line)] bg-[var(--surface)] px-4 py-2 text-xs font-semibold text-[var(--muted)] transition-colors hover:border-[var(--line-strong)] hover:text-[var(--text)]"
        >
          <ArrowLeft className="h-3.5 w-3.5" />
          <span>Back to Portfolio</span>
        </Link>

        <header className="mt-7 max-w-3xl">
          <div className="flex flex-wrap items-center gap-2 text-xs font-semibold">
            <span className="rounded-full bg-[var(--accent-soft)] px-3 py-1 text-[var(--accent)]">
              {project.subcategory || project.category_label}
            </span>
            {project.outcome_metric && (
              <span className="rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 font-mono text-emerald-500">
                {project.outcome_metric}
              </span>
            )}
            {project.is_software && project.price && (
              <span className="rounded-full border border-[var(--line-strong)] bg-[var(--surface)] px-3 py-1 font-mono text-[var(--text)] tabular-nums">
                Direct License {project.price}
              </span>
            )}
          </div>

          <h1 className="mt-4 font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-5xl">
            {project.title}
          </h1>
          <p className="mt-4 text-base leading-relaxed text-[var(--muted)] sm:text-lg">
            {project.summary}
          </p>

          {project.tech_list.length > 0 && (
            <div className="mt-5 flex flex-wrap gap-2">
              {project.tech_list.map((tech) => (
                <span
                  key={tech}
                  className="rounded-lg border border-[var(--line)] bg-[var(--surface)] px-3 py-1 font-mono text-xs font-medium text-[var(--text)]"
                >
                  {tech}
                </span>
              ))}
            </div>
          )}

          <div className="mt-7 flex flex-wrap items-center gap-3">
            {showLive && (
              <a
                href={project.live_url!}
                target="_blank"
                rel="noopener noreferrer"
                className="btn-brand"
              >
                <ExternalLink className="h-4 w-4" />
                <span>Open Live Demo</span>
              </a>
            )}

            {project.is_software && (
              <a
                href={
                  project.gumroad_url ||
                  `/contact?kind=software_presale&offering=${encodeURIComponent(project.title)}`
                }
                target={project.gumroad_url ? '_blank' : undefined}
                rel={project.gumroad_url ? 'noopener noreferrer' : undefined}
                className="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-5 py-3 font-display text-sm font-bold text-[#041021] transition-transform hover:brightness-110 active:scale-95"
              >
                <ShoppingBag className="h-4 w-4" />
                <span>
                  Buy White-Label License{project.price ? ` (${project.price})` : ''}
                </span>
              </a>
            )}

            <a
              href={waHref}
              target="_blank"
              rel="noopener noreferrer"
              className="btn-ghost"
            >
              <MessageCircle className="h-4 w-4 text-emerald-500" />
              <span>Chat on WhatsApp</span>
            </a>
          </div>
        </header>

        {/* Showcase Frame */}
        <div className="mt-10 overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[#0b0e16]">
          <div className="flex items-center justify-between border-b border-white/10 bg-[#080a10] px-4 py-3">
            <div className="flex items-center gap-1.5" aria-hidden="true">
              <span className="h-2.5 w-2.5 rounded-full bg-[#ff5f56]" />
              <span className="h-2.5 w-2.5 rounded-full bg-[#ffbd2e]" />
              <span className="h-2.5 w-2.5 rounded-full bg-[#27c93f]" />
            </div>
            <span className="font-mono text-xs text-slate-400">
              {project.is_software ? 'Production Commercial Software' : 'Shipped Case Study'}
            </span>
          </div>
          {project.image_resolved && (
            <img
              src={project.image_resolved}
              alt={`${project.title} interface showcase`}
              className="max-h-[600px] w-full object-cover object-top"
            />
          )}
        </div>

        {/* Spec Grid */}
        <div className="mt-12 grid gap-8 lg:grid-cols-12">
          <div className="space-y-8 lg:col-span-8">
            <section className="rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-7">
              <h2 className="font-display text-2xl font-bold text-[var(--text)]">
                Architecture &amp; Delivery Overview
              </h2>
              <div className="mt-4 whitespace-pre-line text-[15px] leading-[1.78] text-[var(--muted)]">
                {project.description || project.summary}
              </div>

              {project.is_software && (
                <div className="mt-7 rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] p-5">
                  <h3 className="font-display text-base font-bold text-[var(--text)]">
                    Commercial License Inclusions
                  </h3>
                  <div className="mt-3 grid gap-2.5 sm:grid-cols-2 text-sm text-[var(--text)]">
                    {[
                      '100% Unlocked Source Code',
                      'White-Label Rebranding Rights',
                      'First-Party Support Portal Access',
                      'aaPanel / VPS Deployment Guide',
                      'Zero Recurring Monthly SaaS Fees',
                      'Optional Custom Feature Tailoring',
                    ].map((inc) => (
                      <div key={inc} className="flex items-center gap-2">
                        <Check className="h-4 w-4 shrink-0 text-[var(--emerald)]" />
                        <span>{inc}</span>
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </section>

            {review && (
              <section className="rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-7">
                <div className="flex items-center justify-between gap-2">
                  <div className="flex items-center gap-1 text-amber-400">
                    {Array.from({ length: Math.max(1, Math.min(5, review.rating || 5)) }).map((_, i) => (
                      <Star key={i} className="h-4 w-4 fill-amber-400" />
                    ))}
                  </div>
                  {review.outcome_metric && (
                    <span className="rounded-full bg-[var(--accent-soft)] px-3 py-1 font-mono text-xs font-semibold text-[var(--accent)]">
                      {review.outcome_metric}
                    </span>
                  )}
                </div>
                <p className="mt-4 text-base leading-relaxed text-[var(--text)]">
                  &ldquo;{review.review_text}&rdquo;
                </p>
                <div className="mt-4 text-sm font-semibold text-[var(--muted)]">
                  {review.client_name} · {review.client_role || 'Verified Client'}
                  {review.company_name ? ` (${review.company_name})` : ''}
                </div>
              </section>
            )}
          </div>

          <aside className="space-y-6 lg:col-span-4">
            <div className="rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-6">
              <h3 className="font-display text-base font-bold text-[var(--text)]">
                Project Metadata
              </h3>
              <dl className="mt-4 divide-y divide-[var(--line)] text-sm">
                <div className="flex justify-between py-2.5">
                  <dt className="text-[var(--muted)]">Discipline</dt>
                  <dd className="font-semibold text-[var(--text)]">{project.category_label}</dd>
                </div>
                {project.client_name && (
                  <div className="flex justify-between py-2.5">
                    <dt className="text-[var(--muted)]">Client</dt>
                    <dd className="font-semibold text-[var(--text)]">{project.client_name}</dd>
                  </div>
                )}
                <div className="flex justify-between py-2.5">
                  <dt className="text-[var(--muted)]">Availability</dt>
                  <dd className="font-semibold text-[var(--text)]">
                    {project.is_software ? 'Direct License / Custom' : 'Custom Build'}
                  </dd>
                </div>
              </dl>

              <Link
                href={`/contact?offering=${encodeURIComponent(
                  project.is_software ? `Customize ${project.title}` : `Build similar to ${project.title}`
                )}`}
                className="btn-brand mt-5 w-full"
              >
                <span>Build something similar</span>
                <ArrowRight className="h-4 w-4" />
              </Link>

              {project.is_software && (
                <Link
                  href={`/support?offering=${encodeURIComponent(project.title)}`}
                  className="btn-ghost mt-3 w-full"
                >
                  <span>Already own it? Open support</span>
                </Link>
              )}
            </div>
          </aside>
        </div>

        {/* Related Work */}
        {related.length > 0 && (
          <section className="mt-16 border-t border-[var(--line)] pt-12">
            <h2 className="font-display text-2xl font-bold text-[var(--text)]">
              More shipped work
            </h2>
            <div className="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
              {related.map((item) => (
                <Link
                  key={item.slug}
                  href={`/portfolio/${item.slug}`}
                  className="group overflow-hidden rounded-2xl border border-[var(--line)] bg-[var(--surface)] transition-colors hover:border-[var(--line-strong)]"
                >
                  {item.image_resolved && (
                    <div className="aspect-[16/10] overflow-hidden bg-[#080a10]">
                      <img
                        src={item.image_resolved}
                        alt={item.title}
                        loading="lazy"
                        className="h-full w-full object-cover object-top transition-transform duration-300 group-hover:scale-105"
                      />
                    </div>
                  )}
                  <div className="p-5">
                    <span className="text-xs font-medium text-[var(--accent)]">
                      {item.category_label}
                    </span>
                    <h3 className="mt-1 font-display text-base font-bold text-[var(--text)] group-hover:text-[var(--accent)]">
                      {item.title}
                    </h3>
                  </div>
                </Link>
              ))}
            </div>
          </section>
        )}
      </div>
    </article>
  );
}
