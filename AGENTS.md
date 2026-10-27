# AGENTS.md — Operating Manual for AI Coding Agents on Creatsify.tech

> **Primary Instruction:** This is the tool-agnostic operating standard for all AI coding agents (Antigravity, Claude Code, Cursor, Codex, Windsurf, Aider) working on the **Creatsify.tech** platform. Read this file in full before proposing or executing any code modifications.

**Relationship to Master Build Specifications:**
* **`prompts.md`**: The master build prompt, product vision, business rules, and technical specification.
* **`AGENTS.md` (this file)**: Working conventions, aaPanel/Nginx configuration standards, self-healing database requirements, UI ergonomics, and mandatory verification procedures.

---

## 1. System Identity & Architecture

Creatsify.tech is an elite full-stack digital software studio and agency platform engineered for direct commercial performance.

* **Backend Core:** PHP 8.2+ (strictly typed, zero third-party framework overhead like Laravel or Symfony). Handcrafted, high-velocity MVC engine (`public/index.php` -> `Core\App` -> `Core\Router` -> `Core\Database` -> Controllers -> Models -> Views).
* **Database Layer:** MySQL 8.0+ / MariaDB via PDO with prepared statements exclusively. Direct execution speed, zero ORM query bloat.
* **Frontend Layer:** Vanilla ES6 modules, native CSS custom properties / design tokens, and SVG icons. **No node/npm build step required for production deployment.**
* **Dual Remote Repositories:**
  - `origin`: `https://github.com/ayan2424/creatsify.tech.git` (Primary)
  - `old-creatsify`: `https://github.com/ayan2424/creatsify.git` (Secondary mirror)
  - All commits must be kept in sync and pushed to both remotes.

---

## 2. Commercial Strategy & Third-Party Marketplaces Rule

> [!IMPORTANT]
> **Zero Third-Party Marketplace Leakage (Codester, Coderobotics, CodeGrape, SellAnyCode):**
> All references, links, mentions, and badges pointing to external marketplaces have been completely eliminated from the codebase. Do NOT introduce or re-add any external marketplace links.

### Commercial Software Sales via Gumroad:
* All white-label commercial software scripts (Cheetah Courier Enterprise, Cheetah Driver & Picker Flutter App, RestroMaster POS, ViddMate, SaaSForge) are sold directly through our **Official Gumroad Store**.
* The `projects` database table supports `gumroad_url` and `price` (e.g. `$99`).
* Admin panel allows setting Gumroad URLs and prices per project at `/admin/projects`.
* Public project cards on `/portfolio` and case studies on `/portfolio/{slug}` feature direct **"Buy License on Gumroad ($XX)"** buttons alongside live demos.

---

## 3. In-House Script Support Portal (`/support`)

Creatsify provides direct, first-party technical support for buyers of our Gumroad white-label scripts:

* **Endpoint:** `GET /support` & `POST /support` (and `POST /api/support`).
* **Controller:** `App\Controllers\SupportController`
* **View:** `views/support/index.php`
* **Automated Agency CRM Integration:**
  1. Locates or creates client in `crm_clients` (source set to `"Gumroad Direct Store"`).
  2. Generates ticket number (`TCK-XXXX`) and inserts into `crm_tickets` with category `script_support`, priority, license/order verification, and server diagnostics.
  3. Dispatches notifications across channels:
     - In-App database notification in `notifications` table.
     - Instant real-time WhatsApp alert to the founder's phone via `NotificationDispatcher`.
* **Self-Service aaPanel Helpdesk:** The support portal embeds direct troubleshooting guidance for aaPanel Nginx URL rewrites and common PHP extension configurations.

---

## 4. Multi-Tenant Agency & Team Management System

Creatsify operates as both a software studio and a multi-creator agency. Maintain strict separation between:

1. **Studio Core Projects (`projects` table / `/portfolio`):**
   - Commercial SaaS platforms, courier backbones, and white-label scripts.
2. **Agency Creators & Team Portfolios (`team` table / `/team` & `/team/{slug}`):**
   - Individual portfolios for workers/team members (e.g., thumbnail designers, AI video specialists, frontend engineers).
   - Managed via `/admin/team` with bio, skills, roles, avatar, social channels, and dedicated showcase items.
3. **Agency Services (`services` table / `/services`):**
   - Categorized capabilities (AI Video Ads, High-CTR Thumbnails, WordPress/WooCommerce, Custom PHP/Flutter development).

---

## 5. Server Configuration & aaPanel / Nginx Standards

