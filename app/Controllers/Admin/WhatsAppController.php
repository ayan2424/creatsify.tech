<?php

namespace App\Controllers\Admin;

use App\Helpers\WhatsAppHelper;
use App\Models\WhatsAppMessage;
use Core\Request;
use Core\Response;

/**
 * Creatsify Studio - Two-Way WhatsApp Inbox Controller (Cheetah Architecture)
 */
class WhatsAppController
{
    public function index(Request $request): Response
    {
        $conversations = WhatsAppMessage::recentConversations();
        $selectedPhone = $request->get('phone', $conversations[0]['contact_phone'] ?? '');

        $messages = [];
        if (!empty($selectedPhone)) {
            $messages = WhatsAppMessage::forContact($selectedPhone);
        }

        return view('admin/whatsapp/index', [
            'conversations' => $conversations,
            'selectedPhone' => $selectedPhone,
            'messages' => $messages,
            'meta_title' => 'WhatsApp Inbox | Creatsify Admin'
        ]);
    }

    public function send(Request $request): Response
    {
        $phone = trim((string)$request->input('phone', ''));
        $message = trim((string)$request->input('message', ''));

        if (empty($phone) || empty($message)) {
            flash('error', 'Phone number and message cannot be empty.');
            return Response::redirect('/admin/whatsapp');
        }

        $helper = new WhatsAppHelper();
        $res = $helper->sendMessage($phone, $message);

        if ($res['success']) {
            flash('success', 'WhatsApp message dispatched successfully via Meta Cloud API!');
        } else {
            flash('error', 'Failed to dispatch: ' . ($res['error'] ?? 'Unknown error. Check Meta API credentials in Settings.'));
        }

        return Response::redirect('/admin/whatsapp?phone=' . urlencode($phone));
    }
}
