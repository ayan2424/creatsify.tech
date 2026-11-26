---
name: Creatsify Studio
description: Obsidian-and-daylight digital engineering & commercial software studio system.
colors:
  accent-cyan: "#00d2ee"
  accent-sapphire: "#0284c7"
  emerald-live: "#10b981"
  amber-rating: "#f59e0b"
  obsidian-ground: "#07080a"
  obsidian-subtle: "#0b0e14"
  obsidian-surface: "#0f1219"
  obsidian-elevated: "#141923"
  porcelain-ink: "#f8fafc"
  slate-muted: "#94a3b8"
  daylight-ground: "#f6f9fe"
  daylight-surface: "#ffffff"
  daylight-ink: "#0a1a33"
typography:
  display:
    fontFamily: "Sora, system-ui, sans-serif"
    fontSize: "3rem"
    fontWeight: 800
    lineHeight: 1.1
    letterSpacing: "-0.03em"
  headline:
    fontFamily: "Sora, system-ui, sans-serif"
    fontSize: "2.25rem"
    fontWeight: 800
    lineHeight: 1.15
    letterSpacing: "-0.025em"
  title:
    fontFamily: "Sora, system-ui, sans-serif"
    fontSize: "1.25rem"
    fontWeight: 700
    lineHeight: 1.25
    letterSpacing: "-0.02em"
  body:
    fontFamily: "Plus Jakarta Sans, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.68
  label:
    fontFamily: "JetBrains Mono, ui-monospace, monospace"
    fontSize: "0.75rem"
    fontWeight: 600
    lineHeight: 1.2
rounded:
  md: "12px"
  lg: "16px"
  pill: "9999px"
spacing:
  section: "80px"
  container: "1220px"
  gutter: "20px"
components:
  button-primary:
    backgroundColor: "{colors.accent-cyan}"
    textColor: "{colors.obsidian-ground}"
    rounded: "{rounded.pill}"
    padding: "12px 24px"
  button-ghost:
    backgroundColor: "{colors.obsidian-surface}"
    textColor: "{colors.porcelain-ink}"
    rounded: "{rounded.pill}"
    padding: "12px 22px"
  studio-card:
    backgroundColor: "{colors.obsidian-surface}"
    textColor: "{colors.porcelain-ink}"
    rounded: "{rounded.lg}"
    padding: "24px"
---

# Design System: Creatsify Studio

## Overview

**Creative North Star: "Precision Obsidian & Daylight Studio"**

Creatsify Studio balances high-craft engineering authority with tactile digital clarity. The visual world preserves Creatsify's signature deep obsidian dark canvas paired with a clean daylight light mode, anchored by crisp sapphire-to-cyan brand accents and uninterrupted, high-resolution showcases of shipped software. Every surface speaks through scale, contrast, and clean geometry rather than decorative clutter.

**Key Characteristics:**
- Dual-theme Obsidian Dark and Crisp Daylight polarity persisted without flash of unstyled content.
- Monumental `Sora` display headlines paired with highly readable `Plus Jakarta Sans` prose and `JetBrains Mono` strictly for tabular numbers, prices, and technical telemetry.
- Interactive 3D optical WebGL depth and damped spring tilt in the hero command stage paired with Lenis smooth scroll and GSAP ScrollTrigger workflow choreography.
- Clean 3-to-4 column full-bleed portfolio cards with macOS traffic-light indicators and hover/touch frosted action overlays.

## Colors

The palette pairs restrained, cool-tinted obsidian and daylight neutrals with high-clarity sapphire-cyan conversion surfaces and emerald status signals.

### Primary
- **Creatsify Cyan** (`accent-cyan`): Primary CTA highlight, active filter pill fill, interactive focus ring, and dark-mode accent.
- **Studio Sapphire** (`accent-sapphire`): Secondary gradient anchor on primary buttons and primary high-contrast accent in Daylight Light mode.

### Secondary
- **Live Emerald** (`emerald-live`): Verified delivery checks, live availability dots, WhatsApp conversion controls, and direct Gumroad commercial license badges.
- **Verified Amber** (`amber-rating`): Reserved strictly for verified client star ratings and paid-ROAS metric pills.

### Neutral
- **Obsidian Ground & Surfaces** (`obsidian-ground`, `obsidian-subtle`, `obsidian-surface`, `obsidian-elevated`): Stepped dark charcoal layers establishing hierarchy without heavy drop shadows.
- **Daylight Ground & Surfaces** (`daylight-ground`, `daylight-surface`, `daylight-ink`): Cool architectural blue-tinted daylight surfaces maintaining WCAG AA readability.

