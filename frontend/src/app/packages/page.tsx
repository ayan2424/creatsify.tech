import React from 'react';
import type { Metadata } from 'next';
import { getPackagesData } from '@/lib/api';
import { PackagesAndEstimatorSection } from '@/components/home/PackagesAndEstimatorSection';

export const revalidate = 60;

export const metadata: Metadata = {
  title: 'Packages & Transparent Pricing',
  description:
    'Transparent sprint pricing across web, mobile app, SaaS engineering, branding, UI/UX redesign, and SEO/paid ads — plus our live multi-service bundle calculator.',
  alternates: { canonical: '/packages' },
};

export default async function PackagesPage() {
  const data = await getPackagesData();

  return (
    <div className="pt-6">
      <PackagesAndEstimatorSection
        packagesByTrack={data.packages_by_track}
        showEstimator={true}
        showCompareButton={false}
      />
    </div>
  );
}
