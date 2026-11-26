import React from 'react';
import type { Metadata } from 'next';
import { getProjectsData, getServicesData } from '@/lib/api';
import { SupportClient } from '@/components/support/SupportClient';

export const revalidate = 60;

export const metadata: Metadata = {
  title: 'Support Desk — Gumroad Buyers & Agency Clients',
  description:
    'First-party technical support portal for Creatsify commercial software licenses and ongoing agency builds.',
  alternates: { canonical: '/support' },
};

export default async function SupportPage({
  searchParams,
}: {
  searchParams: Promise<{ offering?: string; project?: string; script?: string }>;
}) {
  const [sp, projData, services] = await Promise.all([
    searchParams,
    getProjectsData(),
    getServicesData(),
  ]);
  const offering = sp.offering || sp.project || sp.script || '';

  return (
    <SupportClient
      projects={projData.projects}
      services={services}
      initialOffering={offering}
    />
  );
}
