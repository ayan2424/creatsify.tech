# Creatsify Studio (Creatsify.tech)

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL Version](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Architecture](https://img.shields.io/badge/Architecture-Custom%20MVC%20Engine-8b7bff?style=for-the-badge)](https://creatsify.tech)
[![Frontend](https://img.shields.io/badge/Frontend-Vanilla%20ES6%20%7C%20CSS%20Tokens-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://creatsify.tech)
[![Production](https://img.shields.io/badge/Deploy-aaPanel%20%7C%20Nginx%20%7C%20Apache-009639?style=for-the-badge&logo=nginx&logoColor=white)](https://creatsify.tech)
[![License](https://img.shields.io/badge/License-Commercial%20Studio-ff4d6d?style=for-the-badge)](https://creatsify.tech)

**Creatsify.tech** is an elite, high-velocity digital software studio and agency platform engineered for commercial performance, zero-bloat operation, and direct digital product licensing.

Built from the ground up without heavy third-party framework overhead (zero Laravel/Symfony runtime dependency), Creatsify pairs a handcrafted **PHP 8.2+ MVC engine** with a pure **Vanilla ES6 / CSS Custom Properties** reactive frontend. It executes dynamic requests in **under 25ms**, consumes minimal server memory, and requires **no Node.js build or bundling step** for live production.

---

## Table of Contents

- [Architectural Philosophy](#-architectural-philosophy)
- [Key Features & Capabilities](#-key-features--capabilities)
  - [1. Public Experience & Visual Engine](#1-public-experience--visual-engine)
  - [2. Lumix AI Command Center (`/admin`)](#2-lumix-ai-command-center-admin)
  - [3. Full Agency CRM Suite](#3-full-agency-crm-suite)
  - [4. Commercial Software Sales via Gumroad](#4-commercial-software-sales-via-gumroad)
  - [5. First-Party Script Support Portal (`/support`)](#5-first-party-script-support-portal-support)
  - [6. Omnichannel Communications (Live Chat & WhatsApp)](#6-omnichannel-communications-live-chat--whatsapp)
  - [7. Zero-Trust Security & Hardening](#7-zero-trust-security--hardening)
- [Technology Stack](#-technology-stack)
- [Directory Structure](#-directory-structure)
- [Quick Start & Installation](#-quick-start--installation)
  - [Method 1: 1-Click Graphical Web Installer](#method-1-1-click-graphical-web-installer-recommended)
  - [Method 2: CLI Demo Seeder](#method-2-cli-demo-seeder-local--quick-eval)
  - [Method 3: Manual Deployment](#method-3-manual-deployment)
- [Production Server Configuration](#-production-server-configuration)
  - [aaPanel / Nginx (creatsify.tech)](#aapanel--nginx-creatsifytech)
  - [Apache / XAMPP (Local Subfolder & VirtualHost)](#apache--xampp-local-subfolder--virtualhost)
- [Environment Variables (`.env`)](#-environment-variables-env)
- [Admin Desk & Authentication](#-admin-desk--authentication)
- [Git Remote Synchronization](#-git-remote-synchronization)
- [Founder & Lead Architect](#-founder--lead-architect)

---

## ⚡ Architectural Philosophy

1. **Zero Framework Bloat:** Traditional monolithic frameworks load hundreds of unused files and megabytes of abstractions on every cycle. Creatsify's front-controller core (`Core\App`, `Core\Router`, `Core\Database`, `Core\Session`, `Core\Request`, `Core\Response`, `Core\View`) boots in microseconds with clean, strictly typed PHP 8.2+.
2. **Direct Relational Database Speed:** PDO with strict parameter binding throughout. Raw execution speed, zero ORM query bloat, zero N+1 query leaks, and self-healing schema migrations (`ensureColumnsExist()`).
3. **Pure Native Reactive Frontend:** Native ES6 modules, CSS design tokens, and crisp vector SVG icons. No npm compile, Webpack, Vite, or Node.js runtime is needed on the production server.
4. **Zero Third-Party Marketplace Leakage:** All external marketplace dependencies and links have been eliminated. Commercial software scripts are licensed directly through our official Gumroad checkout, keeping customer traffic and brand equity strictly within Creatsify.

---

## 🌟 Key Features & Capabilities

### 1. Public Experience & Visual Engine
- **Dark Luxury Aesthetic:** Immersive, clean interface featuring subtle glassmorphic panels, dynamic gradient accents, and synchronized Light/Dark theme switching with zero Flash of Unstyled Content (FOUC).
- **Interactive Studio CLI Terminal:** Built-in interactive console on the homepage featuring custom commands (`help`, `cheetah`, `team`, `matrix`, `clear`, `contact`), live system latency telemetry, and clipboard copying.
- **Dynamic Portfolio Showcase:** Filterable showcase across SaaS backbones, mobile apps, POS systems, and tools with tech stack badges, live external demos, and direct Gumroad purchase buttons.
- **Multi-Tenant Agency Team Portfolios:** Dedicated creator portfolio profiles at `/team/{slug}` highlighting individual skills, case studies, roles, and direct social channels.
- **Technical SEO & A11Y Engine:** Valid semantic HTML5, dynamic OpenGraph/Twitter social cards, canonical tag generation, `robots.txt`, and automated dynamic `/sitemap.xml` yielding real-time project and blog URLs.
- **100% Vector SVG Icons:** Complete elimination of emojis across the UI in favor of clean, authentic SVG stroke icons.

### 2. Lumix AI Command Center (`/admin`)
- **Real-Time Studio Telemetry:** Live visitor metrics, SVG sparkline graphs, system load indicators, and automated 10-second background polling for unread inquiries and chat messages.
- **Homepage Section Customizer:** Dynamic section builder to manage hero titles, rotating banners, statistics counters, and marquee badges directly from the administrative desk without touching code.
- **Studio Theme Customizer & Asset Manager:** Primary brand accent color picker with real-time site synchronization, Dark/Light default settings, custom CSS and script injector (Google Analytics, Meta Pixel).
- **Media Library & Asset Manager:** Drag-and-drop file uploader with automated byte-level MIME inspection via PHP `finfo`, SVG script/XSS sanitization, and 1-click clipboard URL copying.
- **Role-Based Access Control & Immutable Audit Logs:** Role hierarchies (`super_admin`, `admin`, `staff`) paired with automated audit trails logging every user action, IP address, user-agent, and affected entity.

### 3. Full Agency CRM Suite
- **Interactive Client Pipeline (`/admin/crm/pipeline`):** Stage-based deal tracker (`Lead` &rarr; `Discovery` &rarr; `Proposal Sent` &rarr; `Contract Signed` &rarr; `Active Client` &rarr; `Retained` &rarr; `Churned`) with cumulative pipeline valuations.
- **Client Management (`/admin/crm/clients`):** Detailed client directories, multi-channel contact profiles, associated tickets, deal history, and activity summaries.
- **Ticket Desk & Helpdesk System (`/admin/crm/tickets`):** Full support ticketing with priority tiers (`Low`, `Medium`, `High`, `Urgent`), categories (`Bug`, `Feature`, `Maintenance`, `Milestone`, `Billing`, `Onboarding`), client and staff reply threads, and internal private notes.
- **CRM Notes & Interaction Logs (`/admin/crm/notes`):** Chronological log of calls, client meetings, deal term discussions, and pinned actionable items.
- **1-Click Inquiry Converter:** Instantly promotes inbound website contact inquiries into registered CRM clients with populated communication channels.

### 4. Commercial Software Sales via Gumroad
- White-label commercial products (Cheetah Courier Enterprise, Cheetah Driver & Picker Flutter Apps, RestroMaster POS, ViddMate, SaaSForge) sold directly through our **Official Gumroad Store**.
- Database schema supports per-project `gumroad_url` and formatted `price` (e.g. `$99`, `$149`, `$249`).
- Prominent **"Buy License on Gumroad ($XX)"** call-to-actions placed alongside live interactive demos.

### 5. First-Party Script Support Portal (`/support`)
- Dedicated self-service and ticket submission desk for buyers of our white-label commercial scripts.
- Automatically connects buyer submissions to `crm_clients` (tagged `Gumroad Direct Store`) and issues tracked `TCK-XXXX` support tickets.
- Features an embedded, interactive aaPanel Nginx URL rewrite diagnostic guide to help buyers fix routing issues on their own hosting within seconds.

### 6. Omnichannel Communications (Live Chat & WhatsApp)
- **Native Intelligent Live Chat (`/api/chat/*` & `/admin/chat`):** First-party client chat engine with automated context-aware AI fallback, keyword intent detection, and instant human operator handoff with audio notifications.
- **Meta WhatsApp Cloud API v21.0 (`/api/webhooks/whatsapp` & `/admin/whatsapp`):** Bidirectional webhook verification and receiver with international and national phone normalization (E.164 standard).
- **Multi-Channel Notification Dispatcher (`NotificationDispatcher`):** Simultaneously pushes critical events (new inquiries, urgent chat handoffs, support tickets) across in-app notification bells, instant WhatsApp admin alerts, and raw-socket SMTP emails.

### 7. Zero-Trust Security & Hardening
- **Universal CSRF Protection:** Robust session-bound Anti-CSRF token generation and validation middleware protecting all admin mutations and public forms.
- **Upload Directory Execution Shield:** `public/assets/uploads/.htaccess` with complete PHP script engine execution denial (`php_flag engine off` and file extension blacklist).
- **SVG Stored XSS Neutralizer:** Strict regex parser stripping `<script>`, `<iframe>`, `onload`, `onerror`, and JavaScript pseudo-protocols from vector uploads.
- **Protected Demo Mode Middleware:** Built-in `DEMO_MODE=true` toggle that permits buyers to test-drive all admin panels while safely rejecting destructive operations (password alterations, setting modifications, record deletions).
- **Secure Cookie Flags:** Automated `HttpOnly`, `SameSite=Lax`, and dynamic HTTPS `Secure` cookie enforcement.
- **Anti-Spam Form Honeypot:** Transparent honeypot traps silently dropping automated spam bots on public contact and inquiry endpoints.

---

## 🛠️ Technology Stack

| Layer | Technologies & Implementations |
|---|---|
| **Core Backend** | PHP 8.2+ (Strictly Typed Object-Oriented MVC Architecture) |
| **Framework Engine** | Handcrafted Native Kernel (`Core\App`, `Router`, `Database`, `Session`, `View`) |
| **Database** | MySQL 8.0+ / MariaDB 10.3+ / SQLite 3 (via PDO Prepared Statements) |
| **Frontend UI** | Vanilla JavaScript (ES6+ Modules), CSS3 Custom Variables, SVG 2.0 |
| **Typography** | *Bricolage Grotesque* (Headings & Display), *DM Sans* (Body & Interface) |
| **Integrations** | Meta WhatsApp Cloud API v21.0, Twilio SMS REST API, Native TLS Socket SMTP |
| **Web Servers** | Nginx (aaPanel / Linux VPS), Apache 2.4+ (mod_rewrite / .htaccess), cPanel |

---

## 📁 Directory Structure

```
Creatsify.tech/
├── app/
│   ├── Controllers/             # Frontend & API Controllers
│   │   ├── AboutController.php
│   │   ├── BlogController.php
│   │   ├── ChatController.php   # Intelligent live chat visitor engine
│   │   ├── ContactController.php # Inquiry & honeypot spam handler
│   │   ├── HomeController.php   # Landing page & section assembly
│   │   ├── PortfolioController.php
│   │   ├── ServicesController.php
│   │   ├── SitemapController.php # Dynamic XML sitemap generator
│   │   ├── SupportController.php # Commercial script support portal
│   │   ├── TeamController.php   # Creator portfolio showcase
│   │   ├── WebhookController.php # Meta WhatsApp webhook receiver
│   │   └── Admin/               # Lumix Command Center Controllers
│   │       ├── AuthController.php # Admin authentication & demo login
│   │       ├── ChatController.php # Live chat operator desk
│   │       ├── CrmController.php  # Clients, tickets, notes, and pipeline
│   │       ├── DashboardController.php # Telemetry & polling engine
│   │       ├── InquiriesController.php
│   │       ├── MediaController.php # Sanitized asset uploader
│   │       ├── PostsController.php
│   │       ├── ProjectsController.php
│   │       ├── SectionsController.php # Homepage section editor
│   │       ├── ServicesController.php
│   │       ├── SettingsController.php # Studio branding & custom code
│   │       ├── SmsController.php
│   │       ├── TeamController.php
│   │       ├── UsersController.php # User management & audit logs
│   │       └── WhatsAppController.php
│   ├── Helpers/
│   │   ├── Mailer.php           # Raw TLS socket SMTP email engine
│   │   ├── NotificationDispatcher.php # Multi-channel alerting coordinator
│   │   └── functions.php        # Global helpers (url, asset, setting, e, csrf)
│   ├── Middleware/
│   │   ├── AnalyticsMiddleware.php # Live visitor telemetry tracking
│   │   ├── Authenticate.php     # Session authorization gate
│   │   ├── CsrfMiddleware.php   # Anti-CSRF verification
│   │   ├── DemoProtectMiddleware.php # Live buyer demo sandbox guard
│   │   └── RoleMiddleware.php   # Super Admin / Admin role access control
│   └── Models/                  # Strict PDO Data Models
│       ├── AuditLog.php
│       ├── ChatMessage.php
│       ├── ChatSession.php
│       ├── CrmClient.php
│       ├── CrmNote.php
│       ├── CrmTicket.php
│       ├── Inquiry.php
│       ├── Media.php
│       ├── MemberPortfolio.php
│       ├── Notification.php
│       ├── Post.php
│       ├── Project.php
│       ├── Role.php
│       ├── Section.php
│       ├── Service.php
│       ├── Setting.php
│       ├── TeamMember.php
│       └── User.php
├── config/
│   ├── app.php                  # Environment, debug, and locale configuration
│   ├── database.php             # MySQL & SQLite connection parameters
│   ├── mail.php                 # SMTP transmission settings
│   └── routes.php               # High-velocity route registration table
├── core/                        # Zero-Dependency MVC Engine Kernel
│   ├── App.php                  # Application singleton & service container
│   ├── Database.php             # PDO abstraction with transaction support
│   ├── MiddlewarePipeline.php   # Onion-style request middleware pipeline
│   ├── Request.php              # HTTP request representation & payload parser
│   ├── Response.php             # HTTP response & JSON builder
│   ├── Router.php               # Fast regex route dispatcher & group stack
│   ├── Session.php              # Secure session & flash message manager
│   └── View.php                 # Lightweight layout & template view engine
├── database/
│   ├── schema.sql               # Production database DDL schema
│   └── seeds.sql                # Default studio seed data & sample catalog
├── public/                      # Webroot / DocumentRoot
│   ├── .htaccess                # Apache rewrite, GZIP, and security headers
│   ├── index.php                # Universal front controller
│   ├── install.php              # Gateway to web installation wizard
│   ├── robots.txt               # Search engine crawl directives
│   └── assets/
│       ├── brand/               # Logos, wordmarks, and studio emblems
│       ├── css/                 # Modern design tokens, chat, and admin styles
│       ├── js/                  # ES6 modules, CLI terminal, and live chat
│       └── uploads/             # Managed media files (script-execution blocked)
├── storage/                     # Runtime storage (0755 permissions required)
│   ├── cache/
│   ├── logs/
│   └── installed.lock           # Created upon successful installation
├── views/                       # Template Views
│   ├── about/
│   ├── admin/                   # Lumix Command Center views & CRM desk
│   ├── blog/
│   ├── contact/
│   ├── errors/                  # Custom 403, 404, and 500 error pages
│   ├── home/                    # Modern landing page & CLI terminal
│   ├── layouts/                 # Master layouts (admin.php, main.php)
│   ├── partials/                # Navigation, footer, and chat widget
│   ├── portfolio/               # Work showcase & case studies
│   ├── services/
│   ├── support/                 # Buyer script support desk
│   └── team/                    # Agency talent & creator profiles
├── .env.example                 # Environment configuration template
├── .htaccess                    # Root redirection & dotfile blocker
├── aapanel_url_rewrite.conf     # Verified Nginx rewrite rules for aaPanel
├── install.php                  # 4-Step graphical setup wizard
└── seed-demo.php                # CLI-only local database seeder
```

---

## 🚀 Quick Start & Installation

### Method 1: 1-Click Graphical Web Installer (Recommended)

1. Upload the project files to your web server and point your domain or virtual host's **DocumentRoot** to the `/public` directory.
2. Ensure the `storage/` directory and `public/assets/uploads/` are writable (`chmod -R 755 storage public/assets/uploads`).
3. Open your browser and navigate to:
   ```
   https://yourdomain.com/install.php
   ```
4. Follow the intuitive 4-step wizard:
   - **Step 1:** Automatic pre-flight environment checks (PHP 8.2+, PDO, cURL, OpenSSL, mbstring, Fileinfo, GD).
   - **Step 2:** Database credential validation (tests connection and automatically creates the database if privileges allow).
   - **Step 3:** Super Administrator setup, website title, and primary contact parameters.
   - **Step 4:** Automatic table migration, initial seed data population, and lock file generation.

---

### Method 2: CLI Demo Seeder (Local / Quick Eval)

To run Creatsify instantly on a local machine using the built-in PHP development server:

1. Clone the repository and enter the directory:
   ```bash
   git clone https://github.com/ayan2424/creatsify.tech.git
   cd creatsify.tech
   ```

2. Execute the CLI seeder to initialize the environment and database:
   ```bash
   php seed-demo.php
   ```

3. Launch the native PHP server pointing to the `public/` webroot:
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

4. Open `http://127.0.0.1:8000` in your browser. Access the Admin Desk at `http://127.0.0.1:8000/admin`.

---

### Method 3: Manual Deployment

1. Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```
2. Import `database/schema.sql` followed by `database/seeds.sql` into your MySQL/MariaDB database:
   ```bash
   mysql -u your_user -p your_database < database/schema.sql
   mysql -u your_user -p your_database < database/seeds.sql
   ```
3. Update your database credentials and `APP_URL` inside `.env`.
4. Create the lock file to finalize setup:
   ```bash
   touch storage/installed.lock
   ```

---

## 🌐 Production Server Configuration

### aaPanel / Nginx (`creatsify.tech`)

When deploying on **aaPanel** or standard **Nginx**:

1. In aaPanel, navigate to **Websites &rarr; creatsify.tech &rarr; Site Directory**.
2. Set **Running directory (Document Root)** to **`/public`** and save.
3. Open the **URL rewrite** tab and paste the exact configuration from [`aapanel_url_rewrite.conf`](file:///c:/Users/ayans/OneDrive/Documents/A_Projects/Creatsify.tech/aapanel_url_rewrite.conf):

```nginx
# Security Headers
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Permissions-Policy "camera=(), microphone=(), geolocation=()" always;

# Front Controller Pattern - Route all dynamic requests to index.php
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

# Custom Error Handling
error_page 404 /index.php;

# Protect sensitive internal application directories
location ~ ^/(app|config|core|database|storage|views)/ {
    deny all;
    return 404;
}

# Protect sensitive deployment files
location ~* (composer\.(json|lock)|\.lock|\.env|\.sql|\.sqlite|\.log)$ {
    deny all;
    return 404;
}
```

> **Important aaPanel Notice:** Never include outer `server { ... }` blocks, duplicate `gzip on;` directives, or hardcoded PHP-FPM socket listeners inside the aaPanel URL rewrite box, as aaPanel includes this file directly within its managed server block.

---

### Apache / XAMPP (Local Subfolder & VirtualHost)

Creatsify includes native path resolution (`app_base_path()`) that automatically supports running from both root domains and subfolders (such as `http://localhost/creatsify.tech` or `http://localhost/creatsify.tech/public`).

1. Ensure Apache `mod_rewrite` is enabled.
2. Both the root `.htaccess` and `public/.htaccess` are pre-configured to automatically forward requests to the front controller without modifying code.

---

## ⚙️ Environment Variables (`.env`)

```env
APP_NAME="Creatsify Studio"
APP_ENV=production
APP_DEBUG=false
APP_URL="https://creatsify.tech"
APP_TIMEZONE="UTC"

# Live Buyer Demo Protection (true disables destructive admin changes)
DEMO_MODE=false

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=creatsify_db
DB_USERNAME=creatsify_user
DB_PASSWORD="your_secure_password"

# SMTP Mail Delivery
SMTP_HOST=smtp.mailgun.org
SMTP_PORT=587
SMTP_USER=postmaster@yourdomain.com
SMTP_PASS=your_smtp_password
SMTP_ENCRYPTION=tls
SMTP_FROM_EMAIL=noreply@creatsify.tech
SMTP_FROM_NAME="Creatsify Studio"

# Meta WhatsApp Cloud API (v21.0)
WA_API_URL="https://graph.facebook.com/v21.0"
WA_PHONE_NUMBER_ID=""
WA_ACCESS_TOKEN=""
WA_WEBHOOK_VERIFY_TOKEN=""
WA_ADMIN_PHONE=""
```

---

## 🔐 Admin Desk & Authentication

- **Admin URL:** `https://yourdomain.com/admin` (or `/admin/login`)
- **Default Super Admin:** Configured during installation via `install.php` (or loaded via `database/seeds.sql`).
- **1-Click Demo Evaluation:** When evaluating locally or when `DEMO_MODE=true` is enabled, the login screen provides an instant 1-click login button to explore the command center without entering credentials.

---

## 🔄 Git Remote Synchronization

Creatsify maintains two synchronized remote repositories. All commits are mirrored to both remotes:

```bash
# Push to primary production remote
git push origin main

# Push to secondary mirror remote
git push old-creatsify main

# Synchronize both in one command:
git push origin main && git push old-creatsify main
```

- **Primary Repository (`origin`):** `https://github.com/ayan2424/creatsify.tech.git`
- **Secondary Mirror (`old-creatsify`):** `https://github.com/ayan2424/creatsify.git`

---

## 👨‍💻 Founder & Lead Architect

**Muhammad Ayan Shamim**  
*Lead Architect & AI Software Engineer*  
Founder of **Creatsify Studio**

- **Website:** [creatsify.tech](https://creatsify.tech)
- **Direct Email:** [ayan@creatsify.tech](mailto:ayan@creatsify.tech)
- **GitHub:** [@ayan2424](https://github.com/ayan2424)
- **LinkedIn:** [muhammadayanshamim](https://linkedin.com/in/muhammadayanshamim)

---

<p align="center">
  <sub>&copy; 2026 Creatsify Studio. Engineered with pride for commercial scale, direct performance, and uncompromising craft.</sub>
</p>
