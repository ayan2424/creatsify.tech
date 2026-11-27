import * as THREE from 'three';

export type NekoVariant =
  | 'hero'
  | 'chatbot-icon'
  | 'card-avatar'
  | 'full-stack-orbit'
  | 'mini-corner';

export type NekoMode = 'hover' | 'wave' | 'dance' | 'flip' | 'think' | 'funny';

export type NekoExpression =
  | 'normal'
  | 'love'
  | 'joy'
  | 'star'
  | 'funny'
  | 'think'
  | 'excited';

export interface NekoPalette {
  topColor: string;
  bottomColor: string;
  pearl: string;
  gunmetal: string;
  accent: string;
  eyeDeep: string;
  eyeMid: string;
  eyeBright: string;
  cheek: string;
}

export const CREATSIFY_NEKO_PALETTE: NekoPalette = {
  topColor: '#1a66ff',
  bottomColor: '#07195e',
  pearl: '#f4f7fc',
  gunmetal: '#181e2c',
  accent: '#00e5ff',
  eyeDeep: '#0a44d6',
  eyeMid: '#00cfff',
  eyeBright: '#c2f9ff',
  cheek: '#ff6ea9',
};

export interface NekoRig {
  root: THREE.Group;
  bodyPivot: THREE.Group;
  torso?: THREE.Group;
  head: THREE.Group;
  leftEar: THREE.Group;
  rightEar: THREE.Group;
  leftArm?: { shoulder: THREE.Group; elbow: THREE.Group; hand: THREE.Group };
  rightArm?: { shoulder: THREE.Group; elbow: THREE.Group; hand: THREE.Group };
  leftLeg?: { hip: THREE.Group; foot: THREE.Group; glowRing: THREE.Mesh };
  rightLeg?: { hip: THREE.Group; foot: THREE.Group; glowRing: THREE.Mesh };
  tailPivot?: THREE.Group;
  chestSpark?: THREE.Mesh;
  hoverCollar?: THREE.Mesh;
  waistEmitter?: THREE.Group;
  chatBubble?: { group: THREE.Group; dots: THREE.Mesh[] };
  orbitRig?: {
    group: THREE.Group;
    rings: THREE.Mesh[];
    nodes: THREE.Mesh[];
  };
  floorShadow?: THREE.Mesh;
  bokehGroup?: THREE.Group;
  accentGlowMat: THREE.MeshStandardMaterial;
  drawFace: (
    expr: NekoExpression,
    lookX: number,
    lookY: number,
    blinkAmt: number,
    elapsed: number,
    speakAmt?: number
  ) => void;
  spawnBoopBurst: (scene: THREE.Scene) => BurstParticle[];
  dispose: () => void;
}

export interface BurstParticle {
  mesh: THREE.Mesh;
  vel: THREE.Vector3;
  rotVel: THREE.Vector3;
  life: number;
}

function clamp(v: number, lo: number, hi: number) {
  return Math.max(lo, Math.min(hi, v));
}

function spow(v: number, e: number) {
  return Math.sign(v) * Math.pow(Math.abs(v), e);
}

function evalSuperellipsoid(
  rx: number,
  ry: number,
  rz: number,
  e1: number,
  e2: number,
  phi: number,
  theta: number
) {
  const cosT = Math.cos(theta);
  const sinT = Math.sin(theta);
  const cosP = Math.cos(phi);
  const sinP = Math.sin(phi);
  const ce1 = spow(cosT, e1);
  return new THREE.Vector3(
    rx * ce1 * spow(cosP, e2),
    ry * spow(sinT, e1),
    rz * ce1 * spow(sinP, e2)
  );
}

export function createStudioEnvironment(renderer: THREE.WebGLRenderer, isDark: boolean) {
  const pmrem = new THREE.PMREMGenerator(renderer);
  pmrem.compileEquirectangularShader();

  const envScene = new THREE.Scene();
  envScene.background = new THREE.Color(isDark ? '#0c1428' : '#dde8fb');

  const envDisposables: Array<{ dispose: () => void }> = [];
  const addPanel = (
    w: number,
    h: number,
    pos: [number, number, number],
    hex: string,
    mult: number,
    rotX?: number
  ) => {
    const geo = new THREE.PlaneGeometry(w, h);
    const mat = new THREE.MeshBasicMaterial({
      color: new THREE.Color(hex).multiplyScalar(mult),
      side: THREE.DoubleSide,
    });
    const m = new THREE.Mesh(geo, mat);
    m.position.set(pos[0], pos[1], pos[2]);
    if (typeof rotX === 'number') m.rotation.x = rotX;
    else m.lookAt(0, 0, 0);
    envScene.add(m);
    envDisposables.push(geo, mat);
  };

  addPanel(16, 10, [0, 7.5, 2], '#ffffff', isDark ? 2.6 : 2.2, Math.PI / 2);
  addPanel(9, 10, [-8, 2.5, 5], '#eef6ff', 2.2);
  addPanel(4.5, 11, [8.5, 1.5, 2.5], '#38e8ff', isDark ? 2.5 : 1.9);
  addPanel(14, 8, [0, 2.0, -9], '#2563eb', isDark ? 1.7 : 1.1);
  addPanel(20, 20, [0, -6, 0], isDark ? '#1a2847' : '#c6d8f5', 1.0, -Math.PI / 2);

  const envRT = pmrem.fromScene(envScene, 0.04);
  pmrem.dispose();
  envDisposables.forEach((d) => d.dispose());
  return envRT;
}

