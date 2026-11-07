<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Client Reviews & Star Feedback', 'meta_title' => 'Client Reviews | Creatsify Admin']);
?>

<div class="content-header">
    <div>
        <h1 class="page-title">Client Reviews &amp; Star Feedback</h1>
        <p class="page-subtitle">Moderate client reviews, approve incoming feedback, and toggle featured reviews for the Homepage.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <div style="padding:8px 14px;border-radius:10px;background:rgba(16,185,129,0.12);color:#10b981;font-weight:700;font-size:13px;">
            &#9733; <?= number_format((float)($stats['avg_rating'] ?? 4.9), 1) ?> Avg Rating (<?= (int)($stats['total'] ?? 0) ?> Approved)
        </div>
        <a href="<?= url('/reviews') ?>" target="_blank" class="btn btn-secondary" style="min-height: 40px; padding: 8px 16px; display: inline-flex; align-items: center; gap: 6px;">
            <span>Public /reviews Page</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        </a>
    </div>
</div>

<!-- Status Filter Bar -->
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;">
    <?php foreach (['all' => 'All Reviews', 'approved' => 'Approved', 'pending' => 'Pending Moderation', 'rejected' => 'Rejected'] as $stKey => $stLabel): ?>
        <a href="<?= url('/admin/reviews' . ($stKey === 'all' ? '' : '?status=' . $stKey)) ?>" class="btn btn-sm <?= ($statusFilter ?? 'all') === $stKey ? 'btn-primary' : 'btn-secondary' ?>" style="padding:7px 14px;border-radius:999px;">
            <?= e($stLabel) ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Client &amp; Service</th>
                    <th>Rating &amp; Review</th>
                    <th>Outcome Receipt</th>
                    <th>Homepage Featured</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center;padding:36px;color:var(--muted);">No reviews found matching this filter.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($reviews as $rev):
                    $stars = max(1, min(5, (int)($rev['rating'] ?? 5)));
                    $status = $rev['status'] ?? 'pending';
                ?>
                    <tr>
                        <td>
                            <strong style="color:var(--text);font-size:14px;"><?= e($rev['client_name']) ?></strong>
                            <div style="font-size:12px;color:var(--muted);">
                                <?= e($rev['client_role'] ?: 'Client') ?><?= !empty($rev['company_name']) ? ' &bull; ' . e($rev['company_name']) : '' ?>
                            </div>
                            <span style="display:inline-block;margin-top:4px;font-size:11px;padding:2px 8px;border-radius:999px;background:rgba(37,99,235,0.12);color:#3b82f6;font-weight:600;">
                                <?= e($rev['service_category']) ?>
                            </span>
                        </td>
                        <td>
                            <div style="color:#f59e0b;font-size:13px;letter-spacing:1px;">
                                <?= str_repeat('&#9733;', $stars) ?><?= str_repeat('&#9734;', 5 - $stars) ?>
                            </div>
                            <strong style="display:block;font-size:13px;color:var(--text);margin:2px 0;"><?= e($rev['review_title']) ?></strong>
                            <div style="font-size:12px;color:var(--muted);max-width:380px;">
                                <?= e($rev['review_text']) ?>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($rev['outcome_metric'])): ?>
                                <span style="font-size:11.5px;padding:4px 10px;border-radius:8px;background:rgba(16,185,129,0.12);color:#10b981;font-weight:700;">
                                    <?= e($rev['outcome_metric']) ?>
                                </span>
                            <?php else: ?>
                                <span style="color:var(--muted);font-size:12px;">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="<?= url('/admin/reviews/' . $rev['id'] . '/toggle-featured') ?>" method="POST" style="margin:0;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm" style="font-size:11px;padding:4px 10px;border-radius:12px;<?= !empty($rev['is_featured']) ? 'background:rgba(245,158,11,0.15);color:#f59e0b;border:1px solid rgba(245,158,11,0.35);font-weight:700;' : 'background:rgba(163,178,199,0.1);color:var(--muted);border:1px solid var(--line);' ?>">
                                    <?= !empty($rev['is_featured']) ? '★ Featured' : '☆ Standard' ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="<?= url('/admin/reviews/' . $rev['id'] . '/status') ?>" method="POST" style="margin:0;display:inline-flex;gap:6px;align-items:center;">
                                <?= csrf_field() ?>
                                <select name="status" onchange="this.form.submit()" class="form-control" style="padding:4px 8px;font-size:12px;height:32px;min-width:110px;">
                                    <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </form>
                        </td>
                        <td style="text-align:right;white-space:nowrap;">
                            <form action="<?= url('/admin/reviews/' . $rev['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this review permanently?');" style="margin:0;display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-secondary btn-sm" style="color:#ef4444;min-height:34px;padding:5px 12px;">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
