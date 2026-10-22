<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Media Library & Brand Assets</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">Upload images, logos, banners, and documents with instant 1-click URL copying.</p>
        </div>
    </div>

    <!-- Drag & Drop / Direct Uploader Form -->
    <form action="<?= url('/admin/media/upload') ?>" method="POST" enctype="multipart/form-data" style="margin-bottom: 30px; padding: 25px; border: 2px dashed var(--line); border-radius: 12px; text-align: center; background: var(--bg);">
        <?= csrf_field() ?>
        <input type="file" name="file" id="mediaFileInput" style="display:none;" onchange="this.form.submit();" required>
        <label for="mediaFileInput" style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 10px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--accent);"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            <strong style="color:var(--text); font-size:15px;">Click to select or drop files here to upload</strong>
            <span style="font-size:12px; color:var(--muted);">PNG, JPG, SVG, WebP, GIF, PDF up to 25MB</span>
        </label>
    </form>

    <!-- Media Grid -->
    <?php if (empty($media)): ?>
        <p style="padding: 40px; text-align: center; color: var(--muted);">No media assets uploaded yet. Upload images above to use them in sections, projects, or blog posts.</p>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            <?php foreach ($media as $item): ?>
                <?php $isImage = str_starts_with($item['mime_type'], 'image/'); ?>
                <div style="background: var(--bg); border: 1px solid var(--line); border-radius: 10px; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="height: 140px; background: #0c0d13; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                        <?php if ($isImage): ?>
                            <img src="<?= asset($item['path']) ?>" alt="<?= e($item['original_name']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        <?php else: ?>
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--muted);"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        <?php endif; ?>
                    </div>

                    <div style="padding: 12px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <strong style="font-size: 13px; color: var(--text); display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= e($item['original_name']) ?>">
                                <?= e($item['original_name']) ?>
                            </strong>
                            <span style="font-size: 11px; color: var(--muted);"><?= round($item['file_size'] / 1024, 1) ?> KB</span>
                        </div>

                        <div style="display: flex; gap: 6px; margin-top: 12px;">
                            <button type="button" class="btn btn-secondary btn-sm" style="flex-grow:1;" onclick="copyUrl('<?= asset($item['path']) ?>', this)">
                                Copy URL
                            </button>
                            <form action="<?= url('/admin') ?>/media/<?= $item['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete this asset permanently?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--danger);" title="Delete asset">&times;</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function copyUrl(url, btn) {
        navigator.clipboard.writeText(url).then(() => {
            const original = btn.textContent;
            btn.textContent = 'Copied!';
            btn.style.color = 'var(--accent)';
            setTimeout(() => {
                btn.textContent = original;
                btn.style.color = '';
            }, 2000);
        });
    }
</script>
