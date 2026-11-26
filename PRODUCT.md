# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

delegated + user-confirmed: Next.js (App Router, SSR/ISR, React 19, TypeScript, Tailwind CSS v4, Motion, GSAP + ScrollTrigger, Lenis Smooth Scroll, React Three Fiber WebGL) for the public frontend (`frontend/`), paired with the existing Custom PHP 8.2/8.4 MVC backend (`core/`, `app/`, MySQL PDO, Admin Desk `/admin/*`, Agency CRM, WhatsApp Cloud API, Live Chat, Support Portal, self-healing DB) via a thin `/api/v1/*` JSON bridge on the same aaPanel VPS (Nginx routes `/admin`, `/api`, `/uploads`, `/install.php` to PHP 8.4 FPM and public pages to Next.js on localhost Node/PM2).

## Users

1. **Startup Founders, SMB Owners & Operations Directors (Worldwide):** Looking to hire a senior technical & creative partner for custom web platforms, SaaS/CRM systems, Flutter mobile apps, website redesigns, branding, SEO, and paid growth campaigns — evaluating real shipped work, transparent scope/packages, and direct founder access before booking or messaging on WhatsApp.
2. **Commercial Software & Script Buyers:** Entrepreneurs and logistics/restaurant operators buying direct white-label software licenses (Cheetah Courier Enterprise, Cheetah Lite, Cheezify POS, RestroMaster, ViddMate) via Creatsify's Official Gumroad Store and accessing first-party technical support at `/support`.
3. **Creatsify Internal Studio Operators:** Managing projects, services, pricing packages, client reviews, blog posts, CRM clients/tickets, live chat, and WhatsApp/SMS inboxes inside the existing PHP Admin Desk (`/admin`).

## Product Purpose

Creatsify.tech is the flagship digital storefront, portfolio showcase, lead-conversion engine, and direct commercial software licensing hub for Creatsify Digital Studio & Agency. Success means a first-time visitor immediately recognizes senior engineering and design craft from real shipped work, explores case studies and packages without friction, and converts via Project Inquiry, Live Chat, WhatsApp, or direct Gumroad software license checkout.

## Positioning

Unlike template-driven agencies that outsource development or hide behind stock mockups, Creatsify operates as a unified senior full-stack engineering, brand design, and growth studio backed by battle-tested, production-shipped commercial software platforms — offering fixed milestones, 100% source-code & IP ownership, direct senior technical access, and instant-deploy white-label licenses with first-party support.

## Operating Context

- **Public Routes:** `/` (Home), `/portfolio` & `/portfolio/[slug]` (and `/work` aliases), `/software` (Ready-Made Commercial Software Store), `/services`, `/packages`, `/reviews` (read + submit verified review), `/about`, `/blog` & `/blog/[slug]`, `/contact` (project inquiry), `/support` (first-party script buyer support portal with CRM ticket creation + WhatsApp founder alert), `/privacy`, `/sitemap.xml`.
- **Interactive Channels:** Persistent native Live Chat widget (`/api/chat/*` with automated assistant + admin live handoff), direct WhatsApp launch links, project inquiry & support forms, and verified client review submission.
- **Hosting & Operations:** Deployed on aaPanel VPS (Nginx + PHP 8.4 FPM + MySQL 8/MariaDB + Node.js/PM2 for Next.js SSR/ISR) under canonical domain `https://creatsify.tech`. Dual Git mirrors (`origin` and `old-creatsify`) kept in sync.

## Capabilities and Constraints

- **Backend Preservation (Hard Constraint):** The existing Custom PHP 8.2/8.4 MVC backend (`Core\App`, `Core\Router`, `Core\Database`, all Models, `/admin/*` desk, CRM, WhatsApp webhook `/api/webhooks/whatsapp`, Live Chat `/api/chat/*`, Support Portal, self-healing DB migrations) MUST remain intact and authoritative.
- **Commercial Strategy (Hard Constraint):** Zero third-party marketplace leakage (no Codester, Coderobotics, CodeGrape, SellAnyCode links or badges). All ready-made commercial software licenses are sold directly via Creatsify's Official Gumroad Store (`gumroad_url`, `price`, `is_software = 1`).
- **Portfolio Taxonomy:** 7 official discipline categories (`web-dev`, `mobile-app`, `saas-crm`, `software-eng`, `seo-geo`, `digital-marketing`, `ui-ux-branding`), with `web-dev` ("Web Design & Development") first and active by default (no "All Work" tab). Ready-Made Software is an attribute tag (`is_software = 1`), not a category.
- **UX Commitments Already Established:**
  - Full Dark & Light mode support (persisted via `localStorage` without flash of unstyled content).
  - Fully responsive across desktop, tablet, and mobile viewports (zero horizontal overflow).
  - Review/testimonial sliders draggable by mouse on desktop and swipeable by touch on mobile.
  - Work cards keep clean visual showcase (3–4 columns on desktop), hover/focus reveal of actions, and a category-aware Lightbox Preview Modal (Live Preview, Buy License, More Details).
