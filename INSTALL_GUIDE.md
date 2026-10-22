# Creatsify Studio - Production Installation Guide

This guide provides step-by-step instructions for deploying **Creatsify Studio** to any web server or hosting environment.

---

## System Requirements

- **PHP:** 8.2 or higher
- **Extensions:** `pdo`, `pdo_mysql` (or `pdo_sqlite`), `curl`, `openssl`, `mbstring`, `json`
- **Web Server:** Apache (with `mod_rewrite`), Nginx, or cPanel/LiteSpeed
- **Database:** MySQL 5.7+ / MariaDB 10.3+ or SQLite 3

---

## Method 1: Graphical Web Installer (Recommended for cPanel & Shared Hosting)

1. Upload the project zip archive to your web root (`public_html` or domain directory) and extract it.
2. Ensure your domain points to the project root or the `public/` directory.
   - *Note:* A root `.htaccess` file is pre-configured to automatically route traffic into `public/` if you cannot change your document root.
3. Open your browser and navigate to:
   ```
   https://yourdomain.com/install.php
   ```
4. Follow the 4-step wizard:
   - **Step 1:** System verifies server requirements and file permissions.
   - **Step 2:** Enter your MySQL database credentials (or choose SQLite for zero configuration).
   - **Step 3:** Enter your Super Admin email, password, and studio title.
   - **Step 4:** Migration completes automatically, loads seed data, and locks the installer.
5. Log in to your Admin Desk at `https://yourdomain.com/admin/login`.

---

## Method 2: Local Development Setup (CLI & Built-in PHP Server)

1. Open your terminal in the project directory:
   ```bash
   cd c:\Users\ayans\OneDrive\Documents\A_Projects\Creatsify.tech
   ```
2. Run the automated demo seeder:
   ```bash
   php seed-demo.php
   ```
3. Start the built-in development server:
   ```bash
   php -S localhost:8000 -t public
   ```
4. Open `http://localhost:8000` in your browser.
5. Access the Admin Desk at `http://localhost:8000/admin` using:
   - **Email:** `ayan@creatsify.tech`
   - **Password:** `admin123`

---

## Nginx Configuration

If running on Nginx, point `root` to the `public/` directory and configure URL rewrites:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/creatsify.tech/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## Security Recommendations

1. Ensure the `storage/` directory and `storage/installed.lock` are kept protected and not directly accessible via public HTTP.
2. In production, change the default Super Admin password and configure an SSL certificate (Let's Encrypt / Cloudflare).
