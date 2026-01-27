/**
 * Main JavaScript File
 * Handles navigation, form validation, modal, filtering, and other interactions
 * Updated for multi-page architecture
 */

// Project data for modal
const projects = {
    1: {
        title: "Futuristic City",
        category: "3D Animation",
        description: "A stunning 3D animated cityscape showcasing futuristic architecture and dynamic lighting. This project combines cutting-edge rendering techniques with creative storytelling to bring an imaginary metropolis to life.",
        client: "Tech Innovations Inc.",
        duration: "6 weeks",
        tools: "Blender, After Effects, Cinema 4D",
        gradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)"
    },
    2: {
        title: "Brand Identity Animation",
        category: "Motion Graphics",
        description: "Dynamic motion graphics package for a modern tech brand. Features smooth transitions, elegant typography animations, and vibrant color schemes that capture the essence of innovation and creativity.",
        client: "Creative Labs",
        duration: "4 weeks",
        tools: "After Effects, Illustrator, Premiere Pro",
        gradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)"
    },
    3: {
        title: "Sci-Fi Visual Effects",
        category: "VFX",
        description: "Hollywood-quality visual effects for a sci-fi short film. Includes particle simulations, energy effects, and seamless compositing that blends CGI elements with live-action footage.",
        client: "Indie Film Productions",
        duration: "8 weeks",
        tools: "Houdini, Nuke, Maya, Unreal Engine",
        gradient: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"
    },
    4: {
        title: "Animated Character",
        category: "Character Design",
        description: "Fully rigged and animated 3D character with expressive features and smooth motion. Designed for use in games and animations with a unique personality and style.",
        client: "Game Studio X",
        duration: "5 weeks",
        tools: "ZBrush, Maya, Substance Painter",
        gradient: "linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)"
    },
    5: {
        title: "Game Cinematic",
        category: "3D Animation",
        description: "Epic cinematic trailer for an action-packed video game. Features dramatic camera work, intense action sequences, and photorealistic rendering that captivates viewers.",
        client: "Pixel Studios",
        duration: "10 weeks",
        tools: "Unreal Engine, Maya, Substance, DaVinci Resolve",
        gradient: "linear-gradient(135deg, #fa709a 0%, #fee140 100%)"
    },
    6: {
        title: "Data Visualization",
        category: "Motion Graphics",
        description: "Engaging data visualization animations that transform complex statistics into beautiful, easy-to-understand visual stories. Perfect for presentations and marketing materials.",
        client: "Analytics Corp",
        duration: "3 weeks",
        tools: "After Effects, D3.js, Cinema 4D",
        gradient: "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)"
    },
    7: {
        title: "Abstract Fluid Dynamics",
        category: "Motion Graphics",
        description: "Mesmerizing fluid simulations combined with abstract art direction. This project showcases the beauty of physics-based animation with stunning color gradients.",
        client: "Art Gallery Modern",
        duration: "3 weeks",
        tools: "Cinema 4D, X-Particles, After Effects",
        gradient: "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)"
    },
    8: {
        title: "Environmental VFX",
        category: "VFX",
        description: "Photorealistic environmental effects including weather systems, atmospheric elements, and natural phenomena for film production.",
        client: "Nature Films Co.",
        duration: "12 weeks",
        tools: "Houdini, Maya, Nuke, After Effects",
        gradient: "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"
    },
    9: {
        title: "Mascot Character Series",
        category: "Character Design",
        description: "A lovable series of mascot characters designed for a children's educational platform. Features expressive animation cycles and engaging personalities.",
        client: "EduKids Platform",
        duration: "8 weeks",
        tools: "Maya, ZBrush, Substance Painter, Unity",
        gradient: "linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)"
    }
};

// DOM Elements
let navbar, navLinks, hamburger, preloader, portfolioItems, filterButtons;
let currentSlide = 0;
let testimonialItems, sliderDots;

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    navbar = document.getElementById('navbar');
    navLinks = document.getElementById('nav-links');
    hamburger = document.getElementById('hamburger');
    preloader = document.getElementById('preloader');
    // Support both portfolio-item and showcase-item classes
    portfolioItems = document.querySelectorAll('.portfolio-item, .showcase-item');
    filterButtons = document.querySelectorAll('.filter-btn');
    testimonialItems = document.querySelectorAll('.testimonial-item');
    sliderDots = document.querySelectorAll('.dot');

    // Initialize all features
    initNavigation();
    initPreloader();
    initPortfolioFilter();
    initModal();
    initTestimonialSlider();
    initContactForm();
    initNewsletterForm();
    initSmoothScroll();
    initFAQ();
    initMagneticButtons();
    initParallax();
    initBackToTop();
    initHeroStatCounters();
});

