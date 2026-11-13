/* Admin panel — accessibility safety net (adds what individual views are missing; never overrides existing labels) */
(function () {
  'use strict';
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  /* 1. Every page needs exactly one h1 */
  if (!document.querySelector('h1')) {
    var h = document.createElement('h1');
    h.className = 'adm-sr-only';
    h.textContent = (document.title || 'Admin').split(/[|\u2014\u2013-]/)[0].trim() || 'Admin';
    (document.querySelector('main') || document.body).insertBefore(h, (document.querySelector('main') || document.body).firstChild);
  }

  /* 2. Give every unlabeled form control an accessible name */
  function humanize(s) { return (s || '').replace(/\[\]|\[|\]/g, ' ').replace(/[_-]+/g, ' ').replace(/\s+/g, ' ').trim().replace(/^./, function (c) { return c.toUpperCase(); }); }
  $$('input:not([type=hidden]):not([type=submit]):not([type=button]):not([type=reset]):not([type=image]), select, textarea').forEach(function (el) {
    if (el.getAttribute('aria-label') || el.getAttribute('aria-labelledby') || el.closest('label')) return;
    if (el.id && document.querySelector('label[for="' + el.id.replace(/"/g, '\\"') + '"]')) return;
    var name = '';
    var prev = el.previousElementSibling;
    if (prev && prev.tagName === 'LABEL') name = prev.textContent;
    if (!name) { var grp = el.closest('.form-group, .adm-field, .adm-form-group, .field'); var l = grp && grp.querySelector('label'); if (l) name = l.textContent; }
    name = (name || el.getAttribute('placeholder') || el.getAttribute('title') || humanize(el.getAttribute('name')) || 'Field').replace(/\s+/g, ' ').trim();
    el.setAttribute('aria-label', name);
  });

  /* 3. Scrollable regions must be reachable by keyboard */
  function focusScrollers() {
    $$('main [class*="table"], main [class*="scroll"], main [class*="wrap"], main [class*="list"]').forEach(function (el) {
      if (el.hasAttribute('tabindex')) return;
      var cs = getComputedStyle(el);
      if ((cs.overflowX === 'auto' || cs.overflowX === 'scroll') && el.scrollWidth > el.clientWidth + 1) {
        if (!el.querySelector('a[href],button,input,select,textarea,[tabindex]')) {
          el.setAttribute('tabindex', '0'); el.setAttribute('role', 'region');
          if (!el.getAttribute('aria-label')) el.setAttribute('aria-label', 'Scrollable content');
        }
      }
    });
  }
  focusScrollers();
  var t; window.addEventListener('resize', function () { clearTimeout(t); t = setTimeout(focusScrollers, 250); });
})();
