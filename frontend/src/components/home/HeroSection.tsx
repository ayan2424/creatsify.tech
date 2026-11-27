'use client';

import React, { useRef, useState } from 'react';
import dynamic from 'next/dynamic';
import { motion } from 'motion/react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { useGSAP } from '@gsap/react';
import { MessageSquare, MoveHorizontal, Sparkles } from 'lucide-react';
import { useTheme } from '@/components/Providers';
import { Magnetic } from '@/components/ui/Magnetic';
import { whatsappLink } from '@/lib/utils';

gsap.registerPlugin(ScrollTrigger, useGSAP);

const CreatsifyRobotCanvas = dynamic(
  () => import('@/components/ui/CreatsifyRobotCanvas').then((m) => m.CreatsifyRobotCanvas),
  { ssr: false }
);

interface HeroSectionProps {
  avgRating: string;
  totalReviews: number;
  whatsappNumber?: string;
}

export function HeroSection({
  avgRating,
  whatsappNumber = '923002521199',
}: HeroSectionProps) {
  const { theme } = useTheme();
  const sectionRef = useRef<HTMLElement>(null);
  const float1Ref = useRef<HTMLDivElement>(null);
  const float2Ref = useRef<HTMLAnchorElement>(null);
  const [cheerSignal, setCheerSignal] = useState(0);

  const waHref = whatsappLink(
    whatsappNumber,
    'Hello! I would like to discuss a project or service package with Creatsify Digital Agency.'
  );

  const triggerRobotCheer = () => setCheerSignal((n) => n + 1);

  const openLiveChat = () => {
    triggerRobotCheer();
    window.dispatchEvent(new CustomEvent('creatsify:open-chat'));
  };

  // Subtle scroll-scrubbed depth parallax on the floating studio badges (desktop only)
  useGSAP(
    () => {
      const mm = gsap.matchMedia();
      mm.add('(min-width: 960px) and (prefers-reduced-motion: no-preference)', () => {
        if (float1Ref.current) {
          gsap.to(float1Ref.current, {
            y: -26,
            ease: 'none',
            scrollTrigger: {
              trigger: sectionRef.current,
              start: 'top top',
              end: 'bottom top',
              scrub: 0.6,
            },
          });
        }
        if (float2Ref.current) {
          gsap.to(float2Ref.current, {
            y: -42,
            ease: 'none',
            scrollTrigger: {
              trigger: sectionRef.current,
              start: 'top top',
              end: 'bottom top',
              scrub: 0.6,
            },
          });
        }
      });
      return () => mm.revert();
    },
    { scope: sectionRef }
  );

  return (
    <section ref={sectionRef} className="om-hero" id="hero">
      <div className="wrap om-hero-wrap">
        {/* Left Side: Exact PHP High-Authority Agency Value Proposition + Kinetic Reveal */}
        <div className="om-hero-content">
          <motion.div
            initial={{ opacity: 0, y: 12 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, ease: [0.16, 1, 0.3, 1] }}
            className="om-hero-tag"
          >
            <span className="om-tag-beacon" />
            <span>Web, apps, brand &amp; growth &mdash; one team</span>
          </motion.div>

          <h1 className="om-hero-headline">
            <span className="block overflow-hidden">
              <motion.span
                initial={{ y: '105%', opacity: 0 }}
                animate={{ y: 0, opacity: 1 }}
                transition={{ duration: 0.7, delay: 0.06, ease: [0.16, 1, 0.3, 1] }}
                className="om-head-top block"
              >
                Websites, apps and brands
              </motion.span>
            </span>
            <span className="block overflow-hidden pb-1">
              <motion.span
                initial={{ y: '105%', opacity: 0 }}
                animate={{ y: 0, opacity: 1 }}
                transition={{ duration: 0.72, delay: 0.16, ease: [0.16, 1, 0.3, 1] }}
                className="om-head-bot block"
              >
                that help you grow.
              </motion.span>
            </span>
          </h1>

          <motion.p
            initial={{ opacity: 0, y: 14 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.26, ease: [0.16, 1, 0.3, 1] }}
            className="om-hero-sub"
          >
            We design and build fast websites, mobile apps and brand systems &mdash; then help the right customers find you through SEO and paid ads. You work directly with the people doing the work.
          </motion.p>

          <motion.div
            initial={{ opacity: 0, y: 14 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.34, ease: [0.16, 1, 0.3, 1] }}
            className="om-cta-group"
          >
            <Magnetic>
              <a
                className="om-btn-gold"
                href="#contact"
                onMouseEnter={triggerRobotCheer}
              >
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                  <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>
                <span>Start a project</span>
              </a>
            </Magnetic>
            <Magnetic strength={0.2}>
              <a className="om-btn-glass" href="#work">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                  <polyline points="14 2 14 8 20 8" />
                </svg>
                <span>See our work</span>
              </a>
            </Magnetic>
            <a className="om-hero-wa-link" href={waHref} target="_blank" rel="noopener noreferrer">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z" />
              </svg>
              <span>Chat on WhatsApp</span>
            </a>
          </motion.div>

          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.65, delay: 0.45 }}
            className="om-proof-bar"
          >
            {avgRating && (
              <span className="om-proof-item">
                <i className="om-proof-check">&#9733;</i> {avgRating}/5 from client reviews
              </span>
            )}
            <span className="om-proof-item">
              <i className="om-proof-check">&#10003;</i> You own all the code
            </span>
            <span className="om-proof-item">
              <i className="om-proof-check">&#10003;</i> Fixed milestones
            </span>
            <span className="om-proof-item">
              <i className="om-proof-check">&#10003;</i> Direct access to senior builders
            </span>
          </motion.div>
        </div>

        {/* Right Side: Interactive 3D Creatsify Robot Mascot Stage + Floating Trust Badges */}
        <motion.div
          initial={{ opacity: 0, scale: 0.96, y: 18 }}
          animate={{ opacity: 1, scale: 1, y: 0 }}
          transition={{ duration: 0.78, delay: 0.14, ease: [0.16, 1, 0.3, 1] }}
          className="om-hero-visual-stage relative flex min-h-[430px] flex-col items-center justify-center sm:min-h-[500px]"
          id="omVisualStage"
        >
          <div className="om-stage-glow" aria-hidden="true" />

          {/* Live 3D Interactive Creatsify Robot Canvas */}
          <div className="relative h-[400px] w-full max-w-[490px] sm:h-[470px]">
            <CreatsifyRobotCanvas
              variant="hero"
              isDark={theme === 'dark'}
              cheerSignal={cheerSignal}
              globalPointer={true}
              interactive={true}
              className="h-full w-full"
              ariaLabel="Interactive 3D Creatsify Robot — drag to rotate, click to wave"
            />
            <div className="robot-pedestal-glow" aria-hidden="true" />
          </div>

          {/* Sleek Interactive Control Dock Under Robot */}
          <div className="relative z-20 -mt-3 flex flex-wrap items-center justify-center gap-2">
            <button
              type="button"
              onClick={triggerRobotCheer}
              className="inline-flex items-center gap-1.5 rounded-full border border-[var(--line-strong)] bg-[color-mix(in_srgb,var(--surface)_86%,transparent)] px-3.5 py-1.5 font-mono text-[11.5px] font-medium text-[var(--muted)] backdrop-blur-md transition-colors hover:border-[var(--accent)] hover:text-[var(--text)] cursor-pointer"
            >
              <MoveHorizontal className="h-3.5 w-3.5 text-[var(--accent)]" />
              <span>Drag to spin &middot; Tap to wave</span>
            </button>
            <button
              type="button"
              onClick={openLiveChat}
              className="inline-flex items-center gap-1.5 rounded-full border border-[var(--accent)]/40 bg-[var(--accent-soft)] px-3.5 py-1.5 font-display text-[11.5px] font-bold text-[var(--accent)] backdrop-blur-md transition-transform hover:scale-[1.03] active:scale-95 cursor-pointer"
            >
              <MessageSquare className="h-3.5 w-3.5" />
              <span>Ask Creatsify AI</span>
              <Sparkles className="h-3 w-3" />
            </button>
          </div>

          {/* Floating Badge 1: Top Right — 4 Specialist Teams */}
          <div ref={float1Ref} className="om-float-card om-float-divisions">
            <div className="ag-division-icons">
              <span className="ag-div-icon code" title="Web & SaaS Engineering">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                  <polyline points="16 18 22 12 16 6" />
                  <polyline points="8 6 2 12 8 18" />
                </svg>
              </span>
              <span className="ag-div-icon design" title="UI/UX & Branding">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                  <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>
              </span>
              <span className="ag-div-icon growth" title="SEO & Paid Social Ads">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                  <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                  <polyline points="17 6 23 6 23 12" />
                </svg>
              </span>
            </div>
            <div className="om-founders-meta">
              <strong>4 specialist teams</strong>
              <span>Code &bull; Mobile &bull; Brand &bull; Growth</span>
            </div>
          </div>

          {/* Floating Badge 2: Bottom Left — Verified Client Rating */}
          {avgRating && (
            <a
              ref={float2Ref}
              href="#reviews"
              className="om-float-card om-float-quality"
              style={{ textDecoration: 'none' }}
            >
              <div className="om-quality-top">
                <span
                  className="om-quality-step"
                  style={{ color: '#f59e0b', fontSize: '13px', letterSpacing: '1px' }}
                >
                  &#9733;&#9733;&#9733;&#9733;&#9733;
                </span>
                <div className="om-quality-check">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" strokeWidth="3">
                    <polyline points="20 6 9 17 4 12" />
                  </svg>
                </div>
              </div>
              <strong className="om-quality-heading">{avgRating} / 5.0 Rating</strong>
              <span className="om-quality-sub">Client reviews</span>
            </a>
          )}
        </motion.div>
      </div>
    </section>
  );
}
