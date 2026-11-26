import React from 'react';
import type { Metadata } from 'next';
import { getProjectsData } from '@/lib/api';
import { WorkShowcaseSection } from '@/components/home/WorkShowcaseSection';

export const revalidate = 60;

export const metadata: Metadata = {
  title: 'Our Work & Case Studies',
  description:
    'Recent websites, Flutter mobile apps, SaaS platforms, brand systems, and growth campaigns delivered by Creatsify Digital Agency.',
  alternates: { canonical: '/portfolio' },
};

export default async function PortfolioPage({
  searchParams,
}: {
  searchParams: Promise<{ cat?: string }>;
}) {
  const sp = await searchParams;
  const requestedCat = sp.cat && sp.cat !== 'all' ? sp.cat : 'web-dev';
  const data = await getProjectsData();

  return (
    <div className="pt-6">
      <WorkShowcaseSection
        projects={data.projects}
        categories={data.categories}
        initialCategory={requestedCat}
        showViewAllButton={false}
        heading="Our Work & Case Studies"
        subcopy="Explore real websites, Flutter apps, SaaS architectures, brand identities, and commercial platforms built by our senior team."
      />
    </div>
  );
}
