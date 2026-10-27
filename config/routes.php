<?php

/**
 * Creatsify Studio - Application Routes Configuration
 * Zero-framework high-speed routing table
 */

use Core\Router;

if (!isset($router)) {
    return;
}

/** @var Router $router */

// --------------------------------------------------------------------------
// Public Frontend & Visitor Routes (with Live Analytics middleware)
// --------------------------------------------------------------------------
$router->group(['middleware' => ['analytics']], function (Router $r) {
    $r->get('/', [\App\Controllers\HomeController::class, 'index']);
    $r->get('/portfolio', [\App\Controllers\PortfolioController::class, 'index']);
    $r->get('/portfolio/{slug}', [\App\Controllers\PortfolioController::class, 'show']);
    $r->get('/services', [\App\Controllers\ServicesController::class, 'index']);
    $r->get('/team', [\App\Controllers\TeamController::class, 'index']);
    $r->get('/team/{slug}', [\App\Controllers\TeamController::class, 'show']);
    $r->get('/about', [\App\Controllers\AboutController::class, 'index']);
    $r->get('/blog', [\App\Controllers\BlogController::class, 'index']);
    $r->get('/blog/{slug}', [\App\Controllers\BlogController::class, 'show']);
    $r->get('/contact', [\App\Controllers\ContactController::class, 'index']);
    $r->post('/contact', [\App\Controllers\ContactController::class, 'submit'], ['csrf']);
    $r->get('/support', [\App\Controllers\SupportController::class, 'index']);
    $r->post('/support', [\App\Controllers\SupportController::class, 'submit'], ['csrf']);
    $r->get('/sitemap.xml', [\App\Controllers\SitemapController::class, 'index']);
});

// --------------------------------------------------------------------------
// Public API Endpoints (Chat Engine, Contact AJAX, Meta Webhooks)
// --------------------------------------------------------------------------
$router->post('/api/contact', [\App\Controllers\ContactController::class, 'submit']);
$router->post('/api/support', [\App\Controllers\SupportController::class, 'submit']);
$router->post('/api/chat/send', [\App\Controllers\ChatController::class, 'send']);
$router->get('/api/chat/messages', [\App\Controllers\ChatController::class, 'messages']);
$router->get('/api/chat/poll', [\App\Controllers\ChatController::class, 'poll']);

// WhatsApp Cloud API Webhook Handshake (GET) & Incoming Messages (POST)
$router->get('/api/webhooks/whatsapp', [\App\Controllers\WebhookController::class, 'whatsapp']);
$router->post('/api/webhooks/whatsapp', [\App\Controllers\WebhookController::class, 'whatsapp']);

// --------------------------------------------------------------------------
// Admin Authentication Routes
// --------------------------------------------------------------------------
$router->get('/admin/login', [\App\Controllers\Admin\AuthController::class, 'showLogin']);
$router->post('/admin/login', [\App\Controllers\Admin\AuthController::class, 'login'], ['csrf']);
if (env('DEMO_MODE', false) === true) {
    $router->get('/admin/demo-login', [\App\Controllers\Admin\AuthController::class, 'demoLogin']);
}
$router->post('/admin/logout', [\App\Controllers\Admin\AuthController::class, 'logout'], ['csrf']);

