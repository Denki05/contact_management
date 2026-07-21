{{-- ============================================================
     partial_extra.blade.php
     Modul EXTRA — galeri aset berdasarkan bulan & folder
     Flatpickr di-load langsung (bukan lazy) untuk menghindari
     race condition & timing bug.
     Sub-nav PRODUCT akan load partial_product via AJAX.
     ============================================================ --}}

<style id="extra-module-style">
    /* ---- Base ---- */
    .tap-effect { transition:all .15s cubic-bezier(.4,0,.2,1); cursor:pointer; -webkit-tap-highlight-color:transparent; }
    .tap-effect:active { transform:scale(.96); opacity:.8; }
    .fade-in { animation:fadeIn .25s ease-out; }
    @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

    /* ---- Folder tab (menu horizontal) ---- */
    .extra-folder-scroll {
        display:flex; gap:6px; overflow-x:auto;
        scrollbar-width:none; -webkit-overflow-scrolling:touch;
        flex-grow:1; min-width:0; align-items:center;
    }
    .extra-folder-scroll::-webkit-scrollbar { display:none; }

    .extra-tab {
        padding:5px 14px; border-radius:20px; font-size:11px; font-weight:700;
        background:#2a2f36; color:#9ca3af;
        border:1px solid rgba(255,255,255,.1);
        cursor:pointer; white-space:nowrap; user-select:none;
        transition:all .2s ease; -webkit-tap-highlight-color:transparent;
    }
    .extra-tab:hover { color:#e5e7eb; background:#3a3f46; }
    .extra-tab.active {
        background:#0c82f9; color:#fff;
        border-color:#0c82f9;
        box-shadow:0 3px 8px rgba(12,130,249,.35);
    }

    /* ---- Month Picker Input ---- */
    .extra-month-input {
        background-color:#2a2f36 !important;
        border:1px solid rgba(255,255,255,.15) !important;
        border-radius:20px !important;
        padding:5px 10px 5px 30px !important;
        width:130px !important;
        font-weight:700 !important; color:#e5e7eb !important;
        font-size:11px !important; cursor:pointer !important;
        box-shadow:none !important; outline:none !important;
    }
    .extra-month-input::placeholder { color:#6b7280 !important; }
    .extra-cal-icon {
        position:absolute; left:10px; top:50%; transform:translateY(-50%);
        color:#6b7280; font-size:.85rem; pointer-events:none; z-index:5;
    }

    /* ---- Canvas & Grid ---- */
    #extraCanvas {
        background:#fff; border-radius:10px;
        padding:10px; min-height:380px;
        border:1px solid rgba(0,0,0,.06);
    }
    .extra-grid {
        display:grid;
        grid-template-columns:repeat(auto-fill, minmax(110px, 1fr));
        gap:10px; align-content:start;
    }
    .extra-card {
        position:relative; border-radius:12px; overflow:hidden;
        aspect-ratio:1/1; cursor:pointer; background:#e2e8f0;
        transition:transform .2s cubic-bezier(.16,1,.3,1);
        -webkit-tap-highlight-color:transparent;
        box-shadow:0 4px 8px rgba(0,0,0,.05);
    }
    .extra-card:active { transform:scale(.96); }
    .extra-card img, .extra-card video { width:100%; height:100%; object-fit:cover; }
    .extra-card-overlay {
        position:absolute; bottom:0; left:0; right:0;
        padding:20px 8px 8px;
        background:linear-gradient(to top, rgba(0,0,0,.8) 0%, transparent 100%);
        color:#fff; pointer-events:none;
    }
    .extra-card-title { font-size:10px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .extra-play-icon {
        position:absolute; inset:0; display:flex;
        align-items:center; justify-content:center;
        background:rgba(0,0,0,.2); pointer-events:none;
    }

    /* ---- Pagination ---- */
    .extra-pg-btn {
        border-radius:50%; width:32px; height:32px; padding:0;
        display:flex; align-items:center; justify-content:center;
        font-size:13px;
    }

    /* ---- Empty & Loading ---- */
    .extra-empty {
        grid-column:1/-1; text-align:center;
        padding:40px 20px; color:#9ca3af;
    }

    /* ---- Sub-nav ---- */
    .submod-btn { font-size:11px; border:1px solid #3e444b; border-radius:0; }
    .submod-btn.is-active { background-color:#0c82f9!important; border-color:#0c82f9!important; color:#fff!important; }

    /* ---- flatpickr dark override ---- */
    .flatpickr-calendar {
        background:#1e2227 !important;
        border:1px solid #3e444b !important;
        box-shadow:0 8px 24px rgba(0,0,0,.4) !important;
    }
    .flatpickr-months, .flatpickr-monthSelect-months { background:#1e2227 !important; }
    .flatpickr-month, .flatpickr-current-month,
    .flatpickr-prev-month, .flatpickr-next-month { color:#e5e7eb !important; fill:#e5e7eb !important; }
    .flatpickr-monthSelect-month { color:#d1d5db !important; border-radius:6px !important; }
    .flatpickr-monthSelect-month:hover { background:#2d3748 !important; color:#fff !important; }
    .flatpickr-monthSelect-month.selected { background:#0c82f9 !important; color:#fff !important; }
    .flatpickr-monthSelect-month.flatpickr-disabled { opacity:.35 !important; }
    
    /* ================================================================
       MODAL v4 — STANDARISASI MODAL PREVIEW EXTRA
       ================================================================ */
    .modal-dark-backdrop { background-color: rgba(0,0,0,0.8); }
    
    .mv4-topbar {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 12px 12px 12px 16px; border-bottom: 1px solid #f0f0f0; background: #fff;
    }
    .mv4-caption { flex: 1; min-width: 0; padding-top: 3px; }
    .mv4-caption-name {
        font-size: 13px; font-weight: 700; color: #111;
        display: block; word-break: break-word; white-space: normal; line-height: 1.4; text-align: left;
    }
    .mv4-caption-sub { font-size: 11px; color: #9ca3af; display: block; margin-top: 3px; text-align: left; }
    .mv4-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; padding-top: 2px; }
    
    .mv4-btn {
        height: 44px; min-width: 44px; padding: 0 14px; border-radius: 22px;
        border: 1px solid #e5e7eb; background: #f9fafb; display: inline-flex; align-items: center; justify-content: center;
        gap: 5px; cursor: pointer; flex-shrink: 0; -webkit-tap-highlight-color: transparent; transition: background 0.15s, opacity 0.15s; white-space: nowrap;
    }
    .mv4-btn:active { background: #e5e7eb; }
    .mv4-btn i { font-size: 17px; color: #374151; }
    .mv4-btn-label { font-size: 11px; font-weight: 700; color: #374151; line-height: 1; }
     
    .mv4-btn.mv4-share { background: #0d6efd; border-color: #0d6efd; }
    .mv4-btn.mv4-share i, .mv4-btn.mv4-share .mv4-btn-label { color: #fff; }
    .mv4-btn.mv4-share:active { opacity: 0.85; }
     
    .mv4-btn.mv4-close { background: #fef2f2; border-color: #fecaca; }
    .mv4-btn.mv4-close i, .mv4-btn.mv4-close .mv4-btn-label { color: #dc2626; }
    .mv4-btn.mv4-close:active { background: #fee2e2; }
     
    .mv4-btn.mv4-loading { opacity: 0.6; pointer-events: none; }
    .mv4-btn.mv4-success { background: #16a34a; border-color: #16a34a; }
    .mv4-btn.mv4-success i, .mv4-btn.mv4-success .mv4-btn-label { color: #fff; }
    
    .mv4-nav-row { display: flex; align-items: stretch; background: #0f172a; }
    .mv4-nav-btn {
        width: 52px; min-height: 260px; background: rgba(255,255,255,0.07); border: none;
        display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;
        -webkit-tap-highlight-color: transparent; transition: background 0.15s;
    }
    .mv4-nav-btn:active { background: rgba(255,255,255,0.18); }
    .mv4-nav-btn i { font-size: 22px; color: rgba(255,255,255,0.75); }
    .mv4-nav-btn:disabled { opacity: 0.2; pointer-events: none; }
    
    .mv4-media {
        flex: 1; min-width: 0; background: #0f172a; display: flex; align-items: center; justify-content: center;
        min-height: 260px; position: relative; overflow: hidden;
    }
    .mv4-counter {
        position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%);
        background: rgba(0,0,0,0.5); color: #fff; font-size: 10px; font-weight: 600;
        padding: 3px 10px; border-radius: 20px; pointer-events: none; white-space: nowrap; z-index: 10;
    }
</style>

{{-- =========================================================
     2. HTML
     ========================================================= --}}
<div class="container-fluid px-1 mt-0 pt-1 fade-in">
    {{-- ===== MENU BAR EXTRA ===== --}}
    {{-- (identik konsep dengan filter bar PRODUCT: dark background, border, items rata kiri-kanan) --}}
    <div class="d-flex align-items-center p-2 rounded shadow-sm mb-2"
         style="background-color:#1e2227; border:1px solid #3e444b; gap:8px;">

        {{-- Month Picker --}}
        <div style="position:relative; flex-shrink:0;">
            <i class="bi bi-calendar2-month-fill extra-cal-icon"></i>
            <input type="text" id="extraMonthPicker" class="extra-month-input" placeholder="Pilih Bulan" readonly>
        </div>

        {{-- Folder Tabs (scrollable) --}}
        <div class="extra-folder-scroll" id="extraFolderNav">
            <div class="d-flex align-items-center gap-2 py-1">
                <div class="spinner-border spinner-border-sm text-secondary" style="width:14px;height:14px;"></div>
                <span style="font-size:10px; color:#6b7280;">Memuat menu...</span>
            </div>
        </div>

    </div>

    {{-- ===== CANVAS ===== --}}
    <div id="extraCanvas">
        {{-- Loading awal --}}
        <div id="extraInitMsg" class="d-flex flex-column align-items-center justify-content-center py-5 text-muted fade-in">
            <i class="bi bi-collection mb-2" style="font-size:2rem; opacity:.4;"></i>
            <p style="font-size:12px;">Pilih folder di atas untuk memulai</p>
        </div>

        {{-- Spinner saat load folder --}}
        <div id="extraLoader" class="text-center py-5 d-none">
            <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
            <p class="small text-muted mt-2 fw-bold">Memuat aset...</p>
        </div>

        {{-- Grid Aset --}}
        <div class="extra-grid" id="extraGrid"></div>

        {{-- Pagination --}}
        <div id="extraPagination" class="d-none mt-3 d-flex justify-content-center align-items-center gap-2"></div>
    </div>

</div>

{{-- =========================================================
     MODAL: Preview EXTRA (Versi 4)
     ========================================================= --}}
<div class="modal fade" id="extraPreviewModal" tabindex="-1" aria-hidden="true" style="z-index:1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="bg-white shadow-lg d-flex flex-column overflow-hidden fade-in" style="border-radius:18px;">

                {{-- TOPBAR: caption + 3 tombol inline --}}
                <div class="mv4-topbar">
                    <div class="mv4-caption">
                        <span class="mv4-caption-name" id="extraModalTitle">Judul File</span>
                        <span class="mv4-caption-sub" id="extraModalSubtitle">&nbsp;</span>
                    </div>
                    <div class="mv4-actions">
                        <button class="mv4-btn tap-effect" id="btnExtraDownload" aria-label="Unduh">
                            <i class="bi bi-download"></i>
                            <span class="mv4-btn-label">Unduh</span>
                        </button>
                        <button class="mv4-btn mv4-share tap-effect" id="btnExtraShare" aria-label="Bagikan">
                            <i class="bi bi-share"></i>
                            <span class="mv4-btn-label">Bagikan</span>
                        </button>
                        <button class="mv4-btn mv4-close tap-effect" data-bs-dismiss="modal" aria-label="Tutup">
                            <i class="bi bi-x-lg"></i>
                            <span class="mv4-btn-label">Tutup</span>
                        </button>
                    </div>
                </div>

                {{-- NAV + MEDIA --}}
                <div class="mv4-nav-row" id="extraModalBody">
                    <button class="mv4-nav-btn" id="btnExtraPrev" aria-label="Sebelumnya">
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    <div class="mv4-media" id="extraMediaContent">
                        {{-- Konten diisi oleh JS --}}
                    </div>

                    <button class="mv4-nav-btn" id="btnExtraNext" aria-label="Berikutnya">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                
                {{-- Counter --}}
                <div class="mv4-counter" id="extraPreviewCounter" style="display:none;">1 / 1</div>

            </div>
        </div>
    </div>
</div>

{{-- =========================================================
     3. JAVASCRIPT — EXTRA MODULE
     ========================================================= --}}
<script>
(function () {
    'use strict';

    // =========================================================
    // STATE
    // =========================================================
    const API_URL      = '/proxy/extra-assets';
    const ITEMS_PER_PAGE = 10;

    const state = {
        rootFolders   : [],   // [{name, path, ...}]
        activeFolder  : '',
        allFiles      : [],   // semua file dalam folder aktif
        filteredFiles : [],   // setelah filter bulan
        activeMonth   : '',   // format "YYYY-MM"
        currentPage   : 1,
        currentIndex  : -1,   // index preview aktif di filteredFiles
        previewUrl    : '',
        previewTitle  : ''
    };

    // =========================================================
    // INIT — jalankan setelah DOM siap
    // =========================================================
    (function init() {
        _initFlatpickr();
        _loadRootMenu();

        // Bind tombol modal
        document.getElementById('btnExtraShare').addEventListener('click', function () { _share(this); });
        document.getElementById('btnExtraDownload').addEventListener('click', function () { _download(this); }); // <- TAMBAHAN INI
        document.getElementById('btnExtraPrev').addEventListener('click', function () { _navigate(-1); });
        document.getElementById('btnExtraNext').addEventListener('click', function () { _navigate(1); });

        // Tutup video saat modal ditutup
        document.getElementById('extraPreviewModal').addEventListener('hidden.bs.modal', function () {
            const vid = this.querySelector('video');
            if (vid) vid.pause();
        });
    })();

    // =========================================================
    // FLATPICKR — Month Picker
    // =========================================================
    function _initFlatpickr() {
        const locale = {
            name: 'id',
            weekdays: {
                shorthand: ['Min','Sen','Sel','Rab','Kam','Jum','Sab'],
                longhand:  ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']
            },
            months: {
                shorthand: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],
                longhand:  ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']
            }
        };

        flatpickr('#extraMonthPicker', {
            locale,
            disableMobile  : true,
            altInput       : true,
            altFormat      : 'F Y',
            dateFormat     : 'Y-m',
            maxDate        : 'today',
            defaultDate    : 'today',
            plugins        : [new monthSelectPlugin({ shorthand: false, dateFormat: 'Y-m', altFormat: 'F Y' })],
            onChange(_, dateStr) {
                state.activeMonth  = dateStr;
                state.currentPage  = 1;
                _applyFilterAndRender();
            }
        });

        // Ambil nilai default (bulan ini)
        const input = document.getElementById('extraMonthPicker');
        state.activeMonth = input ? input.value : '';
    }

    // =========================================================
    // ROOT MENU — load folder utama
    // =========================================================
    async function _loadRootMenu() {
        const nav = document.getElementById('extraFolderNav');
        try {
            const res  = await fetch(`${API_URL}?path=`);
            const data = await res.json();
            if (data.error) throw new Error(data.error);

            state.rootFolders = data.filter(i => i.type === 'folder');

            if (state.rootFolders.length === 0) {
                nav.innerHTML = '<span style="font-size:10px; color:#6b7280;">Belum ada folder</span>';
                return;
            }

            _renderFolderNav();

            // Auto-pilih folder pertama
            _selectFolder(state.rootFolders[0].name);

        } catch (e) {
            console.error('_loadRootMenu error:', e);
            nav.innerHTML = '<span style="font-size:10px; color:#ef4444;">Gagal memuat menu</span>';
        }
    }

    function _renderFolderNav() {
        const nav = document.getElementById('extraFolderNav');
        nav.innerHTML = state.rootFolders.map(f => {
            const label    = f.name.replace(/^\d+_/, '').replace(/_/g, ' ');
            const isActive = f.name === state.activeFolder ? 'active' : '';
            return `<div class="extra-tab ${isActive}" onclick="window._extraSelectFolder('${f.name}')">${label}</div>`;
        }).join('');
    }

    // Expose ke window agar bisa dipanggil dari onclick
    window._extraSelectFolder = function (folderName) {
        _selectFolder(folderName);
    };

    async function _selectFolder(folderName) {
        if (state.activeFolder === folderName) return;
        state.activeFolder = folderName;
        state.currentPage  = 1;
        _renderFolderNav();

        const loader   = document.getElementById('extraLoader');
        const grid     = document.getElementById('extraGrid');
        const pagination = document.getElementById('extraPagination');
        const initMsg  = document.getElementById('extraInitMsg');

        if (initMsg) initMsg.classList.add('d-none');
        loader.classList.remove('d-none');
        grid.innerHTML = '';
        pagination.classList.add('d-none');

        try {
            const encodedPath = btoa(folderName);
            const res  = await fetch(`${API_URL}?path=${encodedPath}`);
            const data = await res.json();
            if (data.error) throw new Error(data.error);

            state.allFiles = data.filter(i => i.type === 'file');
            _applyFilterAndRender();

        } catch (e) {
            console.error('_selectFolder error:', e);
            grid.innerHTML = `
                <div class="extra-empty">
                    <i class="bi bi-x-circle mb-2" style="font-size:1.8rem; opacity:.5;"></i>
                    <p class="small mb-0">Gagal memuat isi folder.</p>
                </div>`;
        } finally {
            loader.classList.add('d-none');
        }
    }

    // =========================================================
    // FILTER & RENDER GRID
    // =========================================================
    function _applyFilterAndRender() {
        if (!state.activeMonth) {
            state.filteredFiles = state.allFiles;
        } else {
            state.filteredFiles = state.allFiles.filter(file => {
                if (!file.timestamp) return false;
                const d  = new Date(file.timestamp * 1000);
                const mk = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`;
                return mk === state.activeMonth;
            });
        }
        _renderGrid();
    }

    function _renderGrid() {
        const grid       = document.getElementById('extraGrid');
        const pagination = document.getElementById('extraPagination');
        if (!grid) return;

        if (state.filteredFiles.length === 0) {
            grid.innerHTML = `
                <div class="extra-empty">
                    <i class="bi bi-folder-x mb-2" style="font-size:2rem; opacity:.5;"></i>
                    <p class="small mb-0">Tidak ada aset pada bulan ini.</p>
                </div>`;
            pagination.classList.add('d-none');
            return;
        }

        const start = (state.currentPage - 1) * ITEMS_PER_PAGE;
        const slice = state.filteredFiles.slice(start, start + ITEMS_PER_PAGE);

        grid.innerHTML = slice.map((file, i) => {
            const absIdx     = start + i;
            const cleanTitle = file.name.replace(/\.[^/.]+$/, '');
            const isVideo    = /\.(mp4|webm|ogg|mov)$/i.test(file.name);

            if (isVideo) {
                return `
                    <div class="extra-card fade-in" onclick="window._extraOpenPreview(${absIdx})">
                        <video src="${file.url}#t=0.1" preload="metadata" muted playsinline
                               style="width:100%; height:100%; object-fit:cover;"></video>
                        <div class="extra-play-icon">
                            <i class="bi bi-play-circle-fill text-white" style="font-size:2.2rem; opacity:.85;"></i>
                        </div>
                        <div class="extra-card-overlay">
                            <div class="extra-card-title">${cleanTitle}</div>
                        </div>
                    </div>`;
            } else {
                return `
                    <div class="extra-card fade-in" onclick="window._extraOpenPreview(${absIdx})">
                        <img src="${file.url}" loading="lazy" alt="${cleanTitle}"
                             style="width:100%; height:100%; object-fit:cover;">
                        <div class="extra-card-overlay">
                            <div class="extra-card-title">${cleanTitle}</div>
                        </div>
                    </div>`;
            }
        }).join('');

        _renderPagination();
    }

    function _renderPagination() {
        const pagination = document.getElementById('extraPagination');
        const total      = Math.ceil(state.filteredFiles.length / ITEMS_PER_PAGE);

        if (total <= 1) { pagination.classList.add('d-none'); return; }

        pagination.classList.remove('d-none');
        pagination.innerHTML = `
            <button class="btn btn-light border shadow-sm extra-pg-btn fw-bold text-primary tap-effect"
                    ${state.currentPage === 1 ? 'disabled' : ''}
                    onclick="window._extraChangePage(-1)">
                <i class="bi bi-chevron-left"></i>
            </button>
            <span class="fw-bold text-muted" style="font-size:11px;">
                Hal ${state.currentPage} / ${total}
            </span>
            <button class="btn btn-light border shadow-sm extra-pg-btn fw-bold text-primary tap-effect"
                    ${state.currentPage === total ? 'disabled' : ''}
                    onclick="window._extraChangePage(1)">
                <i class="bi bi-chevron-right"></i>
            </button>`;
    }

    window._extraChangePage = function (dir) {
        state.currentPage += dir;
        _renderGrid();
        // Scroll ke atas canvas
        const canvas = document.getElementById('extraCanvas');
        if (canvas) canvas.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    // =========================================================
    // PREVIEW MODAL
    // =========================================================
    window._extraOpenPreview = function (absIdx) {
        state.currentIndex = absIdx;
        _renderPreview();
        const modal = new bootstrap.Modal(document.getElementById('extraPreviewModal'));
        modal.show();
    };

    function _renderPreview() {
        const file    = state.filteredFiles[state.currentIndex];
        if (!file) return;

        state.previewUrl   = file.url;
        state.previewTitle = file.name;

        const isVideo   = /\.(mp4|webm|ogg|mov)$/i.test(file.name);
        const cleanName = file.name.replace(/\.[^/.]+$/, '').replace(/_/g, ' ');
        const total     = state.filteredFiles.length;
        const cur       = state.currentIndex + 1;

        // ── Caption & Subtitle ──
        document.getElementById('extraModalTitle').innerText = cleanName;

        const ext = (file.name.match(/\.([^.]+)$/) || ['', ''])[1].toUpperCase();
        const subEl = document.getElementById('extraModalSubtitle');
        if (subEl) subEl.innerText = (ext ? ext + ' · ' : '') + cur + ' dari ' + total;

        // ── Counter Overlay ──
        const counter = document.getElementById('extraPreviewCounter');
        if (counter) {
            counter.innerText = cur + ' / ' + total;
            counter.style.display = total > 1 ? 'block' : 'none';
        }

        // ── Nav Buttons ──
        document.getElementById('btnExtraPrev').disabled = (state.currentIndex <= 0);
        document.getElementById('btnExtraNext').disabled = (state.currentIndex >= state.filteredFiles.length - 1);

        const content = document.getElementById('extraMediaContent');
        if (isVideo) {
            content.innerHTML = `
                <video src="${file.url}" controls autoplay playsinline style="width:100%; max-height:75vh; object-fit:contain; background:#000; z-index:2; position:relative;"></video>`;
        } else {
            content.innerHTML = `
                <div id="extraLoadingPreview" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:1;">
                    <div class="spinner-border text-secondary mb-2" role="status"></div>
                    <span class="text-muted fw-bold" style="font-size:11px;">Memuat...</span>
                </div>
                <img src="${file.url}" class="img-fluid shadow-sm"
                     style="max-height:75vh; object-fit:contain; border-radius:4px; z-index:2; position:relative; opacity:0; transition:opacity .3s;"
                     onload="const lp=document.getElementById('extraLoadingPreview'); if(lp) lp.style.display='none'; this.style.opacity=1;">`;
        }
        
        if (counter) document.getElementById('extraMediaContent').appendChild(counter);
    }

    function _navigate(dir) {
        const newIdx = state.currentIndex + dir;
        if (newIdx < 0 || newIdx >= state.filteredFiles.length) return;
        state.currentIndex = newIdx;

        // Animasi fade singkat
        const content = document.getElementById('extraMediaContent');
        if (content) {
            content.style.opacity = '0.3';
            setTimeout(() => {
                _renderPreview();
                content.style.transition = 'opacity .2s';
                content.style.opacity    = '1';
            }, 150);
        } else {
            _renderPreview();
        }
    }

    // =========================================================
    // UNDUH
    // =========================================================
    async function _download(btn) {
        if (!state.previewUrl) return;
        const origHtml = btn.innerHTML;
        try {
            btn.classList.add('mv4-loading');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> <span class="mv4-btn-label">Memuat...</span>';
            btn.disabled  = true;

            const res  = await fetch(state.previewUrl);
            if (!res.ok) throw new Error('Fetch gagal: ' + res.status);
            const blob = await res.blob();
            const url  = URL.createObjectURL(blob);
            const a    = Object.assign(document.createElement('a'), { href: url, style: 'display:none' });
            const ext  = blob.type.split('/')[1] || 'jpg';
            a.download = `${state.previewTitle.replace(/\.[^/.]+$/, '')}-${Date.now()}.${ext}`;
            document.body.appendChild(a); a.click();
            URL.revokeObjectURL(url); a.remove();
            _showSuccess(btn, origHtml, 'Sukses', false);
        } catch (e) {
            console.warn('CORS/Fetch terblokir, mengalihkan ke tab baru:', e);
            
            // FALLBACK JIKA KENA CORS: Paksa buka file langsung lewat browser
            const a = Object.assign(document.createElement('a'), { 
                href: state.previewUrl, 
                target: '_blank', 
                download: state.previewTitle 
            });
            document.body.appendChild(a); 
            a.click();
            a.remove();
            
            _showSuccess(btn, origHtml, 'Dibuka', false);
        }
    }

    // =========================================================
    // SHARE (1 TOMBOL PINTAR: LAPTOP -> UNDUH, HP -> BAGIKAN FISIK)
    // =========================================================
    async function _share(btn) {
        if (!state.previewUrl) return;

        const isVideo  = /\.(mp4|webm|ogg|mov)$/i.test(state.previewTitle);
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        const origHtml = btn.innerHTML;
        btn.classList.add('mv4-loading');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> <span class="mv4-btn-label">Memuat...</span>';
        btn.disabled  = true;

        const proxyFetchUrl = '/product/proxy-file?url=' + encodeURIComponent(state.previewUrl);

        // --- KUNCI FIX: AMBIL EKSTENSI DARI NAMA FILE ASLI ---
        let ext = 'jpg';
        if (state.previewTitle.includes('.')) {
            ext = state.previewTitle.split('.').pop().toLowerCase();
        } else if (isVideo) {
            ext = 'mp4';
        }
        
        const cleanTitle = state.previewTitle.replace(/\.[^/.]+$/, '');
        const finalFileName = `${cleanTitle}-${Date.now()}.${ext}`;

        // Menentukan tipe MIME yang benar agar aplikasi HP (WA/IG) tidak bingung
        const getMimeType = (extension) => {
            if (['mp4', 'mov', 'webm'].includes(extension)) return 'video/mp4';
            if (['png'].includes(extension)) return 'image/png';
            return 'image/jpeg';
        };
        const finalMimeType = getMimeType(ext);

        // ---------------------------------------------------------
        // SKENARIO 1: AKSES DARI LAPTOP / PC
        // ---------------------------------------------------------
        if (!isMobile) {
            try {
                const res  = await fetch(proxyFetchUrl);
                if (!res.ok) throw new Error('Fetch gagal');
                
                const blob = await res.blob();
                const url  = URL.createObjectURL(blob);
                const a    = Object.assign(document.createElement('a'), { href: url, style: 'display:none' });
                
                // Gunakan nama file yang sudah diekstrak ekstensinya (.mp4)
                a.download = finalFileName; 
                document.body.appendChild(a); a.click();
                URL.revokeObjectURL(url); a.remove();
                
                _showSuccess(btn, origHtml, 'Terunduh!', true);
            } catch (e) {
                console.warn('Gagal melewati proxy, mengalihkan ke tab baru:', e);
                const a = Object.assign(document.createElement('a'), { href: state.previewUrl, target: '_blank', download: finalFileName });
                document.body.appendChild(a); a.click(); a.remove();
                _showSuccess(btn, origHtml, 'Dibuka!', true);
            }
            return;
        }

        // ---------------------------------------------------------
        // SKENARIO 2: AKSES DARI HP / TABLET
        // ---------------------------------------------------------
        try {
            if (navigator.share) {
                const res  = await fetch(proxyFetchUrl);
                if (!res.ok) throw new Error('Fetch gagal');
                
                const blob = await res.blob();
                
                // Masukkan nama file dan format MIME yang benar ke dalam paket
                const file = new File([blob], finalFileName, { type: finalMimeType });
                
                const shareData = isVideo 
                    ? { files: [file] } 
                    : { files: [file], title: state.previewTitle, text: state.previewTitle };

                if (navigator.canShare && navigator.canShare(shareData)) {
                    await navigator.share(shareData);
                    btn.innerHTML = origHtml; 
                    btn.disabled = false;
                    return;
                }
            }
            
            await navigator.clipboard.writeText(state.previewUrl);
            _showSuccess(btn, origHtml, 'Link Disalin!', true);

        } catch (e) {
            if (e.name === 'AbortError') { 
                btn.innerHTML = origHtml; 
                btn.disabled = false; 
            } else {
                console.warn('Gagal bagikan fisik karena proxy / memori:', e);
                try {
                    await navigator.clipboard.writeText(state.previewUrl);
                    _showSuccess(btn, origHtml, 'Link Disalin!', true);
                } catch {
                    alert('Gagal membagikan file.');
                    btn.innerHTML = origHtml; 
                    btn.disabled = false;
                }
            }
        }
    }

    function _showSuccess(btn, origHtml, msg) {
        if (!btn) return;
        btn.innerHTML = `<i class="bi bi-check2"></i> <span class="mv4-btn-label" style="color:#fff;">${msg}</span>`;
        btn.classList.add('mv4-success');
        btn.classList.remove('mv4-loading');
        btn.disabled = false;
        setTimeout(() => {
            btn.innerHTML = origHtml;
            btn.classList.remove('mv4-success');
        }, 2500);
    }

    function _resetBtn(btn, origHtml) {
        if (btn) {
            btn.innerHTML = origHtml;
            btn.classList.remove('mv4-loading');
            btn.disabled = false;
        }
    }

})();
</script>