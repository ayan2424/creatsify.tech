'use client';

import React from 'react';
import Link from 'next/link';
import { ArrowUpRight, Check, ExternalLink, ShoppingBag, Sliders } from 'lucide-react';
import type { ProjectItem } from '@/types/creatsify';
import { hasValidLiveUrl } from '@/lib/utils';

interface ReadyMadeSoftwareProps {
  products: ProjectItem[];
  onSelectOffering?: (offering: string) => void;
  showBrowseAll?: boolean;
}

export function ReadyMadeSoftwareSection({
  products,
  onSelectOffering,
  showBrowseAll = true,
}: ReadyMadeSoftwareProps) {
  if (products.length === 0) return null;

  return (
    <section
      id="products"
      className="relative overflow-hidden border-y border-white/10 bg-[#07090f] py-20 text-slate-100 lg:py-28"
    >
      <div className="mx-auto max-w-[1220px] px-5 sm:px-6">
        <div className="max-w-2xl">
          <h2 className="font-display text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
            Launch in days with a ready-made commercial platform
          </h2>
          <p className="mt-3 text-[15.5px] leading-relaxed text-slate-400">
            License our tested white-label software directly on Gumroad with 100% unlocked source code and first-party support — or have our senior engineers tailor it to your exact business workflow.
          </p>
        </div>

        <div className="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          {products.map((prod) => {
            const showLive = hasValidLiveUrl(prod.live_url);
            return (
              <article
                key={prod.slug}
                className="flex flex-col overflow-hidden rounded-2xl border border-white/10 bg-[#0d111a] transition-[border-color,transform] duration-200 hover:-translate-y-1 hover:border-cyan-400/40"
              >
                {/* Frame with macOS Bar */}
                <div className="border-b border-white/10 bg-[#090c14]">
                  <div className="flex items-center justify-between px-4 py-2.5">
                    <div className="flex items-center gap-1.5" aria-hidden="true">
                      <span className="h-2 w-2 rounded-full bg-[#ff5f56]" />
                      <span className="h-2 w-2 rounded-full bg-[#ffbd2e]" />
                      <span className="h-2 w-2 rounded-full bg-[#27c93f]" />
                    </div>
                    <span className="font-mono text-[11px] font-semibold text-cyan-300 tabular-nums">
                      {prod.price ? `Direct: ${prod.price} · Or Customize` : 'Direct License'}
                    </span>
                  </div>
                  <div className="aspect-[16/10] w-full overflow-hidden bg-[#06080f]">
                    {prod.image_resolved ? (
                      <img
                        src={prod.image_resolved}
                        alt={prod.title}
                        loading="lazy"
                        className="h-full w-full object-cover object-top"
                      />
                    ) : (
                      <div className="flex h-full items-center justify-center text-sm text-slate-400">
                        {prod.title}
                      </div>
                    )}
                  </div>
                </div>

                <div className="flex flex-1 flex-col p-6">
                  <div className="flex items-center justify-between gap-2 text-xs">
                    <span className="rounded-full bg-white/8 px-2.5 py-1 font-medium text-slate-300">
                      {prod.subcategory || prod.category_label}
                    </span>
                    <span className="inline-flex items-center gap-1 font-medium text-emerald-400">
                      <Check className="h-3.5 w-3.5" />
                      Full source code
                    </span>
                  </div>

                  <h3 className="mt-4 font-display text-xl font-bold text-white">
                    <Link href={`/portfolio/${prod.slug}`} className="hover:text-cyan-300">
                      {prod.title}
                    </Link>
                  </h3>

                  <p className="mt-2 line-clamp-3 text-sm leading-relaxed text-slate-400">
                    {prod.summary}
                  </p>

                  {prod.tech_list.length > 0 && (
                    <div className="mt-4 flex flex-wrap gap-1.5">
                      {prod.tech_list.slice(0, 5).map((t) => (
                        <span
                          key={t}
                          className="rounded-md border border-white/10 bg-white/5 px-2 py-0.5 font-mono text-[11px] text-slate-300"
                        >
                          {t}
                        </span>
                      ))}
                    </div>
                  )}

                  <div className="mt-6 flex flex-wrap gap-2 pt-2">
                    {prod.gumroad_url && (
                      <a
                        href={prod.gumroad_url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full bg-[#00D2EE] px-4 py-2.5 font-display text-xs font-bold text-[#041021] transition-transform hover:brightness-110 active:scale-95"
                      >
                        <ShoppingBag className="h-3.5 w-3.5" />
                        <span>Buy License {prod.price ? `(${prod.price})` : ''}</span>
                      </a>
                    )}

                    {onSelectOffering ? (
                      <button
                        type="button"
                        onClick={() => onSelectOffering(`Customize ${prod.title} For My Business`)}
                        className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-3.5 py-2.5 font-display text-xs font-semibold text-white transition-colors hover:bg-white/15 active:scale-95 cursor-pointer"
                      >
                        <Sliders className="h-3.5 w-3.5" />
                        <span>Customize</span>
                      </button>
                    ) : (
                      <Link
                        href={`/contact?kind=software_presale&offering=${encodeURIComponent(
                          `Customize ${prod.title}`
                        )}`}
                        className="inline-flex flex-1 items-center justify-center gap-1.5 rounded-full border border-white/15 bg-white/5 px-3.5 py-2.5 font-display text-xs font-semibold text-white transition-colors hover:bg-white/15 active:scale-95"
                      >
                        <Sliders className="h-3.5 w-3.5" />
                        <span>Customize</span>
                      </Link>
                    )}

                    {showLive && (
                      <a
                        href={prod.live_url!}
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label={`Live demo of ${prod.title}`}
                        className="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/5 p-2.5 text-cyan-300 transition-colors hover:bg-white/15"
                      >
                        <ExternalLink className="h-3.5 w-3.5" />
                      </a>
                    )}
                  </div>
                </div>
              </article>
            );
          })}
        </div>

        {showBrowseAll && (
          <div className="mt-10 text-center">
            <Link
              href="/software"
              className="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-6 py-3 font-display text-sm font-semibold text-white transition-colors hover:border-cyan-400/50 hover:bg-white/10"
            >
              <span>Browse all commercial platforms</span>
              <ArrowUpRight className="h-4 w-4" />
            </Link>
          </div>
        )}
      </div>
    </section>
  );
}
