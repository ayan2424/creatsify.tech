# Creatsify Studio - Production Installation & Deployment Guide

This guide provides step-by-step instructions for deploying **Creatsify Studio** to any web server, VPS, or control panel (aaPanel, cPanel, CyberPanel).

---

## aaPanel Deployment Guide (Step-by-Step)

If you are using **aaPanel** (like `creatsify.tech` at `/www/wwwroot/creatsify.tech`), follow these exact steps:

### Step 1: Set Document Root to `/public`
1. Go to **aaPanel -> Websites**.
2. Click on **creatsify.tech**.
3. Under **Site directory**:
   - Site directory: `/www/wwwroot/creatsify.tech`
   - **Running directory (Document Root):** Select **`/public`** from the dropdown.
   - Click **Save**.

### Step 2: Configure URL Rewrite
1. In the same website settings window, click the **URL rewrite** tab on the left.
2. Select everything (`Ctrl + A`) and delete it.
3. Paste the contents of `aapanel_url_rewrite.conf` (or the snippet below):
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
4. Click **Save**.

### Step 3: MySQL Database Setup
1. Go to **aaPanel -> Databases**.
2. Click **Add Database**.
   - Database name: `creatsify_db` (or your preferred name)
   - Username: `creatsify_db`
   - Password: copy the generated password
3. Click **Submit**.

### Step 4: Run 1-Click Graphical Web Installer
1. Open your browser and go to:
   ```
   https://creatsify.tech/install.php
   ```
2. Enter your database details from Step 3:
   - Host: `127.0.0.1`
   - Database Name: `creatsify_db`
   - Username: `creatsify_db`
   - Password: `<your database password>`
3. Set your Super Admin email and password.
4. Click **Run Migration & Finish Setup**.
5. Done! The installer automatically migrates all 17 tables, generates default sections and admin credentials, writes `.env`, and locks the setup.

---

## Alternative: Direct `.env` Configuration (No Installer)

If you already imported `database/schema.sql` and `database/seeds.sql` directly via phpMyAdmin:
1. In **aaPanel -> Files**, open `/www/wwwroot/creatsify.tech/`.
2. Create or edit `.env`:
```env
APP_NAME="Creatsify Studio"
APP_ENV=production
APP_DEBUG=false
APP_URL="https://creatsify.tech"
APP_TIMEZONE="Asia/Karachi"

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=creatsify_db
DB_USERNAME=creatsify_db
DB_PASSWORD="your_database_password_here"
```
3. Create `storage/installed.lock` to signal that installation is complete:
```bash
touch /www/wwwroot/creatsify.tech/storage/installed.lock
```

---

## Standalone Linux VPS Setup (Without Control Panel)

If you are running a clean Ubuntu/Debian VPS with custom Nginx:
1. Copy the configuration from Section 2 of `nginx.conf` into `/etc/nginx/sites-available/creatsify.tech`.
2. Enable site:
   ```bash
   ln -s /etc/nginx/sites-available/creatsify.tech /etc/nginx/sites-enabled/
   nginx -t
   systemctl reload nginx
   ```
3. Run the installer at `https://creatsify.tech/install.php`.