export function createNekoRobotRig(
  variant: NekoVariant,
  isDark: boolean,
  palette: NekoPalette = CREATSIFY_NEKO_PALETTE
): NekoRig {
  const isCompact = variant === 'chatbot-icon' || variant === 'mini-corner';
  const segScale = isCompact ? 0.58 : variant === 'card-avatar' ? 0.72 : 1.0;

  const disposables: Array<{ dispose: () => void }> = [];
  const track = <T extends { dispose: () => void }>(res: T): T => {
    disposables.push(res);
    return res;
  };

  function createSuperellipsoidGeometry(
    rx: number,
    ry: number,
    rz: number,
    e1: number,
    e2: number,
    uSegs = 48,
    vSegs = 36
  ) {
    const uS = Math.max(14, Math.round(uSegs * segScale));
    const vS = Math.max(12, Math.round(vSegs * segScale));
    const geo = track(new THREE.BufferGeometry());
    const positions: number[] = [];
    const uvs: number[] = [];
    const indices: number[] = [];

    for (let iy = 0; iy <= vS; iy++) {
      const v = iy / vS;
      const theta = (v - 0.5) * Math.PI * 0.9992;
      for (let ix = 0; ix <= uS; ix++) {
        const u = ix / uS;
        const phi = u * Math.PI * 2;
        const p = evalSuperellipsoid(rx, ry, rz, e1, e2, phi, theta);
        positions.push(p.x, p.y, p.z);
        uvs.push(u, v);
      }
    }

    const stride = uS + 1;
    for (let iy = 0; iy < vS; iy++) {
      for (let ix = 0; ix < uS; ix++) {
        const a = iy * stride + ix;
        const b = a + 1;
        const c = a + stride + 1;
        const d = a + stride;
        indices.push(a, d, c, a, c, b);
      }
    }

    geo.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    geo.setAttribute('uv', new THREE.Float32BufferAttribute(uvs, 2));
    geo.setIndex(indices);
    geo.computeVertexNormals();
    return geo;
  }

  function createConformalVisorPatch(
    rx: number,
    ry: number,
    rz: number,
    e1: number,
    e2: number,
    phiHalf: number,
    thetaHalf: number,
    offsetOut: number,
    segs = 40
  ) {
    const S = Math.max(18, Math.round(segs * segScale));
    const geo = track(new THREE.BufferGeometry());
    const positions: number[] = [];
    const uvs: number[] = [];
    const indices: number[] = [];
    const sqPower = 4.0;

    for (let iy = 0; iy <= S; iy++) {
      const gy = (iy / S) * 2 - 1;
      for (let ix = 0; ix <= S; ix++) {
        const gx = (ix / S) * 2 - 1;
        const cx = gx * Math.sqrt(Math.max(0, 1 - 0.5 * gy * gy));
        const cy = gy * Math.sqrt(Math.max(0, 1 - 0.5 * gx * gx));
        const r = Math.hypot(cx, cy);

        let sx = cx;
        let sy = cy;
        if (r > 1e-6) {
          const ux = cx / r;
          const uy = cy / r;
          const rSq =
            1 /
            Math.pow(
              Math.pow(Math.abs(ux), sqPower) + Math.pow(Math.abs(uy), sqPower),
              1 / sqPower
            );
          const smoothScale = 1 + (rSq - 1) * (r * r);
          sx = cx * smoothScale;
          sy = cy * smoothScale;
        }

        const phi = Math.PI / 2 - sx * phiHalf;
        const theta = sy * thetaHalf;
        const lensLift = offsetOut + Math.max(0, 1 - r * r) * 0.01;
        const p = evalSuperellipsoid(
          rx + lensLift,
          ry + lensLift,
          rz + lensLift,
          e1,
          e2,
          phi,
          theta
        );
        positions.push(p.x, p.y, p.z);
        uvs.push((sx + 1) * 0.5, (sy + 1) * 0.5);
      }
    }

    const stride = S + 1;
    for (let iy = 0; iy < S; iy++) {
      for (let ix = 0; ix < S; ix++) {
        const a = iy * stride + ix;
        const b = a + 1;
        const c = a + stride + 1;
        const d = a + stride;
        indices.push(a, b, c, a, c, d);
      }
    }

    geo.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    geo.setAttribute('uv', new THREE.Float32BufferAttribute(uvs, 2));
    geo.setIndex(indices);
    geo.computeVertexNormals();
    return geo;
  }

  function createTaperedTubeGeometry(
    curve: THREE.CatmullRomCurve3,
    tubularSegs: number,
    radialSegs: number,
    radiusFn: (u: number) => number,
    closed = false
  ) {
    const tS = Math.max(20, Math.round(tubularSegs * segScale));
    const rS = Math.max(10, Math.round(radialSegs * segScale));
    const frames = curve.computeFrenetFrames(tS, closed);
    const geo = track(new THREE.BufferGeometry());
    const positions: number[] = [];
    const uvs: number[] = [];
    const indices: number[] = [];

    for (let i = 0; i <= tS; i++) {
      const u = i / tS;
      const center = curve.getPointAt(u);
      const N = frames.normals[i];
      const B = frames.binormals[i];
      const rad = Math.max(0.002, radiusFn(u));

      for (let j = 0; j <= rS; j++) {
        const v = j / rS;
        const a = v * Math.PI * 2;
        const cosA = Math.cos(a);
        const sinA = Math.sin(a);
        positions.push(
          center.x + rad * (cosA * N.x + sinA * B.x),
          center.y + rad * (cosA * N.y + sinA * B.y),
          center.z + rad * (cosA * N.z + sinA * B.z)
        );
        uvs.push(u, v);
      }
    }

    const stride = rS + 1;
    for (let i = 0; i < tS; i++) {
      for (let j = 0; j < rS; j++) {
        const a = i * stride + j;
        const b = (i + 1) * stride + j;
        const c = (i + 1) * stride + (j + 1);
        const d = i * stride + (j + 1);
        indices.push(a, d, c, a, c, b);
      }
    }

    geo.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
    geo.setAttribute('uv', new THREE.Float32BufferAttribute(uvs, 2));
    geo.setIndex(indices);
    geo.computeVertexNormals();
    return geo;
  }

  function createConformalBezel(
    rx: number,
    ry: number,
    rz: number,
    e1: number,
    e2: number,
    phiHalf: number,
    thetaHalf: number,
    offsetOut: number,
    tubeR: number
  ) {
    const sqPower = 4.0;
    const pts: THREE.Vector3[] = [];
    const steps = isCompact ? 48 : 72;
    for (let i = 0; i < steps; i++) {
      const a = (i / steps) * Math.PI * 2;
      const sx = spow(Math.cos(a), 2 / sqPower);
      const sy = spow(Math.sin(a), 2 / sqPower);
      const phi = Math.PI / 2 - sx * phiHalf;
      const theta = sy * thetaHalf;
      pts.push(
        evalSuperellipsoid(rx + offsetOut, ry + offsetOut, rz + offsetOut, e1, e2, phi, theta)
      );
    }
    const loop = new THREE.CatmullRomCurve3(pts, true, 'catmullrom', 0.25);
    return createTaperedTubeGeometry(loop, 84, 14, () => tubeR, true);
  }

  function createSculptedCatEarGeometry(width = 0.24, height = 0.34, depth = 0.16) {
    const geo = track(
      new THREE.SphereGeometry(1, Math.max(16, Math.round(30 * segScale)), Math.max(12, Math.round(22 * segScale)))
    );
    const pos = geo.attributes.position;
    const v = new THREE.Vector3();
    for (let i = 0; i < pos.count; i++) {
      v.fromBufferAttribute(pos, i);
      const t = clamp((v.y + 1) * 0.5, 0, 1);
      const taperX = Math.pow(1 - t * 0.78, 0.72);
      const taperZ = Math.pow(1 - t * 0.68, 0.75);
      const x = v.x * width * taperX;
      const y = t * height;
      let z = v.z * depth * taperZ;
      if (v.z > 0 && t > 0.15 && t < 0.85) {
        const cup = Math.sin(((t - 0.15) / 0.7) * Math.PI) * (1 - Math.abs(v.x));
        z -= cup * depth * 0.34;
      }
      z -= Math.pow(t, 2.2) * 0.045;
      pos.setXYZ(i, x, y, z);
    }
    geo.computeVertexNormals();
    return geo;
  }

  function applyVerticalGradient(
    geometry: THREE.BufferGeometry,
    topHex: string,
    bottomHex: string
  ) {
    geometry.computeBoundingBox();
    const bb = geometry.boundingBox!;
    const minY = bb.min.y;
    const spanY = Math.max(0.0001, bb.max.y - minY);
    const cTop = new THREE.Color(topHex);
    const cBot = new THREE.Color(bottomHex);
    const tmp = new THREE.Color();

    const pos = geometry.attributes.position;
    const colors = new Float32Array(pos.count * 3);
    for (let i = 0; i < pos.count; i++) {
      const t = clamp((pos.getY(i) - minY) / spanY, 0, 1);
      const smoothT = t * t * (3 - 2 * t);
      tmp.copy(cBot).lerp(cTop, smoothT);
      colors[i * 3] = tmp.r;
      colors[i * 3 + 1] = tmp.g;
      colors[i * 3 + 2] = tmp.b;
    }
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
  }

  function createSparkleGeometry(radius = 0.08, thickness = 0.018) {
    const shape = new THREE.Shape();
    const inner = radius * 0.3;
    for (let i = 0; i < 8; i++) {
      const r = i % 2 === 0 ? radius : inner;
      const a = (i / 8) * Math.PI * 2 + Math.PI / 2;
      const x = Math.cos(a) * r;
      const y = Math.sin(a) * r;
      if (i === 0) shape.moveTo(x, y);
      else shape.lineTo(x, y);
    }
    shape.closePath();
    const geo = track(
      new THREE.ExtrudeGeometry(shape, {
        depth: thickness,
        bevelEnabled: true,
        bevelThickness: thickness * 0.35,
        bevelSize: radius * 0.06,
        bevelSegments: 2,
      })
    );
    geo.center();
    return geo;
  }

  function createHeartGeometry(scale = 0.085) {
    const s = new THREE.Shape();
    s.moveTo(0, -0.75 * scale);
    s.bezierCurveTo(-1.1 * scale, -0.1 * scale, -1.15 * scale, 0.85 * scale, -0.48 * scale, 0.92 * scale);
    s.bezierCurveTo(-0.14 * scale, 0.96 * scale, 0, 0.62 * scale, 0, 0.44 * scale);
    s.bezierCurveTo(0, 0.62 * scale, 0.14 * scale, 0.96 * scale, 0.48 * scale, 0.92 * scale);
    s.bezierCurveTo(1.15 * scale, 0.85 * scale, 1.1 * scale, -0.1 * scale, 0, -0.75 * scale);
    const geo = track(
      new THREE.ExtrudeGeometry(s, {
        depth: 0.34 * scale,
        bevelEnabled: true,
        bevelThickness: 0.14 * scale,
        bevelSize: 0.1 * scale,
        bevelSegments: 3,
      })
    );
    geo.center();
    return geo;
  }

  // ---- OLED Canvas Texture (512x304 for mini/compact, 1024x608 for hero/orbit) ----
  const faceW = isCompact ? 512 : 1024;
  const faceH = isCompact ? 304 : 608;
  const faceCanvas = document.createElement('canvas');
  faceCanvas.width = faceW;
  faceCanvas.height = faceH;
  const faceCtx = faceCanvas.getContext('2d')!;
  const faceTex = track(new THREE.CanvasTexture(faceCanvas));
  faceTex.colorSpace = THREE.SRGBColorSpace;
  faceTex.minFilter = THREE.LinearFilter;
  faceTex.magFilter = THREE.LinearFilter;

  const oledGridCanvas = document.createElement('canvas');
  oledGridCanvas.width = 1024;
  oledGridCanvas.height = 608;
  const gctx = oledGridCanvas.getContext('2d')!;
  gctx.fillStyle = 'rgba(0, 0, 0, 0.08)';
  for (let y = 0; y < 608; y += 6) {
    gctx.fillRect(0, y, 1024, 1.5);
  }
  const vig = gctx.createRadialGradient(512, 304, 240, 512, 304, 560);
  vig.addColorStop(0.0, 'rgba(0, 0, 0, 0)');
  vig.addColorStop(0.76, 'rgba(0, 0, 0, 0.14)');
  vig.addColorStop(1.0, 'rgba(0, 0, 0, 0.68)');
  gctx.fillStyle = vig;
  gctx.fillRect(0, 0, 1024, 608);

  const drawFace = (
    expr: NekoExpression,
    lookX: number,
    lookY: number,
    blinkAmt: number,
    elapsed: number,
    speakAmt = 0
  ) => {
    const ctx = faceCtx;
    const scaleX = faceW / 1024;
    const scaleY = faceH / 608;
    ctx.save();
    ctx.setTransform(scaleX, 0, 0, scaleY, 0, 0);

    const W = 1024;
    const H = 608;
    ctx.fillStyle = '#000000';
    ctx.fillRect(0, 0, W, H);

    const pal = {
      deep: palette.eyeDeep,
      mid: palette.eyeMid,
      bright: palette.eyeBright,
    };
    const accentHex = palette.accent;
    const cheekHex = palette.cheek;
    const ox = clamp(lookX, -1, 1) * 28;
    const oy = clamp(-lookY, -1, 1) * 18;

    const screenHalo = ctx.createRadialGradient(W / 2 + ox * 0.35, H * 0.46, 36, W / 2, H * 0.5, 440);
    screenHalo.addColorStop(0.0, 'rgba(14, 54, 132, 0.24)');
    screenHalo.addColorStop(0.58, 'rgba(4, 16, 46, 0.09)');
    screenHalo.addColorStop(1.0, 'rgba(0, 0, 0, 0)');
    ctx.fillStyle = screenHalo;
    ctx.fillRect(0, 0, W, H);

    const drawCheek = (cx: number, cy: number, alpha: number) => {
      ctx.save();
      const g = ctx.createRadialGradient(cx, cy, 4, cx, cy, 66);
      g.addColorStop(0.0, cheekHex);
      g.addColorStop(0.46, `${cheekHex}44`);
      g.addColorStop(1.0, 'rgba(0, 0, 0, 0)');
      ctx.globalAlpha = alpha;
      ctx.fillStyle = g;
      ctx.beginPath();
      ctx.ellipse(cx, cy, 66, 34, 0, 0, Math.PI * 2);
      ctx.fill();
      ctx.restore();
    };

    const cheekAlpha = expr === 'love' || expr === 'joy' || expr === 'funny' ? 0.5 : 0.3;
    drawCheek(206 + ox * 0.45, 412 + oy * 0.38, cheekAlpha);
    drawCheek(818 + ox * 0.45, 412 + oy * 0.38, cheekAlpha);

    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    const drawJewelEye = (
      cx: number,
      cy: number,
      rx: number,
      ry: number,
      pupilOffX: number,
      pupilOffY: number,
      upperLid = 0,
      sideSign = 1
    ) => {
      ctx.save();
      ctx.translate(cx, cy);

      const openFactor = Math.max(0.05, 1 - blinkAmt * 0.95);
      if (blinkAmt > 0.78) {
        ctx.strokeStyle = pal.bright;
        ctx.shadowColor = accentHex;
        ctx.shadowBlur = 22;
        ctx.lineWidth = 14;
        ctx.beginPath();
        ctx.arc(0, 18, rx * 0.76, Math.PI * 1.15, Math.PI * 1.85);
        ctx.stroke();

        ctx.strokeStyle = '#ffffff';
        ctx.shadowBlur = 0;
        ctx.lineWidth = 5;
        ctx.beginPath();
        ctx.arc(0, 15, rx * 0.76, Math.PI * 1.24, Math.PI * 1.76);
        ctx.stroke();
        ctx.restore();
        return;
      }

      ctx.scale(1 + blinkAmt * 0.05, openFactor);

      const outerBloom = ctx.createRadialGradient(0, 0, rx * 0.65, 0, 0, rx * 1.38);
      outerBloom.addColorStop(0.0, `${pal.mid}55`);
      outerBloom.addColorStop(0.55, `${pal.deep}24`);
      outerBloom.addColorStop(1.0, 'rgba(0,0,0,0)');
      ctx.fillStyle = outerBloom;
      ctx.beginPath();
      ctx.ellipse(0, 0, rx * 1.38, ry * 1.38, 0, 0, Math.PI * 2);
      ctx.fill();

      ctx.save();
      ctx.shadowColor = accentHex;
      ctx.shadowBlur = 24;
      const irisGrad = ctx.createRadialGradient(0, ry * 0.24, rx * 0.06, 0, 0, Math.max(rx, ry) * 1.04);
      irisGrad.addColorStop(0.0, '#ffffff');
      irisGrad.addColorStop(0.22, pal.bright);
      irisGrad.addColorStop(0.6, pal.mid);
      irisGrad.addColorStop(0.92, pal.deep);
      irisGrad.addColorStop(1.0, '#030d2b');
      ctx.fillStyle = irisGrad;
      ctx.beginPath();
      ctx.ellipse(0, 0, rx, ry, 0, 0, Math.PI * 2);
      ctx.fill();
      ctx.restore();

      ctx.save();
      ctx.beginPath();
      ctx.ellipse(0, 0, rx, ry, 0, 0, Math.PI * 2);
      ctx.clip();

      ctx.save();
      ctx.globalAlpha = 0.16;
      ctx.strokeStyle = '#ffffff';
      ctx.lineWidth = 2.2;
      for (let i = 0; i < 18; i++) {
        const a = (i / 18) * Math.PI * 2;
        ctx.beginPath();
        ctx.moveTo(Math.cos(a) * rx * 0.44, Math.sin(a) * ry * 0.44);
        ctx.lineTo(Math.cos(a) * rx * 0.94, Math.sin(a) * ry * 0.94);
        ctx.stroke();
      }
      ctx.restore();

      const cresGrad = ctx.createRadialGradient(0, ry * 0.68, 4, 0, ry * 0.56, rx * 0.88);
      cresGrad.addColorStop(0.0, '#ffffff');
      cresGrad.addColorStop(0.42, pal.bright);
      cresGrad.addColorStop(1.0, 'rgba(255,255,255,0)');
      ctx.fillStyle = cresGrad;
      ctx.globalAlpha = 0.78;
      ctx.beginPath();
      ctx.ellipse(0, ry * 0.58, rx * 0.8, ry * 0.48, 0, 0, Math.PI * 2);
      ctx.fill();
      ctx.globalAlpha = 1.0;

      const px = clamp(pupilOffX, -rx * 0.26, rx * 0.26);
      const py = clamp(pupilOffY, -ry * 0.24, ry * 0.24);
      const pRx = rx * 0.44;
      const pRy = ry * 0.48;

      ctx.strokeStyle = `${pal.bright}99`;
      ctx.lineWidth = 5;
      ctx.beginPath();
      ctx.ellipse(px, py, pRx + 3, pRy + 3, 0, 0, Math.PI * 2);
      ctx.stroke();

      const pGrad = ctx.createRadialGradient(px, py - 8, 4, px, py, pRx * 1.15);
      pGrad.addColorStop(0.0, '#01030a');
      pGrad.addColorStop(0.76, '#05102b');
      pGrad.addColorStop(1.0, pal.deep);
      ctx.fillStyle = pGrad;
      ctx.beginPath();
      ctx.ellipse(px, py, pRx, pRy, 0, 0, Math.PI * 2);
      ctx.fill();

      const topShade = ctx.createLinearGradient(0, -ry, 0, -ry * 0.08);
      topShade.addColorStop(0.0, 'rgba(1, 5, 18, 0.56)');
      topShade.addColorStop(0.55, 'rgba(2, 8, 28, 0.20)');
      topShade.addColorStop(1.0, 'rgba(2, 8, 28, 0)');
      ctx.fillStyle = topShade;
      ctx.fillRect(-rx, -ry, rx * 2, ry * 1.1);

      const hlX = px * 0.35 - rx * 0.3;
      const hlY = py * 0.35 - ry * 0.32;
      ctx.fillStyle = 'rgba(255, 255, 255, 0.98)';
      ctx.beginPath();
      ctx.ellipse(hlX, hlY, rx * 0.23, ry * 0.18, -0.32, 0, Math.PI * 2);
      ctx.fill();

      ctx.fillStyle = 'rgba(255, 255, 255, 0.88)';
      ctx.beginPath();
      ctx.arc(px * 0.35 + rx * 0.28, py * 0.35 + ry * 0.24, rx * 0.095, 0, Math.PI * 2);
      ctx.fill();

      ctx.fillStyle = 'rgba(255, 255, 255, 0.72)';
      ctx.beginPath();
      ctx.arc(hlX + rx * 0.26, hlY - ry * 0.12, rx * 0.048, 0, Math.PI * 2);
      ctx.fill();

      if (upperLid > 0) {
        const lidY = -ry + ry * 2 * upperLid;
        ctx.fillStyle = '#000000';
        ctx.beginPath();
        ctx.moveTo(-rx - 8, -ry - 8);
        ctx.lineTo(rx + 8, -ry - 8);
        ctx.lineTo(rx + 8, lidY);
        ctx.quadraticCurveTo(0, lidY + 18, -rx - 8, lidY);
        ctx.closePath();
        ctx.fill();

        ctx.strokeStyle = pal.bright;
        ctx.shadowColor = accentHex;
        ctx.shadowBlur = 12;
        ctx.lineWidth = 6.5;
        ctx.beginPath();
        ctx.moveTo(-rx, lidY);
        ctx.quadraticCurveTo(0, lidY + 18, rx, lidY);
        ctx.stroke();
      }
      ctx.restore();

      ctx.strokeStyle = 'rgba(220, 248, 255, 0.42)';
      ctx.lineWidth = 3.2;
      ctx.beginPath();
      ctx.ellipse(0, 0, rx - 1.5, ry - 1.5, 0, 0, Math.PI * 2);
      ctx.stroke();

      ctx.save();
      ctx.strokeStyle = pal.bright;
      ctx.globalAlpha = 0.58;
      ctx.shadowColor = accentHex;
      ctx.shadowBlur = 10;
      ctx.lineWidth = 7.5;
      const browTilt = upperLid > 0 ? 0.1 * sideSign : -0.04 * sideSign;
      ctx.rotate(browTilt);
      ctx.beginPath();
      ctx.arc(0, -ry * 0.36, rx * 0.92, Math.PI * 1.24, Math.PI * 1.76);
      ctx.stroke();
      ctx.restore();

      ctx.restore();
    };

    const drawHeartEye = (cx: number, cy: number, size: number, pulse: number) => {
      ctx.save();
      ctx.translate(cx, cy);
      const sc = size * (1 + Math.sin(pulse) * 0.07);
      ctx.scale(sc, sc);
      ctx.shadowColor = '#ff4d94';
      ctx.shadowBlur = 30;
      const hg = ctx.createRadialGradient(-0.22, -0.32, 0.08, 0, 0, 1.3);
      hg.addColorStop(0.0, '#ffe3f1');
      hg.addColorStop(0.42, '#ff62aa');
      hg.addColorStop(1.0, '#d9166b');
      ctx.fillStyle = hg;
      ctx.beginPath();
      ctx.moveTo(0, 0.85);
      ctx.bezierCurveTo(-1.15, 0.15, -1.25, -0.82, -0.52, -0.95);
      ctx.bezierCurveTo(-0.15, -1.02, 0, -0.62, 0, -0.42);
      ctx.bezierCurveTo(0, -0.62, 0.15, -1.02, 0.52, -0.95);
      ctx.bezierCurveTo(1.25, -0.82, 1.15, 0.15, 0, 0.85);
      ctx.closePath();
      ctx.fill();

      ctx.shadowBlur = 0;
      ctx.fillStyle = 'rgba(255,255,255,0.92)';
      ctx.beginPath();
      ctx.ellipse(-0.42, -0.42, 0.24, 0.14, -0.5, 0, Math.PI * 2);
      ctx.fill();
      ctx.restore();
    };

    const drawHappyArcEye = (cx: number, cy: number, r: number) => {
      ctx.save();
      ctx.translate(cx, cy + 22);
      ctx.strokeStyle = pal.mid;
      ctx.shadowColor = accentHex;
      ctx.shadowBlur = 26;
      ctx.lineWidth = 24;
      ctx.beginPath();
      ctx.arc(0, 0, r, Math.PI * 1.16, Math.PI * 1.84);
      ctx.stroke();

      ctx.strokeStyle = pal.bright;
      ctx.shadowBlur = 12;
      ctx.lineWidth = 14;
      ctx.beginPath();
      ctx.arc(0, -1, r, Math.PI * 1.19, Math.PI * 1.81);
      ctx.stroke();

      ctx.strokeStyle = '#ffffff';
      ctx.shadowBlur = 0;
      ctx.lineWidth = 6;
      ctx.beginPath();
      ctx.arc(0, -3, r - 1, Math.PI * 1.26, Math.PI * 1.74);
      ctx.stroke();
      ctx.restore();
    };

    const drawStarEye = (cx: number, cy: number, r: number, rot: number) => {
      ctx.save();
      ctx.translate(cx, cy);
      ctx.rotate(rot);
      ctx.shadowColor = accentHex;
      ctx.shadowBlur = 28;
      const sg = ctx.createRadialGradient(0, 0, 6, 0, 0, r);
      sg.addColorStop(0.0, '#ffffff');
      sg.addColorStop(0.48, pal.bright);
      sg.addColorStop(1.0, pal.mid);
      ctx.fillStyle = sg;
      ctx.beginPath();
      for (let i = 0; i < 8; i++) {
        const rad = i % 2 === 0 ? r : r * 0.36;
        const a = (i * Math.PI) / 4;
        const px = Math.cos(a) * rad;
        const py = Math.sin(a) * rad;
        if (i === 0) ctx.moveTo(px, py);
        else ctx.lineTo(px, py);
      }
      ctx.closePath();
      ctx.fill();
      ctx.restore();
    };

    const eyeY = 272 + oy * 0.5;
    const leftX = 336 + ox * 0.58;
    const rightX = 688 + ox * 0.58;
    const pX = ox * 0.45;
    const pY = oy * 0.4;

    if (expr === 'love') {
      drawHeartEye(leftX, eyeY, 84, elapsed * 12);
      drawHeartEye(rightX, eyeY, 84, elapsed * 12 + 0.5);
    } else if (expr === 'joy') {
      drawHappyArcEye(leftX, eyeY, 76);
      drawHappyArcEye(rightX, eyeY, 76);
    } else if (expr === 'star') {
      drawStarEye(leftX, eyeY, 90, elapsed * 3.5);
      drawStarEye(rightX, eyeY, 90, -elapsed * 3.5);
    } else if (expr === 'funny') {
      drawJewelEye(leftX, eyeY - 4, 96, 102, 22, -8, 0, -1);
      drawJewelEye(rightX, eyeY + 4, 82, 88, -22, 8, 0, 1);
    } else if (expr === 'think') {
      drawJewelEye(leftX, eyeY, 92, 98, 22, -22, 0.24, -1);
      drawJewelEye(rightX, eyeY, 92, 98, 22, -22, 0.08, 1);
    } else if (expr === 'excited') {
      drawHappyArcEye(leftX, eyeY, 76);
      drawJewelEye(rightX, eyeY, 94, 100, pX, pY, 0, 1);
    } else {
      drawJewelEye(leftX, eyeY, 94, 100, pX, pY, 0, -1);
      drawJewelEye(rightX, eyeY, 94, 100, pX, pY, 0, 1);
    }

    const mx = W / 2 + ox * 0.42;
    const breath = Math.sin(elapsed * 2.4) * 1.5;
    const talkOpen = speakAmt > 0 ? Math.abs(Math.sin(elapsed * 28)) * speakAmt * 18 : 0;
    const my = 442 + oy * 0.3 + breath * 0.4;

    // Sculpted Mini Kitty Nose Gem
    ctx.save();
    const ny = my - 40;
    const noseGrad = ctx.createRadialGradient(mx - 2, ny - 3, 1, mx, ny + 1, 13);
    noseGrad.addColorStop(0.0, '#ffffff');
    noseGrad.addColorStop(0.38, '#ffd4ea');
    noseGrad.addColorStop(0.82, cheekHex);
    noseGrad.addColorStop(1.0, '#b8256a');
    ctx.fillStyle = noseGrad;
    ctx.shadowColor = cheekHex;
    ctx.shadowBlur = 10;
    ctx.beginPath();
    ctx.moveTo(mx - 9.5, ny - 2.5);
    ctx.quadraticCurveTo(mx, ny - 6.5, mx + 9.5, ny - 2.5);
    ctx.quadraticCurveTo(mx + 8.5, ny + 4.5, mx, ny + 6.5);
    ctx.quadraticCurveTo(mx - 8.5, ny + 4.5, mx - 9.5, ny - 2.5);
    ctx.closePath();
    ctx.fill();
    ctx.shadowBlur = 0;
    ctx.fillStyle = 'rgba(255, 255, 255, 0.92)';
    ctx.beginPath();
    ctx.ellipse(mx - 2.6, ny - 2.2, 2.8, 1.4, -0.2, 0, Math.PI * 2);
    ctx.fill();
    ctx.restore();

    const drawTaperedSmile = (
      cx: number,
      cy: number,
      halfW: number,
      dipY: number,
      catLift: number,
      strokeColor: string,
      glowColor: string
    ) => {
      ctx.save();
      ctx.shadowColor = glowColor;
      ctx.shadowBlur = 18;
      ctx.fillStyle = strokeColor;
      ctx.beginPath();
      ctx.moveTo(cx - halfW, cy - 5);
      ctx.bezierCurveTo(
        cx - halfW * 0.56,
        cy + dipY - 3,
        cx - halfW * 0.14,
        cy + dipY * 0.7 - catLift - 3,
        cx,
        cy - catLift - 3.5
      );
      ctx.bezierCurveTo(
        cx + halfW * 0.14,
        cy + dipY * 0.7 - catLift - 3,
        cx + halfW * 0.56,
        cy + dipY - 3,
        cx + halfW,
        cy - 5
      );
      ctx.quadraticCurveTo(cx + halfW + 2, cy - 1.5, cx + halfW - 2, cy + 1.2);
      ctx.bezierCurveTo(
        cx + halfW * 0.54,
        cy + dipY + 5.5,
        cx + halfW * 0.14,
        cy + dipY * 0.7 - catLift + 5.5,
        cx,
        cy - catLift + 4.5
      );
      ctx.bezierCurveTo(
        cx - halfW * 0.14,
        cy + dipY * 0.7 - catLift + 5.5,
        cx - halfW * 0.54,
        cy + dipY + 5.5,
        cx - halfW + 2,
        cy + 1.2
      );
      ctx.quadraticCurveTo(cx - halfW - 2, cy - 1.5, cx - halfW, cy - 5);
      ctx.closePath();
      ctx.fill();

      ctx.shadowBlur = 0;
      ctx.strokeStyle = 'rgba(255, 255, 255, 0.85)';
      ctx.lineWidth = 2.4;
      ctx.beginPath();
      ctx.moveTo(cx - halfW * 0.78, cy + dipY * 0.35);
      ctx.quadraticCurveTo(cx - halfW * 0.35, cy + dipY * 0.85, cx, cy - catLift + 0.5);
      ctx.quadraticCurveTo(cx + halfW * 0.35, cy + dipY * 0.85, cx + halfW * 0.78, cy + dipY * 0.35);
      ctx.stroke();
      ctx.restore();
    };

    const drawVolumetricOpenMouth = (
      cx: number,
      cy: number,
      halfW: number,
      openH: number,
      strokeColor: string,
      glowColor: string
    ) => {
      ctx.save();
      const traceMouthPath = () => {
        ctx.beginPath();
        ctx.moveTo(cx - halfW, cy - 4);
        ctx.quadraticCurveTo(cx - halfW * 0.45, cy + 7, cx, cy - 4);
        ctx.quadraticCurveTo(cx + halfW * 0.45, cy + 7, cx + halfW, cy - 4);
        ctx.bezierCurveTo(
          cx + halfW * 0.94,
          cy + openH * 1.18,
          cx - halfW * 0.94,
          cy + openH * 1.18,
          cx - halfW,
          cy - 4
        );
        ctx.closePath();
      };

      traceMouthPath();
      const cavGrad = ctx.createLinearGradient(cx, cy - 4, cx, cy + openH);
      cavGrad.addColorStop(0.0, '#04091a');
      cavGrad.addColorStop(0.45, '#0c1c3e');
      cavGrad.addColorStop(1.0, '#182b52');
      ctx.fillStyle = cavGrad;
      ctx.shadowColor = glowColor;
      ctx.shadowBlur = 16;
      ctx.fill();

      ctx.save();
      traceMouthPath();
      ctx.clip();
      ctx.shadowBlur = 0;
      const tY = cy + openH * 0.68;
      const tGrad = ctx.createRadialGradient(cx, tY - 6, 4, cx, tY + 6, halfW * 0.72);
      tGrad.addColorStop(0.0, '#ffc4de');
      tGrad.addColorStop(0.45, '#ff73ad');
      tGrad.addColorStop(1.0, '#b5205e');
      ctx.fillStyle = tGrad;
      ctx.beginPath();
      ctx.ellipse(cx, tY + 6, halfW * 0.66, openH * 0.56, 0, 0, Math.PI * 2);
      ctx.fill();
      ctx.restore();

      traceMouthPath();
      ctx.strokeStyle = strokeColor;
      ctx.shadowColor = glowColor;
      ctx.shadowBlur = 14;
      ctx.lineWidth = 5.5;
      ctx.stroke();
      ctx.restore();
    };

    if (expr === 'love') {
      drawTaperedSmile(mx, my, 50, 22, 8, '#ffc2e0', '#ff4d94');
    } else if (expr === 'funny') {
      ctx.save();
      const bx = mx + 14;
      const by = my + 17;
      const blepGrad = ctx.createRadialGradient(bx - 3, by - 4, 3, bx, by + 4, 28);
      blepGrad.addColorStop(0.0, '#ffc7e2');
      blepGrad.addColorStop(0.5, '#ff6ca8');
      blepGrad.addColorStop(1.0, '#b81d60');
      ctx.fillStyle = blepGrad;
      ctx.shadowColor = '#ff4d94';
      ctx.shadowBlur = 12;
      ctx.beginPath();
      ctx.ellipse(bx, by + 3, 17, 21, 0.14, 0, Math.PI * 2);
      ctx.fill();
      ctx.restore();
      drawTaperedSmile(mx, my - 1, 48, 17, 6, pal.bright, accentHex);
    } else if (expr === 'think') {
      drawTaperedSmile(mx + 6, my + 3, 28, 7, 2, pal.bright, accentHex);
    } else if (expr === 'excited' || expr === 'star' || expr === 'joy') {
      const openAnim = 36 + Math.sin(elapsed * 8) * 3 + talkOpen * 0.4;
      drawVolumetricOpenMouth(mx, my - 4, 48, openAnim, pal.bright, accentHex);
    } else if (talkOpen > 3.5) {
      drawVolumetricOpenMouth(mx, my - 2, 34 + talkOpen * 0.4, 16 + talkOpen, pal.bright, accentHex);
    } else {
      drawTaperedSmile(mx, my, 44, 17, 6.5, pal.bright, accentHex);
    }

    ctx.drawImage(oledGridCanvas, 0, 0);
    ctx.restore();
    faceTex.needsUpdate = true;
  };

  // ---- Luxury Solid PBR Materials ----
  const shellGrad = track(
    new THREE.MeshPhysicalMaterial({
      color: new THREE.Color('#ffffff'),
      vertexColors: true,
      roughness: 0.18,
      metalness: 0.12,
      clearcoat: 1.0,
      clearcoatRoughness: 0.08,
      reflectivity: 0.95,
      envMapIntensity: 1.18,
    })
  );

  const shellSolid = track(
    new THREE.MeshPhysicalMaterial({
      color: new THREE.Color(palette.topColor),
      roughness: 0.2,
      metalness: 0.12,
      clearcoat: 0.95,
      clearcoatRoughness: 0.1,
      envMapIntensity: 1.1,
    })
  );

  const pearl = track(
    new THREE.MeshPhysicalMaterial({
      color: new THREE.Color(palette.pearl),
      roughness: 0.25,
      metalness: 0.04,
      clearcoat: 0.75,
      clearcoatRoughness: 0.15,
      envMapIntensity: 0.95,
    })
  );

  const gunmetal = track(
    new THREE.MeshStandardMaterial({
      color: new THREE.Color(palette.gunmetal),
      roughness: 0.34,
      metalness: 0.72,
      envMapIntensity: 1.05,
    })
  );

  const accentGlow = track(
    new THREE.MeshStandardMaterial({
      color: new THREE.Color(palette.accent),
      emissive: new THREE.Color(palette.accent),
      emissiveIntensity: 1.48,
      roughness: 0.15,
      metalness: 0.1,
    })
  );

  const earInner = track(
    new THREE.MeshStandardMaterial({
      color: new THREE.Color('#ffd0e8'),
      emissive: new THREE.Color(palette.cheek),
      emissiveIntensity: 0.65,
      roughness: 0.3,
      metalness: 0.05,
    })
  );

  const oledVisor = track(
    new THREE.MeshPhysicalMaterial({
      color: new THREE.Color('#02040a'),
      emissive: new THREE.Color('#ffffff'),
      emissiveMap: faceTex,
      emissiveIntensity: 1.14,
      roughness: 0.05,
      metalness: 0.04,
      clearcoat: 1.0,
      clearcoatRoughness: 0.03,
      reflectivity: 1.0,
      envMapIntensity: 0.9,
    })
  );

  const shadowMesh = (m: THREE.Mesh) => {
    if (!isCompact) {
      m.castShadow = true;
      m.receiveShadow = true;
    }
    return m;
  };

  // ---- Assemble Root & Selected Variant ----
  const root = new THREE.Group();
  root.name = `Creatsify_Neko_${variant}`;
  const bodyPivot = new THREE.Group();
  root.add(bodyPivot);

  const isHeadOnly = variant === 'chatbot-icon';
  const isBustOnly = variant === 'card-avatar';
  const isChibi = variant === 'mini-corner';
  const hasFullBody = variant === 'hero' || variant === 'full-stack-orbit' || isChibi;

  let torso: THREE.Group | undefined;
  let tailPivot: THREE.Group | undefined;
  let chestSpark: THREE.Mesh | undefined;
  let leftArm: NekoRig['leftArm'];
  let rightArm: NekoRig['rightArm'];
  let leftLeg: NekoRig['leftLeg'];
  let rightLeg: NekoRig['rightLeg'];
  let hoverCollar: THREE.Mesh | undefined;
  let waistEmitter: THREE.Group | undefined;
  let chatBubble: NekoRig['chatBubble'];
  let orbitRig: NekoRig['orbitRig'];

  // ---- A. TORSO (Included for Hero, Full-Stack Orbit, Card Avatar Bust, Mini Corner) ----
  if (!isHeadOnly) {
    torso = new THREE.Group();
    torso.position.set(0, isChibi ? -0.14 : -0.22, 0);
    if (isChibi) {
      torso.scale.set(0.84, 0.74, 0.84);
    }
    bodyPivot.add(torso);

    const torsoPts = isBustOnly
      ? [
          new THREE.Vector2(0.004, -0.26),
          new THREE.Vector2(0.32, -0.25),
          new THREE.Vector2(0.45, -0.14),
          new THREE.Vector2(0.46, 0.06),
          new THREE.Vector2(0.41, 0.26),
          new THREE.Vector2(0.33, 0.39),
          new THREE.Vector2(0.2, 0.45),
          new THREE.Vector2(0.004, 0.46),
        ]
      : [
          new THREE.Vector2(0.004, -0.44),
          new THREE.Vector2(0.22, -0.43),
          new THREE.Vector2(0.39, -0.34),
          new THREE.Vector2(0.47, -0.16),
          new THREE.Vector2(0.46, 0.06),
          new THREE.Vector2(0.41, 0.26),
          new THREE.Vector2(0.33, 0.39),
          new THREE.Vector2(0.2, 0.45),
          new THREE.Vector2(0.004, 0.46),
        ];

    const torsoGeo = track(new THREE.LatheGeometry(torsoPts, Math.max(24, Math.round(44 * segScale))));
    torsoGeo.scale(1.04, 1.0, 0.9);
    applyVerticalGradient(torsoGeo, palette.topColor, palette.bottomColor);
    torso.add(shadowMesh(new THREE.Mesh(torsoGeo, shellGrad)));

    const chestBib = shadowMesh(
      new THREE.Mesh(createSuperellipsoidGeometry(0.34, isBustOnly ? 0.26 : 0.33, 0.22, 0.85, 0.85, 34, 24), pearl)
    );
    chestBib.position.set(0, isBustOnly ? 0.04 : -0.02, 0.25);
    torso.add(chestBib);

    const coreGroup = new THREE.Group();
    coreGroup.position.set(0, 0.09, 0.455);
    torso.add(coreGroup);

    const coreHousing = shadowMesh(
      new THREE.Mesh(track(new THREE.CylinderGeometry(0.115, 0.125, 0.036, 28)), gunmetal)
    );
    coreHousing.rotation.x = Math.PI / 2;
    coreGroup.add(coreHousing);

    const coreRing = new THREE.Mesh(track(new THREE.TorusGeometry(0.095, 0.011, 12, 32)), accentGlow);
    coreRing.position.z = 0.017;
    coreGroup.add(coreRing);

    chestSpark = new THREE.Mesh(createSparkleGeometry(0.068, 0.014), accentGlow);
    chestSpark.position.z = 0.024;
    coreGroup.add(chestSpark);

    const collar = shadowMesh(
      new THREE.Mesh(track(new THREE.TorusGeometry(0.24, 0.048, 16, 36)), pearl)
    );
    collar.rotation.x = Math.PI / 2;
    collar.position.set(0, 0.42, 0);
    torso.add(collar);

    const neck = shadowMesh(
      new THREE.Mesh(track(new THREE.CylinderGeometry(0.145, 0.175, 0.2, 24)), gunmetal)
    );
    neck.position.set(0, 0.5, 0);
    torso.add(neck);

    // Card Avatar Special: Glowing Waist Emitter Disc & Energy Rings
    if (isBustOnly) {
      waistEmitter = new THREE.Group();
      waistEmitter.position.set(0, -0.31, 0);
      torso.add(waistEmitter);

      const emitterBase = new THREE.Mesh(
        track(new THREE.CylinderGeometry(0.32, 0.22, 0.08, 32)),
        gunmetal
      );
      waistEmitter.add(emitterBase);

      const emitterRing1 = new THREE.Mesh(
        track(new THREE.TorusGeometry(0.34, 0.022, 14, 42)),
        accentGlow
      );
      emitterRing1.rotation.x = Math.PI / 2;
      emitterRing1.position.y = -0.02;
      waistEmitter.add(emitterRing1);

      const emitterRing2 = new THREE.Mesh(
        track(new THREE.TorusGeometry(0.22, 0.016, 12, 34)),
        accentGlow
      );
      emitterRing2.rotation.x = Math.PI / 2;
      emitterRing2.position.y = -0.11;
      waistEmitter.add(emitterRing2);
    }

    // Back pod + Animated Feline Cat Tail (Full Body & Chibi)
    if (hasFullBody) {
      const backPod = shadowMesh(
        new THREE.Mesh(createSuperellipsoidGeometry(0.22, 0.23, 0.12, 0.85, 0.85, 24, 18), pearl)
      );
      backPod.position.set(0, 0.04, -0.37);
      torso.add(backPod);

      const backRing = new THREE.Mesh(track(new THREE.TorusGeometry(0.085, 0.012, 12, 28)), accentGlow);
      backRing.position.set(0, 0.04, -0.485);
      torso.add(backRing);

      tailPivot = new THREE.Group();
      tailPivot.position.set(0, -0.26, -0.35);
      torso.add(tailPivot);

      const tailCurve = new THREE.CatmullRomCurve3([
        new THREE.Vector3(0.0, 0.0, 0.0),
        new THREE.Vector3(0.16, -0.05, -0.22),
        new THREE.Vector3(0.46, 0.05, -0.2),
        new THREE.Vector3(0.68, 0.28, -0.06),
        new THREE.Vector3(0.74, 0.54, 0.05),
      ]);
      const tailGeo = createTaperedTubeGeometry(
        tailCurve,
        40,
        16,
        (u) => 0.066 * (1 - u * 0.44) + Math.sin(u * Math.PI) * 0.014,
        false
      );
      applyVerticalGradient(tailGeo, palette.topColor, palette.bottomColor);
      tailPivot.add(shadowMesh(new THREE.Mesh(tailGeo, shellGrad)));

      const tailTip = shadowMesh(new THREE.Mesh(track(new THREE.SphereGeometry(0.046, 16, 14)), pearl));
      tailTip.position.copy(tailCurve.getPointAt(1));
      tailPivot.add(tailTip);

      const tailTipRing = new THREE.Mesh(track(new THREE.TorusGeometry(0.046, 0.011, 12, 24)), accentGlow);
      tailTipRing.position.copy(tailCurve.getPointAt(0.88));
      tailTipRing.rotation.set(Math.PI * 0.44, 0.2, 0.1);
      tailPivot.add(tailTipRing);
    }

    // Articulated Pixar Mitten-Paw Arms
    const buildArm = (side: number) => {
      const socket = shadowMesh(new THREE.Mesh(track(new THREE.SphereGeometry(0.13, 18, 14)), gunmetal));
      socket.position.set(side * 0.43, 0.18, 0);
      torso!.add(socket);

      const shoulder = new THREE.Group();
      shoulder.position.set(side * 0.48, 0.18, 0);
      torso!.add(shoulder);

      const shoulderCap = shadowMesh(new THREE.Mesh(track(new THREE.SphereGeometry(0.135, 18, 14)), shellSolid));
      shoulderCap.scale.set(1.0, 1.15, 1.0);
      shoulder.add(shoulderCap);

      const upperArm = shadowMesh(new THREE.Mesh(track(new THREE.CylinderGeometry(0.095, 0.088, 0.24, 18)), pearl));
      upperArm.position.set(0, -0.15, 0);
      shoulder.add(upperArm);

      const elbow = new THREE.Group();
      elbow.position.set(0, -0.29, 0);
      shoulder.add(elbow);

      elbow.add(shadowMesh(new THREE.Mesh(track(new THREE.SphereGeometry(0.085, 16, 14)), gunmetal)));

      const forearmGeo = track(
        new THREE.LatheGeometry(
          [
            new THREE.Vector2(0.082, 0.0),
            new THREE.Vector2(0.112, -0.07),
            new THREE.Vector2(0.128, -0.18),
            new THREE.Vector2(0.118, -0.25),
            new THREE.Vector2(0.075, -0.27),
          ],
          22
        )
      );
      elbow.add(shadowMesh(new THREE.Mesh(forearmGeo, pearl)));

      const cuffRing = new THREE.Mesh(track(new THREE.TorusGeometry(0.12, 0.011, 10, 24)), accentGlow);
      cuffRing.rotation.x = Math.PI / 2;
      cuffRing.position.set(0, -0.21, 0);
      elbow.add(cuffRing);

      const hand = new THREE.Group();
      hand.position.set(0, -0.32, 0);
      elbow.add(hand);

      const palm = shadowMesh(new THREE.Mesh(createSuperellipsoidGeometry(0.118, 0.138, 0.088, 0.88, 0.88, 22, 16), shellSolid));
      palm.position.set(0, -0.06, 0.01);
      hand.add(palm);

      const thumb = shadowMesh(new THREE.Mesh(createSuperellipsoidGeometry(0.048, 0.076, 0.046, 0.85, 0.85, 14, 12), pearl));
      thumb.position.set(-side * 0.105, -0.025, 0.048);
      thumb.rotation.set(0.15, 0, side * 0.58);
      hand.add(thumb);

      const mainPad = new THREE.Mesh(track(new THREE.SphereGeometry(0.046, 12, 10)), accentGlow);
      mainPad.scale.set(1.15, 0.92, 0.35);
      mainPad.position.set(0, -0.085, 0.086);
      hand.add(mainPad);

      [-0.048, 0, 0.048].forEach((bx, idx) => {
        const toePad = new THREE.Mesh(track(new THREE.SphereGeometry(0.02, 10, 8)), accentGlow);
        toePad.scale.set(0.95, 1.15, 0.4);
        toePad.position.set(bx, idx === 1 ? -0.018 : -0.032, 0.084);
        hand.add(toePad);
      });

      return { shoulder, elbow, hand };
    };

    leftArm = buildArm(-1);
    rightArm = buildArm(1);

    // Chunky Modern Pixar Hover-Boots (Full-body variants)
    if (hasFullBody) {
      const buildLeg = (side: number) => {
        const hip = new THREE.Group();
        hip.position.set(side * 0.21, -0.39, 0);
        torso!.add(hip);

        hip.add(shadowMesh(new THREE.Mesh(track(new THREE.SphereGeometry(0.115, 18, 14)), gunmetal)));

        const thigh = shadowMesh(new THREE.Mesh(createSuperellipsoidGeometry(0.145, 0.175, 0.145, 0.85, 0.85, 20, 16), shellSolid));
        thigh.position.set(0, -0.14, 0);
        hip.add(thigh);

        const knee = shadowMesh(new THREE.Mesh(track(new THREE.SphereGeometry(0.095, 16, 14)), pearl));
        knee.position.set(0, -0.27, 0.03);
        hip.add(knee);

        const foot = new THREE.Group();
        foot.position.set(0, -0.35, 0);
        hip.add(foot);

        const bootUpper = shadowMesh(new THREE.Mesh(createSuperellipsoidGeometry(0.165, 0.145, 0.245, 0.78, 0.82, 24, 18), pearl));
        bootUpper.position.set(0, -0.12, 0.065);
        foot.add(bootUpper);

        const toeCap = shadowMesh(new THREE.Mesh(createSuperellipsoidGeometry(0.145, 0.105, 0.145, 0.82, 0.82, 20, 16), shellSolid));
        toeCap.position.set(0, -0.145, 0.175);
        foot.add(toeCap);

        const sole = shadowMesh(new THREE.Mesh(createSuperellipsoidGeometry(0.172, 0.048, 0.258, 0.65, 0.78, 22, 14), gunmetal));
        sole.position.set(0, -0.235, 0.068);
        foot.add(sole);

        const glowRing = new THREE.Mesh(track(new THREE.TorusGeometry(0.125, 0.015, 12, 28)), accentGlow);
        glowRing.rotation.x = Math.PI / 2;
        glowRing.scale.set(1.0, 1.38, 1.0);
        glowRing.position.set(0, -0.278, 0.068);
        foot.add(glowRing);

        return { hip, foot, glowRing };
      };

      leftLeg = buildLeg(-1);
      rightLeg = buildLeg(1);
    }
  }

  // ---- B. PIXAR CHIBI HEAD + OPTICAL OLED VISOR + 3D CAT EARS (All 5 Variants!) ----
  const head = new THREE.Group();
  head.position.set(0, isHeadOnly ? 0.08 : isChibi ? 0.72 : 0.88, 0.02);
  if (isChibi) head.scale.setScalar(1.12);
  bodyPivot.add(head);

  const hRx = 0.77;
  const hRy = 0.62;
  const hRz = 0.65;
  const hE1 = 0.78;
  const hE2 = 0.8;

  const headGeo = createSuperellipsoidGeometry(hRx, hRy, hRz, hE1, hE2, 52, 38);
  applyVerticalGradient(headGeo, palette.topColor, palette.bottomColor);
  head.add(shadowMesh(new THREE.Mesh(headGeo, shellGrad)));

  const vPhiHalf = 0.82;
  const vThetaHalf = 0.6;
  head.add(new THREE.Mesh(createConformalVisorPatch(hRx, hRy, hRz, hE1, hE2, vPhiHalf, vThetaHalf, 0.013, 40), oledVisor));
  head.add(shadowMesh(new THREE.Mesh(createConformalBezel(hRx, hRy, hRz, hE1, hE2, vPhiHalf, vThetaHalf, 0.015, 0.028), pearl)));
  head.add(new THREE.Mesh(createConformalBezel(hRx, hRy, hRz, hE1, hE2, vPhiHalf * 0.962, vThetaHalf * 0.952, 0.019, 0.007), accentGlow));

  [-1, 1].forEach((side) => {
    const discGroup = new THREE.Group();
    discGroup.position.set(side * (hRx - 0.02), -0.02, -0.02);
    discGroup.rotation.z = (side * -Math.PI) / 2;
    head.add(discGroup);

    discGroup.add(shadowMesh(new THREE.Mesh(track(new THREE.CylinderGeometry(0.18, 0.21, 0.085, 28)), pearl)));
    const innerHub = shadowMesh(new THREE.Mesh(track(new THREE.CylinderGeometry(0.115, 0.125, 0.105, 24)), gunmetal));
    innerHub.position.y = 0.012;
    discGroup.add(innerHub);

    const glowRing = new THREE.Mesh(track(new THREE.TorusGeometry(0.132, 0.012, 12, 28)), accentGlow);
    glowRing.rotation.x = Math.PI / 2;
    glowRing.position.y = 0.048;
    discGroup.add(glowRing);
  });

  const buildCatEar = (side: number) => {
    const earPivot = new THREE.Group();
    earPivot.position.set(side * 0.44, 0.46, -0.02);
    earPivot.rotation.set(0.08, side * -0.14, side * -0.34);
    head.add(earPivot);

    const outerEarGeo = createSculptedCatEarGeometry(0.24, 0.37, 0.17);
    applyVerticalGradient(outerEarGeo, palette.topColor, palette.bottomColor);
    earPivot.add(shadowMesh(new THREE.Mesh(outerEarGeo, shellGrad)));

    const innerEar = new THREE.Mesh(createSculptedCatEarGeometry(0.135, 0.22, 0.065), earInner);
    innerEar.position.set(0, 0.055, 0.092);
    earPivot.add(innerEar);

    const earCollar = shadowMesh(new THREE.Mesh(track(new THREE.TorusGeometry(0.18, 0.022, 12, 26)), pearl));
    earCollar.rotation.x = Math.PI / 2;
    earCollar.scale.set(1.05, 0.72, 1.0);
    earCollar.position.set(0, 0.02, 0);
    earPivot.add(earCollar);
    return earPivot;
  };
  const leftEar = buildCatEar(-1);
  const rightEar = buildCatEar(1);

  // ---- C. VARIANT SPECIAL 1: Chatbot Icon (Glowing Hover Collar + Animated 3-Dot Typing Bubble!) ----
  if (isHeadOnly) {
    const collarBase = new THREE.Mesh(track(new THREE.TorusGeometry(0.46, 0.042, 14, 36)), gunmetal);
    collarBase.rotation.x = Math.PI / 2;
    collarBase.position.set(0, -0.62, 0);
    bodyPivot.add(collarBase);

    hoverCollar = new THREE.Mesh(track(new THREE.TorusGeometry(0.48, 0.024, 14, 36)), accentGlow);
    hoverCollar.rotation.x = Math.PI / 2;
    hoverCollar.position.set(0, -0.66, 0);
    bodyPivot.add(hoverCollar);

    // Floating Glossy 3D Speech Bubble + 3 Animated Cyan Typing Dots
    const bubbleGroup = new THREE.Group();
    bubbleGroup.position.set(0.72, 0.68, 0.22);
    bodyPivot.add(bubbleGroup);

    const bubblePill = new THREE.Mesh(
      createSuperellipsoidGeometry(0.32, 0.18, 0.09, 0.72, 0.72, 22, 16),
      pearl
    );
    bubbleGroup.add(bubblePill);

    const bubbleTail = new THREE.Mesh(track(new THREE.ConeGeometry(0.08, 0.16, 12)), pearl);
    bubbleTail.position.set(-0.16, -0.18, 0);
    bubbleTail.rotation.z = 2.45;
    bubbleGroup.add(bubbleTail);

    const dots: THREE.Mesh[] = [-0.11, 0, 0.11].map((dx) => {
      const dot = new THREE.Mesh(track(new THREE.SphereGeometry(0.036, 12, 10)), accentGlow);
      dot.position.set(dx, 0, 0.088);
      bubbleGroup.add(dot);
      return dot;
    });
    chatBubble = { group: bubbleGroup, dots };
  }

  // ---- D. VARIANT SPECIAL 2: Full-Stack Orbit (3 Rotating Orbital Tech Rings + Geometric Nodes!) ----
  if (variant === 'full-stack-orbit') {
    const orbitGroup = new THREE.Group();
    orbitGroup.position.set(0, 0.25, 0);
    root.add(orbitGroup);

    const ringConfigs = [
      { r: 1.48, tube: 0.016, rot: [1.18, 0.24, 0] as [number, number, number], mat: accentGlow },
      { r: 1.72, tube: 0.013, rot: [0.68, -0.78, 0.35] as [number, number, number], mat: shellSolid },
      { r: 1.92, tube: 0.01, rot: [-0.88, 0.52, -0.2] as [number, number, number], mat: pearl },
    ];

    const rings: THREE.Mesh[] = [];
    const nodes: THREE.Mesh[] = [];
    const nodeGeos = [
      track(new THREE.OctahedronGeometry(0.085, 0)),
      track(new THREE.IcosahedronGeometry(0.075, 0)),
      track(new THREE.BoxGeometry(0.11, 0.11, 0.11)),
      createSparkleGeometry(0.085, 0.018),
    ];

    ringConfigs.forEach((cfg, idx) => {
      const ring = new THREE.Mesh(track(new THREE.TorusGeometry(cfg.r, cfg.tube, 14, 96)), cfg.mat);
      ring.rotation.set(cfg.rot[0], cfg.rot[1], cfg.rot[2]);
      orbitGroup.add(ring);
      rings.push(ring);

      // Attach 2 sleek geometric nodes to each orbital ring
      for (let k = 0; k < 2; k++) {
        const angle = k * Math.PI + idx * 0.9;
        const geo = nodeGeos[(idx * 2 + k) % nodeGeos.length];
        const mat = k === 0 ? accentGlow : pearl;
        const node = new THREE.Mesh(geo, mat);
        node.position.set(Math.cos(angle) * cfg.r, Math.sin(angle) * cfg.r, 0);
        ring.add(node);
        nodes.push(node);
      }
    });

    orbitRig = { group: orbitGroup, rings, nodes };
  }

  // ---- E. Stage Floor Contact Shadow & Subtle Ambient Sparkle Motes ----
  let floorShadow: THREE.Mesh | undefined;
  let bokehGroup: THREE.Group | undefined;

  if (variant === 'hero' || variant === 'full-stack-orbit') {
    const sc = document.createElement('canvas');
    sc.width = 256;
    sc.height = 256;
    const sctx = sc.getContext('2d')!;
    const sg = sctx.createRadialGradient(128, 128, 10, 128, 128, 120);
    sg.addColorStop(0.0, isDark ? 'rgba(0, 229, 255, 0.34)' : 'rgba(10, 26, 60, 0.28)');
    sg.addColorStop(0.52, isDark ? 'rgba(21, 87, 245, 0.14)' : 'rgba(21, 87, 245, 0.10)');
    sg.addColorStop(1.0, 'rgba(0, 0, 0, 0)');
    sctx.fillStyle = sg;
    sctx.fillRect(0, 0, 256, 256);

    const shadowTex = track(new THREE.CanvasTexture(sc));
    floorShadow = new THREE.Mesh(
      track(new THREE.PlaneGeometry(2.35, 2.35)),
      track(new THREE.MeshBasicMaterial({ map: shadowTex, transparent: true, depthWrite: false }))
    );
    floorShadow.rotation.x = -Math.PI / 2;
    floorShadow.position.y = -1.26;
    root.add(floorShadow);

    bokehGroup = new THREE.Group();
    root.add(bokehGroup);
    const moteGeo = createSparkleGeometry(0.036, 0.006);
    for (let i = 0; i < 10; i++) {
      const mote = new THREE.Mesh(moteGeo, accentGlow);
      const angle = (i / 10) * Math.PI * 2;
      const rad = 1.58 + (i % 3) * 0.36;
      mote.position.set(
        Math.cos(angle) * rad,
        -0.35 + (i % 5) * 0.42,
        Math.sin(angle) * rad * 0.65 - 0.3
      );
      mote.userData = {
        baseY: mote.position.y,
        speed: 0.8 + (i % 4) * 0.32,
        phase: i * 1.1,
      };
      mote.scale.setScalar(0.55 + (i % 3) * 0.22);
      bokehGroup.add(mote);
    }
  }

  // ---- F. Boop 3D Heart & Sparkle Burst Factory ----
  const heartGeo = createHeartGeometry(0.085);
  const sparkGeo = createSparkleGeometry(0.072, 0.015);
  const heartMat = track(
    new THREE.MeshPhysicalMaterial({
      color: new THREE.Color('#ff5599'),
      emissive: new THREE.Color('#ff2e7e'),
      emissiveIntensity: 0.78,
      roughness: 0.15,
      clearcoat: 1.0,
    })
  );

  const spawnBoopBurst = (scene: THREE.Scene): BurstParticle[] => {
    const out: BurstParticle[] = [];
    for (let i = 0; i < 12; i++) {
      const isHeart = i % 2 === 0;
      const mesh = new THREE.Mesh(isHeart ? heartGeo : sparkGeo, isHeart ? heartMat : accentGlow);
      const angle = (i / 12) * Math.PI * 2 + Math.random() * 0.35;
      mesh.position.set(
        Math.cos(angle) * 0.35,
        0.55 + Math.random() * 0.35,
        0.35 + Math.sin(angle) * 0.25
      );
      const speed = 1.3 + Math.random() * 1.05;
      scene.add(mesh);
      out.push({
        mesh,
        vel: new THREE.Vector3(
          Math.cos(angle) * speed,
          1.4 + Math.random() * 1.15,
          Math.sin(angle) * speed * 0.7
        ),
        rotVel: new THREE.Vector3(
          (Math.random() - 0.5) * 6,
          (Math.random() - 0.5) * 8,
          (Math.random() - 0.5) * 6
        ),
        life: 1.0,
      });
    }
    return out;
  };

  drawFace('normal', 0, 0, 0, 0, 0);

  return {
    root,
    bodyPivot,
    torso,
    head,
    leftEar,
    rightEar,
    leftArm,
    rightArm,
    leftLeg,
    rightLeg,
    tailPivot,
    chestSpark,
    hoverCollar,
    waistEmitter,
    chatBubble,
    orbitRig,
    floorShadow,
    bokehGroup,
    accentGlowMat: accentGlow,
    drawFace,
    spawnBoopBurst,
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
