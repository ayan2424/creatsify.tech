'use client';

import React from 'react';

export function WorkflowSection() {
  return (
    <section id="how" className="om-how-sec">
      <div className="wrap">
        <div className="om-sec-center">
          <div className="om-sec-tag">
            <span className="om-sec-dot" /> How it works
          </div>
          <h2 className="om-sec-heading">From first call to launch in four steps</h2>
          <p className="om-sec-sub">
            Clear milestones, a live staging link and no guesswork from day one.
          </p>
        </div>

        <div className="ag-workflow-4grid">
          <div className="om-how-card">
            <span className="om-how-step-num">01</span>
            <h3>Discover &amp; scope</h3>
            <p>
              We learn your goals, define the scope and agree on fixed milestones and a timeline before any work starts.
            </p>
          </div>
          <div className="om-how-card">
            <span className="om-how-step-num">02</span>
            <h3>Design</h3>
            <p>
              Our creative team shapes your brand, design system and key screens in Figma, so you can approve the look and feel before development begins.
            </p>
          </div>
          <div className="om-how-card">
            <span className="om-how-step-num">03</span>
            <h3>Build</h3>
            <p>
              We develop on a private staging server so you can review real progress every week and give feedback as we go.
            </p>
          </div>
          <div className="om-how-card">
            <span className="om-how-step-num">04</span>
            <h3>Launch &amp; grow</h3>
            <p>
              We deploy to your production server, set up technical SEO and tracking, and &mdash; if you want &mdash; launch search and social campaigns.
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}
