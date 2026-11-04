/**
 * Creatsify Studio - Hero "C." Core Engine & Blueprint Hologram Scene
 * Recreated for the Midnight Gold "C." Brand Identity
 * 
 * Features:
 * 1. 3D Sculpted Obsidian & Gold "C." Monolith Emblem with realistic specular lighting and spring-damped mouse parallax.
 * 2. Precision Gold & Royal Blue Holographic Laser Engine drawing 5 core systems:
 *    - Web Systems & SaaS
 *    - Courier Logistics (Cheetah)
 *    - Admin Dashboards
 *    - E-Commerce Stores (Cheezify)
 *    - Design Systems & UI
 * 3. Interactive Scene Tabs with animated progress bars (--p: 0..1).
 * 4. Interactive pointer particle trails with gold and blue sparks.
 * 5. Full keyboard accessibility, play/pause toggle, and prefers-reduced-motion support.
 */

(function () {
  'use strict';

  const root = document.getElementById('hp');
  const meta = document.getElementById('hpMeta');
  if (!root) return;

  const cp = root.querySelector('.hp-paint');
  const ct = root.querySelector('.hp-trail');
  const cg = root.querySelector('.hp-gl');
  const tabs = meta ? Array.prototype.slice.call(meta.querySelectorAll('.hp-tab')) : [];
  const cap = meta ? meta.querySelector('.hp-cap') : null;
  const pauseBtn = meta ? meta.querySelector('.hp-pause') : null;
  const page = document.documentElement;

  if (!cp || !ct || !cg) return;

  // Contexts
  const px = cp.getContext('2d');
  const tx = ct.getContext('2d');
  const gx = cg.getContext('2d');
  if (!px || !tx || !gx) return;

  // Reduced motion preference
  const prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Dimensions & scaling
  let W = 0, H = 0, dpr = 1;
  let running = false, paused = prefersReduced;
  let rafId = 0, clock = 0, lastTime = 0;
  let visible = false, pageReady = page.classList.contains('ready');
  let currentSceneIdx = 0;

  // Palette: Midnight Gold kit
  // 0: Royal Blue (#2563EB), 1: Sky (#60A5FA), 2: Bright Sky (#BFDBFE), 3: Metallic Gold (#D4A017)
  const STOPS = [
    [37, 99, 235],
    [96, 165, 250],
    [191, 219, 254],
    [212, 160, 23]
  ];

  function lerp(a, b, t) { return a + (b - a) * t; }
  function clamp(v, min, max) { return Math.max(min, Math.min(max, v)); }
  function easeOutCubic(t) { return 1 - Math.pow(1 - t, 3); }
  function easeInOutCubic(t) { return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; }

  function getPaletteColor(t, alpha) {
    t = clamp(t, 0, 1) * (STOPS.length - 1);
    const i = Math.min(STOPS.length - 2, Math.floor(t));
    const f = t - i;
    const a = STOPS[i];
    const b = STOPS[i + 1];
    const r = Math.round(lerp(a[0], b[0], f));
    const g = Math.round(lerp(a[1], b[1], f));
    const bl = Math.round(lerp(a[2], b[2], f));
    return alpha !== undefined ? `rgba(${r},${g},${bl},${alpha})` : `rgb(${r},${g},${bl})`;
  }

  // --------------------------------------------------------------------------
  // Vector Blueprint Geometry Helpers (coordinates 0..1 relative to stage)
  // --------------------------------------------------------------------------
  function roundedRect(x, y, w, h, r) {
    const pts = [], steps = 5;
    const arc = (cx, cy, start) => {
      for (let i = 0; i <= steps; i++) {
        const a = start + (i / steps) * (Math.PI / 2);
        pts.push([cx + r * Math.cos(a), cy + r * Math.sin(a)]);
      }
    };
    arc(x + w - r, y + r, -Math.PI / 2);
    arc(x + w - r, y + h - r, 0);
    arc(x + r, y + h - r, Math.PI / 2);
    arc(x + r, y + r, Math.PI);
    pts.push(pts[0]);
    return pts;
  }

  function circle(cx, cy, r, count = 24) {
    const pts = [];
    for (let i = 0; i <= count; i++) {
      const a = (i / count) * Math.PI * 2 - Math.PI / 2;
      pts.push([cx + r * Math.cos(a), cy + r * Math.sin(a)]);
    }
    return pts;
  }

  function stroke(pts, width = 0.016) {
    return { pts, width };
  }

  function dot(cx, cy, radius) {
    const pts = [];
    const count = 12;
    for (let i = 0; i <= count; i++) {
      const t = i / count;
      const angle = t * Math.PI * 4;
      const r = radius * t;
      pts.push([cx + r * Math.cos(angle), cy + r * Math.sin(angle)]);
    }
    return { pts, width: radius * 0.85 };
  }

  // --------------------------------------------------------------------------
  // 5 Blueprint Scenes (What Creatsify Architects & Builds)
  // --------------------------------------------------------------------------
  const SCENES = [
    // 1. Web Systems & SaaS (Browser window, header, metrics, 3-card grid)
    {
      name: 'Web Systems & SaaS',
      cap: 'Fast, custom PHP 8 MVC engines, cloud platforms and SaaS architectures.',
      strokes: [
        stroke(roundedRect(0.10, 0.16, 0.80, 0.68, 0.03), 0.018), // Viewport window
        stroke([[0.10, 0.28], [0.90, 0.28]], 0.012),             // Window header divider
        dot(0.16, 0.22, 0.018), dot(0.205, 0.22, 0.018), dot(0.25, 0.22, 0.018), // Traffic light dots
        stroke([[0.34, 0.22], [0.76, 0.22]], 0.016),             // Address pill
        stroke([[0.16, 0.38], [0.55, 0.38]], 0.032),             // Main title
        stroke([[0.16, 0.46], [0.44, 0.46]], 0.020),             // Subtitle
        stroke([[0.16, 0.55], [0.30, 0.55]], 0.046),             // Button pill
        // Speed telemetry waveform
        stroke([[0.58, 0.44], [0.63, 0.40], [0.68, 0.47], [0.73, 0.37], [0.78, 0.45], [0.84, 0.39]], 0.018),
        // 3 Feature Cards
        stroke(roundedRect(0.16, 0.64, 0.19, 0.13, 0.018), 0.014),
        stroke(roundedRect(0.40, 0.64, 0.19, 0.13, 0.018), 0.014),
        stroke(roundedRect(0.64, 0.64, 0.19, 0.13, 0.018), 0.014),
      ]
    },

    // 2. Courier Logistics (Cheetah: Transit nodes, vehicle waypoint, ePOD badge)
    {
      name: 'Courier Logistics',
      cap: 'Cheetah Enterprise multi-branch logistics, rider apps, and live tracking.',
      strokes: [
        stroke(roundedRect(0.12, 0.14, 0.76, 0.72, 0.04), 0.018), // Tablet frame
        circle(0.25, 0.32, 0.05), dot(0.25, 0.32, 0.022),        // Origin Hub Node
        circle(0.50, 0.24, 0.04), dot(0.50, 0.24, 0.018),        // Linehaul Gateway
        circle(0.75, 0.34, 0.05), dot(0.75, 0.34, 0.022),        // Destination Branch
        circle(0.62, 0.58, 0.06), dot(0.62, 0.58, 0.026),        // Active Rider Waypoint
        // Interconnected transit path
        stroke([[0.25, 0.32], [0.50, 0.24], [0.75, 0.34], [0.62, 0.58]], 0.016),
        // Barcode scan lines
        stroke([[0.20, 0.72], [0.20, 0.79]], 0.014),
        stroke([[0.23, 0.72], [0.23, 0.79]], 0.022),
        stroke([[0.27, 0.72], [0.27, 0.79]], 0.014),
        stroke([[0.30, 0.72], [0.30, 0.79]], 0.026),
        stroke([[0.34, 0.72], [0.34, 0.79]], 0.014),
        // ePOD Signed badge
        stroke(roundedRect(0.44, 0.70, 0.42, 0.11, 0.018), 0.014),
        stroke([[0.48, 0.755], [0.52, 0.78], [0.58, 0.72]], 0.024), // Checkmark
        stroke([[0.62, 0.755], [0.81, 0.755]], 0.016),               // Verified text line
      ]
    },

    // 3. Admin Dashboards (Dark console, sidebar rail, 3 KPI cards, bar chart)
    {
      name: 'Admin Dashboards',
      cap: 'Modern dark glassmorphism consoles to manage orders, stock, and telemetry.',
      strokes: [
        stroke(roundedRect(0.08, 0.14, 0.84, 0.72, 0.03), 0.018), // Main console frame
        stroke([[0.26, 0.14], [0.26, 0.86]], 0.014),             // Sidebar divider
        // Sidebar icons
        dot(0.17, 0.24, 0.022), dot(0.17, 0.34, 0.022), dot(0.17, 0.44, 0.022), dot(0.17, 0.54, 0.022), dot(0.17, 0.76, 0.022),
        // 3 KPI metric cards
        stroke(roundedRect(0.31, 0.22, 0.17, 0.14, 0.016), 0.014),
        stroke(roundedRect(0.51, 0.22, 0.17, 0.14, 0.016), 0.014),
        stroke(roundedRect(0.71, 0.22, 0.17, 0.14, 0.016), 0.014),
        // Telemetry bar chart (6 vertical bars)
        stroke([[0.34, 0.78], [0.34, 0.62]], 0.036),
        stroke([[0.41, 0.78], [0.41, 0.52]], 0.036),
        stroke([[0.48, 0.78], [0.48, 0.58]], 0.036),
        stroke([[0.55, 0.78], [0.55, 0.44]], 0.036),
        stroke([[0.62, 0.78], [0.62, 0.48]], 0.036),
        stroke([[0.69, 0.78], [0.69, 0.38]], 0.036),
        // Activity trendline sparkline
        stroke([[0.34, 0.60], [0.41, 0.50], [0.48, 0.56], [0.55, 0.42], [0.62, 0.46], [0.69, 0.35], [0.82, 0.39]], 0.020),
      ]
    },

    // 4. E-Commerce Stores (Cheezify: Storefront, product grid, cart drawer)
    {
      name: 'E-Commerce Stores',
      cap: 'Cheezify and custom WooCommerce stores with cold-chain delivery zones.',
      strokes: [
        stroke(roundedRect(0.14, 0.15, 0.72, 0.70, 0.04), 0.018), // Mobile/Store viewport
        stroke([[0.14, 0.26], [0.86, 0.26]], 0.012),             // Store header
        stroke([[0.20, 0.205], [0.36, 0.205]], 0.018),           // Store logo mark
        circle(0.80, 0.205, 0.022),                               // Cart icon
        // Product 1
        stroke(roundedRect(0.20, 0.32, 0.28, 0.24, 0.02), 0.014),
        stroke([[0.23, 0.47], [0.39, 0.47]], 0.016),
        stroke([[0.23, 0.51], [0.33, 0.51]], 0.022),
        // Product 2
        stroke(roundedRect(0.52, 0.32, 0.28, 0.24, 0.02), 0.014),
        stroke([[0.55, 0.47], [0.71, 0.47]], 0.016),
        stroke([[0.55, 0.51], [0.65, 0.51]], 0.022),
        // Cold-chain delivery zone pills
        stroke(roundedRect(0.20, 0.62, 0.28, 0.06, 0.014), 0.012),
        stroke(roundedRect(0.52, 0.62, 0.28, 0.06, 0.014), 0.012),
        // Instant Checkout button
        stroke(roundedRect(0.20, 0.72, 0.60, 0.08, 0.02), 0.028),
      ]
    },

    // 5. Design Systems & UI (Tokens, swatches, typography, bezier curve)
    {
      name: 'Design Systems & UI',
      cap: 'High-contrast design tokens, Figma component systems, and fluid micro-interactions.',
      strokes: [
        // 4 Color Swatch circles: Midnight Navy, Royal Blue, Sky Blue, Metallic Gold
        circle(0.22, 0.28, 0.06), dot(0.22, 0.28, 0.03),
        circle(0.40, 0.28, 0.06), dot(0.40, 0.28, 0.03),
        circle(0.58, 0.28, 0.06), dot(0.58, 0.28, 0.03),
        circle(0.76, 0.28, 0.06), dot(0.76, 0.28, 0.03),
        // Typography hierarchy scale lines
        stroke([[0.20, 0.42], [0.80, 0.42]], 0.034),
        stroke([[0.20, 0.49], [0.68, 0.49]], 0.024),
        stroke([[0.20, 0.55], [0.56, 0.55]], 0.018),
        // Cubic-Bezier Easing motion diagram
        stroke([[0.20, 0.78], [0.80, 0.78]], 0.012), // Baseline axis
        stroke([[0.20, 0.78], [0.20, 0.62]], 0.012), // Vertical axis
        // S-curve ease
        stroke([[0.20, 0.78], [0.35, 0.77], [0.45, 0.64], [0.65, 0.63], [0.80, 0.62]], 0.020),
        dot(0.35, 0.77, 0.016), dot(0.65, 0.63, 0.016), // Control handles
      ]
    }
  ];

  // Precompute stroke lengths & timeline
  const DRAW_DURATION = 4.2;
  const LIFT_DURATION = 0.14;
  const HOLD_DURATION = 2.0;
  const FADE_DURATION = 0.5;
  const LEAD_DURATION = 0.65;

  SCENES.forEach((scene) => {
    let totalLen = 0;
    const n = scene.strokes.length;
    scene.strokes.forEach((s) => {
      s.cum = [0];
      for (let i = 1; i < s.pts.length; i++) {
        const d = Math.hypot(s.pts[i][0] - s.pts[i - 1][0], s.pts[i][1] - s.pts[i - 1][1]);
        s.cum.push(s.cum[i - 1] + d);
      }
      s.len = s.cum[s.cum.length - 1] || 0.005;
      totalLen += s.len;
    });

    const speed = totalLen / (DRAW_DURATION - LIFT_DURATION * (n - 1));
    let t = 0;
    scene.strokes.forEach((s, i) => {
      s.t0 = t;
      s.dur = Math.max(0.12, s.len / speed);
      t += s.dur;
      s.t1 = t;
      t += LIFT_DURATION;
      s.c0 = i / n;
      s.c1 = (i + 1) / n;
    });
    scene.dur = t - LIFT_DURATION;
  });

  // Animation state
  let drawnProg = [];
  let animState = { phase: 'lead', t: 0, from: { x: 0, y: 0 } };
  let currentTip = { x: 0, y: 0, vx: 0, vy: 0 };

  // 3D Emblem tilt & physics (spring damping from Emil Kowalski motion skill)
  let emblemTilt = { x: 0, y: 0, targetX: 0, targetY: 0 };
  let pointerOver = false;

  // Cursor particle trail
  const trailParticles = [];

  // Point interpolation helper
  function getPointOnStroke(s, f) {
    const targetDist = f * s.len;
    let i = 1;
    while (i < s.cum.length - 1 && s.cum[i] < targetDist) i++;
    const p0 = s.pts[i - 1];
    const p1 = s.pts[i];
    const segLen = s.cum[i] - s.cum[i - 1] || 1;
    const u = (targetDist - s.cum[i - 1]) / segLen;
    return {
      x: (p0[0] + (p1[0] - p0[0]) * u) * W,
      y: (p0[1] + (p1[1] - p0[1]) * u) * H
    };
  }

  function getFirstPoint() {
    return getPointOnStroke(SCENES[currentSceneIdx].strokes[0], 0);
  }

  function getLastPoint() {
    const ss = SCENES[currentSceneIdx].strokes;
    return getPointOnStroke(ss[ss.length - 1], 1);
  }

  // --------------------------------------------------------------------------
  // Drawing: Laser & Vector Strokes
  // --------------------------------------------------------------------------
  function drawSegment(ctx, a, b, colorT, strokeW, alpha, withGlow) {
    const dx = b.x - a.x;
    const dy = b.y - a.y;
    const len = Math.hypot(dx, dy) || 1;
    const nx = -dy / len;
    const ny = dx / len;
    const col = getPaletteColor(colorT);

    ctx.save();
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    if (withGlow) {
      ctx.shadowColor = col;
      ctx.shadowBlur = Math.min(strokeW * 1.8, 16);
    }

    // Double-pass neon laser stroke: core bright + outer glow
    ctx.beginPath();
    ctx.strokeStyle = col;
    ctx.globalAlpha = alpha * 0.95;
    ctx.lineWidth = Math.max(1.8, strokeW);
    ctx.moveTo(a.x, a.y);
    ctx.lineTo(b.x, b.y);
    ctx.stroke();

    // Inner laser core (white-hot center)
    ctx.beginPath();
    ctx.strokeStyle = '#FFFFFF';
    ctx.globalAlpha = alpha * 0.75;
    ctx.lineWidth = Math.max(1.0, strokeW * 0.4);
    ctx.moveTo(a.x, a.y);
    ctx.lineTo(b.x, b.y);
    ctx.stroke();

    ctx.restore();
  }

  function renderStrokeProgress(s, fromF, toF) {
    const strokePx = s.width * W;
    const steps = Math.max(1, Math.ceil((toF - fromF) * s.len * W / 6));
    let prev = getPointOnStroke(s, fromF);

    for (let i = 1; i <= steps; i++) {
      const f = fromF + ((toF - fromF) * i) / steps;
      const cur = getPointOnStroke(s, f);
      const colorT = s.c0 + (s.c1 - s.c0) * f;
      drawSegment(px, prev, cur, colorT, strokePx, 1.0, strokePx >= 10);
      prev = cur;
    }
  }

  function repaintCanvas() {
    px.clearRect(0, 0, W, H);
    SCENES[currentSceneIdx].strokes.forEach((s, idx) => {
      if (drawnProg[idx] > 0) {
        renderStrokeProgress(s, 0, drawnProg[idx]);
      }
    });
  }

  // --------------------------------------------------------------------------
  // The 3D Sculpted Obsidian & Gold "C." Monolith Emblem
  // --------------------------------------------------------------------------
  function render3DEmblem(x, y, vx, vy, time) {
    gx.clearRect(0, 0, W, H);

    // Dynamic levitation and mouse tilt
    const floatY = Math.sin(time * 2.0) * 4;
    const floatRot = Math.sin(time * 1.5) * 0.04;

    // Dimensions of the 3D "C." block
    const baseSize = W < 460 ? 76 : 94;
    const ex = x;
    const ey = y + floatY;

    gx.save();
    gx.translate(ex, ey);

    // Apply 3D perspective rotation matrix
    const rotX = emblemTilt.y * 0.35;
    const rotY = emblemTilt.x * 0.35 + floatRot;
    gx.transform(
      Math.cos(rotY), Math.sin(rotX) * 0.3,
      -Math.sin(rotX) * 0.3, Math.cos(rotX),
      0, 0
    );

    // Ambient drop shadow (Obsidian glass refraction)
    gx.save();
    gx.shadowColor = 'rgba(10, 26, 51, 0.65)';
    gx.shadowBlur = 24;
    gx.shadowOffsetY = 14;

    // 1. Monolith Obsidian Crystal Base (Rounded Squircle)
    const r = baseSize * 0.28;
    const bHalf = baseSize * 0.5;

    // Dark obsidian glass gradient
    const obsGrad = gx.createLinearGradient(-bHalf, -bHalf, bHalf, bHalf);
    obsGrad.addColorStop(0, '#1E2D4A');
    obsGrad.addColorStop(0.35, '#0F1F3D');
    obsGrad.addColorStop(0.7, '#0A1A33');
    obsGrad.addColorStop(1, '#050D1A');

    gx.beginPath();
    gx.roundRect(-bHalf, -bHalf, baseSize, baseSize, r);
    gx.fillStyle = obsGrad;
    gx.fill();
    gx.restore();

    // 2. Crystal Bevel Edge & Rim Specular Highlight
    gx.save();
    const rimGrad = gx.createLinearGradient(-bHalf, -bHalf, bHalf, bHalf);
    rimGrad.addColorStop(0, 'rgba(96, 165, 250, 0.65)'); // Sky blue glint
    rimGrad.addColorStop(0.5, 'rgba(37, 99, 235, 0.25)'); // Royal blue depth
    rimGrad.addColorStop(0.85, 'rgba(212, 160, 23, 0.7)'); // Gold reflection edge
    rimGrad.addColorStop(1, 'rgba(255, 255, 255, 0.4)');
    gx.beginPath();
    gx.roundRect(-bHalf + 1, -bHalf + 1, baseSize - 2, baseSize - 2, r - 1);
    gx.strokeStyle = rimGrad;
    gx.lineWidth = 1.8;
    gx.stroke();
    gx.restore();

    // 3. Sculpted "C" Geometry (Deep Channel with royal-to-sky gradient)
    gx.save();
    gx.beginPath();
    const cRadius = baseSize * 0.27;
    const cWidth = baseSize * 0.16;
    // Outer arc of the "C"
    gx.arc(0, 0, cRadius + cWidth * 0.5, 0.25 * Math.PI, 1.75 * Math.PI, false);
    // Inner return arc
    gx.arc(0, 0, cRadius - cWidth * 0.5, 1.75 * Math.PI, 0.25 * Math.PI, true);
    gx.closePath();

    const cGrad = gx.createLinearGradient(-bHalf * 0.6, -bHalf * 0.6, bHalf * 0.6, bHalf * 0.6);
    cGrad.addColorStop(0, '#60A5FA'); // Sky
    cGrad.addColorStop(0.5, '#2563EB'); // Royal Blue
    cGrad.addColorStop(1, '#1D4ED8');
    gx.fillStyle = cGrad;
    gx.shadowColor = 'rgba(37, 99, 235, 0.6)';
    gx.shadowBlur = 12;
    gx.fill();
    gx.restore();

    // 4. The Radiant Metallic Gold Dot (".")
    // Located at the lower-right terminal of the "C" emblem
    const dotX = bHalf * 0.42;
    const dotY = bHalf * 0.42;
    const dotR = baseSize * 0.125;

    gx.save();
    // Golden atmospheric aura
    const goldAura = gx.createRadialGradient(dotX, dotY, 0, dotX, dotY, dotR * 3.2);
    goldAura.addColorStop(0, 'rgba(212, 160, 23, 0.65)');
    goldAura.addColorStop(0.5, 'rgba(212, 160, 23, 0.2)');
    goldAura.addColorStop(1, 'rgba(212, 160, 23, 0)');
    gx.fillStyle = goldAura;
    gx.beginPath();
    gx.arc(dotX, dotY, dotR * 3.2, 0, Math.PI * 2);
    gx.fill();

    // Metallic Gold Sphere
    const goldGrad = gx.createRadialGradient(
      dotX - dotR * 0.35, dotY - dotR * 0.35, dotR * 0.1,
      dotX, dotY, dotR
    );
    goldGrad.addColorStop(0, '#FFFBEB'); // Specular highlight
    goldGrad.addColorStop(0.25, '#FDE047'); // Light Gold
    goldGrad.addColorStop(0.65, '#D4A017'); // Rich Brand Gold
    goldGrad.addColorStop(1, '#92400E');   // Deep amber gold shadow
    gx.fillStyle = goldGrad;
    gx.beginPath();
    gx.arc(dotX, dotY, dotR, 0, Math.PI * 2);
    gx.shadowColor = '#D4A017';
    gx.shadowBlur = 16;
    gx.fill();
    gx.restore();

    gx.restore(); // Exit 3D emblem transform

    // 5. Holographic Construction Laser Beam
    // Casts a dynamic beam from the Gold Dot down to the active drawing coordinates
    const laserOriginX = ex + dotX * Math.cos(rotY);
    const laserOriginY = ey + dotY * Math.cos(rotX);

    gx.save();
    const beamGrad = gx.createLinearGradient(laserOriginX, laserOriginY, currentTip.x, currentTip.y);
    beamGrad.addColorStop(0, 'rgba(212, 160, 23, 0.9)');
    beamGrad.addColorStop(0.3, 'rgba(96, 165, 250, 0.7)');
    beamGrad.addColorStop(0.8, 'rgba(37, 99, 235, 0.6)');
    beamGrad.addColorStop(1, 'rgba(255, 255, 255, 0.95)');

    gx.beginPath();
    gx.moveTo(laserOriginX, laserOriginY);
    gx.lineTo(currentTip.x, currentTip.y);
    gx.strokeStyle = beamGrad;
    gx.lineWidth = 2.2;
    gx.shadowColor = '#60A5FA';
    gx.shadowBlur = 14;
    gx.stroke();

    // Impact Laser Flare on the Drawing Surface
    const flareGrad = gx.createRadialGradient(currentTip.x, currentTip.y, 0, currentTip.x, currentTip.y, 16);
    flareGrad.addColorStop(0, '#FFFFFF');
    flareGrad.addColorStop(0.4, 'rgba(96, 165, 250, 0.8)');
    flareGrad.addColorStop(0.8, 'rgba(212, 160, 23, 0.4)');
    flareGrad.addColorStop(1, 'rgba(37, 99, 235, 0)');
    gx.fillStyle = flareGrad;
    gx.beginPath();
    gx.arc(currentTip.x, currentTip.y, 16, 0, Math.PI * 2);
    gx.fill();

    gx.restore();
  }

  // --------------------------------------------------------------------------
  // Interactive Pointer Particle Trail (Midnight Gold Sparks)
  // --------------------------------------------------------------------------
  function addTrailParticle(x, y) {
    if (trailParticles.length > 90) trailParticles.shift();
    const isGold = Math.random() > 0.4;
    trailParticles.push({
      x: x + (Math.random() - 0.5) * 6,
      y: y + (Math.random() - 0.5) * 6,
      vx: (Math.random() - 0.5) * 45,
      vy: (Math.random() - 0.5) * 45 - 15,
      radius: Math.random() * 2.8 + 1.2,
      color: isGold ? [212, 160, 23] : [96, 165, 250],
      alpha: 1.0,
      born: performance.now(),
      life: 1100 + Math.random() * 400
    });
  }

  function renderTrailParticles(now) {
    tx.clearRect(0, 0, W, H);
    for (let i = trailParticles.length - 1; i >= 0; i--) {
      const p = trailParticles[i];
      const age = now - p.born;
      if (age >= p.life) {
        trailParticles.splice(i, 1);
        continue;
      }
      const progress = age / p.life;
      p.x += p.vx * 0.016;
      p.y += p.vy * 0.016;
      p.vy += 22 * 0.016; // Mild gravity drift
      const currentR = p.radius * (1 - progress * 0.6);
      const currentA = (1 - progress) * 0.85;

      tx.save();
      tx.beginPath();
      tx.arc(p.x, p.y, currentR, 0, Math.PI * 2);
      tx.fillStyle = `rgba(${p.color[0]}, ${p.color[1]}, ${p.color[2]}, ${currentA})`;
      tx.shadowColor = `rgba(${p.color[0]}, ${p.color[1]}, ${p.color[2]}, 0.6)`;
      tx.shadowBlur = 6;
      tx.fill();
      tx.restore();
    }
  }

  // --------------------------------------------------------------------------
  // Scene Navigation & Animation Sequencing
  // --------------------------------------------------------------------------
  function setScene(idx) {
    currentSceneIdx = idx;
    drawnProg = SCENES[currentSceneIdx].strokes.map(() => 0);
    px.clearRect(0, 0, W, H);
    cp.style.opacity = '1';

    tabs.forEach((tab, k) => {
      tab.classList.toggle('on', k === idx);
      tab.setAttribute('aria-selected', k === idx ? 'true' : 'false');
      tab.style.setProperty('--p', '0');
    });

    if (cap && tabs[idx]) {
      cap.textContent = tabs[idx].dataset.cap || SCENES[idx].cap;
    }
  }

  function jumpToScene(idx) {
    setScene(idx);
    if (prefersReduced || paused) {
      drawnProg = SCENES[currentSceneIdx].strokes.map(() => 1);
      repaintCanvas();
      const end = getLastPoint();
      currentTip.x = end.x;
      currentTip.y = end.y;
      render3DEmblem(W * 0.76, H * 0.24, 0, 0, 0);
    } else {
      animState = { phase: 'lead', t: 0, from: { x: currentTip.x, y: currentTip.y } };
      if (!running) startLoop();
    }
  }

  function advanceDrawing(scene, t) {
    const ss = scene.strokes;
    let pointCaptured = false;

    for (let i = 0; i < ss.length; i++) {
      const s = ss[i];
      const frac = clamp((t - s.t0) / s.dur, 0, 1);

      if (frac > drawnProg[i]) {
        renderStrokeProgress(s, drawnProg[i], frac);
        drawnProg[i] = frac;
      }

      if (!pointCaptured) {
        if (t < s.t0) {
          const prevStroke = i ? ss[i - 1] : null;
          const startP = prevStroke ? getPointOnStroke(prevStroke, 1) : getPointOnStroke(s, 0);
          const destP = getPointOnStroke(s, 0);
          const u = easeInOutCubic(clamp((t - (prevStroke ? prevStroke.t1 : 0)) / LIFT_DURATION, 0, 1));
          currentTip.x = startP.x + (destP.x - startP.x) * u;
          currentTip.y = startP.y + (destP.y - startP.y) * u;
          pointCaptured = true;
        } else if (t <= s.t1) {
          const pt = getPointOnStroke(s, frac);
          currentTip.x = pt.x;
          currentTip.y = pt.y;
          pointCaptured = true;
        }
      }
    }

    if (!pointCaptured) {
      const end = getPointOnStroke(ss[ss.length - 1], 1);
      currentTip.x = end.x;
      currentTip.y = end.y;
    }
  }

  // --------------------------------------------------------------------------
  // Main Animation Loop
  // --------------------------------------------------------------------------
  function onFrame(timestamp) {
    if (!running) return;
    rafId = 0;

    const dt = Math.min((timestamp - (lastTime || timestamp)), 50) / 1000;
    clock += dt;
    lastTime = timestamp;

    // Smooth spring tilt towards target
    emblemTilt.x += (emblemTilt.targetX - emblemTilt.x) * 0.08;
    emblemTilt.y += (emblemTilt.targetY - emblemTilt.y) * 0.08;

    const prevX = currentTip.x;
    const prevY = currentTip.y;
    const scene = SCENES[currentSceneIdx];
    animState.t += dt;

    let totalProgress = 0;
    const totalCycle = LEAD_DURATION + scene.dur + HOLD_DURATION + FADE_DURATION;

    // Phase machine: lead -> draw -> hold -> fade -> next scene
    if (animState.phase === 'lead') {
      const u = easeOutCubic(clamp(animState.t / LEAD_DURATION, 0, 1));
      const target = getFirstPoint();
      currentTip.x = animState.from.x + (target.x - animState.from.x) * u;
      currentTip.y = animState.from.y + (target.y - animState.from.y) * u;
      totalProgress = animState.t;
      if (animState.t >= LEAD_DURATION) {
        animState.phase = 'draw';
        animState.t = 0;
      }
    } else if (animState.phase === 'draw') {
      advanceDrawing(scene, animState.t);
      totalProgress = LEAD_DURATION + animState.t;
      if (animState.t >= scene.dur) {
        animState.phase = 'hold';
        animState.t = 0;
      }
    } else if (animState.phase === 'hold') {
      const u = easeOutCubic(clamp(animState.t / 0.6, 0, 1));
      const end = getLastPoint();
      currentTip.x = end.x + u * W * 0.03;
      currentTip.y = end.y - u * H * 0.04 + Math.sin(clock * 1.8) * 2;
      totalProgress = LEAD_DURATION + scene.dur + animState.t;
      if (animState.t >= HOLD_DURATION) {
        animState.phase = 'fade';
        animState.t = 0;
      }
    } else { // fade phase
      const fadeFrac = clamp(animState.t / FADE_DURATION, 0, 1);
      cp.style.opacity = (1 - fadeFrac).toFixed(2);
      currentTip.x += W * 0.18 * dt;
      currentTip.y -= H * 0.10 * dt;
      totalProgress = LEAD_DURATION + scene.dur + HOLD_DURATION + animState.t;
      if (fadeFrac >= 1) {
        setScene((currentSceneIdx + 1) % SCENES.length);
        animState = { phase: 'lead', t: 0, from: { x: currentTip.x, y: currentTip.y } };
      }
    }

    // Update active tab progress bar
    if (tabs[currentSceneIdx]) {
      const pVal = clamp(totalProgress / totalCycle, 0, 1);
      tabs[currentSceneIdx].style.setProperty('--p', pVal.toFixed(3));
    }

    currentTip.vx = (currentTip.x - prevX) / Math.max(dt, 0.001);
    currentTip.vy = (currentTip.y - prevY) / Math.max(dt, 0.001);

    // Render 3D Monolith Emblem hovering above the blueprint
    // Place emblem gracefully in top-right or offset from active tip
    const emblemAnchorX = clamp(currentTip.x + W * 0.16, W * 0.35, W * 0.82);
    const emblemAnchorY = clamp(currentTip.y - H * 0.22, H * 0.18, H * 0.65);
    render3DEmblem(emblemAnchorX, emblemAnchorY, currentTip.vx, currentTip.vy, clock);

    // Render interactive cursor sparks
    renderTrailParticles(timestamp);

    rafId = requestAnimationFrame(onFrame);
  }

  function startLoop() {
    if (running || paused || !visible || !pageReady || document.hidden) return;
    running = true;
    lastTime = 0;
    rafId = requestAnimationFrame(onFrame);
  }

  function stopLoop() {
    running = false;
    if (rafId) cancelAnimationFrame(rafId);
    rafId = 0;
  }

  function resizeStage() {
    W = root.clientWidth;
    H = root.clientHeight;
    if (!W || !H) return;

    dpr = Math.min(window.devicePixelRatio || 1, 2);
    [cp, ct, cg].forEach((canvas) => {
      canvas.width = W * dpr;
      canvas.height = H * dpr;
    });

    px.setTransform(dpr, 0, 0, dpr, 0, 0);
    tx.setTransform(dpr, 0, 0, dpr, 0, 0);
    gx.setTransform(dpr, 0, 0, dpr, 0, 0);

    repaintCanvas();
  }

  // --------------------------------------------------------------------------
  // Initialization & Event Listeners
  // --------------------------------------------------------------------------
  function initialize() {
    resizeStage();
    setScene(0);
    root.classList.add('ready');

    if (prefersReduced) {
      drawnProg = SCENES[0].strokes.map(() => 1);
      repaintCanvas();
      const end = getLastPoint();
      currentTip.x = end.x;
      currentTip.y = end.y;
      render3DEmblem(W * 0.76, H * 0.24, 0, 0, 0);
    } else {
      currentTip.x = W * 1.05;
      currentTip.y = -H * 0.1;
      animState = { phase: 'lead', t: 0, from: { x: currentTip.x, y: currentTip.y } };
      startLoop();
    }
  }

  // Wait for loading screen to complete
  if (!pageReady) {
    const observer = new MutationObserver(() => {
      if (page.classList.contains('ready')) {
        observer.disconnect();
        setTimeout(() => {
          pageReady = true;
          startLoop();
        }, 300);
      }
    });
    observer.observe(page, { attributes: true, attributeFilter: ['class'] });
    setTimeout(() => {
      if (!pageReady) {
        pageReady = true;
        startLoop();
      }
    }, 6000);
  }

  // Visibility & Intersection Observers
  new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      visible = entry.isIntersecting;
      if (visible) startLoop();
      else stopLoop();
    });
  }, { threshold: 0.1 }).observe(root);

  new ResizeObserver(resizeStage).observe(root);
  document.addEventListener('visibilitychange', () => (document.hidden ? stopLoop() : startLoop()));

  // Tab click listeners
  tabs.forEach((tab, idx) => {
    tab.addEventListener('click', () => jumpToScene(idx));
  });

  // Play/Pause button
  if (pauseBtn) {
    pauseBtn.addEventListener('click', () => {
      paused = !paused;
      pauseBtn.setAttribute('aria-pressed', paused ? 'true' : 'false');
      pauseBtn.setAttribute('aria-label', paused ? 'Play animation' : 'Pause animation');
      if (paused) stopLoop();
      else startLoop();
    });
  }

  // Interactive mouse pointer tracking & spark emitter
  root.addEventListener('pointermove', (e) => {
    pointerOver = true;
    const rect = root.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;

    // Normalize coordinates (-1 to 1) for 3D spring tilt
    emblemTilt.targetX = clamp((x / W) * 2 - 1, -1, 1);
    emblemTilt.targetY = clamp((y / H) * 2 - 1, -1, 1);

    // Emit golden & royal blue sparks on pointer movement
    addTrailParticle(x, y);
  }, { passive: true });

  root.addEventListener('pointerleave', () => {
    pointerOver = false;
    emblemTilt.targetX = 0;
    emblemTilt.targetY = 0;
  });

  // Start initialization
  initialize();
})();
