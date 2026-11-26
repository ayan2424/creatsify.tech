import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { ArrowLeft, Calendar, User } from 'lucide-react';
import { getPostBySlug } from '@/lib/api';

export const revalidate = 60;

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  const post = await getPostBySlug(slug);
  if (!post) return { title: 'Article Not Found' };
  return {
    title: post.title,
    description: post.excerpt || post.title,
    alternates: { canonical: `/blog/${post.slug}` },
  };
}

export default async function BlogDetailPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const post = await getPostBySlug(slug);
  if (!post) notFound();

  return (
    <article className="mx-auto max-w-[780px] px-5 py-14 sm:px-6 lg:py-20">
      <Link
        href="/blog"
        className="inline-flex items-center gap-2 rounded-full border border-[var(--line)] bg-[var(--surface)] px-4 py-2 text-xs font-semibold text-[var(--muted)] hover:text-[var(--text)]"
      >
        <ArrowLeft className="h-3.5 w-3.5" />
        <span>Back to Blog</span>
      </Link>

      <header className="mt-7">
        {post.category && (
          <span className="rounded-full bg-[var(--accent-soft)] px-3 py-1 text-xs font-semibold text-[var(--accent)]">
            {post.category}
          </span>
        )}
        <h1 className="mt-4 font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-4xl">
          {post.title}
        </h1>
        <div className="mt-4 flex flex-wrap items-center gap-4 text-xs text-[var(--muted)]">
          <span className="inline-flex items-center gap-1.5">
            <User className="h-3.5 w-3.5" />
            {post.author_name || 'Creatsify Engineering Team'}
          </span>
          {post.published_at && (
            <span className="inline-flex items-center gap-1.5">
              <Calendar className="h-3.5 w-3.5" />
              {new Date(post.published_at).toLocaleDateString()}
            </span>
          )}
        </div>
      </header>

      {post.featured_image && (
        <div className="mt-8 overflow-hidden rounded-2xl border border-[var(--line)]">
          <img
            src={post.featured_image}
            alt={post.title}
            className="w-full object-cover"
          />
        </div>
      )}

      <div className="mt-8 space-y-5 text-[15.5px] leading-[1.8] text-[var(--text)] whitespace-pre-line">
        {post.content || post.excerpt}
      </div>
    </article>
  );
}
