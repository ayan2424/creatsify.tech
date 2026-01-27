/**
 * Three.js Scene for Hero Section
 * Creates floating 3D geometric shapes and particle effects
 * Enhanced version with better performance and visual effects
 */

let scene, camera, renderer, particles, shapes = [];
let mouseX = 0, mouseY = 0;
let windowHalfX = window.innerWidth / 2;
let windowHalfY = window.innerHeight / 2;
let animationId;

function initThreeScene() {
    const canvas = document.getElementById('three-canvas');
    if (!canvas) return;

    // Scene setup
    scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(0x030014, 0.035);
    
    // Camera setup
    camera = new THREE.PerspectiveCamera(
        60,
        window.innerWidth / window.innerHeight,
        0.1,
        1000
    );
    camera.position.z = 6;
    camera.position.y = 0;

    // Renderer setup with enhanced settings
    renderer = new THREE.WebGLRenderer({
        canvas: canvas,
        alpha: true,
        antialias: true,
        powerPreference: 'high-performance'
    });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.2;

    // Add ambient light
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
    scene.add(ambientLight);

    // Add colored point lights
    const pointLight1 = new THREE.PointLight(0xa855f7, 2, 50);
    pointLight1.position.set(5, 5, 5);
    scene.add(pointLight1);

    const pointLight2 = new THREE.PointLight(0x06b6d4, 2, 50);
    pointLight2.position.set(-5, -5, 5);
    scene.add(pointLight2);

    const pointLight3 = new THREE.PointLight(0xec4899, 1.5, 50);
    pointLight3.position.set(0, 5, -5);
    scene.add(pointLight3);

    // Create particle system
    createParticles();

    // Create floating geometric shapes
    createFloatingShapes();

    // Mouse move event
    document.addEventListener('mousemove', onMouseMove, { passive: true });
    
    // Window resize event
    window.addEventListener('resize', onWindowResize, { passive: true });

    // Start animation
    animate();
}

function createParticles() {
    const particleCount = 800;
    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);
    const colors = new Float32Array(particleCount * 3);
    const sizes = new Float32Array(particleCount);

    const color1 = new THREE.Color(0xa855f7);
    const color2 = new THREE.Color(0x06b6d4);
    const color3 = new THREE.Color(0xec4899);

    for (let i = 0; i < particleCount; i++) {
        const i3 = i * 3;
        
        // Position - spread particles in a sphere
        const radius = 20 + Math.random() * 30;
        const theta = Math.random() * Math.PI * 2;
        const phi = Math.acos(2 * Math.random() - 1);
        
        positions[i3] = radius * Math.sin(phi) * Math.cos(theta);
        positions[i3 + 1] = radius * Math.sin(phi) * Math.sin(theta);
        positions[i3 + 2] = radius * Math.cos(phi);

        // Color
        const colorChoice = Math.random();
        let color;
        if (colorChoice < 0.33) color = color1;
        else if (colorChoice < 0.66) color = color2;
        else color = color3;

        colors[i3] = color.r;
        colors[i3 + 1] = color.g;
        colors[i3 + 2] = color.b;
        
        // Random sizes
        sizes[i] = Math.random() * 3 + 1;
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
    geometry.setAttribute('size', new THREE.BufferAttribute(sizes, 1));

    const material = new THREE.PointsMaterial({
        size: 0.08,
        vertexColors: true,
        transparent: true,
        opacity: 0.6,
        blending: THREE.AdditiveBlending,
        sizeAttenuation: true
    });

    particles = new THREE.Points(geometry, material);
    scene.add(particles);
}

