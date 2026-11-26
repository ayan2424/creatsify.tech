'use client';

import React, { useRef } from 'react';
import { motion } from 'motion/react';
import { whatsappLink } from '@/lib/utils';

interface HeroSectionProps {
  avgRating: string;
  totalReviews: number;
  whatsappNumber?: string;
}

export function HeroSection({
  avgRating,
  whatsappNumber = '923002521199',
}: HeroSectionProps) {
  const stageRef = useRef<HTMLDivElement>(null);
  const cardRef = useRef<HTMLDivElement>(null);
  const float1Ref = useRef<HTMLDivElement>(null);
  const float2Ref = useRef<HTMLAnchorElement>(null);

  const waHref = whatsappLink(
    whatsappNumber,
    'Hello! I would like to discuss a project or service package with Creatsify Digital Agency.'
  );

  const handleStageMove = (e: React.MouseEvent<HTMLDivElement>) => {
    const stage = stageRef.current;
    const card = cardRef.current;
    if (!stage || !card) return;
    if (window.matchMedia('(hover: none), (prefers-reduced-motion: reduce)').matches) return;

    const rect = stage.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const cx = rect.width / 2;
    const cy = rect.height / 2;

    const rotX = ((y - cy) / cy) * -5;
    const rotY = ((x - cx) / cx) * 5;
    card.style.transform = `perspective(800px) rotateX(${rotX.toFixed(2)}deg) rotateY(${rotY.toFixed(2)}deg) scale3d(1.01, 1.01, 1.01)`;

    const floats = [float1Ref.current, float2Ref.current];
    floats.forEach((f, idx) => {
      if (!f) return;
      const factor = (idx + 1) * 1.4;
      const tx = ((x - cx) / cx) * factor;
      const ty = ((y - cy) / cy) * factor;
      f.style.transform = `translate3d(${tx.toFixed(1)}px, ${ty.toFixed(1)}px, 0)`;
    });
  };

  const handleStageLeave = () => {
    if (cardRef.current) cardRef.current.style.transform = '';
    if (float1Ref.current) float1Ref.current.style.transform = '';
    if (float2Ref.current) float2Ref.current.style.transform = '';
  };

  return (
    <section className="om-hero" id="hero">
      <div className="wrap om-hero-wrap">
        {/* Left Side: Exact PHP High-Authority Agency Value Proposition */}
        <motion.div
          initial={{ opacity: 0, y: 18 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.65, ease: [0.16, 1, 0.3, 1] }}
          className="om-hero-content"
        >
          <div className="om-hero-tag">
            <span className="om-tag-beacon" />
            <span>Web, apps, brand &amp; growth &mdash; one team</span>
          </div>

          <h1 className="om-hero-headline">
            <span className="om-head-top">Websites, apps and brands</span>
            <span className="om-head-bot">that help you grow.</span>
          </h1>

          <p className="om-hero-sub">
            We design and build fast websites, mobile apps and brand systems &mdash; then help the right customers find you through SEO and paid ads. You work directly with the people doing the work.
          </p>

          <div className="om-cta-group">
            <a className="om-btn-gold" href="#contact">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
              </svg>
              <span>Start a project</span>
            </a>
            <a className="om-btn-glass" href="#work">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
              </svg>
              <span>See our work</span>
            </a>
            <a className="om-hero-wa-link" href={waHref} target="_blank" rel="noopener noreferrer">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.28 3.03 14.79 2 12.04 2z" />
              </svg>
              <span>Chat on WhatsApp</span>
            </a>
          </div>

          <div className="om-proof-bar">
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
          </div>
        </motion.div>

        {/* Right Side: Exact PHP Multi-Discipline Agency Command Stage */}
        <motion.div
          ref={stageRef}
          onMouseMove={handleStageMove}
          onMouseLeave={handleStageLeave}
          initial={{ opacity: 0, y: 22 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.72, delay: 0.14, ease: [0.16, 1, 0.3, 1] }}
          className="om-hero-visual-stage"
          id="omVisualStage"
        >
          <div className="om-stage-glow" aria-hidden="true" />

          <div ref={cardRef} className="om-centerpiece-card om-agency-command-card">
            <div className="om-card-browser-bar">
              <div className="om-browser-dots">
                <span className="dot d-red" />
                <span className="dot d-yellow" />
                <span className="dot d-green" />
              </div>
              <div className="om-browser-url">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <polyline points="16 18 22 12 16 6" />
                  <polyline points="8 6 2 12 8 18" />
                </svg>
                <span>creatsify.tech / studio</span>
              </div>
              <span className="om-browser-badge">
                <i className="om-live-dot" /> One studio
              </span>
            </div>

            <div className="ag-stage-body">
              <div className="ag-pillar-row">
                <div className="ag-pillar-icon cyan">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2">
                    <rect x="2" y="3" width="20" height="14" rx="2" />
                    <line x1="8" y1="21" x2="16" y2="21" />
                    <line x1="12" y1="17" x2="12" y2="21" />
                  </svg>
                </div>
                <div className="ag-pillar-info">
                  <div className="ag-pillar-top">
                    <strong>Web &amp; mobile apps</strong>
                    <span className="ag-metric-pill emerald">Built to scale</span>
                  </div>
                  <p>PHP 8.4 &bull; Node.js &bull; Next.js &bull; Flutter &amp; Dart &bull; MySQL</p>
                </div>
              </div>

              <div className="ag-pillar-row">
                <div className="ag-pillar-icon cobalt">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2">
                    <path d="M12 19l7-7 3 3-7 7-3-3z" />
                    <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z" />
                    <path d="M2 2l7.586 7.586" />
                    <circle cx="11" cy="11" r="2" />
                  </svg>
                </div>
                <div className="ag-pillar-info">
                  <div className="ag-pillar-top">
                    <strong>Brand &amp; UI/UX design</strong>
                    <span className="ag-metric-pill cyan">UX-first</span>
                  </div>
                  <p>3D &amp; Vector Logos &bull; Brand Guidelines &bull; Figma Design Systems</p>
                </div>
              </div>

              <div className="ag-pillar-row">
                <div className="ag-pillar-icon purple">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                    <polyline points="17 6 23 6 23 12" />
                  </svg>
                </div>
                <div className="ag-pillar-info">
                  <div className="ag-pillar-top">
                    <strong>SEO &amp; paid ads</strong>
                    <span className="ag-metric-pill gold">Growth-led</span>
                  </div>
                  <p>Technical SEO &bull; Meta Ads &bull; Google Ads &bull; Funnels</p>
                </div>
              </div>

              <div className="ag-command-kpis">
                <div className="ag-kpi-box">
                  <span className="ag-kpi-val">100%</span>
                  <span className="ag-kpi-lbl">Code ownership</span>
                </div>
                <div className="ag-kpi-box">
                  <span className="ag-kpi-val">24h</span>
                  <span className="ag-kpi-lbl">Reply time</span>
                </div>
                <div className="ag-kpi-box">
                  <span className="ag-kpi-val">Fixed</span>
                  <span className="ag-kpi-lbl">Milestone pricing</span>
                </div>
              </div>

              <div className="ag-stage-footer">
                <div className="ag-stage-chips">
                  <span>Next.js</span>
                  <span>PHP 8.4</span>
                  <span>Flutter</span>
                  <span>Tailwind</span>
                  <span>SEO &amp; Ads</span>
                </div>
                <a href="#work" className="ag-stage-link">
                  Case Studies
                </a>
              </div>
            </div>
          </div>

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
