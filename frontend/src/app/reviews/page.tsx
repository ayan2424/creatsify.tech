import React from 'react';
import type { Metadata } from 'next';
import { getReviewsData } from '@/lib/api';
import { ReviewsClient } from '@/components/reviews/ReviewsClient';

export const revalidate = 30;

export const metadata: Metadata = {
  title: 'Client Reviews & Verified Testimonials',
  description:
    'Read verified 4.9/5 client reviews and submit feedback for Creatsify across web apps, Flutter mobile apps, SaaS, branding, and SEO/ads.',
  alternates: { canonical: '/reviews' },
};

export default async function ReviewsPage() {
  const data = await getReviewsData('all');

  return (
    <ReviewsClient
      initialReviews={data.reviews}
      categories={data.categories}
      stats={data.stats}
    />
  );
}
