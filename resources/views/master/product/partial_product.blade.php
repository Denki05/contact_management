<style>
    /* ======================================================
       KANVAS UTAMA (LIGHT MODE) - V11 MASTERPIECE
    ====================================================== */
    .product-light-canvas {
        --bg:            #ffffff;
        --surface:       #f8f9fa;
        --surface-2:     #f1f3f5;
        --border:        #e5e7eb;
        --border-dark:   #d1d5db;
        --text-main:     #1f2937;
        --text-sub:      #4b5563;
        --accent-blue:   #0d6efd;
        --overlay-grad:  linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 60%);

        background-color: var(--bg);
        color: var(--text-main);
        border-radius: 16px;
        margin-top: 16px;
        padding: 8px;
        height: calc(100vh - 110px);
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        display: flex;
        flex-direction: column;
    }

    .tap-effect { transition: all 0.1s ease; cursor: pointer; }
    .tap-effect:active { transform: scale(0.97); }
    .fade-in { animation: fadeIn 0.2s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

    /* ======================================================
       NAVIGASI ATAS
    ====================================================== */
    .top-nav-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        padding: 0 4px;
    }

    .nav-group {
        display: flex;
        background: var(--surface-2);
        padding: 2px;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .nav-item {
        padding: 3px 12px;
        font-size: 10px;
        font-weight: 700;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        color: var(--text-sub);
        text-transform: uppercase;
        border: none;
        background: transparent;
    }
    .nav-item.active { background: var(--accent-blue); color: white; }

    /* ======================================================
       FILTER SECTION
    ====================================================== */
    .filter-section {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 4px 8px;
        margin-bottom: 6px;
    }

    .filter-grid {
        display: grid;
        /* Gunakan minmax agar responsif otomatis */
        grid-template-columns: repeat(4, 1fr); 
        gap: 8px;
        align-items: center;
        width: 100%;
    }

    /* Saat di layar kecil (Mobile), tumpuk filter ke bawah */
    @media (max-width: 768px) {
        .filter-grid {
            grid-template-columns: 1fr; /* Jadi 1 kolom */
        }
    }

    .filter-item { width: 100%; min-width: 0; }
    .filter-item label { display: none !important; }
    .select2-container { width: 100% !important; display: block; }

    .select2-container--default .select2-selection--single {
        height: 28px !important;
        border: 1px solid var(--border-dark) !important;
        border-radius: 5px !important;
        font-size: 10px;
        background: #fcfcfc;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px !important; 
        padding-left: 6px !important; 
        font-weight: 700;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        display: block;
        width: 100%; 
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 24px !important;
    }

    /* ======================================================
       CANVAS CONTAINER & ASSETS
    ====================================================== */
    .canvas-container {
        flex: 1;
        overflow-y: auto;
        background: #fff;
        border-radius: 10px;
        border: 1px solid var(--border);
        padding: 8px;
    }
    .canvas-container::-webkit-scrollbar { width: 5px; }
    .canvas-container::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }

    .empty-state {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; height: 100%; color: var(--text-sub); text-align: center;
    }
    .empty-state i { font-size: 2rem; opacity: 0.15; margin-bottom: 5px; }

    .image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 8px; }
    .asset-img { width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 8px; background: #e2e8f0; }
    .video-thumbnail { position: relative; overflow: hidden; border-radius: 8px; background: #1e293b; width: 100%; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; }
    
    .folder-section-header {
        background: #f8fafc; padding: 4px 8px; border-radius: 5px; margin-bottom: 6px;
        font-size: 10px; font-weight: 800; color: var(--text-main);
        border-left: 3px solid var(--accent-blue); display: flex; justify-content: space-between;
    }

    .skeleton-loader { background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%); background-size: 200% 100%; animation: loadingSkeleton 1.5s infinite; }
    @keyframes loadingSkeleton { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    /* Modul Extra */
    .extra-folder-nav-container { display: flex; gap: 8px; overflow-x: auto; scrollbar-width: none; flex-grow: 1; align-items: center; }
    .extra-folder-tab { padding: 4px 12px; border-radius: 30px; font-size: 10px; font-weight: 600; background: var(--surface-2); color: var(--text-sub); border: 1px solid var(--border); cursor: pointer; transition: all 0.2s ease; white-space: nowrap; }
    .extra-folder-tab.active { background: var(--accent-blue); color: white; border-color: var(--accent-blue); }
    .extra-canvas-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; }
    .extra-canvas-item { position: relative; border-radius: 12px; overflow: hidden; aspect-ratio: 1/1; cursor: pointer; background: #e2e8f0; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .extra-item-image { width: 100%; height: 100%; object-fit: cover; }
    .extra-item-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 30px 12px 12px; background: var(--overlay-grad); color: white; display: flex; flex-direction: column; justify-content: flex-end; pointer-events: none;}
    .extra-item-title { font-size: 11px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Document UI */
    .folder-year { background: #f8fafc; border-radius: 6px; padding: 6px 14px; font-weight: 700; color: #334155; display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; font-size: 12px; border: 1px solid #e2e8f0; }
    .doc-tree-wrapper { border-left: 1.5px dashed #cbd5e1; margin-left: 12px; padding-left: 16px; padding-top: 5px; }
    .folder-month { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin: 8px 0; display: flex; align-items: center; gap: 5px; position: relative; }
    .folder-month::before { content: ''; position: absolute; left: -16px; top: 50%; width: 12px; height: 1.5px; background-color: #cbd5e1; }
    .doc-item { background: white; border: 1px solid #edf2f7; border-radius: 8px; transition: all 0.2s; width: 100%; margin-bottom: 6px; padding: 8px 12px; display: flex; align-items: center; cursor: pointer; }
    .doc-item.latest-version { border-left: 4px solid #198754; box-shadow: 0 2px 5px rgba(0,0,0,0.04); }

    /* Modals V4 Styles */
    .mv4-topbar { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-bottom: 1px solid #f0f0f0; background: #fff; }
    .mv4-caption { flex: 1; min-width: 0; }
    .mv4-caption-name { font-size: 14px; font-weight: 700; color: #111; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mv4-caption-sub { font-size: 11px; color: #9ca3af; display: block; margin-top: 2px; }
    .mv4-actions { display: flex; gap: 8px; flex-shrink: 0; }
    .mv4-btn { height: 40px; min-width: 40px; padding: 0 14px; border-radius: 20px; border: 1px solid #e5e7eb; background: #f9fafb; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 12px; font-weight: 700; color: #374151; cursor: pointer; transition: 0.2s; }
    .mv4-btn.mv4-share { background: #0d6efd; border-color: #0d6efd; color: #fff; }
    .mv4-btn.mv4-close { background: #fef2f2; border-color: #fecaca; color: #dc2626; }
    .mv4-btn.mv4-success { background: #16a34a; border-color: #16a34a; color: #fff; }
    
    .mv4-nav-row { display: flex; align-items: stretch; background: #0f172a; min-height: 300px; position: relative; }
    .mv4-nav-btn { width: 50px; background: rgba(255,255,255,0.05); border: none; color: white; cursor: pointer; transition: 0.2s; z-index: 10; }
    .mv4-nav-btn:hover { background: rgba(255,255,255,0.15); }
    .mv4-media { flex: 1; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
    .mv4-counter { position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.6); color: #fff; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 20px; z-index: 10; pointer-events: none; }
    
    .select2-container--default .select2-results__option {
        white-space: normal !important;
        word-wrap: break-word !important;
        font-size: 11px !important; /* Sedikit lebih besar agar mudah dibaca */
        line-height: 1.4 !important;
        padding: 6px 12px !important;
        color: var(--text-main);
    }
    .select2-dropdown {
        /* Hapus width: 100% !important; Biarkan Select2 menghitung pixel secara otomatis */
        border: 1px solid var(--border-dark) !important;
        border-radius: 6px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
        z-index: 1060 !important;
    }
    
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: var(--accent-blue) !important;
        color: #ffffff !important;
    }
    
    .filter-grid .select2-container { width: 100% !important; flex: unset !important; }
    .filter-grid .select2-container--default .select2-selection--single {
        height: 30px !important;
        border: 1px solid var(--border-dark) !important;
        border-radius: 6px !important;
        font-size: 11px !important;
        background: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02) !important;
    }
    .filter-grid .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px !important;
        padding-left: 8px !important;
        font-weight: 700 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        display: block !important;
        color: #374151 !important;
    }
    .filter-grid .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 28px !important;
    }
    
    /* FORCE TEXT COLOR BLACK */
    .select2-container .select2-results__option {
        color: #1f2937 !important; /* Warna text-main dari kanvas Anda */
    }
    
    /* Memastikan background dropdown tetap putih */
    .select2-container .select2-dropdown {
        background-color: #ffffff !important;
    }
    
    /* 1. Pastikan background dropdown saat di-hover tetap biru dan teks menjadi putih */
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: var(--accent-blue) !important;
        color: #ffffff !important; /* Teks jadi putih saat di-hover */
    }
    
    /* 2. Pastikan teks default tetap hitam saat tidak di-hover */
    .select2-container--default .select2-results__option {
        color: #1f2937 !important; 
    }
    
    /* 3. Menghilangkan warna abu-abu aneh pada opsi yang tidak di-hover */
    .select2-container--default .select2-results__option--selected {
        background-color: #f8f9fa !important;
        color: #1f2937 !important;
    }
    
    /* Default text */
    .select2-container--default .select2-results__option{
        color:#1f2937 !important;
    }
    
    /* Hover */
    .select2-container--default .select2-results__option--highlighted,
    .select2-container--default .select2-results__option--highlighted span,
    .select2-container--default .select2-results__option--highlighted div{
        background:#0d6efd !important;
        color:#fff !important;
    }
    
    /* Selected */
    .select2-container--default .select2-results__option[aria-selected=true]{
        background:#f8f9fa !important;
        color:#1f2937 !important;
    }
</style>

<div class="product-light-canvas">
    {{-- TOP NAVIGATION --}}
    <div class="top-nav-bar">
        <div class="nav-group">
            <button class="nav-item active" id="btnNavProduct" onclick="window.switchModul('product', this)">Product</button>
            <button class="nav-item" id="btnNavExtra" onclick="window.switchModul('extra', this)">Extra</button>
        </div>
        <div class="nav-group" id="typeNavGroup">
            <button class="nav-item active" id="btnModeGambar" onclick="window.switchTipeMode('gambar', this)">Gambar</button>
            <button class="nav-item" id="btnModeDokumen" onclick="window.switchTipeMode('dokumen', this)">Dokumen</button>
        </div>
    </div>

    {{-- FILTER SECTION (PRODUCT) --}}
    <div class="filter-section fade-in" id="productFilterSection">
        <div class="filter-grid">
            <div class="filter-item">
                <select id="brandSelectGlobal" class="form-select">
                    <option value="">Merek</option>
                    <option value="gcf">GCF</option>
                    <option value="senses">Senses</option>
                    <option value="project">Project</option>
                </select>
            </div>
            <div class="filter-item">
                <select id="searchBrand" class="form-select" disabled><option value="">Brand</option></select>
            </div>
            <div class="filter-item">
                <select id="searchSearah" class="form-select" disabled><option value="">Searah</option></select>
            </div>
            <div class="filter-item">
                <select id="searchVariant" class="form-select" disabled><option value="">Product</option></select>
            </div>
        </div>
    </div>

    {{-- FILTER SECTION (EXTRA) --}}
    <div class="filter-section fade-in d-none" id="extraFilterSection">
        <div class="d-flex align-items-center gap-2">
            <div style="width: 120px; flex-shrink: 0;">
                <input type="text" id="extraMonthPicker" class="form-control form-control-sm text-center fw-bold" placeholder="Pilih Bulan" style="height: 26px; font-size: 10px; border-radius: 5px;">
            </div>
            <div class="d-flex gap-1 overflow-auto" id="extraFolderNavContainer" style="scrollbar-width: none;">
                <span class="text-muted small">Memuat menu...</span>
            </div>
        </div>
    </div>

    {{-- CANVAS CONTAINER --}}
    <div class="canvas-container">
        <div id="productCanvasArea">
            <div class="empty-state"><i class="bi bi-search"></i><p class="fw-bold">Lengkapi filter di atas</p></div>
        </div>
        {{-- Canvas Extra --}}
        <div id="extraCanvasArea" class="d-none">
            <div class="extra-canvas-grid" id="extraCanvasGrid"></div>
            <div id="extraPaginationContainer" class="d-none mt-3 d-flex justify-content-center align-items-center gap-3"></div>
        </div>
    </div>
</div>

{{-- MODAL PREVIEW (PRODUCT) --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true" style="z-index:1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="bg-white shadow-lg d-flex flex-column overflow-hidden fade-in" style="border-radius:18px;">
                <div class="mv4-topbar">
                    <div class="mv4-caption">
                        <span class="mv4-caption-name" id="previewTitle">Nama File</span>
                        <span class="mv4-caption-sub" id="previewSubtitle">&nbsp;</span>
                    </div>
                    <div class="mv4-actions">
                        <button class="mv4-btn tap-effect" id="btnProductDownload" title="Unduh">
                            <i class="bi bi-download"></i> <span class="mv4-btn-label d-none d-md-inline">Unduh</span>
                        </button>
                        <button class="mv4-btn mv4-share tap-effect" id="btnProductShare" title="Bagikan">
                            <i class="bi bi-share"></i> <span class="mv4-btn-label d-none d-md-inline">Bagikan</span>
                        </button>
                        <button class="mv4-btn mv4-close tap-effect" data-bs-dismiss="modal" title="Tutup">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="mv4-nav-row" id="previewMediaContainer">
                    <button class="mv4-nav-btn" id="btnProductPrev" onclick="window.navigateGallery(-1)"><i class="bi bi-chevron-left"></i></button>
                    <div class="mv4-media" id="previewMediaContent"></div>
                    <button class="mv4-nav-btn" id="btnProductNext" onclick="window.navigateGallery(1)"><i class="bi bi-chevron-right"></i></button>
                    <div class="mv4-counter" id="previewCounter" style="display:none;">1 / 1</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PREVIEW (EXTRA) --}}
<div class="modal fade" id="extraPreviewModal" tabindex="-1" aria-hidden="true" style="z-index:1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="bg-white shadow-lg d-flex flex-column overflow-hidden fade-in" style="border-radius:18px;">
                <div class="mv4-topbar">
                    <div class="mv4-caption">
                        <span class="mv4-caption-name" id="extraPreviewTitle">Nama File</span>
                        <span class="mv4-caption-sub" id="extraPreviewSubtitle">&nbsp;</span>
                    </div>
                    <div class="mv4-actions">
                        <button class="mv4-btn mv4-share tap-effect" id="btnExtraShare" title="Bagikan">
                            <i class="bi bi-box-arrow-up"></i> <span class="mv4-btn-label d-none d-md-inline">Bagikan</span>
                        </button>
                        <button class="mv4-btn mv4-close tap-effect" data-bs-dismiss="modal" title="Tutup">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="mv4-nav-row">
                    <button class="mv4-nav-btn" id="btnExtraPrevImg" onclick="window.extra_changePreview(-1)"><i class="bi bi-chevron-left"></i></button>
                    <div class="mv4-media" id="extraMediaContent"></div>
                    <button class="mv4-nav-btn" id="btnExtraNextImg" onclick="window.extra_changePreview(1)"><i class="bi bi-chevron-right"></i></button>
                    <div class="mv4-counter" id="extraCounter">1 / 1</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    // =========================================================================
    // 1. STATE & CONSTANTS (Adjusted to new routes)
    // =========================================================================
    window.currentModul        = 'product';
    window.currentProductMode  = 'gambar';
    window.currentBrandFilter  = '';
    window.allProductsCache    = [];
    window.lastPreviewSrc      = '';
    window.proxyPreviewSrc     = '';
    window.galleryItems        = [];
    window.currentGalleryIndex = 0;

    // Rute proxy yang mengarah ke ProductController lokal
    const apiBaseUrl      = '{{ route("master.product.proxy_product") }}';
    const driveProxyUrl   = '{{ route("master.product.proxy_drive") }}';
    const extraProxyUrl   = '{{ route("master.product.proxy_extra") }}';
    const extraStreamUrl  = '{{ route("master.product.proxy_extra_stream") }}';
    const designThumbUrl  = '{{ route("master.product.proxy_design_thumbnails") }}';
    const proxyImageUrl   = '{{ route("master.product.proxy_image") }}';

    function extraToProxyUrl(fileUrl) {
        try {
            const pathParam = new URL(fileUrl).searchParams.get('path');
            if (pathParam) return `${extraStreamUrl}?path=${pathParam}`;
        } catch (e) { }
        return fileUrl;
    }

    const PRODUCT_DOWNLOAD_HTML = '<i class="bi bi-download"></i> <span class="mv4-btn-label d-none d-md-inline">Unduh</span>';
    const PRODUCT_SHARE_HTML    = '<i class="bi bi-share"></i> <span class="mv4-btn-label d-none d-md-inline">Bagikan</span>';
    const EXTRA_SHARE_HTML      = '<i class="bi bi-box-arrow-up"></i> <span class="mv4-btn-label d-none d-md-inline">Bagikan</span>';

    window.extraState = {
        rootFolders:       [],
        activeFolder:      '',
        currentPath:       '', // STATE BARU
        currentFolderSubFolders: [], // STATE BARU
        activeMonthKey:    '',
        currentFolderFiles:[],
        filteredFiles:     [],
        currentIndex:      -1,
        currentPage:       1,
        ITEMS_PER_PAGE:    12,
        currentPreviewUrl: '',
        currentPreviewTitle: '',
        initialized:       false   
    };

    // =========================================================================
    // 2. INIT
    // =========================================================================
    function init() {
        if (!document.querySelector('link[href*="select2"]')) {
            document.head.insertAdjacentHTML('beforeend',
                `<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />`
            );
        }

        function whenJQueryReady(cb) {
            if (window.$ && $.fn && $.fn.val) cb();
            else setTimeout(() => whenJQueryReady(cb), 50);
        }

        function whenSelect2Ready(cb) {
            if ($.fn.select2) { cb(); return; }
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
            s.onload = cb;
            document.head.appendChild(s);
        }

        whenJQueryReady(() => {
            whenSelect2Ready(() => {
                initSelect2();
                bindModalEvents();
                window.loadInitialProducts();
            });
        });
    }

    function initSelect2() {
        $('#brandSelectGlobal, #searchBrand, #searchSearah, #searchVariant').select2({ 
            width: '100%'
            // Hapus baris dropdownParent di sini
        });
    
        $('#brandSelectGlobal').on('change', function() {
            window.currentBrandFilter = $(this).val();
            window.populateSelect('searchBrand',  [], 'Brand',    true);
            window.populateSelect('searchSearah', [], 'Searah',   true);
            window.populateSelect('searchVariant',[], 'Product',  true);
            window.updateSearchDropdowns();
            window.renderMainCanvas();
        });
    
        $('#searchBrand').on('change', function() {
            window.populateSelect('searchSearah', [], 'Pilih Searah',  true);
            window.populateSelect('searchVariant',[], 'Pilih Product', true);
            window.updateSearchDropdowns();
        });
    
        $('#searchSearah').on('change', function() {
            window.populateSelect('searchVariant',[], 'Pilih Product', true);
            window.updateSearchDropdowns();
        });
    
        $('#searchVariant').on('change', function() {
            if ($(this).val()) window.executeSearch();
        });
    }

    function bindModalEvents() {
        document.getElementById('btnProductDownload').addEventListener('click', function() { window.processDownload(this); });
        document.getElementById('btnProductShare').addEventListener('click', function() { window.processShare(this); });
        document.getElementById('btnExtraShare').addEventListener('click', function() { window.extra_shareCurrentFile(this); });

        $('#previewModal, #extraPreviewModal').on('hidden.bs.modal', function () {
            const vid = this.querySelector('video');
            if (vid) { vid.pause(); vid.src = ''; }
        });
    }

    // =========================================================================
    // 3. LOAD DATA PRODUK
    // =========================================================================
    window.loadInitialProducts = async function() {
        try {
            const res = await fetch(`${apiBaseUrl}?limit=1000`);
            const result = await res.json();
            if (result.success) window.allProductsCache = result.data;
        } catch (e) { console.error('Cache error:', e); }
    };

    window.updateSearchDropdowns = function() {
        const b = $('#searchBrand').val();
        const s = $('#searchSearah').val();
        let data = window.allProductsCache;
        if (window.currentBrandFilter) {
            data = data.filter(i => i.merek && i.merek.toLowerCase() === window.currentBrandFilter.toLowerCase());
        }

        const brands = [...new Set(data.map(i => i.brand).filter(Boolean))].sort();
        window.populateSelect('searchBrand', brands, 'Brand', false);

        let searahs = [];
        if (b) { searahs = [...new Set(data.filter(i => i.brand === b).map(i => i.searah).filter(Boolean))].sort(); }
        window.populateSelect('searchSearah', searahs, 'Searah', !b);

        let variants = [];
        if (b && s) { variants = [...new Set(data.filter(i => i.brand === b && i.searah === s).map(i => i.product_name).filter(Boolean))].sort(); }
        window.populateSelect('searchVariant', variants, 'Product', !(b && s));
    };

    window.populateSelect = function(id, items, def, disabled) {
        const $el = $('#' + id);
        if (!$el.length) return;
        const cur = $el.val();
        $el.empty().append(new Option(def, '')).prop('disabled', !!disabled);
        items.forEach(i => $el.append(new Option(i, i, i === cur, i === cur)));
        if ($.fn.select2) $el.trigger('change.select2');
    };

    // =========================================================================
    // 4. UI SWITCHER
    // =========================================================================
    window.switchModul = function(m, btn) {
        window.currentModul = m;
        document.querySelectorAll('.nav-group:first-child .nav-item').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');

        const productFilter = document.getElementById('productFilterSection');
        const extraFilter   = document.getElementById('extraFilterSection');
        const typeNavGroup  = document.getElementById('typeNavGroup');
        const productCanvas = document.getElementById('productCanvasArea');
        const extraCanvas   = document.getElementById('extraCanvasArea');

        if (m === 'extra') {
            productFilter.classList.add('d-none');
            extraFilter.classList.remove('d-none');
            typeNavGroup.classList.add('invisible');
            productCanvas.classList.add('d-none');
            extraCanvas.classList.remove('d-none');

            if (!window.extraState.initialized) {
                window.loadExtraDependencies(() => {
                    window.initExtraFlatpickr();
                    window.extra_loadRootMenu();
                });
            } else {
                window.extra_applyFiltersAndRender();
            }
        } else {
            productFilter.classList.remove('d-none');
            extraFilter.classList.add('d-none');
            typeNavGroup.classList.remove('invisible');
            productCanvas.classList.remove('d-none');
            extraCanvas.classList.add('d-none');

            window.renderMainCanvas();
        }
    };

    window.switchTipeMode = function(mode, btn) {
        window.currentProductMode = mode;
        document.querySelectorAll('#typeNavGroup .nav-item').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
        window.renderMainCanvas();
    };

    window.renderMainCanvas = function() {
        const canvas = document.getElementById('productCanvasArea');
        if (!window.currentBrandFilter) {
            canvas.innerHTML = '<div class="empty-state"><i class="bi bi-search"></i><p class="fw-bold">Pilih Merek</p></div>';
            return;
        }
        if (window.currentProductMode === 'dokumen') {
            canvas.innerHTML = '<div class="empty-state"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
            window.fetchDocumentsList();
        } else {
            if ($('#searchVariant').val()) window.executeSearch();
            else canvas.innerHTML = '<div class="empty-state"><i class="bi bi-arrow-up"></i><p class="fw-bold">Lengkapi filter</p></div>';
        }
    };

    // =========================================================================
    // 5. PRODUCT SEARCH
    // =========================================================================
    window.executeSearch = async function() {
        const canvas = document.getElementById('productCanvasArea');
        const v = $('#searchVariant').val();
        const b = $('#searchBrand').val();
        const s = $('#searchSearah').val();
        const p = window.allProductsCache.find(i => i.product_name === v && i.brand === b);
        if (!p) return;
    
        canvas.innerHTML = '<div class="empty-state"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
    
        try {
            window.galleryItems        = [];
            window.currentGalleryIndex = 0;
    
            const urlObj    = new URL(p.drive_list_url);
            const path      = urlObj.searchParams.get('path');
            const merek     = window.currentBrandFilter;
    
            const [resDrive, resDesign] = await Promise.all([
                fetch(`${driveProxyUrl}?path=${path}`),
                fetch(`${designThumbUrl}?merek=${encodeURIComponent(merek)}&brand=${encodeURIComponent(b)}&searah=${encodeURIComponent(s)}&product_name=${encodeURIComponent(v)}`).catch(() => null)
            ]);
    
            const items        = await resDrive.json();
            const designResult = resDesign ? await resDesign.json().catch(() => null) : null;
    
            const thumbData  = (designResult && designResult.success && Array.isArray(designResult.data)) ? designResult.data : [];
            const thumbnails = [];
            thumbData.forEach(t => {
                if (t.image_url)    thumbnails.push({ ...t, _src: t.image_url,    _label: 'Reguler' });
                if (t.image_hd_url) thumbnails.push({ ...t, _src: t.image_hd_url, _label: 'HD' });
            });
    
            let thumbItems = '';
            if (thumbnails.length > 0) {
                thumbnails.forEach(t => {
                    window.galleryItems.push({ src: t._src, title: t.code + ' — ' + (t.name || '') + ' (' + t._label + ')', isVideo: false, origin: 'design' });
                });
    
                thumbItems = thumbnails.map((t, i) => {
                    return `<div class="gallery-asset asset-img shadow-sm tap-effect position-relative skeleton-loader" data-gidx="${i}" style="overflow:hidden;">
                        <img src="${t._src}" class="w-100 h-100 position-relative" loading="lazy" style="object-fit:cover;z-index:2;opacity:0;transition:opacity 0.4s;pointer-events:none;"
                             onload="this.style.opacity=1;this.parentElement.classList.remove('skeleton-loader');" onerror="this.src='https://placehold.co/300x300?text=No+Preview';this.style.opacity=1;this.parentElement.classList.remove('skeleton-loader');">
                        <div style="position:absolute;bottom:0;left:0;right:0;padding:18px 6px 5px;background:linear-gradient(to top,rgba(0,0,0,0.72) 0%,transparent 100%);z-index:3;pointer-events:none;">
                            <div style="font-size:9px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${t.code}</div>
                            <div style="font-size:8px;color:rgba(255,255,255,0.75);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${t._label}</div>
                        </div>
                    </div>`;
                }).join('');
            } else {
                thumbItems = `
                    <div style="grid-column:1/-1;padding:20px;background:#f1f3f5;border:1px dashed #d1d5db;border-radius:10px;text-align:center;">
                        <i class="bi bi-image text-secondary mb-2" style="font-size:1.5rem;display:block;"></i>
                        <div class="text-muted fw-bold" style="font-size:11px;">Belum ada thumbnail</div>
                    </div>`;
            }
    
            let html = `<div class="mb-3">
                            <div class="folder-section-header"><span>Product Thumbnail</span><span>${thumbnails.length}</span></div>
                            <div class="image-grid">${thumbItems}</div>
                        </div>`;
                        
            // Daftar nama folder yang ingin disembunyikan
            const hiddenFolders = ['02_LSFRAGRANCE_VISUAL', '03_CLEAN_VISUAL'];
    
            const subFolders = items.filter(f => f.type === 'folder' && !hiddenFolders.includes(f.name));
    
            if (subFolders.length > 0) {
                for (const folder of subFolders) {
                    const resSub      = await fetch(`${driveProxyUrl}?path=${folder.path}`);
                    const files       = await resSub.json();
                    const cleanTitle  = folder.name.replace(/^\d+_/, '').replace(/_/g, ' ');
                    const folderFiles = files.filter(f => f.type === 'file');
    
                    folderFiles.forEach(file => {
                        const isVideo = /\.(mp4|webm|ogg|mov)$/i.test(file.name);
                        window.galleryItems.push({ src: file.url, title: file.name, isVideo, origin: 'product' });
                    });
    
                    html += window.buildFolderSectionHTML(cleanTitle, folderFiles);
                }
            }
    
            canvas.innerHTML = html;
    
        } catch (e) {
            console.error('[executeSearch] ERROR:', e);
            canvas.innerHTML = '<div class="empty-state text-danger">Gagal memuat aset.</div>';
        }
    };

    window.getThumbnailUrl = function(url) {
        const match = url.match(/id=([^&]+)/) || url.match(/\/d\/([^&]+)/);
        if (match && match[1] && url.includes('drive.google.com')) return `https://drive.google.com/thumbnail?id=${match[1]}&sz=w300`;
        return url;
    };

    window.buildFolderSectionHTML = function(title, files) {
        if (!files || files.length === 0) {
            return `<div class="mb-3"><div class="folder-section-header"><span>${title}</span><span>0</span></div><div style="padding: 12px 8px; text-align: center; color: #94a3b8; font-size: 10px; font-weight: 600; background: #f8fafc; border-radius: 6px; border: 1px dashed #e2e8f0;">Belum ada aset</div></div>`;
        }
        let itemsHtml = '';
        files.forEach(file => {
            const isVideo = /\.(mp4|webm|ogg|mov)$/i.test(file.name);
            const idx     = window.galleryItems.findIndex(g => g.src === file.url && g.title === file.name);

            if (isVideo) {
                itemsHtml += `<div class="gallery-asset video-thumbnail tap-effect shadow-sm" data-gidx="${idx}"><i class="bi bi-play-circle-fill text-white opacity-75" style="font-size:1.5rem;pointer-events:none;"></i></div>`;
            } else {
                const thumbUrl = window.getThumbnailUrl(file.url);
                itemsHtml += `<div class="gallery-asset asset-img shadow-sm tap-effect position-relative skeleton-loader" data-gidx="${idx}" style="overflow:hidden;cursor:pointer;">
                    <img src="${thumbUrl}" referrerpolicy="no-referrer" class="w-100 h-100 position-relative" loading="lazy" style="object-fit:cover;z-index:2;opacity:0;transition:opacity 0.4s;pointer-events:none;"
                         onload="this.style.opacity=1;this.parentElement.classList.remove('skeleton-loader');" onerror="this.src='https://placehold.co/300x300?text=No+Preview';this.style.opacity=1;this.parentElement.classList.remove('skeleton-loader');">
                </div>`;
            }
        });
        return `<div class="mb-3"><div class="folder-section-header"><span>${title}</span><span>${files.length}</span></div><div class="image-grid">${itemsHtml}</div></div>`;
    };

    document.getElementById('productCanvasArea').addEventListener('click', function(event) {
        const target = event.target.closest('.gallery-asset[data-gidx]');
        if (!target) return;
        const idx = parseInt(target.getAttribute('data-gidx'), 10);
        if (!isNaN(idx) && idx >= 0) window.openGallery(idx);
    });

    // =========================================================================
    // 6. GALERI PRODUCT
    // =========================================================================
    window.openGallery = function(idx) {
        if (typeof idx !== 'number') return;
        window.currentGalleryIndex = idx;
        window.renderCurrentGalleryItem();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('previewModal')).show();
    };

    window.navigateGallery = function(direction) {
        if (!window.galleryItems || window.galleryItems.length === 0) return;
        window.currentGalleryIndex += direction;
        if (window.currentGalleryIndex < 0) window.currentGalleryIndex = window.galleryItems.length - 1;
        else if (window.currentGalleryIndex >= window.galleryItems.length) window.currentGalleryIndex = 0;
        window.renderCurrentGalleryItem();
    };

    window.renderCurrentGalleryItem = function() {
        if (!window.galleryItems || window.galleryItems.length === 0) return;
        const item = window.galleryItems[window.currentGalleryIndex];
        if (!item) return;
        const { src: originalSrc, title, isVideo, isPdf } = item;
        const total = window.galleryItems.length;
        const cur   = window.currentGalleryIndex + 1;
    
        let displaySrc = originalSrc;
        if (isVideo) {
            displaySrc = originalSrc;
        } else if (item.origin === 'design') {
            displaySrc = originalSrc; // URL publik langsung
        } else {
            displaySrc = '/proxy-image?url=' + encodeURIComponent(originalSrc);
        }
    
        window.lastPreviewSrc  = originalSrc;
        window.proxyPreviewSrc = displaySrc;
    
        document.getElementById('previewTitle').innerText = title.replace(/\.[^/.]+$/, '').replace(/_/g, ' ');
        const ext = (title.match(/\.([^.]+)$/) || ['', ''])[1].toUpperCase();
        document.getElementById('previewSubtitle').innerText = (ext ? ext + ' · ' : '') + cur + ' dari ' + total;
    
        const counter = document.getElementById('previewCounter');
        if (counter) {
            counter.innerText = cur + ' / ' + total;
            counter.style.display = total > 1 ? 'block' : 'none';
        }
    
        document.getElementById('btnProductPrev').disabled = (cur <= 1);
        document.getElementById('btnProductNext').disabled = (cur >= total);
    
        const contentDiv = document.getElementById('previewMediaContent');
        if (isVideo) {
            contentDiv.innerHTML = `<video src="${displaySrc}" controls autoplay playsinline style="width:100%;max-height:75vh;object-fit:contain;background:#000;"></video>`;
        } else if (isPdf) {
            const pdfSrc = `${displaySrc}#view=FitH`;
            contentDiv.innerHTML = `
                <div id="loadingPreview" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:1;">
                    <div class="spinner-border text-secondary mb-2" role="status"></div><span class="text-muted fw-bold" style="font-size:11px;">Memuat...</span>
                </div>
                <iframe src="${pdfSrc}" onload="var lp=document.getElementById('loadingPreview');if(lp)lp.style.display='none';" style="width:100%;height:75vh;border:none;border-radius:4px;background:#fff;position:relative;z-index:2;"></iframe>`;
        } else {
            contentDiv.innerHTML = `
                <div id="loadingPreview" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:1;">
                    <div class="spinner-border text-secondary mb-2" role="status"></div><span class="text-muted fw-bold" style="font-size:11px;">Memuat...</span>
                </div>
                <img src="${displaySrc}" crossorigin="anonymous" style="max-width:100%;max-height:75vh;object-fit:contain;border-radius:4px;z-index:2;position:relative;opacity:0;transition:opacity 0.3s;" onload="var lp=document.getElementById('loadingPreview');if(lp)lp.style.display='none';this.style.opacity=1;">`;
        }
        if (counter) contentDiv.appendChild(counter);
    };

    // =========================================================================
    // 7. SHARE & DOWNLOAD
    // =========================================================================
    window.processDownload = async function(btn) {
        const isVideo   = !!document.querySelector('#previewMediaContent video');
        const proxyUrl  = window.proxyPreviewSrc;
        const title     = document.getElementById('previewTitle').innerText || 'Aset_Produk';
        const origHtml  = PRODUCT_DOWNLOAD_HTML;
        try {
            if (btn) { btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; btn.disabled = true; }
            const response = await fetch(proxyUrl);
            const blob     = await response.blob();
            const url      = URL.createObjectURL(blob);
            const a        = document.createElement('a');
            a.style.display = 'none'; a.href = url;
            const extension = blob.type.split('/')[1] || (isVideo ? 'mp4' : 'jpg');
            a.download = `${title.replace(/\s+/g, '_')}-${Date.now()}.${extension}`;
            document.body.appendChild(a); a.click();
            URL.revokeObjectURL(url); document.body.removeChild(a);
            window.showSuccessBtn(btn, origHtml, 'Sukses');
        } catch (e) { window.resetBtn(btn, origHtml); }
    };

    window.processShare = async function(btn) {
        const imgEl     = document.querySelector('#previewMediaContent img');
        const title     = document.getElementById('previewTitle').innerText || 'Aset Produk';
        const origUrl   = window.lastPreviewSrc;
        const proxyUrl  = window.proxyPreviewSrc;
        const origHtml  = PRODUCT_SHARE_HTML;
        if (btn && btn.disabled) return;
        try {
            if (btn) { btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; btn.disabled = true; }
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            if (isMobile && navigator.share && imgEl) {
                const res  = await fetch(proxyUrl);
                const blob = await res.blob();
                const ext  = blob.type.split('/')[1] || 'jpg';
                const file = new File([blob], `produk-${Date.now()}.${ext}`, { type: blob.type });
                const shareData = { files: [file], title, text: title };
                if (navigator.canShare && navigator.canShare(shareData)) {
                    await navigator.share(shareData);
                    window.resetBtn(btn, origHtml);
                } else {
                    window.copyImageToClipboard(imgEl, origUrl, btn, origHtml);
                }
            } else {
                if (imgEl) window.copyImageToClipboard(imgEl, origUrl, btn, origHtml);
                else       window.executeCopyText(origUrl, btn, origHtml);
            }
        } catch (error) {
            if (error.name === 'AbortError') window.resetBtn(btn, origHtml);
            else window.executeCopyText(origUrl, btn, origHtml);
        }
    };

    window.copyImageToClipboard = async function(imgEl, fallbackUrl, btn, origHtml) {
        try {
            const cv = document.createElement('canvas');
            cv.width  = imgEl.naturalWidth;
            cv.height = imgEl.naturalHeight;
            cv.getContext('2d').drawImage(imgEl, 0, 0);
            cv.toBlob(async (blob) => {
                if (blob) {
                    try {
                        await navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]);
                        window.showSuccessBtn(btn, origHtml, 'Disalin');
                    } catch (e) { window.executeCopyText(fallbackUrl, btn, origHtml); }
                } else { window.executeCopyText(fallbackUrl, btn, origHtml); }
            }, 'image/png');
        } catch (e) { window.executeCopyText(fallbackUrl, btn, origHtml); }
    };

    window.executeCopyText = function(text, btn, origHtml) {
        navigator.clipboard.writeText(text)
            .then(() => window.showSuccessBtn(btn, origHtml, 'Disalin'))
            .catch(() => window.resetBtn(btn, origHtml));
    };

    window.showSuccessBtn = function(btn, origHtml, message) {
        if (!btn) return;
        btn.innerHTML = `<i class="bi bi-check2"></i> <span class="d-none d-md-inline">${message}</span>`;
        btn.classList.add('mv4-success'); btn.disabled = false;
        setTimeout(() => { btn.innerHTML = origHtml; btn.classList.remove('mv4-success'); }, 2500);
    };

    window.resetBtn = function(btn, origHtml) {
        if (!btn) return;
        btn.innerHTML = origHtml; btn.disabled = false;
        btn.classList.remove('mv4-loading', 'mv4-success');
    };

    // =========================================================================
    // 8. DOKUMEN PRICE LIST / PRODUCT LIST
    // =========================================================================
    window.fetchDocumentsList = async function() {
        const canvas    = document.getElementById('productCanvasArea');
        const folderMap = { gcf: '04_PL_GCF', senses: '05_PL_SENSES', project: '06_PL_PROJECT' };
        try {
            const encodedPath = btoa(folderMap[window.currentBrandFilter]);
            const res    = await fetch(`${driveProxyUrl}?path=${encodedPath}`);
            const result = await res.json();
            let html = '';
            ['Price List', 'Product List'].forEach(cat => {
                let files = result.filter(f => f.name.toLowerCase().includes(cat.toLowerCase().replace(' ', '_')));
                if (files.length) {
                    files.sort((a, b) => b.name.localeCompare(a.name));
                    html += `<div class="mb-3"><div class="folder-section-header">${cat}</div>`;
                    files.forEach((f, idx) => {
                        const isLatest = idx === 0;
                        html += `<div class="doc-item ${isLatest ? 'latest-version' : ''}" ${isLatest ? `onclick="window.openDoc('${f.url}','${f.name}')"` : ''}>
                            <i class="bi bi-file-pdf text-danger me-2"></i>
                            <span class="text-truncate">${f.name}</span>
                            ${isLatest ? '<span class="badge bg-success ms-auto" style="font-size:8px;">TERBARU</span>' : ''}
                        </div>`;
                    });
                    html += `</div>`;
                }
            });
            canvas.innerHTML = html || '<div class="empty-state">Tidak ada dokumen.</div>';
        } catch (e) { canvas.innerHTML = '<div class="empty-state text-danger">Gagal memuat dokumen.</div>'; }
    };

    window.openDoc = function(url, name) {
        window.galleryItems = [{ src: url, title: name, isVideo: false, isPdf: true }];
        window.currentGalleryIndex = 0;
        window.renderCurrentGalleryItem();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('previewModal')).show();
    };

    // =========================================================================
    // 9. EXTRA MODULE
    // =========================================================================
    window.loadExtraDependencies = function(callback) {
        if (typeof flatpickr !== 'undefined' && typeof monthSelectPlugin !== 'undefined') { callback(); return; }
        document.head.insertAdjacentHTML('beforeend', [
            `<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">`,
            `<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">`
        ].join(''));
        const loadScript = (src) => new Promise(resolve => {
            const s = document.createElement('script'); s.src = src; s.onload = resolve; document.head.appendChild(s);
        });
        loadScript('https://cdn.jsdelivr.net/npm/flatpickr')
            .then(() => loadScript('https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js'))
            .then(() => loadScript('https://npmcdn.com/flatpickr/dist/l10n/id.js'))
            .then(callback);
    };

    window.extraIndonesianLocale = {
        name: 'id',
        weekdays: { shorthand: ['Min','Sen','Sel','Rab','Kam','Jum','Sab'], longhand: ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] },
        months:   { shorthand: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'], longhand: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] }
    };

    window.initExtraFlatpickr = function() {
        const now = new Date();
        window.extraState.activeMonthKey = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
        window.extraMonthPickerInstance  = flatpickr('#extraMonthPicker', {
            locale: window.extraIndonesianLocale, disableMobile: true,
            altInput: true, altFormat: 'F Y', dateFormat: 'Y-m',
            plugins: [new monthSelectPlugin({ shorthand: false, dateFormat: 'Y-m', altFormat: 'F Y' })],
            maxDate: 'today', defaultDate: window.extraState.activeMonthKey,
            onChange: function(_, dateStr) {
                window.extraState.activeMonthKey = dateStr;
                window.extraState.currentPage    = 1;
                window.extra_applyFiltersAndRender();
            }
        });
    };

    window.extra_loadRootMenu = async function() {
        try {
            const res  = await fetch(`${extraProxyUrl}?path=`);
            const data = await res.json();
            if (data.error) throw new Error(data.error);
            window.extraState.rootFolders = data.filter(i => i.type === 'folder');
            window.extra_renderFolderNav();
            if (window.extraState.rootFolders.length > 0) {
                window.extraState.activeFolder = '';
                window.extra_selectFolder(window.extraState.rootFolders[0].name);
            } else {
                document.getElementById('extraFolderNavContainer').innerHTML = '<span class="text-muted small py-1">Belum ada folder</span>';
            }
            window.extraState.initialized = true;
        } catch (e) {
            document.getElementById('extraFolderNavContainer').innerHTML = '<span class="text-danger small py-1">Gagal memuat menu</span>';
        }
    };

    window.extra_renderFolderNav = function() {
        const container = document.getElementById('extraFolderNavContainer');
        if (!container) return;
        container.innerHTML = window.extraState.rootFolders.map(folder => {
            const cleanName = folder.name.replace(/^\d+_/, '').replace(/_/g, ' ');
            const isActive  = folder.name === window.extraState.activeFolder ? 'active' : '';
            return `<div class="extra-folder-tab ${isActive}" onclick="window.extra_selectFolder('${folder.name}')">${cleanName}</div>`;
        }).join('');
    };

    window.extra_selectFolder = async function(folderName) {
        window.extraState.activeFolder = folderName;
        window.extra_renderFolderNav();
        window.extra_openPath(folderName);
    };
    
    // FUNGSI BARU: Untuk navigasi path dan sub-folder
    window.extra_openPath = async function(path) {
        window.extraState.currentPath = path;

        const grid       = document.getElementById('extraCanvasGrid');
        const pagination = document.getElementById('extraPaginationContainer');
        
        if (grid)       grid.innerHTML = '<div style="grid-column:1/-1;" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
        if (pagination) pagination.classList.add('d-none');

        try {
            const encodedPath = btoa(path);
            const res  = await fetch(`${extraProxyUrl}?path=${encodedPath}`);
            const data = await res.json();
            if (data.error) throw new Error(data.error);

            // Pisahkan mana folder dan mana file
            window.extraState.currentFolderSubFolders = data.filter(i => i.type === 'folder');
            window.extraState.currentFolderFiles = data.filter(i => i.type === 'file');

            // Pastikan ada default bulan
            const now = new Date();
            if (!window.extraState.activeMonthKey) {
                window.extraState.activeMonthKey = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
                if (window.extraMonthPickerInstance) window.extraMonthPickerInstance.setDate(window.extraState.activeMonthKey, false);
            }
            
            window.extraState.currentPage = 1;
            window.extra_applyFiltersAndRender();
        } catch (e) {
            if (grid) grid.innerHTML = `<div style="grid-column:1/-1;" class="text-center text-danger py-4"><i class="bi bi-x-circle mb-2"></i><br>Gagal memuat isi folder.</div>`;
        }
    };

    window.extra_applyFiltersAndRender = function() {
        if (!window.extraState.activeMonthKey) {
            window.extraState.filteredFiles = window.extraState.currentFolderFiles;
        } else {
            window.extraState.filteredFiles = window.extraState.currentFolderFiles.filter(file => {
                const match = file.name.match(/^(\d{6})_/);
                if (match) {
                    return (match[1].substring(0, 4) + '-' + match[1].substring(4, 6)) === window.extraState.activeMonthKey;
                }
                if (!file.timestamp) return false;
                const d = new Date(file.timestamp * 1000);
                return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}` === window.extraState.activeMonthKey;
            });
        }
        window.extra_renderGrid();
    };

    window.extra_renderGrid = function() {
        const grid       = document.getElementById('extraCanvasGrid');
        const pagination = document.getElementById('extraPaginationContainer');
        if (!grid) return;

        const isSubFolder = window.extraState.currentPath !== window.extraState.activeFolder;

        // Jika kosong di bulan ini (dan bukan sedang di dalam sub-folder kosong)
        if (window.extraState.filteredFiles.length === 0 && window.extraState.currentFolderSubFolders.length === 0 && !isSubFolder) {
            grid.innerHTML = `<div style="grid-column:1/-1;" class="text-center text-muted py-5">
                <i class="bi bi-folder-x mb-2" style="font-size:2rem;opacity:0.5;display:block;"></i>
                <span class="small">Tidak ada folder atau aset pada bulan ini.</span>
            </div>`;
            if (pagination) pagination.classList.add('d-none');
            return;
        }

        let html = '';

        // 1. TOMBOL KEMBALI
        if (isSubFolder) {
            const parentPath = window.extraState.currentPath.substring(0, window.extraState.currentPath.lastIndexOf('/')) || window.extraState.activeFolder;
            html += `<div class="extra-canvas-item fade-in bg-light tap-effect" style="border: 2px dashed #cbd5e1; cursor: pointer;" onclick="window.extra_openPath('${parentPath}')">
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-secondary">
                            <i class="bi bi-arrow-90deg-up" style="font-size: 2rem;"></i>
                            <span style="font-size: 11px; margin-top: 8px; font-weight: 700;">Kembali</span>
                        </div>
                     </div>`;
        }

        // 2. RENDER FOLDER
        window.extraState.currentFolderSubFolders.forEach(folder => {
            const nextPath = `${window.extraState.currentPath}/${folder.name}`;
            const cleanTitle = folder.name.replace(/^\d+_/, '').replace(/_/g, ' ');
            html += `<div class="extra-canvas-item fade-in tap-effect" style="background:#f8f9fa; border:1px solid #e2e8f0; cursor: pointer;" onclick="window.extra_openPath('${nextPath}')">
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-folder-fill" style="font-size: 3rem; color:#9ca3af;"></i>
                        </div>
                        <div class="extra-item-overlay" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%); padding-top: 30px;">
                            <span class="extra-item-title text-center w-100 d-block">${cleanTitle}</span>
                        </div>
                     </div>`;
        });

        // 3. RENDER FILE
        const start    = (window.extraState.currentPage - 1) * window.extraState.ITEMS_PER_PAGE;
        const end      = start + window.extraState.ITEMS_PER_PAGE;
        const toRender = window.extraState.filteredFiles.slice(start, end);

        html += toRender.map((file, relIdx) => {
            const cleanTitle = file.name.replace(/\.[^/.]+$/, '');
            const absIdx     = start + relIdx;
            const isVideo    = /\.(mp4|webm|ogg|mov)$/i.test(file.name);
            const proxiedUrl = extraToProxyUrl(file.url);

            if (isVideo) {
                return `<div class="extra-canvas-item fade-in bg-dark tap-effect" onclick="window.extra_openPreview(${absIdx})">
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-play-circle-fill text-white" style="font-size:3rem;opacity:0.85;filter:drop-shadow(0 2px 6px rgba(0,0,0,0.6));"></i>
                    </div>
                    <div class="extra-item-overlay"><span class="extra-item-title">${cleanTitle}</span></div>
                </div>`;
            } else {
                return `<div class="extra-canvas-item fade-in tap-effect" onclick="window.extra_openPreview(${absIdx})">
                    <img src="${proxiedUrl}" class="extra-item-image" loading="lazy"
                         onerror="this.src='https://placehold.co/300x300?text=No+Preview';">
                    <div class="extra-item-overlay"><span class="extra-item-title">${cleanTitle}</span></div>
                </div>`;
            }
        }).join('');

        grid.innerHTML = html;

        // Jika masuk ke sub-folder tapi isinya kosong
        if (html === '' && isSubFolder) {
            grid.innerHTML += `<div style="grid-column:1/-1;" class="text-center text-muted py-5"><i class="bi bi-folder2-open mb-2" style="font-size:2rem;opacity:0.5;display:block;"></i><span class="small">Folder ini kosong</span></div>`;
        }

        window.extra_renderPagination();
    };

    window.extra_renderPagination = function() {
        const pagination = document.getElementById('extraPaginationContainer');
        if (!pagination) return;
        const total = Math.ceil(window.extraState.filteredFiles.length / window.extraState.ITEMS_PER_PAGE);
        if (total <= 1) { pagination.classList.add('d-none'); return; }
        pagination.classList.remove('d-none');
        pagination.innerHTML = `
            <button class="mv4-btn tap-effect" ${window.extraState.currentPage === 1 ? 'disabled' : ''} onclick="window.extra_changePage(-1)">
                <i class="bi bi-chevron-left"></i>
            </button>
            <span class="fw-bold text-muted" style="font-size:11px;">Hal ${window.extraState.currentPage} dari ${total}</span>
            <button class="mv4-btn tap-effect" ${window.extraState.currentPage === total ? 'disabled' : ''} onclick="window.extra_changePage(1)">
                <i class="bi bi-chevron-right"></i>
            </button>`;
    };

    window.extra_changePage = function(dir) {
        window.extraState.currentPage += dir;
        window.extra_renderGrid();
    };

    window.extra_openPreview = function(index) {
        window.extraState.currentIndex = index;
        window.extra_updateModalUI();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('extraPreviewModal')).show();
    };

    window.extra_changePreview = function(direction) {
        const newIndex = window.extraState.currentIndex + direction;
        if (newIndex >= 0 && newIndex < window.extraState.filteredFiles.length) {
            window.extraState.currentIndex = newIndex;
            window.extra_updateModalUI();
        }
    };

    window.extra_updateModalUI = function() {
        const file = window.extraState.filteredFiles[window.extraState.currentIndex];
        if (!file) return;

        window.extraState.currentPreviewUrl   = file.url;
        window.extraState.currentPreviewTitle = file.name;

        const isVideo = /\.(mp4|webm|ogg|mov)$/i.test(file.name);
        const cur     = window.extraState.currentIndex + 1;
        const total   = window.extraState.filteredFiles.length;

        const titleEl = document.getElementById('extraPreviewTitle');
        if (titleEl) titleEl.innerText = file.name.replace(/\.[^/.]+$/, '');

        const subEl  = document.getElementById('extraPreviewSubtitle');
        const extStr = (file.name.match(/\.([^.]+)$/) || ['', ''])[1].toUpperCase();
        if (subEl) subEl.innerText = (extStr ? extStr + ' · ' : '') + cur + ' dari ' + total;

        document.getElementById('btnExtraPrevImg').disabled = (cur <= 1);
        document.getElementById('btnExtraNextImg').disabled = (cur >= total);

        const ctrEl   = document.getElementById('extraCounter');
        if (ctrEl) ctrEl.innerText = cur + ' / ' + total;

        const mediaDiv   = document.getElementById('extraMediaContent');
        const proxiedSrc = extraToProxyUrl(file.url);

        if (isVideo) {
            const oldVid = mediaDiv.querySelector('video');
            if (oldVid) { oldVid.pause(); oldVid.remove(); }
            const oldImg = mediaDiv.querySelector('img');
            if (oldImg) oldImg.style.display = 'none';

            const vid = document.createElement('video');
            vid.src      = proxiedSrc;
            vid.controls = true; vid.autoplay = true; vid.setAttribute('playsinline', '');
            vid.style.cssText = 'width:100%;max-height:75vh;object-fit:contain;background:#000;z-index:2;position:relative;';
            mediaDiv.insertBefore(vid, mediaDiv.firstChild);
        } else {
            const oldVid = mediaDiv.querySelector('video');
            if (oldVid) { oldVid.pause(); oldVid.remove(); }

            let img = mediaDiv.querySelector('img#extraPreviewImage');
            if (!img) {
                img = document.createElement('img');
                img.id        = 'extraPreviewImage';
                img.className = 'img-fluid';
                img.style.cssText = 'max-width:100%;max-height:75vh;object-fit:contain;border-radius:4px;z-index:2;position:relative;transition:opacity 0.25s;';
                mediaDiv.insertBefore(img, mediaDiv.firstChild);
            }
            img.style.display = ''; img.style.opacity = '0'; img.src = proxiedSrc;
            img.onload = () => { img.style.opacity = '1'; };
        }
        if (ctrEl && !mediaDiv.contains(ctrEl)) mediaDiv.appendChild(ctrEl);
    };

    window.extra_shareCurrentFile = async function(btn) {
        if (!window.extraState.currentPreviewUrl || (btn && btn.disabled)) return;

        const origUrl  = window.extraState.currentPreviewUrl;
        const title    = window.extraState.currentPreviewTitle || 'Aset Extra';
        const isVideo  = /\.(mp4|webm|ogg|mov)$/i.test(title);
        const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        const proxyUrl = extraToProxyUrl(origUrl);
        const origHtml = EXTRA_SHARE_HTML;

        if (btn) { btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; btn.disabled = true; }

        const setReady = () => { if (!btn) return; btn.innerHTML = origHtml; btn.disabled = false; btn.classList.remove('mv4-loading', 'mv4-success'); };

        try {
            const resp = await fetch(proxyUrl);
            if (!resp.ok) throw new Error('Proxy gagal: ' + resp.status);
            const blob = await resp.blob();
            if (!blob || blob.size === 0) throw new Error('Blob kosong');

            let mimeType = blob.type;
            let ext = mimeType.split('/')[1] || (isVideo ? 'mp4' : 'jpg');
            ext = ext.replace('quicktime', 'mov').replace('x-matroska', 'mkv');
            const cleanTitle = title.replace(/\.[^/.]+$/, '');

            if (isMobile && navigator.share) {
                const file = new File([blob], `${cleanTitle}-${Date.now()}.${ext}`, { type: mimeType });
                const shareData = { files: [file] };
                if (navigator.canShare && navigator.canShare(shareData)) {
                    await navigator.share(shareData);
                } else {
                    await navigator.share({ title, url: origUrl });
                }
            } else {
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = `${cleanTitle}-${Date.now()}.${ext}`;
                a.style.display = 'none';
                document.body.appendChild(a); a.click();
                URL.revokeObjectURL(a.href); document.body.removeChild(a);
            }

            if (btn) {
                btn.classList.add('mv4-success');
                btn.innerHTML = '<i class="bi bi-check2"></i> <span class="mv4-btn-label d-none d-md-inline">Terkirim</span>';
                btn.disabled = false;
                setTimeout(setReady, 2500);
            }
        } catch (err) {
            if (err.name === 'AbortError') { setReady(); } 
            else {
                navigator.clipboard.writeText(origUrl).then(() => {
                    if (btn) {
                        btn.classList.add('mv4-success');
                        btn.innerHTML = '<i class="bi bi-check2"></i> <span class="mv4-btn-label d-none d-md-inline">Link disalin</span>';
                        btn.disabled = false;
                        setTimeout(setReady, 2500);
                    }
                }).catch(() => setReady());
            }
        }
    };

    // =========================================================================
    // BOOT
    // =========================================================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
</script>