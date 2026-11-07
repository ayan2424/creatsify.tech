<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\ClientReview;
use Core\Request;
use Core\Response;

/**
 * Creatsify Admin Desk - Client Reviews Moderation Controller
 */
class ReviewsController
{
    public function index(Request $request): Response
    {
        $reviews = ClientReview::allAdmin();
        $stats = ClientReview::stats();

        return view('admin/reviews/index', [
            'reviews'    => $reviews,
            'stats'      => $stats,
            'page_title' => 'Client Reviews & Star Feedback',
        ]);
    }

    public function updateStatus(Request $request, string $id): Response
    {
        $status = trim((string)$request->input('status', 'approved'));
        ClientReview::updateStatus((int)$id, $status);
        flash('success', 'Review status updated to ' . ucfirst($status) . '.');
        return Response::redirect(url('/admin/reviews'));
    }

    public function toggleFeatured(Request $request, string $id): Response
    {
        $newState = ClientReview::toggleFeatured((int)$id);
        flash('success', $newState ? 'Review marked as featured on Homepage.' : 'Review removed from Homepage featured list.');
        return Response::redirect(url('/admin/reviews'));
    }

    public function delete(Request $request, string $id): Response
    {
        ClientReview::delete((int)$id);
        flash('success', 'Client review deleted.');
        return Response::redirect(url('/admin/reviews'));
    }
}
