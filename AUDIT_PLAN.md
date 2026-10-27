# MASTER AUDIT SPECIFICATION & TASK PLAN: CREATSIFY.TECH
> **Status:** All Subagents Paused / Standby Mode  
> **Execution Strategy:** 5-by-5 Sequential Batches (to ensure zero quota limits, 100% precision, and comprehensive coverage)

---

## BATCH 1: Core Engine, Routing, Data & Security (5 Subagents)

### 1. Core Framework Auditor
* **Target Files:** `core/App.php`, `core/Router.php`, `core/Request.php`, `core/Response.php`, `core/Database.php`, `core/Session.php`, `core/View.php`, `core/MiddlewarePipeline.php`, `app/Helpers/functions.php`.
* **Inspection Scope:**
  - Route pattern matching (regex parameter extraction for `{slug}`, `{id}`).
  - Method override handling (`_method` parameter for PUT/PATCH/DELETE).
  - Middleware pipeline onion execution and alias resolution (`auth`, `role`, `csrf`, `analytics`, `demo`).
  - Session lifecycle: flash message persistence, token generation, lifetime settings.
  - View layout resolution and variable scope isolation.
  - Error and exception handling (production vs debug modes).

### 2. Controllers & Endpoints Auditor
* **Target Files:**
  - Public Controllers: `HomeController`, `PortfolioController`, `ServicesController`, `TeamController`, `AboutController`, `BlogController`, `ContactController`, `SupportController`, `ChatController`, `WebhookController`, `SitemapController`.
  - Admin Controllers: `AuthController`, `DashboardController`, `ProjectsController`, `ServicesController`, `TeamController`, `AgencyProjectsController`, `SectionsController`, `PostsController`, `InquiriesController`, `CrmController`, `UsersController`, `MediaController`, `SettingsController`, `ChatController`, `WhatsAppController`, `SmsController`.
* **Inspection Scope:**
  - Verify every route in `config/routes.php` maps to an existing, callable method.
  - Identify dead code (controller methods with no active routes).
  - Ensure all POST/PUT/DELETE handlers perform strict input validation and type sanitization.
  - Check return types (must return `Core\Response`).
  - Verify flash messages and redirect URLs use the `url()` helper.

### 3. Database Schema & Relational Integrity Auditor
* **Target Files:** `database/schema.sql`, all SQL migrations, database connection config.
* **Inspection Scope:**
  - Compare every table and column in `database/schema.sql` against queries in models and controllers.
  - Verify all foreign key dependencies and indexing on frequently queried columns (`slug`, `status`, `client_id`, `ticket_number`).
  - Check column data types: ensure ENUM types are not restrictive (e.g. `crm_tickets.category` set to VARCHAR(60)).
  - Verify self-healing schema checks (`ensureColumnsExist()`, `ensureSchema()`, `ensureTableExists()`) across models.

### 4. Models & Data Access Layer Auditor
* **Target Files:** All 22 models in `app/Models/` (`Project`, `AgencyProject`, `TeamMember`, `MemberPortfolio`, `Service`, `Post`, `Inquiry`, `CrmClient`, `CrmTicket`, `CrmNote`, `User`, `Role`, `Section`, `Setting`, `Notification`, `Media`, `AuditLog`, `ChatSession`, `ChatMessage`, `WhatsAppMessage`, `SmsMessage`, `AnalyticsVisit`).
* **Inspection Scope:**
  - Guarantee 100% prepared statements via PDO (`db()->query(...)`, `db()->fetchAll(...)`, `db()->insert(...)`).
  - Verify zero calls to undefined methods (such as `db()->execute(...)`).
  - Check null safety on nullable fields (`company`, `phone`, `whatsapp`, `notes_summary`, `due_date`).
  - Ensure consistent return types (arrays vs null).

