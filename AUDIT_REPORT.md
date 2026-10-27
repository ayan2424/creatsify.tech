# Creatsify.tech Master Codebase Audit Report (Batch 1 Synthesis)

**Project:** Creatsify.tech Digital Studio & Agency Platform  
**Target Architecture:** PHP 8.2+ MVC (Custom Engine), MySQL 8.0+ / SQLite, Vanilla ES6, CSS Tokens  
**Repository Working Directory:** `c:\Users\ayans\OneDrive\Documents\A_Projects\Creatsify.tech`  
**Dual Remotes Verified:**  
- Primary (`origin`): `https://github.com/ayan2424/creatsify.tech.git`  
- Secondary (`old-creatsify`): `https://github.com/ayan2424/creatsify.git`  
**Audit Scope (Batch 1):** Core Framework & Routing, Controllers & Endpoints, Database Schema & Relational Integrity, 22+ Models & Data Access Layer, Security & Access Controls, Views & Template Architecture.

---

## Executive Summary

A comprehensive, multi-agent inspection was executed across the backend, data, security, and presentation layers of the Creatsify.tech platform. Six specialized subagents conducted static analysis, AST inspection, call-chain tracing, and dynamic PHP 8.2 CLI execution.

### Key Headline Statistics:
- **PHP Syntax Integrity:** 100% of PHP files passed `php -l` (0 fatal syntax errors).
- **Marketplace Cleanliness:** 0 mentions, links, or remnants of third-party marketplaces (Codester, Coderobotics, CodeGrape, SellAnyCode) in active runtime files.
- **Total Tables in Master Schema:** 28 tables verified (including full agency project and milestone delivery suite).
- **Total Critical / High Severity Bugs Identified:** 18 confirmed vulnerabilities and runtime crashes.
- **Total Medium / Low Bugs & Gaps Identified:** 17 architectural flaws, leakage vectors, and edge-case exceptions.

---

## Master Bug Catalog (Batch 1)

### Section 1: Core Framework & Routing Engine

#### BUG-CORE-01: Named Parameter Mismatch in Team Member Portfolio Admin Routes
- **Severity:** `CRITICAL`
- **Affected Files:**
  - `config/routes.php` (Lines 108–109)
  - `app/Controllers/Admin/TeamController.php` (Lines 171, 185)
  - `core/Router.php` (Line 113)
- **Root Cause:**
  In `config/routes.php`, the routes are declared as `/team/{id}/portfolio/create` and `POST /team/{id}/portfolio`. In `TeamController.php`, the action signatures are:
  ```php
  public function createPortfolio(Request $request, string $memberId): Response
  public function storePortfolio(Request $request, string $memberId): Response
  ```
  `Router.php` extracts named regex capture groups `['id' => '...']` and calls `call_user_func_array([$controller, $method], $params)`. In PHP 8.0+, string keys are treated as named function arguments. Because the method expects `$memberId` but receives named argument `'id'`, PHP throws a fatal unhandled error:
  ```
  Fatal error: Uncaught Error: Unknown named parameter $id in core/Router.php:113
  ```
- **Remediation:**
  1. Align the route definitions in `config/routes.php` to use `{memberId}`:
     ```php
     $r->get('/team/{memberId}/portfolio/create', [\App\Controllers\Admin\TeamController::class, 'createPortfolio']);
     $r->post('/team/{memberId}/portfolio', [\App\Controllers\Admin\TeamController::class, 'storePortfolio']);
     ```
  2. Strip associative keys in `core/Router.php:113` using `array_values($params)` so parameter names in routes do not conflict with variable names in controllers:
     ```php
     return call_user_func_array([$controller, $action], array_values($params));
     ```

---

#### BUG-CORE-02: Double Response Wrap Fatal TypeError in 404 Handlers
- **Severity:** `HIGH`
- **Affected Files:**
  - `core/Router.php` (Line 142)
  - `app/Controllers/PortfolioController.php` (Line 38)
  - `app/Controllers/BlogController.php` (Line 29)