// ========================================
// Preloader with Percentage Counter
// ========================================
function initPreloader() {
    if (!preloader) return;
    
    const progressBar = preloader.querySelector('.loader-progress-bar');
    const percentEl = document.getElementById('load-percent');
    let progress = 0;
    
    const interval = setInterval(() => {
        progress += Math.random() * 12;
        if (progress > 100) progress = 100;
        
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
        
        if (percentEl) {
            percentEl.textContent = Math.floor(progress);
        }
        
        if (progress === 100) {
            clearInterval(interval);
        }
    }, 80);
    
    window.addEventListener('load', () => {
        // Ensure we reach 100%
        progress = 100;
        if (progressBar) {
            progressBar.style.width = '100%';
        }
        if (percentEl) {
            percentEl.textContent = '100';
        }
        
        setTimeout(() => {
            preloader.classList.add('hidden');
            document.body.classList.add('loaded');
        }, 500);
    });
}

// ========================================
// Navigation
// ========================================
function initNavigation() {
    if (!hamburger || !navLinks) return;
    
    // Hamburger menu toggle
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navLinks.classList.toggle('active');
        document.body.classList.toggle('menu-open');
    });

    // Close menu when clicking on a link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navLinks.classList.remove('active');
            document.body.classList.remove('menu-open');
        });
    });

    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        if (navbar) {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }

        // Update active nav link based on scroll position
        updateActiveNavLink();
    });
    
    // Set initial state based on scroll position
    if (navbar && window.scrollY > 50) {
        navbar.classList.add('scrolled');
    }
}

function updateActiveNavLink() {
    const sections = document.querySelectorAll('section[id]');
    const navLinksArray = document.querySelectorAll('.nav-link');
    
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (window.pageYOffset >= sectionTop - 200) {
            current = section.getAttribute('id');
        }
    });

    navLinksArray.forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.includes('#')) {
            link.classList.remove('active');
            if (href === '#' + current) {
                link.classList.add('active');
            }
        }
    });
}

// ========================================
// Smooth Scroll
// ========================================
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ========================================
// Portfolio Filter
// ========================================
function initPortfolioFilter() {
    if (!filterButtons.length || !portfolioItems.length) return;
    
    const portfolioCount = document.querySelector('.portfolio-count');
    
    function updateCount(shown, total) {
        if (portfolioCount) {
            portfolioCount.textContent = `Showing ${shown} of ${total} projects`;
        }
    }
    
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // Filter items with animation
            let visibleCount = 0;
            portfolioItems.forEach((item, index) => {
                const category = item.getAttribute('data-category');
                
                if (filter === 'all' || category === filter) {
                    item.style.transitionDelay = `${index * 0.05}s`;
                    item.classList.remove('hide');
                    item.classList.add('show');
                    visibleCount++;
                } else {
                    item.style.transitionDelay = '0s';
                    item.classList.remove('show');
                    item.classList.add('hide');
                }
            });
            
            updateCount(visibleCount, portfolioItems.length);
        });
    });

    // Show all items initially
    portfolioItems.forEach(item => {
        item.classList.add('show');
    });
    
    // Set initial count
    updateCount(portfolioItems.length, portfolioItems.length);
}

// ========================================
// Project Modal
// ========================================
function initModal() {
    const modal = document.getElementById('project-modal');
    if (!modal) return;
    
    const closeBtn = modal.querySelector('.modal-close');
    const viewProjectBtns = document.querySelectorAll('.view-project');

    // Open modal
    viewProjectBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const projectId = btn.getAttribute('data-project');
            openModal(projectId);
        });
    });

    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            closeModal(modal);
        });
    }

    // Close on outside click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal(modal);
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal(modal);
        }
    });
}

