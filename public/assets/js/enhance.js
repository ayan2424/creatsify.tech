/* Creatsify — premium interaction layer (progressive enhancement; safe without it) */
(function () {
  'use strict';
  var RM = matchMedia('(prefers-reduced-motion: reduce)').matches;
  var FINE = matchMedia('(hover: hover) and (pointer: fine)').matches;
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  /* 1. Cursor spotlight on cards */
  if (FINE && !RM) {
    $$('.ag-svc-bento-card,.ag-strength-card,.om-how-card,.ag-pkg-card,.om-prod-card').forEach(function (c) {
      var g = document.createElement('i');
      g.className = 'spot-glow'; g.setAttribute('aria-hidden', 'true');
      c.appendChild(g); c.classList.add('has-spot');
      c.addEventListener('pointermove', function (e) {
        var r = c.getBoundingClientRect();
        c.style.setProperty('--mx', (e.clientX - r.left) + 'px');
        c.style.setProperty('--my', (e.clientY - r.top) + 'px');
      }, { passive: true });
    });
  }

  /* 2. Hero: aurora background + gentle 3D tilt on the studio card */
  var hero = $('.om-hero');
  if (hero) {
    var au = document.createElement('div');
    au.className = 'hero-aurora'; au.setAttribute('aria-hidden', 'true');
    au.innerHTML = '<i></i><i></i><i></i>';
    hero.insertBefore(au, hero.firstChild);

    var card = $('.om-centerpiece-card', hero);
    if (card && card.parentNode && FINE && !RM) {
      var w = document.createElement('div');
      w.className = 'tilt-wrap';
      card.parentNode.insertBefore(w, card); w.appendChild(card);
      var raf = 0, nx = 0, ny = 0;
      hero.addEventListener('pointermove', function (e) {
        var r = hero.getBoundingClientRect();
        nx = (e.clientX - r.left) / r.width - 0.5; ny = (e.clientY - r.top) / r.height - 0.5;
        if (raf) return;
        raf = requestAnimationFrame(function () {
          raf = 0;
          w.style.setProperty('--rx', (-ny * 5).toFixed(2) + 'deg');
          w.style.setProperty('--ry', (nx * 7).toFixed(2) + 'deg');
          au.style.setProperty('--ax', (nx * 40).toFixed(0) + 'px');
          au.style.setProperty('--ay', (ny * 30).toFixed(0) + 'px');
        });
      }, { passive: true });
      hero.addEventListener('pointerleave', function () {
        w.style.setProperty('--rx', '0deg'); w.style.setProperty('--ry', '0deg');
      });
    }
  }

  /* 3. Mobile sticky call-to-action (appears after the hero, hides at the contact form) */
  var contact = $('#contact');
  if (hero && contact) {
    var wa = $('.om-hero-wa-link');
    var bar = document.createElement('div');
    bar.className = 'm-cta'; bar.setAttribute('aria-hidden', 'true'); bar.setAttribute('inert', '');
    bar.innerHTML = '<a class="m-cta-main" href="#contact">Start a project</a>' +
      (wa && wa.href ? '<a class="m-cta-wa" aria-label="Chat on WhatsApp" target="_blank" rel="noopener noreferrer" href="' + wa.href + '">' +
        '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.4L3 21l2.2-5.4A8.5 8.5 0 1 1 21 11.5z"/></svg></a>' : '');
    document.body.appendChild(bar);
    var pastHero = false, atContact = false;
    var sync = function () {
      var on = pastHero && !atContact;
      bar.classList.toggle('show', on);
      bar.setAttribute('aria-hidden', on ? 'false' : 'true');
      if (on) bar.removeAttribute('inert'); else bar.setAttribute('inert', '');
    };
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (en) { pastHero = !en[0].isIntersecting && en[0].boundingClientRect.top < 0; sync(); }).observe(hero);
      new IntersectionObserver(function (en) { atContact = en[0].isIntersecting; sync(); }, { rootMargin: '0px 0px -20% 0px' }).observe(contact);
    }
  }
})();
