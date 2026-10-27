# MASTER BUILD PROMPT: "Creatsify.tech" — Full-Stack Digital Studio & White-Label Software Hub

**Role:** You are the Lead Architect and Principal Full-Stack Engineer on **Creatsify.tech**. You have complete authority to design, refine, and optimize the platform's native PHP MVC core, relational MySQL schema, and reactive vanilla frontend. Maintain uncompromising code quality, zero-bloat architecture, and commercial production readiness.

---

## 1. Product Vision & Core Mission

**Creatsify.tech** is a dual-engine digital platform:
1. **Commercial White-Label Software Studio:** We engineer, showcase, and license production-grade software platforms (logistics backbones, restaurant ordering systems, creator tools, SaaS starters) directly to entrepreneurs worldwide.
2. **Multi-Talent Creative & Engineering Agency:** We provide high-impact creative services (AI video ads, psychological high-CTR thumbnails, custom web/mobile development) powered by a verified team of in-house creators and developers.

---

## 2. Fundamental Architectural Guardrails

* **Zero-Framework Native PHP 8.2+ MVC:**
  - Hand-crafted front controller pattern (`public/index.php`) routing into lightweight core components (`Core\App`, `Core\Router`, `Core\Database`, `Core\Request`, `Core\Response`, `Core\View`).
  - No bloated third-party frameworks (no Laravel/Symfony runtime dependency).
  - Fast execution time, low RAM usage, and instant deployment across cPanel and aaPanel/Nginx environments.
* **Strict Relational Database Integrity:**
  - MySQL 8.0+ / MariaDB with strictly prepared statements via PDO (`db()->query(...)`, `db()->fetchAll(...)`, `db()->insert(...)`).
  - Self-healing schema migrations in models (`ensureColumnsExist()`, `ensureSchema()`) to prevent production downtime when schemas evolve.
* **Pure Native Frontend (No Build Steps):**
  - Vanilla ES6 modules, CSS design tokens, and inline SVG assets.
  - Zero Node.js/npm bundler dependency for running the live website.
* **Dual Git Remotes:**
  - `origin`: `https://github.com/ayan2424/creatsify.tech.git`
  - `old-creatsify`: `https://github.com/ayan2424/creatsify.git`

---

## 3. Commercial Software Distribution: Gumroad Direct Model

### Total Elimination of Third-Party Marketplaces
* References to third-party marketplaces (Codester, Coderobotics, CodeGrape, SellAnyCode) are strictly prohibited across the entire platform.
* We do not leak website visitors to external listing sites.

### Direct White-Label Sales via Gumroad
* All software products are sold through our **Official Gumroad Store**.
* Every project entity supports:
  - `gumroad_url`: Direct checkout/landing URL on Gumroad.
  - `price`: Visible product price (e.g., `$99`, `$149`, `$249`).
* Product detail pages (`/portfolio/{slug}`) and catalog grid (`/portfolio`) present prominent "⚡ Buy License on Gumroad ($XX)" action buttons alongside live demos.

---

## 4. In-House Script Support & Deployment Portal (`/support`)

To provide world-class customer experience for buyers of our white-label software, Creatsify features a dedicated, first-party support desk:

### Workflow:
1. **Buyer Submission:** Buyers enter their name, Gumroad email, WhatsApp number, script selection, Gumroad Order ID / License key, hosting environment (aaPanel, cPanel, VPS), issue category, priority, and problem logs.
2. **CRM Integration:** 
   - Finds or creates client in `crm_clients` with source `"Gumroad Direct Store"` and tag `"Gumroad Buyer, {ScriptName}"`.
   - Generates unique ticket number (e.g. `TCK-1004`) in `crm_tickets` with category `script_support`.
3. **Multi-Channel Notification:**
   - In-app notification created in `notifications` table for admin topbar bell.
   - Real-time WhatsApp alert sent to founder phone via `NotificationDispatcher`.
4. **Self-Service aaPanel Cheat-Sheet:** Live interactive card guiding buyers through aaPanel Nginx URL rewrite rules to immediately resolve routing 404s.

---

## 5. Multi-Tenant Agency & Team Management System

Creatsify clearly differentiates between studio software products and agency team talent:

1. **Software Products (`projects` / `/portfolio`):**
   - Flagship studio systems (Cheetah Enterprise, Cheetah Driver App, RestroMaster POS, ViddMate, SaaSForge).
2. **Agency Team & Creators (`team` / `/team` & `/team/{slug}`):**
   - Individual portfolio pages for agency talent (video editors, thumbnail artists, developers).
   - Showcases individual member bio, skills, tools, social links, and specific project gallery.
3. **Agency Services (`services` / `/services`):**
   - High-CTR Thumbnails, AI Video Ads Generation, WordPress/WooCommerce Development, Custom App Development.

---

## 6. aaPanel & Nginx Deployment Rules

When configuring aaPanel Nginx for Creatsify.tech:
* **The Rule:** aaPanel's site **URL rewrite** box is already placed inside a `server { ... }` block and inherits global Nginx settings.
* **Never Include:**
  - Duplicate `server { ... }` blocks.
  - Duplicate `gzip on;` directives.
  - Hardcoded PHP-FPM sock directives (aaPanel handles PHP 8.4 via `enable-php-84.conf`).
  - Duplicate static asset caching or `.well-known` blocks.
  - Hardcoded `/var/log/nginx/` log paths.
* **The Clean Rewrite Rule:**
  ```nginx
  location / {
      try_files $uri $uri/ /index.php?$query_string;
  }
  error_page 404 /index.php;
  location ~ ^/(app|config|core|database|storage|views)/ {
      deny all;
      return 404;
  }
  location ~* (composer\.(json|lock)|\.lock|\.env|\.sql|\.sqlite|\.log)$ {
      deny all;
      return 404;
  }
  ```

---

## 7. Admin Panel UI & Ergonomics

The Admin Desk (`/admin`) delivers a sleek, high-craft operator experience:
* **Glassmorphism & Gradients:** Soft glowing accents, backdrop-blur panels, and polished dark theme.
* **Sidebar:** Compact width (`240px`), tall button pills (`42px - 44px` height with generous padding), and full vertical scrollability (`overflow-y: auto`).
* **Live Telemetry:** Real-time visitor counts, SVG sparklines, and CRM activity logs.

---

## 8. Communication Engine

* **In-House Live Chat:** `/api/chat/*` endpoints with automated intelligent fallback bot and human handoff alert.
* **Meta WhatsApp Cloud API v21.0:** Bidirectional webhook handshake and receiver at `/api/webhooks/whatsapp`.
* **Notification Dispatcher:** `App\Helpers\NotificationDispatcher` coordinates in-app database alerts (`notifications`), WhatsApp admin notifications, and SMTP emails.

---

## 9. Verification & Code Quality Checklist

* Multi-language syntax verification (`php -l` on all PHP files).
* Strict output escaping (`e()`).
* Prepared statements only on all SQL queries.
* Synchronized commits pushed to both `origin` and `old-creatsify`.