function openModal(projectId) {
    const project = projects[projectId];
    const modal = document.getElementById('project-modal');
    
    if (!project || !modal) return;

    // Update modal content
    const modalTitle = document.getElementById('modal-title');
    const modalCategory = document.getElementById('modal-category');
    const modalDescription = document.getElementById('modal-description');
    const modalClient = document.getElementById('modal-client');
    const modalDuration = document.getElementById('modal-duration');
    const modalTools = document.getElementById('modal-tools');
    const modalImage = document.getElementById('modal-image');

    if (modalTitle) modalTitle.textContent = project.title;
    if (modalCategory) modalCategory.textContent = project.category;
    if (modalDescription) modalDescription.textContent = project.description;
    if (modalClient) modalClient.textContent = project.client;
    if (modalDuration) modalDuration.textContent = project.duration;
    if (modalTools) modalTools.textContent = project.tools;
    if (modalImage) modalImage.style.background = project.gradient;
    
    // Show modal with animation
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

// ========================================
// Testimonial Slider
// ========================================
let sliderAutoPlay;
let isSliderPaused = false;

function initTestimonialSlider() {
    if (!testimonialItems || !testimonialItems.length) return;
    
    const prevBtn = document.querySelector('.slider-btn.prev');
    const nextBtn = document.querySelector('.slider-btn.next');
    const pauseBtn = document.querySelector('.slider-btn.pause');

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            changeSlide(currentSlide - 1);
            resetAutoPlay();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            changeSlide(currentSlide + 1);
            resetAutoPlay();
        });
    }
    
    // Pause/Play toggle
    if (pauseBtn) {
        pauseBtn.addEventListener('click', () => {
            isSliderPaused = !isSliderPaused;
            if (isSliderPaused) {
                pauseBtn.classList.add('paused');
                pauseBtn.classList.remove('playing');
                pauseBtn.setAttribute('aria-label', 'Play slider');
                pauseBtn.querySelector('i').classList.remove('fa-pause');
                pauseBtn.querySelector('i').classList.add('fa-play');
                clearInterval(sliderAutoPlay);
            } else {
                pauseBtn.classList.remove('paused');
                pauseBtn.classList.add('playing');
                pauseBtn.setAttribute('aria-label', 'Pause slider');
                pauseBtn.querySelector('i').classList.remove('fa-play');
                pauseBtn.querySelector('i').classList.add('fa-pause');
                startAutoPlay();
            }
        });
    }

    // Dot navigation
    if (sliderDots && sliderDots.length) {
        sliderDots.forEach(dot => {
            dot.addEventListener('click', () => {
                const slideIndex = parseInt(dot.getAttribute('data-slide'));
                changeSlide(slideIndex);
                resetAutoPlay();
            });
        });
    }

    // Start auto slide
    startAutoPlay();
}

function startAutoPlay() {
    if (isSliderPaused) return;
    sliderAutoPlay = setInterval(() => {
        changeSlide(currentSlide + 1);
    }, 6000);
}

function resetAutoPlay() {
    if (isSliderPaused) return;
    clearInterval(sliderAutoPlay);
    startAutoPlay();
}

function changeSlide(newSlide) {
    if (!testimonialItems || !testimonialItems.length) return;
    
    if (newSlide < 0) {
        newSlide = testimonialItems.length - 1;
    } else if (newSlide >= testimonialItems.length) {
        newSlide = 0;
    }

    testimonialItems[currentSlide].classList.remove('active');
    if (sliderDots && sliderDots[currentSlide]) {
        sliderDots[currentSlide].classList.remove('active');
    }
    
    currentSlide = newSlide;
    
    testimonialItems[currentSlide].classList.add('active');
    if (sliderDots && sliderDots[currentSlide]) {
        sliderDots[currentSlide].classList.add('active');
    }
}

// ========================================
// Contact Form
// ========================================
function initContactForm() {
    const form = document.getElementById('contact-form');
    if (!form) return;
    
    const formStatus = document.querySelector('.form-status');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Validate form
        if (validateForm(form)) {
            // Show success message
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<span>Sending...</span><i class="fas fa-spinner fa-spin"></i>';
            submitBtn.disabled = true;
            
            // Simulate form submission
            setTimeout(() => {
                submitBtn.innerHTML = '<span>Message Sent!</span><i class="fas fa-check"></i>';
                submitBtn.classList.add('success');
                
                // Reset form after delay
                setTimeout(() => {
                    form.reset();
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('success');
                }, 3000);
            }, 1500);
        }
    });

    // Real-time validation
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('blur', () => {
            validateField(input);
        });
        
        input.addEventListener('input', () => {
            if (input.parentElement.classList.contains('error')) {
                validateField(input);
            }
        });
    });
}

