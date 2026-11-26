'use client';

import React, { useState } from 'react';
import type { HomePayload } from '@/types/creatsify';
import { HeroSection } from './HeroSection';
import { ArsenalAndProof } from './ArsenalAndProof';
import { ServicesSection } from './ServicesSection';
import { WorkShowcaseSection } from './WorkShowcaseSection';
import { WhyAndCompareSection } from './WhyAndCompareSection';
import { PackagesAndEstimatorSection } from './PackagesAndEstimatorSection';
import { ReadyMadeSoftwareSection } from './ReadyMadeSoftwareSection';
import { WorkflowSection } from './WorkflowSection';
import { ReviewsMarqueeSection } from './ReviewsMarqueeSection';
import { ContactSection } from './ContactSection';

export function HomeClient({ data }: { data: HomePayload }) {
  const [selectedOffering, setSelectedOffering] = useState<string>('');
  const [selectedNote, setSelectedNote] = useState<string>('');

  const handleSelectOffering = (offering: string, note?: string) => {
    setSelectedOffering(offering);
    if (note) setSelectedNote(note);
    const el = document.getElementById('contact');
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };

  const workProjects =
    data.portfolio_works.length > 0 ? data.portfolio_works : data.projects;

  return (
    <>
      <HeroSection
        avgRating={data.review_stats.avg_rating || '4.9'}
        totalReviews={data.review_stats.total || 8}
        whatsappNumber={data.site.whatsapp_number}
      />

      <ArsenalAndProof avgRating={data.review_stats.avg_rating || '4.9'} />

      <ServicesSection
        services={data.services}
        onSelectOffering={(title) => handleSelectOffering(title)}
      />

      <WorkShowcaseSection
        projects={workProjects}
        categories={data.categories}
        initialCategory="web-dev"
      />

      <WhyAndCompareSection />

      <PackagesAndEstimatorSection
        packagesByTrack={data.packages_by_track}
        onSelectOffering={handleSelectOffering}
      />

      <ReadyMadeSoftwareSection
        products={data.ready_made_products}
        onSelectOffering={(title) => handleSelectOffering(title)}
      />

      <WorkflowSection />

      <ReviewsMarqueeSection reviews={data.reviews} stats={data.review_stats} />

      <ContactSection
        whatsappNumber={data.site.whatsapp_number}
        packagesByTrack={data.packages_by_track}
        preselectedOffering={selectedOffering}
        preselectedMessage={selectedNote}
      />
    </>
  );
}