### 5. Security, Authentication & Penetration Auditor
* **Target Files:** `app/Controllers/Admin/AuthController.php`, `core/Session.php`, `app/Middleware/Authenticate.php`, `app/Middleware/RoleMiddleware.php`, `app/Middleware/CsrfMiddleware.php`.
* **Inspection Scope:**
  - Timing attack prevention on token and password comparisons (`hash_equals()`).
  - Password hashing security (`password_hash(..., PASSWORD_DEFAULT)`).
  - CSRF verification on all state-changing endpoints, including AJAX/JSON requests (`X-CSRF-Token` header).
  - IDOR vulnerabilities: verify user permissions before reading, updating, or deleting CRM records, tickets, or media.
  - Sensitive file protection: verify web access denial for `.env`, `.sql`, `.log`, `.git`.

---

## BATCH 2: Views, Frontend Code & Client Experience (5 Subagents)

### 6. Views & Template Rendering Auditor
* **Target Files:** All public views (`views/home/`, `views/portfolio/`, `views/services/`, `views/team/`, `views/about/`, `views/blog/`, `views/contact/`, `views/support/`, `views/partials/`, `views/layouts/`, `views/errors/`) and all admin views (`views/admin/**`).
* **Inspection Scope:**
  - Output escaping: verify every dynamic variable is wrapped in `e()` or `htmlspecialchars()` to prevent XSS.
  - CSRF fields: verify every `<form method="POST">` includes `<?= csrf_field() ?>`.
  - Anti-spam honeypot: verify public forms include the hidden `_hp_check` field.
  - Safe variable access: verify null-coalescing operators (`?? ''`) are used for optional view data.
  - Layout inheritance: verify `$this->layout(...)` declarations.

### 7. CSS & Design Tokens Auditor
* **Target Files:** All CSS stylesheets in `public/assets/css/` and embedded `<style>` blocks in views.
* **Inspection Scope:**
  - Design token integrity: consistency of `:root` variables (`--bg`, `--surface`, `--line`, `--text`, `--accent`, `--muted`).
  - Dark / Light theme switching: ensure text remains readable with high contrast across both modes.
  - Mobile layout overflow: prevent horizontal scrollbugs (`overflow-x: hidden`, no fixed-pixel widths > 100vw).
  - Flexbox and CSS Grid constraints: check for collapsed containers and z-index stacking conflicts.
  - Admin sidebar ergonomics: width 240px, button height 42-44px, smooth vertical scrollbar (`overflow-y: auto`).

### 8. JavaScript Logic & Interactive Controls Auditor
* **Target Files:** `public/assets/js/main.js` and all inline `<script>` tags across all views.
* **Inspection Scope:**
  - DOM query guards: ensure elements are checked for null before attaching event listeners or modifying properties.
  - Form submission handlers: check CSRF token transmission, button disabling during submission, error toast handling.
  - Theme and sound toggles: check state synchronization with `localStorage`.
  - Chat widget logic: polling intervals, message rendering, unhandled promise rejections.
  - Elimination of all legacy third-party marketplace references in JS fallback arrays.

### 9. Accessibility (a11y) & WCAG 2.1 AA Auditor
* **Target Files:** All view templates across the application.
* **Inspection Scope:**
  - Semantic HTML landmarks: `<header>`, `<nav>`, `<main>`, `<section>`, `<footer>`, `<aside>`.
  - Heading hierarchy: single `<h1>` per page, followed logically by `<h2>` and `<h3>` without skipping levels.
  - Form accessibility: every `<input>`, `<select>`, `<textarea>` must have an associated `<label for="id">` or `aria-label`.
  - Icon buttons: ensure theme toggle, sound toggle, mobile menu button have descriptive `aria-label` or `title`.
  - Color contrast: verify 4.5:1 ratio for standard text and 3:1 for large text across light and dark themes.

### 10. Mobile Responsiveness & Viewport Auditor
* **Target Files:** Public layouts, admin desk, navigation drawer, modals, data tables.
* **Inspection Scope:**
  - Breakpoint behavior: Small phone (360px), large phone (430px), tablet (768px/1024px), laptop/desktop (1280px+).
  - Touch tap targets: minimum 44x44px for buttons, pills, and links.
  - Table responsiveness: horizontal scrolling wrappers for CRM tables, inquiries, and user lists on small screens.
  - Mobile drawer navigation: open/close animations, backdrop dismissal, keyboard escape handling.

---