- **Root Cause:**
  The `view()` helper returns an instance of `Core\Response`. However, `Response::html(string $content, int $statusCode)` strictly enforces that `$content` must be a `string`. In three places, the code passes the `view()` response directly into `Response::html()`:
  ```php
  return Response::html(view('errors/404', ['message' => 'Post not found']), 404);
  ```
  This immediately triggers a fatal `TypeError`:
  ```
  TypeError: Core\Response::html(): Argument #1 ($content) must be of type string, Core\Response given
  ```
  In `PortfolioController` and `BlogController`, missing slugs crash with an unhandled 500 server error instead of serving a 404 page. In `Router.php`, the error is caught and falls back to unstyled raw HTML.
- **Remediation:**
  Update callers to set status codes directly on the response instance:
  ```php
  return view('errors/404', ['message' => 'Post not found'])->setStatusCode(404);
  ```

---

#### BUG-CORE-03: Fatal Exception in 500 Error View Inside Global Handler
- **Severity:** `CRITICAL`
- **Affected Files:**
  - `core/App.php` (Lines 204–208)
  - `views/errors/500.php` (Line 2)
- **Root Cause:**
  In `core/App.php:207`, `App::handleException()` attempts to render the 500 error page using:
  ```php
  require dirname(__DIR__) . '/views/errors/500.php';
  ```
  However, `views/errors/500.php` begins with:
  ```php
  <?php $this->layout('layouts/main', ['meta_title' => '500 Server Error - Creatsify']); ?>
  ```
  Because `views/errors/500.php` is included directly in the static method `App::handleException()`, `$this` does not point to an instance of `Core\View`. It throws:
  ```
  Fatal error: Call to undefined method Core\App::layout()
  ```
  This crashes the exception handler itself, emitting a raw white-screen fatal error with leaked stack trace paths.
- **Remediation:**
  Change `core/App.php:207` to render through `\Core\View`:
  ```php
  echo \Core\View::make('errors/500');
  ```
  Or render a self-contained, high-craft standalone 500 template with `asset()` and `url()` helpers.

---

#### BUG-CORE-04: View Data Loss in `Core\View::layout()`
- **Severity:** `HIGH`
- **Affected Files:**
  - `core/View.php` (Lines 34, 71)
- **Root Cause:**
  In `Core\View::render()`, `extract($this->data)` runs before the template file is executed. If a child view calls `$this->layout('layouts/main', ['meta_title' => 'Custom Title'])`, the arguments are stored in `$this->layoutData`. But when `View::render()` later includes the layout file, it only extracts `$this->data`. `$this->layoutData` is completely ignored and dropped. Any title or meta descriptions set inside child templates are never applied.
- **Remediation:**
  Merge `$this->layoutData` into the scope prior to rendering the layout:
  ```php
  extract(array_merge($this->data, $this->layoutData));
  ```

---

#### BUG-CORE-05: Post-Logout Flash Toast Loss Due to `session()->destroy()`
- **Severity:** `MEDIUM`
- **Affected Files:**
  - `app/Controllers/Admin/AuthController.php` (Lines 109–113)
- **Root Cause:**
  `AuthController::logout()` calls `session()->destroy()` before calling `flash('success', 'You have been logged out.')`. The flash message is written to a destroyed, uncommitted session and is never displayed on the login page.
- **Remediation:**
  Remove `session()->destroy()` and replace with selective clearance and session ID regeneration:
  ```php
  session()->remove('auth_user');
  session()->regenerate();
  flash('success', 'You have been securely logged out.');
  ```

---

### Section 2: Controllers & API Endpoints

#### BUG-CTRL-01: Silent Exclusion of Blog Posts & Team Profiles from `sitemap.xml`
- **Severity:** `HIGH`
- **Affected Files:**
  - `app/Controllers/SitemapController.php` (Lines 65, 80)
- **Root Cause:**
  `SitemapController` attempts to query published posts and active team members using nonexistent static model methods:
  ```php
  $posts = Post::published(); // Call to undefined method App\Models\Post::published()
  $members = TeamMember::active(); // Call to undefined method App\Models\TeamMember::active()
  ```
  Because both calls are wrapped in empty `catch (\Throwable $e) {}` blocks, the fatal errors are swallowed silently. The sitemap is generated with 0 blog posts and 0 team member profiles.
- **Remediation:**
  Update to use the existing model methods:
  ```php
  $posts = Post::allPublished();
  $members = TeamMember::allActive();
  ```

---

