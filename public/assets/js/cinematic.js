/**
 * Creatsify Studio - Cinematic & Interactive Feature Suite
 * 1. WebGL/Interactive Particle Constellation Canvas
 * 2. Web Audio Micro-Interactions (Clicks, Whooshes & Chimes)
 * 3. Interactive Studio CLI Terminal
 * 4. Glass Project Detail Modal
 * 5. Interactive Scope & Cost Calculator
 */

(function () {
  'use strict';

  // =========================================================================
  // 1. Interactive Particle Constellation Canvas
  // =========================================================================
  const initParticleCanvas = () => {
    const canvas = document.getElementById('glCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    let width = (canvas.width = window.innerWidth);
    let height = (canvas.height = window.innerHeight);

    window.addEventListener('resize', () => {
      width = canvas.width = window.innerWidth;
      height = canvas.height = window.innerHeight;
    }, { passive: true });

    const particleCount = Math.min(Math.floor((width * height) / 22000), 75);
    const particles = [];
    const mouse = { x: -1000, y: -1000, radius: 140 };

    window.addEventListener('pointermove', (e) => {
      mouse.x = e.clientX;
      mouse.y = e.clientY;
    }, { passive: true });

    window.addEventListener('mouseleave', () => {
      mouse.x = -1000;
      mouse.y = -1000;
    });

    const isDark = () => document.documentElement.dataset.theme !== 'light';

    class Particle {
      constructor() {
        this.x = Math.random() * width;
        this.y = Math.random() * height;
        this.vx = (Math.random() - 0.5) * 0.45;
        this.vy = (Math.random() - 0.5) * 0.45;
        this.radius = Math.random() * 1.6 + 1.2;
        this.alpha = Math.random() * 0.5 + 0.3;
      }

      update() {
        this.x += this.vx;
        this.y += this.vy;

        if (this.x < 0) this.x = width;
        if (this.x > width) this.x = 0;
        if (this.y < 0) this.y = height;
        if (this.y > height) this.y = 0;

        // Mouse repulsion
        const dx = mouse.x - this.x;
        const dy = mouse.y - this.y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < mouse.radius) {
          const force = (mouse.radius - dist) / mouse.radius;
          const angle = Math.atan2(dy, dx);
          this.x -= Math.cos(angle) * force * 2.5;
          this.y -= Math.sin(angle) * force * 2.5;
        }
      }

      draw() {
        ctx.save();
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fillStyle = isDark()
          ? `rgba(139, 123, 255, ${this.alpha})`
          : `rgba(91, 71, 224, ${this.alpha * 0.6})`;
        ctx.shadowBlur = 8;
        ctx.shadowColor = 'rgba(139, 123, 255, 0.4)';
        ctx.fill();
        ctx.restore();
      }
    }

    for (let i = 0; i < particleCount; i++) {
      particles.push(new Particle());
    }

    const connect = () => {
      const maxDist = 115;
      for (let a = 0; a < particles.length; a++) {
        for (let b = a + 1; b < particles.length; b++) {
          const dx = particles[a].x - particles[b].x;
          const dy = particles[a].y - particles[b].y;
          const dist = Math.sqrt(dx * dx + dy * dy);

          if (dist < maxDist) {
            const alpha = (1 - dist / maxDist) * 0.22;
            ctx.beginPath();
            ctx.moveTo(particles[a].x, particles[a].y);
            ctx.lineTo(particles[b].x, particles[b].y);
            ctx.strokeStyle = isDark()
              ? `rgba(139, 123, 255, ${alpha})`
              : `rgba(91, 71, 224, ${alpha * 0.5})`;
            ctx.lineWidth = 0.85;
            ctx.stroke();
          }
        }
      }
    };

    let isVisible = true;
    document.addEventListener('visibilitychange', () => {
      isVisible = !document.hidden;
    });

    const animate = () => {
      if (isVisible) {
        ctx.clearRect(0, 0, width, height);
        particles.forEach((p) => {
          p.update();
          p.draw();
        });
        connect();
      }
      requestAnimationFrame(animate);
    };

    animate();
  };

  // =========================================================================
  // 2. Web Audio Micro-Interactions (Audio Branding)
  // =========================================================================
  let audioCtx = null;
  let soundEnabled = localStorage.getItem('creatsify-sound-enabled') !== 'false';

  const getAudioContext = () => {
    if (!audioCtx) {
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      if (AudioContext) {
        audioCtx = new AudioContext();
      }
    }
    if (audioCtx && audioCtx.state === 'suspended') {
      audioCtx.resume();
    }
    return audioCtx;
  };

  const playClick = () => {
    if (!soundEnabled) return;
    try {
      const ctx = getAudioContext();
      if (!ctx) return;
      const osc = ctx.createOscillator();
      const gain = ctx.createGain();

      osc.type = 'sine';
      osc.frequency.setValueAtTime(1200, ctx.currentTime);
      osc.frequency.exponentialRampToValueAtTime(450, ctx.currentTime + 0.035);

      gain.gain.setValueAtTime(0.02, ctx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.035);

      osc.connect(gain);
      gain.connect(ctx.destination);

      osc.start();
      osc.stop(ctx.currentTime + 0.04);
    } catch (e) {}
  };

  const playThud = () => {
    if (!soundEnabled) return;
    try {
      const ctx = getAudioContext();
      if (!ctx) return;
      const osc = ctx.createOscillator();
      const gain = ctx.createGain();

      osc.type = 'sine';
      osc.frequency.setValueAtTime(180, ctx.currentTime);
      osc.frequency.exponentialRampToValueAtTime(60, ctx.currentTime + 0.09);

      gain.gain.setValueAtTime(0.05, ctx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.09);

      osc.connect(gain);
      gain.connect(ctx.destination);

      osc.start();
      osc.stop(ctx.currentTime + 0.1);
    } catch (e) {}
  };

  const playChime = () => {
    if (!soundEnabled) return;
    try {
      const ctx = getAudioContext();
      if (!ctx) return;
      [528, 792].forEach((freq) => {
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();

        osc.type = 'sine';
        osc.frequency.setValueAtTime(freq, ctx.currentTime);

        gain.gain.setValueAtTime(0.025, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.28);

        osc.connect(gain);
        gain.connect(ctx.destination);

        osc.start();
        osc.stop(ctx.currentTime + 0.3);
      });
    } catch (e) {}
  };

  const initAudioControls = () => {
    const soundBtn = document.getElementById('soundToggle');
    if (soundBtn) {
      soundBtn.classList.toggle('muted', !soundEnabled);
      soundBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        soundEnabled = !soundEnabled;
        localStorage.setItem('creatsify-sound-enabled', soundEnabled ? 'true' : 'false');
        soundBtn.classList.toggle('muted', !soundEnabled);
        if (soundEnabled) {
          playChime();
        }
      });
    }

    // Attach subtle audio cues to interactive elements
    document.addEventListener('pointerenter', (e) => {
      if (e.target && e.target.closest && e.target.closest('.btn, button, .card, .pj, #nav a, .calc-feature-item, .calc-platform-card')) {
        playClick();
      }
    }, true);

    document.addEventListener('click', (e) => {
      if (e.target && e.target.closest && e.target.closest('.btn, button, .chat-chip, .calc-feature-item, .calc-platform-card')) {
        playThud();
      }
    }, true);

    const themeBtn = document.getElementById('theme');
    if (themeBtn) {
      themeBtn.addEventListener('click', () => {
        playChime();
      });
    }
  };

  // =========================================================================
  // 3. Interactive Studio CLI Terminal
  // =========================================================================
  const initTerminal = () => {
    const termBody = document.getElementById('termOutput');
    const termInput = document.getElementById('termInput');
    if (!termBody || !termInput) return;

    const history = [];
    let histIndex = -1;

    const printLine = (text, className = '') => {
      const line = document.createElement('div');
      line.className = 'term-line ' + className;
      line.innerHTML = text;
      termBody.appendChild(line);
      termBody.scrollTop = termBody.scrollHeight;
    };

    const commands = {
      help: () => {
        printLine('Available commands:', 'accent');
        printLine('  <b>cheetah</b>    — View Cheetah Courier & Warehouse architecture', 'system');
        printLine('  <b>projects</b>   — Display all 10 shipped studio systems', 'system');
        printLine('  <b>stack</b>      — Full-stack engineering toolchain', 'system');
        printLine('  <b>whoami</b>     — Founder Muhammad Ayan Shamim profile', 'system');
        printLine('  <b>contact</b>    — Connect directly with engineering team', 'system');
        printLine('  <b>quote</b>      — Instant engineering proverb or quote', 'system');
        printLine('  <b>clear</b>      — Clear terminal console buffer', 'system');
      },
      cheetah: () => {
        printLine('🚀 <b>Cheetah Logistics Suite</b> (Flagship System)', 'success');
        printLine('  • <b>Starter</b>: Single-origin courier with 7 role portals (PHP/Laravel/MySQL)', 'system');
        printLine('  • <b>Pro</b>: Multi-hub linehaul, route optimization, automated ePOD', 'system');
        printLine('  • <b>SaaS</b>: Multi-tenant cloud with subscription billing & custom subdomains', 'system');
        printLine('  • <b>Driver App</b>: Flutter Android/iOS with offline sync & barcode scanning', 'system');
        printLine('→ Demo: <a href="https://cheetah.ayan24.me" target="_blank" style="color:var(--accent);">https://cheetah.ayan24.me</a>', 'accent');
      },
      projects: () => {
        printLine('📦 <b>Shipped Portfolio Highlights:</b>', 'accent');
        printLine('  1. Cheetah Enterprise (Courier/WMS SaaS)', 'system');
        printLine('  2. Cheetah Driver & Picker (Flutter)', 'system');
        printLine('  3. VisionLab Cloud IDE (Laravel 11 & Docker)', 'system');
        printLine('  4. ViddMate Creator Browser & Web (Electron & Celery)', 'system');
        printLine('  5. RestroMaster Ordering (QR Table POS & KDS)', 'system');
        printLine('  6. Cheezify Artisanal Store (Karachi Cold-chain)', 'system');
        printLine('  7. SaaSForge Launchpad (React 19 & Next.js)', 'system');
      },
      stack: () => {
        printLine('⚡ <b>Creatsify Core Architecture:</b>', 'accent');
        printLine('  • <b>Backend</b>: PHP 8.2+ MVC, Laravel 11, Python, Node.js', 'system');
        printLine('  • <b>Frontend</b>: React, Next.js, Modern ES6+, Vanilla High-FPS CSS', 'system');
        printLine('  • <b>Mobile</b>: Flutter (Dart) for iOS & Android', 'system');
        printLine('  • <b>Desktop</b>: Electron & Chromium', 'system');
        printLine('  • <b>Database</b>: MySQL, MariaDB, Redis, SQLite', 'system');
        printLine('  • <b>Comms API</b>: Meta WhatsApp Cloud API, Twilio SMS, Stripe', 'system');
      },
      whoami: () => {
        printLine('👤 <b>Muhammad Ayan Shamim</b>', 'success');
        printLine('  Founder & Lead Architect at Creatsify Studio, Karachi.', 'system');
        printLine('  Studies AI in the ACCP Program at Aptech Karachi (Distinction Holder).', 'system');
        printLine('  Builds and ships verified commercial products globally.', 'system');
        printLine('  GitHub: <a href="https://github.com/ayan2424" target="_blank" style="color:var(--accent);">github.com/ayan2424</a>', 'accent');
      },
      contact: () => {
        printLine('📬 Direct Line to Leadership:', 'accent');
        printLine('  Email: <a href="mailto:ayan@creatsify.tech" style="color:#fff;">ayan@creatsify.tech</a>', 'system');
        printLine('  WhatsApp: +92 300 0000000', 'system');
        printLine('Opening chat desk widget...', 'success');
        const chatBtn = document.getElementById('chatLauncherBtn');
        if (chatBtn) chatBtn.click();
      },
      quote: () => {
        const quotes = [
          '"Clean code always looks like it was written by someone who cares." — Robert C. Martin',
          '"Simplicity is prerequisite for reliability." — Edsger W. Dijkstra',
          '"Make it work, make it right, make it fast." — Kent Beck',
          '"Software is a great combination between artistry and engineering." — Bill Gates'
        ];
        printLine(quotes[Math.floor(Math.random() * quotes.length)], 'warn');
      },
      clear: () => {
        termBody.innerHTML = '';
      }
    };

    const executeCmd = (raw) => {
      const cmd = raw.trim().toLowerCase();
      printLine('<span class="term-prompt">ayan@creatsify:~$</span> ' + raw, 'cmd');
      if (cmd) {
        history.push(raw);
        histIndex = history.length;
        if (commands[cmd]) {
          commands[cmd]();
        } else {
          printLine(`Command not found: "${cmd}". Type <b>help</b> for a list of available commands.`, 'warn');
        }
      }
    };

    termInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        const val = termInput.value;
        termInput.value = '';
        executeCmd(val);
      } else if (e.key === 'ArrowUp') {
        if (histIndex > 0) {
          histIndex--;
          termInput.value = history[histIndex] || '';
        }
      } else if (e.key === 'ArrowDown') {
        if (histIndex < history.length - 1) {
          histIndex++;
          termInput.value = history[histIndex] || '';
        } else {
          histIndex = history.length;
          termInput.value = '';
        }
      }
    });

    // Quick Command Buttons
    document.querySelectorAll('.term-quick-btn').forEach((btn) => {
      btn.addEventListener('click', () => {
        const cmd = btn.dataset.cmd;
        if (cmd) {
          executeCmd(cmd);
          termInput.focus();
        }
      });
    });

    // Focus input on clicking anywhere in terminal
    const wrapper = document.querySelector('.terminal-wrapper');
    if (wrapper) {
      wrapper.addEventListener('click', () => termInput.focus());
    }
  };

  // =========================================================================
  // 4. Glass Project Modal Preview
  // =========================================================================
  const initProjectModal = () => {
    let overlay = document.getElementById('projectModalOverlay');
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = 'projectModalOverlay';
      overlay.className = 'cinematic-modal-overlay';
      overlay.innerHTML = `
        <div class="cinematic-modal">
          <button type="button" class="cinematic-modal-close" id="modalCloseBtn" aria-label="Close modal">&times;</button>
          <img class="cinematic-modal-img" id="modalImg" src="" alt="Project Preview">
          <div class="cinematic-modal-content">
            <div class="cinematic-modal-header">
              <div>
                <span class="pill" id="modalBadge"><i></i><span id="modalCategory">SaaS</span></span>
                <h3 id="modalTitle" style="margin-top:10px;font-size:26px;"></h3>
              </div>
            </div>
            <p id="modalDesc" style="color:var(--muted);font-size:15px;line-height:1.7;"></p>
            <div class="cinematic-modal-chips" id="modalChips"></div>
            <div class="cinematic-modal-actions" id="modalActions"></div>
          </div>
        </div>
      `;
      document.body.appendChild(overlay);
    }

    const closeBtn = document.getElementById('modalCloseBtn');
    const closeModal = () => {
      overlay.classList.remove('active');
    };

    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeModal();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && overlay.classList.contains('active')) closeModal();
    });

    // Attach click handler to project cards
    document.addEventListener('click', (e) => {
      const card = e.target.closest('.pj-in');
      // If user clicked directly on an external link or demo link, don't hijack
      if (e.target.closest('a')) return;

      if (card) {
        const title = card.querySelector('h3')?.textContent || 'Project Details';
        const desc = card.querySelector('p')?.textContent || '';
        const badge = card.querySelector('.st')?.textContent || 'Shipped';
        const img = card.closest('.pj')?.querySelector('.pjm img')?.src || '';
        const chips = Array.from(card.querySelectorAll('.chips span')).map((s) => s.textContent);
        const links = Array.from(card.querySelectorAll('.links a')).map((a) => ({
          text: a.textContent.replace('→', '').trim(),
          url: a.getAttribute('href')
        }));

        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalDesc').textContent = desc;
        document.getElementById('modalCategory').textContent = badge;
        document.getElementById('modalImg').src = img;

        const chipsWrap = document.getElementById('modalChips');
        chipsWrap.innerHTML = chips.map((c) => `<span class="fch a" style="position:static;">${c}</span>`).join('');

        const actionsWrap = document.getElementById('modalActions');
        actionsWrap.innerHTML = links.map((l) => `<a href="${l.url}" class="btn p" target="_blank" rel="noopener noreferrer">${l.text} &rarr;</a>`).join('') +
          `<a href="#contact" class="btn modal-inquire-btn" style="cursor:pointer;">Request similar system</a>`;

        const inqBtn = actionsWrap.querySelector('.modal-inquire-btn');
        if (inqBtn) {
          inqBtn.addEventListener('click', () => {
            closeModal();
            const contactSec = document.getElementById('contact');
            if (contactSec) contactSec.scrollIntoView({ behavior: 'smooth' });
          });
        }

        overlay.classList.add('active');
      }
    });
  };

  // =========================================================================
  // 5. Interactive Scope & Cost Calculator
  // =========================================================================
  const initCalculator = () => {
    const calc = document.getElementById('projectCalculator');
    if (!calc) return;

    const platforms = {
      courier: {
        name: 'Courier & Warehouse SaaS (Cheetah Core)',
        baseWeeks: 3,
        baseModules: 5,
        baseTables: 48,
        description: 'Multi-branch logistics platform, live dispatch tracking, merchant portals, ePOD'
      },
      restaurant: {
        name: 'Restaurant Suite (OrderKit Core)',
        baseWeeks: 2,
        baseModules: 4,
        baseTables: 32,
        description: 'QR table ordering, live kitchen display system (KDS), thermal POS, driver dispatch'
      },
      saas: {
        name: 'Custom Web Platform & SaaS Starter',
        baseWeeks: 3,
        baseModules: 4,
        baseTables: 38,
        description: 'Multi-tenant architecture, role-based auth, subscription billing, API endpoints'
      },
      mobile: {
        name: 'Cross-Platform Mobile App (Flutter)',
        baseWeeks: 4,
        baseModules: 5,
        baseTables: 30,
        description: 'Native iOS & Android app, offline caching, push notifications, smooth UI'
      }
    };

    let selectedPlatform = 'courier';

    const platformCards = calc.querySelectorAll('.calc-platform-card');
    const featureItems = calc.querySelectorAll('.calc-feature-item');
    const timelineEl = document.getElementById('calcTimeline');
    const modulesEl = document.getElementById('calcModules');
    const prefillBtn = document.getElementById('calcPrefillBtn');

    const updateCalculations = () => {
      const p = platforms[selectedPlatform];
      let addWeeks = 0;
      let addModules = 0;
      let addTables = 0;
      const selectedFeatures = [];

      featureItems.forEach((item) => {
        const cb = item.querySelector('input[type="checkbox"]');
        if (cb && cb.checked) {
          item.classList.add('checked');
          addWeeks += parseFloat(item.dataset.weeks || 0.5);
          addModules += parseInt(item.dataset.modules || 1, 10);
          addTables += parseInt(item.dataset.tables || 6, 10);
          selectedFeatures.push(item.querySelector('span')?.textContent.trim());
        } else {
          item.classList.remove('checked');
        }
      });

      const totalWeeksMin = Math.round(p.baseWeeks + addWeeks * 0.7);
      const totalWeeksMax = Math.round(p.baseWeeks + addWeeks * 1.3);
      const totalModules = p.baseModules + addModules;
      const totalTables = p.baseTables + addTables;

      if (timelineEl) {
        timelineEl.textContent = `⏱ Estimated Velocity: ${totalWeeksMin} – ${totalWeeksMax} Weeks`;
      }
      if (modulesEl) {
        modulesEl.textContent = `${totalModules} Core Modules • ${totalTables} Normalized DB Tables`;
      }

      return {
        platformName: p.name,
        timeline: `${totalWeeksMin} – ${totalWeeksMax} Weeks`,
        features: selectedFeatures
      };
    };

    platformCards.forEach((card) => {
      card.addEventListener('click', () => {
        platformCards.forEach((c) => c.classList.remove('selected'));
        card.classList.add('selected');
        selectedPlatform = card.dataset.platform;
        updateCalculations();
      });
    });

    featureItems.forEach((item) => {
      item.addEventListener('click', (e) => {
        const cb = item.querySelector('input[type="checkbox"]');
        if (e.target !== cb) {
          cb.checked = !cb.checked;
        }
        updateCalculations();
      });
    });

    if (prefillBtn) {
      prefillBtn.addEventListener('click', () => {
        const spec = updateCalculations();
        const msgField = document.querySelector('textarea[name="message"]');
        const projSelect = document.querySelector('select[name="project_type"]');

        if (projSelect) {
          if (selectedPlatform === 'courier') projSelect.value = 'Courier or warehouse system';
          else if (selectedPlatform === 'restaurant') projSelect.value = 'Restaurant ordering system';
          else if (selectedPlatform === 'mobile') projSelect.value = 'Mobile or desktop app';
          else projSelect.value = 'Web app or SaaS';
        }

        if (msgField) {
          msgField.value = `Hi Creatsify Studio,\n\nI configured a project scope using your online calculator:\n• Platform: ${spec.platformName}\n• Desired Timeline: ${spec.timeline}\n• Required Modules:\n  - ${spec.features.join('\n  - ')}\n\nPlease send us an architectural proposal and quote.`;
        }

        const contactSec = document.getElementById('contact');
        if (contactSec) {
          contactSec.scrollIntoView({ behavior: 'smooth' });
          if (msgField) msgField.focus();
        }
      });
    }

    updateCalculations();
  };

  // Initialize all features once DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      initParticleCanvas();
      initAudioControls();
      initTerminal();
      initProjectModal();
      initCalculator();
    });
  } else {
    initParticleCanvas();
    initAudioControls();
    initTerminal();
    initProjectModal();
    initCalculator();
  }
})();