### Named Rules
**The Single-Accent Discipline Rule.** Brand cyan and sapphire lead primary actions and active states; never introduce unmotivated purple/indigo AI gradients or decorative gradient fills on headings.

## Typography

**Display Font:** Sora (system-ui fallback)
**Body Font:** Plus Jakarta Sans (system-ui fallback)
**Label/Mono Font:** JetBrains Mono (ui-monospace fallback)

**Character:** Geometric, confident display lettering paired with an open, readable humanist body face and crisp tabular monospace strictly for numerical specs, pricing, and stack identifiers.

### Hierarchy
- **Display** (`800`, `clamp(32px, 4vw, 48px)`, `1.1`): Primary page and hero statements.
- **Headline** (`800`, `30px–36px`, `1.15`): Major section anchors without preceding eyebrow kickers.
- **Title** (`700`, `18px–22px`, `1.25`): Card headings, package titles, and case study subheads.
- **Body** (`400`, `15px–16.5px`, `1.68`): Product-truth prose constrained to 56–70ch measure.
- **Label** (`600`, `11px–12px`, `tabular-nums`): Index numbers, delivery timelines, prices, and live telemetry chips.

### Named Rules
**The Weight-Not-Gradient Rule.** Headings earn emphasis through scale, tight tracking (`-0.025em` to `-0.03em`), and stark contrast — never gradient text clips or uppercase kicker tags above them.

## Layout

Pages center inside a fluid `1220px` container (`1280px` for the 4-column portfolio grid) with `20px–24px` horizontal gutters and generous `80px–112px` vertical section separation. Sections alternate smoothly between canvas ground and subtle tinted bands (`--bg-subtle`), while the hero stacks cleanly to a single column below `1024px` with zero horizontal clipping.

## Elevation & Depth

Surfaces rely primarily on stepped tonal fills and crisp `1px` rim borders (`--line` / `--line-strong`). Deep diffused shadows are reserved strictly for floating overlays (the hero command card, modal lightboxes, and the persistent live chat drawer).

### Named Rules
**The Single Elevation Declaration Rule.** Never stack heavy drop shadows beneath high-contrast borders on resting cards; declare elevation once through clean rim borders or tonal stepping.

## Shapes

Cards, panels, and modal dialogs maintain a disciplined `16px` (`rounded-2xl`) radius; inputs and inner tiles use `12px` (`rounded-xl`); interactive buttons, nav active pills, and filter tabs use full pill geometry (`9999px`).

## Components

### Buttons
- **Shape:** Full pill (`9999px`) with instant tactile press (`scale(0.97)` over `160ms` exponential ease-out).
- **Primary:** Sapphire-to-cyan gradient fill with high-contrast dark ink in dark mode and white ink in light mode.
- **Secondary / Ghost:** Surface fill with `1px` crisp rim border that brightens on fine-pointer hover.

### Portfolio Work Cards
- **Style:** Full-bleed `4:3` screenshot container (`16px` radius) with always-visible top-left macOS 3 traffic-light dots in a dark frosted capsule.
- **Interaction:** Resting card stays visually uncluttered; hover, keyboard focus, or mobile tap reveals a progressive bottom-up frosted overlay carrying outcome badge, title, and category-aware actions (Live Site, Quick Preview Lightbox, Buy License, Case Details).

### Navigation & Persistent Live Chat
- **Header:** Sticky frosted glass bar with spring-animated `layoutId` active indicator, theme polarity switcher, and slide-over mobile drawer.
- **Live Chat:** Mounted in `RootLayout` so active visitor conversations persist seamlessly across all route transitions while syncing directly to `/api/chat/*`.

## Do's and Don'ts

### Do:
- **Do** keep `Web Design & Development` first and active by default across `#work` and `/portfolio` (no "All Work" tab).
- **Do** route all public data reads and form submissions through the Custom PHP MVC `/api/v1/*`, `/api/contact`, `/api/support`, and `/api/chat/*` endpoints.
- **Do** gate hover-lift transforms inside `@media (hover: hover) and (pointer: fine)` and honor `prefers-reduced-motion` across Motion, GSAP, Lenis, and WebGL.
- **Do** style overlooked browser surfaces (`::selection`, `caret-color`, custom scrollbars, `:focus-visible`, `tabular-nums`).

### Don't:
- **Don't** add eyebrow/kicker tags above headings, gradient text on headings, or emoji icons anywhere in the UI.
- **Don't** introduce third-party script marketplace links (Codester, CodeGrape, SellAnyCode) — sell commercial software strictly through Gumroad and direct inquiry.
- **Don't** animate interactive UI elements from `scale(0)` or with sluggish `ease-in` curves.
