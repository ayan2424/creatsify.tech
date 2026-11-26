import React from 'react';
import type { Metadata } from 'next';
import { getBootstrap, getPackagesData } from '@/lib/api';
import { ContactSection } from '@/components/home/ContactSection';

export const revalidate = 60;

export const metadata: Metadata = {
  title: 'Contact Us — Start Your Project',
  description:
    'Start a custom web, Flutter mobile app, SaaS, branding, or SEO engagement — or request customization for a Creatsify platform. Replies within 24 hours.',
  alternates: { canonical: '/contact' },
};

export default async function ContactPage({
  searchParams,
}: {
  searchParams: Promise<{ offering?: string; service?: string; project?: string }>;
}) {
  const [sp, boot, pkgData] = await Promise.all([
    searchParams,
    getBootstrap(),
    getPackagesData(),
  ]);
  const offering = sp.offering || sp.service || sp.project || '';

  return (
    <div id="inquiry-form" className="pt-4">
      <ContactSection
        whatsappNumber={boot.site.whatsapp_number}
        packagesByTrack={pkgData.packages_by_track}
        preselectedOffering={offering}
      />
    </div>
  );
}