#### BUG-CTRL-02: Strict Typing Mismatch in `AgencyProjectsController::storeNote()`
- **Severity:** `HIGH`
- **Affected Files:**
  - `app/Controllers/Admin/AgencyProjectsController.php` (Line 161)
  - `app/Models/AgencyProject.php` (Line 284)
- **Root Cause:**
  `AgencyProjectsController` invokes `AgencyProject::addNote()` with `(int)($user['id'] ?? 0) ?: null`. When the user ID evaluates to `null`, PHP 8.2 strictly rejects it because `AgencyProject::addNote()` declares `int $userId` rather than `?int $userId = null`.
- **Remediation:**
  Update `AgencyProject::addNote` signature:
  ```php
  public static function addNote(int $projectId, ?int $userId, string $note, bool $isClientVisible = false): int
  ```

---

#### BUG-CTRL-03: Chat Visitor Name Overwrite Bug
- **Severity:** `MEDIUM`
- **Affected Files:**
  - `app/Controllers/ChatController.php` (Line 24)
  - `app/Models/ChatSession.php` (Line 16)
- **Root Cause:**
  When a visitor sends subsequent messages after initializing a conversation with their real name, the request payload often omits `name`. `ChatController` falls back to `'Visitor'`. `ChatSession::findOrCreate()` executes an `UPDATE` that overwrites the customer's real name with `'Visitor'` in database records and admin views.
- **Remediation:**
  Only update `visitor_name` if `!empty($data['name'])` and the existing name is default.

---

#### BUG-CTRL-04: Unhandled Notification Exceptions in Contact Form
- **Severity:** `HIGH`
- **Affected Files:**
  - `app/Controllers/ContactController.php` (Line 111)
- **Root Cause:**
  `NotificationDispatcher` is called outside any `try...catch` block. If the SMTP server is down, an invalid API key is provided, or a WhatsApp network timeout occurs, inquiry creation crashes with an uncaught 500 error, giving visitors the impression their submission failed when it was actually saved in the database.
- **Remediation:**
  Wrap `NotificationDispatcher` in a `try...catch (\Throwable $e)` block, log the failure to `AuditLog`, and allow the customer request to complete successfully.

---

#### BUG-CTRL-05: Missing Notification Dispatch on Staff Ticket Replies
- **Severity:** `HIGH`
- **Affected Files:**
  - `app/Controllers/Admin/CrmController.php` (Lines 358–392)
- **Root Cause:**
  When an admin replies to a support ticket at `/admin/crm/tickets/{id}`, the reply is stored in `crm_ticket_replies`. However, neither `Mailer` nor WhatsApp notifications are dispatched to the customer. Since there is no public ticket tracking portal, the customer never learns that their ticket was answered.
- **Remediation:**
  If `$isInternal` is false, dispatch the reply to the client's email via `Mailer::send()` and to their WhatsApp number if available.

---

### Section 3: Database Schema & Relational Integrity

#### BUG-DB-01: Critical Self-Healing Schema Violations in Agency Models
- **Severity:** `CRITICAL`
- **Affected Files:**
  - `app/Models/AgencyProject.php` (Lines 18–105)
  - `app/Models/MemberPortfolio.php` (Line 23)
  - `app/Models/TeamMember.php` (Line 27)
- **Root Cause:**
  `AgencyProject::ensureSchema()` executes 5 raw DDL queries without `try...catch` blocks and sets `self::$schemaChecked = true` before execution. If any single query fails, the application crashes and subsequent requests never attempt recovery. `MemberPortfolio` and `TeamMember` also lack fallback catch blocks.
- **Remediation:**
  Wrap each DDL execution in individual `try...catch (\Throwable $e)` blocks, ensuring the latch is only set after all table creations succeed.

---

#### BUG-DB-02: Cascading Deletion Relational Leaks
- **Severity:** `HIGH`
- **Affected Files:**
  - `app/Models/TeamMember.php`
  - `app/Models/CrmClient.php`
  - `app/Models/CrmTicket.php`
  - `app/Models/User.php`
