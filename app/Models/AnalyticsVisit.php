<?php

namespace App\Models;

/**
 * Creatsify Studio - Live Analytics Engine
 */
class AnalyticsVisit
{
    public static function log(string $path, string $ip, ?string $referrer, ?string $userAgent): void
    {
        // Ignore static asset requests or admin asset polls
        if (str_starts_with($path, '/assets') || str_starts_with($path, '/api/admin/poll')) {
            return;
        }

        $device = 'desktop';
        if ($userAgent) {
            $ua = strtolower($userAgent);
            if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
                $device = 'mobile';
            } elseif (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
                $device = 'tablet';
            }
        }

        try {
            db()->insert('analytics_visits', [
                'ip_address' => $ip,
                'path' => substr($path, 0, 255),
                'referrer' => $referrer ? substr($referrer, 0, 255) : null,
                'user_agent' => $userAgent ? substr($userAgent, 0, 255) : null,
                'device_type' => $device,
                'country' => 'PK',
                'visited_at' => date('Y-m-d H:i:s')
            ]);
        } catch (\Throwable $e) {
            // Silently ignore if DB table not yet created
        }
    }

    public static function getStats(): array
    {
        $today = date('Y-m-d 00:00:00');
        $thisWeek = date('Y-m-d 00:00:00', strtotime('-7 days'));

        $total = (int)db()->fetchValue("SELECT COUNT(*) FROM `analytics_visits`");
        $todayVisits = (int)db()->fetchValue("SELECT COUNT(*) FROM `analytics_visits` WHERE `visited_at` >= :t", ['t' => $today]);
        $uniqueVisitors = (int)db()->fetchValue("SELECT COUNT(DISTINCT `ip_address`) FROM `analytics_visits`");

        // Top pages
        $topPages = db()->fetchAll("SELECT `path`, COUNT(*) as hits FROM `analytics_visits` GROUP BY `path` ORDER BY hits DESC LIMIT 5");

        // Device breakdown
        $devices = db()->fetchAll("SELECT `device_type`, COUNT(*) as count FROM `analytics_visits` GROUP BY `device_type`");

        // Recent 7 days trend
        $trend = db()->fetchAll(
            "SELECT DATE(visited_at) as visit_date, COUNT(*) as hits 
             FROM analytics_visits 
             WHERE visited_at >= :w 
             GROUP BY DATE(visited_at) 
             ORDER BY visit_date ASC",
            ['w' => $thisWeek]
        );

        return [
            'total_visits' => $total,
            'today_visits' => $todayVisits,
            'unique_visitors' => $uniqueVisitors,
            'top_pages' => $topPages,
            'devices' => $devices,
            'trend' => $trend
        ];
    }
}
