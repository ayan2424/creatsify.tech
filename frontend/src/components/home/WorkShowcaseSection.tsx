'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { AnimatePresence, motion } from 'motion/react';
import { ArrowRight, ExternalLink, Eye, Monitor, ShoppingBag } from 'lucide-react';
import type { ProjectItem } from '@/types/creatsify';
import { PortfolioLightbox } from '@/components/PortfolioLightbox';
import { hasValidLiveUrl } from '@/lib/utils';

interface WorkShowcaseSectionProps {
  projects: ProjectItem[];
  categories: Record<string, string>;
  initialCategory?: string;
  showViewAllButton?: boolean;
  heading?: string;
  subcopy?: string;
}

export function WorkShowcaseSection({
  projects,
  categories,
  initialCategory = 'web-dev',
  showViewAllButton = true,
  heading = "Projects we're proud of",
  subcopy = 'Selected websites, mobile apps, SaaS platforms, redesigns, and growth systems — click Preview to inspect or open live.',
}: WorkShowcaseSectionProps) {
  const categoryEntries = Object.entries(categories);
  const firstKey = categoryEntries[0]?.[0] || 'web-dev';
  const [activeCat, setActiveCat] = useState<string>(
    categories[initialCategory] ? initialCategory : firstKey
  );
  const [lightboxProject, setLightboxProject] = useState<ProjectItem | null>(null);
  const [touchedSlug, setTouchedSlug] = useState<string | null>(null);

  const filtered = projects.filter((pr) => pr.category === activeCat);

  return (
    <section id="work" className="py-20 lg:py-28">
      <div className="mx-auto max-w-[1280px] px-5 sm:px-6">
        <div className="mx-auto max-w-2xl text-center">
          <h2 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-4xl">
            {heading}
          </h2>
          <p className="mt-3 text-[15.5px] leading-relaxed text-[var(--muted)]">{subcopy}</p>
        </div>

        {/* 7 Official Discipline Filter Tabs (Web Design & Development First — No All Work) */}
        <div
          role="group"
          aria-label="Filter case studies by discipline"
          className="mt-8 flex flex-wrap items-center justify-center gap-2"
        >
          {categoryEntries.map(([key, label]) => {
            const active = activeCat === key;
            return (
              <button
                key={key}
                type="button"
                onClick={() => {
                  setActiveCat(key);
                  setTouchedSlug(null);
                }}
                aria-pressed={active}
                className={`relative rounded-full px-4 py-2 text-[13px] font-semibold transition-colors cursor-pointer ${
                  active
                    ? 'text-[var(--on-accent)]'
                    : 'border border-[var(--line)] bg-[var(--surface)] text-[var(--muted)] hover:border-[var(--line-strong)] hover:text-[var(--text)]'
                }`}
              >
                {active && (
                  <motion.span
                    layoutId="work-category-pill"
                    transition={{ type: 'spring', stiffness: 420, damping: 32 }}
                    className="absolute inset-0 -z-0 rounded-full bg-[var(--accent)]"
                  />
                )}
                <span className="relative z-10">{label}</span>
              </button>
            );
          })}
        </div>

        {/* 3-to-4 Column Desktop Showcase Grid */}
        <motion.div
          layout
          className="mt-10 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4"
        >
          <AnimatePresence mode="popLayout">
            {filtered.map((project) => {
              const showLive = hasValidLiveUrl(project.live_url);
              const isRevealed = touchedSlug === project.slug;

              return (
                <motion.article
                  layout
                  key={project.slug}
                  initial={{ opacity: 0, scale: 0.96, y: 10 }}
                  animate={{ opacity: 1, scale: 1, y: 0 }}
                  exit={{ opacity: 0, scale: 0.96 }}
                  transition={{ duration: 0.26, ease: [0.16, 1, 0.3, 1] }}
                  onClick={() => setTouchedSlug((prev) => (prev === project.slug ? null : project.slug))}
                  className="group relative aspect-[4/3] min-h-[300px] overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[#0b0e16] transition-[transform,border-color,box-shadow] duration-300 hover:-translate-y-1 hover:border-cyan-400/55 hover:shadow-[0_22px_54px_-16px_rgba(0,210,238,0.28)]"
                >
                  {/* Clean Full-Bleed Screenshot */}
                  {project.image_resolved ? (
                    <img
                      src={project.image_resolved}
                      alt={project.title}
                      loading="lazy"
                      className="absolute inset-0 h-full w-full object-cover object-top transition-transform duration-500 ease-out group-hover:scale-[1.04]"
                    />
                  ) : (
                    <div className="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-[#0d1019] text-slate-400">
                      <Monitor className="h-8 w-8" />
                      <span className="text-sm font-medium">{project.title}</span>
                    </div>
                  )}

                  {/* Top-Left Always-Visible macOS 3 Traffic Light Dots */}
                  <div
                    aria-hidden="true"
                    className="absolute left-4 top-4 z-20 flex items-center gap-1.5 rounded-full border border-white/15 bg-[#080a10]/75 px-2.5 py-1.5 backdrop-blur-md"
                  >
                    <span className="h-2 w-2 rounded-full bg-[#ff5f56]" />
                    <span className="h-2 w-2 rounded-full bg-[#ffbd2e]" />
                    <span className="h-2 w-2 rounded-full bg-[#27c93f]" />
                  </div>

                  {/* Software Price Pill if applicable */}
                  {project.is_software && project.price && (
                    <span className="absolute right-4 top-4 z-20 rounded-full border border-emerald-400/30 bg-[#080a10]/80 px-2.5 py-1 font-mono text-[11px] font-semibold text-emerald-300 backdrop-blur-md tabular-nums">
                      {project.price}
                    </span>
                  )}

                  {/* Progressive Hover / Focus / Touch Frosted Overlay */}
                  <div
                    className={`absolute inset-0 z-10 flex flex-col justify-end bg-gradient-to-t from-[#06080f]/95 via-[#06080f]/78 to-[#06080f]/10 p-5 backdrop-blur-[3px] transition-[opacity,transform] duration-250 ease-out ${
                      isRevealed
                        ? 'opacity-100 translate-y-0'
                        : 'opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 group-focus-within:opacity-100 group-focus-within:translate-y-0'
                    }`}
                  >
                    {project.outcome_metric && (
                      <span className="mb-1.5 inline-flex w-fit rounded-full bg-cyan-400/15 px-2.5 py-0.5 font-mono text-[11px] font-semibold text-cyan-300">
                        {project.outcome_metric}
                      </span>
                    )}

                    <h3 className="font-display text-[17px] font-bold leading-snug text-white">
                      <Link
                        href={`/portfolio/${project.slug}`}
                        onClick={(e) => e.stopPropagation()}
                        className="hover:text-cyan-300"
                      >
                        {project.title}
                      </Link>
                    </h3>

                    {/* Category-Aware Action Buttons */}
                    <div className="mt-4 flex flex-wrap items-center gap-2">
                      {project.category === 'web-dev' && showLive ? (
                        <a
                          href={project.live_url!}
                          target="_blank"
                          rel="noopener noreferrer"
                          onClick={(e) => e.stopPropagation()}
                          className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full bg-[#00D2EE] px-3.5 py-2 font-display text-xs font-bold text-[#041021] transition-transform hover:brightness-110 active:scale-95"
                        >
                          <ExternalLink className="h-3.5 w-3.5" />
                          <span>Live Site</span>
                        </a>
                      ) : (
                        <button
                          type="button"
                          onClick={(e) => {
                            e.stopPropagation();
                            setLightboxProject(project);
                          }}
                          className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full bg-[#00D2EE] px-3.5 py-2 font-display text-xs font-bold text-[#041021] transition-transform hover:brightness-110 active:scale-95 cursor-pointer"
                        >
                          <Eye className="h-3.5 w-3.5" />
                          <span>Preview</span>
                        </button>
                      )}

                      {project.category === 'web-dev' && showLive && (
                        <button
                          type="button"
                          onClick={(e) => {
                            e.stopPropagation();
                            setLightboxProject(project);
                          }}
                          aria-label={`Quick preview ${project.title}`}
                          className="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 p-2 text-white transition-colors hover:bg-white/20 active:scale-95 cursor-pointer"
                        >
                          <Eye className="h-3.5 w-3.5" />
                        </button>
                      )}

                      {project.is_software && project.gumroad_url && (
                        <a
                          href={project.gumroad_url}
                          target="_blank"
                          rel="noopener noreferrer"
                          onClick={(e) => e.stopPropagation()}
                          aria-label={`Buy ${project.title} license`}
                          className="inline-flex items-center justify-center rounded-full border border-emerald-400/35 bg-emerald-500/20 p-2 text-emerald-300 transition-colors hover:bg-emerald-500/30 active:scale-95"
                        >
                          <ShoppingBag className="h-3.5 w-3.5" />
                        </a>
                      )}

                      <Link
                        href={`/portfolio/${project.slug}`}
                        onClick={(e) => e.stopPropagation()}
                        className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-3.5 py-2 font-display text-xs font-semibold text-white transition-colors hover:bg-white/20 active:scale-95"
                      >
                        <span>Details</span>
                        <ArrowRight className="h-3.5 w-3.5" />
                      </Link>
                    </div>
                  </div>
                </motion.article>
              );
            })}
          </AnimatePresence>
        </motion.div>

        {filtered.length === 0 && (
          <div className="mt-10 rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-10 text-center text-sm text-[var(--muted)]">
            New case studies for this discipline are being prepared.{' '}
            <Link href="/contact" className="font-semibold text-[var(--accent)] hover:underline">
              Request private samples →
            </Link>
          </div>
        )}

        {showViewAllButton && (
          <div className="mt-10 text-center">
            <Link href="/portfolio" className="btn-ghost">
              <span>Explore full portfolio archive</span>
              <ArrowRight className="h-4 w-4" />
            </Link>
          </div>
        )}
      </div>

      <PortfolioLightbox project={lightboxProject} onClose={() => setLightboxProject(null)} />
    </section>
  );
}