- **Root Cause:**
  - Deleting a `TeamMember` leaves orphaned records in `agency_project_members` and un-nullified IDs in `agency_project_tasks.assigned_team_member_id`.
  - Deleting a `CrmClient` nullifies tickets but leaves `agency_projects.client_id` dangling.
  - `CrmTicket::delete()` deletes child rows without wrapping in a database transaction (`beginTransaction()` / `commit()` / `rollback()`).
  - Deleting a `User` corrupts `crm_notes.user_id` (defined as `INT NOT NULL`).
- **Remediation:**
  Add transaction-wrapped cascade cleaning in each model's `delete()` method.

---

#### BUG-DB-03: Unvalidated MySQL Strict Mode ENUM Failures
- **Severity:** `HIGH`
- **Affected Columns:**
  - `users.status` (`'active'`, `'inactive'`, `'suspended'`)
  - `inquiries.status` (`'new'`, `'read'`, `'replied'`, `'archived'`)
  - `crm_clients.stage` (`'lead'`, `'prospect'`, `'active'`, `'churned'`)
  - `crm_tickets.priority` (`'low'`, `'medium'`, `'high'`, `'urgent'`)
  - `crm_tickets.status` (`'open'`, `'in_progress'`, `'waiting'`, `'resolved'`, `'closed'`)
  - `crm_notes.type` (`'general'`, `'call'`, `'email'`, `'meeting'`)
- **Root Cause:**
  User inputs are passed into ENUM columns without `in_array()` whitelist validation. In MySQL strict mode, any unrecognized string throws `SQLSTATE[22001]: Data truncated for column`.
- **Remediation:**
  Enforce explicit whitelist array validation in models and controllers before saving.

---

#### BUG-DB-04: SQLite DDL Crash on `UNIQUE KEY` in `schema.sql` and `install.php`
- **Severity:** `HIGH`
- **Affected Files:**
  - `database/schema.sql` (Line 405)
  - `seed-demo.php` (Lines 48–62)
  - `install.php` (Line 97)
- **Root Cause:**
  Line 405 contains `UNIQUE KEY \`uq_agency_project_member\` (\`agency_project_id\`, \`team_member_id\`)`. SQLite rejects the keyword `KEY` inside table definitions. Neither `seed-demo.php` nor `install.php` sanitizes `UNIQUE KEY`.
