/**
 * Creatsify hero: a 3D paintbrush paints what we make, one scene at a time (website, mobile app, admin panel, design, ad).
 * Build: npm i three esbuild && npx esbuild tools/hero-paint/hero-paint.src.js --bundle --minify --format=iife --target=es2019 --outfile=public/assets/js/hero-paint.js
 * Layers inside #hp: .hp-paint (2D, the painting), .hp-trail (2D, cursor trail), .hp-gl (WebGL, the brush).
 * #hpMeta holds the caption and the scene tabs. If WebGL is missing or the user saves data, the .hp-poster image is shown instead.
 */
import { WebGLRenderer, OrthographicCamera, Scene, Group, Box3, Vector3, PMREMGenerator, DirectionalLight } from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { MeshoptDecoder } from 'three/examples/jsm/libs/meshopt_decoder.module.js';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';

(function () {
  const root = document.getElementById('hp'), meta = document.getElementById('hpMeta');
  if (!root) return;
  const cp = root.querySelector('.hp-paint'), ct = root.querySelector('.hp-trail'), cg = root.querySelector('.hp-gl');
  const tabs = meta ? [].slice.call(meta.querySelectorAll('.hp-tab')) : [], cap = meta && meta.querySelector('.hp-cap');
  const modelUrl = root.dataset.model, page = document.documentElement;
  const fallback = () => root.classList.add('fallback');
  const conn = navigator.connection || {};
  let glOk = false;
  try { const c = document.createElement('canvas'); glOk = !!(c.getContext('webgl2') || c.getContext('webgl')); } catch (e) {}
  const spriteUrl = root.dataset.sprite, tipAttr = (root.dataset.tip || '').split(',').map(Number);
  const mode = (m) => { root.dataset.mode = m; if (window.console && console.info) console.info('[hero-paint] mode:', m); };
  if (!cp || !ct || !cg || !modelUrl || conn.saveData) { mode('poster'); return fallback(); }

  // The painting always plays, like the rest of the site's motion. The pause button is the way to stop it.
  const STATIC = root.dataset.static === '1';
  const EXT = { l: 0.22, r: 0.30, t: 0.24, b: 0.04 };   // the WebGL canvas is larger than the stage so the handle is never cut off
  const STOPS = [[123, 47, 247], [59, 107, 255], [20, 200, 255], [255, 60, 172], [255, 154, 46]];   // logo palette
  const DRAWT = 4.2, LIFT = 0.12, HOLD = 2.0, FADE = 0.5, LEAD = 0.7;
  const clamp = (v, a, b) => Math.max(a, Math.min(b, v));
  const ease = (x) => (x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2);
  const easeOut = (x) => 1 - Math.pow(1 - x, 3);
  const pal = (t) => { t = clamp(t, 0, 1) * (STOPS.length - 1); const i = Math.min(STOPS.length - 2, Math.floor(t)), f = t - i, a = STOPS[i], b = STOPS[i + 1]; return [a[0] + (b[0] - a[0]) * f | 0, a[1] + (b[1] - a[1]) * f | 0, a[2] + (b[2] - a[2]) * f | 0]; };

  // ---- the scenes (coordinates are 0..1 of the stage) ----
  const rr = (x, y, w, h, r) => { const p = [], n = 5, a = (cx, cy, s) => { for (let i = 0; i <= n; i++) { const t = s + i / n * Math.PI / 2; p.push([cx + r * Math.cos(t), cy + r * Math.sin(t)]); } }; a(x + w - r, y + r, -Math.PI / 2); a(x + w - r, y + h - r, 0); a(x + r, y + h - r, Math.PI / 2); a(x + r, y + r, Math.PI); p.push(p[0]); return p; };
  const ci = (cx, cy, r) => Array.from({ length: 25 }, (_, i) => [cx + r * Math.cos(i / 24 * 6.2832 - 1.5708), cy + r * Math.sin(i / 24 * 6.2832 - 1.5708)]);
  const fl = (x, y, w, h, st) => { const p = []; let d = 1; for (let yy = y; yy <= y + h + 1e-6; yy += st) { p.push(d > 0 ? [x, yy] : [x + w, yy], d > 0 ? [x + w, yy] : [x, yy]); d = -d; } return p; };
  const dot = (x, y, w) => ({ pts: Array.from({ length: 13 }, (_, i) => { const t = i / 12, an = t * Math.PI * 4, r = w * 0.3 * t; return [x + r * Math.cos(an), y + r * Math.sin(an)]; }), w: w * 0.42 });   // a round blob, painted as a spiral
  const S = (pts, w) => ({ pts, w });
  const SCENES = [
    { range: [0, 0.9], strokes: [   // website
      S(rr(.11, .2, .78, .6, .03), .016), S([[.11, .31], [.89, .31]], .012), dot(.17, .255, .022), dot(.215, .255, .022), dot(.26, .255, .022),
      S([[.18, .42], [.5, .42]], .034), S([[.18, .5], [.42, .5]], .024), S([[.18, .6], [.3, .6]], .058), S(fl(.58, .38, .22, .16, .04), .045),
      S(rr(.18, .68, .19, .09, .015), .012), S(rr(.4, .68, .19, .09, .015), .012), S(rr(.62, .68, .19, .09, .015), .012)] },
    { range: [0.1, 1], strokes: [   // mobile app
      S(rr(.31, .08, .38, .84, .06), .018), S([[.44, .14], [.56, .14]], .014), S(ci(.5, .29, .07), .018), S([[.39, .43], [.61, .43]], .026), S([[.39, .49], [.54, .49]], .018),
      S(rr(.37, .56, .26, .08, .016), .012), S(rr(.37, .67, .26, .08, .016), .012), dot(.4, .86, .03), dot(.495, .86, .03), dot(.595, .86, .03)] },
    { range: [0, 1], strokes: [   // admin panel
      S(rr(.09, .18, .82, .64, .03), .016), S([[.27, .26], [.27, .74]], .012), S([[.14, .3], [.22, .3]], .024), S([[.14, .39], [.22, .39]], .024), S([[.14, .48], [.22, .48]], .024),
      S(rr(.32, .25, .16, .1, .015), .012), S(rr(.5, .25, .16, .1, .015), .012), S(rr(.68, .25, .16, .1, .015), .012),
      S([[.34, .72], [.34, .58]], .04), S([[.41, .72], [.41, .5]], .04), S([[.48, .72], [.48, .55]], .04), S([[.55, .72], [.55, .44]], .04),
      S([[.62, .66], [.68, .58], [.73, .62], [.79, .5], [.85, .54]], .02)] },
    { range: [0.3, 1], strokes: [   // brand and design
      S(ci(.3, .32, .13), .02), S([[.3, .22], [.38, .37], [.22, .37], [.3, .22]], .018), S([[.52, .28], [.8, .28]], .04), S([[.52, .37], [.7, .37]], .024),
      dot(.24, .57, .08), dot(.4, .57, .08), dot(.56, .57, .08), dot(.72, .57, .08),
      S([[.15, .84], [.3, .7], [.5, .86], [.7, .72], [.85, .8]], .014), dot(.15, .84, .034), dot(.85, .8, .034)] },
    { range: [0.45, 1], strokes: [   // ad creative
      S(rr(.2, .12, .6, .76, .04), .016), S(rr(.26, .18, .48, .28, .02), .012), S(ci(.37, .28, .045), .016), S([[.28, .44], [.42, .33], [.52, .4], [.62, .3], [.72, .44]], .016),
      S([[.26, .55], [.7, .55]], .034), S([[.26, .62], [.58, .62]], .024), S([[.28, .76], [.48, .76]], .06),
      S([[.6, .78], [.66, .72], [.7, .75], [.76, .66]], .018), S([[.7, .66], [.76, .66], [.76, .72]], .018)] },
  ];
  SCENES.forEach((sc) => {   // lengths, timeline and colours
    let total = 0; const ss = sc.strokes, n = ss.length;
    ss.forEach((s) => { s.cum = [0]; for (let i = 1; i < s.pts.length; i++) s.cum.push(s.cum[i - 1] + Math.hypot(s.pts[i][0] - s.pts[i - 1][0], s.pts[i][1] - s.pts[i - 1][1])); s.len = s.cum[s.cum.length - 1] || 0.004; total += s.len; });
    const speed = total / (DRAWT - LIFT * (n - 1)); let t = 0;
    ss.forEach((s, i) => { s.t0 = t; s.dur = Math.max(0.1, s.len / speed); t += s.dur; s.t1 = t; t += LIFT; s.c0 = sc.range[0] + (sc.range[1] - sc.range[0]) * i / n; s.c1 = sc.range[0] + (sc.range[1] - sc.range[0]) * (i + 1) / n; });
    sc.dur = t - LIFT;
  });

  let W = 0, H = 0, dpr = 1, glW = 0, glH = 0, ox = 0, oy = 0, K = 1;
  const px = cp.getContext('2d'), tx = ct.getContext('2d');
  let renderer, camera, scene, holder, cs, sx, spr, useSprite = false, paused = false, sp = { dz: 0 }, loaded = false, visible = false, pageOk = page.classList.contains('ready'), running = false, raf = 0, started = false;
  let clock = 0, last = 0, si = 0, drawn = [], st = { ph: 'lead', t: 0, from: { x: 0, y: 0 } }, trail = [], trailT = 0, touched = false;
  const tip = { x: 0, y: 0, vx: 0, vy: 0 };

  const P = (s, f) => { const d = f * s.len; let i = 1; while (i < s.cum.length - 1 && s.cum[i] < d) i++; const a = s.pts[i - 1], b = s.pts[i], u = (d - s.cum[i - 1]) / ((s.cum[i] - s.cum[i - 1]) || 1); return { x: (a[0] + (b[0] - a[0]) * u) * W, y: (a[1] + (b[1] - a[1]) * u) * H }; };
  const startPt = () => P(SCENES[si].strokes[0], 0);
  const endPt = () => { const ss = SCENES[si].strokes; return P(ss[ss.length - 1], 1); };

  // One bristly brush-stroke segment
  function seg(ctx, a, b, t, w, n, alpha, glow) {
    const dx = b.x - a.x, dy = b.y - a.y, L = Math.hypot(dx, dy) || 1, nx = -dy / L, ny = dx / L, c0 = pal(t);
    ctx.lineCap = 'round';
    for (let k = 0; k < n; k++) {
      const c = n > 1 ? k / (n - 1) - 0.5 : 0, o = c * w, mix = ((k * 37) % 7) / 7 * 0.18;
      ctx.strokeStyle = `rgba(${c0[0] + (255 - c0[0]) * mix | 0},${c0[1] + (255 - c0[1]) * mix | 0},${c0[2] + (255 - c0[2]) * mix | 0},${alpha * (0.92 - Math.abs(c) * 0.3)})`;
      ctx.lineWidth = Math.max(1.2, w / n * 1.9);
      if (glow && k === (n >> 1)) { ctx.shadowColor = `rgba(${c0[0]},${c0[1]},${c0[2]},${0.5 * alpha})`; ctx.shadowBlur = w * 0.5; } else ctx.shadowBlur = 0;
      ctx.beginPath(); ctx.moveTo(a.x + nx * o, a.y + ny * o); ctx.lineTo(b.x + nx * o, b.y + ny * o); ctx.stroke();
    }
    ctx.shadowBlur = 0;
  }
  function paintStroke(s, f0, f1) {
    const wpx = s.w * W, n = wpx >= 22 ? 7 : wpx >= 11 ? 5 : 3, steps = Math.max(1, Math.ceil((f1 - f0) * s.len * W / 5));
    let prev = P(s, f0);
    for (let i = 1; i <= steps; i++) { const f = f0 + (f1 - f0) * i / steps, cur = P(s, f); seg(px, prev, cur, s.c0 + (s.c1 - s.c0) * f, wpx * (0.75 + 0.25 * Math.sin(Math.PI * f)), n, 1, wpx >= 14); prev = cur; }
  }
  const repaint = () => { px.clearRect(0, 0, W, H); SCENES[si].strokes.forEach((s, i) => { if (drawn[i] > 0) paintStroke(s, 0, drawn[i]); }); };
  const paintAll = () => { drawn = SCENES[si].strokes.map(() => 1); repaint(); };

  function setScene(i) {
    si = i; drawn = SCENES[si].strokes.map(() => 0);
    px.clearRect(0, 0, W, H); cp.style.opacity = '';
    tabs.forEach((b, k) => { b.classList.toggle('on', k === i); b.setAttribute('aria-selected', k === i ? 'true' : 'false'); b.style.setProperty('--p', 0); });
    if (cap && tabs[i]) cap.textContent = tabs[i].dataset.cap || '';
  }
  function jump(i) {
    if (!loaded) return;
    setScene(i);
    if (STATIC || paused) { paintAll(); const e = endPt(); tip.x = e.x; tip.y = e.y; pose(0, 0, 0); draw(); }
    else { st = { ph: 'lead', t: 0, from: { x: tip.x, y: tip.y } }; if (!running) play(); }
  }

  function fit() {
    W = root.clientWidth; H = root.clientHeight;
    if (!W || !H) return;
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    [cp, ct].forEach((c) => { c.width = W * dpr; c.height = H * dpr; });
    px.setTransform(dpr, 0, 0, dpr, 0, 0); tx.setTransform(dpr, 0, 0, dpr, 0, 0);
    glW = W * (1 + EXT.l + EXT.r); glH = H * (1 + EXT.t + EXT.b); ox = W * EXT.l; oy = H * EXT.t;
    if (sx) { cs.width = glW * dpr; cs.height = glH * dpr; sx.setTransform(dpr, 0, 0, dpr, 0, 0); }
    if (renderer) { renderer.setPixelRatio(dpr); renderer.setSize(glW, glH, false); camera.left = -glW / 2; camera.right = glW / 2; camera.top = glH / 2; camera.bottom = -glH / 2; camera.updateProjectionMatrix(); }
    K = (W * (W < 460 ? 0.32 : 0.40)) / 1.102;   // brush length: 40% of the stage (32% on phones)
    repaint();
    if (STATIC && loaded) { const e = endPt(); tip.x = e.x; tip.y = e.y; pose(0, 0, 0); draw(); }
  }
  function pose(vx, vy, t) {
    if (useSprite) { sp.dz = (W < 460 ? 0.25 : 0) + clamp(vx * 0.0004, -0.35, 0.35); return; }
    holder.position.set(tip.x + ox - glW / 2, -(tip.y + oy - glH / 2), 0);
    holder.scale.setScalar(K);
    holder.rotation.set(-0.28 + clamp(vy * 0.0002, -0.2, 0.2) + Math.sin(t * 1.1) * 0.03, Math.sin(t * 1.3) * 0.1 + clamp(vx * 0.0003, -0.3, 0.3), (W < 460 ? 2.75 : 2.5) + clamp(vx * 0.0004, -0.35, 0.35));
  }
  const draw = () => {
    if (!useSprite) return renderer.render(scene, camera);
    // 2D brush (used when WebGL is not available): the same brush, drawn from a picture
    sx.clearRect(0, 0, glW, glH); sx.save(); sx.translate(tip.x + ox, tip.y + oy); sx.rotate(-sp.dz);
    const k = (W / 520) * 0.5 * (W < 460 ? 0.8 : 1);
    sx.drawImage(spr, -tipAttr[0] * k, -tipAttr[1] * k, spr.width * k, spr.height * k); sx.restore();
  };

  function drawAt(sc, t) {
    const ss = sc.strokes; let set = false;
    for (let i = 0; i < ss.length; i++) {
      const s = ss[i], f = clamp((t - s.t0) / s.dur, 0, 1);
      if (f > drawn[i]) { paintStroke(s, drawn[i], f); drawn[i] = f; }
      if (set) continue;
      if (t < s.t0) { const p = i ? ss[i - 1] : null, a = p ? P(p, 1) : P(s, 0), b = P(s, 0), u = ease(clamp((t - (p ? p.t1 : 0)) / LIFT, 0, 1)); tip.x = a.x + (b.x - a.x) * u; tip.y = a.y + (b.y - a.y) * u; set = true; }
      else if (t <= s.t1) { const q = P(s, f); tip.x = q.x; tip.y = q.y; set = true; }
    }
    if (!set) { const e = P(ss[ss.length - 1], 1); tip.x = e.x; tip.y = e.y; }
  }

  function frame() {
    const now = performance.now(); raf = 0; if (!running) return;
    // The animation clock advances at most 50 ms per frame, so a long stall (shader compile, slow phone) never skips ahead
    const dt = Math.min(now - (last || now), 50) / 1000; clock += dt; last = now;
    const ppx = tip.x, ppy = tip.y, sc = SCENES[si]; st.t += dt;
    let prog = 0; const total = LEAD + sc.dur + HOLD + FADE;
    if (st.ph === 'lead') { const e = easeOut(clamp(st.t / LEAD, 0, 1)), a = startPt(); tip.x = st.from.x + (a.x - st.from.x) * e; tip.y = st.from.y + (a.y - st.from.y) * e; prog = st.t; if (st.t >= LEAD) { st.ph = 'draw'; st.t = 0; } }
    else if (st.ph === 'draw') { drawAt(sc, st.t); prog = LEAD + st.t; if (st.t >= sc.dur) { st.ph = 'hold'; st.t = 0; } }
    else if (st.ph === 'hold') { const f = easeOut(clamp(st.t / 0.6, 0, 1)), e = endPt(); tip.x = e.x + f * W * 0.04; tip.y = e.y - f * H * 0.05 + Math.sin(clock * 1.6) * 3 * f; prog = LEAD + sc.dur + st.t; if (st.t >= HOLD) { st.ph = 'fade'; st.t = 0; } }
    else { const f = clamp(st.t / FADE, 0, 1); cp.style.opacity = 1 - f; tip.x += W * 0.2 * dt; tip.y -= H * 0.12 * dt; prog = LEAD + sc.dur + HOLD + st.t; if (f >= 1) { setScene((si + 1) % SCENES.length); st = { ph: 'lead', t: 0, from: { x: tip.x, y: tip.y } }; } }
    if (tabs[si]) tabs[si].style.setProperty('--p', Math.min(1, prog / total).toFixed(3));
    tip.vx = (tip.x - ppx) / Math.max(dt, 0.001); tip.vy = (tip.y - ppy) / Math.max(dt, 0.001);   // px per second
    pose(tip.vx, tip.vy, clock);
    if (trail.length || touched) {   // cursor trail with age fade
      tx.clearRect(0, 0, W, H);
      for (let i = trail.length - 1; i > 0; i--) { const age = (now - trail[i].t) / 1500; if (age >= 1) { trail.splice(0, i); break; } seg(tx, trail[i - 1], trail[i], trail[i].c, trail[i].w * (1 - age * 0.6), 5, 1 - age, false); }
    }
    draw(); raf = requestAnimationFrame(frame);
  }
  function play() { if (!loaded || STATIC || paused || running || !visible || !pageOk || document.hidden) return; running = true; last = 0; raf = requestAnimationFrame(frame); }
  const pause = () => { running = false; if (raf) cancelAnimationFrame(raf); raf = 0; };

  function ready() {
    loaded = true; fit(); setScene(0); root.classList.add('ready');
    if (STATIC) { paintAll(); const e = endPt(); tip.x = e.x; tip.y = e.y; pose(0, 0, 0); draw(); }
    else { tip.x = W * 1.1; tip.y = -H * 0.1; st = { ph: 'lead', t: 0, from: { x: tip.x, y: tip.y } }; pose(0, 0, 0); draw(); play(); }
  }
  function startSprite() {
    if (!spriteUrl || tipAttr.length < 2 || isNaN(tipAttr[0])) { mode('poster'); return fallback(); }
    spr = new Image();
    spr.onload = () => {
      cs = document.createElement('canvas'); cs.className = 'hp-gl'; cs.setAttribute('aria-hidden', 'true');
      cg.parentNode.replaceChild(cs, cg); sx = cs.getContext('2d'); useSprite = true; mode('sprite'); ready();
    };
    spr.onerror = () => { mode('poster'); fallback(); };
    spr.src = spriteUrl;
  }
  function start() {
    started = true;
    if (!glOk) return startSprite();
    try {
      renderer = new WebGLRenderer({ canvas: cg, alpha: true, antialias: true, powerPreference: 'high-performance' });
      renderer.setClearColor(0x000000, 0);
      scene = new Scene(); camera = new OrthographicCamera(-1, 1, 1, -1, -3000, 3000);
      scene.environment = new PMREMGenerator(renderer).fromScene(new RoomEnvironment(), 0.04).texture;
      const key = new DirectionalLight(0xffffff, 1.2); key.position.set(-300, 500, 700); scene.add(key);
    } catch (e) { renderer = null; return startSprite(); }
    new GLTFLoader().setMeshoptDecoder(MeshoptDecoder).load(modelUrl, (gltf) => {
      try {
        const brush = gltf.scene, box = new Box3().setFromObject(brush), c = box.getCenter(new Vector3());
        brush.position.sub(new Vector3(c.x, box.max.y, c.z));     // the bristle tip becomes the origin
        holder = new Group(); holder.add(brush); scene.add(holder);
        brush.traverse((o) => { if (o.isMesh) { const m = o.material; m.emissiveMap = m.map; m.emissive.setRGB(1, 1, 1); m.emissiveIntensity = 0.45; m.envMapIntensity = 0.8; m.roughness = Math.max(m.roughness, 0.25); m.needsUpdate = true; } });
      } catch (e) { renderer = null; return startSprite(); }
      mode('webgl'); ready();
    }, undefined, () => { renderer = null; startSprite(); });
    cg.addEventListener('webglcontextlost', (e) => { e.preventDefault(); pause(); loaded = false; renderer = null; startSprite(); });
  }

  // wait for the loading screen: the painting must start when the visitor can actually see it
  if (!pageOk) {
    const mo = new MutationObserver(() => { if (page.classList.contains('ready')) { mo.disconnect(); setTimeout(() => { pageOk = true; play(); }, 350); } });
    mo.observe(page, { attributes: true, attributeFilter: ['class'] });
    setTimeout(() => { if (!pageOk) { pageOk = true; play(); } }, 8000);
  }
  new IntersectionObserver((es) => es.forEach((e) => { visible = e.isIntersecting; if (visible && !started) start(); else if (visible) play(); else pause(); }), { threshold: 0.1 }).observe(root);
  new ResizeObserver(fit).observe(root);
  document.addEventListener('visibilitychange', () => (document.hidden ? pause() : play()));
  tabs.forEach((b, k) => b.addEventListener('click', () => jump(k)));
  const pauseBtn = meta && meta.querySelector('.hp-pause');
  if (pauseBtn) pauseBtn.addEventListener('click', () => {
    paused = !paused; pauseBtn.setAttribute('aria-pressed', paused ? 'true' : 'false'); pauseBtn.setAttribute('aria-label', paused ? 'Play animation' : 'Pause animation');
    if (paused) pause(); else play();
  });
  if (!STATIC) {
    root.addEventListener('pointermove', (e) => {
      const r = root.getBoundingClientRect(), x = e.clientX - r.left, y = e.clientY - r.top, q = trail[trail.length - 1];
      if (q && Math.hypot(x - q.x, y - q.y) < 3) return;
      const d = q ? Math.hypot(x - q.x, y - q.y) : 0; trailT = (trailT + d / 1100) % 1; touched = true;
      trail.push({ x, y, t: performance.now(), c: trailT, w: clamp(W * 0.04 - d * 0.4, W * 0.01, W * 0.04) }); if (trail.length > 120) trail.shift();
    }, { passive: true });
  }
})();
