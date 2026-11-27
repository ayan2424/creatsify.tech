'use client';

import React, { useState } from 'react';
import dynamic from 'next/dynamic';
import { motion } from 'motion/react';
import { useTheme } from '@/components/Providers';
import { Magnetic } from '@/components/ui/Magnetic';
import { whatsappLink } from '@/lib/utils';

const CreatsifyNekoCanvas = dynamic(
  () => import('@/components/ui/CreatsifyNekoCanvas').then((m) => m.CreatsifyNekoCanvas),
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
  const [boopSignal, setBoopSignal] = useState(0);

  const waHref = whatsappLink(
    whatsappNumber,
    'Hello! I would like to discuss a project or service package with Creatsify Digital Agency.'
  );

  const triggerRobotBoop = () => setBoopSignal((n) => n + 1);

  return (
    <section className="om-hero" id="hero">
      <div className="wrap om-hero-wrap">
        {/* Left Side: High-Authority Agency Value Proposition */}
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
                onMouseEnter={triggerRobotBoop}
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

        {/* Right Side: Pure, Unobstructed Floating 3D Pixar Neko Hero Mascot */}
        <motion.div
          initial={{ opacity: 0, scale: 0.96, y: 16 }}
          animate={{ opacity: 1, scale: 1, y: 0 }}
          transition={{ duration: 0.78, delay: 0.12, ease: [0.16, 1, 0.3, 1] }}
          className="om-hero-visual-stage relative flex min-h-[430px] items-center justify-center sm:min-h-[520px]"
          id="omVisualStage"
        >
          <div className="om-stage-glow" aria-hidden="true" />
          <div className="relative h-[430px] w-full max-w-[520px] sm:h-[520px]">
            <CreatsifyNekoCanvas
              variant="hero"
              mode="hover"
              isDark={theme === 'dark'}
              boopSignal={boopSignal}
              globalPointer={true}
              interactive={true}
              className="h-full w-full"
              ariaLabel="Interactive 3D Creatsify Neko Mascot — drag to spin, tap to boop"
            />
          </div>
        </motion.div>
      </div>
    </section>
  );
}
