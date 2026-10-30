<?php
/** @var Core\View $this */
$this->layout('layouts/admin', ['page_title' => 'Add New Client', 'meta_title' => 'New Client | Creatsify Admin']);
?>
<div class="content-header">
    <div>
        <a href="<?= url('/admin/crm/clients') ?>" class="btn btn-secondary btn-sm" style="margin-bottom: 10px; display: inline-flex; align-items: center; gap: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg><span>Back to Clients Pipeline</span>
        </a>
        <h1 class="page-title">Add New CRM Client File</h1>
        <p class="page-subtitle">Register a prospective lead, enterprise account, or ongoing commercial client.</p>
    </div>
</div>

<div class="card" style="max-width: 900px;">
    <div class="card-header" style="border-bottom: 1px solid var(--adm-line);">
        <h3 class="card-title">Client File & Commercial Scope</h3>
        <?php if (!empty($inquiry)): ?>
            <span class="user-role-badge">Imported from Inquiry #<?= $inquiry['id'] ?></span>
        <?php endif; ?>
    </div>

    <form action="<?= url('/admin/crm/clients') ?>" method="POST" style="padding: 24px;">
        <?= csrf_field() ?>
        <?php if (!empty($inquiry)): ?>
            <input type="hidden" name="inquiry_id" value="<?= $inquiry['id'] ?>">
        <?php endif; ?>

        <!-- 1. Contact & Identity -->
        <div class="crm-form-section">
            <h4 class="crm-form-section-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>1. Contact & Identity</span>
            </h4>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="name">Contact Person / Principal Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Tariq Rahim" value="<?= e($inquiry['name'] ?? '') ?>" required>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="company">Company / Organization / Brand</label>
                    <input type="text" id="company" name="company" class="form-control" placeholder="e.g. Express Linehaul Logistics">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="email">Direct Email Address <span style="color:#ef4444;">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="client@example.com" value="<?= e($inquiry['email'] ?? '') ?>" required>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="phone">Direct Phone</label>
                    <input type="text" id="phone" name="phone" class="form-control" placeholder="+92 300 1234567" value="<?= e($inquiry['phone'] ?? '') ?>">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="whatsapp">WhatsApp Number</label>
                    <input type="text" id="whatsapp" name="whatsapp" class="form-control" placeholder="923001234567" value="<?= e(!empty($inquiry['phone']) ? clean_phone_number($inquiry['phone'], false) : '') ?>">
                </div>
            </div>
        </div>

        <!-- 2. Pipeline & Commercials -->
        <div class="crm-form-section">
            <h4 class="crm-form-section-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span>2. Pipeline & Commercial Terms</span>
            </h4>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="stage">Pipeline Stage</label>
                    <select id="stage" name="stage" class="form-control">
                        <option value="lead">Lead (Initial Contact)</option>
                        <option value="discovery">Discovery (Call / Scoping)</option>
                        <option value="proposal_sent">Proposal Sent (Quotation)</option>
                        <option value="contract_signed">Contract Signed</option>
                        <option value="active_client">Active Client (In Execution)</option>
                        <option value="retained">Retained (Monthly Architecture SLA)</option>
                        <option value="churned">Churned / Lost</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="deal_value">Estimated Contract Value</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="currency" class="form-control" style="width: 95px; flex-shrink: 0;">
                            <option value="USD">USD</option>
                            <option value="PKR">PKR</option>
                            <option value="EUR">EUR</option>
                            <option value="AED">AED</option>
                            <option value="GBP">GBP</option>
                        </select>
                        <input type="number" step="0.01" id="deal_value" name="deal_value" class="form-control" placeholder="5000.00" value="<?= e(preg_replace('/[^0-9.]/', '', $inquiry['budget'] ?? '0')) ?>">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="source">Acquisition Source</label>
                    <select id="source" name="source" class="form-control">
                        <option value="Website Inquiry">Website Inquiry</option>
                        <option value="WhatsApp Inbound">WhatsApp Inbound</option>
                        <option value="Direct Live Chat">Direct Live Chat</option>
                        <option value="Gumroad Direct Store">Gumroad Direct Store</option>
                        <option value="Client Referral">Client Referral</option>
                        <option value="Direct Outreach">Direct Outreach</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. Location & Web Presence -->
        <div class="crm-form-section">
            <h4 class="crm-form-section-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                <span>3. Location & Web Presence</span>
            </h4>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" class="form-control" placeholder="e.g. Karachi / Dubai / London">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" class="form-control" placeholder="e.g. Pakistan">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="website">Company Website</label>
                    <input type="url" id="website" name="website" class="form-control" placeholder="https://example.com">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="tags">Industry Tags / Product Focus (Comma separated)</label>
                <input type="text" id="tags" name="tags" class="form-control" placeholder="e.g. Cheetah, Logistics, SaaS, Mobile App, Stripe" value="<?= e($inquiry['project_type'] ?? '') ?>">
            </div>
        </div>

        <!-- 4. Project Scope Briefing -->
        <div class="crm-form-section">
            <h4 class="crm-form-section-title">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span>4. Initial Project Summary & Commercial Scope</span>
            </h4>

            <div class="form-group" style="margin-bottom: 0;">
                <textarea id="notes_summary" name="notes_summary" rows="4" class="form-control" placeholder="Brief technical requirements, client background, budget constraints..."><?= e($inquiry['message'] ?? '') ?></textarea>
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center; border-top: 1px solid var(--adm-line); padding-top: 20px;">
            <a href="<?= url('/admin/crm/clients') ?>" class="btn btn-secondary" style="min-height: 42px; padding: 10px 20px;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; min-height: 42px; padding: 10px 26px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                <span>Save Client Record</span>
            </button>
        </div>
    </form>
</div>
