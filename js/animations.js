/**
 * GSAP Animations - Hero Section Only
 * All other animations temporarily disabled
 * Will be re-enabled in final phase
 */

// Initialize GSAP and ScrollTrigger
gsap.registerPlugin(ScrollTrigger);

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        initHeroAnimations();
    }, 100);
});

function initHeroAnimations() {
    const currentPage = detectCurrentPage();
    
    if (currentPage === 'home') {
        animateHomeHero();
    } else {
        animatePageHero();
    }
}

function detectCurrentPage() {
    const path = window.location.pathname.toLowerCase();
    if (path.includes('portfolio')) return 'portfolio';
    if (path.includes('services')) return 'services';
    if (path.includes('about')) return 'about';
    if (path.includes('contact')) return 'contact';
    return 'home';
}

// ========================================
// Home Page Hero Animation
// ========================================

function animateHomeHero() {
    const hero = document.querySelector('.hero');
    if (!hero) return;
    
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    
    // Animate gradient orbs
    gsap.to('.hero-orb-1', {
        x: 50,
        y: -30,
        duration: 8,
        repeat: -1,
        yoyo: true,
        ease: 'sine.inOut'
    });
    
    gsap.to('.hero-orb-2', {
        x: -40,
        y: 40,
        duration: 10,
        repeat: -1,
        yoyo: true,
        ease: 'sine.inOut'
    });
    
    gsap.to('.hero-orb-3', {
        x: 30,
        y: -20,
        duration: 12,
        repeat: -1,
        yoyo: true,
        ease: 'sine.inOut'
    });
    
    // Hero content animations
    tl.from('.hero-badge', {
        y: 30,
        opacity: 0,
        duration: 0.8,
        delay: 0.3
    })
    .from('.hero-title-wrapper .line', {
        y: 120,
        opacity: 0,
        duration: 1,
        stagger: 0.15
    }, '-=0.4')
    .from('.hero-description', {
        y: 40,
        opacity: 0,
        duration: 0.8
    }, '-=0.6')
    .from('.hero-buttons .btn', {
        y: 30,
        opacity: 0,
        duration: 0.6,
        stagger: 0.1
    }, '-=0.4')
    .from('.hero-stats', {
        y: 40,
        opacity: 0,
        duration: 0.8
    }, '-=0.3')
    .from('.scroll-indicator', {
        opacity: 0,
        duration: 0.6
    }, '-=0.2');
    
    // Parallax effect on scroll
    gsap.to('.hero-content', {
        scrollTrigger: {
            trigger: '.hero',
            start: 'top top',
            end: 'bottom top',
            scrub: 1
        },
        y: 100,
        opacity: 0.3
    });
}

// ========================================
// Other Pages Hero Animation
// ========================================

function animatePageHero() {
    const pageHero = document.querySelector('.page-hero');
    if (!pageHero) return;
    
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    
    // Animate hero badge if exists
    const badge = pageHero.querySelector('.hero-badge');
    if (badge) {
        tl.from(badge, {
            y: 20,
            opacity: 0,
            duration: 0.6
        });
    }
    
    tl.from('.page-title', {
        y: 80,
        opacity: 0,
        duration: 1
    }, badge ? '-=0.3' : '0')
    .from('.page-description', {
        y: 40,
        opacity: 0,
        duration: 0.8
    }, '-=0.5');
    
    // Animate hero stats if exists
    const heroStats = pageHero.querySelector('.hero-stats');
    if (heroStats) {
        tl.from('.hero-stat', {
            y: 30,
            opacity: 0,
            duration: 0.6,
            stagger: 0.1
        }, '-=0.4');
    }
}

// ========================================
// Performance
// ========================================

window.addEventListener('resize', () => {
    ScrollTrigger.refresh();
});

document.fonts.ready.then(() => {
    ScrollTrigger.refresh();
});
