<div class="content-header">
    <div>
        <a href="<?= url('/admin/crm/clients') ?>" class="btn btn-secondary btn-sm" style="margin-bottom: 8px;">&larr; Back to Clients</a>
        <h1 class="page-title">Add New CRM Client File</h1>
        <p class="page-subtitle">Register a prospective lead, enterprise account, or ongoing commercial client.</p>
    </div>
</div>

<div class="card" style="max-width: 860px;">
    <div class="card-header">
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

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="name">Contact Person / Principal Name <span style="color:#ef4444;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Tariq Rahim" value="<?= e($inquiry['name'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="company">Company / Organization / Brand</label>
                <input type="text" id="company" name="company" class="form-control" placeholder="e.g. Express Linehaul Logistics">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="email">Direct Email Address <span style="color:#ef4444;">*</span></label>
                <input type="email" id="email" name="email" class="form-control" placeholder="client@example.com" value="<?= e($inquiry['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Direct Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" placeholder="+92 300 1234567" value="<?= e($inquiry['phone'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="whatsapp">WhatsApp Number</label>
                <input type="text" id="whatsapp" name="whatsapp" class="form-control" placeholder="923001234567" value="<?= e(!empty($inquiry['phone']) ? clean_phone_number($inquiry['phone'], false) : '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 20px;">
            <div class="form-group">
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

            <div class="form-group">
                <label for="deal_value">Estimated Deal / Contract Value</label>
                <div style="display: flex; gap: 8px;">
                    <select name="currency" class="form-control" style="width: 90px; flex-shrink: 0;">
                        <option value="USD">USD</option>
                        <option value="PKR">PKR</option>
                        <option value="EUR">EUR</option>
                        <option value="AED">AED</option>
                        <option value="GBP">GBP</option>
                    </select>
                    <input type="number" step="0.01" id="deal_value" name="deal_value" class="form-control" placeholder="5000.00" value="<?= e(preg_replace('/[^0-9.]/', '', $inquiry['budget'] ?? '0')) ?>">
                </div>
            </div>

            <div class="form-group">
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

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 20px;">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" class="form-control" placeholder="e.g. Karachi / Dubai / London">
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" class="form-control" placeholder="e.g. Pakistan">
            </div>

            <div class="form-group">
                <label for="website">Company Website</label>
                <input type="url" id="website" name="website" class="form-control" placeholder="https://example.com">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label for="tags">Industry Tags / Product Focus (Comma separated)</label>
            <input type="text" id="tags" name="tags" class="form-control" placeholder="e.g. Cheetah, Logistics, SaaS, Mobile App, Stripe" value="<?= e($inquiry['project_type'] ?? '') ?>">
        </div>

        <div class="form-group" style="margin-bottom: 26px;">
            <label for="notes_summary">Initial Project Summary & Commercial Scope</label>
            <textarea id="notes_summary" name="notes_summary" rows="4" class="form-control" placeholder="Brief technical requirements, client background, budget constraints..."><?= e($inquiry['message'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid var(--adm-line); padding-top: 20px;">
            <a href="<?= url('/admin/crm/clients') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                Save Client Record &rarr;
            </button>
        </div>
    </form>
</div>