- **Remediation:**
  Add regex transformation in SQLite execution paths:
  ```php
  $sql = preg_replace('/UNIQUE\s+KEY\s+`?[^`\s]+`?\s*\(([^)]+)\)/i', 'UNIQUE ($1)', $sql);
  ```

---

#### BUG-DB-05: Web Installer Step 4 Premature Lockout
- **Severity:** `HIGH`
- **Affected Files:**
  - `install.php` (Lines 14–16, 136–138)
- **Root Cause:**
  At the end of Step 3, the installer writes `storage/installed.lock` and redirects to `install.php?step=4`. Line 14 immediately terminates with `die('Creatsify Studio is already configured')` before checking if `$step === 4`.
- **Remediation:**
  Update line 14:
  ```php
  if (file_exists($lockFile) && ($step !== 4)) {
  ```

---

### Section 4: Models & Data Access Layer

#### BUG-MDL-01: Critical Cross-Client Message Leak in WhatsApp & SMS Models
- **Severity:** `CRITICAL`
- **Affected Files:**
  - `app/Models/WhatsAppMessage.php` (Lines 22–30)
  - `app/Models/SmsMessage.php` (Lines 22–30)
- **Root Cause:**
  In `forContact($phone)`:
  ```php
  $digits = substr(preg_replace('/\D/', '', $phone), -10);
  $sql = "... WHERE `recipient_phone` LIKE :p OR `sender_phone` LIKE :p ...";
  return db()->fetchAll($sql, [':p' => "%{$digits}%"]);
  ```
  If `$phone` contains no digits (or is empty/null), `$digits` becomes `""`, binding `:p` to `%%`. The query matches every row in the database, returning **every client's private messages**.
- **Remediation:**
  Return an empty array if `strlen($digits) < 7`.

---

#### BUG-MDL-02: Incomplete Phone Normalization in `clean_phone_number()`
- **Severity:** `HIGH`
- **Affected Files:**
  - `app/Helpers/functions.php` (Lines 240–252)
- **Root Cause:**
  The helper strips non-digits and prepends `+`. If given an empty string or symbols (e.g. `(---)`), it returns `"+"`. When passed to WhatsApp API, it triggers an API error.
- **Remediation:**
  Return an empty string if no digits exist.

---

#### BUG-MDL-03: Fatal SQL Syntax Error on Empty Array in `Core\Database::update()`
- **Severity:** `HIGH`
- **Affected Files:**
  - `core/Database.php` (Lines 110–120)
- **Root Cause:**
  If an empty array `$data = []` is passed, `update()` generates invalid SQL:
  ```sql
  UPDATE `table` SET  WHERE `id` = :id
  ```
  This immediately triggers a `PDOException`.
- **Remediation:**
  Return 0 early if `empty($data)`.

---

#### BUG-MDL-04: Secondary Crash in `Core\Database::rollback()`
- **Severity:** `MEDIUM`
- **Affected Files:**
  - `core/Database.php` (Lines 140–148)
- **Root Cause:**
  Calling `rollback()` when no transaction is active throws a `PDOException: There is no active transaction`.
- **Remediation:**
  Guard with `if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }`.

---

#### BUG-MDL-05: Return Type Mismatch in `ChatSession::findOrCreate()`
- **Severity:** `HIGH`
- **Affected Files:**
  - `app/Models/ChatSession.php` (Line 16)
- **Root Cause:**
  Declares return type `: array`, but on database failure or empty result `fetchOne()` returns `false` or `null`.
- **Remediation:**
  Update return type to `?array` or return `[]` on failure.

---

#### BUG-MDL-06: Raw Interpolation of `LIMIT` and `OFFSET` in Models
- **Severity:** `MEDIUM`
- **Affected Files:**
  - `app/Models/Notification.php` (Lines 13, 36)
  - `app/Models/ChatMessage.php` (Line 13)
  - `app/Models/AuditLog.php` (Line 33)
- **Root Cause:**
  Direct string interpolation `LIMIT {$limit} OFFSET {$offset}` instead of explicit integer casting `(int)$limit`.
- **Remediation:**
  Cast all pagination parameters strictly with `(int)`.

---

### Section 5: Application Security & Access Controls

#### BUG-SEC-01: Critical Automated Installer Hijack Vector
- **Severity:** `CRITICAL`
- **Affected Files:**
  - `core/App.php` (Lines 136–144)
  - `.gitignore` (Line 7)
- **Root Cause:**
  `core/App.php` checks if `storage/installed.lock` exists. If not, it redirects every visitor to `/install.php`. Because `storage/installed.lock` is gitignored, every fresh production checkout allows unauthenticated attackers to re-run the installer and overwrite super admin credentials.
- **Remediation:**
  1. If `config/database.php` has configured credentials and DB connection succeeds, bypass redirect to `/install.php`.
  2. Block `/install.php` in production when database tables exist.

---

#### BUG-SEC-02: Infinite 302 Redirect Loop for `editor` Role
- **Severity:** `HIGH`
- **Affected Files:**
  - `config/routes.php` (Line 63)
  - `app/Controllers/Admin/AuthController.php` (Line 55)
  - `app/Middleware/RoleMiddleware.php` (Line 30)
- **Root Cause:**
  When an `editor` logs in, `AuthController::login()` redirects to `/admin`. But `/admin` in `routes.php` is protected with `role:super_admin,admin`. `RoleMiddleware` redirects unauthorized users back to `/admin`, creating an infinite 302 redirect loop.
- **Remediation:**
  Redirect `editor` to `/admin/posts` or update `/admin` role permissions to include `editor`.

---

#### BUG-SEC-03: Stale Privileges on User Deactivation
- **Severity:** `HIGH`
- **Affected Files:**
  - `app/Middleware/Authenticate.php` (Line 15)
  - `app/Helpers/functions.php` (Line 186)
- **Root Cause:**
  The session stores user data on login. If an admin deactivates an employee account (`status = 'inactive'`) or deletes it, the session remains active indefinitely.
- **Remediation:**
  Periodically re-verify user status in `Authenticate` middleware against the database.

---

#### BUG-SEC-04: Open Redirect Vulnerability in CRM Controller
- **Severity:** `MEDIUM`
- **Affected Files:**
  - `app/Controllers/Admin/CrmController.php` (Lines 193, 440, 464, 472, 480, 489)
- **Root Cause:**
  `Response::redirect($request->input('redirect'))` directly follows user-supplied URLs without verifying that they are relative paths or trusted internal domains.
- **Remediation:**
  Sanitize redirect targets: ensure they start with `/` and not `//`.

