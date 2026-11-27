'use client';

import React, { useEffect, useRef, useState } from 'react';
import * as THREE from 'three';
import {
  CREATSIFY_NEKO_PALETTE,
  createNekoRobotRig,
  createStudioEnvironment,
  type BurstParticle,
  type NekoExpression,
  type NekoMode,
  type NekoPalette,
  type NekoVariant,
} from '@/lib/nekoRobotEngine';

export interface CreatsifyNekoCanvasProps {
  variant: NekoVariant;
  mode?: NekoMode;
  expressionOverride?: NekoExpression;
  isDark?: boolean;
  boopSignal?: number;
  speaking?: boolean;
  globalPointer?: boolean;
  interactive?: boolean;
  lazyMount?: boolean;
  palette?: NekoPalette;
  className?: string;
  ariaLabel?: string;
  onBoop?: () => void;
}

const CAMERA_PRESETS: Record<
  NekoVariant,
  { fov: number; pos: [number, number, number]; lookAt: [number, number, number] }
> = {
  hero: { fov: 32, pos: [0, 0.3, 5.85], lookAt: [0, 0.22, 0] },
  'chatbot-icon': { fov: 34, pos: [0.14, 0.14, 4.05], lookAt: [0.14, 0.1, 0] },
  'card-avatar': { fov: 32, pos: [0, 0.46, 5.05], lookAt: [0, 0.44, 0] },
  'full-stack-orbit': { fov: 35, pos: [0, 0.36, 7.4], lookAt: [0, 0.24, 0] },
  'mini-corner': { fov: 33, pos: [0, 0.34, 5.25], lookAt: [0, 0.28, 0] },
};

function clamp(v: number, lo: number, hi: number): number {
  return Math.max(lo, Math.min(hi, v));
}

function lerp(a: number, b: number, t: number): number {
  return a + (b - a) * t;
}

