<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\NotificationDispatcher;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Core\Request;
use Core\Response;

/**
 * Creatsify Digital Agency - Native Intelligent Live Chat Engine
 */
class ChatController
{
    public function send(Request $request): Response
    {
        $token = trim((string)$request->input('token', ''));
        $rawMessage = (string)$request->input('message', '');
        $message = trim($rawMessage);
        $rawName = trim((string)$request->input('name', 'Visitor'));
        $name = htmlspecialchars(strip_tags($rawName), ENT_QUOTES, 'UTF-8');
        if (empty($name) || mb_strlen($name) > 80) {
            $name = 'Visitor';
        }
        $email = trim((string)$request->input('email', ''));

        if (empty($token) || empty($message)) {
            return Response::json(['success' => false, 'error' => 'Token and message required.'], 422);
        }

        if (mb_strlen($message) > 1000) {
            return Response::json(['success' => false, 'error' => 'Message exceeds maximum length of 1,000 characters.'], 422);
        }

        // Flood / Rate Limiting (max 10 messages per minute per IP + token)
        $rateKey = 'chat_rl_' . md5($request->getIp() . '_' . $token);
        $now = time();
        $history = session()->get($rateKey, []);
        if (is_array($history)) {
            $history = array_filter($history, static fn($ts) => ($now - (int)$ts) < 60);
            if (count($history) >= 10) {
                return Response::json(['success' => false, 'error' => 'Rate limit exceeded. Please wait a moment before sending another message.'], 429);
            }
            $history[] = $now;
            session()->set($rateKey, $history);
        }

        $session = ChatSession::findOrCreate($token, $request->getIp(), $name, $email);
        ChatMessage::add((int)$session['id'], 'visitor', $name, $message);

        if ((int)$session['is_human_handoff'] === 1) {
            return Response::json([
                'success' => true,
                'session' => $session,
                'handoff' => true,
                'message' => 'Message delivered to Creatsify live support.',
            ]);
        }

        if ($this->isHumanHandoffRequest($message)) {
            ChatSession::setHandoff((int)$session['id'], true);

            $dispatcher = new NotificationDispatcher();
            $dispatcher->dispatchChatHandoff($session, $message);

            $siteMail = setting('site_email', 'support@creatsify.tech');
            $reply = "I've connected this conversation directly with our **Senior Agency Engineering & Strategy Desk**. A specialist will respond here shortly. You can also reach us via WhatsApp, email us at {$siteMail}, or submit your project brief on our Contact page.";
            ChatMessage::add((int)$session['id'], 'bot', 'Assistant', $reply);

            return Response::json([
                'success' => true,
                'handoff' => true,
                'reply'   => $reply,
            ]);
        }

        $botReply = $this->getLocalChatResponse($message);
        ChatMessage::add((int)$session['id'], 'bot', 'Assistant', $botReply);

        return Response::json([
            'success' => true,
            'reply'   => $botReply,
            'handoff' => false,
        ]);
    }

    public function messages(Request $request): Response
    {
        $token = trim((string)$request->get('token', ''));
        if (empty($token)) {
            return Response::json(['success' => false, 'messages' => []]);
        }

        $session = ChatSession::findOrCreate($token, $request->getIp());
        $messages = ChatMessage::forSession((int)$session['id'], 100);

        return Response::json([
            'success'  => true,
            'session'  => $session,
            'messages' => $messages,
        ]);
    }

    public function poll(Request $request): Response
    {
        $token = trim((string)$request->get('token', ''));
        $lastId = (int)$request->get('last_id', 0);

        if (empty($token)) {
            return Response::json(['success' => false, 'messages' => []]);
        }

        $session = ChatSession::findOrCreate($token, $request->getIp());
        $messages = db()->fetchAll(
            "SELECT * FROM `chat_messages` WHERE `session_id` = :sid AND `id` > :lid ORDER BY `id` ASC",
            ['sid' => $session['id'], 'lid' => $lastId]
        );

        return Response::json([
            'success'    => true,
            'messages'   => $messages,
            'is_handoff' => (bool)$session['is_human_handoff'],
        ]);
    }

    private function isHumanHandoffRequest(string $message): bool
    {
        $m = strtolower($message);
        return (bool)preg_match('/\b(talk\s+to(\s+a)?\s+human|speak\s+(with|to)(\s+a)?\s+human|real\s+person|live\s+agent|human\s+support|human\s+agent|human\s+please|connect\s+with\s+(a\s+)?human|talk\s+to\s+specialist|call\s+me|phone\s+number|whatsapp\s+me|operator|support\s+rep)\b/i', $m);
    }

