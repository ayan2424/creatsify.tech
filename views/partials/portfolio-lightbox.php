<!-- Enhanced Portfolio Quick Preview Lightbox Modal -->
<div id="portfolioLightboxModal" class="ag-lightbox-modal" aria-hidden="true" role="dialog" aria-label="Project Preview">
    <div class="ag-lightbox-backdrop"></div>
    <div class="ag-lightbox-dialog">
        <!-- Modal Top Bar -->
        <div class="ag-lightbox-header">
            <div class="ag-lightbox-header-left">
                <span class="ag-lightbox-badge" id="lightboxBadge">Web Design &amp; Development</span>
                <span class="ag-case-tag-sw" id="lightboxSoftwareBadge" style="display:none;">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    <span id="lightboxPriceLabel">Ready-Made Software &bull; $99</span>
                </span>
                <h3 class="ag-lightbox-title" id="lightboxTitle">Project Preview</h3>
            </div>
            <button type="button" class="ag-lightbox-close" aria-label="Close Preview">&times;</button>
        </div>

        <!-- Modal Visual Preview & Details -->
        <div class="ag-lightbox-body">
            <div class="ag-lightbox-img-wrap">
                <img src="" alt="" id="lightboxImg" class="ag-lightbox-img" loading="eager">
            </div>

            <div class="ag-lightbox-meta-panel" id="lightboxMetaPanel">
                <p class="ag-lightbox-desc" id="lightboxDesc"></p>
                <div class="ag-lightbox-tech-wrap" id="lightboxTechWrap">
                    <span class="ag-lightbox-tech-label">Stack:</span>
                    <div class="ag-lightbox-tech-pills" id="lightboxTechPills"></div>
                </div>
            </div>
        </div>

        <!-- Modal Footer Actions (Category & Type Aware) -->
        <div class="ag-lightbox-footer">
            <div class="ag-lightbox-footer-info">
                <span id="lightboxClientName" style="color:rgba(255,255,255,0.6);font-size:12.5px;font-weight:600;"></span>
            </div>
            <div class="ag-lightbox-footer-btns">
                <!-- 1. Live Preview Website Link (for live websites/demos) -->
                <a href="#" id="lightboxLiveLink" target="_blank" rel="noopener noreferrer" class="ag-btn-modal-live" style="display:none;" title="Visit Live Website">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    <span>Live Preview</span>
                </a>

                <!-- 2. Buy License / Buy Now Button (for ready-made software/scripts) -->
                <a href="#" id="lightboxBuyLink" target="_blank" rel="noopener noreferrer" class="ag-btn-modal-buy" style="display:none;" title="Direct License Purchase">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    <span id="lightboxBuyLabel">Buy License ($99)</span>
                </a>

                <!-- 3. More Details Case Study Link -->
                <a href="#" id="lightboxCaseLink" class="ag-btn-modal-case" title="View In-Depth Technical Case Study">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span>More Details</span>
                </a>
            </div>
        </div>
    </div>
</div>
