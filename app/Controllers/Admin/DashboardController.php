<?php

namespace App\Controllers\Admin;

use App\Models\AnalyticsVisit;
use App\Models\ChatMessage;
use App\Models\ChatSession;
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

        return view('admin/dashboard/index', [
            'stats' => $stats,
            'recentInquiries' => $recentInquiries,
            'activeChats' => array_slice($activeChats, 0, 5),
            'unreadCount' => $unreadCount,
            'totalProjects' => $totalProjects,
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
