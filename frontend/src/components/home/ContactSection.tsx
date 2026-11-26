'use client';

import React, { useEffect, useState } from 'react';
import { ArrowUpRight, CheckCircle2, ChevronDown, Loader2, MessageCircle } from 'lucide-react';
import type { PackageItem } from '@/types/creatsify';
import { fetchClientCsrfToken } from '@/lib/api';
import { whatsappLink } from '@/lib/utils';

interface ContactSectionProps {
  whatsappNumber: string;
  packagesByTrack: Record<string, PackageItem[]>;
  preselectedOffering?: string;
  preselectedMessage?: string;
}

const FAQS = [
  {
    q: 'Do I own the source code and brand files?',
    a: 'Yes. Every custom website, mobile app, and brand system comes with 100% unlocked source code and commercial IP rights upon handover.',
  },
  {
    q: 'Can you customize your ready-made platforms for our brand?',
    a: 'Yes. License any platform directly via Gumroad or ask our engineering team to rebrand, add custom modules, and integrate local payment or shipping gateways.',
  },
  {
    q: 'How do milestone payments work for custom builds?',
    a: 'We break projects into clear weekly sprints. You review real progress on a live staging server at each milestone before approving the next stage.',
  },
];

export function ContactSection({
  whatsappNumber,
  packagesByTrack,
  preselectedOffering = '',
  preselectedMessage = '',
}: ContactSectionProps) {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [projectType, setProjectType] = useState(preselectedOffering || 'Custom Web Development');
  const [message, setMessage] = useState(preselectedMessage || '');
  const [honeypot, setHoneypot] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [status, setStatus] = useState<{ type: 'success' | 'error'; text: string } | null>(null);
  const [openFaq, setOpenFaq] = useState<number>(0);

  useEffect(() => {
    if (preselectedOffering) setProjectType(preselectedOffering);
  }, [preselectedOffering]);

  useEffect(() => {
    if (preselectedMessage) setMessage(preselectedMessage);
  }, [preselectedMessage]);

  const allPackageOptions = Object.values(packagesByTrack)
    .flat()
    .map((p) => `${p.title} (${p.price_label})`);

  const waHref = whatsappLink(
    whatsappNumber,
    `Hello Creatsify! I would like to discuss: ${projectType}.`
  );

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (submitting) return;
    setSubmitting(true);
    setStatus(null);

    try {
      const csrf = await fetchClientCsrfToken();
      const res = await fetch('/api/contact', {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-Token': csrf,
        },
        body: JSON.stringify({
          _csrf_token: csrf,
          _hp_check: honeypot,
          name,
          email,
          phone,
          project_type: projectType,
          inquiry_kind: projectType.toLowerCase().includes('license')
            ? 'software_presale'
            : 'custom_service',
          message,
        }),
      });
      const json = await res.json();
      if (res.ok && json?.success) {
        setStatus({
          type: 'success',
          text: json.message || 'Proposal brief received! Our team will reply within 24 hours.',
        });
        setName('');
        setEmail('');
        setPhone('');
        setMessage('');
      } else {
        setStatus({
          type: 'error',
          text: json?.error || 'Could not submit your inquiry. Please verify your fields and retry.',
        });
      }
    } catch {
      setStatus({
        type: 'error',
        text: 'Network error submitting brief. You can also reach us immediately via WhatsApp.',
      });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <section id="contact" className="py-20 lg:py-28">
      <div className="mx-auto grid max-w-[1220px] gap-10 px-5 sm:px-6 lg:grid-cols-12">
        {/* Left: WhatsApp & FAQs */}
        <div className="lg:col-span-5">
          <h2 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-4xl">
            Tell us what you&apos;d like to build
          </h2>
          <p className="mt-3 text-[15.5px] leading-relaxed text-[var(--muted)]">
            Choose a package, request customization on one of our ready-made platforms, or book a free website &amp; SEO teardown.
          </p>

          <div className="mt-7 rounded-2xl border border-emerald-500/30 bg-[var(--surface)] p-5">
            <div className="flex items-center justify-between gap-3">
              <div className="flex items-center gap-3">
                <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-500">
                  <MessageCircle className="h-5 w-5" />
                </div>
                <div>
                  <strong className="block font-display text-sm font-bold text-[var(--text)]">
                    Direct WhatsApp Desk
                  </strong>
                  <span className="text-xs text-[var(--muted)]">
                    Talk straight with senior engineers
                  </span>
                </div>
              </div>
              <span className="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/15 px-2.5 py-1 text-[11px] font-semibold text-emerald-500">
                <span className="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                Quick replies
              </span>
            </div>

            <a
              href={waHref}
              target="_blank"
              rel="noopener noreferrer"
              className="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3 font-display text-sm font-bold text-[#041021] transition-transform hover:brightness-110 active:scale-[0.98]"
            >
              <MessageCircle className="h-4 w-4" />
              <span>Message us on WhatsApp</span>
            </a>
          </div>

          {/* FAQ Accordion */}
          <div className="mt-7 space-y-2.5">
            {FAQS.map((faq, idx) => {
              const isOpen = openFaq === idx;
              return (
                <div
                  key={faq.q}
                  className="overflow-hidden rounded-xl border border-[var(--line)] bg-[var(--surface)]"
                >
                  <button
                    type="button"
                    onClick={() => setOpenFaq(isOpen ? -1 : idx)}
                    aria-expanded={isOpen}
                    className="flex w-full items-center justify-between gap-3 px-4 py-3.5 text-left text-sm font-semibold text-[var(--text)] cursor-pointer"
                  >
                    <span>{faq.q}</span>
                    <ChevronDown
                      className={`h-4 w-4 shrink-0 text-[var(--muted)] transition-transform duration-200 ${
                        isOpen ? 'rotate-180' : ''
                      }`}
                    />
                  </button>
                  {isOpen && (
                    <div className="border-t border-[var(--line)] px-4 py-3 text-sm leading-relaxed text-[var(--muted)]">
                      {faq.a}
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>

        {/* Right: Project Brief Form */}
        <form
          onSubmit={handleSubmit}
          className="rounded-2xl border border-[var(--line-strong)] bg-[var(--surface)] p-6 sm:p-8 lg:col-span-7"
        >
          <input
            type="text"
            name="_hp_check"
            value={honeypot}
            onChange={(e) => setHoneypot(e.target.value)}
            tabIndex={-1}
            autoComplete="off"
            aria-hidden="true"
            className="hidden"
          />

          <div className="border-b border-[var(--line)] pb-4">
            <strong className="font-display text-lg font-bold text-[var(--text)]">
              Request a proposal
            </strong>
            <p className="mt-1 text-xs text-[var(--muted)]">
              We reply within 24 hours · Your project details stay strictly confidential
            </p>
          </div>

          {status && (
            <div
              role="status"
              className={`mt-5 flex items-start gap-2.5 rounded-xl border p-4 text-sm ${
                status.type === 'success'
                  ? 'border-emerald-500/35 bg-emerald-500/10 text-emerald-500'
                  : 'border-rose-500/35 bg-rose-500/10 text-rose-400'
              }`}
            >
              {status.type === 'success' && <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0" />}
              <span>{status.text}</span>
            </div>
          )}

          <div className="mt-5 grid gap-4 sm:grid-cols-2">
            <label className="block text-xs font-semibold text-[var(--text)]">
              Your name
              <input
                type="text"
                required
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="John Doe"
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] placeholder:text-[var(--muted)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>

            <label className="block text-xs font-semibold text-[var(--text)]">
              Your email
              <input
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="john@company.com"
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] placeholder:text-[var(--muted)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
          </div>

          <div className="mt-4 grid gap-4 sm:grid-cols-2">
            <label className="block text-xs font-semibold text-[var(--text)]">
              Phone or WhatsApp (optional)
              <input
                type="tel"
                value={phone}
                onChange={(e) => setPhone(e.target.value)}
                placeholder="+1 555 123 4567"
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] placeholder:text-[var(--muted)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>

            <label className="block text-xs font-semibold text-[var(--text)]">
              Service or Package Needed
              <select
                value={projectType}
                onChange={(e) => setProjectType(e.target.value)}
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              >
                <optgroup label="Agency Services">
                  <option value="Custom Web Development">Custom web development</option>
                  <option value="Mobile App Development (Flutter & Dart)">Mobile app (Flutter iOS &amp; Android)</option>
                  <option value="Website Redesign & UI/UX Modernization">Website redesign</option>
                  <option value="SaaS Products & Custom Portals">SaaS product or portal</option>
                  <option value="Search Engine Optimization (SEO)">Technical SEO</option>
                  <option value="Social Media Marketing & Paid Ads">Social media &amp; paid ads</option>
                  <option value="Branding, Logo & Graphic Design">Branding &amp; logo design</option>
                  <option value="E-Commerce & Custom Store Systems">E-commerce &amp; POS</option>
                </optgroup>
                {allPackageOptions.length > 0 && (
                  <optgroup label="Service Packages">
                    {allPackageOptions.map((opt) => (
                      <option key={opt} value={opt}>
                        {opt}
                      </option>
                    ))}
                  </optgroup>
                )}
                <optgroup label="Ready-Made Platforms">
                  <option value="Customize Ready-Made Software For My Business">
                    Customize a ready-made platform
                  </option>
                  <option value="Cheetah Enterprise License ($99)">Cheetah Enterprise License ($99)</option>
                  <option value="Cheetah Lite License ($49)">Cheetah Lite License ($49)</option>
                  <option value="Cheezify Food & POS License ($59)">Cheezify Food &amp; POS License ($59)</option>
                </optgroup>
              </select>
            </label>
          </div>

          <label className="mt-4 block text-xs font-semibold text-[var(--text)]">
            Project details &amp; goals
            <textarea
              rows={4}
              required
              value={message}
              onChange={(e) => setMessage(e.target.value)}
              placeholder="Tell us about your project, goals, target timeline, or any specific requirements..."
              className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] placeholder:text-[var(--muted)] focus:border-[var(--accent)] focus:outline-none"
            />
          </label>

          <button type="submit" disabled={submitting} className="btn-brand mt-5 w-full disabled:opacity-50">
            {submitting ? (
              <>
                <Loader2 className="h-4 w-4 animate-spin" />
                <span>Sending brief...</span>
              </>
            ) : (
              <>
                <span>Send your brief</span>
                <ArrowUpRight className="h-4 w-4" />
              </>
            )}
          </button>
        </form>
      </div>
    </section>
  );
}