function validateField(field) {
    const formGroup = field.closest('.form-group');
    if (!formGroup) return true;
    
    let isValid = true;

    if (field.hasAttribute('required') && !field.value.trim()) {
        isValid = false;
    } else if (field.type === 'email' && field.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            isValid = false;
        }
    }

    if (isValid) {
        formGroup.classList.remove('error');
        formGroup.classList.add('valid');
    } else {
        formGroup.classList.add('error');
        formGroup.classList.remove('valid');
    }

    return isValid;
}

function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });

    return isValid;
}

// ========================================
// Newsletter Form
// ========================================
function initNewsletterForm() {
    const forms = document.querySelectorAll('.newsletter-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const email = form.querySelector('input[type="email"]');
            const btn = form.querySelector('button');
            
            if (email && email.value) {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.classList.add('success');
                
                setTimeout(() => {
                    form.reset();
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('success');
                }, 2000);
            }
        });
    });
}

// ========================================
// FAQ Accordion
// ========================================
function initFAQ() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        
        if (question && answer) {
            question.addEventListener('click', () => {
                const isOpen = item.classList.contains('active');
                
                // Close all other items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                        const otherAnswer = otherItem.querySelector('.faq-answer');
                        if (otherAnswer) otherAnswer.style.maxHeight = null;
                    }
                });
                
                // Toggle current item
                if (isOpen) {
                    item.classList.remove('active');
                    answer.style.maxHeight = null;
                } else {
                    item.classList.add('active');
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                }
            });
        }
    });
}

// ========================================
// Magnetic Buttons Effect
// ========================================
function initMagneticButtons() {
    const magneticElements = document.querySelectorAll('.btn, .project-card, .service-card');
    
    magneticElements.forEach(el => {
        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            
            el.style.transform = `translate(${x * 0.1}px, ${y * 0.1}px)`;
        });
        
        el.addEventListener('mouseleave', () => {
            el.style.transform = '';
        });
    });
}

// ========================================
// Parallax Effects
// ========================================
function initParallax() {
    const parallaxElements = document.querySelectorAll('.gradient-orb, .hero-bg');
    
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach(el => {
            const speed = el.dataset.speed || 0.5;
            el.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
}

// ========================================
// Utility Functions
// ========================================

// Debounce function for performance optimization
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function for scroll events
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Add intersection observer for lazy animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe elements for animations
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.reveal, .portfolio-item, .service-card, .team-member, .stat-card').forEach(el => {
        observer.observe(el);
    });
});

// Counter animation for stats
function animateCounter(el) {
    const target = parseInt(el.getAttribute('data-count'));
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;
    
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        el.textContent = Math.floor(current) + '+';
    }, 16);
}

// Initialize counters when in view
const statObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const counter = entry.target.querySelector('.stat-number');
            if (counter && !counter.classList.contains('animated')) {
                counter.classList.add('animated');
                animateCounter(counter);
            }
            statObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.stat-card').forEach(el => {
        statObserver.observe(el);
    });
});
// ========================================
// Back to Top Button
// ========================================
function initBackToTop() {
    const backToTopBtn = document.querySelector('.back-to-top');
    if (!backToTopBtn) return;
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', throttle(() => {
        if (window.scrollY > 500) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    }, 100));
    
    // Scroll to top when clicked
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// ========================================
// Hero Stat Counters Animation
// ========================================
function initHeroStatCounters() {
    const heroStats = document.querySelectorAll('.hero-stat-number[data-target]');
    if (!heroStats.length) return;
    
    const heroStatsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                if (!target.classList.contains('counted')) {
                    target.classList.add('counted');
                    animateHeroCounter(target);
                }
                heroStatsObserver.unobserve(target);
            }
        });
    }, { threshold: 0.5 });
    
    heroStats.forEach(stat => {
        heroStatsObserver.observe(stat);
    });
}

function animateHeroCounter(element) {
    const target = parseInt(element.getAttribute('data-target'));
    const duration = 2000;
    const startTime = performance.now();
    const startValue = 0;
    
    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function for smooth animation
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        const currentValue = Math.floor(startValue + (target - startValue) * easeOutQuart);
        
        element.textContent = currentValue + '+';
        
        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target + '+';
        }
    }
    
    requestAnimationFrame(updateCounter);
}