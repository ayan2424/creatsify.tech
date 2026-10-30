<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Media Library', 'meta_title' => 'Media Library | Creatsify Admin']);

$totalCount = count($media ?? []);
$totalSizeBytes = array_sum(array_column($media ?? [], 'file_size'));
$totalMb = round($totalSizeBytes / (1024 * 1024), 2);
?>

<div class="content-header">
    <div>
        <h1 class="page-title">Media Library & Brand Assets</h1>
        <p class="page-subtitle">Central asset repository with drag &amp; drop uploads, instant previews, and 1-click clipboard URLs.</p>
    </div>
    <div style="display: flex; align-items: center; gap: 10px; font-size: 12px; flex-wrap: wrap;">
        <span class="user-role-badge" style="background: rgba(139,123,255,0.15); color: var(--adm-accent); font-weight: 700; padding: 6px 12px; border-radius: 8px;"><?= $totalCount ?> Assets</span>
        <span class="user-role-badge" style="padding: 6px 12px; border-radius: 8px;"><?= $totalMb ?> MB Storage</span>
    </div>
</div>

<div class="card">

    <!-- Cheezify-Style Drag & Drop Upload Zone -->
    <div id="mediaDropzone" style="margin-bottom: 25px; padding: 32px 20px; border: 2px dashed var(--line); border-radius: 14px; text-align: center; background: var(--bg); transition: all 0.25s ease; cursor: pointer;">
        <form action="<?= url('/admin/media/upload') ?>" method="POST" enctype="multipart/form-data" id="mediaUploadForm">
            <?= csrf_field() ?>
            <input type="file" name="file" id="mediaFileInput" style="display:none;" onchange="this.form.submit();" accept="image/*,.pdf,.mp4" required>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(139,123,255,0.12); display: flex; align-items: center; justify-content: center; color: var(--accent);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                </div>
                <div>
                    <strong style="color:var(--text); font-size:15px; display:block;">Drag &amp; Drop Assets Here, or Click to Browse</strong>
                    <span style="font-size:12px; color:var(--muted); margin-top:4px; display:block;">Supports high-res WebP, PNG, JPG, SVG, GIF &amp; PDF up to 25MB</span>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('mediaFileInput').click();" style="margin-top: 6px;">
                    Select File From Device &rarr;
                </button>
            </div>
        </form>
    </div>

    <!-- Media Filter Toolbar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; gap: 8px; flex: 1 1 260px; max-width: 380px;">
            <input type="text" id="mediaSearchInput" class="form-control" placeholder="Filter assets by name..." style="font-size: 13px; min-height: 38px;">
        </div>
        <span style="font-size: 12px; color: var(--muted);" id="visibleCountLabel">Showing all <?= $totalCount ?> files</span>
    </div>

    <!-- Media Grid -->
    <?php if (empty($media)): ?>
        <div style="padding: 50px 20px; text-align: center; color: var(--muted);">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="opacity: 0.4; margin-bottom: 10px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            <p style="margin: 0; font-size: 14px;">No media assets uploaded yet. Upload brand logos or project graphics above.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 18px;" id="mediaGrid">
            <?php foreach ($media as $item): ?>
                <?php 
                    $isImage = str_starts_with($item['mime_type'], 'image/'); 
                    $ext = strtoupper(pathinfo($item['filename'], PATHINFO_EXTENSION));
                ?>
                <div class="media-card-item" data-name="<?= strtolower(e($item['original_name'])) ?>">
                    <div style="height: 140px; background: #07080d; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                        <?php if ($isImage): ?>
                            <img src="<?= asset($item['path']) ?>" alt="<?= e($item['original_name']) ?>" loading="lazy" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        <?php else: ?>
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--muted);"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        <?php endif; ?>
                        <span style="position: absolute; top: 8px; right: 8px; font-size: 9.5px; padding: 2px 6px; border-radius: 4px; background: rgba(0,0,0,0.6); color: #fff; font-weight: 700; border: 1px solid rgba(255,255,255,0.1);"><?= e($ext) ?></span>
                    </div>

                    <div style="padding: 12px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <strong style="font-size: 12.5px; color: var(--text); display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= e($item['original_name']) ?>">
                                <?= e($item['original_name']) ?>
                            </strong>
                            <span style="font-size: 11px; color: var(--muted);"><?= round($item['file_size'] / 1024, 1) ?> KB</span>
                        </div>

                        <div style="display: flex; gap: 6px; margin-top: 12px;">
                            <button type="button" class="btn btn-secondary btn-sm" style="flex-grow:1; font-size: 11.5px; padding: 6px; min-height: 34px;" onclick="copyUrl('<?= asset($item['path']) ?>', this)">
                                Copy URL
                            </button>
                            <form action="<?= url('/admin/media/' . $item['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Delete this asset permanently?');" style="margin:0;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-secondary btn-sm" style="color: #ef4444; font-size: 14px; padding: 5px 10px; min-height: 34px;" title="Delete asset">&times;</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    // Drag & Drop Highlight
    const dropzone = document.getElementById('mediaDropzone');
    const fileInput = document.getElementById('mediaFileInput');
    if (dropzone && fileInput) {
        ['dragenter', 'dragover'].forEach(name => {
            dropzone.addEventListener(name, (e) => {
                e.preventDefault();
                dropzone.style.borderColor = 'var(--accent)';
                dropzone.style.background = 'rgba(139,123,255,0.06)';
            });
        });
        ['dragleave', 'drop'].forEach(name => {
            dropzone.addEventListener(name, (e) => {
                e.preventDefault();
                dropzone.style.borderColor = 'var(--line)';
                dropzone.style.background = 'var(--bg)';
            });
        });
        dropzone.addEventListener('drop', (e) => {
            if (e.dataTransfer && e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                document.getElementById('mediaUploadForm').submit();
            }
        });
    }

    // Instant Search Filter
    const searchInput = document.getElementById('mediaSearchInput');
    const cards = document.querySelectorAll('.media-card-item');
    const label = document.getElementById('visibleCountLabel');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const q = e.target.value.toLowerCase().trim();
            let count = 0;
            cards.forEach(c => {
                const name = c.dataset.name || '';
                if (!q || name.includes(q)) {
                    c.style.display = '';
                    count++;
                } else {
                    c.style.display = 'none';
                }
            });
            if (label) label.textContent = 'Showing ' + count + ' of ' + cards.length + ' files';
        });
    }

    // Clipboard Copy with Animated Pill Feedback & Robust Fallback
    function copyUrl(url, btn) {
        function showSuccess() {
            const original = btn.textContent;
            btn.textContent = '✓ Copied!';
            btn.style.color = '#00c853';
            btn.style.borderColor = '#00c853';
            setTimeout(() => {
                btn.textContent = original;
                btn.style.color = '';
                btn.style.borderColor = '';
            }, 2200);
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(showSuccess).catch(fallbackCopy);
        } else {
            fallbackCopy();
        }

        function fallbackCopy() {
            const ta = document.createElement('textarea');
            ta.value = url;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            try {
                document.execCommand('copy');
                showSuccess();
            } catch(e) {}
            document.body.removeChild(ta);
        }
    }
</script>