export function CreatsifyNekoCanvas({
  variant,
  mode = 'hover',
  expressionOverride,
  isDark = true,
  boopSignal = 0,
  speaking = false,
  globalPointer = false,
  interactive = false,
  lazyMount = false,
  palette = CREATSIFY_NEKO_PALETTE,
  className = '',
  ariaLabel = 'Interactive 3D Creatsify Neko Robot',
  onBoop,
}: CreatsifyNekoCanvasProps) {
  const hostRef = useRef<HTMLDivElement>(null);
  const [shouldMount, setShouldMount] = useState(!lazyMount);
  const [webglReady, setWebglReady] = useState(false);

  // Live reactive refs so prop changes never tear down the WebGL context
  const modeRef = useRef<NekoMode>(mode);
  const exprOverrideRef = useRef<NekoExpression | undefined>(expressionOverride);
  const speakingRef = useRef<boolean>(speaking);
  const onBoopRef = useRef<(() => void) | undefined>(onBoop);
  const triggerBoopRef = useRef<(() => void) | null>(null);
  const lastBoopSignalRef = useRef<number>(boopSignal);

  useEffect(() => {
    modeRef.current = mode;
  }, [mode]);

  useEffect(() => {
    exprOverrideRef.current = expressionOverride;
  }, [expressionOverride]);

  useEffect(() => {
    speakingRef.current = speaking;
  }, [speaking]);

  useEffect(() => {
    onBoopRef.current = onBoop;
  }, [onBoop]);

  useEffect(() => {
    if (boopSignal !== lastBoopSignalRef.current) {
      lastBoopSignalRef.current = boopSignal;
      triggerBoopRef.current?.();
    }
  }, [boopSignal]);

  // Lazy mount / unmount WebGL context when far outside viewport to keep total active contexts <= 3
  useEffect(() => {
    if (!lazyMount) {
      setShouldMount(true);
      return;
    }
    const el = hostRef.current;
    if (!el || typeof IntersectionObserver === 'undefined') {
      setShouldMount(true);
      return;
    }
    const io = new IntersectionObserver(
      (entries) => {
        const entry = entries[0];
        if (entry) {
          setShouldMount(entry.isIntersecting);
        }
      },
      { rootMargin: '260px 0px', threshold: 0.01 }
    );
    io.observe(el);
    return () => io.disconnect();
  }, [lazyMount]);

  useEffect(() => {
    if (!shouldMount) {
      setWebglReady(false);
      return;
    }
    const host = hostRef.current;
    if (!host) return;

    const reducedMotion =
      typeof window !== 'undefined' &&
      window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const isCompact = variant === 'chatbot-icon' || variant === 'mini-corner';

    let renderer: THREE.WebGLRenderer;
    try {
      renderer = new THREE.WebGLRenderer({
        antialias: !isCompact || window.devicePixelRatio <= 1.5,
        alpha: true,
        powerPreference: isCompact ? 'low-power' : 'high-performance',
        premultipliedAlpha: true,
      });
    } catch {
      return;
    }

    const maxDpr = isCompact ? 1.75 : 2;
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, maxDpr));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = isDark ? 1.14 : 1.08;
    renderer.setClearColor(0x000000, 0);

    const canvas = renderer.domElement;
    canvas.style.width = '100%';
    canvas.style.height = '100%';
    canvas.style.display = 'block';
    canvas.style.touchAction = interactive ? 'pan-y' : 'auto';
    host.appendChild(canvas);

    const scene = new THREE.Scene();
    const envRT = createStudioEnvironment(renderer, isDark);
    scene.environment = envRT.texture;

    const preset = CAMERA_PRESETS[variant];
    const width = Math.max(host.clientWidth, 40);
    const height = Math.max(host.clientHeight, 40);
    renderer.setSize(width, height, false);

    const camera = new THREE.PerspectiveCamera(preset.fov, width / height, 0.1, 45);
    camera.position.set(preset.pos[0], preset.pos[1], preset.pos[2]);
    camera.lookAt(preset.lookAt[0], preset.lookAt[1], preset.lookAt[2]);

    // Studio 3-point Pixar lighting rig
    const hemi = new THREE.HemisphereLight(
      isDark ? 0xdbeafe : 0xffffff,
      isDark ? 0x0b1530 : 0xbfd4f8,
      isDark ? 0.72 : 0.92
    );
    scene.add(hemi);

    const keyLight = new THREE.DirectionalLight(0xffffff, isDark ? 1.75 : 1.55);
    keyLight.position.set(-3.4, 5.2, 4.6);
    scene.add(keyLight);

    const rimLight = new THREE.DirectionalLight(0x25e3f5, isDark ? 1.55 : 1.1);
    rimLight.position.set(4.5, 2.4, -2.5);
    scene.add(rimLight);

    const fillLight = new THREE.DirectionalLight(0x3b82f6, isDark ? 0.85 : 0.65);
    fillLight.position.set(3.2, -1.6, 3.8);
    scene.add(fillLight);

    const rig = createNekoRobotRig(variant, isDark, palette);
    scene.add(rig.root);

    // Interactive state
    let pointerX = 0;
    let pointerY = 0;
    let smoothLookX = 0;
    let smoothLookY = 0;

    let dragging = false;
    let dragMoved = false;
    let lastClientX = 0;
    let lastClientY = 0;
    let targetYaw = 0;
    let currentYaw = 0;
    let targetPitch = 0;
    let currentPitch = 0;

    let boopTimer = 0;
    let nextBlink = 2.4 + Math.random() * 2.4;
    let blinkProgress = 0;
    let earTwitchTimer = 0;
    let earTwitchSide = 1;
    let nextEarTwitch = 3.2 + Math.random() * 3.0;
    let isVisible = true;
    let lastFaceDraw = -1;

    const particles: BurstParticle[] = [];

    const triggerBoop = () => {
      boopTimer = 1.45;
      earTwitchTimer = 0.45;
      if (!reducedMotion && variant !== 'chatbot-icon') {
        const burst = rig.spawnBoopBurst(scene);
        if (variant === 'mini-corner' || variant === 'card-avatar') {
          burst.forEach((p) => {
            p.mesh.scale.setScalar(0.76);
            p.vel.multiplyScalar(0.72);
          });
        }
        particles.push(...burst);
      }
    };
    triggerBoopRef.current = triggerBoop;

    const updatePointerFromClient = (clientX: number, clientY: number) => {
      if (globalPointer) {
        pointerX = clamp((clientX / Math.max(window.innerWidth, 1)) * 2 - 1, -1, 1);
        pointerY = clamp(-((clientY / Math.max(window.innerHeight, 1)) * 2 - 1), -1, 1);
      } else {
        const rect = host.getBoundingClientRect();
        if (rect.width <= 0 || rect.height <= 0) return;
        pointerX = clamp(((clientX - rect.left) / rect.width) * 2 - 1, -1, 1);
        pointerY = clamp(-(((clientY - rect.top) / rect.height) * 2 - 1), -1, 1);
      }
    };

    const onWindowPointerMove = (e: PointerEvent) => {
      if (dragging) {
        const dx = e.clientX - lastClientX;
        const dy = e.clientY - lastClientY;
        if (Math.hypot(dx, dy) > 3) dragMoved = true;
        targetYaw += dx * 0.011;
        targetPitch = clamp(targetPitch + dy * 0.005, -0.35, 0.35);
        lastClientX = e.clientX;
        lastClientY = e.clientY;
      }
      updatePointerFromClient(e.clientX, e.clientY);
    };

    const onHostPointerDown = (e: PointerEvent) => {
      if (!interactive) return;
      dragging = true;
      dragMoved = false;
      lastClientX = e.clientX;
      lastClientY = e.clientY;
      host.setPointerCapture?.(e.pointerId);
    };

    const onPointerUp = () => {
      if (!dragging) return;
      dragging = false;
      if (!dragMoved) {
        triggerBoop();
        onBoopRef.current?.();
      }
    };

    const onHostClick = () => {
      if (!interactive) {
        triggerBoop();
        onBoopRef.current?.();
      }
    };

    if (globalPointer || interactive) {
      window.addEventListener('pointermove', onWindowPointerMove, { passive: true });
    }
    if (interactive) {
      host.addEventListener('pointerdown', onHostPointerDown);
      window.addEventListener('pointerup', onPointerUp);
      window.addEventListener('pointercancel', onPointerUp);
    } else {
      host.addEventListener('click', onHostClick);
    }

    // ResizeObserver for sharp responsive aspect ratio
    const ro = new ResizeObserver((entries) => {
      const box = entries[0]?.contentRect;
      if (!box || box.width < 10 || box.height < 10) return;
      renderer.setSize(box.width, box.height, false);
      camera.aspect = box.width / box.height;
      camera.updateProjectionMatrix();
      if (reducedMotion) {
        renderer.render(scene, camera);
      }
    });
    ro.observe(host);

    // Visibility pause when scrolled offscreen
    let visObserver: IntersectionObserver | null = null;
    if (typeof IntersectionObserver !== 'undefined') {
      visObserver = new IntersectionObserver(
        (entries) => {
          isVisible = Boolean(entries[0]?.isIntersecting);
        },
        { threshold: 0.01 }
      );
      visObserver.observe(host);
    }

    const applyPose = (elapsed: number, dt: number) => {
      const activeMode = modeRef.current;
      const isBooping = boopTimer > 0;

      if (boopTimer > 0) {
        boopTimer = Math.max(0, boopTimer - dt);
      }

      // Natural eye blinking
      nextBlink -= dt;
      if (nextBlink <= 0) {
        blinkProgress += dt * 11;
        if (blinkProgress >= Math.PI) {
          blinkProgress = 0;
          nextBlink = 2.4 + Math.random() * 3.1;
        }
      }
      const blinkAmt = blinkProgress > 0 ? Math.sin(blinkProgress) : 0;

      // Occasional lively cat ear twitches
      nextEarTwitch -= dt;
      if (nextEarTwitch <= 0) {
        earTwitchTimer = 0.36;
        earTwitchSide = Math.random() > 0.5 ? 1 : -1;
        nextEarTwitch = 3.0 + Math.random() * 4.2;
      }
      if (earTwitchTimer > 0) {
        earTwitchTimer = Math.max(0, earTwitchTimer - dt);
      }
      const twitchWave = earTwitchTimer > 0 ? Math.sin(earTwitchTimer * 28) * 0.16 : 0;

      // Smooth pointer look + gentle drag damping
      smoothLookX = lerp(smoothLookX, pointerX, Math.min(1, dt * 6.5));
      smoothLookY = lerp(smoothLookY, pointerY, Math.min(1, dt * 6.5));

      if (!dragging) {
        targetPitch = lerp(targetPitch, 0, Math.min(1, dt * 2.5));
        // Slowly return gentle yaw back toward hero front when not being dragged
        if (Math.abs(targetYaw) > 0.002) {
          targetYaw = lerp(targetYaw, 0, Math.min(1, dt * 0.55));
        }
      }
      currentYaw = lerp(currentYaw, targetYaw, Math.min(1, dt * 9));
      currentPitch = lerp(currentPitch, targetPitch, Math.min(1, dt * 9));

      // Compute mode + boop motion
      const boopBounce = isBooping ? Math.sin((boopTimer / 1.45) * Math.PI * 3) * 0.13 : 0;
      const hoverY = Math.sin(elapsed * 2.35) * (variant === 'chatbot-icon' ? 0.065 : 0.095);

      let pivotY = hoverY + boopBounce;
      let pivotRotX = currentPitch;
      let pivotRotY = currentYaw + Math.sin(elapsed * 0.9) * 0.06;
      let pivotRotZ = Math.sin(elapsed * 1.4) * 0.028;

      let headRotX = -smoothLookY * 0.22;
      let headRotY = smoothLookX * 0.32;
      let headRotZ = -smoothLookX * 0.07;

      let lShoulderX = 0.08;
      let lShoulderZ = -0.22 + Math.sin(elapsed * 2.2) * 0.05;
      let lElbowX = -0.18;
      let lElbowZ = -0.08;

      let rShoulderX = 0.08;
      let rShoulderZ = 0.22 - Math.sin(elapsed * 2.2) * 0.05;
      let rElbowX = -0.18;
      let rElbowZ = 0.08;

      let lHipX = Math.sin(elapsed * 2.35) * 0.09;
      let rHipX = -Math.sin(elapsed * 2.35) * 0.09;
      let tailSpeed = 2.6;
      let tailAmp = 0.22;

      if (activeMode === 'wave') {
        pivotRotZ = -0.05 + Math.sin(elapsed * 3.4) * 0.03;
        headRotZ = 0.09 + Math.sin(elapsed * 3.4) * 0.04;
        rShoulderX = -0.25;
        rShoulderZ = 2.42 + Math.sin(elapsed * 7.8) * 0.24;
        rElbowZ = 0.42 + Math.sin(elapsed * 7.8 - 0.65) * 0.28;
        lShoulderZ = -0.34;
        tailSpeed = 5.4;
        tailAmp = 0.34;
      } else if (activeMode === 'dance') {
        const beat = elapsed * 6.4;
        pivotY = Math.abs(Math.sin(beat)) * 0.19 - 0.02 + boopBounce;
        pivotRotY = currentYaw + Math.sin(beat * 0.5) * 0.26;
        pivotRotZ = Math.sin(beat * 0.5) * 0.1;
        headRotZ = -Math.sin(beat * 0.5) * 0.14;
        headRotY = Math.sin(beat * 0.5) * 0.22;
        lShoulderX = -0.65 + Math.sin(beat) * 0.55;
        lShoulderZ = -1.15 - Math.cos(beat) * 0.52;
        lElbowZ = -0.45;
        rShoulderX = -0.65 - Math.sin(beat) * 0.55;
        rShoulderZ = 1.15 + Math.cos(beat) * 0.52;
        rElbowZ = 0.45;
        lHipX = Math.sin(beat) * 0.34;
        rHipX = -Math.sin(beat) * 0.34;
        tailSpeed = 8.4;
        tailAmp = 0.45;
      } else if (activeMode === 'flip') {
        const cycle = elapsed % 2.5;
        const flipPhase = cycle < 1.1 ? cycle / 1.1 : 0;
        const easeFlip =
          flipPhase > 0
            ? flipPhase < 0.5
              ? 2 * flipPhase * flipPhase
              : 1 - Math.pow(-2 * flipPhase + 2, 2) / 2
            : 0;
        pivotY = hoverY + Math.sin(flipPhase * Math.PI) * 0.46 + boopBounce;
        pivotRotX = currentPitch - easeFlip * Math.PI * 2;
        lShoulderZ = -0.75 - Math.sin(flipPhase * Math.PI) * 0.9;
        rShoulderZ = 0.75 + Math.sin(flipPhase * Math.PI) * 0.9;
        lHipX = -Math.sin(flipPhase * Math.PI) * 0.58;
        rHipX = -Math.sin(flipPhase * Math.PI) * 0.58;
        tailSpeed = 6.2;
      } else if (activeMode === 'think') {
        pivotRotZ = 0.06;
        headRotZ = 0.14;
        headRotX = -0.14;
        headRotY = -0.16 + smoothLookX * 0.15;
        rShoulderX = -1.25;
        rShoulderZ = -0.35;
        rElbowX = -1.15;
        rElbowZ = -0.45;
        lShoulderZ = -0.26;
        tailSpeed = 1.5;
        tailAmp = 0.14;
      } else if (activeMode === 'funny') {
        const wiggle = elapsed * 5.2;
        pivotRotZ = Math.sin(wiggle) * 0.12;
        headRotZ = -Math.sin(wiggle) * 0.22;
        lShoulderZ = -1.6 + Math.sin(wiggle * 1.4) * 0.45;
        rShoulderZ = 1.6 - Math.sin(wiggle * 1.4) * 0.45;
        tailSpeed = 7.2;
        tailAmp = 0.38;
      }

      if (isBooping) {
        rShoulderZ = Math.max(rShoulderZ, 1.85 + Math.sin(elapsed * 16) * 0.25);
        lShoulderZ = Math.min(lShoulderZ, -1.85 - Math.sin(elapsed * 16) * 0.25);
        headRotX -= 0.08;
      }

      rig.bodyPivot.position.y = pivotY;
      rig.bodyPivot.rotation.set(pivotRotX, pivotRotY, pivotRotZ);
      rig.head.rotation.set(headRotX, headRotY, headRotZ);

      // Cat ears spring + twitch
      const earLift = isBooping ? 0.15 : 0;
      rig.leftEar.rotation.z = 0.34 + earLift + (earTwitchSide < 0 ? twitchWave : 0);
      rig.rightEar.rotation.z = -0.34 - earLift + (earTwitchSide > 0 ? twitchWave : 0);

      // Articulated limbs
      if (rig.leftArm) {
        rig.leftArm.shoulder.rotation.set(lShoulderX, 0, lShoulderZ);
        rig.leftArm.elbow.rotation.set(lElbowX, 0, lElbowZ);
      }
      if (rig.rightArm) {
        rig.rightArm.shoulder.rotation.set(rShoulderX, 0, rShoulderZ);
        rig.rightArm.elbow.rotation.set(rElbowX, 0, rElbowZ);
      }
      if (rig.leftLeg && rig.rightLeg) {
        rig.leftLeg.hip.rotation.x = lHipX;
        rig.rightLeg.hip.rotation.x = rHipX;
        rig.leftLeg.foot.rotation.x = -lHipX * 0.45 + 0.08;
        rig.rightLeg.foot.rotation.x = -rHipX * 0.45 + 0.08;
      }

      // Animated cat tail
      if (rig.tailPivot) {
        rig.tailPivot.rotation.y = Math.sin(elapsed * tailSpeed) * tailAmp;
        rig.tailPivot.rotation.x = Math.cos(elapsed * (tailSpeed * 0.5)) * 0.08;
      }

      // Chest sparkle gentle spin
      if (rig.chestSpark) {
        rig.chestSpark.rotation.z = elapsed * (activeMode === 'dance' ? 4.2 : 1.15);
      }

      // Accent emissive breathing pulse
      rig.accentGlowMat.emissiveIntensity = 1.38 + Math.sin(elapsed * 3.1) * 0.22;

      // Variant Special 1: Chatbot hover collar + animated 3-dot typing bubble
      if (rig.hoverCollar) {
        const collarScale = 1 + Math.sin(elapsed * 3.6) * 0.045;
        rig.hoverCollar.scale.set(collarScale, collarScale, 1);
      }
      if (rig.chatBubble) {
        rig.chatBubble.group.position.y = 0.68 + Math.sin(elapsed * 2.8) * 0.045;
        rig.chatBubble.group.rotation.z = Math.sin(elapsed * 1.9) * 0.05;
        rig.chatBubble.dots.forEach((dot, idx) => {
          const wave = Math.max(0, Math.sin(elapsed * 5.2 - idx * 0.85));
          dot.position.y = wave * 0.048;
          dot.scale.setScalar(0.86 + wave * 0.34);
        });
      }

      // Variant Special 2: Card Avatar glowing waist emitter
      if (rig.waistEmitter) {
        rig.waistEmitter.rotation.y = elapsed * 1.35;
        rig.waistEmitter.position.y = -0.31 + Math.sin(elapsed * 3.4) * 0.015;
      }

      // Variant Special 3: Full-Stack Orbit rotating tech rings & geometric nodes
      if (rig.orbitRig) {
        rig.orbitRig.group.rotation.y = currentYaw * 0.55;
        const [r0, r1, r2] = rig.orbitRig.rings;
        if (r0) r0.rotation.z = elapsed * 0.65;
        if (r1) r1.rotation.z = -elapsed * 0.48;
        if (r2) r2.rotation.z = elapsed * 0.36;
        rig.orbitRig.nodes.forEach((node, idx) => {
          node.rotation.x = elapsed * (1.1 + (idx % 3) * 0.35);
          node.rotation.y = elapsed * (1.3 - (idx % 2) * 0.4);
        });
      }

      // Floor contact shadow & ambient sparkle motes
      if (rig.floorShadow) {
        const s = 1 - pivotY * 0.22;
        rig.floorShadow.scale.set(s, s, 1);
      }
      if (rig.bokehGroup) {
        rig.bokehGroup.rotation.y = elapsed * 0.14;
        rig.bokehGroup.children.forEach((child) => {
          const ud = child.userData as { baseY: number; speed: number; phase: number };
          child.position.y = ud.baseY + Math.sin(elapsed * ud.speed + ud.phase) * 0.14;
          child.rotation.z = elapsed * ud.speed;
        });
      }

      // Boop 3D particles physics update
      for (let i = particles.length - 1; i >= 0; i--) {
        const p = particles[i];
        p.life -= dt * 0.92;
        if (p.life <= 0) {
          scene.remove(p.mesh);
          particles.splice(i, 1);
          continue;
        }
        p.vel.y -= 3.2 * dt;
        p.mesh.position.addScaledVector(p.vel, dt);
        p.mesh.rotation.x += p.rotVel.x * dt;
        p.mesh.rotation.y += p.rotVel.y * dt;
        p.mesh.rotation.z += p.rotVel.z * dt;
        const sc = Math.min(1, p.life * 1.6);
        p.mesh.scale.setScalar(Math.max(0.01, sc));
      }

      // Determine OLED expression & throttle face canvas redraw to ~30fps for crisp zero-jank perf
      let targetExpr: NekoExpression = exprOverrideRef.current ?? 'normal';
      if (isBooping) {
        targetExpr = variant === 'chatbot-icon' ? 'joy' : 'love';
      } else if (!exprOverrideRef.current) {
        if (activeMode === 'wave') targetExpr = 'joy';
        else if (activeMode === 'dance') targetExpr = 'star';
        else if (activeMode === 'flip') targetExpr = 'excited';
        else if (activeMode === 'think') targetExpr = 'think';
        else if (activeMode === 'funny') targetExpr = 'funny';
      }

      if (elapsed - lastFaceDraw > 0.032 || lastFaceDraw < 0) {
        lastFaceDraw = elapsed;
        rig.drawFace(
          targetExpr,
          smoothLookX,
          smoothLookY,
          blinkAmt,
          elapsed,
          speakingRef.current ? 1 : 0
        );
      }
    };

    setWebglReady(true);

    // Static clean frame for prefers-reduced-motion
    if (reducedMotion) {
      applyPose(0.2, 0.016);
      renderer.render(scene, camera);
    }

    let rafId = 0;
    let lastTime = performance.now();
    let elapsed = 0;

    const tick = (now: number) => {
      rafId = requestAnimationFrame(tick);
      if (!isVisible || document.hidden) {
        lastTime = now;
        return;
      }
      const dt = Math.min(0.05, Math.max(0.001, (now - lastTime) / 1000));
      lastTime = now;
      elapsed += dt;
      applyPose(elapsed, dt);
      renderer.render(scene, camera);
    };

    if (!reducedMotion) {
      rafId = requestAnimationFrame(tick);
    }

    return () => {
      cancelAnimationFrame(rafId);
      triggerBoopRef.current = null;
      ro.disconnect();
      visObserver?.disconnect();
      window.removeEventListener('pointermove', onWindowPointerMove);
      if (interactive) {
        host.removeEventListener('pointerdown', onHostPointerDown);
        window.removeEventListener('pointerup', onPointerUp);
        window.removeEventListener('pointercancel', onPointerUp);
      } else {
        host.removeEventListener('click', onHostClick);
      }
      particles.forEach((p) => scene.remove(p.mesh));
      rig.dispose();
      envRT.dispose();
      renderer.dispose();
      if (canvas.parentNode === host) {
        host.removeChild(canvas);
      }
    };
  }, [shouldMount, variant, isDark, globalPointer, interactive, palette]);

  return (
    <div
      ref={hostRef}
      role="img"
      aria-label={ariaLabel}
      className={`relative select-none ${interactive ? 'cursor-grab active:cursor-grabbing' : ''} ${className}`}
      data-neko-variant={variant}
      data-webgl-ready={webglReady ? 'true' : 'false'}
    />
  );
}