function createFloatingShapes() {
    // Material configurations
    const materials = {
        purple: new THREE.MeshPhongMaterial({
            color: 0xa855f7,
            emissive: 0xa855f7,
            emissiveIntensity: 0.3,
            shininess: 100,
            transparent: true,
            opacity: 0.85
        }),
        cyan: new THREE.MeshPhongMaterial({
            color: 0x06b6d4,
            emissive: 0x06b6d4,
            emissiveIntensity: 0.3,
            shininess: 100,
            transparent: true,
            opacity: 0.85
        }),
        pink: new THREE.MeshPhongMaterial({
            color: 0xec4899,
            emissive: 0xec4899,
            emissiveIntensity: 0.3,
            shininess: 100,
            transparent: true,
            opacity: 0.85
        }),
        green: new THREE.MeshPhongMaterial({
            color: 0x43e97b,
            emissive: 0x43e97b,
            emissiveIntensity: 0.3,
            shininess: 100,
            transparent: true,
            opacity: 0.85
        }),
        orange: new THREE.MeshPhongMaterial({
            color: 0xf5576c,
            emissive: 0xf5576c,
            emissiveIntensity: 0.3,
            shininess: 100,
            transparent: true,
            opacity: 0.85
        })
    };

    // Sphere
    const sphereGeometry = new THREE.SphereGeometry(0.4, 32, 32);
    const sphere = new THREE.Mesh(sphereGeometry, materials.purple);
    sphere.position.set(-3, 1.5, -2);
    sphere.userData = { 
        rotationSpeed: { x: 0.008, y: 0.01, z: 0.005 },
        floatSpeed: 0.4,
        floatOffset: 0,
        baseY: 1.5
    };
    scene.add(sphere);
    shapes.push(sphere);

    // Torus
    const torusGeometry = new THREE.TorusGeometry(0.4, 0.15, 16, 50);
    const torus = new THREE.Mesh(torusGeometry, materials.cyan);
    torus.position.set(3, -0.5, -1);
    torus.userData = {
        rotationSpeed: { x: 0.015, y: 0.008, z: 0.01 },
        floatSpeed: 0.6,
        floatOffset: Math.PI / 2,
        baseY: -0.5
    };
    scene.add(torus);
    shapes.push(torus);

    // Icosahedron
    const icosahedronGeometry = new THREE.IcosahedronGeometry(0.45, 0);
    const icosahedron = new THREE.Mesh(icosahedronGeometry, materials.pink);
    icosahedron.position.set(0, 2.5, -3);
    icosahedron.userData = {
        rotationSpeed: { x: 0.01, y: 0.012, z: 0.008 },
        floatSpeed: 0.5,
        floatOffset: Math.PI,
        baseY: 2.5
    };
    scene.add(icosahedron);
    shapes.push(icosahedron);

    // Octahedron
    const octahedronGeometry = new THREE.OctahedronGeometry(0.4, 0);
    const octahedron = new THREE.Mesh(octahedronGeometry, materials.green);
    octahedron.position.set(-2.5, -1.5, -1.5);
    octahedron.userData = {
        rotationSpeed: { x: 0.008, y: 0.015, z: 0.01 },
        floatSpeed: 0.7,
        floatOffset: Math.PI * 1.5,
        baseY: -1.5
    };
    scene.add(octahedron);
    shapes.push(octahedron);

    // Torus Knot
    const torusKnotGeometry = new THREE.TorusKnotGeometry(0.25, 0.08, 80, 12);
    const torusKnot = new THREE.Mesh(torusKnotGeometry, materials.orange);
    torusKnot.position.set(2.5, 2, -2);
    torusKnot.userData = {
        rotationSpeed: { x: 0.012, y: 0.008, z: 0.015 },
        floatSpeed: 0.55,
        floatOffset: Math.PI / 4,
        baseY: 2
    };
    scene.add(torusKnot);
    shapes.push(torusKnot);

    // Dodecahedron
    const dodecahedronGeometry = new THREE.DodecahedronGeometry(0.35, 0);
    const dodecahedron = new THREE.Mesh(dodecahedronGeometry, materials.purple);
    dodecahedron.position.set(1.5, -2, -2.5);
    dodecahedron.userData = {
        rotationSpeed: { x: 0.007, y: 0.009, z: 0.011 },
        floatSpeed: 0.45,
        floatOffset: Math.PI / 3,
        baseY: -2
    };
    scene.add(dodecahedron);
    shapes.push(dodecahedron);

    // Cone
    const coneGeometry = new THREE.ConeGeometry(0.3, 0.6, 32);
    const cone = new THREE.Mesh(coneGeometry, materials.cyan);
    cone.position.set(-1.5, 0, -3);
    cone.userData = {
        rotationSpeed: { x: 0.006, y: 0.014, z: 0.009 },
        floatSpeed: 0.65,
        floatOffset: Math.PI * 0.75,
        baseY: 0
    };
    scene.add(cone);
    shapes.push(cone);
}

function onMouseMove(event) {
    mouseX = (event.clientX - windowHalfX) / windowHalfX;
    mouseY = (event.clientY - windowHalfY) / windowHalfY;
}

function onWindowResize() {
    windowHalfX = window.innerWidth / 2;
    windowHalfY = window.innerHeight / 2;
    
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    
    renderer.setSize(window.innerWidth, window.innerHeight);
}

function animate() {
    animationId = requestAnimationFrame(animate);

    const time = Date.now() * 0.001;

    // Rotate particles slowly
    if (particles) {
        particles.rotation.y += 0.0003;
        particles.rotation.x += 0.0001;
    }

    // Animate shapes
    shapes.forEach((shape) => {
        // Rotation
        shape.rotation.x += shape.userData.rotationSpeed.x;
        shape.rotation.y += shape.userData.rotationSpeed.y;
        shape.rotation.z += shape.userData.rotationSpeed.z;

        // Float animation
        const floatY = Math.sin(time * shape.userData.floatSpeed + shape.userData.floatOffset) * 0.3;
        shape.position.y = shape.userData.baseY + floatY;
    });

    // Camera follows mouse smoothly
    const targetX = mouseX * 1.5;
    const targetY = -mouseY * 1;
    
    camera.position.x += (targetX - camera.position.x) * 0.02;
    camera.position.y += (targetY - camera.position.y) * 0.02;
    camera.lookAt(scene.position);

    renderer.render(scene, camera);
}

// Cleanup function
function destroyThreeScene() {
    if (animationId) {
        cancelAnimationFrame(animationId);
    }
    
    if (renderer) {
        renderer.dispose();
    }
    
    if (scene) {
        scene.traverse((object) => {
            if (object.geometry) {
                object.geometry.dispose();
            }
            if (object.material) {
                if (Array.isArray(object.material)) {
                    object.material.forEach(material => material.dispose());
                } else {
                    object.material.dispose();
                }
            }
        });
    }
    
    document.removeEventListener('mousemove', onMouseMove);
    window.removeEventListener('resize', onWindowResize);
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThreeScene);
} else {
    initThreeScene();
}

// Cleanup on page unload
window.addEventListener('beforeunload', destroyThreeScene);
