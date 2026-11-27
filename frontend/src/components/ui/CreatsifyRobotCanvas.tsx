'use client';

import React, { useEffect, useRef, useState } from 'react';
import * as THREE from 'three';

export interface CreatsifyRobotCanvasProps {
  variant?: 'hero' | 'chat-launcher' | 'chat-header';
  isDark?: boolean;
  /** Increment this counter from outside to trigger a cheerful wave + happy face + hop */
  cheerSignal?: number;
  /** Follow mouse across entire window (great for hero & chat launcher) */
  globalPointer?: boolean;
  /** Allow drag-to-spin and click-to-cheer directly on the canvas */
  interactive?: boolean;
  className?: string;
  ariaLabel?: string;
}

function buildCreatsifyRobot(isMini: boolean) {
  const V3 = THREE.Vector3;
  const C = (h: number) => new THREE.Color(h).convertSRGBToLinear();

  // Creatsify logo palette
  const PAL = {
    navy: 0x16246b,
    royal: 0x2b4fdb,
    azure: 0x2aa3f7,
    cyan: 0x25e3f5,
    pearl: 0xf3f7ff,
    blush: 0xff9dc0,
  };

  const M = {
    shell: new THREE.MeshPhysicalMaterial({
      color: C(PAL.pearl),
      roughness: 0.26,
      metalness: 0,
      clearcoat: 1,
      clearcoatRoughness: 0.1,
    }),
    royal: new THREE.MeshPhysicalMaterial({
      color: C(PAL.royal),
      roughness: 0.26,
      metalness: 0.1,
      clearcoat: 1,
      clearcoatRoughness: 0.1,
    }),
    navy: new THREE.MeshPhysicalMaterial({
      color: C(PAL.navy),
      roughness: 0.3,
      metalness: 0.2,
      clearcoat: 1,
      clearcoatRoughness: 0.12,
    }),
    steel: new THREE.MeshStandardMaterial({
      color: C(0x9fb0d0),
      roughness: 0.35,
      metalness: 0.7,
    }),
    screen: new THREE.MeshPhysicalMaterial({
      color: C(0x050a1e),
      roughness: 0.06,
      metalness: 0.25,
      clearcoat: 1,
      clearcoatRoughness: 0.02,
    }),
    glow: new THREE.MeshBasicMaterial({
      color: C(PAL.cyan),
      toneMapped: false,
    }),
    eye: new THREE.MeshBasicMaterial({
      vertexColors: true,
      toneMapped: false,
    }),
    spec: new THREE.MeshBasicMaterial({
      color: 0xffffff,
      toneMapped: false,
    }),
    blush: new THREE.MeshBasicMaterial({
      color: C(PAL.blush),
      transparent: true,
      opacity: 0.62,
      depthWrite: false,
      toneMapped: false,
    }),
    tip: new THREE.MeshStandardMaterial({
      color: C(PAL.azure),
      emissive: C(PAL.cyan),
      emissiveIntensity: 0.75,
      roughness: 0.3,
    }),
  };

  const disposables: Array<{ dispose: () => void }> = Object.values(M);
  const trackGeo = <T extends THREE.BufferGeometry>(g: T): T => {
    disposables.push(g);
    return g;
  };

  function mesh(
    geo: THREE.BufferGeometry,
    m: THREE.Material,
    name: string,
    shadows = true
  ) {
    const o = new THREE.Mesh(geo, m);
    o.name = name;
    if (shadows && !isMini) {
      o.castShadow = true;
      o.receiveShadow = true;
    }
    return o;
  }

  function ell(
    r: number,
    sx: number,
    sy: number,
    sz: number,
    m: THREE.Material,
    seg: number,
    name: string,
    shadows = true
  ) {
    const s = isMini ? Math.max(16, Math.round(seg * 0.55)) : seg;
    const o = mesh(
      trackGeo(new THREE.SphereGeometry(r, s, (s * 0.75) | 0)),
      m,
      name,
      shadows
    );
    o.scale.set(sx, sy, sz);
    return o;
  }

  function put<T extends THREE.Object3D>(
    parent: THREE.Object3D,
    obj: T,
    x: number,
    y: number,
    z: number
  ): T {
    obj.position.set(x, y, z);
    parent.add(obj);
    return obj;
  }

  function group(name: string) {
    const g = new THREE.Group();
    g.name = name;
    return g;
  }

  const H = { a: 1.18, b: 0.92, c: 1.0, n: 3 };

  function headSurface(x: number, y: number, off: number) {
    const { a, b, c, n } = H;
    const ux = Math.abs(x / a);
    const uy = Math.abs(y / b);
    const q = Math.max(0, 1 - Math.pow(ux, n) - Math.pow(uy, n));
    const z = c * Math.pow(q, 1 / n);
    const uz = Math.abs(z / c);
    let gx = (n * Math.pow(ux, n - 1) * Math.sign(x)) / a;
    let gy = (n * Math.pow(uy, n - 1) * Math.sign(y)) / b;
    let gz = (n * Math.pow(uz, n - 1)) / c;
    const l = Math.hypot(gx, gy, gz) || 1;
    gx /= l;
    gy /= l;
    gz /= l;
    return {
      p: new V3(x + gx * off, y + gy * off, z + gz * off),
      n: new V3(gx, gy, gz),
    };
  }

  function headGeometry(seg: number) {
    const s = isMini ? Math.max(28, Math.round(seg * 0.55)) : seg;
    const g = trackGeo(new THREE.SphereGeometry(1, s, (s * 0.75) | 0));
    const P = g.attributes.position;
    const N = g.attributes.normal;
    const { a, b, c, n } = H;
    for (let i = 0; i < P.count; i++) {
      const x = P.getX(i);
      const y = P.getY(i);
      const z = P.getZ(i);
      const m = Math.pow(
        Math.pow(Math.abs(x), n) + Math.pow(Math.abs(y), n) + Math.pow(Math.abs(z), n),
        1 / n
      );
      const px = (a * x) / m;
      const py = (b * y) / m;
      const pz = (c * z) / m;
      P.setXYZ(i, px, py, pz);
      const ux = Math.abs(px / a);
      const uy = Math.abs(py / b);
      const uz = Math.abs(pz / c);
      const gx = (n * Math.pow(ux, n - 1) * Math.sign(px)) / a;
      const gy = (n * Math.pow(uy, n - 1) * Math.sign(py)) / b;
      const gz = (n * Math.pow(uz, n - 1)) / c;
      const l = Math.hypot(gx, gy, gz) || 1;
      N.setXYZ(i, gx / l, gy / l, gz / l);
    }
    return g;
  }

  function screenGeo(o: {
    sx: number;
    sy: number;
    ox: number;
    oy: number;
    a: number;
    b: number;
    n2: number;
    off: number;
    rings: number;
    segs: number;
    color?: (rr: number) => THREE.Color;
  }) {
    const pos: number[] = [];
    const nor: number[] = [];
    const col: number[] = [];
    const idx: number[] = [];
    const rings = isMini ? Math.max(4, Math.round(o.rings * 0.6)) : o.rings;
    const segs = isMini ? Math.max(16, Math.round(o.segs * 0.6)) : o.segs;

    for (let r = 0; r <= rings; r++) {
      const rr = r / rings;
      for (let s = 0; s < segs; s++) {
        const th = (s / segs) * Math.PI * 2;
        const c = Math.cos(th);
        const sn = Math.sin(th);
        const x = o.sx + o.a * Math.sign(c) * Math.pow(Math.abs(c), 2 / o.n2) * rr;
        const y = o.sy + o.b * Math.sign(sn) * Math.pow(Math.abs(sn), 2 / o.n2) * rr;
        const h = headSurface(x, y, o.off);
        pos.push(h.p.x - o.ox, h.p.y - o.oy, h.p.z);
        nor.push(h.n.x, h.n.y, h.n.z);
        if (o.color) {
          const k = o.color(rr);
          col.push(k.r, k.g, k.b);
        }
      }
    }
    for (let r = 0; r < rings; r++) {
      for (let s = 0; s < segs; s++) {
        const a = r * segs + s;
        const b = (r + 1) * segs + s;
        const c = (r + 1) * segs + ((s + 1) % segs);
        const d = r * segs + ((s + 1) % segs);
        idx.push(a, b, c, a, c, d);
      }
    }
    const g = trackGeo(new THREE.BufferGeometry());
    g.setAttribute('position', new THREE.Float32BufferAttribute(pos, 3));
    g.setAttribute('normal', new THREE.Float32BufferAttribute(nor, 3));
    if (o.color) g.setAttribute('color', new THREE.Float32BufferAttribute(col, 3));
    g.setIndex(idx);
    return g;
  }

  function arcTube(
    o: {
      ox: number;
      oy: number;
      x0: number;
      x1: number;
      fn: (x: number) => number;
      off: number;
      r: number;
      n?: number;
    },
    m: THREE.Material,
    name: string
  ) {
    const N = o.n || 14;
    const pts: THREE.Vector3[] = [];
    for (let i = 0; i <= N; i++) {
      const x = o.x0 + ((o.x1 - o.x0) * i) / N;
      const s = headSurface(o.ox + x, o.oy + o.fn(x), o.off);
      pts.push(new V3(s.p.x - o.ox, s.p.y - o.oy, s.p.z));
    }
    const g = group(name);
    g.add(
      mesh(
        trackGeo(new THREE.TubeGeometry(new THREE.CatmullRomCurve3(pts), N * 2, o.r, 8, false)),
        m,
        `${name}_Line`,
        false
      )
    );
    const cap = trackGeo(new THREE.SphereGeometry(o.r, 10, 8));
    [pts[0], pts[N]].forEach((p, i) => {
      put(g, mesh(cap, m, `${name}_Cap${i}`, false), p.x, p.y, p.z);
    });
    return g;
  }

  const eyeStops: Array<[number, number]> = [
    [0, 0xddfeff],
    [0.4, PAL.cyan],
    [0.8, PAL.azure],
    [1, PAL.royal],
  ];

  function lerpStops(stops: Array<[number, number]>, t: number) {
    for (let i = 1; i < stops.length; i++) {
      if (t <= stops[i][0]) {
        const a = stops[i - 1];
        const b = stops[i];
        const k = (t - a[0]) / (b[0] - a[0] || 1);
        return C(a[1]).lerp(C(b[1]), k);
      }
    }
    return C(stops[stops.length - 1][1]);
  }

  const root = group('Robot');
  const bodyG = group('Body');
  root.add(bodyG);

  bodyG.add(ell(1, 0.95, 1.05, 0.88, M.shell, 64, 'BodyShell'));

  const band = mesh(
    trackGeo(new THREE.TorusGeometry(1, 0.05, 14, isMini ? 48 : 80)),
    M.royal,
    'WaistBand'
  );
  band.rotation.x = Math.PI / 2;
  band.scale.set(0.85, 0.79, 1);
  band.position.y = -0.5;
  bodyG.add(band);

  put(
    bodyG,
    mesh(trackGeo(new THREE.CylinderGeometry(0.2, 0.3, 0.24, isMini ? 20 : 32)), M.steel, 'Nozzle'),
    0,
    -1.02,
    0
  );
  const jetGlow = put(bodyG, ell(1, 0.2, 0.05, 0.2, M.glow, 24, 'JetGlow', false), 0, -1.16, 0);

  function makeArm(side: number) {
    const pivot = group(side > 0 ? 'ArmR' : 'ArmL');
    pivot.position.set(side * 0.86, 0.45, 0);
    put(pivot, ell(0.2, 0.9, 1.7, 0.9, M.shell, 32, 'Arm'), 0, -0.34, 0);
    const cuff = mesh(
      trackGeo(new THREE.TorusGeometry(0.15, 0.04, 10, isMini ? 20 : 32)),
      M.navy,
      'Cuff'
    );
    cuff.rotation.x = Math.PI / 2;
    put(pivot, cuff, 0, -0.62, 0);
    put(pivot, ell(0.2, 1, 1.05, 0.95, M.royal, 32, 'Hand'), 0, -0.8, 0);
    put(pivot, ell(0.08, 1, 1.35, 1, M.royal, 20, 'Thumb'), -side * 0.06, -0.74, 0.16);
    bodyG.add(pivot);
    return pivot;
  }

  const armR = makeArm(1);
  const armL = makeArm(-1);
  armR.rotation.z = 0.3;
  armL.rotation.z = -0.3;

  put(
    root,
    mesh(trackGeo(new THREE.CylinderGeometry(0.3, 0.3, 0.4, isMini ? 20 : 32)), M.steel, 'Neck'),
    0,
    1.0,
    0
  );
  const neckRing = mesh(
    trackGeo(new THREE.TorusGeometry(0.33, 0.1, 14, isMini ? 28 : 48)),
    M.navy,
    'NeckRing'
  );
  neckRing.rotation.x = Math.PI / 2;
  put(root, neckRing, 0, 1.0, 0);

  const headG = group('Head');
  headG.position.set(0, 1.2, 0);
  root.add(headG);
  const headC = group('HeadCenter');
  headC.position.set(0, 0.68, 0);
  headG.add(headC);

  headC.add(mesh(headGeometry(72), M.shell, 'HeadShell'));

  headC.add(
    mesh(
      screenGeo({ sx: 0, sy: 0, ox: 0, oy: 0, a: 0.95, b: 0.67, n2: 3, off: 0.012, rings: 10, segs: 72 }),
      M.royal,
      'ScreenBezel',
      false
    )
  );
  headC.add(
    mesh(
      screenGeo({ sx: 0, sy: 0, ox: 0, oy: 0, a: 0.9, b: 0.62, n2: 3, off: 0.026, rings: 10, segs: 72 }),
      M.screen,
      'Screen',
      false
    )
  );

  [-1, 1].forEach((s) => {
    const nm = s < 0 ? 'EarL' : 'EarR';
    put(headC, ell(1, 0.2, 0.34, 0.34, M.royal, 32, nm), s * 1.15, 0, 0);
    put(headC, ell(1, 0.08, 0.2, 0.2, M.glow, 24, `${nm}_Glow`, false), s * 1.31, 0, 0);
  });

  const antG = group('Antenna');
  antG.position.set(0, 0.88, 0);
  headC.add(antG);
  put(
    antG,
    mesh(trackGeo(new THREE.CylinderGeometry(0.1, 0.12, 0.08, 20)), M.steel, 'AntennaBase'),
    0,
    0.02,
    0
  );
  put(
    antG,
    mesh(trackGeo(new THREE.CylinderGeometry(0.035, 0.035, 0.36, 14)), M.navy, 'AntennaStem'),
    0,
    0.2,
    0
  );
  const tip = group('AntennaTip');
  tip.add(ell(0.11, 1, 1, 1, M.tip, 28, 'AntennaTipBall'));
  put(antG, tip, 0, 0.45, 0);

  const faceG = group('Face');
  headC.add(faceG);

  function makeEye(side: number) {
    const nm = side < 0 ? 'EyeL' : 'EyeR';
    const cx = side * 0.36;
    const cy = 0.06;
    const g = group(nm);
    g.position.set(cx, cy, 0);

    const open = group(`${nm}_Open`);
    open.add(
      mesh(
        screenGeo({
          sx: cx,
          sy: cy,
          ox: cx,
          oy: cy,
          a: 0.19,
          b: 0.25,
          n2: 2,
          off: 0.04,
          rings: 10,
          segs: 40,
          color: (rr) => lerpStops(eyeStops, rr),
        }),
        M.eye,
        `${nm}_Ball`,
        false
      )
    );
    open.add(
      mesh(
        screenGeo({
          sx: cx + 0.065,
          sy: cy + 0.1,
          ox: cx,
          oy: cy,
          a: 0.055,
          b: 0.055,
          n2: 2,
          off: 0.055,
          rings: 3,
          segs: 20,
        }),
        M.spec,
        `${nm}_Spark1`,
        false
      )
    );
    open.add(
      mesh(
        screenGeo({
          sx: cx - 0.06,
          sy: cy - 0.1,
          ox: cx,
          oy: cy,
          a: 0.026,
          b: 0.026,
          n2: 2,
          off: 0.055,
          rings: 2,
          segs: 14,
        }),
        M.spec,
        `${nm}_Spark2`,
        false
      )
    );
    g.add(open);

    const happy = arcTube(
      {
        ox: cx,
        oy: cy,
        x0: -0.17,
        x1: 0.17,
        fn: (x) => -0.06 + 0.14 * (1 - Math.pow(x / 0.17, 2)),
        off: 0.045,
        r: 0.04,
        n: 14,
      },
      M.glow,
      `${nm}_Happy`
    );
    happy.visible = false;
    g.add(happy);

    faceG.add(g);
    return { g, open, happy, baseX: cx, baseY: cy };
  }

  const eyeL = makeEye(-1);
  const eyeR = makeEye(1);

  function makeBrow(side: number) {
    const ox = side * 0.36;
    const oy = 0.43;
    const b = arcTube(
      {
        ox,
        oy,
        x0: -0.14,
        x1: 0.14,
        fn: (x) => 0.05 * (1 - Math.pow(x / 0.15, 2)) - 0.3 * side * x,
        off: 0.04,
        r: 0.018,
        n: 10,
      },
      M.glow,
      side < 0 ? 'BrowL' : 'BrowR'
    );
    put(faceG, b, ox, oy, 0);
    return b;
  }

  const browL = makeBrow(-1);
  const browR = makeBrow(1);

  const mouth = arcTube(
    {
      ox: 0,
      oy: 0,
      x0: -0.12,
      x1: 0.12,
      fn: (x) => -0.2 + 0.06 * Math.pow(x / 0.12, 2),
      off: 0.04,
      r: 0.022,
      n: 12,
    },
    M.glow,
    'Mouth'
  );
  faceG.add(mouth);

  const mouthBig = arcTube(
    {
      ox: 0,
      oy: 0,
      x0: -0.17,
      x1: 0.17,
      fn: (x) => -0.19 + 0.1 * Math.pow(x / 0.17, 2),
      off: 0.04,
      r: 0.03,
      n: 14,
    },
    M.glow,
    'MouthBig'
  );
  mouthBig.visible = false;
  faceG.add(mouthBig);

  [-1, 1].forEach((s) => {
    const nm = s < 0 ? 'BlushL' : 'BlushR';
    faceG.add(
      mesh(
        screenGeo({
          sx: s * 0.68,
          sy: -0.2,
          ox: 0,
          oy: 0,
          a: 0.13,
          b: 0.08,
          n2: 2,
          off: 0.032,
          rings: 4,
          segs: 28,
        }),
        M.blush,
        `${nm}_Outer`,
        false
      )
    );
    faceG.add(
      mesh(
        screenGeo({
          sx: s * 0.68,
          sy: -0.2,
          ox: 0,
          oy: 0,
          a: 0.075,
          b: 0.045,
          n2: 2,
          off: 0.034,
          rings: 3,
          segs: 24,
        }),
        M.blush,
        `${nm}_Inner`,
        false
      )
    );
  });

  return {
    root,
    bodyG,
    headG,
    headC,
    armR,
    armL,
    antG,
    tip,
    eyeL,
    eyeR,
    browL,
    browR,
    mouth,
    mouthBig,
    jetGlow,
    dispose: () => {
      disposables.forEach((d) => {
        try {
          d.dispose();
        } catch {
          // Ignore disposal errors
        }
      });
    },
  };
}

