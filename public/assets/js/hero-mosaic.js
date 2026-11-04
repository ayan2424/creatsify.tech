/**
 * Creatsify Kinetic Hero Mosaic (hero-mosaic.js)
 * Awwwards-inspired micro-interactions & tilt physics
 * Pure vanilla ES6, 60fps, passive listeners
 */
(function () {
  'use strict';

  function initMosaic() {
    const stage = document.getElementById('mosaicStage');
    if (!stage) return;

    const cards = stage.querySelectorAll('.mosaic-card');
    const isTouch = window.matchMedia && window.matchMedia('(hover: none)').matches;
    if (isTouch) return;

    cards.forEach(function (card) {
      card.addEventListener('mousemove', function (e) {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const cx = rect.width / 2;
        const cy = rect.height / 2;
        const rotateX = ((y - cy) / cy) * -5;
        const rotateY = ((x - cx) / cx) * 5;
        card.style.transform = 'perspective(600px) rotateX(' + rotateX.toFixed(2) + 'deg) rotateY(' + rotateY.toFixed(2) + 'deg) scale3d(1.02, 1.02, 1.02)';
      }, { passive: true });

      card.addEventListener('mouseleave', function () {
        card.style.transform = '';
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMosaic);
  } else {
    initMosaic();
  }
})();
