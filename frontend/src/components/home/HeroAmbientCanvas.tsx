'use client';

import React, { useMemo, useRef } from 'react';
import { Canvas, useFrame } from '@react-three/fiber';
import { Float, MeshDistortMaterial } from '@react-three/drei';
import * as THREE from 'three';

function OpticalTorusField({ isDark }: { isDark: boolean }) {
  const meshRef = useRef<THREE.Mesh>(null);
  const ringRef = useRef<THREE.Mesh>(null);

  useFrame((state, delta) => {
    const dt = Math.min(delta, 0.05);
    if (meshRef.current) {
      meshRef.current.rotation.x += dt * 0.14;
      meshRef.current.rotation.y += dt * 0.22;
      meshRef.current.position.x = THREE.MathUtils.lerp(
        meshRef.current.position.x,
        state.pointer.x * 0.35,
        0.05
      );
      meshRef.current.position.y = THREE.MathUtils.lerp(
        meshRef.current.position.y,
        state.pointer.y * 0.25,
        0.05
      );
    }
    if (ringRef.current) {
      ringRef.current.rotation.z -= dt * 0.12;
      ringRef.current.rotation.x = 0.65 + state.pointer.y * 0.12;
    }
  });

  const primaryColor = useMemo(() => (isDark ? '#00D2EE' : '#0284C7'), [isDark]);
  const secondaryColor = useMemo(() => (isDark ? '#0284C7' : '#38BDF8'), [isDark]);

  return (
    <group>
      <ambientLight intensity={isDark ? 0.45 : 0.7} />
      <directionalLight position={[4, 5, 4]} intensity={1.8} color={primaryColor} />
      <directionalLight position={[-4, -3, 2]} intensity={0.9} color="#10B981" />

      <Float speed={1.5} rotationIntensity={0.35} floatIntensity={0.45}>
        <mesh ref={meshRef} scale={1.38}>
          <torusKnotGeometry args={[0.92, 0.26, 128, 28]} />
          <MeshDistortMaterial
            color={primaryColor}
            emissive={secondaryColor}
            emissiveIntensity={isDark ? 0.32 : 0.16}
            roughness={0.22}
            metalness={0.78}
            distort={0.14}
            speed={1.4}
            transparent
            opacity={isDark ? 0.32 : 0.18}
          />
        </mesh>
      </Float>

      <mesh ref={ringRef} rotation={[0.65, 0.2, 0]} scale={2.15}>
        <torusGeometry args={[1, 0.008, 16, 96]} />
        <meshBasicMaterial color={primaryColor} transparent opacity={isDark ? 0.28 : 0.2} />
      </mesh>
    </group>
  );
}

export function HeroAmbientCanvas({ isDark }: { isDark: boolean }) {
  return (
    <div
      aria-hidden="true"
      className="pointer-events-none hidden lg:block absolute -inset-8 -z-10 overflow-hidden opacity-90"
    >
      <Canvas
        dpr={[1, 1.5]}
        camera={{ position: [0, 0, 5.2], fov: 42 }}
        gl={{ antialias: true, alpha: true, powerPreference: 'high-performance' }}
      >
        <OpticalTorusField isDark={isDark} />
      </Canvas>
    </div>
  );
}
