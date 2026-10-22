<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Client Inquiries & Project Leads</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Prospective clients requesting quotes or consultations.</p>
        </div>
    </div>

    <?php if (empty($inquiries)): ?>
        <p style="padding: 30px; text-align: center; color: var(--muted);">No project inquiries yet.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Project Scope</th>
                    <th>Phone / WhatsApp</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inquiries as $inq): ?>
                    <tr>
                        <td>
                            <strong style="color:var(--text);"><?= e($inq['name']) ?></strong>
                            <div style="font-size:12px; color:var(--muted);"><?= e($inq['email']) ?></div>
                        </td>
                        <td><?= e($inq['project_type']) ?></td>
                        <td>
                            <?php if (!empty($inq['phone'])): ?>
                                <a href="<?= url('/admin') ?>/whatsapp?phone=<?= urlencode($inq['phone']) ?>" style="color: #25D366; text-decoration: none; font-weight: 600;">
                                    <?= e($inq['phone']) ?> &rarr;
                                </a>
                            <?php else: ?>
                                <span style="color:var(--muted);">&mdash;</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="user-role-badge"><?= strtoupper(e($inq['status'])) ?></span>
                        </td>
                        <td style="font-size:12px; color:var(--muted);"><?= date('M j, Y H:i', strtotime($inq['created_at'])) ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="<?= url('/admin') ?>/inquiries/<?= $inq['id'] ?>" class="btn btn-secondary btn-sm">Inspect</a>
                                <form action="<?= url('/admin') ?>/inquiries/<?= $inq['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this inquiry?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--danger);">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
