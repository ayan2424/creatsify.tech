'use client';

import React, { useEffect } from 'react';
import Link from 'next/link';
import { AnimatePresence, motion } from 'motion/react';
import { ArrowRight, ExternalLink, ShoppingBag, X } from 'lucide-react';
import type { ProjectItem } from '@/types/creatsify';
import { hasValidLiveUrl } from '@/lib/utils';

interface PortfolioLightboxProps {
  project: ProjectItem | null;
  onClose: () => void;
}

export function PortfolioLightbox({ project, onClose }: PortfolioLightboxProps) {
  useEffect(() => {
    if (!project) return;
    const onKeyDown = (e: KeyboardEvent) => {
      if (e.key === 'Escape') onClose();
    };
    document.body.style.overflow = 'hidden';
    window.addEventListener('keydown', onKeyDown);
    return () => {
      document.body.style.overflow = '';
      window.removeEventListener('keydown', onKeyDown);
    };
  }, [project, onClose]);

  const showLive = project ? hasValidLiveUrl(project.live_url) : false;

  return (
    <AnimatePresence>
      {project && (
        <div
          className="fixed inset-0 z-[90] flex items-center justify-center p-4 sm:p-6"
          role="dialog"
          aria-modal="true"
          aria-label={`${project.title} Preview`}
        >
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.2 }}
            onClick={onClose}
            className="absolute inset-0 bg-black/80 backdrop-blur-md"
          />

          <motion.div
            initial={{ opacity: 0, scale: 0.96, y: 12 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.96, y: 12 }}
            transition={{ duration: 0.24, ease: [0.16, 1, 0.3, 1] }}
            className="relative z-10 flex max-h-[90vh] w-full max-w-[920px] flex-col overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[#0b0e16] text-slate-50 shadow-[0_32px_90px_-20px_rgba(0,0,0,0.85)]"
          >
            <div className="flex items-center justify-between gap-4 border-b border-white/10 px-5 py-4 sm:px-6">
              <div className="flex min-w-0 flex-wrap items-center gap-2.5">
                <span className="rounded-full bg-cyan-400/15 px-3 py-1 text-[11px] font-semibold text-cyan-300">
                  {project.subcategory || project.category_label}
                </span>
                {project.is_software && (
                  <span className="rounded-full bg-emerald-500/15 px-3 py-1 font-mono text-[11px] font-semibold text-emerald-300 tabular-nums">
                    Ready-Made Software {project.price ? `· ${project.price}` : ''}
                  </span>
                )}
                <h3 className="truncate font-display text-base font-bold text-white sm:text-lg">
                  {project.title}
                </h3>
              </div>

              <button
                type="button"
                onClick={onClose}
                aria-label="Close preview"
                className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-white/15 bg-white/5 text-white transition-colors hover:bg-white/15 active:scale-95 cursor-pointer"
              >
                <X className="h-4 w-4" />
              </button>
            </div>

            <div className="flex-1 overflow-y-auto p-5 sm:p-6">
              <div className="overflow-hidden rounded-xl border border-white/10 bg-[#07090f]">
                {project.image_resolved ? (
                  <img
                    src={project.image_resolved}
                    alt={project.title}
                    className="max-h-[52vh] w-full object-cover object-top"
                  />
                ) : (
                  <div className="flex aspect-video items-center justify-center text-sm text-slate-400">
                    {project.title}
                  </div>
                )}
              </div>

              {project.summary && (
                <p className="mt-5 text-sm leading-relaxed text-slate-300">
                  {project.summary}
                </p>
              )}

              {project.tech_list.length > 0 && (
                <div className="mt-4 flex flex-wrap items-center gap-2">
                  <span className="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Stack:
                  </span>
                  {project.tech_list.map((tech) => (
                    <span
                      key={tech}
                      className="rounded-md border border-white/10 bg-white/5 px-2.5 py-1 text-xs font-medium text-slate-200"
                    >
                      {tech}
                    </span>
                  ))}
                </div>
              )}
            </div>

            <div className="flex flex-col items-start justify-between gap-3 border-t border-white/10 bg-[#080b12] px-5 py-4 sm:flex-row sm:items-center sm:px-6">
              <span className="text-xs font-medium text-slate-400">
                {project.client_name ? `Client: ${project.client_name}` : project.outcome_metric || 'Verified Creatsify Delivery'}
              </span>

              <div className="flex w-full flex-wrap items-center gap-2.5 sm:w-auto">
                {showLive && (
                  <a
                    href={project.live_url!}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="btn-brand flex-1 !py-2.5 !text-xs sm:flex-initial"
                  >
                    <ExternalLink className="h-3.5 w-3.5" />
                    <span>Live Preview</span>
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
                    className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full bg-emerald-500 px-4 py-2.5 font-display text-xs font-bold text-[#041021] transition-transform hover:brightness-110 active:scale-95 sm:flex-initial"
                  >
                    <ShoppingBag className="h-3.5 w-3.5" />
                    <span>Buy License {project.price ? `(${project.price})` : ''}</span>
                  </a>
                )}

                <Link
                  href={`/portfolio/${project.slug}`}
                  onClick={onClose}
                  className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-4 py-2.5 font-display text-xs font-semibold text-white transition-colors hover:bg-white/15 active:scale-95 sm:flex-initial"
                >
                  <span>More Details</span>
                  <ArrowRight className="h-3.5 w-3.5" />
                </Link>
              </div>
            </div>
          </motion.div>
        </div>
      )}
    </AnimatePresence>
  );
}
