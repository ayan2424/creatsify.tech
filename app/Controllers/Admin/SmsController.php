<?php

namespace App\Controllers\Admin;

use App\Helpers\SmsHelper;
use App\Models\SmsMessage;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Two-Way SMS Inbox Controller (Cheetah Architecture)
 */
class SmsController
{
    public function index(Request $request): Response
    {
        $conversations = SmsMessage::recentConversations();
        $selectedPhone = $request->get('phone', $conversations[0]['phone_number'] ?? '');

        $messages = [];
        if (!empty($selectedPhone)) {
            $messages = SmsMessage::forContact($selectedPhone);
        }

        return view('admin/sms/index', [
            'conversations' => $conversations,
            'selectedPhone' => $selectedPhone,
            'messages' => $messages,
            'meta_title' => 'SMS Inbox | Creatsify Admin'
        ]);
    }

    public function send(Request $request): Response
    {
        $phone = trim((string)$request->input('phone', ''));
        $message = trim((string)$request->input('message', ''));

        if (empty($phone) || empty($message)) {
            flash('error', 'Phone number and message cannot be empty.');
            return Response::redirect('/admin/sms');
        }

        $helper = new SmsHelper();
        $res = $helper->sendSms($phone, $message);

        if ($res['success']) {
            flash('success', 'SMS dispatched successfully via gateway!');
        } else {
            flash('error', 'SMS failed: ' . ($res['error'] ?? 'Check gateway configuration.'));
        }

        return Response::redirect('/admin/sms?phone=' . urlencode($phone));
    }
}