> [!CAUTION]
> **CRITICAL aaPanel Nginx URL Rewrite Rules:**
> When configuring aaPanel's site **URL rewrite** box, remember that aaPanel automatically places the box contents INSIDE an existing `server { ... }` block and inherits global Nginx settings.

### What is STRICTLY FORBIDDEN in aaPanel URL Rewrite:
1. **NO outer `server { ... }` block** or `listen`/`server_name`/`root`/`index` directives (causes `"server" directive is not allowed here` crash).
2. **NO `gzip on;`** (causes duplicate directive error).
3. **NO PHP-FPM blocks (`location ~ \.php$`)** with hardcoded `php8.2-fpm.sock` (server runs PHP 8.4 via aaPanel's internal `enable-php-84.conf`).
4. **NO duplicate static asset caching** (`location ~* \.(jpg|css|js)...`) or `.well-known` blocks.
5. **NO duplicate access/error log paths**.

### What MUST be in aaPanel URL Rewrite:
```nginx
# Front Controller Pattern - Route all dynamic requests to index.php
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

# Custom Error Pages
error_page 404 /index.php;

# Protect internal application directories
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

---

## 6. Admin Panel UI & Ergonomics Guidelines

The admin desk (`/admin`) follows a modern, high-craft dark aesthetic:

* **Visual Design:** Dark glassmorphism (`background: var(--adm-surface)`, `backdrop-filter: blur(12px)`), soft indigo/purple gradients, and subtle micro-animations (`transition: all 0.25s cubic-bezier(...)`).
* **Sidebar Specifications:**
  - Slim width: `240px` (avoiding excessive screen real estate).
  - Taller clickable button pills: `42px - 44px` height with `8px - 10px` vertical padding for effortless mouse and touch interaction.
  - Full vertical scrollability: `overflow-y: auto`, smooth scrollbars (`::-webkit-scrollbar`), avoiding any cramped or cut-off navigation items.
* **Metric Cards & Telemetry:** Integrated SVG sparklines, live badges, and real-time visitor telemetry.

---

## 7. Self-Healing Database Migrations

To prevent production server downtime on aaPanel/XAMPP:

* All model enhancements that rely on new columns or altered types MUST include self-healing schema checks:
  ```php
  // Example from App\Models\Project
  public static function ensureColumnsExist(): void
  {
      static $checked = false;
      if ($checked) return;
      $checked = true;
      try {
          db()->query("SELECT `gumroad_url`, `price` FROM `projects` LIMIT 1");
      } catch (\Throwable $e) {
          try {
              db()->query("ALTER TABLE `projects` 
                  ADD COLUMN `gumroad_url` VARCHAR(255) NULL AFTER `live_url`, 
                  ADD COLUMN `price` VARCHAR(50) NULL DEFAULT '$99' AFTER `gumroad_url`");
          } catch (\Throwable $e2) {}
      }
  }
  ```
* Always use `db()->query(...)` (not `execute()`, which does not exist in `Core\Database`).

---

## 8. Omnichannel Communication System

* **In-House Live Chat:** `/api/chat/*` endpoints with automated intelligent fallback bot and human handoff alert.
* **WhatsApp Cloud API v21.0:** Bidirectional webhook handshake and receiver at `/api/webhooks/whatsapp`.
* **Multi-Channel Dispatcher:** `App\Helpers\NotificationDispatcher` coordinates in-app database alerts (`notifications`), WhatsApp admin notifications, and SMTP emails.

---

## 9. Security & Hard Guardrails

1. **Prepared Statements Only:** Never concatenate unescaped input into SQL strings. Always use PDO parameter binding.
2. **Output Escaping:** Always wrap dynamic output in `e(...)` or `htmlspecialchars(...)`.
3. **CSRF & Honeypots:** Every public state-changing form must include `csrf_field()` and the hidden `_hp_check` honeypot.
4. **URL & Asset Generation:** Always use `url('/path')` and `asset('path')` helpers to ensure full portability across subdirectory hosting (e.g. `localhost/creatsify`) and root domain hosting (`creatsify.tech`).
5. **No Blind Scaffolding:** Do not create unused folders, dummy stubs, or speculative files. Build what is needed and verify it end-to-end.

---

## 10. Pre-Flight Verification Checklist

Before marking any task complete:
1. `php -l <file>` on all touched and created PHP files (0 syntax errors).
2. Verify asset paths visually or via HTTP request check.
3. Test route resolution and database operations end-to-end.
4. Commit with descriptive semantic commit messages (`feat:`, `fix:`, `refactor:`, `chore:`).
5. Push to both remotes: `git push origin main; git push old-creatsify main`.
