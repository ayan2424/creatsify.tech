/**
 * Creatsify Interactive Agency Stage & Conversion Engine (hero-omoway.js)
 * - 3D Hero Stage Tilt & Telemetry Bars
 * - Interactive Service Pillar Filter ([data-svc-filter])
 * - Interactive Case Studies Filter ([data-work-filter])
 * - Interactive Package Track Tabs ([data-pkg-tab])
 * - Live Multi-Service Custom Bundle Estimator (#scopeEstimator)
 * - 1-Click Package & Service Selector ([data-select-package])
 * - Interactive 1-5 Star Rating Picker (#interactiveStarPicker)
 */
(function () {
  'use strict';

  function initAgencyInteractions() {
    // 1. Hero 3D Stage & Bar Animation
    const stage = document.getElementById('omVisualStage');
    if (stage) {
      const centerpiece = stage.querySelector('.om-centerpiece-card');
      const floatCards = stage.querySelectorAll('.om-float-card');
      const isTouch = window.matchMedia && window.matchMedia('(hover: none)').matches;

      if (!isTouch && centerpiece) {
        stage.addEventListener('mousemove', function (e) {
          const rect = stage.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          const cx = rect.width / 2;
          const cy = rect.height / 2;

          const rotX = ((y - cy) / cy) * -5;
          const rotY = ((x - cx) / cx) * 5;

          centerpiece.style.transform = `perspective(800px) rotateX(${rotX.toFixed(2)}deg) rotateY(${rotY.toFixed(2)}deg) scale3d(1.01, 1.01, 1.01)`;

          floatCards.forEach((card, idx) => {
            const factor = (idx + 1) * 1.4;
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

    // 2. Service Category Filter ([data-svc-filter])
    const svcButtons = document.querySelectorAll('[data-svc-filter]');
    const svcCards = document.querySelectorAll('[data-svc-cat]');
    if (svcButtons.length && svcCards.length) {
      svcButtons.forEach(btn => {
        btn.addEventListener('click', function () {
          const filter = this.getAttribute('data-svc-filter');
          svcButtons.forEach(b => b.classList.remove('active'));
          this.classList.add('active');

          svcCards.forEach(card => {
            const cat = card.getAttribute('data-svc-cat') || '';
            if (filter === 'all' || cat.includes(filter)) {
              card.style.display = '';
              card.classList.add('on');
            } else {
              card.style.display = 'none';
            }
          });
        });
      });
    }

    // 3. Case Study Filter ([data-work-filter])
    const workButtons = document.querySelectorAll('[data-work-filter]');
    const workCards = document.querySelectorAll('[data-work-cat]');
    if (workButtons.length && workCards.length) {
      workButtons.forEach(btn => {
        btn.addEventListener('click', function () {
          const filter = this.getAttribute('data-work-filter');
          workButtons.forEach(b => b.classList.remove('active'));
          this.classList.add('active');

          workCards.forEach(card => {
            const cat = card.getAttribute('data-work-cat') || '';
            if (filter === 'all' || cat.includes(filter)) {
              card.style.display = '';
              card.classList.add('on');
            } else {
              card.style.display = 'none';
            }
          });
        });
      });
    }

    // 4. Package Track Switcher Tabs ([data-pkg-tab])
    const pkgTabs = document.querySelectorAll('[data-pkg-tab]');
    const pkgPanels = document.querySelectorAll('[data-pkg-panel]');
    if (pkgTabs.length && pkgPanels.length) {
      pkgTabs.forEach(tab => {
        tab.addEventListener('click', function () {
          const targetTrack = this.getAttribute('data-pkg-tab');
          pkgTabs.forEach(t => t.classList.remove('active'));
          this.classList.add('active');

          pkgPanels.forEach(panel => {
            const panelTrack = panel.getAttribute('data-pkg-panel');
            if (targetTrack === 'all' || panelTrack === targetTrack) {
              panel.style.display = '';
              panel.classList.add('active');
            } else {
              panel.style.display = 'none';
              panel.classList.remove('active');
            }
          });
        });
      });
    }

    // 5. Interactive Custom Scope & Bundle Estimator (#scopeEstimator)
    const estimator = document.getElementById('scopeEstimator');
    if (estimator) {
      const checkboxes = estimator.querySelectorAll('input[type="checkbox"]');
      const totalPriceEl = document.getElementById('seTotalPrice');
      const timelineEl = document.getElementById('seTimeline');
      const lockBtn = document.getElementById('seLockBtn');

      function recalcBundle() {
        let rawTotal = 0;
        let maxDays = 5;
        const selectedNames = [];

        checkboxes.forEach(cb => {
          if (cb.checked) {
            rawTotal += parseInt(cb.getAttribute('data-price') || '0', 10);
            const d = parseInt(cb.getAttribute('data-days') || '5', 10);
            if (d > maxDays) maxDays = d;
            selectedNames.push(cb.value);
          }
        });

        const discounted = selectedNames.length > 1 ? Math.round(rawTotal * 0.85) : rawTotal;
        if (totalPriceEl) {
          totalPriceEl.textContent = discounted > 0 ? '$' + discounted.toLocaleString() : 'Select Services';
        }
        if (timelineEl) {
          timelineEl.textContent = selectedNames.length > 0
            ? `Est. Kickoff & Delivery: ~${maxDays + (selectedNames.length > 1 ? 3 : 0)} Days`
            : 'Check at least one service above';
        }
        return { discounted, selectedNames };
      }

      checkboxes.forEach(cb => cb.addEventListener('change', recalcBundle));
      recalcBundle();

      if (lockBtn) {
        lockBtn.addEventListener('click', function () {
          const { discounted, selectedNames } = recalcBundle();
          if (!selectedNames.length) return;
          const summaryStr = `Custom Agency Bundle ($${discounted}): ` + selectedNames.join(' + ');
          selectPackageAndScroll(summaryStr, `I would like to lock in this Custom Multi-Service Bundle (Estimated $${discounted}):\n- ` + selectedNames.join('\n- '));
        });
      }
    }

    // 6. 1-Click Package & Service Selector ([data-select-package])
    function selectPackageAndScroll(pkgName, customMessage) {
      const selectEl = document.getElementById('homeProjectTypeSelect');
      const msgEl = document.getElementById('homeProjectMessage');
      if (selectEl && pkgName) {
        let matched = false;
        for (let i = 0; i < selectEl.options.length; i++) {
          if (selectEl.options[i].value === pkgName || selectEl.options[i].text.includes(pkgName)) {
            selectEl.selectedIndex = i;
            matched = true;
            break;
          }
        }
        if (!matched) {
          const opt = document.createElement('option');
          opt.value = pkgName;
          opt.textContent = pkgName;
          opt.selected = true;
          selectEl.insertBefore(opt, selectEl.firstChild);
        }
      }
      if (msgEl && customMessage && !msgEl.value.trim()) {
        msgEl.value = customMessage;
      }
      const contactSec = document.getElementById('contact') || document.getElementById('package-booking') || document.getElementById('inquiry-form');
      if (contactSec) {
        contactSec.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    document.querySelectorAll('[data-select-package]').forEach(trigger => {
      trigger.addEventListener('click', function (e) {
        const pkgName = this.getAttribute('data-select-package');
        const contactSec = document.getElementById('contact') || document.getElementById('package-booking');
        if (pkgName && contactSec) {
          e.preventDefault();
          selectPackageAndScroll(pkgName, `Hello Creatsify! I am interested in: ${pkgName}. Please share the next steps.`);
        }
      });
    });

    // 7. Interactive 1-5 Star Rating Picker (#interactiveStarPicker)
    const starPicker = document.getElementById('interactiveStarPicker');
    if (starPicker) {
      const starBtns = starPicker.querySelectorAll('.ag-star-btn');
      const ratingInput = document.getElementById('reviewRatingInput');
      const starText = document.getElementById('starPickerText');
      const labels = {
        5: '5.0 — Exceptional Experience',
        4: '4.0 — Very Good Experience',
        3: '3.0 — Good Experience',
        2: '2.0 — Fair Experience',
        1: '1.0 — Needs Improvement'
      };

      starBtns.forEach(btn => {
        btn.addEventListener('click', function () {
          const val = parseInt(this.getAttribute('data-star-val') || '5', 10);
          if (ratingInput) ratingInput.value = String(val);
          starBtns.forEach(b => {
            const bVal = parseInt(b.getAttribute('data-star-val') || '0', 10);
            b.classList.toggle('active', bVal <= val);
          });
          if (starText && labels[val]) {
            starText.textContent = labels[val];
          }
        });
      });
    }

    // 8. Interactive Reviews Carousel Slider (#revCarouselWrap)
    const revWrap = document.getElementById('revCarouselWrap');
    if (revWrap) {
      const viewport = document.getElementById('revViewport');
      const track = document.getElementById('revTrack');
      const prevBtn = document.getElementById('revPrevBtn');
      const nextBtn = document.getElementById('revNextBtn');
      const dotsContainer = document.getElementById('revDots');
      const slides = Array.from(track ? track.querySelectorAll('.ag-review-slide') : []);
      const totalSlides = slides.length;

      if (viewport && track && totalSlides > 0) {
        let currentIndex = 0;
        let autoTimer = null;
        let startX = 0;
        let isDragging = false;

        function getVisibleCount() {
          const width = window.innerWidth;
          if (width <= 680) return 1;
          if (width <= 1024) return 2;
          return 3;
        }

        function getMaxIndex() {
          return Math.max(0, totalSlides - getVisibleCount());
        }

        function buildDots() {
          if (!dotsContainer) return;
          dotsContainer.innerHTML = '';
          const max = getMaxIndex();
          const totalDots = max + 1;
          for (let i = 0; i < totalDots; i++) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'ag-carousel-dot' + (i === currentIndex ? ' active' : '');
            dot.setAttribute('aria-label', 'Go to review slide ' + (i + 1));
            dot.addEventListener('click', () => {
              goTo(i);
              resetAuto();
            });
            dotsContainer.appendChild(dot);
          }
        }

        function update() {
          const max = getMaxIndex();
          if (currentIndex > max) currentIndex = max;
          if (currentIndex < 0) currentIndex = 0;

          const firstSlide = slides[0];
          const slideOffset = firstSlide ? (firstSlide.offsetWidth + 24) * currentIndex : 0;
          track.style.transform = `translateX(-${slideOffset}px)`;

          if (dotsContainer) {
            const dots = dotsContainer.querySelectorAll('.ag-carousel-dot');
            dots.forEach((dot, idx) => {
              dot.classList.toggle('active', idx === currentIndex);
            });
          }

          if (prevBtn) prevBtn.style.opacity = currentIndex === 0 ? '0.4' : '1';
          if (nextBtn) nextBtn.style.opacity = currentIndex >= max ? '0.4' : '1';
        }

        function goTo(index) {
          const max = getMaxIndex();
          if (index > max) currentIndex = 0;
          else if (index < 0) currentIndex = max;
          else currentIndex = index;
          update();
        }

        function next() {
          const max = getMaxIndex();
          if (currentIndex >= max) goTo(0);
          else goTo(currentIndex + 1);
        }

        function prev() {
          const max = getMaxIndex();
          if (currentIndex <= 0) goTo(max);
          else goTo(currentIndex - 1);
        }

        if (prevBtn) {
          prevBtn.addEventListener('click', () => {
            prev();
            resetAuto();
          });
        }

        if (nextBtn) {
          nextBtn.addEventListener('click', () => {
            next();
            resetAuto();
          });
        }

        function startAuto() {
          stopAuto();
          autoTimer = setInterval(next, 5000);
        }

        function stopAuto() {
          if (autoTimer) {
            clearInterval(autoTimer);
            autoTimer = null;
          }
        }

        function resetAuto() {
          stopAuto();
          startAuto();
        }

        revWrap.addEventListener('mouseenter', stopAuto);
        revWrap.addEventListener('mouseleave', startAuto);

        // Touch swipe support
        viewport.addEventListener('touchstart', (e) => {
          startX = e.touches[0].clientX;
          stopAuto();
        }, { passive: true });

        viewport.addEventListener('touchend', (e) => {
          const diff = startX - e.changedTouches[0].clientX;
          if (Math.abs(diff) > 40) {
            if (diff > 0) next();
            else prev();
          }
          startAuto();
        }, { passive: true });

        // Mouse drag support
        viewport.addEventListener('mousedown', (e) => {
          isDragging = true;
          startX = e.clientX;
          stopAuto();
        });

        window.addEventListener('mouseup', (e) => {
          if (!isDragging) return;
          isDragging = false;
          const diff = startX - e.clientX;
          if (Math.abs(diff) > 40) {
            if (diff > 0) next();
            else prev();
          }
          startAuto();
        });

        window.addEventListener('resize', () => {
          buildDots();
          update();
        });

        buildDots();
        update();
        startAuto();
      }
    }

    // 9. Portfolio Quick Preview Lightbox Modal handled globally in main.js


    // 10. Dual-Row Touch & Mouse Draggable Review Marquee Streams
    const marqueeContainer = document.getElementById('reviewsMarqueeContainer');
    if (marqueeContainer) {
      const rows = marqueeContainer.querySelectorAll('.om-marquee-row');
      rows.forEach(row => {
        let isDown = false;
        let startX = 0;
        let scrollStart = 0;

        // Mouse Drag Handlers
        row.addEventListener('mousedown', (e) => {
          isDown = true;
          row.classList.add('is-dragging');
          marqueeContainer.classList.add('js-drag-active');
          startX = e.pageX - row.offsetLeft;
          scrollStart = row.scrollLeft;
        });

        window.addEventListener('mousemove', (e) => {
          if (!isDown) return;
          const x = e.pageX - row.offsetLeft;
          const walk = (x - startX) * 1.5;
          row.scrollLeft = scrollStart - walk;
        });

        window.addEventListener('mouseup', () => {
          if (!isDown) return;
          isDown = false;
          row.classList.remove('is-dragging');
          setTimeout(() => {
            marqueeContainer.classList.remove('js-drag-active');
          }, 400);
        });

        // Touch Interaction Handlers
        row.addEventListener('touchstart', () => {
          marqueeContainer.classList.add('js-drag-active');
        }, { passive: true });

        row.addEventListener('touchend', () => {
          setTimeout(() => {
            marqueeContainer.classList.remove('js-drag-active');
          }, 1200);
        }, { passive: true });

        // Wheel horizontal scrolling support
        row.addEventListener('wheel', (e) => {
          if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
            marqueeContainer.classList.add('js-drag-active');
            clearTimeout(row._wheelTimeout);
            row._wheelTimeout = setTimeout(() => {
              marqueeContainer.classList.remove('js-drag-active');
            }, 800);
          }
        }, { passive: true });
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAgencyInteractions);
  } else {
    initAgencyInteractions();
  }
})();