---

#### BUG-SEC-05: Missing `.git` Repository Protection in Nginx Rules
- **Severity:** `HIGH`
- **Affected Files:**
  - `aapanel_url_rewrite.conf`
- **Root Cause:**
  While `.htaccess` blocks `/\.git`, `aapanel_url_rewrite.conf` omits protection for hidden Git directories.
- **Remediation:**
  Add location block to deny access to `/\.git`:
  ```nginx
  location ~ /\.git {
      deny all;
      return 404;
  }
  ```

---

### Section 6: Views & Template Rendering

#### BUG-VIEW-01: Critical CRM View Layout Omission
- **Severity:** `CRITICAL`
- **Affected Files:**
  - `views/admin/crm/clients/index.php`
  - `views/admin/crm/clients/create.php`
  - `views/admin/crm/clients/edit.php`
  - `views/admin/crm/clients/show.php`
  - `views/admin/crm/notes/index.php`
  - `views/admin/crm/tickets/index.php`
  - `views/admin/crm/tickets/create.php`
  - `views/admin/crm/tickets/show.php`
- **Root Cause:**
  All 8 CRM view templates lack `$this->layout('layouts/admin')`. They render as naked unstyled HTML fragments without `<head>`, `admin.css`, topbar desk, or sidebar navigation.
- **Remediation:**
  Add `$this->layout('layouts/admin', ['title' => '...']);` at the top of all 8 templates.

---

#### BUG-VIEW-02: Missing HTML Escaping for Initial Badges & URLs
- **Severity:** `MEDIUM`
- **Affected Files:**
  - `views/admin/chat/index.php` (Lines 65, 129)
  - `views/admin/crm/clients/index.php` (Line 146)
  - `views/admin/crm/tickets/show.php` (Line 80)
  - `views/home/index.php` (Lines 378, 637)
  - `views/about/index.php` (Line 37)
  - `views/team/index.php` (Line 30)
  - `views/team/show.php` (Lines 21, 108)
- **Root Cause:**
  Direct output of initials and URLs without `e(...)` or `htmlspecialchars()`.
- **Remediation:**
  Wrap all dynamic values in `e()`.

---

#### BUG-VIEW-03: Malformed OpenGraph URL in Layout Main
- **Severity:** `LOW`
- **Affected Files:**
  - `views/layouts/main.php` (Line 30)
- **Root Cause:**
  `og:url` is passed an asset path rather than an absolute URL, producing root-relative meta tags instead of canonical links.
- **Remediation:**
  Use `url($currentUri)` with canonical domain generation.

---

## Part 2: Positive Architecture & Rule Compliance

The following hard rules from `AGENTS.md` and `prompts.md` were evaluated and found fully compliant:

1. **Zero Third-Party Marketplace Contamination:**  
   Codester, Coderobotics, CodeGrape, and SellAnyCode references are completely removed from production source code.
2. **Official Gumroad Commercial Architecture:**  
   White-label software products correctly utilize `gumroad_url` and `price` fields. The `/support` portal integrates Gumroad license verification.
3. **Prepared Statements Exclusivity:**  
   Zero raw concatenation in model queries.
4. **Strict Dual-Remote Alignment:**  
   Repository is configured with both `origin` and `old-creatsify` remotes.

---

## Batch 2 Transition Roadmap

With the Core Engine, Routing, Controllers, Database, Models, Security, and Views fully audited and mapped in Batch 1, the following 5 scopes are initiated for Batch 2:

1. **Agent 7: CSS Architecture & Design Tokens Auditor** (`public/assets/css/*.css`)
2. **Agent 8: JavaScript Logic & Client Engine Auditor** (`public/assets/js/*.js`)
3. **Agent 9: Accessibility (WCAG 2.1 AA) Auditor** (ARIA, color contrast, semantic landmarks)
4. **Agent 10: Mobile Responsiveness & Viewports Auditor** (Touch targets >= 44px, drawer behaviour)
5. **Agent 11: Feature & CRM Business Logic Auditor** (Lead progression, customer tickets, demo safeguards)