// --------------------------------------------------------------------------
// Protected Admin Panel Desk (Requires Authenticate, CSRF & Demo Protection Middleware)
// --------------------------------------------------------------------------
$router->group(['prefix' => '/admin', 'middleware' => ['auth', 'csrf', 'demo']], function (Router $r) {
    $r->group(['middleware' => ['role:super_admin,admin']], function (Router $r) {
    // Dashboard & Live System Poll
    $r->get('/', [\App\Controllers\Admin\DashboardController::class, 'index']);
    $r->get('/poll', [\App\Controllers\Admin\DashboardController::class, 'poll']);

    // Live Chat Desk (Cheetah Architecture)
    $r->get('/chat', [\App\Controllers\Admin\ChatController::class, 'index']);
    $r->post('/chat/reply', [\App\Controllers\Admin\ChatController::class, 'reply']);
    $r->get('/chat/fetch', [\App\Controllers\Admin\ChatController::class, 'fetchMessages']);
    $r->post('/chat/{id}/close', [\App\Controllers\Admin\ChatController::class, 'close']);

    // Communication Inboxes (WhatsApp & SMS)
    $r->get('/whatsapp', [\App\Controllers\Admin\WhatsAppController::class, 'index']);
    $r->post('/whatsapp/send', [\App\Controllers\Admin\WhatsAppController::class, 'send']);
    $r->get('/sms', [\App\Controllers\Admin\SmsController::class, 'index']);
    $r->post('/sms/send', [\App\Controllers\Admin\SmsController::class, 'send']);
    });

    $r->group(['middleware' => ['role:super_admin,admin,editor']], function (Router $r) {
    // Homepage Section Builder (Cheezify Architecture)
    $r->get('/sections', [\App\Controllers\Admin\SectionsController::class, 'index']);
    $r->get('/sections/{key}/edit', [\App\Controllers\Admin\SectionsController::class, 'edit']);
    $r->post('/sections/{key}', [\App\Controllers\Admin\SectionsController::class, 'update']);
    $r->post('/sections/{key}/toggle', [\App\Controllers\Admin\SectionsController::class, 'toggle']);

    // Agency Products & Systems (Cheetah, RestroMaster, etc.)
    $r->get('/projects', [\App\Controllers\Admin\ProjectsController::class, 'index']);
    $r->get('/projects/create', [\App\Controllers\Admin\ProjectsController::class, 'create']);
    $r->post('/projects', [\App\Controllers\Admin\ProjectsController::class, 'store']);
    $r->get('/projects/{id}/edit', [\App\Controllers\Admin\ProjectsController::class, 'edit']);
    $r->post('/projects/{id}', [\App\Controllers\Admin\ProjectsController::class, 'update']);
    $r->post('/projects/{id}/toggle-featured', [\App\Controllers\Admin\ProjectsController::class, 'toggleFeatured']);
    $r->post('/projects/{id}/delete', [\App\Controllers\Admin\ProjectsController::class, 'delete']);

    // Agency Team & Member Portfolios
    $r->get('/team', [\App\Controllers\Admin\TeamController::class, 'index']);
    $r->get('/team/create', [\App\Controllers\Admin\TeamController::class, 'create']);
    $r->post('/team', [\App\Controllers\Admin\TeamController::class, 'store']);
    $r->get('/team/{id}/edit', [\App\Controllers\Admin\TeamController::class, 'edit']);
    $r->post('/team/{id}', [\App\Controllers\Admin\TeamController::class, 'update']);
    $r->post('/team/{id}/toggle', [\App\Controllers\Admin\TeamController::class, 'toggle']);
    $r->post('/team/{id}/delete', [\App\Controllers\Admin\TeamController::class, 'delete']);

    // Individual Member Portfolios
    $r->get('/team/{id}/portfolio', [\App\Controllers\Admin\TeamController::class, 'portfolio']);
    $r->get('/team/{id}/portfolio/create', [\App\Controllers\Admin\TeamController::class, 'createPortfolio']);
    $r->post('/team/{id}/portfolio', [\App\Controllers\Admin\TeamController::class, 'storePortfolio']);
    $r->get('/team/{memberId}/portfolio/{itemId}/edit', [\App\Controllers\Admin\TeamController::class, 'editPortfolio']);
    $r->post('/team/{memberId}/portfolio/{itemId}', [\App\Controllers\Admin\TeamController::class, 'updatePortfolio']);
    $r->post('/team/{memberId}/portfolio/{itemId}/toggle-featured', [\App\Controllers\Admin\TeamController::class, 'togglePortfolioFeatured']);
    $r->post('/team/{memberId}/portfolio/{itemId}/delete', [\App\Controllers\Admin\TeamController::class, 'deletePortfolio']);

    // Services
    $r->get('/services', [\App\Controllers\Admin\ServicesController::class, 'index']);
    $r->get('/services/create', [\App\Controllers\Admin\ServicesController::class, 'create']);
    $r->post('/services', [\App\Controllers\Admin\ServicesController::class, 'store']);
    $r->get('/services/{id}/edit', [\App\Controllers\Admin\ServicesController::class, 'edit']);
    $r->post('/services/{id}', [\App\Controllers\Admin\ServicesController::class, 'update']);
    $r->post('/services/{id}/toggle', [\App\Controllers\Admin\ServicesController::class, 'toggle']);
    $r->post('/services/{id}/delete', [\App\Controllers\Admin\ServicesController::class, 'delete']);

    // Blog Posts
    $r->get('/posts', [\App\Controllers\Admin\PostsController::class, 'index']);
    $r->get('/posts/create', [\App\Controllers\Admin\PostsController::class, 'create']);
    $r->post('/posts', [\App\Controllers\Admin\PostsController::class, 'store']);
    $r->get('/posts/{id}/edit', [\App\Controllers\Admin\PostsController::class, 'edit']);
    $r->post('/posts/{id}', [\App\Controllers\Admin\PostsController::class, 'update']);
    $r->post('/posts/{id}/toggle', [\App\Controllers\Admin\PostsController::class, 'toggle']);
    $r->post('/posts/{id}/delete', [\App\Controllers\Admin\PostsController::class, 'delete']);
    });

    $r->group(['middleware' => ['role:super_admin,admin']], function (Router $r) {
    // Inquiries
    $r->get('/inquiries', [\App\Controllers\Admin\InquiriesController::class, 'index']);
    $r->get('/inquiries/{id}', [\App\Controllers\Admin\InquiriesController::class, 'show']);
    $r->post('/inquiries/{id}/status', [\App\Controllers\Admin\InquiriesController::class, 'updateStatus']);
    $r->post('/inquiries/{id}/delete', [\App\Controllers\Admin\InquiriesController::class, 'delete']);
    $r->post('/inquiries/{id}/convert-to-client', [\App\Controllers\Admin\CrmController::class, 'convertInquiry']);

    // Agency CRM Suite (Clients, Deals, Support Tickets & Internal Notes)
    $r->get('/crm/clients', [\App\Controllers\Admin\CrmController::class, 'clients']);
    $r->get('/crm/clients/create', [\App\Controllers\Admin\CrmController::class, 'createClient']);
    $r->post('/crm/clients', [\App\Controllers\Admin\CrmController::class, 'storeClient']);
    $r->get('/crm/clients/{id}', [\App\Controllers\Admin\CrmController::class, 'showClient']);
    $r->get('/crm/clients/{id}/edit', [\App\Controllers\Admin\CrmController::class, 'editClient']);
    $r->post('/crm/clients/{id}', [\App\Controllers\Admin\CrmController::class, 'updateClient']);
    $r->post('/crm/clients/{id}/stage', [\App\Controllers\Admin\CrmController::class, 'updateClientStage']);
    $r->post('/crm/clients/{id}/delete', [\App\Controllers\Admin\CrmController::class, 'deleteClient']);

    // Support & Project Tickets
    $r->get('/crm/tickets', [\App\Controllers\Admin\CrmController::class, 'tickets']);
    $r->get('/crm/tickets/create', [\App\Controllers\Admin\CrmController::class, 'createTicket']);
    $r->post('/crm/tickets', [\App\Controllers\Admin\CrmController::class, 'storeTicket']);
    $r->get('/crm/tickets/{id}', [\App\Controllers\Admin\CrmController::class, 'showTicket']);
    $r->post('/crm/tickets/{id}/status', [\App\Controllers\Admin\CrmController::class, 'updateTicketStatus']);
    $r->post('/crm/tickets/{id}/reply', [\App\Controllers\Admin\CrmController::class, 'replyTicket']);
    $r->post('/crm/tickets/{id}/delete', [\App\Controllers\Admin\CrmController::class, 'deleteTicket']);

    // Internal Notes & Reminders
    $r->get('/crm/notes', [\App\Controllers\Admin\CrmController::class, 'notes']);
    $r->post('/crm/notes', [\App\Controllers\Admin\CrmController::class, 'storeNote']);
    $r->post('/crm/notes/{id}/toggle-pin', [\App\Controllers\Admin\CrmController::class, 'toggleNotePin']);
    $r->post('/crm/notes/{id}/toggle-followup', [\App\Controllers\Admin\CrmController::class, 'toggleFollowUp']);
    $r->post('/crm/notes/{id}/delete', [\App\Controllers\Admin\CrmController::class, 'deleteNote']);

    // Internal agency delivery workspace (not public products or a client portal)
    $r->get('/agency-projects', [\App\Controllers\Admin\AgencyProjectsController::class, 'index']);
    $r->get('/agency-projects/create', [\App\Controllers\Admin\AgencyProjectsController::class, 'create']);
    $r->post('/agency-projects', [\App\Controllers\Admin\AgencyProjectsController::class, 'store']);
    $r->get('/agency-projects/{id}', [\App\Controllers\Admin\AgencyProjectsController::class, 'show']);
    $r->post('/agency-projects/{id}/status', [\App\Controllers\Admin\AgencyProjectsController::class, 'updateStatus']);
    $r->post('/agency-projects/{id}/members', [\App\Controllers\Admin\AgencyProjectsController::class, 'assignMember']);
    $r->post('/agency-projects/{id}/members/{memberId}/remove', [\App\Controllers\Admin\AgencyProjectsController::class, 'removeMember']);
    $r->post('/agency-projects/{id}/milestones', [\App\Controllers\Admin\AgencyProjectsController::class, 'storeMilestone']);
    $r->post('/agency-projects/{id}/milestones/{milestoneId}/status', [\App\Controllers\Admin\AgencyProjectsController::class, 'updateMilestoneStatus']);
    $r->post('/agency-projects/{id}/tasks', [\App\Controllers\Admin\AgencyProjectsController::class, 'storeTask']);
    $r->post('/agency-projects/{id}/tasks/{taskId}/status', [\App\Controllers\Admin\AgencyProjectsController::class, 'updateTaskStatus']);
    $r->post('/agency-projects/{id}/notes', [\App\Controllers\Admin\AgencyProjectsController::class, 'storeNote']);
    });

    $r->group(['middleware' => ['role:super_admin']], function (Router $r) {
    // Users, Roles & Security Audit Logs (Cheezify Architecture)
    $r->get('/users', [\App\Controllers\Admin\UsersController::class, 'index']);
    $r->get('/users/create', [\App\Controllers\Admin\UsersController::class, 'create']);
    $r->post('/users', [\App\Controllers\Admin\UsersController::class, 'store']);
    $r->get('/users/{id}/edit', [\App\Controllers\Admin\UsersController::class, 'edit']);
    $r->post('/users/{id}', [\App\Controllers\Admin\UsersController::class, 'update']);
    $r->post('/users/{id}/delete', [\App\Controllers\Admin\UsersController::class, 'delete']);
    $r->get('/audit-logs', [\App\Controllers\Admin\UsersController::class, 'auditLogs']);
    });

    $r->group(['middleware' => ['role:super_admin,admin,editor']], function (Router $r) {
    // Media Library (Cheezify Architecture)
    $r->get('/media', [\App\Controllers\Admin\MediaController::class, 'index']);
    $r->post('/media/upload', [\App\Controllers\Admin\MediaController::class, 'upload']);
    $r->post('/media/{id}/delete', [\App\Controllers\Admin\MediaController::class, 'delete']);
    });

    $r->group(['middleware' => ['role:super_admin']], function (Router $r) {
    // Platform Settings, Theme Customizer & Custom Code
    $r->get('/settings', [\App\Controllers\Admin\SettingsController::class, 'index']);
    $r->post('/settings', [\App\Controllers\Admin\SettingsController::class, 'update']);
    $r->post('/settings/test-email', [\App\Controllers\Admin\SettingsController::class, 'testEmail']);
    });
});
