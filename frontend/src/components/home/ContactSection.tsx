'use client';

import React, { useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion } from 'motion/react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useGSAP } from '@gsap/react';
import { Plus } from 'lucide-react';
import type { PackageItem } from '@/types/creatsify';
import { fetchClientCsrfToken } from '@/lib/api';
import { whatsappLink } from '@/lib/utils';
import { Magnetic } from '@/components/ui/Magnetic';

gsap.registerPlugin(ScrollTrigger, useGSAP);

interface ContactSectionProps {
  whatsappNumber: string;
  packagesByTrack: Record<string, PackageItem[]>;
  preselectedOffering?: string;
  preselectedMessage?: string;
}

const FAQS = [
  {
    q: 'Do I own the source code and brand files?',
    a: "Yes. Every custom website, app and design system comes with the full source code and commercial rights, so you're never locked in.",
  },
  {
    q: 'Can you customize your ready-made platforms for us?',
    a: 'Yes. You can license a platform directly on Gumroad, or we can rebrand it, add features and connect your local payment and shipping services.',
  },
  {
    q: 'How do payments work for custom projects?',
    a: "We work in milestones. You review progress on a staging site at each stage and pay for a milestone once you're happy with it.",
  },
];

export function ContactSection({
  whatsappNumber,
  packagesByTrack,
  preselectedOffering = '',
  preselectedMessage = '',
}: ContactSectionProps) {
  const sectionRef = useRef<HTMLElement>(null);
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [projectType, setProjectType] = useState(preselectedOffering || 'Custom Web Development');
  const [customOptions, setCustomOptions] = useState<string[]>([]);
  const [message, setMessage] = useState(preselectedMessage || '');
  const [honeypot, setHoneypot] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [status, setStatus] = useState<{ type: 'success' | 'error'; text: string } | null>(null);
  const [openFaq, setOpenFaq] = useState<number>(0);

  const allPackageOptions = Object.values(packagesByTrack)
    .flat()
    .map((p) => `${p.title} (${p.price_label})`);

  useGSAP(
    () => {
      const mm = gsap.matchMedia();
      mm.add('(prefers-reduced-motion: no-preference)', () => {
        if (!sectionRef.current) return;
        gsap.fromTo(
          sectionRef.current.querySelectorAll('.om-contact-left, .om-contact-form'),
          { opacity: 0, y: 24 },
          {
            opacity: 1,
            y: 0,
            duration: 0.65,
            stagger: 0.12,
            ease: 'power3.out',
            clearProps: 'transform,opacity',
            scrollTrigger: {
              trigger: sectionRef.current,
              start: 'top 84%',
              once: true,
              fastScrollEnd: true,
            },
          }
        );
      });
      return () => mm.revert();
    },
    { scope: sectionRef }
  );

  useEffect(() => {
    if (!preselectedOffering) return;
    setProjectType(preselectedOffering);
    const knownStatic = [
      'Custom Web Development',
      'Mobile App Development (Flutter & Dart)',
      'Website Redesign & Modernization',
      'SaaS Product Engineering',
      'Search Engine Optimization (SEO)',
      'Social Media Marketing & Paid Ads',
      'Branding, Logo & Graphic Design',
      'E-Commerce & Online Store Build',
      'Customize Ready-Made Software For My Business',
      'Cheetah Enterprise License ($99)',
      'Cheetah Lite License ($49)',
      'Cheezify Food & POS License ($59)',
      'Free Website, UX & SEO Teardown Audit',
      ...allPackageOptions,
    ];
    if (!knownStatic.includes(preselectedOffering)) {
      setCustomOptions((prev) =>
        prev.includes(preselectedOffering) ? prev : [preselectedOffering, ...prev]
      );
    }
  }, [preselectedOffering]);

  useEffect(() => {
    if (preselectedMessage) setMessage(preselectedMessage);
  }, [preselectedMessage]);

  const waHref = whatsappLink(
    whatsappNumber,
    `Hello! I would like to discuss ${projectType} with Creatsify Digital Agency.`
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
    <section ref={sectionRef} id="contact" className="om-contact-sec">
      <div className="wrap om-contact-wrap">
        <div className="om-contact-left">
          <div className="om-sec-tag">
            <span className="om-sec-dot" /> Get in touch
          </div>
          <h2 className="om-sec-heading">Tell us what you&apos;d like to build</h2>
          <p className="om-sec-sub">
            Choose a package, ask about customizing one of our ready-made platforms, or request a free website and SEO review. Prefer a quick chat? Message us on WhatsApp.
          </p>

          <div className="om-whatsapp-card">
            <div className="om-wa-header">
              <span className="om-wa-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z" />
                </svg>
              </span>
              <div className="om-wa-header-info">
                <b>Chat on WhatsApp</b>
                <span>Talk directly with our senior team</span>
              </div>
              <span className="om-wa-active-pill">
                <i className="om-pulse-dot" /> Quick replies
              </span>
            </div>
            <a
              href={waHref}
              target="_blank"
              rel="noopener noreferrer"
              className="om-wa-btn"
              id="homeWaDynamicLink"
            >
              <span className="om-wa-btn-lead">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z" />
                </svg>
                <span>Message us on WhatsApp</span>
              </span>
            </a>
          </div>

          {/* Silky Smooth Animated FAQ Accordion */}
          <div className="ag-faq-mini">
            {FAQS.map((faq, idx) => {
              const isOpen = openFaq === idx;
              return (
                <div
                  key={faq.q}
                  className="overflow-hidden rounded-[14px] border border-[var(--line)] bg-[var(--surface)] transition-colors hover:border-[var(--line-strong)]"
                >
                  <button
                    type="button"
                    onClick={() => setOpenFaq(isOpen ? -1 : idx)}
                    aria-expanded={isOpen}
                    className="flex w-full items-center justify-between gap-3 px-4 py-3.5 text-left font-display text-[13.5px] font-bold text-[var(--text)] cursor-pointer"
                  >
                    <span>{faq.q}</span>
                    <Plus
                      className={`h-4 w-4 shrink-0 text-[var(--accent)] transition-transform duration-200 ${
                        isOpen ? 'rotate-45' : ''
                      }`}
                    />
                  </button>
                  <AnimatePresence initial={false}>
                    {isOpen && (
                      <motion.div
                        key="answer"
                        initial={{ height: 0, opacity: 0 }}
                        animate={{ height: 'auto', opacity: 1 }}
                        exit={{ height: 0, opacity: 0 }}
                        transition={{ duration: 0.24, ease: [0.16, 1, 0.3, 1] }}
                        className="overflow-hidden"
                      >
                        <p className="px-4 pb-4 text-[13px] leading-relaxed text-[var(--muted)]">
                          {faq.a}
                        </p>
                      </motion.div>
                    )}
                  </AnimatePresence>
                </div>
              );
            })}
          </div>
        </div>

        <form id="form" className="om-contact-form" onSubmit={handleSubmit}>
          <input
            type="text"
            name="_hp_check"
            value={honeypot}
            onChange={(e) => setHoneypot(e.target.value)}
            style={{ display: 'none' }}
            tabIndex={-1}
            autoComplete="off"
            aria-hidden="true"
          />

          <div className="ag-form-head">
            <strong>Request a proposal</strong>
            <span>We reply within 24 hours &bull; Your details stay private</span>
          </div>

          {status && (
            <motion.div
              initial={{ opacity: 0, y: -6 }}
              animate={{ opacity: 1, y: 0 }}
              role="status"
              style={{
                padding: '12px 14px',
                borderRadius: '12px',
                fontSize: '13.5px',
                marginBottom: '12px',
                border:
                  status.type === 'success'
                    ? '1px solid rgba(16,185,129,0.4)'
                    : '1px solid rgba(244,63,94,0.4)',
                background:
                  status.type === 'success'
                    ? 'rgba(16,185,129,0.12)'
                    : 'rgba(244,63,94,0.12)',
                color: status.type === 'success' ? '#10b981' : '#fb7185',
              }}
            >
              {status.text}
            </motion.div>
          )}

          <label>
            Your name
            <input
              name="name"
              required
              autoComplete="name"
              value={name}
              onChange={(e) => setName(e.target.value)}
              placeholder="John Doe"
            />
          </label>
          <label>
            Your email
            <input
              name="email"
              type="email"
              required
              autoComplete="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="john@company.com"
            />
          </label>
          <label>
            Phone or WhatsApp (optional)
            <input
              name="phone"
              type="tel"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
              placeholder="+1 555 123 4567"
              autoComplete="tel"
            />
          </label>
          <label>
            Service or Package Needed
            <select
              name="project_type"
              id="homeProjectTypeSelect"
              value={projectType}
              onChange={(e) => setProjectType(e.target.value)}
            >
              {customOptions.map((opt) => (
                <option key={opt} value={opt}>
                  {opt}
                </option>
              ))}
              <optgroup label="Agency Service Packages">
                <option value="Custom Web Development">Custom web development</option>
                <option value="Mobile App Development (Flutter & Dart)">Mobile app (iOS &amp; Android)</option>
                <option value="Website Redesign & Modernization">Website redesign</option>
                <option value="SaaS Product Engineering">SaaS product or web portal</option>
                <option value="Search Engine Optimization (SEO)">SEO</option>
                <option value="Social Media Marketing & Paid Ads">Social media &amp; paid ads</option>
                <option value="Branding, Logo & Graphic Design">Branding &amp; logo design</option>
                <option value="E-Commerce & Online Store Build">E-commerce store</option>
              </optgroup>
              {allPackageOptions.length > 0 && (
                <optgroup label="Package Tiers">
                  {allPackageOptions.map((opt) => (
                    <option key={opt} value={opt}>
                      {opt}
                    </option>
                  ))}
                </optgroup>
              )}
              <optgroup label="Ready-Made Software (Buy or Customize)">
                <option value="Customize Ready-Made Software For My Business">
                  Customize a ready-made platform
                </option>
                <option value="Cheetah Enterprise License ($99)">
                  Cheetah Enterprise License ($99)
                </option>
                <option value="Cheetah Lite License ($49)">Cheetah Lite License ($49)</option>
                <option value="Cheezify Food & POS License ($59)">
                  Cheezify Food &amp; POS License ($59)
                </option>
              </optgroup>
              <optgroup label="Free Audit">
                <option value="Free Website, UX & SEO Teardown Audit">
                  Free 15-minute website &amp; SEO review
                </option>
              </optgroup>
            </select>
          </label>
          <label>
            Project details &amp; goals
            <textarea
              name="message"
              id="homeProjectMessage"
              rows={4}
              required
              value={message}
              onChange={(e) => setMessage(e.target.value)}
              placeholder="Tell us about your project, goals, target timeline, or any specific requirements..."
            />
          </label>
          <Magnetic strength={0.15} className="w-full">
            <button
              className="om-btn-gold"
              type="submit"
              disabled={submitting}
              style={{ width: '100%', justifyContent: 'center', border: 'none', cursor: 'pointer' }}
            >
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
              </svg>
              <span>{submitting ? 'Sending brief...' : 'Send your brief'}</span>
            </button>
          </Magnetic>
        </form>
      </div>
    </section>
  );
}
