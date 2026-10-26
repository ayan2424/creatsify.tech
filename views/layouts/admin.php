<?php
/** @var Core\View $this */
$user = auth() ?? ['name' => 'Admin User', 'email' => 'admin@creatsify.tech', 'role' => 'admin'];
$primaryAccent = setting('primary_accent', '#8b7bff');
$currentPath = request()->getPath();
$topbarUnreadInquiries = \App\Models\Inquiry::countByStatus('new');
$topbarUnreadNotifs = \App\Models\Notification::countUnread();
$topbarTotalAlerts = $topbarUnreadInquiries + $topbarUnreadNotifs;
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= e($meta_title ?? 'Creatsify Admin Desk') ?></title>
    <link rel="icon" type="image/png" href="<?= asset('brand/favicon.png') ?>">

    <!-- Fonts: Bricolage Grotesque & DM Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600..700&family=DM+Sans:ital,opsz,wght@0,9..40,400..600;1,9..40,400..600&display=swap" rel="stylesheet">

    <!-- Lumix AI Command Center Stylesheet -->
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>?v=3.2">

    <!-- Theme & Sidebar State Initializer -->
    <script>
        (function() {
            try {
                var t = localStorage.getItem('creatsify-admin-theme') || 'dark';
                document.documentElement.dataset.theme = t;
                if (localStorage.getItem('creatsify-admin-sidebar-collapsed') === 'true' && window.innerWidth > 1024) {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch(e) {}
        })();
    </script>
</head>
<body>
    <!-- 1. Lumix AI Rail Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <a href="<?= url('/admin') ?>" class="brand-anchor" title="Creatsify Admin Dashboard">
                <img src="<?= asset('brand/creatsify-wordmark.png') ?>" alt="Creatsify" class="brand-wordmark-dark">
                <img src="<?= asset('brand/creatsify-wordmark-light.png') ?>" alt="Creatsify" class="brand-wordmark-light">
                <img src="<?= asset('brand/creatsify-icon.png') ?>" alt="Creatsify" class="brand-icon-collapsed">
            </a>
            <div class="sidebar-brand-right">
                <span class="brand-dot" title="Live Node Online"></span>
                <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Close navigation drawer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>

        <nav class="sidebar-menu">
            <a href="<?= url('/admin') ?>" class="nav-link <?= $currentPath === '/admin' ? 'active' : '' ?>" data-title="Overview" title="Overview">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span>Overview</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/chat') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/chat') ? 'active' : '' ?>" data-title="Live Chat Desk" title="Live Chat Desk">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <span>Live Chat Desk</span>
                </span>
                <span class="nav-badge danger" id="sideLiveChatBadge" style="display:none;">Live</span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/inquiries') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/inquiries') ? 'active' : '' ?>" data-title="Client Inquiries" title="Client Inquiries">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span>Client Inquiries</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/whatsapp') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/whatsapp') ? 'active' : '' ?>" data-title="WhatsApp API" title="WhatsApp API">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <span>WhatsApp API</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/sms') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/sms') ? 'active' : '' ?>" data-title="SMS Gateway" title="SMS Gateway">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    <span>SMS Gateway</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>

            <div class="menu-divider"></div>

            <a href="<?= url('/admin/crm/clients') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/crm/clients') ? 'active' : '' ?>" data-title="Clients & Deals" title="Clients & Deals">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>Clients & Deals</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/crm/tickets') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/crm/tickets') ? 'active' : '' ?>" data-title="Support Tickets" title="Support Tickets">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="13" y2="17"/></svg>
                    <span>Support Tickets</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/crm/notes') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/crm/notes') ? 'active' : '' ?>" data-title="CRM Notes & Logs" title="CRM Notes & Logs">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    <span>CRM Notes & Logs</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>

            <div class="menu-divider"></div>

            <a href="<?= url('/admin/sections') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/sections') ? 'active' : '' ?>" data-title="Section Builder" title="Section Builder">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    <span>Section Builder</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/projects') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/projects') ? 'active' : '' ?>" data-title="Agency Products" title="Agency Products">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                    <span>Agency Products</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/team') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/team') ? 'active' : '' ?>" data-title="Team & Portfolios" title="Team & Portfolios">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Team & Portfolios</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/services') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/services') ? 'active' : '' ?>" data-title="Studio Services" title="Studio Services">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                    <span>Studio Services</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/posts') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/posts') ? 'active' : '' ?>" data-title="Blog Articles" title="Blog Articles">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    <span>Blog Articles</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/media') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/media') ? 'active' : '' ?>" data-title="Media Library" title="Media Library">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                    <span>Media Library</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>

            <div class="menu-divider"></div>

            <a href="<?= url('/admin/users') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/users') ? 'active' : '' ?>" data-title="Users & RBAC" title="Users & RBAC">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span>Users & RBAC</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/audit-logs') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/audit-logs') ? 'active' : '' ?>" data-title="Audit Logs" title="Audit Logs">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    <span>Audit Logs</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="<?= url('/admin/settings') ?>" class="nav-link <?= str_starts_with($currentPath, '/admin/settings') ? 'active' : '' ?>" data-title="White-Label Settings" title="White-Label Settings">
                <span class="nav-link-content">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <span>White-Label Settings</span>
                </span>
                <svg class="nav-chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </nav>

        <!-- Sidebar User Footer Card (Analytics Style) -->
        <div class="sidebar-user-footer">
            <div class="sidebar-user-info" title="<?= e($user['name']) ?> (<?= e($user['role']) ?>)">
                <img src="<?= asset('brand/creatsify-icon.png') ?>" alt="<?= e($user['name']) ?>" class="sidebar-user-avatar">
                <div class="sidebar-user-details">
                    <span class="sidebar-user-name"><?= e($user['name']) ?></span>
                    <span class="sidebar-user-role"><?= e($user['role']) ?></span>
                </div>
            </div>
            <form action="<?= url('/admin/logout') ?>" method="POST" style="margin:0;">
                <?= csrf_field() ?>
                <button type="submit" class="sidebar-logout-btn" title="Sign Out" aria-label="Sign out">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                </button>
            </form>
        </div>
    </aside>
    <!-- Off-Canvas Sidebar Backdrop -->
    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>

    <!-- 2. Main Executive Desk Area -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div class="topbar-left">
                <!-- Mobile Navigation Drawer Toggle Button -->
                <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Toggle navigation drawer">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>

                <!-- Mobile Brand Link (Official Logo) -->
                <a href="<?= url('/admin') ?>" class="topbar-mobile-brand" title="Creatsify Admin">
                    <img src="<?= asset('brand/creatsify-wordmark.png') ?>" alt="Creatsify" class="brand-wordmark-dark">
                    <img src="<?= asset('brand/creatsify-wordmark-light.png') ?>" alt="Creatsify" class="brand-wordmark-light">
                </a>

                <!-- Page Title (Clean & Airy) -->
                <div class="topbar-heading-group">
                    <h2 class="topbar-page-heading"><?= e($page_title ?? 'Dashboard Overview') ?></h2>
                </div>
            </div>

            <div class="topbar-right">
                <!-- Desktop Executive Command Search Bar -->
                <div class="command-search-wrap">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" class="command-search-input" id="adminGlobalSearch" placeholder="Search...">
                    <span class="command-kbd">⌘K</span>
                </div>

                <!-- Mobile Search Toggle Button -->
                <button type="button" class="topbar-icon-btn mobile-search-toggle" id="mobileSearchToggle" title="Search desk">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>

                <!-- Primary Action Button (Panze / Analytics Capsule CTA) -->
                <a href="<?= url('/admin/projects/create') ?>" class="btn-top-action" title="Create a new system or post">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span class="btn-top-action-text">New Project</span>
                </a>

                <!-- Notification Bell with Alert Badge (Analytics Style) -->
                <a href="<?= url('/admin/inquiries') ?>" class="topbar-icon-btn topbar-bell-btn" title="Client Inquiries & Notifications">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <span class="topbar-bell-badge" id="topbarBellBadge" style="<?= $topbarTotalAlerts > 0 ? '' : 'display:none;' ?>"><?= $topbarTotalAlerts ?></span>
                </a>

                <!-- Live Frontend Site Link -->
                <a href="<?= url('/') ?>" target="_blank" class="topbar-icon-btn topbar-public-link" title="View Public Website">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </a>

                <!-- Day / Night Theme Toggle Button -->
                <button type="button" class="topbar-icon-btn" id="adminThemeToggle" title="Switch Theme (Lumix Dark / Panze Light)">
                    <svg class="adm-icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                </button>

                <!-- User Profile Pill (Sleek Avatar Only) -->
                <div class="user-profile-pill" title="<?= e($user['name']) ?> (<?= e($user['role']) ?>)">
                    <img src="<?= asset('brand/creatsify-icon.png') ?>" alt="<?= e($user['name']) ?>" class="user-avatar-img">
                </div>
            </div>
            <!-- Seamless Junction Fillet Curve between Sidebar & Topbar -->
            <div class="topbar-junction-curve" aria-hidden="true"></div>
        </header>

        <!-- Mobile Search Dropdown Bar -->
        <div class="mobile-search-bar" id="mobileSearchBar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" class="mobile-search-input" id="adminMobileSearchInput" placeholder="Search projects, client leads...">
            <button type="button" class="mobile-search-close" id="mobileSearchClose" aria-label="Close search">&times;</button>
        </div>

        <main class="admin-content">
            <!-- Flash Alerts (Dismissible Toast Style) -->
            <?php if ($msg = flash('success')): ?>
                <div class="alert alert-success" id="adminFlashAlert">
                    <div class="alert-content">
                        <span class="alert-icon">&check;</span>
                        <span><?= e($msg) ?></span>
                    </div>
                    <button type="button" class="alert-dismiss-btn" onclick="document.getElementById('adminFlashAlert').remove()">&times;</button>
                </div>
            <?php endif; ?>
            <?php if ($err = flash('error')): ?>
                <div class="alert alert-danger" id="adminFlashAlert">
                    <div class="alert-content">
                        <span class="alert-icon">&excl;</span>
                        <span><?= e($err) ?></span>
                    </div>
                    <button type="button" class="alert-dismiss-btn" onclick="document.getElementById('adminFlashAlert').remove()">&times;</button>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>

    <!-- Background Live Desk Audio Chime -->
    <audio id="adminDeskChime" preload="auto">
        <source src="data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU..." type="audio/wav">
    </audio>

    <!-- Admin Live Polling, Mobile Drawer & Theme Script -->
    <script>
        (function() {
            // Theme Switcher Logic
            const themeBtn = document.getElementById('adminThemeToggle');
            if (themeBtn) {
                themeBtn.addEventListener('click', function() {
                    const current = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
                    document.documentElement.dataset.theme = current;
                    localStorage.setItem('creatsify-admin-theme', current);
                });
            }

            // Sidebar Controls (Collapsible Desktop Rail + Mobile Off-Canvas Drawer)
            const sidebar = document.getElementById('adminSidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const closeBtn = document.getElementById('sidebarCloseBtn');
            const backdrop = document.getElementById('adminSidebarBackdrop');

            function isDesktop() {
                return window.innerWidth > 1024;
            }

            function toggleDesktopSidebar() {
                const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
                localStorage.setItem('creatsify-admin-sidebar-collapsed', isCollapsed ? 'true' : 'false');
                if (tooltipEl) tooltipEl.classList.remove('visible');
            }

            function openMobileSidebar() {
                if (sidebar) sidebar.classList.add('open');
                if (backdrop) backdrop.classList.add('active');
                document.body.classList.add('sidebar-locked');
            }

            function closeMobileSidebar() {
                if (sidebar) sidebar.classList.remove('open');
                if (backdrop) backdrop.classList.remove('active');
                document.body.classList.remove('sidebar-locked');
            }

            // Single Toggle button in Topbar
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (isDesktop()) {
                        toggleDesktopSidebar();
                    } else {
                        openMobileSidebar();
                    }
                });
            }

            if (closeBtn) closeBtn.addEventListener('click', closeMobileSidebar);
            if (backdrop) backdrop.addEventListener('click', closeMobileSidebar);

            // Auto-close drawer on mobile when clicking a navigation link
            if (sidebar) {
                sidebar.querySelectorAll('.nav-link, .btn-quick-action').forEach(link => {
                    link.addEventListener('click', function() {
                        if (!isDesktop()) {
                            closeMobileSidebar();
                        }
                    });
                });
            }

            // Floating Tooltip for Collapsed Sidebar
            const tooltipEl = document.createElement('div');
            tooltipEl.className = 'sidebar-floating-tooltip';
            document.body.appendChild(tooltipEl);

            if (sidebar) {
                sidebar.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
                    link.addEventListener('mouseenter', function() {
                        if (!document.documentElement.classList.contains('sidebar-collapsed') || !isDesktop()) return;
                        const title = this.getAttribute('data-title') || this.getAttribute('title');
                        if (!title) return;
                        tooltipEl.textContent = title;
                        const rect = this.getBoundingClientRect();
                        tooltipEl.style.top = (rect.top + rect.height / 2) + 'px';
                        tooltipEl.style.left = (rect.right + 12) + 'px';
                        tooltipEl.classList.add('visible');
                    });
                    link.addEventListener('mouseleave', function() {
                        tooltipEl.classList.remove('visible');
                    });
                });
            }

            // Mobile Search Controls
            const mobileSearchToggle = document.getElementById('mobileSearchToggle');
            const mobileSearchBar = document.getElementById('mobileSearchBar');
            const mobileSearchClose = document.getElementById('mobileSearchClose');
            const mobileSearchInput = document.getElementById('adminMobileSearchInput');

            if (mobileSearchToggle && mobileSearchBar) {
                mobileSearchToggle.addEventListener('click', function() {
                    mobileSearchBar.classList.toggle('active');
                    if (mobileSearchBar.classList.contains('active') && mobileSearchInput) {
                        setTimeout(() => mobileSearchInput.focus(), 100);
                    }
                });
            }

            if (mobileSearchClose && mobileSearchBar) {
                mobileSearchClose.addEventListener('click', function() {
                    mobileSearchBar.classList.remove('active');
                });
            }

            // Global Keyboard Shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl+K / Cmd+K for search
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    if (window.innerWidth <= 900) {
                        if (mobileSearchBar) {
                            mobileSearchBar.classList.add('active');
                            if (mobileSearchInput) mobileSearchInput.focus();
                        }
                    } else {
                        const s = document.getElementById('adminGlobalSearch');
                        if (s) s.focus();
                    }
                }
                // Ctrl+B / Cmd+B for sidebar toggle
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b') {
                    e.preventDefault();
                    if (isDesktop()) {
                        toggleDesktopSidebar();
                    } else {
                        if (sidebar && sidebar.classList.contains('open')) {
                            closeMobileSidebar();
                        } else {
                            openMobileSidebar();
                        }
                    }
                }
                // ESC to close drawer & search
                if (e.key === 'Escape') {
                    closeMobileSidebar();
                    if (mobileSearchBar) mobileSearchBar.classList.remove('active');
                }
            });

            // Fast Executive Quick-Jump Search Handler
            function executeDeskSearch(query) {
                const q = (query || '').trim().toLowerCase();
                if (!q) return;
                const routes = {
                    'chat': '<?= url("/admin/chat") ?>',
                    'inquir': '<?= url("/admin/inquiries") ?>',
                    'lead': '<?= url("/admin/inquiries") ?>',
                    'project': '<?= url("/admin/projects") ?>',
                    'product': '<?= url("/admin/projects") ?>',
                    'post': '<?= url("/admin/posts") ?>',
                    'blog': '<?= url("/admin/posts") ?>',
                    'service': '<?= url("/admin/services") ?>',
                    'team': '<?= url("/admin/team") ?>',
                    'media': '<?= url("/admin/media") ?>',
                    'asset': '<?= url("/admin/media") ?>',
                    'user': '<?= url("/admin/users") ?>',
                    'role': '<?= url("/admin/users") ?>',
                    'setting': '<?= url("/admin/settings") ?>',
                    'smtp': '<?= url("/admin/settings?tab=mail") ?>',
                    'mail': '<?= url("/admin/settings?tab=mail") ?>',
                    'theme': '<?= url("/admin/settings?tab=appearance") ?>',
                    'color': '<?= url("/admin/settings?tab=appearance") ?>',
                    'whatsapp': '<?= url("/admin/whatsapp") ?>',
                    'sms': '<?= url("/admin/sms") ?>',
                    'section': '<?= url("/admin/sections") ?>',
                    'home': '<?= url("/admin/sections") ?>',
                    'log': '<?= url("/admin/audit-logs") ?>',
                    'audit': '<?= url("/admin/audit-logs") ?>'
                };
                for (const [key, dest] of Object.entries(routes)) {
                    if (q.includes(key)) {
                        window.location.href = dest;
                        return;
                    }
                }
                window.location.href = '<?= url("/admin/audit-logs") ?>?search=' + encodeURIComponent(q);
            }

            const deskGlobalSearch = document.getElementById('adminGlobalSearch');
            if (deskGlobalSearch) {
                deskGlobalSearch.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        executeDeskSearch(this.value);
                    }
                });
            }
            if (mobileSearchInput) {
                mobileSearchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        executeDeskSearch(this.value);
                    }
                });
            }

            // Live Desk Polling
            function pollSystem() {
                fetch('<?= url('/admin/poll') ?>')
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const badge = document.getElementById('sideLiveChatBadge');
                            if (badge) {
                                if (data.unread_chat_messages > 0) {
                                    badge.style.display = 'inline-block';
                                    badge.textContent = data.unread_chat_messages + ' New';
                                } else {
                                    badge.style.display = 'none';
                                }
                            }
                            const bellBadge = document.getElementById('topbarBellBadge');
                            if (bellBadge) {
                                const total = (data.new_inquiries || 0) + (data.unread_notifications || 0);
                                if (total > 0) {
                                    bellBadge.textContent = total;
                                    bellBadge.style.display = 'inline-flex';
                                } else {
                                    bellBadge.style.display = 'none';
                                }
                            }
                        }
                    })
                    .catch(() => {});
            }
            setInterval(pollSystem, 10000);
        })();
    </script>
</body>
</html>
