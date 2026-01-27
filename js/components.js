/**
 * Creatsify Component Loader
 * Loads and manages header and footer components across all pages
 * Ensures consistent design and navigation across the website
 */

class ComponentLoader {
    constructor() {
        this.basePath = this.getBasePath();
        this.currentPage = this.getCurrentPage();
        this.componentsLoaded = false;
    }

    /**
     * Determine the base path based on current page location
     * Handles pages in subdirectories (e.g., /blog/)
     */
    getBasePath() {
        const path = window.location.pathname;
        const depth = (path.match(/\//g) || []).length - 1;
        
        // Check if we're in a subdirectory
        if (path.includes('/blog/')) {
            return '../';
        }
        return '';
    }

    /**
     * Get the current page identifier for active nav highlighting
     */
    getCurrentPage() {
        const path = window.location.pathname.toLowerCase();
        
        if (path.includes('index') || path.endsWith('/') || path === '') return 'home';
        if (path.includes('portfolio')) return 'portfolio';
        if (path.includes('services')) return 'services';
        if (path.includes('about')) return 'about';
        if (path.includes('blog') && !path.includes('/blog/')) return 'blog';
        if (path.includes('/blog/')) return 'blog'; // Blog posts also highlight blog
        if (path.includes('contact')) return 'contact';
        
        return '';
    }

    /**
     * Load HTML component from file
     */
    async loadComponent(componentPath) {
        try {
            const response = await fetch(this.basePath + componentPath);
            if (!response.ok) throw new Error(`Failed to load ${componentPath}`);
            return await response.text();
        } catch (error) {
            console.error(`Error loading component: ${error.message}`);
            return null;
        }
    }

    /**
     * Update component paths based on current page depth
     */
    updatePaths(html) {
        if (!this.basePath) return html;
        
        // Update relative paths for assets, css, js, and pages
        return html
            .replace(/href="index\.html/g, `href="${this.basePath}index.html`)
            .replace(/href="portfolio\.html/g, `href="${this.basePath}portfolio.html`)
            .replace(/href="services\.html/g, `href="${this.basePath}services.html`)
            .replace(/href="about\.html/g, `href="${this.basePath}about.html`)
            .replace(/href="blog\.html/g, `href="${this.basePath}blog.html`)
            .replace(/href="contact\.html/g, `href="${this.basePath}contact.html`)
            .replace(/href="privacy\.html/g, `href="${this.basePath}privacy.html`)
            .replace(/href="terms\.html/g, `href="${this.basePath}terms.html`)
            .replace(/src="assets\//g, `src="${this.basePath}assets/`);
    }

    /**
     * Set active navigation link based on current page
     */
    setActiveNavLink(container) {
        const navLinks = container.querySelectorAll('.nav-link[data-page]');
        navLinks.forEach(link => {
            const page = link.getAttribute('data-page');
            if (page === this.currentPage) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    /**
     * Initialize header component
     */
    async initHeader() {
        const headerPlaceholder = document.getElementById('header-component');
        if (!headerPlaceholder) return;

        const headerHtml = await this.loadComponent('components/header.html');
        if (headerHtml) {
            const updatedHtml = this.updatePaths(headerHtml);
            headerPlaceholder.innerHTML = updatedHtml;
            
            // Set active navigation
            this.setActiveNavLink(headerPlaceholder);
            
            // Re-initialize hamburger menu functionality
            this.initMobileMenu();
            
            // Apply scrolled class if needed
            this.handleNavbarScroll();
        }
    }

    /**
     * Initialize footer component
     */
    async initFooter() {
        const footerPlaceholder = document.getElementById('footer-component');
        if (!footerPlaceholder) return;

        const footerHtml = await this.loadComponent('components/footer.html');
        if (footerHtml) {
            const updatedHtml = this.updatePaths(footerHtml);
            footerPlaceholder.innerHTML = updatedHtml;
            
            // Re-initialize newsletter form
            this.initNewsletterForm();
        }
    }

    /**
     * Initialize mobile menu functionality
     */
    initMobileMenu() {
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('nav-links');
        
        if (hamburger && navLinks) {
            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                navLinks.classList.toggle('active');
                document.body.classList.toggle('menu-open');
            });

            // Close menu when clicking a link
            navLinks.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    hamburger.classList.remove('active');
                    navLinks.classList.remove('active');
                    document.body.classList.remove('menu-open');
                });
            });
        }
    }

    /**
     * Handle navbar scroll behavior
     */
    handleNavbarScroll() {
        const navbar = document.getElementById('navbar');
        if (!navbar) return;

        // Check if page has hero section (should not be scrolled initially)
        const hasHero = document.querySelector('.hero');
        
        // For pages without hero, always show scrolled navbar
        if (!hasHero) {
            navbar.classList.add('scrolled');
        }

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else if (hasHero) {
                navbar.classList.remove('scrolled');
            }
        });
    }

    /**
     * Initialize newsletter form
     */
    initNewsletterForm() {
        const form = document.getElementById('newsletter-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const email = form.querySelector('input[type="email"]').value;
                
                // Show success message
                const button = form.querySelector('button');
                const originalHtml = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                
                setTimeout(() => {
                    button.innerHTML = originalHtml;
                    button.style.background = '';
                    form.reset();
                }, 2000);
                
                console.log('Newsletter subscription:', email);
            });
        }
    }

    /**
     * Set custom loader text based on page
     */
    setLoaderText() {
        const loaderText = document.querySelector('.loader-text');
        if (!loaderText) return;

        const pageTexts = {
            'home': 'Creating Magic',
            'portfolio': 'Loading Gallery',
            'services': 'Our Services',
            'about': 'Our Story',
            'blog': 'Loading Articles',
            'contact': 'Get in Touch'
        };

        loaderText.textContent = pageTexts[this.currentPage] || 'Loading';
    }

    /**
     * Initialize all components
     */
    async init() {
        await Promise.all([
            this.initHeader(),
            this.initFooter()
        ]);
        
        this.setLoaderText();
        this.componentsLoaded = true;
        
        // Dispatch event when components are loaded
        window.dispatchEvent(new CustomEvent('componentsLoaded'));
    }
}

// Initialize component loader when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const loader = new ComponentLoader();
    loader.init();
});

// Export for use in other scripts
window.ComponentLoader = ComponentLoader;
