import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';
import { ArrowRight, Calendar } from 'lucide-react';
import { getPostsData } from '@/lib/api';

export const revalidate = 60;

export const metadata: Metadata = {
  title: 'Engineering & Growth Insights Blog',
  description:
    'Practical guides on PHP 8.4 architecture, Next.js SEO, Flutter mobile performance, and digital conversion from the Creatsify team.',
  alternates: { canonical: '/blog' },
};

export default async function BlogIndexPage() {
  const posts = await getPostsData();

  return (
    <div className="mx-auto max-w-[1160px] px-5 py-14 sm:px-6 lg:py-20">
      <header className="max-w-2xl">
        <h1 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-5xl">
          Engineering &amp; Growth Notes
        </h1>
        <p className="mt-4 text-base leading-relaxed text-[var(--muted)]">
          Field notes from our builds across custom web platforms, Flutter apps, technical SEO, and white-label software engineering.
        </p>
      </header>

      {posts.length === 0 ? (
        <div className="mt-12 rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-10 text-center">
          <p className="text-sm text-[var(--muted)]">
            New engineering articles are being prepared in the studio desk.
          </p>
          <Link href="/portfolio" className="btn-brand mt-5">
            <span>Explore Case Studies</span>
            <ArrowRight className="h-4 w-4" />
          </Link>
        </div>
      ) : (
        <div className="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          {posts.map((post) => (
            <Link
              key={post.slug}
              href={`/blog/${post.slug}`}
              className="group flex flex-col justify-between overflow-hidden rounded-2xl border border-[var(--line)] bg-[var(--surface)] transition-colors hover:border-[var(--line-strong)]"
            >
              <div>
                {post.featured_image && (
                  <div className="aspect-[16/10] overflow-hidden bg-[var(--bg-subtle)]">
                    <img
                      src={post.featured_image}
                      alt={post.title}
                      loading="lazy"
                      className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                  </div>
                )}
                <div className="p-6">
                  {post.category && (
                    <span className="text-xs font-semibold text-[var(--accent)]">
                      {post.category}
                    </span>
                  )}
                  <h2 className="mt-2 font-display text-lg font-bold text-[var(--text)] group-hover:text-[var(--accent)]">
                    {post.title}
                  </h2>
                  {post.excerpt && (
                    <p className="mt-2 line-clamp-3 text-sm leading-relaxed text-[var(--muted)]">
                      {post.excerpt}
                    </p>
                  )}
                </div>
              </div>
              <div className="flex items-center justify-between border-t border-[var(--line)] px-6 py-4 text-xs text-[var(--muted)]">
                <span className="inline-flex items-center gap-1.5">
                  <Calendar className="h-3.5 w-3.5" />
                  {post.published_at ? new Date(post.published_at).toLocaleDateString() : 'Recent'}
                </span>
                <span className="font-semibold text-[var(--accent)]">Read →</span>
              </div>
            </Link>
          ))}
        </div>
      )}
    </div>
  );
}
