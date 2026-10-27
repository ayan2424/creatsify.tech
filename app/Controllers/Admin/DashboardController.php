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
        $stats = AnalyticsVisit::getStats();
        $recentInquiries = db()->fetchAll("SELECT * FROM `inquiries` ORDER BY id DESC LIMIT 5");
        $activeChats = ChatSession::allActiveWithUnread();
        $unreadCount = Notification::countUnread();
        $totalProjects = count(Project::all());

        // Measure live database query round-trip latency
        $t0 = microtime(true);
        db()->fetchValue("SELECT 1");
        $dbLatencyMs = max(1, round((microtime(true) - $t0) * 1000, 1));

        // Real system telemetries
        $tableCount = count(db()->fetchAll("SHOW TABLES"));
        $memoryBytes = memory_get_usage(true);
        $memoryUsageMb = round($memoryBytes / 1024 / 1024, 1);
        $memoryLimitStr = ini_get('memory_limit') ?: '128M';
        $memoryLimitMb = (int)$memoryLimitStr ?: 128;
        $memoryPercent = min(100, max(5, round(($memoryUsageMb / $memoryLimitMb) * 100)));

        // Recent 7 days breakdown for real dynamic chart
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

        // Conversion & Lead counts
        $totalInquiries = (int)db()->fetchValue("SELECT COUNT(*) FROM `inquiries`");
        $newInquiries = (int)db()->fetchValue("SELECT COUNT(*) FROM `inquiries` WHERE `status` = 'new'");

        // CRM Telemetry
        $crmStageMeta = CrmClient::stageCounts();
        $openTicketsCount = (int)db()->fetchValue("SELECT COUNT(*) FROM `crm_tickets` WHERE `status` IN ('open', 'in_progress', 'waiting_client')");
        $recentTickets = db()->fetchAll("SELECT t.*, c.company as client_company FROM `crm_tickets` t LEFT JOIN `crm_clients` c ON c.id = t.client_id ORDER BY t.id DESC LIMIT 4");
        $pendingFollowUps = CrmNote::pendingFollowUps();
        $deliveryMetrics = AgencyProject::metrics();

        return view('admin/dashboard/index', [
            'stats' => $stats,
            'recentInquiries' => $recentInquiries,
            'activeChats' => array_slice($activeChats, 0, 5),
            'unreadCount' => $unreadCount,
            'totalProjects' => $totalProjects,
            'dbLatencyMs' => $dbLatencyMs,
            'tableCount' => $tableCount,
            'memoryUsageMb' => $memoryUsageMb,
            'memoryLimitMb' => $memoryLimitMb,
            'memoryPercent' => $memoryPercent,
            'dailyTrend' => $dailyTrend,
            'totalInquiries' => $totalInquiries,
            'newInquiries' => $newInquiries,
            'crmStageCounts' => $crmStageMeta['counts'],
            'totalPipelineValue' => $crmStageMeta['total_value'],
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