- **SEO & Performance Requirement:** Server-rendered HTML via Next.js App Router SSR/ISR, dynamic `generateMetadata()` (canonical, OpenGraph, Twitter cards), JSON-LD structured data, automatic WebP/AVIF image optimization, and 95+ Core Web Vitals.

## Brand Commitments

- **Brand Name:** Creatsify (`Creatsify.tech` / Creatsify Digital Agency & Software Studio).
- **Binding Visual Upgrade Direction (User-Pinned):** Upgrade and elevate the **existing Creatsify.tech design identity and layout architecture** — preserving the Obsidian Dark (`#07080A` / `#0F1117`) and Daylight (`#F6F9FE` / `#FFFFFF`) theme duality, brand sapphire-to-cyan accents (`#0284C7` / `#00D2EE`), emerald live indicators (`#10B981`), `Sora` + `Plus Jakarta Sans` typography, agency command stage, work showcase cards, service bento, pricing tracks + scope estimator, ready-made software showcase, 4-step workflow, draggable dual-row review marquee, consultation hub, and persistent live chat — rebuilt cleanly in Next.js App Router with Motion, GSAP ScrollTrigger, Lenis smooth scroll, and restrained React Three Fiber / WebGL depth.
- **Existing Brand Assets (Preserved):**
  - `public/assets/brand/creatsify-logo-dark.svg` & `creatsify-logo-light.svg` (and PNG equivalents)
  - `public/assets/brand/creatsify-wordmark.png` & `creatsify-wordmark-light.png`
  - `public/assets/brand/creatsify-icon.svg`, `favicon.ico`, `favicon.svg`, `apple-touch-icon.png`, `og-image.png`
- **Voice:** Direct, confident, engineering-first human copy. Zero generic AI marketing buzzwords.

## Evidence on Hand

- **Live MySQL Content (Authoritative via PHP Models):**
  - Projects & Case Studies (`app/Models/Project.php`): Apex Retail, Cheetah Enterprise (`https://cheetah.creatsify.tech`, `$99`), Cheetah Lite (`https://cheetah-lite.creatsify.tech`, `$49`), Cheezify POS, RestroMaster POS & KDS, ViddMate Video Suite, Lumina Fleet Companion, Kinetix Brand System, PulseRank GEO/SEO, NovaGrowth Performance Ads.
  - High-resolution project showcase imagery in `public/assets/images/` (`apex-preview.webp`, `cheetah-showcase.webp`, `cheetah-lite-preview.webp`, `cheezify-preview.webp`, `restromaster-preview.webp`, `viddmate-preview.webp`, `lumina-preview.webp`, `kinetix-preview.webp`, `pulserank-preview.webp`, `novagrowth-preview.webp`).
  - 8 Core Agency Services (`app/Models/Service.php`): Custom Web Development, Mobile App Development (Flutter & Dart), Website Redesign & UI/UX Modernization, SaaS Products & Custom Portals, SEO, Social Media Marketing & Paid Ads, Branding/Logo/Graphic Design, E-Commerce & Custom Store Systems.
  - Multi-track Service Packages (`app/Models/ServicePackage.php`: `dev`, `design`, `growth` tracks).
  - Verified Client Reviews & aggregate stats (`app/Models/ClientReview.php`).
  - Blog Articles (`app/Models/Post.php`) and editable homepage Sections (`app/Models/Section.php`).
- **Absences not to fabricate:** Do not invent fake Fortune-500 client logos, unverified user counts, or synthetic pricing outside database-backed packages and software products.

## Product Principles

1. **Proof Before Pitch:** Lead with concrete shipped engineering systems, real case-study visuals, and measurable outcomes rather than abstract agency slogans.
2. **Unified Backend Authority:** Every public page reads live data from the Custom PHP MVC + MySQL backend so edits made in `/admin` reflect automatically via ISR without code redeploys.
3. **Frictionless Conversion Paths:** Visitors always have a clear, low-friction next step — live project preview, interactive scope/package selection, persistent live chat, direct WhatsApp, or instant Gumroad license purchase.
4. **Performance as Credibility:** As a web engineering and SEO agency, Creatsify's own site speed, responsiveness, motion discipline, and search metadata must be its strongest portfolio piece.

## Accessibility & Inclusion

- Semantic HTML5 landmarks, keyboard-navigable menus/tabs/modals with focus trapping and Escape dismissal, visible `:focus-visible` outlines, WCAG AA contrast in both dark and light themes, and full `prefers-reduced-motion` support across all GSAP/Motion/WebGL animations.
