<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\ClientReview;
use Core\Request;
use Core\Response;

/**
 * Creatsify Digital Agency - Public Client Reviews & Feedback Controller
 */
class ReviewsController
{
    public function index(Request $request): Response
    {
        $activeCategory = trim((string)($request->get('category') ?: $request->get('cat', 'all')));
        $serviceCategories = [
            'Web Development',
            'Mobile App Development',
            'Website Redesign & UI/UX',
            'SaaS & Custom Software',
            'SEO & Social Media Ads',
            'Branding & Graphic Design',
            'White-Label Customization',
        ];
        if ($activeCategory !== 'all' && !in_array($activeCategory, $serviceCategories, true)) {
            $activeCategory = 'all';
        }

        $reviews = ClientReview::allApproved($activeCategory);
        $stats = ClientReview::stats();

        return view('reviews/index', [
            'reviews'         => $reviews,
            'stats'           => $stats,
            'activeCategory'  => $activeCategory,
            'currentCategory' => $activeCategory,
            'categories'      => $serviceCategories,
            'meta_title'      => 'Client Reviews & Verified Testimonials | ' . setting('site_name', 'Creatsify'),
            'meta_desc'       => 'Read verified client reviews and star ratings for Creatsify Digital Agency across custom web development, Flutter mobile apps, website redesign, branding, SEO, and social media ads.',
        ]);
    }

    public function store(Request $request): Response
    {
        // Honeypot anti-bot check
        if (!empty($request->input('_hp_check'))) {
            flash('success', 'Thank you! Your review has been received.');
            return Response::redirect(url('/reviews'));
        }

        // Rate limit: max 5 submissions per 30 minutes per session
        $rlKey = 'review_rl_' . md5($request->getIp());
        $now = time();
        $history = session()->get($rlKey, []);
        if (is_array($history)) {
            $history = array_filter($history, static fn($ts) => ($now - (int)$ts) < 1800);
            if (count($history) >= 5) {
                flash('error', 'You have submitted several reviews recently. Please wait a few minutes before submitting another.');
                return Response::redirect(url('/reviews#submit-review'));
            }
            $history[] = $now;
            session()->set($rlKey, $history);
        }

        $clientName = trim(strip_tags((string)$request->input('client_name', '')));
        $clientEmail = trim(strip_tags((string)$request->input('client_email', '')));
        $clientRole = trim(strip_tags((string)$request->input('client_role', '')));
        $companyName = trim(strip_tags((string)$request->input('company_name', '')));
        $serviceCategory = trim(strip_tags((string)$request->input('service_category', 'Web Development')));
        $rating = max(1, min(5, (int)$request->input('rating', 5)));
        $reviewTitle = trim(strip_tags((string)$request->input('review_title', '')));
        $reviewText = trim(strip_tags((string)$request->input('review_text', '')));
        $outcomeMetric = trim(strip_tags((string)$request->input('outcome_metric', '')));

        if (mb_strlen($clientName) < 2 || mb_strlen($reviewTitle) < 4 || mb_strlen($reviewText) < 15) {
            flash('error', 'Please enter your name, a short headline, and your detailed feedback (at least 15 characters).');
            return Response::redirect(url('/reviews#submit-review'));
        }

        $allowedCategories = [
            'Web Development',
            'Mobile App Development',
            'Website Redesign & UI/UX',
            'SaaS & Custom Software',
            'SEO & Social Media Ads',
            'Branding & Graphic Design',
            'White-Label Customization',
        ];
        if (!in_array($serviceCategory, $allowedCategories, true)) {
            $serviceCategory = 'Web Development';
        }

        $status = ($rating >= 4) ? 'approved' : 'pending';

        ClientReview::create([
            'client_name'      => mb_substr($clientName, 0, 120),
            'client_email'     => mb_substr($clientEmail, 0, 150),
            'client_role'      => mb_substr($clientRole ?: 'Verified Client', 0, 120),
            'company_name'     => mb_substr($companyName, 0, 150),
            'service_category' => $serviceCategory,
            'rating'           => $rating,
            'review_title'     => mb_substr($reviewTitle, 0, 200),
            'review_text'      => mb_substr($reviewText, 0, 2000),
            'outcome_metric'   => mb_substr($outcomeMetric ?: 'Verified Delivery', 0, 120),
            'is_verified'      => 1,
            'is_featured'      => ($rating === 5) ? 1 : 0,
            'status'           => $status,
            'ip_address'       => $request->getIp(),
        ]);

        // Log in-app notification for Admin Desk
        try {
            db()->insert('notifications', [
                'type'       => 'review',
                'title'      => "New {$rating}★ Client Review from {$clientName}",
                'message'    => "{$reviewTitle} ({$serviceCategory})",
                'link'       => '/admin/reviews',
                'is_read'    => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
        }

        if ($status === 'approved') {
            flash('success', 'Thank you for your feedback! Your verified review is now live.');
        } else {
            flash('success', 'Thank you for your feedback! Your review has been submitted to our quality desk.');
        }

        return Response::redirect(url('/reviews#reviews-grid'));
    }
}
