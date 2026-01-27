# AnimStudio - Premium Animation & VFX Studio Website

![AnimStudio](https://img.shields.io/badge/AnimStudio-Premium-a855f7?style=for-the-badge)
![Version](https://img.shields.io/badge/version-2.0.0-06b6d4?style=for-the-badge)
![License](https://img.shields.io/badge/license-MIT-ec4899?style=for-the-badge)

A stunning, professional, and fully responsive multi-page website for an animation and visual effects studio. Built with modern web technologies and premium design principles.

## ✨ Features

### Design & UX
- 🎨 **Premium Dark Theme** - Professional dark color scheme with vibrant gradient accents
- 🌟 **Glassmorphism Effects** - Modern frosted glass UI components
- 🎭 **Smooth Animations** - GSAP-powered scroll animations and transitions
- 🖼️ **3D Hero Scene** - Interactive Three.js background with floating geometric shapes
- 📱 **Fully Responsive** - Optimized for all devices from mobile to 4K displays
- ♿ **Accessible** - WCAG compliant with proper ARIA labels and semantic HTML

### Pages
- **Homepage** - Hero section with 3D canvas, featured projects, services preview, testimonials
- **Portfolio** - Filterable project grid with category filters and project modal
- **Services** - Detailed service offerings with process timeline
- **About** - Company story, team members, values, and awards
- **Contact** - Contact form, FAQ accordion, and location information

### Technical Features
- 🚀 **Performance Optimized** - Lazy loading, efficient animations, optimized assets
- 🔍 **SEO Ready** - Complete meta tags, Open Graph, Twitter Cards, sitemap
- 📧 **Form Validation** - Client-side validation with user feedback
- 🍔 **Mobile Navigation** - Smooth hamburger menu with overlay
- 🎥 **Video Modal Support** - Ready for video content integration

## 🛠️ Tech Stack

- **HTML5** - Semantic markup
- **CSS3** - Custom properties, Flexbox, Grid, animations
- **JavaScript (ES6+)** - Modern vanilla JS
- **Three.js** - 3D graphics and animations
- **GSAP** - Professional-grade animations
- **Font Awesome** - Icon library
- **Google Fonts** - Poppins typography

## 📁 Project Structure

```
animation-studio/
├── index.html              # Homepage
├── robots.txt              # Search engine directives
├── sitemap.xml             # XML sitemap for SEO
├── README.md               # Documentation
│
├── css/
│   └── styles.css          # Main stylesheet (~3000+ lines)
│
├── js/
│   ├── main.js             # Core functionality
│   ├── animations.js       # GSAP animations
│   └── three-scene.js      # Three.js 3D scene
│
└── pages/
    ├── portfolio.html      # Portfolio page
    ├── services.html       # Services page
    ├── about.html          # About page
    └── contact.html        # Contact page
```

## 🚀 Getting Started

### Prerequisites
- A modern web browser (Chrome, Firefox, Safari, Edge)
- A local web server (recommended for Three.js)

### Local Development

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/animation-studio.git
   cd animation-studio
   ```

2. **Start a local server**
   
   Using Python:
   ```bash
   python -m http.server 8000
   ```
   
   Using Node.js (npx):
   ```bash
   npx serve
   ```
   
   Using VS Code:
   - Install "Live Server" extension
   - Right-click `index.html` → "Open with Live Server"

3. **Open in browser**
   ```
   http://localhost:8000
   ```

## 🌐 Deployment

### GitHub Pages
1. Push your code to GitHub
2. Go to Settings → Pages
3. Select source branch (main/master)
4. Your site will be live at `https://username.github.io/animation-studio`

### Netlify
1. Connect your GitHub repository
2. Build command: (leave empty - static site)
3. Publish directory: `/`
4. Deploy!

### Vercel
1. Import your GitHub repository
2. Framework Preset: Other
3. Deploy!

### Traditional Hosting
Upload all files to your web server's public directory (usually `public_html` or `www`).

## ⚙️ Customization

### Colors
Edit CSS custom properties in `css/styles.css`:
```css
:root {
    --primary-color: #a855f7;    /* Purple */
    --secondary-color: #06b6d4;  /* Cyan */
    --accent-color: #ec4899;     /* Pink */
    --dark-bg: #030014;          /* Background */
}
```

### Content
- Update text content directly in HTML files
- Replace placeholder images with your actual project images
- Modify project data in `js/main.js` (projects object)

### Forms
The contact form currently shows a success message on submit. To make it functional:
1. Add a backend endpoint (PHP, Node.js, etc.)
2. Or use a service like Formspree, Netlify Forms, or EmailJS

## 📊 Browser Support

| Browser | Version |
|---------|---------|
| Chrome  | 90+     |
| Firefox | 88+     |
| Safari  | 14+     |
| Edge    | 90+     |

## 🔧 Performance Tips

1. **Images**: Replace placeholder gradients with optimized WebP images
2. **Fonts**: Subset Google Fonts to only needed characters
3. **CDN**: Use a CDN for external libraries in production
4. **Caching**: Configure proper cache headers on your server

## 📝 License

This project is licensed under the MIT License - feel free to use it for personal or commercial projects.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Contact

For questions or support, please open an issue on GitHub.

---

**Made with ❤️ by AnimStudio**
