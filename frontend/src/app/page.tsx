import React from 'react';
import type { Metadata } from 'next';
import { getHomeData } from '@/lib/api';
import { HomeClient } from '@/components/home/HomeClient';

export const revalidate = 60;

export const metadata: Metadata = {
  title: 'Creatsify | Web, Mobile & SaaS Development Agency',
  description:
    'Custom websites, Flutter mobile apps, SaaS platforms, branding, SEO, and ready-made commercial software from one senior team — fixed milestones and 100% source code ownership.',
  alternates: { canonical: '/' },
};

export default async function HomePage() {
  const data = await getHomeData();

  const jsonLd = {
    '@context': 'https://schema.org',
    '@type': 'ProfessionalService',
    name: `${data.site.name} Digital Agency`,
    url: 'https://creatsify.tech/',
    logo: 'https://creatsify.tech/assets/brand/creatsify-logo-dark.png',
    description: data.site.meta_description,
    priceRange: '$$',
    ...(data.review_stats.total > 0
      ? {
          aggregateRating: {
            '@type': 'AggregateRating',
            ratingValue: String(data.review_stats.avg_rating),
            bestRating: '5',
            worstRating: '1',
            reviewCount: String(data.review_stats.total),
          },
        }
      : {}),
  };

  return (
    <>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
      />
      <HomeClient data={data} />
    </>
  );
}
