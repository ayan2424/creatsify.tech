<?php
/** @var Core\View $this */
$this->layout('layouts/admin');
?>

<div class="card" style="max-width: 840px;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Publish New Work or Ready-Made Software</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 2px;">Unified studio publishing: choose whether to publish as a commercial ready-made software script or an agency client case study.</p>
        </div>
        <a href="<?= url('/admin/projects') ?>" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 6px; min-height: 36px; padding: 6px 12px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><span>Back to Projects</span></a>
    </div>

    <form action="<?= url('/admin/projects') ?>" method="POST">
        <?= csrf_field() ?>

        <!-- 1. Publication Channel & Offering Type -->
        <div class="form-group" style="background: color-mix(in srgb, var(--surface) 90%, black); padding: 18px; border-radius: 12px; border: 1px solid var(--line); margin-bottom: 24px;">
            <label style="font-weight: 700; font-size: 14px; margin-bottom: 8px; display: block; color: var(--text);">Publication Channel &amp; Offering Type</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px;">
                <label style="display: flex; gap: 10px; align-items: flex-start; cursor: pointer; padding: 12px 14px; border: 1px solid var(--line); border-radius: 10px; background: var(--bg);" id="typeSoftwareLabel">
                    <input type="radio" name="is_software" value="1" id="typeSoftwareRadio" checked style="margin-top: 3px;">
                    <div>
                        <strong style="color: var(--text); font-size: 13.5px; display: block;">Ready-Made Commercial Software</strong>
                        <span style="color: var(--muted); font-size: 11.5px; line-height: 1.4; display: block; margin-top: 2px;">
                            Shows in <b>Software Store (/software)</b> &amp; <b>Work Portfolio (/portfolio)</b> with Gumroad checkout &amp; pricing.
                        </span>
                    </div>
                </label>
                <label style="display: flex; gap: 10px; align-items: flex-start; cursor: pointer; padding: 12px 14px; border: 1px solid var(--line); border-radius: 10px; background: var(--bg);" id="typeCaseLabel">
                    <input type="radio" name="is_software" value="0" id="typeCaseRadio" style="margin-top: 3px;">
                    <div>
                        <strong style="color: var(--text); font-size: 13.5px; display: block;">Client Work &amp; Case Study</strong>
                        <span style="color: var(--muted); font-size: 11.5px; line-height: 1.4; display: block; margin-top: 2px;">
                            Delivered bespoke agency build. Shows exclusively in <b>Work Portfolio (/portfolio)</b> without marketplace buy buttons.
                        </span>
                    </div>
                </label>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="title">Project Title</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Cheetah Courier Suite" required>
            </div>
            <div class="form-group">
                <label for="slug">URL Slug (leave empty for auto)</label>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="cheetah-courier-suite">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category">Core Agency Category (The 7 Disciplines)</label>
                <select id="category" name="category" class="form-control">
                    <?php foreach (\App\Models\Project::CATEGORIES as $catKey => $catLabel): ?>
                        <option value="<?= e($catKey) ?>"><?= e($catLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="subcategory">Specialty / Industry Purpose (Displayed on Card Tag)</label>
                <input type="text" id="subcategory" name="subcategory" class="form-control" placeholder="e.g. Courier & Logistics Backbone, Restaurant POS">
                <div style="display: flex; gap: 6px; flex-wrap: wrap; margin-top: 6px;">
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 10.5px; padding: 2px 7px;" onclick="document.getElementById('subcategory').value='Courier & Logistics Backbone'">+ Courier &amp; Logistics</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 10.5px; padding: 2px 7px;" onclick="document.getElementById('subcategory').value='Restaurant POS & KDS'">+ Restaurant POS</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 10.5px; padding: 2px 7px;" onclick="document.getElementById('subcategory').value='Food Delivery Platform'">+ Food Delivery</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 10.5px; padding: 2px 7px;" onclick="document.getElementById('subcategory').value='Storefront Redesign & CRO'">+ E-Commerce CRO</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 10.5px; padding: 2px 7px;" onclick="document.getElementById('subcategory').value='Flutter Mobile Companion'">+ Flutter App</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 10.5px; padding: 2px 7px;" onclick="document.getElementById('subcategory').value='AI Search & Local Entity Authority'">+ SEO &amp; GEO Engine</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 10.5px; padding: 2px 7px;" onclick="document.getElementById('subcategory').value='Omnichannel Performance Ad Engine'">+ Performance Ads</button>
                    <button type="button" class="btn btn-secondary btn-sm" style="font-size: 10.5px; padding: 2px 7px;" onclick="document.getElementById('subcategory').value='Brand Identity & Design System'">+ UI/UX Branding</button>
                </div>
            </div>
        </div>

        <!-- Software-Specific Pricing & Gumroad Block -->
        <div id="softwareFields" style="background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.2); padding: 16px; border-radius: 10px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 12px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <strong style="color: #f59e0b; font-size: 13px;">Commercial Software License Settings</strong>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="price">Script Price (USD)</label>
                    <input type="text" id="price" name="price" class="form-control" placeholder="$99" value="$99">
                </div>
                <div class="form-group">
                    <label for="gumroad_url">Gumroad Purchase URL (Direct Buy)</label>
                    <input type="text" id="gumroad_url" name="gumroad_url" class="form-control" placeholder="https://gumroad.com/l/cheetah-courier">
                    <small style="color:var(--muted);font-size:11.5px;margin-top:4px;display:block;">Direct checkout URL on official Gumroad store.</small>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="live_url">Live Interactive Demo URL</label>
                <input type="text" id="live_url" name="live_url" class="form-control" placeholder="https://demo.creatsify.tech/platform">
            </div>
            <div class="form-group">
                <label for="client">Client / Target Industry</label>
                <input type="text" id="client" name="client" class="form-control" placeholder="Courier Operators, E-Commerce Brands">
            </div>
        </div>

        <div class="form-group">
            <label for="image_url">Showcase Artwork Image URL</label>
            <input type="text" id="image_url" name="image_url" class="form-control" placeholder="/assets/images/cheetah-showcase.jpg or upload in Media Library" value="/assets/images/hero-artwork.jpg">
            <small style="color:var(--muted);font-size:12px;margin-top:4px;display:block;">Tip: High-resolution 16:10 ratio artwork. E.g. /assets/images/cheetah-showcase.jpg, /assets/images/restromaster-preview.jpg, /assets/images/apex-preview.jpg.</small>
        </div>

        <div class="form-group">
            <label for="tech_stack">Tech Stack (comma separated)</label>
            <input type="text" id="tech_stack" name="tech_stack" class="form-control" placeholder="PHP 8.4, Next.js, Flutter, MySQL 8, Tailwind CSS">
        </div>

        <div class="form-group">
            <label for="summary">Short Teaser Summary</label>
            <textarea id="summary" name="summary" class="form-control" rows="2" placeholder="Brief 1-sentence value proposition..."></textarea>
        </div>

        <div class="form-group">
            <label for="description">Full Description &amp; Technical Specs</label>
            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Detailed architectural breakdown..."></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="status">Status Badge</label>
                <input type="text" id="status" name="status" class="form-control" value="Delivered">
            </div>
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="0">
            </div>
            <div class="form-group">
                <label for="featured">Featured On Homepage</label>
                <select id="featured" name="featured" class="form-control">
                    <option value="1">Yes (Featured)</option>
                    <option value="0">No (Standard Catalog)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; min-height: 42px; padding: 10px 24px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>Publish Project / Software</span>
            </button>
            <a href="<?= url('/admin/projects') ?>" class="btn btn-secondary" style="min-height: 42px; padding: 10px 20px;">Cancel</a>
        </div>
    </form>
</div>

<script>
    (function() {
        const swRadio = document.getElementById('typeSoftwareRadio');
        const csRadio = document.getElementById('typeCaseRadio');
        const swFields = document.getElementById('softwareFields');
        const statusInput = document.getElementById('status');

        function syncType() {
            if (swRadio.checked) {
                swFields.style.display = 'block';
                if (statusInput.value === 'Delivered') statusInput.value = 'For sale';
            } else {
                swFields.style.display = 'none';
                if (statusInput.value === 'For sale') statusInput.value = 'Delivered';
            }
        }

        if (swRadio && csRadio && swFields) {
            swRadio.addEventListener('change', syncType);
            csRadio.addEventListener('change', syncType);
            syncType();
        }
    })();
</script>
