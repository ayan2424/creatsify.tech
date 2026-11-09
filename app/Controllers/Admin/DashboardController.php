<?php

namespace App\Controllers\Admin;

use App\Models\AnalyticsVisit;
use App\Models\AgencyProject;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\CrmClient;
use App\Models\CrmTicket;
use App\Models\CrmNote;
use App\Models\Inquiry;
use App\Models\Notification;
use App\Models\Project;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Admin Dashboard & Analytics Controller
 */
class DashboardController
{
    public function index(Request $request): Response
    {
        // 0. Ensure all schemas and admin credentials exist
        try {
            \App\Database\SchemaMigrator::ensureAll();
        } catch (\Throwable $e) {}

        // 1. Analytics & Visits
        $stats = [];
        try {
            $stats = AnalyticsVisit::getStats();
        } catch (\Throwable $e) {}

        // 2. Inquiries
        $recentInquiries = [];
        try {
            $recentInquiries = db()->fetchAll("SELECT * FROM `inquiries` ORDER BY id DESC LIMIT 5");
        } catch (\Throwable $e) {}

        // 3. Active Chat Sessions
        $activeChats = [];
        try {
            $activeChats = ChatSession::allActiveWithUnread();
        } catch (\Throwable $e) {}

        // 4. Notifications
        $unreadCount = 0;
        try {
            $unreadCount = Notification::countUnread();
        } catch (\Throwable $e) {}

        // 5. Total Projects
        $totalProjects = 0;
        try {
            $totalProjects = count(Project::all());
        } catch (\Throwable $e) {}

        // 6. Measure live database query round-trip latency
        $t0 = microtime(true);
        $dbLatencyMs = 2;
        try {
            db()->fetchValue("SELECT 1");
            $dbLatencyMs = max(1, round((microtime(true) - $t0) * 1000, 1));
        } catch (\Throwable $e) {}

        // 7. System Telemetries
        $tableCount = 0;
        try {
            $tableCount = count(db()->fetchAll("SHOW TABLES"));
        } catch (\Throwable $e) {}

        $memoryBytes = memory_get_usage(true);
        $memoryUsageMb = round($memoryBytes / 1024 / 1024, 1);
        $memoryLimitStr = ini_get('memory_limit') ?: '128M';
        $memoryLimitMb = (int)$memoryLimitStr ?: 128;
        $memoryPercent = min(100, max(5, round(($memoryUsageMb / $memoryLimitMb) * 100)));

        // 8. Recent 7 days breakdown for dynamic chart
        $dailyTrend = [];
        $trendMap = [];
        if (!empty($stats['trend'])) {
            foreach ($stats['trend'] as $tr) {
                $trendMap[$tr['visit_date']] = (int)$tr['hits'];
            }
        }
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $dailyTrend[] = [
                'date' => $d,
                'day' => date('D', strtotime($d)),
                'hits' => $trendMap[$d] ?? 0
            ];
        }

        // 9. Conversion & Lead counts
        $totalInquiries = 0;
        $newInquiries = 0;
        try {
            $totalInquiries = (int)db()->fetchValue("SELECT COUNT(*) FROM `inquiries`");
            $newInquiries = (int)db()->fetchValue("SELECT COUNT(*) FROM `inquiries` WHERE `status` = 'new'");
        } catch (\Throwable $e) {}

        // 10. CRM Telemetry
        $crmStageMeta = [
            'counts' => [
                'all' => 0, 'lead' => 0, 'discovery' => 0, 'proposal_sent' => 0, 
                'contract_signed' => 0, 'active_client' => 0, 'retained' => 0, 'churned' => 0
            ], 
            'total_value' => 0.0
        ];
        try {
            $crmStageMeta = CrmClient::stageCounts();
        } catch (\Throwable $e) {}

        $openTicketsCount = 0;
        try {
            $openTicketsCount = (int)db()->fetchValue("SELECT COUNT(*) FROM `crm_tickets` WHERE `status` IN ('open', 'in_progress', 'waiting_client')");
        } catch (\Throwable $e) {}

        $recentTickets = [];
        try {
            $recentTickets = db()->fetchAll("SELECT t.*, c.company as client_company FROM `crm_tickets` t LEFT JOIN `crm_clients` c ON c.id = t.client_id ORDER BY t.id DESC LIMIT 4");
        } catch (\Throwable $e) {}

        $pendingFollowUps = [];
        try {
            $pendingFollowUps = CrmNote::pendingFollowUps();
        } catch (\Throwable $e) {}

        $deliveryMetrics = ['counts' => [], 'open_tasks' => 0, 'overdue_tasks' => 0];
        try {
            $deliveryMetrics = AgencyProject::metrics();
        } catch (\Throwable $e) {}

        $readyMadeProducts = [];
        try {
            $readyMadeProducts = Project::readyMadeProducts();
        } catch (\Throwable $e) {}

        return view('admin/dashboard/index', [
            'stats' => $stats,
            'recentInquiries' => $recentInquiries,
            'activeChats' => array_slice($activeChats, 0, 5),
            'unreadCount' => $unreadCount,
            'totalProjects' => $totalProjects,
            'readyMadeProducts' => $readyMadeProducts,
            'dbLatencyMs' => $dbLatencyMs,
            'tableCount' => $tableCount,
            'memoryUsageMb' => $memoryUsageMb,
            'memoryLimitMb' => $memoryLimitMb,
            'memoryPercent' => $memoryPercent,
            'dailyTrend' => $dailyTrend,
            'totalInquiries' => $totalInquiries,
            'newInquiries' => $newInquiries,
            'crmStageCounts' => $crmStageMeta['counts'] ?? [],
            'totalPipelineValue' => $crmStageMeta['total_value'] ?? 0.0,
            'openTicketsCount' => $openTicketsCount,
            'recentTickets' => $recentTickets,
            'pendingFollowUps' => $pendingFollowUps,
            'deliveryMetrics' => $deliveryMetrics,
            'meta_title' => 'Dashboard | Creatsify Admin'
        ]);
    }

    /**
     * Poll endpoint for in-app bell counter & live audio chimes
     */
    public function poll(Request $request): Response
    {
        $unreadNotifs = Notification::countUnread();
        $unreadChatMsgs = ChatMessage::countUnread();
        $newInquiries = Inquiry::countByStatus('new');

        return Response::json([
            'success' => true,
            'unread_notifications' => $unreadNotifs,
            'unread_chat_messages' => $unreadChatMsgs,
            'new_inquiries' => $newInquiries,
            'has_alert' => ($unreadChatMsgs > 0 || $newInquiries > 0)
        ]);
    }
}
