'use client';

import React, { useState } from 'react';
import type { HomePayload } from '@/types/creatsify';
import { HeroSection } from './HeroSection';
import { ArsenalAndFactsSection } from './ArsenalAndFactsSection';
import { ServicesSection } from './ServicesSection';
import { WorkShowcaseSection } from './WorkShowcaseSection';
import { WhyCreatsifySection } from './WhyCreatsifySection';
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
    data.projects.length > 0 ? data.projects : data.portfolio_works;

  return (
    <>
      {/* Stage 1: Exact PHP Hero */}
      <HeroSection
        avgRating={data.review_stats.avg_rating || '4.9'}
        totalReviews={data.review_stats.total || 8}
        whatsappNumber={data.site.whatsapp_number}
      />

      {/* Stage 2: Exact PHP Arsenal Carousel & Facts Proof Bar */}
      <ArsenalAndFactsSection avgRating={data.review_stats.avg_rating || '4.9'} />

      {/* Stage 3: KEPT NEW — Four disciplines. One senior team. */}
      <ServicesSection
        services={data.services}
        onSelectOffering={(title) =>
          handleSelectOffering(
            title,
            `Hello Creatsify! I am interested in ${title}. Please share next steps.`
          )
        }
      />

      {/* Stage 4: KEPT NEW — Selected Work Card Design & Lightbox */}
      <WorkShowcaseSection
        projects={workProjects}
        categories={data.categories}
        initialCategory="web-dev"
      />

      {/* Stage 5: Exact PHP Why Creatsify + Comparison Table */}
      <WhyCreatsifySection />

      {/* Stage 6: Exact PHP Transparent Packages + Scope Estimator */}
      <PackagesAndEstimatorSection
        packagesByTrack={data.packages_by_track}
        onSelectOffering={handleSelectOffering}
        showEstimator={true}
        showCompareButton={true}
      />

      {/* Stage 7: Exact PHP Ready-Made Commercial Software Platforms (clean symmetrical 3-card row) */}
      <ReadyMadeSoftwareSection
        products={data.ready_made_products}
        onSelectOffering={handleSelectOffering}
        showBrowseAll={true}
        limit={3}
      />

      {/* Stage 8: Exact PHP 4-Step Agency Workflow */}
      <WorkflowSection />

      {/* Stage 9: Exact PHP Verified Client Reviews Dual-Row Marquee */}
      <ReviewsMarqueeSection reviews={data.reviews} stats={data.review_stats} />

      {/* Stage 10: Exact PHP Direct Consultation & WhatsApp Hub */}
      <ContactSection
        whatsappNumber={data.site.whatsapp_number}
        packagesByTrack={data.packages_by_track}
        preselectedOffering={selectedOffering}
        preselectedMessage={selectedNote}
      />
    </>
  );
}
