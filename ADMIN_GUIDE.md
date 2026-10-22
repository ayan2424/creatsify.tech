# Creatsify Studio - Administrator & Operations Guide

Welcome to the **Creatsify Studio** administration desk. This guide details how to operate the CMS, live communication channels, portfolio showcase, and security systems.

---

## 1. Accessing the Admin Desk

- **URL:** `https://yourdomain.com/admin` (or `/admin/login`)
- **Default Super Admin:** `ayan@creatsify.tech`
- **Default Password:** `admin123`
- **1-Click Demo Login:** For marketplace evaluators, click the **⚡ 1-Click Super Admin Login** button on the login screen.

---

## 2. Live Chat Desk (Cheetah Architecture)

Located at `/admin/chat`. Modeled directly on the **Cheetah** logistics chat desk.

1. **Visitor Stream & Handoff Alerts:**
   - Visitors using the public floating widget on your website are automatically serviced by the first-party intelligent knowledge engine.
   - When a visitor asks to speak with a human or founder, the session is marked with a pink **HANDOFF** badge.
   - An audible chime alerts online administrators, and an automated WhatsApp notification is dispatched to the admin's phone.
2. **Taking Over Live Conversations:**
   - Click any active session from the left-hand column to view the conversation history.
   - Type your response in the bottom reply input and click **Send**. The visitor receives your message in real time without refreshing.
3. **Closing Sessions:**
   - Click **Close Session** in the top right to archive completed chats.

---

## 3. Two-Way WhatsApp Inbox (Meta Cloud API v21.0)

Located at `/admin/whatsapp`.

- **Inbound Messages:** Received automatically via webhook at `/api/webhooks/whatsapp`.
- **Viewing Threads:** Conversations are grouped by international/Pakistani E.164 phone numbers (`923xx`).
- **Composing Messages:** Enter the recipient's phone number and message to dispatch via the Meta Graph API.
- **Automated Alerts:** When a client submits an inquiry through the contact form, an instant WhatsApp message is dispatched to the configured admin phone number.

---

## 4. Two-Way SMS Inbox

Located at `/admin/sms`.

- Powered by **Twilio** or local Pakistani SMS gateways.
- Send single or bulk SMS alerts directly from the browser.

---

## 5. Homepage Section Builder (Cheezify Architecture)

Located at `/admin/sections`.

Every section on the homepage is dynamically editable:
1. **Hero Section:** Edit headlines, rotating word arrays, lead paragraphs, and floating badge chips.
2. **Tech Stack Marquee:** Update technologies displayed in the infinite scroll track.
3. **Key Stats:** Modify numbers, suffixes (`+`), and descriptive labels.
4. **Cheetah Flagship Showcase:** Update the live shipment tracker simulation (Tracking code, origin, destination, timeline milestones).
5. **About Founder:** Edit Muhammad Ayan Shamim's bio, education distinction, GitHub, and LinkedIn links.
6. **Big Marquee CTA:** Adjust typography callouts.
7. **Contact:** Edit the introductory text and instructions.

---

## 6. Portfolio & Project Management

Located at `/admin/projects`.

- **Categories:** Platforms & SaaS (`saas`), Stores & Ordering (`stores`), Apps & Tools (`apps`).
- **Fields:** Title, slug, tech stack tags, summary, full description, live URL, client name, and sort order.
- **Featured Toggle:** Mark projects as *Featured* to prioritize them on the homepage.

---

## 7. Media Library & Brand Assets

Located at `/admin/media`.

- **Drag-and-Drop Uploader:** Supports PNG, JPG, SVG, WebP, GIF, and PDF files.
- **1-Click URL Copying:** Click **Copy URL** on any asset card to immediately copy the public asset link to your clipboard.

---

## 8. User & Role Management (Cheezify Security)

Located at `/admin/users`.

- **Roles:**
  - `Super Admin`: Full system permissions, code injectors, user management, and credentials.
  - `Admin`: Can manage portfolio content, blog posts, chat desk, and view analytics.
  - `Editor`: Authors blog posts and edits project writeups.
- **Audit Logs:** View an immutable timeline of administrative logins, edits, and file uploads at `/admin/audit-logs`.

---

## 9. Theme Customizer, SMTP & Custom Code

Located at `/admin/settings`.

- **Theme & Appearance:** Interactive color picker to set the primary accent color across the entire site, dark/light default mode, and custom logo URLs.
- **Mail & SMTP:** Configure SMTP host, port, credentials, and use the **Diagnostic Mailer Test** to verify email delivery.
- **WhatsApp & SMS:** Manage Meta Cloud API tokens, phone IDs, and Twilio credentials.
- **Custom Code & CSS:** Inject tracking scripts (Google Analytics, Meta Pixel) into `<head>` or `<footer>` and write custom CSS overrides directly in the browser.