## BATCH 3: Agency Business Logic, Integrations & Server Deployment (5 Subagents)

### 11. CRM, Inquiries & Support Ticket Workflow Auditor
* **Target Files:** `app/Controllers/Admin/InquiriesController.php`, `app/Controllers/Admin/CrmController.php`, `app/Controllers/SupportController.php`, `app/Models/CrmClient.php`, `app/Models/CrmTicket.php`, `app/Models/CrmNote.php`.
* **Inspection Scope:**
  - Inquiry-to-client conversion: verify proper mapping of contact details, budget, project type, and stage.
  - Support ticket workflow: Gumroad order validation, automated ticket number generation (`TCK-XXXX`), priority levels.
  - Admin reply thread: verify email/WhatsApp notification dispatch when admin replies to a ticket.
  - Status transitions: open -> in_progress -> waiting_client -> resolved -> closed (verify `resolved_at` timestamp).
  - CRM note management: pinning notes, setting follow-up dates, activity logging.

### 12. Commercial Software & Agency Talent Portfolios Auditor
* **Target Files:** `app/Controllers/PortfolioController.php`, `app/Controllers/TeamController.php`, `app/Controllers/Admin/ProjectsController.php`, `app/Controllers/Admin/TeamController.php`.
* **Inspection Scope:**
  - Studio software products: Gumroad direct checkout integration (`gumroad_url`, `price`), live demo links, case study details.
  - Agency creators portfolio: individual member profiles (`/team/{slug}`), personal showcase galleries, skills, tools, and social links.
  - Complete elimination of external marketplaces: verify zero references to Codester, Coderobotics, CodeGrape, SellAnyCode.

### 13. Omnichannel Messaging & Notification Engine Auditor
* **Target Files:** `app/Controllers/ChatController.php`, `app/Controllers/Admin/ChatController.php`, `app/Controllers/WebhookController.php`, `app/Helpers/NotificationDispatcher.php`, `app/Helpers/WhatsAppHelper.php`, `app/Helpers/SmsHelper.php`, `app/Helpers/Mailer.php`.
* **Inspection Scope:**
  - First-party Live Chat: session generation, smart local rule engine, human handoff mode, message polling.
  - Meta WhatsApp Cloud API v21.0: webhook verification handshake (`hub.challenge`), incoming message receipt and CRM client linking.
  - Notification Dispatcher: coordination of in-app database alerts (`notifications` table), WhatsApp alerts to founder, SMTP emails.
  - Graceful degradation: ensure failure of third-party APIs (WhatsApp/SMS/SMTP) does not break user requests.

### 14. Media Manager, File Uploads & Asset Security Auditor
* **Target Files:** `app/Controllers/Admin/MediaController.php`, `public/assets/uploads/.htaccess`, `app/Models/Media.php`.
* **Inspection Scope:**
  - Upload security: strict MIME-type validation, file extension whitelisting, double-extension blocking (`.php.png`).
  - Execution prevention: verify `public/assets/uploads/.htaccess` disables PHP script execution.
  - Physical file existence: verify all assets referenced via `asset(...)` exist in `public/` or `public/assets/`.

### 15. Server Deployment, Nginx/aaPanel & Apache Portability Auditor
* **Target Files:** `aapanel_url_rewrite.conf`, `nginx.conf`, `.htaccess`, `public/.htaccess`, `install.php`, `seed-demo.php`, `app/Middleware/DemoProtectMiddleware.php`.
* **Inspection Scope:**
  - aaPanel URL rewrite rules: strict front controller pattern, no outer `server {}`, no duplicate `gzip`, no duplicate PHP-FPM sock.
  - Standalone Nginx configuration: security headers, SSL parameters, fastcgi buffers.
  - Apache `.htaccess`: `mod_rewrite` rules and strict directory protection for `/app`, `/config`, `/core`, `/database`, `/storage`, `/views`.
  - Subdirectory hosting portability: verify `url()` and `asset()` helpers work seamlessly on `localhost/creatsify` and `creatsify.tech`.
  - Installer & demo protection: `install.php` lifecycle, `storage/installed.lock` enforcement, demo mode write protection.
