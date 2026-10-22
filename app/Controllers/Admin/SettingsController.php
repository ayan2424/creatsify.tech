<?php

namespace App\Controllers\Admin;

use App\Helpers\Mailer;
use App\Models\Setting;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Platform Settings, Visual Theme, & Custom Code Controller
 */
class SettingsController
{
    public function index(Request $request): Response
    {
        $settings = Setting::all();

        return view('admin/settings/index', [
            'settings' => $settings,
            'tab' => $request->get('tab', 'general'),
            'meta_title' => 'Studio Settings & Theme Customizer | Creatsify Admin'
        ]);
    }

    public function update(Request $request): Response
    {
        $group = trim((string)$request->input('_group', 'general'));
        $data = $request->all();

        // Filter internal tokens
        unset($data['_csrf_token'], $data['_group'], $data['_method']);

        Setting::bulkUpdate($data, $group);

        audit_log('update_settings', 'settings', null, "Updated settings for group: {$group}");
        flash('success', "Settings for '" . ucfirst($group) . "' saved successfully!");

        return Response::redirect('/admin/settings?tab=' . urlencode($group));
    }

    /**
     * Send Test Diagnostic Email via SMTP
     */
    public function testEmail(Request $request): Response
    {
        $to = trim((string)$request->input('test_email', ''));
        if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please enter a valid recipient email address.');
            return Response::redirect('/admin/settings?tab=mail');
        }

        $mailer = new Mailer();
        $ok = $mailer->send(
            $to,
            "Creatsify Studio - SMTP Test Verification",
            "<p>Congratulations! Your SMTP configuration on <strong>Creatsify Studio</strong> is active and delivering emails flawlessly.</p><p>Timestamp: " . date('Y-m-d H:i:s T') . "</p>"
        );

        if ($ok) {
            flash('success', "Test email successfully dispatched to {$to}!");
        } else {
            flash('error', "Failed to send test email. Please check your SMTP host, port, and credentials in the Mail tab.");
        }

        return Response::redirect('/admin/settings?tab=mail');
    }
}
