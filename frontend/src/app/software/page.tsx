import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';
import { LifeBuoy, ShieldCheck } from 'lucide-react';
import { getProjectsData } from '@/lib/api';
import { ReadyMadeSoftwareSection } from '@/components/home/ReadyMadeSoftwareSection';

export const revalidate = 60;

export const metadata: Metadata = {
  title: 'Ready-Made Commercial Software Platforms',
  description:
    'License battle-tested white-label courier, POS, and SaaS platforms directly from Creatsify on Gumroad with 100% unlocked source code and first-party support.',
  alternates: { canonical: '/software' },
};

export default async function SoftwarePage() {
  const data = await getProjectsData(undefined, true);

  return (
    <div>
      <section className="mx-auto max-w-[1220px] px-5 pb-10 pt-14 sm:px-6 lg:pt-20">
        <div className="max-w-3xl">
          <h1 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-5xl">
            Production-ready commercial platforms — built to own
          </h1>
          <p className="mt-4 text-base leading-relaxed text-[var(--muted)] sm:text-lg">
            Skip months of foundational development. Purchase a direct white-label license through our Official Gumroad Store with 100% unlocked source code, or commission our engineers to rebrand and customize any platform for your market.
          </p>
          <div className="mt-6 flex flex-wrap gap-3">
            <Link href="/support" className="btn-ghost">
              <LifeBuoy className="h-4 w-4 text-[var(--accent)]" />
              <span>Buyer Support Portal</span>
            </Link>
            <Link
              href="/contact?kind=software_presale&offering=Customize+Ready-Made+Software"
              className="btn-brand"
            >
              <ShieldCheck className="h-4 w-4" />
              <span>Request Custom Tailoring</span>
            </Link>
          </div>
        </div>
      </section>

      <ReadyMadeSoftwareSection products={data.software} showBrowseAll={false} />
    </div>
  );
}