    private function getLocalChatResponse(string $userMsg): string
    {
        $m = strtolower($userMsg);

        if (preg_match('/\b(hi|hello|hey|salam|assalam|aoa)\b/', $m)) {
            return "Hello! Welcome to **Creatsify Digital Agency**. We specialize in **Custom Web & App Development** (PHP, Node.js, Next.js, Flutter), **Website Redesign**, **Branding & Logo Design**, **Technical SEO & Paid Social Ads**, and **Customizable Ready-to-Ship Software**. How can we help scale your business today?";
        }

        if (str_contains($m, 'seo') || str_contains($m, 'marketing') || str_contains($m, 'ads') || str_contains($m, 'social media') || str_contains($m, 'google') || str_contains($m, 'meta')) {
            return "Our **Digital Growth & Performance Marketing Division** offers:\n"
                 . "• **Technical & On-Page SEO**: Core Web Vitals speed optimization, semantic keyword architecture, schema markup, and organic ranking growth.\n"
                 . "• **Paid Social & Search Ads**: High-ROAS campaigns across Meta (Facebook & Instagram), Google Ads, and TikTok with full pixel/CAPI tracking.\n"
                 . "• **Social Media Marketing**: Content strategy, custom visual creatives, and audience growth.\n\n"
                 . "Explore our Growth Packages on the homepage or request a free SEO & Ads teardown!";
        }

        if (str_contains($m, 'logo') || str_contains($m, 'brand') || str_contains($m, 'graphic') || str_contains($m, 'redesign') || str_contains($m, 'ui') || str_contains($m, 'ux')) {
            return "Our **Creative Design & UI/UX Division** handles:\n"
                 . "• **Custom Logo & Brand Identity**: Master vector logos, color & typography guidelines, and brand books.\n"
                 . "• **Website Redesign & Modernization**: Transforming slow, outdated websites into fast, mobile-first conversion engines.\n"
                 . "• **Graphic Design & Ad Kits**: Social media templates, pitch decks, and high-CTR ad creatives.";
        }

        if (str_contains($m, 'package') || str_contains($m, 'price') || str_contains($m, 'cost') || str_contains($m, 'quote') || str_contains($m, 'rate') || str_contains($m, 'budget')) {
            return "We offer transparent **Agency Service Packages** across 3 tracks:\n"
                 . "1. **Web, App & SaaS Development**: From **$299** (Launchpad Website/Redesign) and **$699** (Custom Full-Stack Web App) to **$1,499+** (SaaS & Flutter Mobile Suite).\n"
                 . "2. **Branding, Logo & UI/UX Redesign**: From **$149** (Logo & Identity Starter) and **$349** (Complete Brand & Social Kit) to **$599** (Full UI/UX Website Redesign).\n"
                 . "3. **SEO & Social Media Ads**: From **$249/mo** (SEO Foundation) and **$399/mo** (Paid Ads Growth) to **$749/mo** (Omnichannel SEO + Ads).\n"
                 . "4. **Ready-to-Launch Software**: Instant licenses from **$49–$99** (or custom tailored for your brand).\n\n"
                 . "Scroll to the **Packages** section on our homepage to configure your scope!";
        }

        if (str_contains($m, 'stack') || str_contains($m, 'technology') || str_contains($m, 'php') || str_contains($m, 'node') || str_contains($m, 'flutter') || str_contains($m, 'next')) {
            return "Our engineering & creative stack covers:\n"
                 . "• **Backend & DB**: PHP 8.4, Node.js, MySQL, PostgreSQL, REST & GraphQL APIs\n"
                 . "• **Frontend**: Next.js, HTML5/CSS3, JavaScript ES6, Tailwind CSS, Bootstrap, jQuery\n"
                 . "• **Mobile Apps**: Flutter & Dart (iOS & Android)\n"
                 . "• **Growth & Design**: Technical SEO, Meta/Google/TikTok Ads, Figma UI/UX, Vector Brand Design.";
        }

        if (str_contains($m, 'product') || str_contains($m, 'ready') || str_contains($m, 'white label') || str_contains($m, 'cheetah') || str_contains($m, 'cheezify') || str_contains($m, 'software')) {
            return "In addition to our custom agency services, we offer **Ready-to-Launch Commercial Software** (Courier & Logistics Enterprise, Courier Lite, and Multi-Branch Food/Retail POS). You can either:\n"
                 . "1. **Buy a Direct License ($49–$99)** with 100% unlocked source code for immediate deployment, OR\n"
                 . "2. **Customize It For Your Business** — tell us what changes, branding, or features you want, and our team will tailor the entire platform for you!";
        }

        if (str_contains($m, 'review') || str_contains($m, 'feedback') || str_contains($m, 'rating') || str_contains($m, 'testimonial')) {
            return "Creatsify holds a **4.9 / 5.0 ★ rating** from verified clients across Web Development, Flutter Mobile Apps, Website Redesign, Branding, and SEO/Social Ads. You can read all client reviews or submit your own feedback at **/reviews**!";
        }

        return "Thanks for reaching out to **Creatsify Digital Agency**! Whether you need a custom Web or Flutter Mobile App, a Website Redesign, Branding & Logo Design, SEO & Social Media Ads, or a customized Ready-to-Launch software platform, we're ready to help. Type **'talk to a human'** to alert our live specialist desk!";
    }
}