export function CreatsifyRobotCanvas({
  variant = 'hero',
  isDark = true,
  cheerSignal = 0,
  globalPointer = true,
  interactive = true,
  className = '',
  ariaLabel = 'Interactive 3D Creatsify robot mascot',
}: CreatsifyRobotCanvasProps) {
  const containerRef = useRef<HTMLDivElement>(null);
  const cheerRef = useRef<(() => void) | null>(null);
  const [webglFailed, setWebglFailed] = useState(false);

  useEffect(() => {
    if (cheerSignal > 0 && cheerRef.current) {
      cheerRef.current();
    }
  }, [cheerSignal]);

  useEffect(() => {
    const stage = containerRef.current;
    if (!stage) return;

    const isMini = variant !== 'hero';
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let renderer: THREE.WebGLRenderer;
    try {
      renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true,
        powerPreference: isMini ? 'low-power' : 'high-performance',
      });
    } catch {
      setWebglFailed(true);
      return;
    }

    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, isMini ? 1.5 : 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = isDark ? 1.06 : 1.02;
    renderer.shadowMap.enabled = !isMini;
    if (!isMini) {
      renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    }
    renderer.setClearColor(0x000000, 0);
    renderer.domElement.style.display = 'block';
    renderer.domElement.style.width = '100%';
    renderer.domElement.style.height = '100%';
    stage.appendChild(renderer.domElement);

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(variant === 'hero' ? 31 : 28, 1, 0.1, 100);

    // Glossy studio environment reflections
    const envScene = new THREE.Scene();
    const envSphereGeo = new THREE.SphereGeometry(20, 24, 14);
    const posAttr = envSphereGeo.attributes.position;
    const envCols: number[] = [];
    const topCol = new THREE.Color(isDark ? 0xeaf6ff : 0xffffff);
    const botCol = new THREE.Color(isDark ? 0x1e3a78 : 0x8fb0ea);
    for (let i = 0; i < posAttr.count; i++) {
      const k = (posAttr.getY(i) / 20 + 1) / 2;
      const mixed = botCol.clone().lerp(topCol, k);
      envCols.push(mixed.r, mixed.g, mixed.b);
    }
    envSphereGeo.setAttribute('color', new THREE.Float32BufferAttribute(envCols, 3));
    const envSphereMat = new THREE.MeshBasicMaterial({
      vertexColors: true,
      side: THREE.BackSide,
    });
    envScene.add(new THREE.Mesh(envSphereGeo, envSphereMat));

    const envDisposables: Array<{ dispose: () => void }> = [envSphereGeo, envSphereMat];
    const addSoftbox = (
      w: number,
      h: number,
      x: number,
      y: number,
      z: number,
      v: number,
      tint?: number
    ) => {
      const geo = new THREE.PlaneGeometry(w, h);
      const col = tint
        ? new THREE.Color(tint).multiplyScalar(v * 0.5)
        : new THREE.Color(v, v, v);
      const mat = new THREE.MeshBasicMaterial({ color: col, side: THREE.DoubleSide });
      const box = new THREE.Mesh(geo, mat);
      box.position.set(x, y, z);
      box.lookAt(0, 0, 0);
      envScene.add(box);
      envDisposables.push(geo, mat);
    };

    addSoftbox(10, 6, 8, 8, 8, 5.8);
    addSoftbox(8, 8, -10, 3, 4, 3.2, 0x25e3f5);
    addSoftbox(12, 3, 0, 10, -6, 4.0);
    addSoftbox(3, 10, 6, 2, 10, 4.8);
    addSoftbox(6, 4, -3, 1, 12, 3.0);

    const pmrem = new THREE.PMREMGenerator(renderer);
    const envRT = pmrem.fromScene(envScene, 0.04);
    pmrem.dispose();
    envDisposables.forEach((d) => d.dispose());
    scene.environment = envRT.texture;

    // Studio Lights
    scene.add(new THREE.HemisphereLight(0xffffff, isDark ? 0x28458a : 0xb4c8f0, isDark ? 0.52 : 0.48));
    const key = new THREE.DirectionalLight(0xffffff, 1.42);
    key.position.set(4, 6, 6);
    if (!isMini) {
      key.castShadow = true;
      key.shadow.mapSize.set(1024, 1024);
      key.shadow.camera.left = -4;
      key.shadow.camera.right = 4;
      key.shadow.camera.top = 5;
      key.shadow.camera.bottom = -4;
      key.shadow.camera.near = 0.5;
      key.shadow.camera.far = 25;
      key.shadow.bias = -0.0004;
      key.shadow.normalBias = 0.03;
    }
    scene.add(key);

    const rim = new THREE.DirectionalLight(0x25e3f5, isDark ? 1.55 : 1.15);
    rim.position.set(-5, 3, -4);
    scene.add(rim);

    const fill = new THREE.DirectionalLight(0xdfe8ff, 0.45);
    fill.position.set(-4, 1, 5);
    scene.add(fill);

    // Build Robot
    const R = buildCreatsifyRobot(isMini);
    scene.add(R.root);

    // State
    const mouse = { x: 0, y: 0 };
    const look = { x: 0, y: 0 };
    let drag = false;
    let lastX = 0;
    let downX = 0;
    let downY = 0;
    let dragYaw = 0;
    let t = 0;
    let nextBlink = 2.2;
    let blinkStart = -1;
    let waveUntil = variant === 'hero' ? 3.2 : 2.0;
    let happyUntil = variant === 'hero' ? 3.2 : 2.0;
    let hopStart = -10;
    let isVisible = true;
    let rafId = 0;

    const triggerCheer = () => {
      waveUntil = t + 2.5;
      happyUntil = t + 2.5;
      hopStart = t;
    };
    cheerRef.current = triggerCheer;

    const onWindowPointerMove = (e: PointerEvent) => {
      const rect = stage.getBoundingClientRect();
      if (rect.width === 0 || rect.height === 0) return;
      const cx = rect.left + rect.width / 2;
      const cy = rect.top + rect.height / 2;
      const normX = THREE.MathUtils.clamp((e.clientX - cx) / (window.innerWidth * 0.48), -1, 1);
      const normY = THREE.MathUtils.clamp(-((e.clientY - cy) / (window.innerHeight * 0.48)), -1, 1);
      mouse.x = normX;
      mouse.y = normY;
    };

    const onPointerDown = (e: PointerEvent) => {
      if (!interactive) return;
      drag = true;
      lastX = downX = e.clientX;
      downY = e.clientY;
      try {
        stage.setPointerCapture(e.pointerId);
      } catch {
        // Ignore capture failure
      }
    };

    const onPointerMove = (e: PointerEvent) => {
      const r = stage.getBoundingClientRect();
      if (r.width > 0 && r.height > 0) {
        mouse.x = THREE.MathUtils.clamp(((e.clientX - r.left) / r.width) * 2 - 1, -1, 1);
        mouse.y = THREE.MathUtils.clamp(-(((e.clientY - r.top) / r.height) * 2 - 1), -1, 1);
      }
      if (drag && interactive) {
        dragYaw += (e.clientX - lastX) * 0.013;
        lastX = e.clientX;
      }
    };

    const onPointerUp = (e: PointerEvent) => {
      if (!interactive) return;
      if (drag && Math.hypot(e.clientX - downX, e.clientY - downY) < 7) {
        triggerCheer();
      }
      drag = false;
    };

    const onPointerCancel = () => {
      drag = false;
    };

    if (globalPointer) {
      window.addEventListener('pointermove', onWindowPointerMove, { passive: true });
    }
    if (interactive) {
      stage.addEventListener('pointerdown', onPointerDown);
      stage.addEventListener('pointermove', onPointerMove);
      stage.addEventListener('pointerup', onPointerUp);
      stage.addEventListener('pointercancel', onPointerCancel);
    }

    const resize = () => {
      const w = stage.clientWidth || 1;
      const h = stage.clientHeight || 1;
      renderer.setSize(w, h, false);
      camera.aspect = w / h;

      if (variant === 'hero') {
        const d = 9.4 * Math.max(1, 0.72 / camera.aspect);
        camera.position.set(0, 0.92, d);
        camera.lookAt(0, 0.78, 0);
      } else if (variant === 'chat-launcher') {
        camera.position.set(0, 1.38, 6.2);
        camera.lookAt(0, 1.25, 0);
      } else {
        camera.position.set(0, 1.48, 5.6);
        camera.lookAt(0, 1.38, 0);
      }
      camera.updateProjectionMatrix();
    };

    const ro = new ResizeObserver(resize);
    ro.observe(stage);
    resize();

    const io = new IntersectionObserver(
      (entries) => {
        isVisible = entries[0]?.isIntersecting ?? true;
      },
      { threshold: 0.01 }
    );
    io.observe(stage);

    const clock = new THREE.Clock();
    const clamp = THREE.MathUtils.clamp;

    const renderFrame = (dt: number) => {
      t += dt;
      const k = Math.min(1, dt * 6.5);

      look.x += (mouse.x - look.x) * k;
      look.y += (mouse.y - look.y) * k;

      if (!drag) {
        dragYaw += (0 - dragYaw) * Math.min(1, dt * 3.2);
      }
      R.root.rotation.y = dragYaw + Math.sin(t * 0.65) * 0.075;

      const hopT = t - hopStart;
      const hop = hopT >= 0 && hopT < 0.7 ? Math.sin((hopT / 0.7) * Math.PI) * 0.45 : 0;
      R.root.position.y = Math.sin(t * 1.65) * 0.11 + hop;
      R.root.rotation.z = Math.sin(t * 1.1) * 0.025;

      // Head follows cursor
      R.headG.rotation.y += (clamp(look.x * 0.55, -0.62, 0.62) - R.headG.rotation.y) * k;
      R.headG.rotation.x += (-look.y * 0.28 - R.headG.rotation.x) * k;
      R.headG.rotation.z = Math.sin(t * 1.3) * 0.03 - look.x * 0.05;

      // Eyes glance slightly ahead
      [R.eyeL, R.eyeR].forEach((e) => {
        e.g.position.x = e.baseX + look.x * 0.045;
        e.g.position.y = e.baseY + look.y * 0.035;
      });

      // Blinking + Happy expression
      if (blinkStart < 0 && t > nextBlink) blinkStart = t;
      let bl = 1;
      if (blinkStart >= 0) {
        const p = (t - blinkStart) / 0.16;
        if (p >= 1) {
          blinkStart = -1;
          nextBlink = t + 2.2 + Math.random() * 2.8;
        } else {
          bl = 1 - Math.sin(p * Math.PI) * 0.93;
        }
      }
      const happy = t < happyUntil;
      [R.eyeL, R.eyeR].forEach((e) => {
        e.open.visible = !happy;
        e.happy.visible = happy;
        e.open.scale.y = bl;
      });
      R.mouth.visible = !happy;
      R.mouthBig.visible = happy;

      const by = happy ? 0.03 : 0;
      R.browL.position.y += (0.43 + by - R.browL.position.y) * k;
      R.browR.position.y = R.browL.position.y;

      // Arms wave on load, click, or external CTA hover
      const wave = t < waveUntil;
      const rT = wave ? 2.45 + Math.sin(t * 10.5) * 0.3 : 0.3 + Math.sin(t * 1.5) * 0.04;
      const lT = -0.3 - Math.sin(t * 1.5 + 1) * 0.04;
      const ak = Math.min(1, dt * 9);
      R.armR.rotation.z += (rT - R.armR.rotation.z) * ak;
      R.armL.rotation.z += (lT - R.armL.rotation.z) * ak;

      // Antenna + Jet pulse
      R.antG.rotation.z = Math.sin(t * 2.3) * 0.12 - R.headG.rotation.y * 0.4;
      R.antG.rotation.x = Math.cos(t * 1.9) * 0.08;
      R.tip.scale.setScalar(1 + Math.sin(t * 4) * 0.13);
      R.jetGlow.scale.set(
        0.2 * (1 + Math.sin(t * 6) * 0.12),
        0.05,
        0.2 * (1 + Math.sin(t * 6) * 0.12)
      );

      renderer.render(scene, camera);
    };

    if (prefersReduced) {
      waveUntil = 0;
      happyUntil = 0;
      renderFrame(0.016);
    } else {
      const tick = () => {
        rafId = requestAnimationFrame(tick);
        if (!isVisible || document.hidden) {
          clock.getDelta();
          return;
        }
        const dt = Math.min(clock.getDelta(), 0.05);
        renderFrame(dt);
      };
      rafId = requestAnimationFrame(tick);
    }

    return () => {
      cancelAnimationFrame(rafId);
      cheerRef.current = null;
      ro.disconnect();
      io.disconnect();
      if (globalPointer) {
        window.removeEventListener('pointermove', onWindowPointerMove);
      }
      if (interactive) {
        stage.removeEventListener('pointerdown', onPointerDown);
        stage.removeEventListener('pointermove', onPointerMove);
        stage.removeEventListener('pointerup', onPointerUp);
        stage.removeEventListener('pointercancel', onPointerCancel);
      }
      R.dispose();
      envRT.dispose();
      renderer.dispose();
      if (renderer.domElement.parentNode === stage) {
        stage.removeChild(renderer.domElement);
      }
    };
  }, [variant, isDark, globalPointer, interactive]);

  if (webglFailed) {
    return (
      <div
        role="img"
        aria-label={ariaLabel}
        className={`flex items-center justify-center ${className}`}
      >
        <img
          src="/assets/brand/creatsify-icon.svg"
          alt="Creatsify Mascot"
          className="h-20 w-20 object-contain"
        />
      </div>
    );
  }

  return (
    <div
      ref={containerRef}
      role="img"
      aria-label={ariaLabel}
      className={`${interactive ? 'cursor-grab active:cursor-grabbing touch-pan-y select-none' : 'pointer-events-none select-none'} ${className}`}
    />
  );
}
