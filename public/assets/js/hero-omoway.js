/**
 * Creatsify Interactive Hero Stage (hero-omoway.js)
 * High-craft floating card physics, 3D tilt, and dynamic chart interactions
 * Pure vanilla ES6, 60fps, passive listeners
 */
(function () {
  'use strict';

  function initHero() {
    const stage = document.getElementById('omVisualStage');
    if (!stage) return;

    const centerpiece = stage.querySelector('.om-centerpiece-card');
    const floatCards = stage.querySelectorAll('.om-float-card');
    const isTouch = window.matchMedia && window.matchMedia('(hover: none)').matches;

    // Subtle 3D tilt on centerpiece when hovering stage
    if (!isTouch && centerpiece) {
      stage.addEventListener('mousemove', function (e) {
        const rect = stage.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const cx = rect.width / 2;
        const cy = rect.height / 2;

        const rotX = ((y - cy) / cy) * -6;
        const rotY = ((x - cx) / cx) * 6;

        centerpiece.style.transform = `perspective(800px) rotateX(${rotX.toFixed(2)}deg) rotateY(${rotY.toFixed(2)}deg) scale3d(1.01, 1.01, 1.01)`;

        floatCards.forEach((card, idx) => {
          const factor = (idx + 1) * 1.5;
          const transX = ((x - cx) / cx) * factor;
          const transY = ((y - cy) / cy) * factor;
          card.style.transform = `translate3d(${transX.toFixed(1)}px, ${transY.toFixed(1)}px, 0)`;
        });
      }, { passive: true });

      stage.addEventListener('mouseleave', function () {
        centerpiece.style.transform = '';
        floatCards.forEach(card => {
          card.style.transform = '';
        });
      });
    }

    // Trigger bar chart growth on view
    const bars = stage.querySelectorAll('.om-chart-bar');
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            bars.forEach(bar => bar.classList.add('active'));
            observer.disconnect();
          }
        });
      }, { threshold: 0.2 });
      observer.observe(stage);
    } else {
      bars.forEach(bar => bar.classList.add('active'));
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHero);
  } else {
    initHero();
  }
})();
