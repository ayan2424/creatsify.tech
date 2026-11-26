import React from 'react';
import type { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'Privacy Policy & Commercial Terms',
  description:
    'How Creatsify handles client data, project confidentiality, source code IP ownership, and direct Gumroad software licensing.',
  alternates: { canonical: '/privacy' },
};

export default function PrivacyPage() {
  return (
    <article className="mx-auto max-w-[780px] px-5 py-14 sm:px-6 lg:py-20">
      <h1 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-4xl">
        Privacy Policy &amp; Commercial Terms
      </h1>
      <p className="mt-3 text-sm text-[var(--muted)]">
        Effective Date: {new Date().getFullYear()} · Creatsify Digital Agency
      </p>

      <div className="mt-10 space-y-8 text-[15px] leading-[1.78] text-[var(--muted)]">
        <section>
          <h2 className="font-display text-xl font-bold text-[var(--text)]">
            1. Information We Collect
          </h2>
          <p className="mt-2">
            When you submit a project inquiry, open a support ticket, leave a review, or message our live chat desk, we collect only the contact details and project specifications you explicitly submit. We never sell or rent client contact data to third parties.
          </p>
        </section>

        <section>
          <h2 className="font-display text-xl font-bold text-[var(--text)]">
            2. Source Code &amp; Intellectual Property Ownership
          </h2>
          <p className="mt-2">
            For all custom agency engagements, upon completion of agreed milestones, 100% of custom-written source code, databases, and design assets transfer exclusively to the client with full commercial rights.
          </p>
        </section>

        <section>
          <h2 className="font-display text-xl font-bold text-[var(--text)]">
            3. Direct Gumroad Commercial Software Licenses
          </h2>
          <p className="mt-2">
            Creatsify ready-made software platforms are licensed directly through our Official Gumroad Store. Each commercial license grants full white-label deployment rights for your business with first-party technical support through our Support Desk at /support.
          </p>
        </section>

        <section>
          <h2 className="font-display text-xl font-bold text-[var(--text)]">
            4. Data Deletion &amp; Contact
          </h2>
          <p className="mt-2">
            You may request export or deletion of your inquiry or support records at any time by emailing hello@creatsify.tech.
          </p>
        </section>
      </div>
    </article>
  );
}
