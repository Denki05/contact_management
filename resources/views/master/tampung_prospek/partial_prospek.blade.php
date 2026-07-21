@php
    // 1. Data Provinsi untuk Modal
    $provinces = \App\Models\Province::orderBy('prov_name', 'asc')->get();

    // 2. Mapping Dinamis L1: Zona -> Kategori (Untuk Javascript Dropdown)
    $zoneCatMap = [];
    if ($currentTab === 'L1' && isset($data_by_zone)) {
        foreach ($data_by_zone as $p) {
            $z = strtoupper(trim($p->zone ?? ''));
            if ($z === '') $z = 'TANPA ZONA';
            
            $k = strtoupper(trim($p->kategori ?? ''));
            if ($k === '') $k = 'NONE';
            
            if (!isset($zoneCatMap[$z])) $zoneCatMap[$z] = [];
            if (!in_array($k, $zoneCatMap[$z])) $zoneCatMap[$z][] = $k;
        }
        ksort($zoneCatMap);
        foreach($zoneCatMap as $z => &$cats) {
            sort($cats);
        }
    }
@endphp

@php
    $__activePage = ($currentTab !== 'L1' && isset($data_prospek)) ? $data_prospek->currentPage() : 1;
@endphp
<script>
    window.currentProspekPage = {{ $__activePage }};
    window.currentProspekTab  = '{{ $currentTab }}';
</script>

<div class="ci-wrap">

    {{-- TOOLBAR FILTER --}}
    <div class="ci-toolbar">
        <div class="ci-toolbar-left">
            <span style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;"></span>
        </div>
        <div class="ci-toolbar-right">
            <button class="ci-filter {{ $currentTab=='L1'?'cf-active':'' }}" onclick="window.loadProspekPartial(1,'L1')">Review</button>
            <button class="ci-filter {{ $currentTab=='L2'?'cf-active':'' }}" onclick="window.loadProspekPartial(1,'L2')">Approved</button>
            <button class="ci-filter {{ $currentTab=='L3'?'cf-active':'' }}" onclick="window.loadProspekPartial(1,'L3')">PIC</button>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- L1 → 1 CARD DENGAN SELECT BOX DI HEADER & SUB-HEADER         --}}
    {{-- ============================================================ --}}
    @if($currentTab === 'L1')

        <div class="zc-card mb-4 shadow-sm" style="--zc-color: #3b82f6; --zc-bg: rgba(59,130,246,0.03); border: 1px solid rgba(59,130,246,0.2);">
            
            {{-- HEADER GRUP 1: ZONA & SEARCH --}}
            <div class="zc-header d-flex flex-wrap gap-3 align-items-center" style="background: rgba(0,0,0,0.2); padding: 14px 18px; border-bottom: 1px solid rgba(255,255,255,0.05); cursor: default;">
                <div class="d-flex align-items-center gap-2 flex-grow-1">
                    <div style="background: rgba(59,130,246,0.2); width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #60a5fa;">
                        <i class="bi bi-geo-alt-fill fs-6"></i>
                    </div>
                    <select id="uiSelectZone" class="form-select form-select-sm text-white fw-bold header-select" style="max-width: 350px;">
                        <option value="">[ PILIH ZONA KERJA ]</option>
                        @foreach(array_keys($zoneCatMap) as $zName)
                            <option value="{{ $zName }}">ZONA: {{ $zName }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Fitur Search Global --}}
                <div class="position-relative" style="width: 100%; max-width: 300px;">
                    <i class="bi bi-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.8rem;"></i>
                    <input type="text" id="uiSearchInput" class="form-control form-control-sm text-white" 
                           placeholder="Cari Nama, Provinsi, Kota..." 
                           style="background-color: #1a1d21; border: 1px solid #3e444b; padding-left: 32px; font-size: 0.8rem; border-radius: 6px;" disabled>
                </div>
            </div>

            {{-- HEADER GRUP 2 (SUB-HEADER): KATEGORI --}}
            <div id="uiSubHeader" class="d-none" style="background: rgba(255,255,255,0.02); padding: 10px 18px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-return-right text-secondary ms-2"></i>
                    <div style="background: rgba(245,158,11,0.15); width: 30px; height: 30px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #fbd38d;">
                        <i class="bi bi-tag-fill" style="font-size: 0.75rem;"></i>
                    </div>
                    <select id="uiSelectKat" class="form-select form-select-sm text-white fw-bold header-select" style="max-width: 300px; font-size: 0.85rem;" disabled>
                        <option value="">[ PILIH KATEGORI ]</option>
                    </select>
                    <span id="uiInfoCount" class="badge bg-primary rounded-pill ms-auto d-none" style="font-size: 0.7rem;">0 Data</span>
                </div>
            </div>

            {{-- BODY / AREA KONTEN --}}
            <div class="zc-body p-0">
                
                {{-- State 1: Awal Kosong (Menunggu Zona) --}}
                <div id="uiEmptyWaitZone" class="text-center py-5">
                    <i class="bi bi-inboxes text-secondary" style="font-size:2.5rem; opacity:0.5; display:block; margin-bottom:10px;"></i>
                    <span style="color:#6b7280; font-size:0.85rem;">Area kerja kosong. Silakan buka dropdown <b>[ PILIH ZONA KERJA ]</b> di atas.</span>
                </div>

                {{-- State 2: Zona Dipilih (Menunggu Kategori) --}}
                <div id="uiEmptyWaitKat" class="text-center py-5 d-none">
                    <i class="bi bi-tags text-secondary" style="font-size:2.5rem; opacity:0.5; display:block; margin-bottom:10px;"></i>
                    <span style="color:#6b7280; font-size:0.85rem;">Zona aktif. Silakan pilih <b>[ KATEGORI ]</b> untuk menampilkan data.</span>
                </div>

                {{-- Tabel Data --}}
                <div class="ci-tbl-wrap d-none" id="uiTableContainer" style="border: none; border-radius: 0 0 10px 10px;">
                    <div class="ci-l1-scroll-area">
                        <table class="ci-tbl mb-0">
                            <thead>
                                <tr style="background: rgba(0,0,0,0.3);">
                                    <th style="width:20%; padding-left: 18px;">Provinsi & Kota</th>
                                    <th style="width:25%">Nama & Perusahaan</th>
                                    <th style="width:15%">Kontak</th>
                                    <th style="width:25%">Progress</th>
                                    <th style="width:15%; padding-right: 18px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($data_by_zone))
                                @foreach($data_by_zone as $zi)
                                @php
                                    $isOk = ($zi->nama && $zi->phone && $zi->alamat && $zi->provinsi && $zi->kota) ? 1 : 0;
                                @endphp
                                <tr class="ci-data-row l1-dynamic-row" id="row-prospek-{{ $zi->id }}" 
                                    data-zone="{{ strtoupper(trim($zi->zone ?? 'TANPA ZONA')) }}"
                                    data-kat="{{ strtoupper(trim($zi->kategori ?? 'NONE')) }}"
                                    data-search="{{ strtolower($zi->nama . ' ' . $zi->provinsi . ' ' . $zi->kota . ' ' . $zi->perusahaan) }}"
                                    data-ok="{{ $isOk }}">
                                    
                                    <td style="padding-left: 18px;">
                                        <div style="font-size: 0.75rem; color: #c9d1d9; font-weight: 600;">{{ $zi->provinsi ?? '-' }}</div>
                                        <div style="font-size: 0.72rem; color: #8b949e;">{{ $zi->kota ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="td-name">{{ $zi->nama }}</div>
                                        <div class="td-sub">{{ $zi->perusahaan ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.75rem; color: #c9d1d9;"><i class="bi bi-telephone"></i> {{ $zi->phone ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="progress-container" data-id="{{ $zi->id }}" data-json="{{ json_encode($zi) }}"></div>
                                    </td>
                                    <td style="padding-right: 18px;">
                                        <div id="action-container-{{ $zi->id }}"></div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination L1 sekarang full client-side, dibangun otomatis oleh JS processFilter() --}}
                    <div class="d-flex justify-content-center align-items-center gap-2 py-3 d-none" id="uiL1PaginationWrap">
                        <button class="btn btn-sm btn-dark" id="uiL1PgFirst" onclick="window.gotoL1Page(1)">&laquo;</button>
                        <button class="btn btn-sm btn-dark" id="uiL1PgPrev" onclick="window.gotoL1Page(window.l1PaginationState.currentPage - 1)">&lsaquo;</button>
                        <span class="text-white fw-bold px-3" id="uiL1PgInfo">1/1</span>
                        <button class="btn btn-sm btn-dark" id="uiL1PgNext" onclick="window.gotoL1Page(window.l1PaginationState.currentPage + 1)">&rsaquo;</button>
                        <button class="btn btn-sm btn-dark" id="uiL1PgLast" onclick="window.gotoL1Page(window.l1PaginationState.totalPages)">&raquo;</button>
                    </div>

                    {{-- Pesan Data Tidak Ditemukan saat Search/Filter --}}
                    <div id="uiNoResult" class="text-center py-5 d-none" style="color:#6b7280; font-size:0.85rem;">
                        <i class="bi bi-search" style="font-size:2rem; display:block; margin-bottom:8px; opacity:.4;"></i>
                        Tidak ada prospek yang sesuai di pencarian/kategori ini.
                    </div>
                </div>
            </div>
        </div>

    {{-- ============================================================ --}}
    {{-- L2 & L3 → TABEL BIASA (tidak berubah)                        --}}
    {{-- ============================================================ --}}
    @else

        {{-- Toolbar Bulk Action — HANYA untuk tab L3, diletakkan SEKALI di atas tabel --}}
        @if($currentTab === 'L3')
        <div id="bulkActionToolbar" class="d-flex align-items-center gap-2 mb-2 p-2 rounded"
             style="background:rgba(255,255,255,0.02); border:1px solid #30363d;">
            <span style="font-size:0.75rem; color:#6b7280;">
                <i class="bi bi-check2-square me-1"></i>
                <span id="bulkSelectedCount">0</span> data dipilih
            </span>

            <div class="ms-auto d-flex gap-2">
                <button type="button" id="btnBulkSetPic" class="btn btn-sm btn-outline-warning rounded-pill px-3" disabled
                    onclick="window.bulkActionSetPic()" style="font-size:0.72rem;">
                    <i class="bi bi-person-add me-1"></i> Set PIC (Massal)
                </button>
                <button type="button" id="btnBulkNaikkanMis" class="btn btn-sm btn-outline-info rounded-pill px-3" disabled
                    onclick="window.bulkActionNaikkanMis()" style="font-size:0.72rem;">
                    <i class="bi bi-arrow-up-circle me-1"></i> Naikkan ke MIS (Massal)
                </button>
            </div>
        </div>
        @endif

        <div class="ci-tbl-wrap">
            <table class="ci-tbl">
                <thead>
                    <tr>
                        @if($currentTab === 'L3')
                        <th style="width:3%; text-align:center;">
                            <input type="checkbox" id="bulkSelectAll" class="form-check-input" style="cursor:pointer;" onchange="window.toggleBulkSelectAll(this)">
                        </th>
                        @endif
                        <th style="width:5%">No</th>
                        <th style="width:32%">Nama & Perusahaan</th>
                        <th style="width:25%">Kontak & Lokasi</th>
                        <th style="width:20%">Progress</th>
                        <th style="width:15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $currentGroup = ''; @endphp
                    @forelse($data_prospek as $index => $prospek)
                        @php $groupName = ($prospek->zone ?: 'Tanpa Zone').' — '.($prospek->kota ?: 'Tanpa Kota'); @endphp
                        @if($currentGroup !== $groupName)
                            <tr style="background:rgba(255,255,255,0.03);">
                                <td colspan="{{ $currentTab === 'L3' ? 6 : 5 }}" style="padding:8px 10px;font-size:10px;color:#0c82f9;font-weight:700;text-transform:uppercase;">
                                    <i class="bi bi-geo-alt-fill"></i> {{ $groupName }}
                                </td>
                            </tr>
                            @php $currentGroup = $groupName; @endphp
                        @endif
                        <tr class="ci-data-row" id="row-prospek-{{ $prospek->id }}">
                            @if($currentTab === 'L3')
                            <td class="text-center" onclick="event.stopPropagation();">
                                <input type="checkbox" class="form-check-input bulk-row-checkbox" style="cursor:pointer;"
                                    data-id="{{ $prospek->id }}"
                                    data-source="{{ strtoupper(trim($prospek->source ?? '')) }}"
                                    onchange="window.updateBulkToolbar()">
                            </td>
                            @endif
                            <td class="text-center" style="color:#6b7280;">
                                {{ ($data_prospek->currentPage()-1)*$data_prospek->perPage()+$loop->iteration }}
                            </td>
                            <td>
                                <div class="td-name">{{ $prospek->nama }}</div>
                                <div class="td-sub">{{ $prospek->perusahaan ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="td-small"><i class="bi bi-telephone"></i> {{ $prospek->phone ?? '-' }}</div>
                                <div class="td-sub">{{ $prospek->kota ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="progress-container"
                                     data-id="{{ $prospek->id }}"
                                     data-json="{{ json_encode($prospek) }}"></div>
                            </td>
                            <td><div id="action-container-{{ $prospek->id }}"></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ $currentTab === 'L3' ? 6 : 5 }}" class="ci-empty"><p>Tidak ada data ditemukan</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($data_prospek->hasPages())
            <div class="d-flex justify-content-center align-items-center">
            
                <button class="btn btn-sm btn-dark"
                    {{ $data_prospek->onFirstPage() ? 'disabled' : '' }}
                    onclick="window.loadProspekPartial(1,'{{ $currentTab }}')">
                    &laquo;
                </button>
            
                <button class="btn btn-sm btn-dark"
                    {{ $data_prospek->onFirstPage() ? 'disabled' : '' }}
                    onclick="window.loadProspekPartial({{ $data_prospek->currentPage()-1 }},'{{ $currentTab }}')">
                    &lsaquo;
                </button>
            
                <span class="text-white fw-bold px-3">
                    {{ $data_prospek->currentPage() }}/{{ $data_prospek->lastPage() }}
                </span>
            
                <button class="btn btn-sm btn-dark"
                    {{ !$data_prospek->hasMorePages() ? 'disabled' : '' }}
                    onclick="window.loadProspekPartial({{ $data_prospek->currentPage()+1 }},'{{ $currentTab }}')">
                    &rsaquo;
                </button>
            
                <button class="btn btn-sm btn-dark"
                    {{ !$data_prospek->hasMorePages() ? 'disabled' : '' }}
                    onclick="window.loadProspekPartial({{ $data_prospek->lastPage() }},'{{ $currentTab }}')">
                    &raquo;
                </button>
            
            </div>
            @endif
    @endif

</div>

<div class="modal fade modal-dark" id="modalEditProspek" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg" style="background: #161b22; border: 1px solid #30363d; border-radius: 12px;">
            
            {{-- HEADER --}}
            <div class="modal-header py-2 px-3 border-0" style="background: #0d1117; border-radius: 12px 12px 0 0; border-bottom: 1px solid #30363d !important;">
                <div class="d-flex align-items-center gap-2">
                    <div style="background:rgba(217,119,6,0.15);width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-pencil-square" style="color:#f59e0b;font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" style="font-size:0.85rem;color:#e6edf3;">Lengkapi Data Prospek</h6>
                        <div style="font-size:0.6rem;color:#6b7280;">Update & Validasi Data — Admin / Back Office</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" style="font-size:0.7rem;" data-bs-dismiss="modal"></button>
            </div>

            {{-- TAB NAV --}}
            <div style="background:#0d1117; border-bottom:1px solid #30363d; padding:0 16px;">
                <div class="d-flex" id="edit-tab-nav">
                    <button type="button" class="tambah-tab-btn active" data-edittab="1" onclick="switchEditTab(1)">
                        <i class="bi bi-person-badge me-1"></i> Data PIC
                        <span class="tambah-tab-indicator" id="edit-tab-ind-1"></span>
                    </button>
                    <button type="button" class="tambah-tab-btn" data-edittab="2" onclick="switchEditTab(2)">
                        <i class="bi bi-building me-1"></i> Info Bisnis
                        <span class="tambah-tab-indicator" id="edit-tab-ind-2"></span>
                    </button>
                    <button type="button" class="tambah-tab-btn" data-edittab="3" onclick="switchEditTab(3)">
                        <i class="bi bi-geo-alt me-1"></i> Lokasi & Geotag
                        <span class="tambah-tab-indicator" id="edit-tab-ind-3"></span>
                    </button>
                </div>
            </div>

            <div class="modal-body p-0">
                <form id="form-edit-prospek" onsubmit="event.preventDefault(); window.saveProspekEdit();">
                    <input type="hidden" id="edit-prospek-id" name="id">

                    {{-- ======== TAB 1: DATA PIC ======== --}}
                    <div class="tambah-tab-content p-4" id="edit-tab-content-1">
                        {{-- Status --}}
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 rounded event-input">
                                    <input type="hidden" name="visit" value="0">
                                    <input class="form-check-input mt-0 me-2" type="checkbox" id="edit-visit" name="visit" value="1" style="background-color:#1a1d21;border-color:#484f58;cursor:pointer;width:16px;height:16px;">
                                    <label class="fw-medium mb-0 user-select-none d-flex align-items-center gap-2" for="edit-visit" style="font-size:0.75rem;cursor:pointer;color:#e5e7eb;">
                                        <i class="bi bi-person-check" style="color:#f59e0b;"></i> Pernah Divisit?
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 rounded event-input">
                                    <input type="hidden" name="transaksi" value="0">
                                    <input class="form-check-input mt-0 me-2" type="checkbox" id="edit-transaksi" name="transaksi" value="1" style="background-color:#1a1d21;border-color:#484f58;cursor:pointer;width:16px;height:16px;">
                                    <label class="fw-medium mb-0 user-select-none d-flex align-items-center gap-2" for="edit-transaksi" style="font-size:0.75rem;cursor:pointer;color:#e5e7eb;">
                                        <i class="bi bi-bag-check" style="color:#f59e0b;"></i> Sudah Transaksi?
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Identitas --}}
                        <div class="tambah-section-label" style="color:#f59e0b; border-left-color:#d97706;">
                            <i class="bi bi-person-badge me-1"></i> Identitas
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="tambah-label">Nama PIC <span class="text-danger">*</span></label>
                                <input type="text" id="edit-nama" name="nama" class="form-control event-input form-control-sm" required>
                            </div>
                            <div class="col-md-4">
                                <label class="tambah-label">Jabatan</label>
                                <input type="text" id="edit-jabatan" name="jabatan" class="form-control event-input form-control-sm">
                            </div>
                            <div class="col-md-4">
                                <label class="tambah-label">No. Handphone <span class="text-danger">*</span></label>
                                <input type="text" id="edit-phone" name="phone" class="form-control event-input form-control-sm" required>
                            </div>
                        </div>

                        {{-- Foto --}}
                        <div class="tambah-section-label" style="color:#f59e0b; border-left-color:#d97706;">
                            <i class="bi bi-images me-1"></i> Foto / Bukti Gambar
                            <span class="text-secondary text-lowercase ms-1" style="font-size:0.6rem;letter-spacing:normal;">(Maks. 3 · JPG/PNG · ≤2MB)</span>
                            <span id="img-counter" class="ms-auto" style="color:#f59e0b;font-size:0.65rem;font-weight:400;">0/3</span>
                        </div>
                        <div id="img-dropzone" style="border:2px dashed #30363d;border-radius:8px;padding:18px 12px;text-align:center;cursor:pointer;transition:all 0.2s ease;background:#0d1117;position:relative;user-select:none;">
                            <i class="bi bi-cloud-arrow-up-fill" style="font-size:1.6rem;color:#484f58;display:block;margin-bottom:5px;"></i>
                            <div style="font-size:0.72rem;color:#8b949e;line-height:1.5;">
                                Drag & drop di sini, atau <span style="color:#f59e0b;font-weight:600;">klik untuk pilih</span>
                            </div>
                            <div style="font-size:0.65rem;color:#484f58;margin-top:3px;">
                                Bisa juga <kbd style="background:#161b22;border:1px solid #30363d;border-radius:3px;padding:1px 4px;font-size:0.6rem;color:#8b949e;">Ctrl+V</kbd> paste gambar
                            </div>
                            <input type="file" id="img-file-input" accept="image/*" multiple style="display:none;">
                        </div>
                        <div id="img-preview-container" class="d-flex gap-2 mt-2 flex-wrap"></div>
                        <input type="hidden" id="edit-images" name="images" value="[]">

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold" onclick="switchEditTab(2)" style="font-size:0.75rem;">
                                Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ======== TAB 2: INFO BISNIS ======== --}}
                    <div class="tambah-tab-content p-4 d-none" id="edit-tab-content-2">
                        <div class="tambah-section-label" style="color:#f59e0b; border-left-color:#d97706;">
                            <i class="bi bi-building me-1"></i> Identitas Bisnis
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="tambah-label">Perusahaan / Toko</label>
                                <input type="text" id="edit-perusahaan" name="perusahaan" class="form-control event-input form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="tambah-label">Zone</label>
                                <select name="zone" id="edit-zone" class="form-select event-input form-select-sm">
                                    <option value="">Pilih Zone</option>
                                    <option value="JABODETABEK">JABODETABEK</option>
                                    <option value="JABAR">JABAR</option>
                                    <option value="JATENG - JATIM">JATENG - JATIM</option>
                                    <option value="SUMATERA">SUMATERA</option>
                                    <option value="BALI - KALIMANTAN - SULAWESI">BALI - KALIMANTAN - SULAWESI</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="tambah-label">Kategori</label>
                                <select name="kategori" id="edit-kategori" class="form-select event-input form-select-sm">
                                    <option value="">Pilih Kategori</option>
                                    <option value="Umum (semua prospek)">Umum (semua prospek)</option>
                                    <option value="Agen - perfumery trusted">Agen - perfumery trusted</option>
                                    <option value="Bigreseller">Bigreseller</option>
                                    <option value="Smreseller">Smreseller</option>
                                    <option value="Bigperfumery">Bigperfumery</option>
                                    <option value="Smperfumery">Smperfumery</option>
                                    <option value="Home industri kosmetik">Home industri kosmetik</option>
                                    <option value="Home industri pkrt">Home industri pkrt</option>
                                    <option value="Industri kosmetik (PPN)">Industri kosmetik (PPN)</option>
                                    <option value="Industri pkrt (PPN)">Industri pkrt (PPN)</option>
                                    <option value="Umum project (semua prospek)">Umum project (semua prospek)</option>
                                </select>
                            </div>
                        </div>

                        <div class="tambah-section-label mt-2" style="color:#f59e0b; border-left-color:#d97706;">
                            <i class="bi bi-diagram-3 me-1"></i> Model & Channel
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="tambah-label">Model Bisnis</label>
                                <select name="model_bisnis" id="edit-model" class="form-select event-input form-select-sm">
                                    <option value="">Pilih Model</option>
                                    <option value="Open Label">Open Label</option>
                                    <option value="Close Label">Close Label</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="tambah-label">Media Sosial</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text border-0" style="background:#21262d;color:#8b949e;"><i class="bi bi-instagram"></i></span>
                                    <input type="text" name="media_sosial" id="edit-sosmed" class="form-control event-input border-start-0" placeholder="IG / FB / TikTok">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="tambah-label">Marketplace</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text border-0" style="background:#21262d;color:#8b949e;"><i class="bi bi-shop-window"></i></span>
                                    <input type="text" name="marketplace" id="edit-marketplace" class="form-control event-input border-start-0" placeholder="Shopee / Tokopedia">
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-8">
                                <label class="tambah-label">Toko Multicabang <span class="text-secondary text-lowercase" style="font-size:0.6rem;">(Opsional)</span></label>
                                <textarea name="toko_multicabang" id="edit-multicabang" class="form-control event-input" rows="2" style="font-size:0.8rem;min-height:50px;"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="tambah-label">Pengajuan</label>
                                <input type="text" name="pengajuan" id="edit-pengajuan" class="form-control event-input form-control-sm">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="switchEditTab(1)" style="font-size:0.75rem;">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold" onclick="switchEditTab(3)" style="font-size:0.75rem;">
                                Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ======== TAB 3: LOKASI & GEOTAG ======== --}}
                    <div class="tambah-tab-content p-4 d-none" id="edit-tab-content-3">
                        <div class="tambah-section-label" style="color:#f59e0b; border-left-color:#d97706;">
                            <i class="bi bi-crosshair me-1"></i> Koordinat Geotag (Opsional)
                            <span class="ms-auto" id="edit-geo-status-badge"></span>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5">
                                <label class="tambah-label">Latitude</label>
                                <input type="text" id="edit-lat" name="latitude" class="form-control event-input form-control-sm" placeholder="-7.2575000" oninput="onEditCoordsInput()">
                            </div>
                            <div class="col-md-5">
                                <label class="tambah-label">Longitude</label>
                                <input type="text" id="edit-lng" name="longitude" class="form-control event-input form-control-sm" placeholder="112.7521000" oninput="onEditCoordsInput()">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="edit-btn-autofill" class="btn btn-sm btn-primary w-100 fw-bold rounded" onclick="doEditReverseGeocode()" style="font-size:0.72rem;height:31px;" disabled>
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>

                        <div id="edit-map-wrap" class="d-none mb-3">
                            <div id="edit-map" style="height:150px;border-radius:8px;border:1px solid #30363d;"></div>
                        </div>

                        <div class="tambah-section-label mt-3" style="color:#f59e0b; border-left-color:#d97706;">
                            <i class="bi bi-map me-1"></i> Wilayah <span class="text-danger">*</span>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-3">
                                <label class="tambah-label">Provinsi</label>
                                <select name="provinsi" id="edit-provinsi" class="form-select event-input form-select-sm select2-modal" onchange="window.fetchRegionModal('kota', this)">
                                    <option value="" data-id="">Pilih Provinsi</option>
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov->prov_name }}" data-id="{{ $prov->prov_id }}">{{ $prov->prov_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="tambah-label">Kota / Kab</label>
                                <select name="kota" id="edit-kota" class="form-select event-input form-select-sm select2-modal" onchange="window.fetchRegionModal('kecamatan', this)">
                                    <option value="" data-id="" selected>Pilih Kota</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="tambah-label">Kecamatan</label>
                                <select name="kecamatan" id="edit-kecamatan" class="form-select event-input form-select-sm select2-modal" onchange="window.fetchRegionModal('kelurahan', this)">
                                    <option value="" data-id="" selected>Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="tambah-label">Kelurahan</label>
                                <select name="kelurahan" id="edit-kelurahan" class="form-select event-input form-select-sm select2-modal">
                                    <option value="" data-id="" selected>Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-12">
                                <label class="tambah-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <input type="text" id="edit-alamat" name="alamat" class="form-control event-input form-control-sm" required>
                            </div>
                        </div>

                        <input type="hidden" name="geo_source" id="edit-geo-source" value="">
                        
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="switchEditTab(2)" style="font-size:0.75rem;">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-sm btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm" onclick="$('#form-edit-prospek').submit()" style="font-size:0.75rem;">
                                <i class="bi bi-save2 me-1"></i> Simpan Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer py-2 px-3 border-0 d-flex justify-content-between" style="background: #0d1117; border-top: 1px solid #30363d !important; border-radius: 0 0 12px 12px;">
                <small class="text-warning" style="font-size: 0.65rem;"><i class="bi bi-shield-exclamation"></i> Tombol lanjut otomatis muncul jika data lengkap.</small>
                <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1" style="font-size: 0.75rem;" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-dark" id="modalReviewSpv" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg" style="background-color: #1a1d21; border: 1px solid #3e444b; color: #fff;">
            <div class="modal-header py-2" style="background-color: #16191d; border-bottom: 1px solid #3e444b;">
                <h6 class="modal-title fw-bold mb-0 text-warning"><i class="bi bi-clipboard-check me-2"></i>Review Prospek (SPV)</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="spv-prospek-id">
                
                <div class="row mb-4">
                    <div class="col-md-7">
                        <h5 class="fw-bold text-white mb-1" id="spv-nama-pic">Nama PIC</h5>
                        <div class="text-white-50 mb-2" id="spv-perusahaan">Nama Perusahaan</div>
                        <div class="small text-white-50"><i class="bi bi-telephone me-1"></i> <span id="spv-phone"></span></div>
                        <div class="small text-white-50"><i class="bi bi-geo-alt me-1"></i> <span id="spv-alamat"></span></div>
                    </div>
                    <div class="col-md-5">
                        <div class="p-3 rounded-3" style="background-color: #1e2227; border: 1px dashed #3e444b;">
                            <label class="text-white-50 ms-1 mb-1 fw-medium" style="font-size: 0.65rem;">Pengajuan (Diajukan Oleh):</label>
                            <div class="small text-white" id="spv-pengajuan" style="min-height: 40px;">-</div>
                        </div>
                    </div>
                </div>

                <!--<h6 class="border-bottom border-secondary pb-2 mb-3" style="font-size: 0.8rem; color: #adb5bd;">Kelengkapan Final L3 (Oleh SPV)</h6>-->
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="text-white-50 ms-1 mb-1 fw-medium" style="font-size: 0.65rem;">PIC <span class="text-warning">*</span></label>
                        <input type="text" id="spv-input-pic" class="form-control form-control-sm text-white" style="background-color: #1e2227; border: 1px solid #3e444b; font-size: 0.75rem;" placeholder="Masukkan nama PIC">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-white-50 ms-1 mb-1 fw-medium" style="font-size: 0.65rem;">Officer / AO <span class="text-warning">*</span></label>
                        <input type="text" id="spv-input-officer" class="form-control form-control-sm text-white" style="background-color: #1e2227; border: 1px solid #3e444b; font-size: 0.75rem;" placeholder="Masukkan nama Officer">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-white-50 ms-1 mb-1 fw-medium" style="font-size: 0.65rem;">Kategori Prospek</label>
                        <select id="spv-input-kategori" class="form-select form-select-sm text-white" style="background-color: #1e2227; border: 1px solid #3e444b; font-size: 0.75rem;">
                            <option value="">Pilih Kategori</option>
                                <option value="Umum (semua prospek)">Umum (semua prospek)</option>
                                <option value="Agen - perfumery trusted">Agen - perfumery trusted</option>
                                <option value="Bigreseller">Bigreseller</option>
                                <option value="Smreseller">Smreseller</option>
                                <option value="Bigperfumery">Bigperfumery</option>
                                <option value="Smperfumery">Smperfumery</option>
                                <option value="Home industri kosmetik">Home industri kosmetik</option>
                                <option value="Home industri pkrt">Home industri pkrt</option>
                                <option value="Industri kosmetik (PPN)">Industri kosmetik (PPN)</option>
                                <option value="Industri pkrt (PPN)">Industri pkrt (PPN)</option>
                                <option value="Umum project (semua prospek)">Umum project (semua prospek)</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="text-white-50 ms-1 mb-1 fw-medium" style="font-size: 0.65rem;">ZONE</label>
                        <select id="spv-input-zone" class="form-select form-select-sm text-white" style="background-color: #1e2227; border: 1px solid #3e444b; font-size: 0.75rem;">
                            <option value="">Pilih Zone</option>
                            <option value="JABODETABEK">JABODETABEK</option>
                            <option value="JABAR">JABAR</option>
                            <option value="JATENG - JATIM">JATENG - JATIM</option>
                            <option value="SUMATERA">SUMATERA</option>
                            <option value="BALI - KALIMANTAN - SULAWESI">BALI - KALIMANTAN - SULAWESI</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 d-flex justify-content-between" style="background-color: #16191d; border-top: 1px solid #3e444b;">
                <button type="button" class="btn btn-sm btn-outline-danger px-4 rounded-pill fw-bold" onclick="actionTolakSpv()">
                    <i class="bi bi-x-circle me-1"></i> Tolak Data
                </button>
                <button type="button" class="btn btn-sm btn-success px-4 rounded-pill fw-bold" onclick="actionApproveSpv()">
                    <i class="bi bi-check2-all me-1"></i> Setujui & Mutasi L3
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-dark" id="modalApprovedProspek" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg" style="background:#161b22;border:1px solid #30363d;border-radius:12px;">

            {{-- HEADER --}}
            <div class="modal-header py-2 px-3 border-0"
                style="background:#0d1117;border-radius:12px 12px 0 0;border-bottom:1px solid #30363d !important;">
                <div class="d-flex align-items-center gap-2">
                    <div style="background:rgba(34,197,94,0.15);width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-clipboard-check-fill" style="color:#4ade80;font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" style="font-size:0.85rem;color:#e6edf3;">
                            Review Data Prospek
                        </h6>
                        <div style="font-size:0.6rem;color:#6b7280;">
                            Verifikasi oleh SPV — Data bersifat readonly
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill" style="background:rgba(245,158,11,0.15);color:#f59e0b;font-size:0.65rem;">
                        <i class="bi bi-clock me-1"></i> Menunggu Review
                    </span>
                    <button type="button" class="btn-close btn-close-white" style="font-size:0.7rem;" data-bs-dismiss="modal"></button>
                </div>
            </div>

            {{-- TAB NAV --}}
            <div style="background:#0d1117;border-bottom:1px solid #30363d;padding:0 16px;">
                <div class="d-flex">
                    <button type="button" class="approved-tab-btn active" data-aptab="1" onclick="switchApprovedTab(1)">
                        <i class="bi bi-person-badge me-1"></i> Data PIC
                    </button>
                    <button type="button" class="approved-tab-btn" data-aptab="2" onclick="switchApprovedTab(2)">
                        <i class="bi bi-building me-1"></i> Info Bisnis
                    </button>
                    <button type="button" class="approved-tab-btn" data-aptab="3" onclick="switchApprovedTab(3)">
                        <i class="bi bi-geo-alt me-1"></i> Lokasi & Geotag
                    </button>
                </div>
            </div>

            <div class="modal-body p-0">
                <input type="hidden" id="approved-prospek-id">

                {{-- ===== TAB 1: DATA PIC (READONLY) ===== --}}
                <div class="approved-tab-content p-4" id="approved-tab-1">

                    {{-- Status badges --}}
                    <div class="d-flex gap-2 mb-3">
                        <div id="ap-badge-visit" class="d-none">
                            <span class="badge rounded-pill px-3 py-2" style="background:rgba(59,130,246,0.15);color:#60a5fa;font-size:0.72rem;">
                                <i class="bi bi-person-check me-1"></i> Pernah Divisit
                            </span>
                        </div>
                        <div id="ap-badge-transaksi" class="d-none">
                            <span class="badge rounded-pill px-3 py-2" style="background:rgba(34,197,94,0.15);color:#4ade80;font-size:0.72rem;">
                                <i class="bi bi-bag-check me-1"></i> Sudah Transaksi
                            </span>
                        </div>
                        <div id="ap-badge-no-status" class="d-none">
                            <span class="badge rounded-pill px-3 py-2" style="background:rgba(107,114,128,0.15);color:#9ca3af;font-size:0.72rem;">
                                <i class="bi bi-dash-circle me-1"></i> Belum Visit / Transaksi
                            </span>
                        </div>
                    </div>

                    {{-- Identitas --}}
                    <div class="ap-section-label">
                        <i class="bi bi-person-badge me-1"></i> Identitas
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="ap-label">Nama PIC</label>
                            <div class="ap-readonly" id="ap-nama">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="ap-label">Jabatan</label>
                            <div class="ap-readonly" id="ap-jabatan">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="ap-label">No. Handphone</label>
                            <div class="ap-readonly" id="ap-phone">—</div>
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div class="ap-section-label">
                        <i class="bi bi-images me-1"></i> Foto / Bukti Gambar
                        <span id="ap-foto-count" class="ms-auto" style="color:#6b7280;font-size:0.65rem;font-weight:400;"></span>
                    </div>
                    <div id="ap-foto-wrap" class="d-flex gap-2 flex-wrap mb-2">
                        <div class="ap-foto-empty">
                            <i class="bi bi-image" style="font-size:1.5rem;color:#3e444b;display:block;margin-bottom:4px;"></i>
                            <span style="font-size:0.7rem;color:#6b7280;">Tidak ada foto</span>
                        </div>
                    </div>

                    {{-- Nav --}}
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4"
                            onclick="switchApprovedTab(2)" style="font-size:0.75rem;">
                            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                {{-- ===== TAB 2: INFO BISNIS (READONLY) ===== --}}
                <div class="approved-tab-content p-4 d-none" id="approved-tab-2">

                    <div class="ap-section-label">
                        <i class="bi bi-building me-1"></i> Identitas Bisnis
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="ap-label">Perusahaan / Toko</label>
                            <div class="ap-readonly" id="ap-perusahaan">—</div>
                        </div>
                        <div class="col-md-3">
                            <label class="ap-label">Zone</label>
                            <div class="ap-readonly" id="ap-zone">—</div>
                        </div>
                        <div class="col-md-3">
                            <label class="ap-label">Kategori</label>
                            <div class="ap-readonly" id="ap-kategori">—</div>
                        </div>
                    </div>

                    <div class="ap-section-label">
                        <i class="bi bi-diagram-3 me-1"></i> Model & Channel
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="ap-label">Model Bisnis</label>
                            <div class="ap-readonly" id="ap-model">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="ap-label">Media Sosial</label>
                            <div class="ap-readonly" id="ap-sosmed">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="ap-label">Marketplace</label>
                            <div class="ap-readonly" id="ap-marketplace">—</div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-8">
                            <label class="ap-label">Toko Multicabang</label>
                            <div class="ap-readonly" id="ap-multicabang" style="min-height:44px;">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="ap-label">Pengajuan</label>
                            <div class="ap-readonly" id="ap-pengajuan">—</div>
                        </div>
                    </div>

                    {{-- Nav --}}
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                            onclick="switchApprovedTab(1)" style="font-size:0.75rem;">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4"
                            onclick="switchApprovedTab(3)" style="font-size:0.75rem;">
                            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                {{-- ===== TAB 3: LOKASI & GEOTAG (READONLY) ===== --}}
                <div class="approved-tab-content p-4 d-none" id="approved-tab-3">

                    {{-- Geo Info Badge --}}
                    <div id="ap-geo-info-wrap" class="p-2 rounded mb-3 d-none"
                        style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.2);">
                        <div class="d-flex flex-wrap gap-3" id="ap-geo-info-row">
                        </div>
                    </div>
                    <div id="ap-geo-pending-wrap" class="p-2 rounded mb-3 d-none"
                        style="background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.2);font-size:0.75rem;color:#f59e0b;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Geotag belum tersedia untuk data ini.
                    </div>

                    {{-- Mini Map --}}
                    <div id="ap-map-wrap" class="d-none mb-3">
                        <div id="ap-map" style="height:160px;border-radius:8px;border:1px solid #30363d;"></div>
                        <div style="font-size:0.65rem;color:#6b7280;margin-top:4px;text-align:center;">
                            <i class="bi bi-lock me-1"></i> Peta readonly — tidak dapat digeser
                        </div>
                    </div>

                    {{-- Koordinat --}}
                    <div class="ap-section-label">
                        <i class="bi bi-crosshair me-1"></i> Koordinat
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="ap-label">Latitude</label>
                            <div class="ap-readonly" id="ap-lat">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="ap-label">Longitude</label>
                            <div class="ap-readonly" id="ap-lng">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="ap-label">Akurasi GPS</label>
                            <div class="ap-readonly" id="ap-accuracy">—</div>
                        </div>
                    </div>

                    {{-- Wilayah --}}
                    <div class="ap-section-label">
                        <i class="bi bi-map me-1"></i> Wilayah
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="ap-label">Provinsi</label>
                            <div class="ap-readonly" id="ap-provinsi">—</div>
                        </div>
                        <div class="col-md-3">
                            <label class="ap-label">Kota / Kab</label>
                            <div class="ap-readonly" id="ap-kota">—</div>
                        </div>
                        <div class="col-md-3">
                            <label class="ap-label">Kecamatan</label>
                            <div class="ap-readonly" id="ap-kecamatan">—</div>
                        </div>
                        <div class="col-md-3">
                            <label class="ap-label">Kelurahan</label>
                            <div class="ap-readonly" id="ap-kelurahan">—</div>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="ap-label">Alamat Lengkap</label>
                            <div class="ap-readonly" id="ap-alamat">—</div>
                        </div>
                    </div>

                    {{-- Nav --}}
                    <div class="d-flex justify-content-start mt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                            onclick="switchApprovedTab(2)" style="font-size:0.75rem;">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </button>
                    </div>
                </div>

            </div>

            {{-- FOOTER — Tombol aksi SPV --}}
            <div class="modal-footer py-2 px-3 border-0 d-flex justify-content-between"
                style="background:#0d1117;border-top:1px solid #30363d !important;border-radius:0 0 12px 12px;">
                <button type="button"
                    class="btn btn-sm btn-outline-danger rounded-pill px-4 fw-bold"
                    style="font-size:0.75rem;" onclick="actionTolakApproved()">
                    <i class="bi bi-x-circle me-1"></i> Tolak & Kembalikan
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1"
                        style="font-size:0.75rem;" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i> Tutup
                    </button>
                    <button type="button"
                        class="btn btn-sm btn-success rounded-pill px-4 fw-bold shadow-sm"
                        style="font-size:0.75rem;" onclick="actionApproveToL2()">
                        <i class="bi bi-check2-all me-1"></i> Approve & Lanjut ke PIC
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade modal-dark" id="modalPicProspek" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content shadow-lg" style="background:#161b22;border:1px solid #30363d;border-radius:12px;">

            {{-- HEADER --}}
            <div class="modal-header py-2 px-3 border-0"
                style="background:#0d1117;border-radius:12px 12px 0 0;border-bottom:1px solid #30363d !important;">
                <div class="d-flex align-items-center gap-2">
                    <div style="background:rgba(139,92,246,0.15);width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-gear" style="color:#a78bfa;font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" style="font-size:0.85rem;color:#e6edf3;">Setting PIC & Data Lanjutan</h6>
                        <div style="font-size:0.6rem;color:#6b7280;">Data sudah disetujui — lengkapi informasi market & setting PIC</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill" style="background:rgba(139,92,246,0.15);color:#a78bfa;font-size:0.65rem;">
                        <i class="bi bi-check2-circle me-1"></i> Approved
                    </span>
                    <button type="button" class="btn-close btn-close-white" style="font-size:0.7rem;" data-bs-dismiss="modal"></button>
                </div>
            </div>

            {{-- TAB NAV --}}
            <div style="background:#0d1117;border-bottom:1px solid #30363d;padding:0 16px;overflow-x:auto;">
                <div class="d-flex" style="min-width:max-content;">
                    <button type="button" class="pic-tab-btn active" data-pictab="1" onclick="switchPicTab(1)">
                        <i class="bi bi-person-badge me-1"></i> Data PIC
                    </button>
                    <button type="button" class="pic-tab-btn" data-pictab="2" onclick="switchPicTab(2)">
                        <i class="bi bi-building me-1"></i> Info Bisnis
                    </button>
                    <button type="button" class="pic-tab-btn" data-pictab="3" onclick="switchPicTab(3)">
                        <i class="bi bi-geo-alt me-1"></i> Lokasi
                    </button>
                    <button type="button" class="pic-tab-btn" data-pictab="4" onclick="switchPicTab(4)">
                        <i class="bi bi-graph-up me-1"></i> Market Insight
                        <span class="badge rounded-pill ms-1" style="background:rgba(234,179,8,0.15);color:#ca8a04;font-size:0.55rem;">Baru</span>
                    </button>
                    <button type="button" class="pic-tab-btn" data-pictab="5" onclick="switchPicTab(5)">
                        <i class="bi bi-person-check me-1"></i> Setting PIC
                        <span class="badge rounded-pill ms-1" style="background:rgba(139,92,246,0.15);color:#a78bfa;font-size:0.55rem;">Final</span>
                    </button>
                </div>
            </div>

            <div class="modal-body p-0 pic-modal-body">
                <input type="hidden" id="pic-prospek-id">

                {{-- ===== TAB 1: DATA PIC (READONLY) ===== --}}
                <div class="pic-tab-content p-4" id="pic-tab-1">
                    <div class="pic-readonly-notice">
                        <i class="bi bi-lock me-1"></i> Data ini readonly — hasil dari proses review yang telah disetujui
                    </div>

                    <div class="d-flex gap-2 mb-3" id="pic-badges-wrap"></div>

                    <div class="pic-section-label">
                        <i class="bi bi-person-badge me-1"></i> Identitas
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="pic-label">Nama PIC</label>
                            <div class="pic-readonly" id="pic-ro-nama">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="pic-label">Jabatan</label>
                            <div class="pic-readonly" id="pic-ro-jabatan">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="pic-label">No. Handphone</label>
                            <div class="pic-readonly" id="pic-ro-phone">—</div>
                        </div>
                    </div>

                    <div class="pic-section-label">
                        <i class="bi bi-images me-1"></i> Foto Toko
                        <span id="pic-ro-foto-count" class="ms-auto" style="color:#6b7280;font-size:0.65rem;font-weight:400;"></span>
                    </div>
                    <div id="pic-ro-foto-wrap" class="d-flex gap-2 flex-wrap mb-2">
                        <div class="pic-foto-empty">
                            <i class="bi bi-image" style="font-size:1.5rem;color:#3e444b;display:block;margin-bottom:4px;"></i>
                            <span style="font-size:0.7rem;color:#6b7280;">Tidak ada foto</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4"
                            onclick="switchPicTab(2)" style="font-size:0.75rem;">
                            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                {{-- ===== TAB 2: INFO BISNIS (READONLY) ===== --}}
                <div class="pic-tab-content p-4 d-none" id="pic-tab-2">
                    <div class="pic-readonly-notice">
                        <i class="bi bi-lock me-1"></i> Data ini readonly — hasil dari proses review yang telah disetujui
                    </div>

                    <div class="pic-section-label">
                        <i class="bi bi-building me-1"></i> Identitas Bisnis
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="pic-label">Perusahaan / Toko</label>
                            <div class="pic-readonly" id="pic-ro-perusahaan">—</div>
                        </div>
                        <div class="col-md-3">
                            <label class="pic-label">Zone</label>
                            <div class="pic-readonly" id="pic-ro-zone">—</div>
                        </div>
                        <div class="col-md-3">
                            <label class="pic-label">Kategori</label>
                            <div class="pic-readonly" id="pic-ro-kategori">—</div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="pic-label">Model Bisnis</label>
                            <div class="pic-readonly" id="pic-ro-model">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="pic-label">Media Sosial</label>
                            <div class="pic-readonly" id="pic-ro-sosmed">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="pic-label">Marketplace</label>
                            <div class="pic-readonly" id="pic-ro-marketplace">—</div>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-8">
                            <label class="pic-label">Toko Multicabang</label>
                            <div class="pic-readonly" id="pic-ro-multicabang" style="min-height:44px;">—</div>
                        </div>
                        <div class="col-md-4">
                            <label class="pic-label">Pengajuan</label>
                            <div class="pic-readonly" id="pic-ro-pengajuan">—</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                            onclick="switchPicTab(1)" style="font-size:0.75rem;">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4"
                            onclick="switchPicTab(3)" style="font-size:0.75rem;">
                            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                {{-- ===== TAB 3: LOKASI (READONLY) ===== --}}
                <div class="pic-tab-content p-4 d-none" id="pic-tab-3">
                    <div class="pic-readonly-notice">
                        <i class="bi bi-lock me-1"></i> Data lokasi & geotag readonly — bukti kunjungan fisik
                    </div>

                    <div id="pic-geo-info-wrap" class="p-2 rounded mb-3 d-none"
                        style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.2);">
                        <div class="d-flex flex-wrap gap-3" id="pic-geo-info-row"></div>
                    </div>
                    <div id="pic-geo-pending-wrap" class="p-2 rounded mb-3 d-none"
                        style="background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.2);font-size:0.75rem;color:#f59e0b;">
                        <i class="bi bi-exclamation-triangle me-1"></i> Geotag belum tersedia.
                    </div>

                    <div id="pic-map-wrap" class="d-none mb-3">
                        <div id="pic-map" style="height:150px;border-radius:8px;border:1px solid #30363d;"></div>
                        <div style="font-size:0.65rem;color:#6b7280;margin-top:4px;text-align:center;">
                            <i class="bi bi-lock me-1"></i> Readonly
                        </div>
                    </div>

                    <div class="pic-section-label"><i class="bi bi-map me-1"></i> Wilayah</div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="pic-label">Provinsi</label>
                            <div class="pic-readonly" id="pic-ro-provinsi">—</div>
                        </div>
                        <div class="col-md-3">
                            <label class="pic-label">Kota / Kab</label>
                            <div class="pic-readonly" id="pic-ro-kota">—</div>
                        </div>
                        <div class="col-md-3">
                            <label class="pic-label">Kecamatan</label>
                            <div class="pic-readonly" id="pic-ro-kecamatan">—</div>
                        </div>
                        <div class="col-md-3">
                            <label class="pic-label">Kelurahan</label>
                            <div class="pic-readonly" id="pic-ro-kelurahan">—</div>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="pic-label">Alamat Lengkap</label>
                            <div class="pic-readonly" id="pic-ro-alamat">—</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                            onclick="switchPicTab(2)" style="font-size:0.75rem;">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </button>
                        <button type="button" class="btn btn-sm rounded-pill px-4 fw-bold"
                            onclick="switchPicTab(4)"
                            style="font-size:0.75rem;background:rgba(234,179,8,0.15);color:#ca8a04;border:1px solid rgba(234,179,8,0.3);">
                            Isi Market Insight <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                {{-- ===== TAB 4: MARKET INSIGHT (FORM BARU - REFINED UI) ===== --}}
                <div class="pic-tab-content p-4 d-none" id="pic-tab-4">
                    
                    <div class="pic-readonly-notice mb-3">
                        <i class="bi bi-pencil-square me-1"></i> Lengkapi data market insight untuk kebutuhan analisa strategi penjualan.
                    </div>
                
                    {{-- Section 1: Produk & Pricing --}}
                    <div class="pic-section-label" style="color:#ca8a04; border-left-color:#eab308;">
                        <i class="bi bi-tag me-1"></i> Produk & Pricing
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="pic-label">Produk Paling Laris</label>
                            <input type="text" id="pic-produk-laris" name="produk_laris" class="form-control event-input form-control-sm" placeholder="Contoh: Parfum A, Lotion B">
                        </div>
                        <div class="col-md-6">
                            <label class="pic-label">Range Harga Jual</label>
                            <input type="text" id="pic-range-harga" name="range_harga" class="form-control event-input form-control-sm" placeholder="Contoh: 25rb - 150rb">
                        </div>
                    </div>
                
                    {{-- Section 2: Market Dynamics --}}
                    <div class="pic-section-label" style="color:#ca8a04; border-left-color:#eab308;">
                        <i class="bi bi-shop me-1"></i> Market Dynamics
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="pic-label">Traffic Toko</label>
                            <select id="pic-traffic-toko" name="traffic_toko" class="form-select event-input form-select-sm">
                                <option value="">Pilih Kondisi Traffic</option>
                                <option value="Ramai">Ramai</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Sepi">Sepi</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="pic-label">Channel Penjualan</label>
                            <input type="text" id="pic-channel-dominan" name="channel_penjualan" class="form-control event-input form-control-sm" placeholder="Contoh: Offline, Online, Keduanya">
                        </div>
                        <div class="col-md-4">
                            <label class="pic-label">Output Market</label>
                            <input type="text" id="pic-output-market" name="output_market" class="form-control event-input form-control-sm" placeholder="Contoh: Grosir, Retail, Reseller">
                        </div>
                    </div>
                
                    {{-- Section 3: Analisa Kompetitor --}}
                    <div class="pic-section-label" style="color:#ca8a04; border-left-color:#eab308;">
                        <i class="bi bi-people me-1"></i> Analisa Kompetitor
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="pic-label">Platform Dominan</label>
                            <input type="text" id="pic-platform-dominan" name="platform_dominan" class="form-control event-input form-control-sm" placeholder="Contoh: Shopee, TikTok, WA">
                        </div>
                        <div class="col-md-4">
                            <label class="pic-label">Brand Kompetitor</label>
                            <input type="text" id="pic-brand-dominan" name="brand_dominan" class="form-control event-input form-control-sm" placeholder="Contoh: Wardah, Mustika Ratu">
                        </div>
                        <div class="col-md-4">
                            <label class="pic-label">Aktivitas Promo</label>
                            <input type="text" id="pic-promo-kompetitor" name="aktivitas_promo" class="form-control event-input form-control-sm" placeholder="Contoh: Diskon 50%, Beli 1 Gratis 1">
                        </div>
                    </div>
                
                    {{-- Tombol Navigasi Bawah --}}
                    <div class="d-flex justify-content-between pic-tab-nav-buttons">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                            onclick="switchPicTab(3)" style="font-size:0.75rem;">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </button>
                        
                        {{-- Tombol selanjutnya dibuat warna ungu untuk transisi natural ke tab Setting PIC (Tab 5) --}}
                        <button type="button" class="btn btn-sm rounded-pill px-4 fw-bold"
                            onclick="switchPicTab(5)"
                            style="font-size:0.75rem; background:rgba(139,92,246,0.15); color:#a78bfa; border:1px solid rgba(139,92,246,0.3);">
                            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                {{-- ===== TAB 5: SETTING PIC (FORM FINAL) ===== --}}
                <div class="pic-tab-content p-4 d-none" id="pic-tab-5">

                    <div class="p-2 rounded mb-3"
                        style="background:rgba(139,92,246,0.06);border:1px solid rgba(139,92,246,0.2);font-size:0.75rem;color:#a78bfa;">
                        <i class="bi bi-person-check me-1"></i>
                        Setting ini menentukan siapa yang bertanggung jawab atas prospek ini setelah dimutasi ke sistem utama.
                    </div>

                    <div class="pic-section-label" style="color:#a78bfa;border-left-color:#7c3aed;">
                        <i class="bi bi-person-gear me-1"></i> Assignment PIC
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-12">
                            <label class="pic-label">PIC <span class="text-danger">*</span></label>
                            <input type="text" id="pic-input-pic" name="pic"
                                class="form-control event-input form-control-sm"
                                placeholder="Nama PIC yang bertanggung jawab">
                        </div>
                    </div>

                    {{-- Summary Market Insight --}}
                    <div id="pic-insight-summary" class="p-3 rounded mb-3 d-none"
                        style="background:rgba(234,179,8,0.04);border:1px solid rgba(234,179,8,0.15);">
                        <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#ca8a04;margin-bottom:8px;">
                            <i class="bi bi-graph-up me-1"></i> Ringkasan Market Insight
                        </div>
                        <div id="pic-insight-summary-content" style="font-size:0.78rem;color:#c9d1d9;line-height:1.8;"></div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                            onclick="switchPicTab(4)" style="font-size:0.75rem;">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </button>
                    </div>
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer py-2 px-3 border-0 d-flex justify-content-between"
                style="background:#0d1117;border-top:1px solid #30363d !important;border-radius:0 0 12px 12px;">
                <small style="font-size:0.65rem;color:#6b7280;">
                    <i class="bi bi-info-circle me-1"></i>
                    Field <span class="text-danger">*</span> wajib diisi sebelum mutasi final.
                </small>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1"
                        style="font-size:0.75rem;" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i> Tutup
                    </button>
            
                    {{-- TOMBOL LAMA: SIMPAN / UPDATE INSIGHT --}}
                    <button type="button" id="pic-btn-simpan"
                        class="btn rounded-pill px-4 py-1 fw-bold shadow-sm"
                        style="font-size:0.75rem;background:rgba(139,92,246,0.8);border-color:#7c3aed;color:#fff;"
                        onclick="doSavePic()">
                        <i class="bi bi-check2-all me-1"></i> Simpan & Mutasi
                    </button>
                    
                    {{-- TOMBOL BARU: APPROVE FINAL (Disembunyikan secara default dengan d-none) --}}
                    <button type="button" id="pic-btn-approve-final"
                        class="btn rounded-pill px-4 py-1 fw-bold shadow-sm d-none"
                        style="font-size:0.75rem;background:#10b981;border-color:#10b981;color:#fff;"
                        onclick="doApproveFinal()">
                        <i class="bi bi-shield-check me-1"></i> Approve Final
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade modal-dark" id="modalTambahProspek" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg" style="background: #161b22; border: 1px solid #30363d; border-radius: 12px;">

            {{-- HEADER --}}
            <div class="modal-header py-2 px-3 border-0" style="background: #0d1117; border-radius: 12px 12px 0 0; border-bottom: 1px solid #30363d !important;">
                <div class="d-flex align-items-center gap-2">
                    <div style="background:rgba(31,111,235,0.15);width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-plus-fill" style="color:#58a6ff;font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0" style="font-size:0.85rem;color:#e6edf3;">Entry Prospek Baru</h6>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" style="font-size:0.7rem;" data-bs-dismiss="modal"></button>
            </div>

            {{-- TAB NAV --}}
            <div style="background:#0d1117; border-bottom:1px solid #30363d; padding:0 16px;">
                <div class="d-flex" id="tambah-tab-nav">
                    <button type="button" class="tambah-tab-btn active" data-tab="1" onclick="switchTambahTab(1)">
                        <i class="bi bi-person-badge me-1"></i> Data PIC
                        <span class="tambah-tab-indicator" id="tambah-tab-ind-1"></span>
                    </button>
                    <button type="button" class="tambah-tab-btn" data-tab="2" onclick="switchTambahTab(2)">
                        <i class="bi bi-building me-1"></i> Info Bisnis
                        <span class="tambah-tab-indicator" id="tambah-tab-ind-2"></span>
                    </button>
                    <button type="button" class="tambah-tab-btn" data-tab="3" onclick="switchTambahTab(3)">
                        <i class="bi bi-geo-alt me-1"></i> Lokasi & Geotag
                        <span class="tambah-tab-indicator" id="tambah-tab-ind-3"></span>
                    </button>
                </div>
            </div>

            <div class="modal-body p-0">
                <form id="form-tambah-prospek">

                    {{-- ======== TAB 1: DATA PIC ======== --}}
                    <div class="tambah-tab-content p-4" id="tambah-tab-content-1">

                        {{-- Status --}}
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 rounded event-input">
                                    <input type="hidden" name="visit" value="0">
                                    <input class="form-check-input mt-0 me-2" type="checkbox" id="tambah-visit" name="visit" value="1"
                                        style="background-color:#1a1d21;border-color:#484f58;cursor:pointer;width:16px;height:16px;">
                                    <label class="fw-medium mb-0 user-select-none d-flex align-items-center gap-2" for="tambah-visit"
                                        style="font-size:0.75rem;cursor:pointer;color:#e5e7eb;">
                                        <i class="bi bi-person-check" style="color:#58a6ff;"></i> Pernah Divisit?
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-2 rounded event-input">
                                    <input type="hidden" name="transaksi" value="0">
                                    <input class="form-check-input mt-0 me-2" type="checkbox" id="tambah-transaksi" name="transaksi" value="1"
                                        style="background-color:#1a1d21;border-color:#484f58;cursor:pointer;width:16px;height:16px;">
                                    <label class="fw-medium mb-0 user-select-none d-flex align-items-center gap-2" for="tambah-transaksi"
                                        style="font-size:0.75rem;cursor:pointer;color:#e5e7eb;">
                                        <i class="bi bi-bag-check" style="color:#58a6ff;"></i> Sudah Transaksi?
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Identitas --}}
                        <div class="tambah-section-label">
                            <i class="bi bi-person-badge me-1"></i> Identitas
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="tambah-label">Nama PIC <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="tambah-nama" class="form-control event-input form-control-sm" required placeholder="Nama lengkap PIC">
                            </div>
                            <div class="col-md-4">
                                <label class="tambah-label">Jabatan</label>
                                <input type="text" name="jabatan" id="tambah-jabatan" class="form-control event-input form-control-sm" placeholder="Owner / Kepala Toko / dll">
                            </div>
                            <div class="col-md-4">
                                <label class="tambah-label">No. Handphone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="tambah-phone" class="form-control event-input form-control-sm" required placeholder="08xx-xxxx-xxxx">
                            </div>
                        </div>

                        {{-- Foto --}}
                        <div class="tambah-section-label">
                            <i class="bi bi-images me-1"></i> Foto / Bukti Gambar
                            <span class="text-secondary text-lowercase ms-1" style="font-size:0.6rem;letter-spacing:normal;">(Maks. 3 · JPG/PNG · ≤2MB)</span>
                            <span id="tambah-img-counter" class="ms-auto" style="color:#58a6ff;font-size:0.65rem;font-weight:400;">0/3</span>
                        </div>
                        <div id="tambah-img-dropzone" style="border:2px dashed #30363d;border-radius:8px;padding:18px 12px;text-align:center;cursor:pointer;transition:all 0.2s ease;background:#0d1117;position:relative;user-select:none;">
                            <i class="bi bi-cloud-arrow-up-fill" style="font-size:1.6rem;color:#484f58;display:block;margin-bottom:5px;"></i>
                            <div style="font-size:0.72rem;color:#8b949e;line-height:1.5;">
                                Drag & drop di sini, atau <span style="color:#58a6ff;font-weight:600;">klik untuk pilih</span>
                            </div>
                            <div style="font-size:0.65rem;color:#484f58;margin-top:3px;">
                                Bisa juga <kbd style="background:#161b22;border:1px solid #30363d;border-radius:3px;padding:1px 4px;font-size:0.6rem;color:#8b949e;">Ctrl+V</kbd> paste gambar
                            </div>
                            <input type="file" id="tambah-img-file-input" accept="image/*" multiple style="display:none;">
                        </div>
                        <div id="tambah-img-preview-container" class="d-flex gap-2 mt-2 flex-wrap"></div>
                        <input type="hidden" id="tambah-images" name="images" value="[]">

                        {{-- Nav --}}
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold" onclick="switchTambahTab(2)" style="font-size:0.75rem;">
                                Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ======== TAB 2: INFO BISNIS ======== --}}
                    <div class="tambah-tab-content p-4 d-none" id="tambah-tab-content-2">

                        <div class="tambah-section-label">
                            <i class="bi bi-building me-1"></i> Identitas Bisnis
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="tambah-label">Perusahaan / Toko</label>
                                <input type="text" name="perusahaan" class="form-control event-input form-control-sm" placeholder="Nama perusahaan / toko">
                            </div>
                            <div class="col-md-3">
                                <label class="tambah-label">Zone</label>
                                <select name="zone" class="form-select event-input form-select-sm">
                                    <option value="">Pilih Zone</option>
                                    <option value="JABODETABEK">JABODETABEK</option>
                                    <option value="JABAR">JABAR</option>
                                    <option value="JATENG - JATIM">JATENG - JATIM</option>
                                    <option value="SUMATERA">SUMATERA</option>
                                    <option value="BALI - KALIMANTAN - SULAWESI">BALI - KALIMANTAN - SULAWESI</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="tambah-label">Kategori</label>
                                <select name="kategori" class="form-select event-input form-select-sm">
                                    <option value="">Pilih Kategori</option>
                                    <option value="Umum (semua prospek)">Umum (semua prospek)</option>
                                    <option value="Agen - perfumery trusted">Agen - perfumery trusted</option>
                                    <option value="Bigreseller">Bigreseller</option>
                                    <option value="Smreseller">Smreseller</option>
                                    <option value="Bigperfumery">Bigperfumery</option>
                                    <option value="Smperfumery">Smperfumery</option>
                                    <option value="Home industri kosmetik">Home industri kosmetik</option>
                                    <option value="Home industri pkrt">Home industri pkrt</option>
                                    <option value="Industri kosmetik (PPN)">Industri kosmetik (PPN)</option>
                                    <option value="Industri pkrt (PPN)">Industri pkrt (PPN)</option>
                                    <option value="Umum project (semua prospek)">Umum project (semua prospek)</option>
                                </select>
                            </div>
                        </div>

                        <div class="tambah-section-label mt-2">
                            <i class="bi bi-diagram-3 me-1"></i> Model & Channel
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="tambah-label">Model Bisnis</label>
                                <select name="model_bisnis" class="form-select event-input form-select-sm">
                                    <option value="">Pilih Model</option>
                                    <option value="Open Label">Open Label</option>
                                    <option value="Close Label">Close Label</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="tambah-label">Media Sosial</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text border-0" style="background:#21262d;color:#8b949e;"><i class="bi bi-instagram"></i></span>
                                    <input type="text" name="media_sosial" class="form-control event-input border-start-0" placeholder="IG / FB / TikTok">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="tambah-label">Marketplace</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text border-0" style="background:#21262d;color:#8b949e;"><i class="bi bi-shop-window"></i></span>
                                    <input type="text" name="marketplace" class="form-control event-input border-start-0" placeholder="Shopee / Tokopedia">
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-8">
                                <label class="tambah-label">Toko Multicabang <span class="text-secondary text-lowercase" style="font-size:0.6rem;">(Opsional)</span></label>
                                <textarea name="toko_multicabang" class="form-control event-input" rows="2"
                                    style="font-size:0.8rem;min-height:50px;" placeholder="Daftar cabang lain jika ada..."></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="tambah-label">Pengajuan</label>
                                <input type="text" name="pengajuan" class="form-control event-input form-control-sm" placeholder="Pengajuan produk / program">
                            </div>
                        </div>

                        {{-- Nav --}}
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="switchTambahTab(1)" style="font-size:0.75rem;">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold" onclick="switchTambahTab(3)" style="font-size:0.75rem;">
                                Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ======== TAB 3: LOKASI & GEOTAG ======== --}}
                    <div class="tambah-tab-content p-4 d-none" id="tambah-tab-content-3">

                        <!--<div class="p-2 rounded mb-3" style="background:rgba(31,111,235,0.06);border:1px solid rgba(31,111,235,0.2);font-size:0.75rem;color:#58a6ff;">-->
                        <!--    <i class="bi bi-info-circle me-1"></i>-->
                        <!--    Geotag <strong>opsional</strong> untuk back office. Input koordinat manual dari Google Maps lalu klik-->
                        <!--    <strong>Auto-fill</strong>, atau skip — data tetap bisa disimpan dengan status <em>pending geotag</em>.-->
                        <!--</div>-->

                        {{-- Input koordinat --}}
                        <div class="tambah-section-label">
                            <i class="bi bi-crosshair me-1"></i> Koordinat
                            <span class="ms-auto" id="tambah-geo-status-badge"></span>
                        </div>

                        <!--<div class="p-2 rounded mb-2" style="background:rgba(255,255,255,0.02);border:1px solid #30363d;font-size:0.72rem;color:#8b949e;">-->
                        <!--    <i class="bi bi-lightbulb me-1" style="color:#58a6ff;"></i>-->
                        <!--    Buka <strong style="color:#c9d1d9;">maps.google.com</strong> → cari lokasi toko → klik kanan → <em>Salin koordinat</em> → paste di bawah.-->
                        <!--</div>-->

                        <div class="row g-2 mb-2">
                            <div class="col-md-5">
                                <label class="tambah-label">Latitude</label>
                                <input type="text" id="tambah-lat" name="latitude"
                                    class="form-control event-input form-control-sm"
                                    placeholder="-7.2575000" oninput="onTambahCoordsInput()">
                            </div>
                            <div class="col-md-5">
                                <label class="tambah-label">Longitude</label>
                                <input type="text" id="tambah-lng" name="longitude"
                                    class="form-control event-input form-control-sm"
                                    placeholder="112.7521000" oninput="onTambahCoordsInput()">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" id="tambah-btn-autofill"
                                    class="btn btn-sm btn-primary w-100 fw-bold rounded"
                                    onclick="doTambahReverseGeocode()"
                                    style="font-size:0.72rem;height:31px;" disabled>
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Mini map --}}
                        <div id="tambah-map-wrap" class="d-none mb-3">
                            <div id="tambah-map" style="height:150px;border-radius:8px;border:1px solid #30363d;"></div>
                        </div>

                        {{-- Divider --}}
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="flex:1;height:1px;background:#30363d;"></div>
                            <span style="font-size:0.7rem;color:#6b7280;">atau</span>
                            <div style="flex:1;height:1px;background:#30363d;"></div>
                        </div>

                        {{-- Skip --}}
                        <!--<button type="button" id="tambah-btn-skip" onclick="doTambahSkipGeo()"-->
                        <!--    class="btn btn-sm btn-outline-secondary rounded-pill w-100 mb-3"-->
                        <!--    style="font-size:0.75rem;">-->
                        <!--    <i class="bi bi-skip-forward me-1"></i> Skip — tandai Pending Geotag-->
                        <!--</button>-->

                        {{-- Wilayah --}}
                        <div class="tambah-section-label">
                            <i class="bi bi-map me-1"></i> Wilayah
                            <span style="font-size:0.6rem;color:#6b7280;font-weight:400;letter-spacing:normal;" class="ms-1">
                                (auto-fill setelah koordinat, atau isi manual)
                            </span>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-3">
                                <label class="tambah-label">Provinsi</label>
                                <select name="provinsi" id="tambah-provinsi"
                                    class="form-select event-input form-select-sm"
                                    onchange="window.fetchRegionTambah('kota', this)">
                                    <option value="" data-id="">Pilih Provinsi</option>
                                    @foreach($provinces as $p)
                                        <option value="{{ $p->prov_name }}" data-id="{{ $p->prov_id }}">{{ $p->prov_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="tambah-label">Kota / Kab</label>
                                <select name="kota" id="tambah-kota"
                                    class="form-select event-input form-select-sm"
                                    onchange="window.fetchRegionTambah('kecamatan', this)">
                                    <option value="" data-id="">Pilih Kota</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="tambah-label">Kecamatan</label>
                                <select name="kecamatan" id="tambah-kecamatan"
                                    class="form-select event-input form-select-sm"
                                    onchange="window.fetchRegionTambah('kelurahan', this)">
                                    <option value="" data-id="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="tambah-label">Kelurahan</label>
                                <select name="kelurahan" id="tambah-kelurahan"
                                    class="form-select event-input form-select-sm">
                                    <option value="" data-id="">Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-12">
                                <label class="tambah-label">Alamat Lengkap</label>
                                <input type="text" name="alamat" id="tambah-alamat"
                                    class="form-control event-input form-control-sm"
                                    placeholder="Jalan, No., RT/RW...">
                            </div>
                        </div>

                        {{-- Hidden geotag fields --}}
                        <input type="hidden" name="geo_source" id="tambah-geo-source" value="PENDING_GEOTAG">
                        <input type="hidden" name="geo_accuracy" id="tambah-geo-accuracy" value="">
                        <input type="hidden" name="geo_captured_at" id="tambah-geo-captured-at" value="">

                        {{-- Nav + Simpan --}}
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                onclick="switchTambahTab(2)" style="font-size:0.75rem;">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </button>
                            <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm"
                                id="btn-save-tambah" style="font-size:0.75rem;">
                                <i class="bi bi-person-plus me-1"></i> Simpan Data Baru
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            {{-- FOOTER — hanya Batal --}}
            <div class="modal-footer py-2 px-3 border-0"
                style="background:#0d1117;border-top:1px solid #30363d !important;border-radius:0 0 12px 12px;">
                <small id="tambah-footer-info" style="font-size:0.65rem;color:#6b7280;">
                    <i class="bi bi-info-circle me-1"></i> Field bertanda <span class="text-danger">*</span> wajib diisi.
                </small>
                <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1"
                    style="font-size:0.75rem;" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i> Batal
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade modal-dark" id="modalSetPicAwal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow-lg" style="background-color: #1a1d21; border: 1px solid #3e444b;">
            <div class="modal-header py-2" style="background-color: #16191d; border-bottom: 1px solid #3e444b;">
                <h6 class="modal-title fw-bold mb-0 text-white"><i class="bi bi-person-fill-add text-warning me-2"></i>Setting PIC</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <input type="hidden" id="setpic-prospek-id">
                
                <!--<p class="small text-white-50 mb-3">Data ini sudah masuk tahap PIC. Silakan tetapkan PIC sebelum melengkapi Market Insight.</p>-->
                
                <div class="mb-3 text-start">
                    <label class="text-white-50 ms-1 mb-1 fw-medium" style="font-size: 0.75rem;">PIC <span class="text-danger">*</span></label>
                
                    {{-- Input bebas — untuk source selain APM/SPG --}}
                    <input type="text" id="setpic-input-pic" class="form-control text-white"
                        style="background-color: #1e2227; border: 1px solid #3e444b;"
                        placeholder="Inputkan Nama PIC!">
                
                    {{-- Select2 — khusus source APM/SPG (sumber: master_tampung_prospek) --}}
                    <select id="setpic-select-pic" class="form-control text-white d-none" style="width:100%;">
                        <option value="">Pilih SPG</option>
                    </select>
                
                    <div id="setpic-pic-hint" class="d-none mt-1" style="font-size:0.65rem;color:#6b7280;">
                        <i class="bi bi-info-circle me-1"></i> Data dari APM/SPG — pilih nama dari daftar.
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 d-flex justify-content-end" style="background-color: #16191d; border-top: 1px solid #3e444b;">
                <button type="button" id="btn-save-pic" class="btn btn-sm btn-warning px-4 rounded-pill fw-bold" onclick="window.actionSimpanPicAwal()">
                    Lanjut <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Styling Select Box Transparan untuk Header Card */
.header-select {
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
    cursor: pointer;
    letter-spacing: 0.5px;
    padding-left: 0;
}
.header-select:focus {
    outline: none;
    box-shadow: none;
}
.header-select option {
    background-color: #1a1d21; /* Warna dropdown menu saat dibuka */
    color: #e5e7eb;
}

/* Zone Cards */
.zc-card   { border-radius:10px; border:1px solid rgba(255,255,255,0.06); overflow:hidden; background:var(--zc-bg); }

.zc-header { display:flex; align-items:center; justify-content:space-between;
             padding:10px 14px; cursor:pointer; user-select:none;
             border-left:3px solid var(--zc-color); transition:background .15s; }
.zc-header:hover { background:rgba(255,255,255,0.03); }
.zc-header-left  { display:flex; align-items:center; gap:10px; }
.zc-header-right { display:flex; align-items:center; gap:8px; }

.zc-icon  { width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,0.06);
            display:flex; align-items:center; justify-content:center;
            color:var(--zc-color); font-size:0.85rem; flex-shrink:0; }

.zc-title { font-size:0.95rem; font-weight:700; color:#e5e7eb; letter-spacing:.4px; }
.zc-meta  { font-size:0.62rem; color:#6b7280; display:flex; gap:6px; align-items:center; margin-top:2px; flex-wrap:wrap; }

.zc-pill       { display:inline-block; border-radius:20px; padding:1px 7px; font-size:0.6rem; font-weight:600; }
.zc-pill-ok    { background:rgba(16,185,129,.15); color:#34d399; }
.zc-pill-nd    { background:rgba(107,114,128,.15); color:#9ca3af; }

.zc-chevron { color:#6b7280; font-size:0.7rem; transition:transform .25s; }
.zc-header:not(.collapsed) .zc-chevron { transform:rotate(180deg); }

.zc-body   { border-top:1px solid rgba(255,255,255,0.05); }

/* === L1 TABLE SCROLL AREA (pola sama seperti Guestbook) === */
#uiTableContainer {
    display: flex;
    flex-direction: column;
}
#uiTableContainer .ci-l1-scroll-area {
    max-height: 380px; /* ±5-6 baris terlihat sebelum scroll */
    overflow-y: auto;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
#uiTableContainer .ci-l1-scroll-area::-webkit-scrollbar { width: 8px; height: 8px; }
#uiTableContainer .ci-l1-scroll-area::-webkit-scrollbar-track { background: #161b22; }
#uiTableContainer .ci-l1-scroll-area::-webkit-scrollbar-thumb { background: #30363d; border-radius: 4px; }
#uiTableContainer .ci-l1-scroll-area::-webkit-scrollbar-thumb:hover { background: #484f58; }

.ci-tbl thead th {
    position: sticky;
    top: 0;
    z-index: 5;
    background: #161b22 !important;
}

@media (max-width: 991.98px) {
    #uiTableContainer .ci-l1-scroll-area { max-height: 340px; }
}
@media (max-width: 576px) {
    #uiTableContainer .ci-l1-scroll-area { max-height: 300px; }
}

.zc-item   { display:flex; align-items:center; justify-content:space-between;
             padding:9px 14px; cursor:pointer; transition:background .12s;
             border-bottom:1px solid rgba(255,255,255,0.04); }
.zc-item:last-child { border-bottom:none; }
.zc-item:hover      { background:rgba(255,255,255,0.04); }
.zc-item-left  { display:flex; align-items:flex-start; gap:10px; flex:1; min-width:0; }
.zc-item-right { display:flex; align-items:center; flex-shrink:0; }

.zc-dot      { width:8px; height:8px; border-radius:50%; margin-top:5px; flex-shrink:0; }
.zc-dot-blue { background:#3b82f6; box-shadow:0 0 5px rgba(59,130,246,.5); }
.zc-dot-red  { background:#ef4444; box-shadow:0 0 5px rgba(239,68,68,.5); }
.zc-dot-gray { background:#374151; }

.zc-item-name    { font-size:0.75rem; font-weight:600; color:#e5e7eb; }
.zc-item-company { font-size:0.65rem; color:#6b7280; margin-top:1px; }
.zc-item-contact { font-size:0.62rem; color:#4b5563; margin-top:3px; display:flex; gap:10px; flex-wrap:wrap; }
.zc-item-contact i { margin-right:2px; }

.zc-badge      { font-size:0.6rem; font-weight:700; padding:2px 8px; border-radius:20px; white-space:nowrap; }
.zc-badge-blue { background:rgba(59,130,246,.15); color:#60a5fa; }
.zc-badge-red  { background:rgba(239,68,68,.15);  color:#f87171; }
.zc-badge-gray { background:rgba(107,114,128,.15); color:#9ca3af; }

/* Penyesuaian Ukuran Font & Legibilitas Tabel */
.ci-tbl th {
    font-size: 0.85rem !important;
    color: #e5e7eb !important;
    font-weight: 600 !important;
    letter-spacing: 0.5px;
    padding-top: 12px;
    padding-bottom: 12px;
}

.ci-tbl td {
    font-size: 0.85rem !important;
    color: #f8f9fa !important;
    padding-top: 4px;
    padding-bottom: 4;
    vertical-align: middle;
}

.td-name {
    font-size: 0.95rem !important;
    font-weight: 700;
    color: #ffffff;
}

.td-sub {
    font-size: 0.8rem !important;
    color: #adb5bd !important;
    margin-top: 3px;
}

/* === MIS STYLE ADAPTATION FOR CUSTOMER MODAL === */
.event-input {
    background-color: #0d1117 !important;
    color: #e6edf3 !important;
    border: 1px solid #30363d !important;
    border-radius: 8px;
    color-scheme: dark;
}
.event-input::placeholder { color: rgba(230,237,243,.4) !important; }
.event-input:focus {
    border-color: #1f6feb !important;
    box-shadow: 0 0 0 3px rgba(31,111,235,.15) !important;
    outline: none;
}

/* === SELECT2 DARK THEME (MIS STYLE) === */
.select2-container--default .select2-selection--single {
    background-color: #0d1117 !important; border: 1px solid #30363d !important;
    border-radius: 8px !important; height: 31px !important; transition: border-color .2s;
}
.select2-container--default.select2-container--open .select2-selection--single {
    border-color: #1f6feb !important; box-shadow: 0 0 0 3px rgba(31,111,235,.15) !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #e6edf3 !important; line-height: 29px !important; font-size: 0.75rem !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 29px !important; }
.select2-dropdown {
    background-color: #161b22 !important; border: 1px solid #30363d !important;
    border-radius: 8px !important; box-shadow: 0 12px 30px rgba(0,0,0,.5) !important;
}
.select2-search--dropdown .select2-search__field {
    background-color: #0d1117 !important; color: #e6edf3 !important;
    border: 1px solid #30363d !important; border-radius: 6px !important;
}
.select2-results__option { color: #8b949e !important; font-size: 0.8rem !important; }
.select2-results__option--highlighted[aria-selected] { background-color: #1f6feb !important; color: #fff !important; }
.select2-results__option[aria-selected=true] { background-color: #21262d !important; color: #58a6ff !important; font-weight: 600; }

/* ===== MODAL TAMBAH — TAB STYLE ===== */
.tambah-tab-btn {
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    color: #6b7280;
    font-size: 0.78rem;
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all .15s;
    position: relative;
    white-space: nowrap;
}
.tambah-tab-btn:hover { color: #c9d1d9; }
.tambah-tab-btn.active {
    color: #3fb950;
    border-bottom-color: #2ea043;
    font-weight: 600;
}
.tambah-tab-indicator {
    width: 6px; height: 6px;
    border-radius: 50%;
    display: none;
    margin-left: 3px;
}
.tambah-tab-indicator.filled {
    display: inline-block;
    background: #3fb950;
}
.tambah-section-label {
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #3fb950;
    border-left: 3px solid #2ea043;
    padding-left: 8px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}
.tambah-label {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #8b949e;
    display: block;
    margin-bottom: 4px;
}
.alert-tambah-info {
    background: rgba(59,130,246,0.06);
    border: 1px solid rgba(59,130,246,0.2);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.75rem;
    color: #60a5fa;
}

/* ===== MODAL APPROVED — READONLY STYLE ===== */
.approved-tab-btn {
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    color: #6b7280;
    font-size: 0.78rem;
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all .15s;
    white-space: nowrap;
}
.approved-tab-btn:hover { color: #c9d1d9; }
.approved-tab-btn.active {
    color: #4ade80;
    border-bottom-color: #22c55e;
    font-weight: 600;
}
.ap-section-label {
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #6b7280;
    border-left: 3px solid #3e444b;
    padding-left: 8px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}
.ap-label {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #6b7280;
    display: block;
    margin-bottom: 4px;
}
.ap-readonly {
    background: rgba(255,255,255,0.02);
    border: 1px solid #21262d;
    border-radius: 8px;
    padding: 7px 10px;
    font-size: 0.8rem;
    color: #c9d1d9;
    min-height: 32px;
    display: flex;
    align-items: center;
    user-select: text;
}
.ap-readonly.empty { color: #484f58; font-style: italic; }
.ap-foto-empty {
    width: 80px; height: 80px;
    border-radius: 8px;
    border: 1px dashed #3e444b;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* ===== MODAL PIC — 5 TAB ===== */
.pic-tab-btn {
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    color: #6b7280;
    font-size: 0.78rem;
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all .15s;
    white-space: nowrap;
}
.pic-tab-btn:hover { color: #c9d1d9; }
.pic-tab-btn.active {
    color: #a78bfa;
    border-bottom-color: #7c3aed;
    font-weight: 600;
}
.pic-section-label {
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #6b7280;
    border-left: 3px solid #3e444b;
    padding-left: 8px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}
.pic-label {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #6b7280;
    display: block;
    margin-bottom: 4px;
}
.pic-readonly {
    background: rgba(255,255,255,0.02);
    border: 1px solid #21262d;
    border-radius: 8px;
    padding: 7px 10px;
    font-size: 0.8rem;
    color: #c9d1d9;
    min-height: 32px;
    display: flex;
    align-items: center;
}
.pic-readonly.empty { color: #484f58; font-style: italic; }
.pic-readonly-notice {
    background: rgba(107,114,128,0.08);
    border: 1px solid #30363d;
    border-radius: 8px;
    padding: 7px 12px;
    font-size: 0.72rem;
    color: #6b7280;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.pic-foto-empty {
    width: 80px; height: 80px;
    border-radius: 8px;
    border: 1px dashed #3e444b;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* 1. Modal Body - Adaptive Height (Menyesuaikan isi, tidak memaksa tinggi) */
.pic-modal-body {
    max-height: 75vh; /* Batas maksimal tinggi adalah 75% layar agar tidak tumpah */
    display: flex;
    flex-direction: column;
    overflow: hidden !important; /* Mencegah double-scroll dengan layar utama */
}

/* 2. Tab Content - Area Scroll Jika Konten Panjang */
.pic-tab-content {
    overflow-y: auto;
    overflow-x: hidden;
    padding: 1.25rem !important; /* Padding yang lebih rapat (menggantikan p-4) */
    
    /* Scrollbar Dark Theme */
    scrollbar-width: thin;
    scrollbar-color: #30363d transparent;
}

/* Kustomisasi Scrollbar Webkit (Chrome/Edge/Safari) */
.pic-tab-content::-webkit-scrollbar { width: 6px; }
.pic-tab-content::-webkit-scrollbar-track { background: transparent; }
.pic-tab-content::-webkit-scrollbar-thumb { background-color: #30363d; border-radius: 10px; }

/* 3. Tombol Navigasi Bawah - Kembali ke posisi natural */
.pic-tab-nav-buttons {
    margin-top: 25px; /* Jarak natural dari elemen terakhir, tidak dipaksa ke paling bawah layang */
    padding-top: 15px;
    border-top: 1px dashed rgba(255,255,255,0.08); /* Garis batas tipis yang elegan */
    display: flex;
    justify-content: space-between;
}
</style>

<script>
// Buka Modal Set PIC
// ── Daftar SPG (hardcode sementara — gampang diganti ke endpoint DB nanti) ──
window.SPG_LIST = ['SPG1SBY', 'SPG1MLG', 'SPG1MDN', 'SPG1DIY'];

// ═════════════════════════════════════════════════════════════════
// ⚠️ HOLD SEMENTARA (temporary): fitur "Naikkan ke MIS" untuk source
// selain APM/SPG belum siap (masih placeholder "Segera Hadir"), jadi
// data non-APM/SPG jadi buntu di tab PIC (tidak bisa Set PIC/Mutasi/
// isi Market Insight).
//
// Selama flag ini TRUE, semua source di tab PIC DISETARAKAN dengan
// APM/SPG (bisa Set PIC → Mutasi → isi Market Insight seperti biasa).
//
// Kode/logika ASLI (pembeda APM/SPG vs source lain) TIDAK dihapus,
// hanya di-bypass. Kalau fitur "Naikkan ke MIS" sudah siap, tinggal
// ubah nilai di bawah ini jadi `false` untuk kembali ke behavior asli.
// ═════════════════════════════════════════════════════════════════
// ═════════════════════════════════════════════════════════════════
// ⚠️ Dulu di-hold sementara (TRUE) karena fitur "Naikkan ke MIS"
// masih placeholder. Sekarang alur MIS sudah dibangun betulan
// (lihat naikkanKeMis() di ProspekTampungController + menu
// "Setting PIC" di modul MIS) — jadi kembali ke behavior ASLI.
// Set balik ke `true` jika suatu saat perlu hold lagi.
// ═════════════════════════════════════════════════════════════════
window.TEMP_EQUALIZE_SOURCE_WITH_APM = false;

// Buka Modal Set PIC
window.openModalSetPicAwal = function(id) {
    $('#setpic-prospek-id').val(id);
    $('#setpic-input-pic').val('');

    // Ambil data row untuk baca field `source`
    var container = $('.progress-container[data-id="' + id + '"]').first();
    var data = null;

    if (container.length && container.data('json')) {
        data = container.data('json');
        if (typeof data === 'string') data = JSON.parse(data);
        renderSetPicMode(data);
        new bootstrap.Modal(document.getElementById('modalSetPicAwal')).show();
    } else {
        // Fallback kalau data tidak ada di DOM (misal dipanggil dari tempat lain)
        $.get('/prospek_tampung/get-data/' + id, function(res) {
            if (res.success) {
                renderSetPicMode(res.data);
            } else {
                renderSetPicMode({}); // default ke mode text bebas
            }
            new bootstrap.Modal(document.getElementById('modalSetPicAwal')).show();
        }).fail(function() {
            renderSetPicMode({});
            new bootstrap.Modal(document.getElementById('modalSetPicAwal')).show();
        });
    }
};

// ── Tentukan mode input PIC: select2 (APM/SPG) atau text bebas (lainnya) ──
function renderSetPicMode(data) {
    var source = (data.source || '').toUpperCase();
    var isSpgSource = (source === 'APM');

    var $text   = $('#setpic-input-pic');
    var $select = $('#setpic-select-pic');
    var $hint   = $('#setpic-pic-hint');

    // Reset select2 jika sudah pernah di-init, biar tidak dobel saat modal dibuka berkali-kali
    if ($select.hasClass('select2-hidden-accessible')) {
        $select.select2('destroy');
    }

    if (isSpgSource) {
        // Tampilkan select2, sembunyikan text
        $text.addClass('d-none').val('');
        $select.removeClass('d-none');
        $hint.removeClass('d-none');

        var opts = '<option value="">Pilih SPG</option>';
        window.SPG_LIST.forEach(function(name) {
            var sel = (data.pic === name) ? 'selected' : '';
            opts += '<option value="' + name + '" ' + sel + '>' + name + '</option>';
        });
        $select.html(opts);

        $select.select2({
            width: '100%',
            dropdownParent: $('#modalSetPicAwal'),
            placeholder: 'Pilih SPG'
        });
    } else {
        // Tampilkan text bebas, sembunyikan select2
        $select.addClass('d-none');
        $hint.addClass('d-none');
        $text.removeClass('d-none').val(data.pic || '');
    }
}

// Proses Simpan PIC ke Database via AJAX
window.actionSimpanPicAwal = function() {
    var id = $('#setpic-prospek-id').val();

    // Ambil value dari elemen yang sedang tampil (select2 atau text)
    var picName = $('#setpic-select-pic').hasClass('d-none')
        ? $('#setpic-input-pic').val()
        : $('#setpic-select-pic').val();

    if (!picName) {
        Swal.fire({title:'Peringatan', text:'Lengkapi PIC!', icon:'warning', background:'#1a1d21', color:'#fff'});
        return;
    }

    var btn = $('#btn-save-pic');
    var oriText = btn.html();
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

    $.ajax({
        url: '/prospek_tampung/set-pic/' + id,
        type: 'POST',
        data: { 
            _token: $('meta[name="csrf-token"]').attr('content'),
            pic: picName,
        },
        success: function(res) {
            if(res.success) {
                var modalElement = document.getElementById('modalSetPicAwal');
                var modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) modalInstance.hide();
        
                // GANTI DARI: window.loadProspekPartial(1, 'L3');
                if(typeof window.loadProspekPartial === 'function') {
                    var pageToKeep = window.currentProspekPage || 1;
                    window.loadProspekPartial(pageToKeep, 'L3');
                }
        
                setTimeout(() => {
                    var finalData = res.data ? res.data : { id: id, pic: picName };
                    if (!finalData.pic) finalData.pic = picName;
                    if (typeof window._executeOpenModalPic === 'function') {
                        window._executeOpenModalPic(finalData, id);
                    }
                }, 400);
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        },
        complete: function() {
            btn.prop('disabled', false).html(oriText);
        }
    });
};

window.actionNaikkanKeMis = function(id) {
    if (!id) return;

    Swal.fire({
        title: 'Naikkan ke MIS?',
        html: '<div style="font-size:0.8rem;color:#e5e7eb;line-height:1.8;text-align:left;">' +
              'Data ini akan dikirim ke MIS untuk ditentukan <b>AM, ASM, PIC, dan SPG</b>.<br>' +
              '<span style="color:#f59e0b;">Setelah dikirim, data akan menunggu di-assign oleh MIS.</span>' +
              '</div>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0c82f9',
        cancelButtonColor: '#484f58',
        confirmButtonText: '<i class="bi bi-arrow-up-circle me-1"></i> Ya, Naikkan!',
        cancelButtonText: 'Batal',
        background: '#1a1d21', color: '#fff'
    }).then(function(result) {
        if (!result.isConfirmed) return;

        Swal.showLoading();

        $.ajax({
            url: '{{ route("master.prospek_tampung.naikkan_mis", ["id" => "__ID__"]) }}'.replace('__ID__', id),
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                if (res.success) {
                    Swal.fire({ title: 'Terkirim', text: res.message, icon: 'success', background: '#1a1d21', color: '#fff', timer: 1800, showConfirmButton: false });
                    var pageToKeep = window.currentProspekPage || 1;
                    if (typeof window.loadProspekPartial === 'function') {
                        window.loadProspekPartial(pageToKeep, 'L3');
                    }
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function(xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                Swal.fire('Gagal', msg, 'error');
            }
        });
    });
};

// =========================================================================
// BULK ACTION — L3 (Set PIC / Naikkan ke MIS massal)
// =========================================================================

window.toggleBulkSelectAll = function(masterCheckbox) {
    $('.bulk-row-checkbox').prop('checked', masterCheckbox.checked);
    window.updateBulkToolbar();
};

window.updateBulkToolbar = function() {
    var $checked = $('.bulk-row-checkbox:checked');
    var count = $checked.length;

    $('#bulkSelectedCount').text(count);

    var $btnSetPic = $('#btnBulkSetPic');
    var $btnMis    = $('#btnBulkNaikkanMis');

    if (count === 0) {
        $btnSetPic.prop('disabled', true);
        $btnMis.prop('disabled', true);
        $('#bulkSelectAll').prop('checked', false);
        return;
    }

    // Cek apakah semua source yang tercentang sama
    var sources = [];
    $checked.each(function() {
        sources.push($(this).data('source') || '');
    });
    var uniqueSources = [...new Set(sources)];
    var isUniform = (uniqueSources.length === 1);
    var theSource = uniqueSources[0] || '';
    // Kode asli: var isSpgSource = (theSource === 'APM' || theSource === 'SPG');
    var isSpgSource = window.TEMP_EQUALIZE_SOURCE_WITH_APM ? true : (theSource === 'APM' || theSource === 'SPG');

    // Tombol tetap enabled walau campur — validasi sesungguhnya terjadi saat tombol diklik
    // (sesuai kebutuhan: beri peringatan saat eksekusi, bukan memblokir checkbox)
    $btnSetPic.prop('disabled', false);
    $btnMis.prop('disabled', false);

    // Update sinkron master checkbox "select all"
    var totalRows = $('.bulk-row-checkbox').length;
    $('#bulkSelectAll').prop('checked', count === totalRows && totalRows > 0);
};

// ── Validasi: pastikan source seragam sebelum eksekusi ──
function validateBulkSourceUniform(expectedType) {
    var $checked = $('.bulk-row-checkbox:checked');

    if ($checked.length === 0) {
        Swal.fire({ title: 'Belum Ada Data', text: 'Pilih minimal 1 data terlebih dahulu.', icon: 'warning', background: '#1a1d21', color: '#fff' });
        return null;
    }

    var sources = [];
    var ids = [];
    $checked.each(function() {
        sources.push($(this).data('source') || '');
        ids.push($(this).data('id'));
    });

    var uniqueSources = [...new Set(sources)];

    if (uniqueSources.length > 1) {
        Swal.fire({
            title: 'Source Data Tidak Seragam',
            html: '<div style="font-size:0.8rem;color:#e5e7eb;text-align:left;line-height:1.7;">' +
                  'Data yang dipilih berasal dari source berbeda: <b>' + uniqueSources.join(', ') + '</b>.<br><br>' +
                  'Mohon pilih data dengan <b>source yang sama</b> untuk aksi massal ini.' +
                  '</div>',
            icon: 'error',
            confirmButtonText: 'Mengerti',
            background: '#1a1d21', color: '#fff'
        });
        return null;
    }

    var theSource = uniqueSources[0];
    // Kode asli: var isSpgSource = (theSource === 'APM' || theSource === 'SPG');
    var isSpgSource = window.TEMP_EQUALIZE_SOURCE_WITH_APM ? true : (theSource === 'APM' || theSource === 'SPG');

    // Validasi kesesuaian dengan jenis aksi yang diminta
    if (expectedType === 'SPG' && !isSpgSource) {
        Swal.fire({
            title: 'Source Tidak Sesuai',
            html: '<div style="font-size:0.8rem;color:#e5e7eb;text-align:left;line-height:1.7;">' +
                  'Aksi <b>Set PIC</b> hanya berlaku untuk data dengan source <b>APM/SPG</b>.<br>' +
                  'Data yang dipilih bersumber dari: <b>' + (theSource || 'Tidak diketahui') + '</b>.' +
                  '</div>',
            icon: 'warning',
            confirmButtonText: 'Mengerti',
            background: '#1a1d21', color: '#fff'
        });
        return null;
    }

    if (expectedType === 'OTHER' && isSpgSource) {
        Swal.fire({
            title: 'Source Tidak Sesuai',
            html: '<div style="font-size:0.8rem;color:#e5e7eb;text-align:left;line-height:1.7;">' +
                  'Aksi <b>Naikkan ke MIS</b> hanya berlaku untuk data <b>selain APM/SPG</b>.<br>' +
                  'Data yang dipilih bersumber dari: <b>' + theSource + '</b>.' +
                  '</div>',
            icon: 'warning',
            confirmButtonText: 'Mengerti',
            background: '#1a1d21', color: '#fff'
        });
        return null;
    }

    return { ids: ids, source: theSource };
}

// ── Aksi Massal: Set PIC (APM/SPG) ──
window.bulkActionSetPic = function() {
    var validated = validateBulkSourceUniform('SPG');
    if (!validated) return;

    var ids = validated.ids;

    // Buat dropdown pilih SPG via SweetAlert input
    var optionsHtml = '<option value="">Pilih SPG</option>';
    window.SPG_LIST.forEach(function(name) {
        optionsHtml += '<option value="' + name + '">' + name + '</option>';
    });

    Swal.fire({
        title: 'Set PIC Massal',
        html: '<div style="font-size:0.8rem;color:#e5e7eb;text-align:left;">' +
              '<p>Akan menetapkan PIC yang sama untuk <b>' + ids.length + ' data</b> terpilih.</p>' +
              '<select id="bulkSpgSelect" class="form-select form-select-sm" style="background:#1e2227;color:#fff;border:1px solid #3e444b;">' +
              optionsHtml +
              '</select>' +
              '</div>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#484f58',
        confirmButtonText: 'Terapkan',
        cancelButtonText: 'Batal',
        background: '#1a1d21', color: '#fff',
        preConfirm: function() {
            var val = document.getElementById('bulkSpgSelect').value;
            if (!val) {
                Swal.showValidationMessage('Pilih SPG terlebih dahulu');
                return false;
            }
            return val;
        }
    }).then(function(result) {
        if (!result.isConfirmed) return;
        var picName = result.value;

        // ── Placeholder: ganti dengan AJAX bulk-set-pic ke backend ──
        Swal.fire({
            title: 'Segera Hadir',
            text: 'Endpoint bulk Set PIC (' + ids.length + ' data → ' + picName + ') sedang dalam pengembangan.',
            icon: 'info',
            background: '#1a1d21', color: '#fff'
        });
    });
};

// ── Aksi Massal: Naikkan ke MIS (source lain) ──
window.bulkActionNaikkanMis = function() {
    var validated = validateBulkSourceUniform('OTHER');
    if (!validated) return;

    var ids = validated.ids;

    Swal.fire({
        title: 'Naikkan ke MIS (Massal)?',
        html: '<div style="font-size:0.8rem;color:#e5e7eb;line-height:1.8;text-align:left;">' +
              '<b>' + ids.length + ' data</b> akan dikirim ke MIS untuk ditentukan PIC dan tipe industri.<br>' +
              '<span style="color:#f59e0b;">Proses ini tidak bisa dibatalkan.</span>' +
              '</div>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0c82f9',
        cancelButtonColor: '#484f58',
        confirmButtonText: '<i class="bi bi-arrow-up-circle me-1"></i> Ya, Naikkan Semua!',
        cancelButtonText: 'Batal',
        background: '#1a1d21', color: '#fff'
    }).then(function(result) {
        if (!result.isConfirmed) return;

        // ── Placeholder: ganti dengan AJAX bulk-naikkan-mis ke backend ──
        Swal.fire({
            title: 'Segera Hadir',
            text: 'Endpoint bulk Naikkan ke MIS (' + ids.length + ' data) sedang dalam pengembangan.',
            icon: 'info',
            background: '#1a1d21', color: '#fff'
        });
    });
};

// =========================================================================
// MODULE UPLOAD GAMBAR (Paste / Drag-Drop / Click)
// =========================================================================
(function () {
    var MAX_IMAGES   = 3;
    var currentImages = [];   // array of filename string
    var prospekIdImg  = null; // id prospek yang sedang dibuka modal

    // ------------------------------------------------------------------
    // PUBLIC: dipanggil saat modal edit dibuka
    // ------------------------------------------------------------------
    window.initImageUpload = function (existingImages, id) {
        prospekIdImg  = id;
        currentImages = Array.isArray(existingImages) ? existingImages.slice() : [];
        renderPreviews();
        updateHiddenInput();
    };

    // ------------------------------------------------------------------
    // Setup sekali saja di DOMContentLoaded
    // ------------------------------------------------------------------
    function setupDropzone() {
        var dropzone  = document.getElementById('img-dropzone');
        var fileInput = document.getElementById('img-file-input');
        if (!dropzone || !fileInput) return;

        // Klik buka file dialog
        dropzone.addEventListener('click', function () {
            if (currentImages.length < MAX_IMAGES) fileInput.click();
        });

        // File dipilih via dialog
        fileInput.addEventListener('change', function () {
            handleFiles(this.files);
            this.value = '';
        });

        // Drag over
        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.borderColor = '#0c82f9';
            this.style.background  = 'rgba(12,130,249,0.06)';
        });

        // Drag leave
        dropzone.addEventListener('dragleave', function (e) {
            e.stopPropagation();
            resetDropzoneStyle();
        });

        // Drop
        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            resetDropzoneStyle();
            handleFiles(e.dataTransfer.files);
        });

        // Paste (Ctrl+V) — hanya aktif jika modal terbuka
        document.addEventListener('paste', function (e) {
            var modal = document.getElementById('modalEditProspek');
            if (!modal || !modal.classList.contains('show')) return;

            var items = e.clipboardData ? e.clipboardData.items : [];
            var blobs = [];
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    blobs.push(items[i].getAsFile());
                }
            }
            if (blobs.length > 0) {
                e.preventDefault();
                handleFiles(blobs);
            }
        });
    }

    function resetDropzoneStyle() {
        var dz = document.getElementById('img-dropzone');
        if (!dz) return;
        dz.style.borderColor = '#3e444b';
        dz.style.background  = '#1e2227';
    }

    // ------------------------------------------------------------------
    // Proses array file
    // ------------------------------------------------------------------
    function handleFiles(files) {
        var remaining = MAX_IMAGES - currentImages.length;
        if (remaining <= 0) {
            Swal.fire({
                title: 'Batas Gambar',
                text: 'Maksimal 3 gambar. Hapus salah satu untuk menambah.',
                icon: 'warning', background: '#1a1d21', color: '#fff'
            });
            return;
        }
        var limit = Math.min(files.length, remaining);
        for (var i = 0; i < limit; i++) {
            uploadFile(files[i]);
        }
    }

    // ------------------------------------------------------------------
    // Upload 1 file ke server
    // ------------------------------------------------------------------
    function uploadFile(file) {
        if (!file || !file.type.match('image.*')) return;

        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                title: 'File Terlalu Besar',
                text: file.name + ' melebihi batas 2MB.',
                icon: 'warning', background: '#1a1d21', color: '#fff'
            });
            return;
        }

        // Tambah placeholder loading
        var tempId = 'imgtemp-' + Date.now() + '-' + Math.floor(Math.random() * 9999);
        addLoadingPreview(tempId);

        var fd = new FormData();
        fd.append('image', file);
        fd.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: '/prospek_tampung/upload-image',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function (res) {
                removeLoadingPreview(tempId);
                if (res.success) {
                    currentImages.push(res.filename);
                    updateHiddenInput();
                    renderPreviews();
                } else {
                    Swal.fire({ title: 'Upload Gagal', text: res.message, icon: 'error', background: '#1a1d21', color: '#fff' });
                }
            },
            error: function () {
                removeLoadingPreview(tempId);
                Swal.fire({ title: 'Error', text: 'Gagal upload gambar ke server.', icon: 'error', background: '#1a1d21', color: '#fff' });
            }
        });
    }

    // ------------------------------------------------------------------
    // Hapus gambar (dari server + dari list)
    // ------------------------------------------------------------------
    window.removeImageFromList = function (filename) {
        // Jika prospek sudah tersimpan → hapus dari server juga
        if (prospekIdImg) {
            $.post(
                '/prospek_tampung/delete-image/' + prospekIdImg,
                { _token: $('meta[name="csrf-token"]').attr('content'), filename: filename },
                function (res) { /* silent */ }
            );
        }
        currentImages = currentImages.filter(function (f) { return f !== filename; });
        updateHiddenInput();
        renderPreviews();
    };

    // ------------------------------------------------------------------
    // Render semua preview
    // ------------------------------------------------------------------
    function renderPreviews() {
        var container = $('#img-preview-container');
        container.empty();

        currentImages.forEach(function (filename) {
            var imgUrl = '/prospek_tampung/image/' + encodeURIComponent(filename);
            var card   =
                '<div style="position:relative; width:80px; height:80px; border-radius:6px; ' +
                'overflow:hidden; border:1px solid #3e444b; flex-shrink:0;">' +
                    // Gambar
                    '<img src="' + imgUrl + '" loading="lazy" ' +
                    'style="width:100%;height:100%;object-fit:cover;display:block;" ' +
                    'onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'flex\'">' +
                    // Fallback jika gambar error
                    '<div style="display:none;width:100%;height:100%;background:#2a2d33;' +
                    'align-items:center;justify-content:center;font-size:1.2rem;color:#4b5563;">' +
                    '<i class="bi bi-image-fill"></i></div>' +
                    // Tombol hapus
                    '<button type="button" ' +
                    'onclick="window.removeImageFromList(\'' + filename.replace(/'/g, "\\'") + '\')" ' +
                    'title="Hapus gambar" ' +
                    'style="position:absolute;top:3px;right:3px;width:20px;height:20px;' +
                    'border-radius:50%;background:rgba(220,53,69,0.92);border:none;' +
                    'color:white;font-size:10px;display:flex;align-items:center;' +
                    'justify-content:center;padding:0;cursor:pointer;line-height:1;">' +
                    '<i class="bi bi-x-lg"></i></button>' +
                '</div>';
            container.append(card);
        });

        // Update counter & dropzone opacity
        var count = currentImages.length;
        $('#img-counter').text(count + '/' + MAX_IMAGES);

        var dz = document.getElementById('img-dropzone');
        if (dz) {
            dz.style.opacity = (count >= MAX_IMAGES) ? '0.45' : '1';
            dz.style.cursor  = (count >= MAX_IMAGES) ? 'not-allowed' : 'pointer';
        }
    }

    function addLoadingPreview(tempId) {
        var el =
            '<div id="' + tempId + '" ' +
            'style="width:80px;height:80px;border-radius:6px;background:#2a2d33;' +
            'border:1px dashed #3e444b;display:flex;align-items:center;' +
            'justify-content:center;flex-shrink:0;">' +
            '<span class="spinner-border spinner-border-sm text-secondary"></span></div>';
        $('#img-preview-container').append(el);
    }

    function removeLoadingPreview(tempId) {
        $('#' + tempId).remove();
    }

    function updateHiddenInput() {
        $('#edit-images').val(JSON.stringify(currentImages));
    }

    // Init dropzone saat DOM ready
    $(document).ready(function () {
        setupDropzone();
    });

})();

// =========================================================================
// FILTER TABS
// =========================================================================
window.filterProspek = function(statusTab, btnElement) {
    $(btnElement).parent().find('button').removeClass('active');
    $(btnElement).addClass('active');
    if (typeof window.loadProspekPartial === 'function') {
        window.loadProspekPartial(1, statusTab);
    }
};

// =========================================================================
// DEPENDENT DROPDOWN REGION
// =========================================================================
window.fetchRegionModal = function(type, element, prefillValue) {
    prefillValue = prefillValue || null;
    var selectedOption = element.options[element.selectedIndex];
    var regionId = selectedOption ? selectedOption.getAttribute('data-id') : null;
    var targetId, url, objectKeyName, primaryKeyName;

    if (type === 'kota') {
        targetId = 'edit-kota'; url = '/prospek_tampung/region/kota/'+regionId;
        objectKeyName = 'city_name'; primaryKeyName = 'city_id';
        $('#edit-kecamatan').html('<option value="" data-id="">Pilih Kecamatan</option>').trigger('change.select2');
        $('#edit-kelurahan').html('<option value="" data-id="">Pilih Kelurahan</option>').trigger('change.select2');
    } else if (type === 'kecamatan') {
        targetId = 'edit-kecamatan'; url = '/prospek_tampung/region/kecamatan/'+regionId;
        objectKeyName = 'dis_name'; primaryKeyName = 'dis_id';
        $('#edit-kelurahan').html('<option value="" data-id="">Pilih Kelurahan</option>').trigger('change.select2');
    } else if (type === 'kelurahan') {
        targetId = 'edit-kelurahan'; url = '/prospek_tampung/region/kelurahan/'+regionId;
        objectKeyName = 'subdis_name'; primaryKeyName = 'subdis_id';
    }

    if (!regionId) {
        $('#'+targetId).html('<option value="" data-id="">Pilih '+(type.charAt(0).toUpperCase()+type.slice(1))+'</option>').trigger('change.select2');
        return;
    }
    
    $('#'+targetId).html('<option value="">Memuat...</option>').trigger('change.select2');
    
    $.get(url, function(res) {
        var opts = '<option value="" data-id="">Pilih '+(type.charAt(0).toUpperCase()+type.slice(1))+'</option>';
        
        res.forEach(function(item) {
            // FIX: Ubah ke huruf kecil semua agar pencocokan nama kota/kec/kel tidak terganggu huruf kapital
            var dbVal = String(item[objectKeyName]).toLowerCase();
            var preVal = prefillValue ? String(prefillValue).toLowerCase() : '';
            var sel = (prefillValue && preVal === dbVal) ? 'selected' : '';
            
            opts += '<option value="'+item[objectKeyName]+'" data-id="'+item[primaryKeyName]+'" '+sel+'>'+item[objectKeyName]+'</option>';
        });
        
        $('#'+targetId).html(opts).trigger('change.select2');
        
        // Teruskan chain ke tingkat berikutnya jika ada prefillValue
        if (prefillValue && type === 'kota')      window.fetchRegionModal('kecamatan', document.getElementById('edit-kota'), window._tempPrefillKec);
        if (prefillValue && type === 'kecamatan') window.fetchRegionModal('kelurahan', document.getElementById('edit-kecamatan'), window._tempPrefillKel);
        
    }).fail(function() {
        $('#'+targetId).html('<option value="">Gagal memuat</option>').trigger('change.select2');
    });
};

// =========================================================================
// DEPENDENT DROPDOWN REGION — MODAL TAMBAH
// (versi terpisah agar tidak bentrok dengan modal edit)
// =========================================================================
window.fetchRegionTambah = function(type, element, prefillValue) {
    prefillValue = prefillValue || null;
    var selectedOption = element.options[element.selectedIndex];
    var regionId = selectedOption ? selectedOption.getAttribute('data-id') : null;
    var targetId, url, objectKeyName, primaryKeyName;

    if (type === 'kota') {
        targetId = 'tambah-kota'; url = '/prospek_tampung/region/kota/' + regionId;
        objectKeyName = 'city_name'; primaryKeyName = 'city_id';
        $('#tambah-kecamatan').html('<option value="" data-id="">Pilih Kecamatan</option>');
        $('#tambah-kelurahan').html('<option value="" data-id="">Pilih Kelurahan</option>');
    } else if (type === 'kecamatan') {
        targetId = 'tambah-kecamatan'; url = '/prospek_tampung/region/kecamatan/' + regionId;
        objectKeyName = 'dis_name'; primaryKeyName = 'dis_id';
        $('#tambah-kelurahan').html('<option value="" data-id="">Pilih Kelurahan</option>');
    } else if (type === 'kelurahan') {
        targetId = 'tambah-kelurahan'; url = '/prospek_tampung/region/kelurahan/' + regionId;
        objectKeyName = 'subdis_name'; primaryKeyName = 'subdis_id';
    }

    if (!regionId) {
        var label = type.charAt(0).toUpperCase() + type.slice(1);
        $('#' + targetId).html('<option value="" data-id="">Pilih ' + label + '</option>');
        return;
    }

    $('#' + targetId).html('<option value="">Memuat...</option>');
    $.get(url, function(res) {
        var label = type.charAt(0).toUpperCase() + type.slice(1);
        var opts = '<option value="" data-id="">Pilih ' + label + '</option>';
        res.forEach(function(item) {
            var sel = (prefillValue && item[objectKeyName].toLowerCase().indexOf(prefillValue.toLowerCase().substring(0, 5)) > -1) ? 'selected' : '';
            opts += '<option value="' + item[objectKeyName] + '" data-id="' + item[primaryKeyName] + '" ' + sel + '>' + item[objectKeyName] + '</option>';
        });
        $('#' + targetId).html(opts);
    }).fail(function() {
        $('#' + targetId).html('<option value="">Gagal memuat</option>');
    });
};

// =========================================================================
// MODAL TAMBAH — TAB SWITCHING
// =========================================================================
window.switchTambahTab = function(num) {
    for (var i = 1; i <= 3; i++) {
        var content = document.getElementById('tambah-tab-content-' + i);
        var btn = document.querySelector('.tambah-tab-btn[data-tab="' + i + '"]');
        if (content) {
            if (i === num) {
                content.classList.remove('d-none');
            } else {
                content.classList.add('d-none');
            }
        }
        if (btn) {
            btn.classList.toggle('active', i === num);
        }
    }
    updateTambahFooterInfo(num);
};

function updateTambahFooterInfo(tab) {
    var info = document.getElementById('tambah-footer-info');
    if (!info) return;
    if (tab === 3) {
        var geoSource = document.getElementById('tambah-geo-source').value;
        if (geoSource === 'MANUAL_INPUT') {
            info.innerHTML = '<i class="bi bi-geo-alt-fill text-success me-1"></i> Koordinat manual tersimpan.';
        } else {
            info.innerHTML = '<i class="bi bi-exclamation-triangle text-warning me-1"></i> Geotag belum diisi — akan masuk pending.';
        }
    } else {
        info.innerHTML = '<i class="bi bi-info-circle me-1"></i> Field bertanda <span class="text-danger">*</span> wajib diisi.';
    }
}

// =========================================================================
// MODAL TAMBAH — GEOTAG (BACK OFFICE: INPUT MANUAL + NOMINATIM)
// =========================================================================
var tambahLeafletMap = null;
var tambahLeafletMarker = null;

window.onTambahCoordsInput = function() {
    var lat = document.getElementById('tambah-lat').value.trim();
    var lng = document.getElementById('tambah-lng').value.trim();
    var btn = document.getElementById('tambah-btn-autofill');
    var isValid = lat !== '' && lng !== '' &&
                  !isNaN(parseFloat(lat)) && !isNaN(parseFloat(lng));
    btn.disabled = !isValid;

    // Reset geo_source jika koordinat dihapus
    if (!isValid) {
        document.getElementById('tambah-geo-source').value = 'PENDING_GEOTAG';
        updateTambahGeoBadge('pending');
    }
};

window.doTambahReverseGeocode = function() {
    var lat = parseFloat(document.getElementById('tambah-lat').value.trim());
    var lng = parseFloat(document.getElementById('tambah-lng').value.trim());
    if (isNaN(lat) || isNaN(lng)) return;

    var btn = document.getElementById('tambah-btn-autofill');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    btn.disabled = true;

    fetch('https://nominatim.openstreetmap.org/reverse?lat=' + lat +
          '&lon=' + lng + '&format=json&addressdetails=1&accept-language=id', {
        headers: { 'User-Agent': 'LSFragrance-CRM/1.0' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (!data || !data.address) {
            Swal.fire({ title: 'Tidak Ditemukan', text: 'Koordinat tidak menghasilkan alamat.', icon: 'warning', background: '#161b22', color: '#fff' });
            return;
        }

        var addr = data.address;

        // ── Ambil nama provinsi dari Nominatim ──
        var provRaw = addr.state || addr.province || addr.region || '';
        // Buang prefix umum
        var provNorm = provRaw
            .replace(/^(provinsi|daerah istimewa|daerah khusus ibukota|dki)\s*/i, '')
            .trim()
            .toLowerCase();

        // ── Kota dari Nominatim ──
        var kotaRaw = addr.city || addr.regency || addr.county || addr.town || addr.village || '';
        var kotaNorm = kotaRaw
            .replace(/^(kota|kabupaten|kab\.?)\s*/i, '')
            .trim()
            .toLowerCase();

        // ── Alamat ──
        var alamatParts = [];
        if (addr.road)         alamatParts.push(addr.road);
        if (addr.house_number) alamatParts.push('No. ' + addr.house_number);
        if (addr.neighbourhood || addr.quarter) alamatParts.push(addr.neighbourhood || addr.quarter);
        if (alamatParts.length > 0) {
            document.getElementById('tambah-alamat').value = alamatParts.join(', ');
        }

        // ── Cari provinsi di dropdown — SCORING system ──
        // Tidak pakai 4 karakter pertama karena "jawa bar" vs "jawa tim" sama-sama cocok di 4 char
        var provSelect = document.getElementById('tambah-provinsi');
        var bestMatchIdx = -1;
        var bestScore = 0;

        for (var i = 0; i < provSelect.options.length; i++) {
            var optRaw = provSelect.options[i].text;
            var optNorm = optRaw
                .replace(/^(provinsi|daerah istimewa|daerah khusus ibukota|dki)\s*/i, '')
                .trim()
                .toLowerCase();

            if (!optNorm) continue;

            var score = 0;

            // Exact match = skor tertinggi
            if (optNorm === provNorm) {
                score = 100;
            }
            // Satu contains yang lain secara penuh
            else if (provNorm.indexOf(optNorm) > -1 || optNorm.indexOf(provNorm) > -1) {
                // Hitung panjang overlap sebagai skor
                score = Math.min(optNorm.length, provNorm.length) * 2;
            }
            // Partial: hitung berapa karakter berurutan yang sama dari depan
            else {
                var minLen = Math.min(optNorm.length, provNorm.length);
                var matchLen = 0;
                for (var c = 0; c < minLen; c++) {
                    if (optNorm[c] === provNorm[c]) matchLen++;
                    else break;
                }
                // Hanya anggap match jika minimal 6 karakter sama dari depan
                // Ini yang membedakan "jawa barat" vs "jawa timur" — berbeda di karakter ke-6
                if (matchLen >= 6) score = matchLen;
            }

            if (score > bestScore) {
                bestScore = score;
                bestMatchIdx = i;
            }
        }

        var matchedProvId = null;
        var matchedProvName = null;

        if (bestMatchIdx > -1 && bestScore >= 6) {
            provSelect.selectedIndex = bestMatchIdx;
            matchedProvId = provSelect.options[bestMatchIdx].getAttribute('data-id')
                         || provSelect.options[bestMatchIdx].value;
            matchedProvName = provSelect.options[bestMatchIdx].text;
        }

        // ── Set geo fields ──
        document.getElementById('tambah-geo-source').value = 'MANUAL_INPUT';
        document.getElementById('tambah-geo-captured-at').value =
            new Date().toISOString().slice(0, 19).replace('T', ' ');

        updateTambahGeoBadge('manual');
        renderTambahMap(lat, lng);
        updateTambahFooterInfo(3);

        // ── Load kota jika provinsi ditemukan ──
        if (matchedProvId) {
            $('#tambah-kota').html('<option value="">Memuat kota...</option>');
            $('#tambah-kecamatan').html('<option value="" data-id="">Pilih Kecamatan</option>');
            $('#tambah-kelurahan').html('<option value="" data-id="">Pilih Kelurahan</option>');

            $.get('/prospek_tampung/region/kota/' + matchedProvId, function(res) {
                var opts = '<option value="" data-id="">Pilih Kota</option>';
                var foundKota = false;

                res.forEach(function(item) {
                    var dbKotaNorm = item.city_name
                        .replace(/^(kota|kabupaten|kab\.?)\s*/i, '')
                        .trim()
                        .toLowerCase();

                    // Scoring kota juga pakai sistem yang sama
                    var kotaScore = 0;
                    if (dbKotaNorm === kotaNorm) {
                        kotaScore = 100;
                    } else if (kotaNorm.length >= 4) {
                        var minLen = Math.min(dbKotaNorm.length, kotaNorm.length);
                        var matchLen = 0;
                        for (var c = 0; c < minLen; c++) {
                            if (dbKotaNorm[c] === kotaNorm[c]) matchLen++;
                            else break;
                        }
                        if (matchLen >= 4) kotaScore = matchLen;
                    }

                    var sel = (kotaScore >= 4 && !foundKota) ? 'selected' : '';
                    if (kotaScore >= 4 && !foundKota) foundKota = true;

                    opts += '<option value="' + item.city_name + '" data-id="' + item.city_id + '" ' + sel + '>'
                          + item.city_name + '</option>';
                });

                $('#tambah-kota').html(opts);

                Swal.fire({
                    title: 'Auto-fill Berhasil',
                    html: '<div style="font-size:0.8rem;text-align:left;line-height:1.8">' +
                          '<b>Provinsi:</b> ' + (matchedProvName || provRaw || '-') + '<br>' +
                          '<b>Kota:</b> ' + (kotaRaw || '-') + '<br>' +
                          '<b>Alamat:</b> ' + (document.getElementById('tambah-alamat').value || '-') +
                          '</div>',
                    icon: 'success', timer: 2500, showConfirmButton: false,
                    background: '#161b22', color: '#fff'
                });

            }).fail(function() {
                Swal.fire({ title: 'Gagal', text: 'Gagal memuat data kota.', icon: 'error', background: '#161b22', color: '#fff' });
            });

        } else {
            Swal.fire({
                title: 'Provinsi Tidak Dikenali',
                html: '<div style="font-size:0.8rem;">Nominatim mengembalikan: <b>' + provRaw + '</b><br>' +
                      'Silakan pilih provinsi manual dari dropdown.</div>',
                icon: 'info', background: '#161b22', color: '#fff'
            });
        }
    })
    .catch(function(err) {
        console.error('Nominatim error:', err);
        Swal.fire({
            title: 'Gagal Auto-fill',
            text: 'Tidak dapat terhubung ke layanan geocoding. Coba beberapa saat lagi.',
            icon: 'error', background: '#161b22', color: '#fff'
        });
    })
    .finally(function() {
        btn.innerHTML = '<i class="bi bi-search me-1"></i> Auto-fill';
        btn.disabled = false;
    });
};

window.doTambahSkipGeo = function() {
    document.getElementById('tambah-lat').value = '';
    document.getElementById('tambah-lng').value = '';
    document.getElementById('tambah-geo-source').value = 'PENDING_GEOTAG';
    document.getElementById('tambah-geo-accuracy').value = '';
    document.getElementById('tambah-geo-captured-at').value = '';
    document.getElementById('tambah-btn-autofill').disabled = true;
    document.getElementById('tambah-map-wrap').classList.add('d-none');
    updateTambahGeoBadge('pending');
    updateTambahFooterInfo(3);

    Swal.fire({
        title: 'Geotag Dilewati',
        text: 'Data akan disimpan dengan status Pending Geotag.',
        icon: 'info', timer: 1800, showConfirmButton: false,
        background: '#161b22', color: '#fff'
    });
};

function renderTambahMap(lat, lng) {
    var wrap = document.getElementById('tambah-map-wrap');
    wrap.classList.remove('d-none');

    if (!tambahLeafletMap) {
        tambahLeafletMap = L.map('tambah-map', { zoomControl: true, scrollWheelZoom: false }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19
        }).addTo(tambahLeafletMap);
        tambahLeafletMarker = L.marker([lat, lng]).addTo(tambahLeafletMap);
    } else {
        tambahLeafletMap.setView([lat, lng], 16);
        tambahLeafletMarker.setLatLng([lat, lng]);
        setTimeout(function() { tambahLeafletMap.invalidateSize(); }, 100);
    }
}

function updateTambahGeoBadge(status) {
    var badge = document.getElementById('tambah-geo-status-badge');
    if (!badge) return;
    if (status === 'manual') {
        badge.innerHTML = '<span class="badge bg-success rounded-pill" style="font-size:0.6rem;"><i class="bi bi-check-circle me-1"></i>Manual Input</span>';
    } else {
        badge.innerHTML = '<span class="badge bg-secondary rounded-pill" style="font-size:0.6rem;"><i class="bi bi-clock me-1"></i>Pending Geotag</span>';
    }
}

// =========================================================================
// MODAL TAMBAH — IMAGE UPLOAD (terpisah dari modal edit)
// =========================================================================
(function() {
    var MAX = 3;
    var imgs = [];

    function setup() {
        var dz = document.getElementById('tambah-img-dropzone');
        var fi = document.getElementById('tambah-img-file-input');
        if (!dz || !fi) return;

        dz.addEventListener('click', function() {
            if (imgs.length < MAX) fi.click();
        });
        fi.addEventListener('change', function() {
            handleFiles(this.files); this.value = '';
        });
        dz.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = '#3fb950';
            this.style.background = 'rgba(46,160,67,0.08)';
        });
        dz.addEventListener('dragleave', function() { resetDz(); });
        dz.addEventListener('drop', function(e) {
            e.preventDefault(); resetDz();
            handleFiles(e.dataTransfer.files);
        });
        document.addEventListener('paste', function(e) {
            var modal = document.getElementById('modalTambahProspek');
            if (!modal || !modal.classList.contains('show')) return;
            var blobs = [];
            var items = e.clipboardData ? e.clipboardData.items : [];
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) blobs.push(items[i].getAsFile());
            }
            if (blobs.length) { e.preventDefault(); handleFiles(blobs); }
        });
    }

    function resetDz() {
        var dz = document.getElementById('tambah-img-dropzone');
        if (!dz) return;
        dz.style.borderColor = '#2ea043';
        dz.style.background = '#0d1117';
    }

    function handleFiles(files) {
        var rem = MAX - imgs.length;
        if (rem <= 0) {
            Swal.fire({ title: 'Batas Gambar', text: 'Maksimal 3 gambar.', icon: 'warning', background: '#1a1d21', color: '#fff' });
            return;
        }
        for (var i = 0; i < Math.min(files.length, rem); i++) uploadFile(files[i]);
    }

    function uploadFile(file) {
        if (!file || !file.type.match('image.*')) return;
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({ title: 'File Terlalu Besar', text: file.name + ' melebihi 2MB.', icon: 'warning', background: '#1a1d21', color: '#fff' });
            return;
        }
        var tempId = 'timg-' + Date.now();
        $('#tambah-img-preview-container').append(
            '<div id="' + tempId + '" style="width:80px;height:80px;border-radius:6px;background:#2a2d33;' +
            'border:1px dashed #3e444b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
            '<span class="spinner-border spinner-border-sm text-secondary"></span></div>'
        );
        var fd = new FormData();
        fd.append('image', file);
        fd.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $.ajax({
            url: '/prospek_tampung/upload-image', type: 'POST',
            data: fd, processData: false, contentType: false,
            success: function(res) {
                $('#' + tempId).remove();
                if (res.success) { imgs.push(res.filename); renderPreviews(); }
                else Swal.fire({ title: 'Upload Gagal', text: res.message, icon: 'error', background: '#1a1d21', color: '#fff' });
            },
            error: function() {
                $('#' + tempId).remove();
                Swal.fire({ title: 'Error', text: 'Gagal upload gambar.', icon: 'error', background: '#1a1d21', color: '#fff' });
            }
        });
    }

    window.removeTambahImage = function(filename) {
        imgs = imgs.filter(function(f) { return f !== filename; });
        renderPreviews();
    };

    function renderPreviews() {
        var c = $('#tambah-img-preview-container');
        c.empty();
        imgs.forEach(function(filename) {
            var url = '/prospek_tampung/image/' + encodeURIComponent(filename);
            c.append(
                '<div style="position:relative;width:80px;height:80px;border-radius:6px;overflow:hidden;border:1px solid #3e444b;flex-shrink:0;">' +
                '<img src="' + url + '" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;">' +
                '<button type="button" onclick="window.removeTambahImage(\'' + filename.replace(/'/g, "\\'") + '\')" ' +
                'style="position:absolute;top:3px;right:3px;width:20px;height:20px;border-radius:50%;' +
                'background:rgba(220,53,69,0.92);border:none;color:white;font-size:10px;' +
                'display:flex;align-items:center;justify-content:center;padding:0;cursor:pointer;">' +
                '<i class="bi bi-x-lg"></i></button></div>'
            );
        });
        $('#tambah-img-counter').text(imgs.length + '/' + MAX);
        $('#tambah-images').val(JSON.stringify(imgs));
        var dz = document.getElementById('tambah-img-dropzone');
        if (dz) {
            dz.style.opacity = imgs.length >= MAX ? '0.45' : '1';
            dz.style.cursor = imgs.length >= MAX ? 'not-allowed' : 'pointer';
        }
    }

    // Reset saat modal ditutup
    $('#modalTambahProspek').on('hidden.bs.modal', function() {
        imgs = [];
        renderPreviews();
        // Reset semua field form
        document.getElementById('form-tambah-prospek').reset();
        // Reset tab ke 1
        switchTambahTab(1);
        // Reset geotag
        document.getElementById('tambah-geo-source').value = 'PENDING_GEOTAG';
        document.getElementById('tambah-geo-status-badge').innerHTML = '';
        document.getElementById('tambah-map-wrap').classList.add('d-none');
        if (tambahLeafletMap) {
            tambahLeafletMap.remove();
            tambahLeafletMap = null;
            tambahLeafletMarker = null;
        }
        // Reset kota/kec/kel
        $('#tambah-kota').html('<option value="" data-id="">Pilih Kota</option>');
        $('#tambah-kecamatan').html('<option value="" data-id="">Pilih Kecamatan</option>');
        $('#tambah-kelurahan').html('<option value="" data-id="">Pilih Kelurahan</option>');
    });

    $(document).ready(function() { setup(); });
})();

// =========================================================================
// PERBAIKAN: refreshAllRowProgress() - Tambah onclick untuk L1 (Review)
// =========================================================================
window.refreshAllRowProgress = function() {
    $('.progress-container').each(function() {
        var container = $(this);
        var id   = container.data('id');
        var data = container.data('json');
        if (typeof data === 'string') data = JSON.parse(data);

        // ── KOREKSI 1: Bungkus dengan String() agar fungsi .trim() tidak memicu error
        // ketika menerima data integer (seperti nomor telepon).
        var hasNama    = !!(data.nama && String(data.nama).trim() !== '');
        var hasPhone   = !!(data.phone && String(data.phone).trim() !== '');
        var hasAlamat  = !!(data.alamat && String(data.alamat).trim() !== '');
        var hasProv    = !!(data.provinsi && String(data.provinsi).trim() !== '');
        var hasKota    = !!(data.kota && String(data.kota).trim() !== '');
        var progress   = 0;

        if(hasNama) progress    += 15;
        if(hasPhone) progress   += 15;
        if(hasAlamat) progress  += 15;
        if(hasProv) progress    += 10;
        if(hasKota) progress    += 10;

        var ok = (hasNama && hasPhone && hasAlamat && hasProv && hasKota);
        
        // Bungkus juga kalkulasi tambahannya agar aman
        if(data.zone && String(data.zone).trim() !== '') progress            += 3;
        if(data.kecamatan && String(data.kecamatan).trim() !== '') progress += 3;
        if(data.kelurahan && String(data.kelurahan).trim() !== '') progress += 3;
        if(data.kategori && String(data.kategori).trim() !== '') progress   += 4;
        if(data.pengajuan && String(data.pengajuan).trim() !== '') progress += 5;
        if(data.pic && String(data.pic).trim() !== '') progress             += 10;
        if(data.officer && String(data.officer).trim() !== '') progress      += 7;

        if(progress > 100) progress = 100;

        var actionBtn = '';
        var colorClass = 'bg-secondary';

        // ═════════════════════════════════════════════════════════════════
        // 🔴 KONDISI L1 (BARU) - Data belum masuk L2 (belum review SPV)
        // ═════════════════════════════════════════════════════════════════
        // ── KOREKSI 2: Gunakan `==` agar String "0" tetap terdeteksi sebagai 0
        if (data.status_request == 0 || !data.status_request || data.status_request === null) {
            
            // ✅ SET ONCLICK PADA BARIS TABEL
            $('#row-prospek-' + id)
                .attr('onclick', 'window.openModalEdit(' + id + ')')
                .css('cursor', 'pointer');
        
            // Tampilkan tombol "Ajukan ke L2"
            if (ok) {
                actionBtn = '<button class="btn btn-sm btn-primary rounded-pill w-100 shadow-sm fw-bold" style="font-size:0.65rem;" ' +
                            'onclick="event.stopPropagation(); window.mutasiProspek(' + id + ')">' +
                            '<i class="bi bi-send me-1"></i> Lanjutkan</button>';
                colorClass = 'bg-primary';
            } else {
                actionBtn = '<span class="badge bg-secondary w-100 py-1 rounded-pill" style="font-size:0.65rem;">' +
                            '<i class="bi bi-hourglass"></i> Belum Lengkap</span>';
            }
        }
        
        // ═════════════════════════════════════════════════════════════════
        // 🟠 KONDISI DITOLAK (status_request == 3) — Perlu direvisi & diajukan ulang
        // ═════════════════════════════════════════════════════════════════
        else if (data.status_request == 3) {
        
            $('#row-prospek-' + id)
                .attr('onclick', 'window.openModalEdit(' + id + ')')
                .css('cursor', 'pointer');
        
            if (ok) {
                actionBtn = '<button class="btn btn-sm btn-danger rounded-pill w-100 shadow-sm fw-bold" style="font-size:0.65rem;" ' +
                            'onclick="event.stopPropagation(); window.mutasiProspek(' + id + ')">' +
                            '<i class="bi bi-arrow-repeat me-1"></i> Ajukan Ulang</button>';
                colorClass = 'bg-danger';
            } else {
                actionBtn = '<span class="badge bg-secondary w-100 py-1 rounded-pill" style="font-size:0.65rem;">' +
                            '<i class="bi bi-hourglass"></i> Belum Lengkap</span>';
            }
        }
        
        // ═════════════════════════════════════════════════════════════════
        // 🟡 KONDISI L2 (APPROVED) - Data sedang menunggu review SPV
        // ═════════════════════════════════════════════════════════════════
        else if (data.status_request == 1) {
            $('#row-prospek-' + id)
                .attr('onclick', 'window.openModalApproved(' + id + ')')
                .css('cursor', 'pointer');
        
            actionBtn = '<button class="btn btn-sm btn-success rounded-pill w-100 shadow-sm fw-bold" style="font-size:0.65rem;" ' +
                        'onclick="event.stopPropagation(); window.actionApproveToL2(' + id + ')">' +
                        '<i class="bi bi-check2-all me-1"></i> Lanjutkan</button>';
            colorClass = 'bg-success';
        }
        
        // ═════════════════════════════════════════════════════════════════
        // 🟣 KONDISI L3 (PIC) - Data menunggu Setting PIC & Market Insight
        // ═════════════════════════════════════════════════════════════════
        else if (data.status_request == 2) {
            var hasPic = (data.pic && String(data.pic).trim() !== '' && data.pic !== '-');
            var src = (data.source || '').toString().toUpperCase().trim();
            // Kode asli: var isSpgSource = (src === 'APM' || src === 'SPG');
            var isSpgSource = window.TEMP_EQUALIZE_SOURCE_WITH_APM ? true : (src === 'APM' || src === 'SPG');
        
            if (!hasPic) {
        
                if (isSpgSource) {
                    // ── Source APM/SPG: tombol Set PIC (select2) seperti biasa ──
                    $('#row-prospek-' + id)
                        .attr('onclick', 'event.stopPropagation(); window.openModalSetPicAwal(' + id + ');')
                        .css('cursor', 'pointer');
        
                    actionBtn = '<button class="btn btn-sm btn-outline-warning rounded-pill w-100 shadow-sm fw-bold" style="font-size:0.65rem;" ' +
                                'onclick="event.stopPropagation(); window.openModalSetPicAwal(' + id + ');">' +
                                '<i class="bi bi-person-add me-1"></i> Set PIC</button>';
                    colorClass = 'bg-warning';
        
                } else if (data.mis_status === 'requested') {
                    // ── Sudah dikirim ke MIS, tinggal menunggu di-assign (AM/ASM/PIC/SPG) ──
                    $('#row-prospek-' + id)
                        .removeAttr('onclick')
                        .css('cursor', 'default');
        
                    actionBtn = '<span class="badge bg-secondary w-100 py-1 rounded-pill" style="font-size:0.65rem;">' +
                                '<i class="bi bi-hourglass-split me-1"></i> Menunggu MIS</span>';
                    colorClass = 'bg-secondary';
        
                } else {
                    // ── Source lain (AO/manual): tombol Naikkan ke MIS langsung di tabel ──
                    $('#row-prospek-' + id)
                        .attr('onclick', 'event.stopPropagation(); window.actionNaikkanKeMis(' + id + ');')
                        .css('cursor', 'pointer');
        
                    actionBtn = '<button class="btn btn-sm btn-outline-info rounded-pill w-100 shadow-sm fw-bold" style="font-size:0.65rem;" ' +
                                'onclick="event.stopPropagation(); window.actionNaikkanKeMis(' + id + ');">' +
                                '<i class="bi bi-arrow-up-circle me-1"></i> Naikkan ke MIS</button>';
                    colorClass = 'bg-info';
                }
        
            } else {
                // PIC sudah ada (otomatis dari APM saat transaksi=1) → langsung ke modal mutasi, skip Set PIC
                $('#row-prospek-' + id)
                    .attr('onclick', 'window.openModalPic(' + id + ')')
                    .css('cursor', 'pointer');
        
                actionBtn = '<button class="btn btn-sm btn-warning rounded-pill w-100 shadow-sm fw-bold text-dark" style="font-size:0.65rem;" ' +
                            'onclick="event.stopPropagation(); window.openModalPic(' + id + ')">' +
                            '<i class="bi bi-person-gear me-1"></i> Mutasi</button>';
                colorClass = 'bg-warning';
            }
        }
        
        // ═════════════════════════════════════════════════════════════════
        // 🟢 KONDISI SUDAH DIMUTASI (L3 Completed)
        // ═════════════════════════════════════════════════════════════════
        else if (data.status_request == 6 || data.is_mutated == 1) {
            $('#row-prospek-' + id)
                .attr('onclick', 'window.openModalPic(' + id + ')')
                .css('cursor', 'pointer');
        
            actionBtn = '<button class="btn btn-sm btn-info rounded-pill w-100 shadow-sm fw-bold" style="font-size:0.65rem;" ' +
                        'onclick="event.stopPropagation(); window.openModalPic(' + id + ')">' +
                        '<i class="bi bi-graph-up me-1"></i> Update</button>';
            colorClass = 'bg-info';
        }

        // ── RENDER PROGRESS BAR ──
        container.html(
            '<div class="d-flex justify-content-between mb-1">' +
            '<span style="color:#adb5bd; font-size:0.8rem;">Progress</span>' +
            '<span class="text-white fw-bold" style="font-size:0.85rem;">' + progress + '%</span>' +
            '</div>' +
            '<div class="progress" style="height:6px;background:#3e444b;">' +
            '<div class="progress-bar ' + colorClass + '" style="width:' + progress + '%"></div>' +
            '</div>'
        );

        $('#action-container-' + id).html(actionBtn);
    });
};

// =========================================================================
// OPEN MODAL EDIT
// =========================================================================
window.openModalEdit = function(id) {
    var container = $('.progress-container[data-id="'+id+'"]').first();
    var data = null;

    if (container.length && container.data('json')) {
        data = container.data('json');
        if (typeof data === 'string') data = JSON.parse(data);
    } else {
        // Fallback AJAX jika elemen tidak terbaca
        $.get('/prospek_tampung/get-data/'+id, function(res) {
             if(res.success) {
                  var hasPicAjax = (res.data.pic && res.data.pic.trim() !== '' && res.data.pic !== '-');
                  if ((res.data.transaksi == 1 || res.data.transaksi === true) && !hasPicAjax) {
                      Swal.fire({ title: 'Data Sudah Transaksi', text: 'Data ini akan langsung masuk ke tab Setting PIC.', icon: 'info', background: '#161b22', color: '#e6edf3' });
                      return;
                  }
                  // Panggil fungsi populate (Nanti modal akan dibuka dari dalam fungsi ini)
                  window._populateEditModal(res.data, id);
             }
        });
        return;
    }

    var hasPic = (data.pic && data.pic.trim() !== '' && data.pic !== '-');

    if ((data.transaksi == 1 || data.transaksi === true) && !hasPic) {
        Swal.fire({
            title: 'Data Sudah Transaksi',
            text: 'Data ini akan langsung masuk ke tab Setting PIC.',
            icon: 'info',
            background: '#161b22', color: '#e6edf3'
        });
        return; 
    }

    // Panggil fungsi populate
    window._populateEditModal(data, id);
};

function _populateEditModal(data, id) {
    // 1. Tampilkan Nama PIC di Header
    $('#header-assigned-pic').text(data.pic ? data.pic : 'Belum Ditugaskan');

    // 2. Tampilkan Banner APM jika sumbernya dari APM
    if (data.source && data.source.toUpperCase() === 'APM') {
        $('#apm-info-banner').removeClass('d-none'); // Munculkan banner
        
        // Isi data dari database ke banner
        $('#info-apm-id').text(data.id || '-'); 
        $('#info-apm-nama').text(data.nama || '-'); 
    } else {
        // Jika dari Admin Manual, sembunyikan banner
        $('#apm-info-banner').addClass('d-none');
    }
    
    $('#edit-prospek-id').val(data.id);
    $('#edit-nama').val(data.nama||'');
    $('#edit-jabatan').val(data.jabatan||'');   // ← TAMBAH INI
    $('#edit-perusahaan').val(data.perusahaan||'');
    $('#edit-phone').val(data.phone||'');
    $('#edit-alamat').val(data.alamat||'');
    $('#edit-zone').val(data.zone||'');
    $('#edit-kategori').val(data.kategori||'');
    $('#edit-pengajuan').val(data.pengajuan||'');
    
    // TAMPILKAN STATUS CHECKBOX (Centang jika nilai = 1, hilangkan jika 0/null)
    $('#edit-visit').prop('checked', data.visit == 1);
    $('#edit-transaksi').prop('checked', data.transaksi == 1);

    $('.select2-modal').select2({width:'100%',dropdownParent:$('#modalEditProspek')});
    $('#edit-kota').html('<option value="">Pilih Kota</option>').val(null);
    $('#edit-kecamatan').html('<option value="">Pilih Kecamatan</option>').val(null);
    $('#edit-kelurahan').html('<option value="">Pilih Kelurahan</option>').val(null);
    
    // Field Tambahan dari MIS
    $('#edit-model').val(data.model_bisnis || '');
    $('#edit-sosmed').val(data.media_sosial || '');
    $('#edit-marketplace').val(data.marketplace || '');
    $('#edit-multicabang').val(data.toko_multicabang || '');
    
    // Geotag
    $('#edit-lat').val(data.latitude || '');
    $('#edit-lng').val(data.longitude || '');

    if (data.provinsi) {
        $('#edit-provinsi').val(data.provinsi).trigger('change.select2');
        window._tempPrefillKec = data.kecamatan;
        window._tempPrefillKel = data.kelurahan;
        window.fetchRegionModal('kota', document.getElementById('edit-provinsi'), data.kota);
    } else {
        $('#edit-provinsi').val('').trigger('change.select2');
    }

    // Load gambar
    var existingImages = [];
    if (data.image) {
        try { existingImages = Array.isArray(data.image) ? data.image : JSON.parse(data.image); } catch(e) {}
    }
    if (typeof window.initImageUpload === 'function') window.initImageUpload(existingImages, id);

    // Gunakan jQuery atau getOrCreateInstance agar backdrop tidak menumpuk
    $('#modalEditProspek').modal('show');
}

// =========================================================================
// MUTASI KE L2 (dari zone item — dipanggil setelah simpan + ajukan)
// =========================================================================
window.mutasiProspek = function(id) {
    Swal.fire({
        title:'Lanjutkan Prospek?',text:'Data akan diteruskan ke Menu Approved.',
        icon:'question',showCancelButton:true,
        confirmButtonColor:'#0c82f9',cancelButtonColor:'#dc3545',
        confirmButtonText:'<i class="bi bi-send"></i> Ya, Lanjutkan!',cancelButtonText:'Batal',
        background:'#1a1d21',color:'#fff'
    }).then(function(r) {
        if (!r.isConfirmed) return;
        Swal.fire({title:'Memproses...',allowOutsideClick:false,background:'#1a1d21',color:'#fff',didOpen:function(){Swal.showLoading();}});
        $.ajax({
            url:'/prospek_tampung/ajukan-l2/'+id,type:'POST',
            data:{_token:'{{ csrf_token() }}'},
            success:function(res){
                if(res.success){
                    Swal.fire({title:'Berhasil!',text:res.message,icon:'success',background:'#1a1d21',color:'#fff',timer:2000,showConfirmButton:false});
                    $('#row-prospek-'+id).fadeOut(300,function(){
                        $(this).remove();
                        if(typeof processFilter === "function") processFilter();
                    });
                } else {
                    Swal.fire({title:'Gagal',text:res.message,icon:'warning',background:'#1a1d21',color:'#fff'});
                }
            },
            error:function(xhr){ Swal.fire({title:'Error '+xhr.status,text:'Kesalahan sistem.',icon:'error',background:'#1a1d21',color:'#fff'}); }
        });
    });
};

// Untuk transaksi = 1 → langsung ke PIC
window.mutasiLangsungPic = function(id) {
    Swal.fire({
        title: 'Langsung ke Setting PIC?',
        text: 'Data sudah bertransaksi, akan langsung masuk ke tab PIC.',
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#484f58',
        confirmButtonText: '<i class="bi bi-send-check"></i> Ya, Lanjutkan!',
        cancelButtonText: 'Batal',
        background: '#1a1d21', color: '#fff'
    }).then(function(r) {
        if (!r.isConfirmed) return;
        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, background: '#1a1d21', color: '#fff', didOpen: function() { Swal.showLoading(); } });
        $.ajax({
            url: '/prospek_tampung/ajukan-pic/' + id,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.success) {
                    Swal.fire({ title: 'Berhasil!', text: res.message, icon: 'success', background: '#1a1d21', color: '#fff', timer: 2000, showConfirmButton: false });
                    $('#row-prospek-' + id).fadeOut(300, function() {
                        $(this).remove();
                        if (typeof processFilter === 'function') processFilter();
                    });
                } else {
                    Swal.fire({ title: 'Gagal', text: res.message, icon: 'warning', background: '#1a1d21', color: '#fff' });
                }
            },
            error: function(xhr) {
                Swal.fire({ title: 'Error ' + xhr.status, text: 'Kesalahan sistem.', icon: 'error', background: '#1a1d21', color: '#fff' });
            }
        });
    });
};

// =========================================================================
// 6. FUNGSI UNTUK SPV (L2)
// =========================================================================

window.openModalSpv = function(id) {
    var container = $('.progress-container[data-id="'+id+'"]').first();
    var data = container.data('json');
    if (typeof data === 'string') data = JSON.parse(data);
    $('#spv-prospek-id').val(data.id);
    $('#spv-nama-pic').text(data.nama||'Tanpa Nama');
    $('#spv-perusahaan').text(data.perusahaan||'-');
    $('#spv-phone').text(data.phone||'-');
    $('#spv-alamat').text(data.alamat||'-');
    $('#spv-pengajuan').text(data.pengajuan||'-');
    $('#spv-input-pic').val(data.pic||'');
    $('#spv-input-officer').val(data.officer||'');
    $('#spv-input-kategori').val(data.kategori||'');
    $('#spv-input-zone').val(data.zone||'');
    new bootstrap.Modal(document.getElementById('modalReviewSpv')).show();
};

// Aksi Setujui dan Mutasi ke L3
window.actionApproveSpv = function() {
    let id = $('#spv-prospek-id').val();
    let pic = $('#spv-input-pic').val();
    let kategori = $('#spv-input-kategori').val();
    let zone = $('#spv-input-zone').val();

    // Validasi Front-end
    if(!pic) {
        Swal.fire({ title: 'Peringatan', text: 'Nama PIC wajib diisi!', icon: 'warning', background: '#1a1d21', color: '#fff' });
        return;
    }

    Swal.fire({
        title: 'Setujui & Mutasi L3?',
        text: "Data akan dipindahkan ke Data Exist (Customer).",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-check2-all"></i> Ya, Setujui',
        background: '#1a1d21', color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, background: '#1a1d21', color: '#fff', didOpen: () => Swal.showLoading() });
            
            $.ajax({
                url: `/prospek_tampung/approve-l3/${id}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    pic: pic,
                    kategori: kategori,
                    zone: zone
                },
                success: function(res) {
                    if(res.success) {
                        Swal.fire({ title: 'Berhasil!', text: res.message, icon: 'success', background: '#1a1d21', color: '#fff' });
                        
                        // Tutup modal dan hilangkan baris
                        bootstrap.Modal.getInstance(document.getElementById('modalReviewSpv')).hide();
                        $(`#row-prospek-${id}`).fadeOut(300, function() { $(this).remove(); });
                    } else {
                        Swal.fire({ title: 'Gagal', text: res.message, icon: 'warning', background: '#1a1d21', color: '#fff' });
                    }
                },
                error: function(xhr) {
                    Swal.fire({ title: 'Error', text: 'Terjadi kesalahan sistem.', icon: 'error', background: '#1a1d21', color: '#fff' });
                }
            });
        }
    });
};

// Aksi Tolak (Revisi atau Buang)
window.actionTolakSpv = function() {
    let id = $('#spv-prospek-id').val();

    Swal.fire({
        title: 'Tolak Data Prospek?',
        text: "Pilih aksi untuk data ini:",
        icon: 'warning',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: '<i class="bi bi-arrow-return-left"></i> Revisi ke AO',
        denyButtonText: '<i class="bi bi-trash"></i> Buang (Hapus)',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ffc107',
        denyButtonColor: '#dc3545',
        background: '#1a1d21', color: '#fff'
    }).then((result) => {
        
        let actionType = '';
        if (result.isConfirmed) {
            actionType = 'revisi';
        } else if (result.isDenied) {
            actionType = 'hapus';
        } else {
            return;
        }

        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, background: '#1a1d21', color: '#fff', didOpen: () => Swal.showLoading() });
        
        $.ajax({
            url: `/prospek_tampung/reject-l2/${id}`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', action_type: actionType },
            success: function(res) {
                if(res.success) {
                    Swal.fire({ title: 'Berhasil!', text: res.message, icon: 'success', background: '#1a1d21', color: '#fff' });
                    bootstrap.Modal.getInstance(document.getElementById('modalReviewSpv')).hide();
                    $(`#row-prospek-${id}`).fadeOut(300, function() { $(this).remove(); });
                } else {
                    Swal.fire({ title: 'Gagal', text: res.message, icon: 'error', background: '#1a1d21', color: '#fff' });
                }
            },
            error: function(xhr) {
                Swal.fire({ title: 'Error', text: 'Terjadi kesalahan sistem.', icon: 'error', background: '#1a1d21', color: '#fff' });
            }
        });
    });
};

window.saveProspekEdit = function() {
    var btnSave = $('#btn-save-edit-prospek');
    var orig    = btnSave.html();
    var id      = $('#edit-prospek-id').val();
    btnSave.html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...').attr('disabled',true);

    var formData = {};
    $('#form-edit-prospek').serializeArray().forEach(function(i){ formData[i.name]=i.value; });

    $.ajax({
        url:'/prospek_tampung/update/'+id, type:'POST',
        data: Object.assign({}, formData, {_token:'{{ csrf_token() }}'}),
        success: function(res) {
            if (res.success) {
                Swal.fire({title:'Berhasil!',text:res.message,icon:'success',timer:1500,showConfirmButton:false,background:'#24292f',color:'#fff'});

                // Update tabel jika ada (Berlaku untuk L1/L2/L3)
                var $row = $('#row-prospek-'+id);
                if ($row.length) {
                    $row.find('td:eq(1) .td-name').text(res.data.nama);
                    $row.find('td:eq(1) .td-sub').text(res.data.perusahaan||'-');
                    
                    if ($row.hasClass('l1-dynamic-row')) {
                        $row.find('td:eq(0) div:eq(0)').text(res.data.provinsi||'-');
                        $row.find('td:eq(0) div:eq(1)').text(res.data.kota||'-');
                        $row.find('td:eq(2) div:eq(0)').html('<i class="bi bi-telephone"></i> '+(res.data.phone||'-'));
                        // Update attribute pencarian/filter
                        $row.attr('data-zone', (res.data.zone||'TANPA ZONA').toUpperCase());
                        $row.attr('data-kat', (res.data.kategori||'NONE').toUpperCase());
                        $row.attr('data-search', (res.data.nama+' '+(res.data.provinsi||'')+' '+(res.data.kota||'')+' '+(res.data.perusahaan||'')).toLowerCase());
                    } else {
                        $row.find('td:eq(2) .td-small').html('<i class="bi bi-telephone"></i> '+(res.data.phone||'-'));
                        $row.find('td:eq(2) .td-sub').text(res.data.kota||'-');
                    }

                    var $c = $row.find('.progress-container');
                    $c.data('json',res.data).attr('data-json',JSON.stringify(res.data));
                }

                bootstrap.Modal.getInstance(document.getElementById('modalEditProspek'))?.hide();
                window.refreshAllRowProgress();
                
                // Refresh filter jika di L1
                if(typeof processFilter === "function") processFilter();
            } else {
                Swal.fire({title:'Gagal!',text:res.message,icon:'warning',background:'#24292f',color:'#fff'});
            }
        },
        error: function(){ Swal.fire({title:'Error!',text:'Gagal menyimpan.',icon:'error',background:'#24292f',color:'#fff'}); },
        complete: function(){ btnSave.html(orig).attr('disabled',false); }
    });
};


    // =========================================================================
// LOGIKA TAB & MAP UNTUK MODAL EDIT
// =========================================================================
window.switchEditTab = function(num) {
    for (var i = 1; i <= 3; i++) {
        var content = document.getElementById('edit-tab-content-' + i);
        var btn = document.querySelector('.tambah-tab-btn[data-edittab="' + i + '"]');
        if (content) {
            if (i === num) {
                content.classList.remove('d-none');
            } else {
                content.classList.add('d-none');
            }
        }
        if (btn) {
            btn.classList.toggle('active', i === num);
        }
    }
};

// Modifikasi _populateEditModal agar me-reset Tab kembali ke Tab 1 saat modal dibuka
var originalPopulateEdit = window._populateEditModal;

window._populateEditModal = function(data, id) {
    if ($('#header-assigned-pic').length) {
        $('#header-assigned-pic').text(data.pic ? data.pic : 'Belum Ditugaskan');
    }
    if ($('#apm-info-banner').length) {
        if (data.source && data.source.toUpperCase().includes('APM')) {
            $('#apm-info-banner').removeClass('d-none');
            $('#info-apm-id').text(data.id || '-'); 
            $('#info-apm-nama').text(data.nama || '-'); 
        } else {
            $('#apm-info-banner').addClass('d-none');
        }
    }
    
    $('#edit-prospek-id').val(data.id || '');
    $('#edit-nama').val(data.nama || '');
    $('#edit-jabatan').val(data.jabatan || '');   
    $('#edit-perusahaan').val(data.perusahaan || '');
    $('#edit-phone').val(data.phone || '');
    $('#edit-alamat').val(data.alamat || '');
    $('#edit-zone').val(data.zone || '');
    $('#edit-kategori').val(data.kategori || '');
    $('#edit-pengajuan').val(data.pengajuan || '');
    
    $('#edit-visit').prop('checked', data.visit == 1 || data.visit === true);
    $('#edit-transaksi').prop('checked', data.transaksi == 1 || data.transaksi === true);

    $('#edit-model').val(data.model_bisnis || '');
    $('#edit-sosmed').val(data.media_sosial || '');
    $('#edit-marketplace').val(data.marketplace || '');
    $('#edit-multicabang').val(data.toko_multicabang || '');
    
    $('#edit-lat').val(data.latitude || '');
    $('#edit-lng').val(data.longitude || '');

    // =========================================================================
    // FIX WILAYAH: Hapus Opsi Lama Secara Paksa
    // =========================================================================
    $('#edit-kota').empty().append('<option value="">Pilih Kota</option>');
    $('#edit-kecamatan').empty().append('<option value="">Pilih Kecamatan</option>');
    $('#edit-kelurahan').empty().append('<option value="">Pilih Kelurahan</option>');

    // Re-init Select2
    if ($('.select2-modal').hasClass("select2-hidden-accessible")) {
        $('.select2-modal').select2('destroy');
    }
    $('.select2-modal').select2({width:'100%', dropdownParent:$('#modalEditProspek')});
    
    if (data.provinsi) {
        window._tempPrefillKec = data.kecamatan || null;
        window._tempPrefillKel = data.kelurahan || null;

        // Pencarian Provinsi kebal huruf besar/kecil
        var $provSelect = $('#edit-provinsi');
        var provMatched = false;
        
        $provSelect.find('option').each(function() {
            if (String($(this).val()).toLowerCase() === String(data.provinsi).toLowerCase()) {
                $provSelect.val($(this).val());
                provMatched = true;
            }
        });

        if (provMatched) {
            $provSelect.trigger('change.select2');
            // Trigger fungsi AJAX untuk Kota
            window.fetchRegionModal('kota', document.getElementById('edit-provinsi'), data.kota);
        } else {
            $provSelect.val('').trigger('change.select2');
        }
    } else {
        $('#edit-provinsi').val('').trigger('change.select2');
    }
    // =========================================================================

    var existingImages = [];
    if (data.image) {
        try { existingImages = Array.isArray(data.image) ? data.image : JSON.parse(data.image); } catch(e) {}
    }
    if (typeof window.initImageUpload === 'function') window.initImageUpload(existingImages, id);

    if (typeof window.switchEditTab === 'function') {
        window.switchEditTab(1); // Paksa selalu buka di Tab 1
    }

    $('#modalEditProspek').modal('show');
};

window.onEditCoordsInput = function() {
    var lat = document.getElementById('edit-lat').value.trim();
    var lng = document.getElementById('edit-lng').value.trim();
    var btn = document.getElementById('edit-btn-autofill');
    btn.disabled = !(lat !== '' && lng !== '' && !isNaN(parseFloat(lat)) && !isNaN(parseFloat(lng)));
};


// =========================================================================
// PERBAIKAN AUTO-FILL BERANTAI (CHAINED AJAX) SAMPAI KELURAHAN
// =========================================================================

// Helper function untuk scoring pencarian wilayah
function findBestMatch(options, searchStr) {
    var bestMatchIdx = -1;
    var bestScore = 0;
    var searchNorm = searchStr.toLowerCase().replace(/^(kota|kabupaten|kab\.?|kecamatan|kec\.?|kelurahan|desa|kampung)\s*/i, '').trim();

    if (!searchNorm) return -1;

    for (var i = 0; i < options.length; i++) {
        // Abaikan option pertama (placeholder)
        if (options[i].value === "") continue; 

        var optNorm = options[i].text.toLowerCase().replace(/^(kota|kabupaten|kab\.?|kecamatan|kec\.?|kelurahan|desa|kampung)\s*/i, '').trim();
        var score = 0;

        if (optNorm === searchNorm) {
            score = 100;
        } else if (searchNorm.indexOf(optNorm) > -1 || optNorm.indexOf(searchNorm) > -1) {
            score = Math.min(optNorm.length, searchNorm.length) * 2;
        } else {
            var minLen = Math.min(optNorm.length, searchNorm.length);
            var matchLen = 0;
            for (var c = 0; c < minLen; c++) {
                if (optNorm[c] === searchNorm[c]) matchLen++;
                else break;
            }
            if (matchLen >= 4) score = matchLen;
        }

        if (score > bestScore) {
            bestScore = score;
            bestMatchIdx = i;
        }
    }
    return bestScore >= 4 ? bestMatchIdx : -1;
}

// FUNGSI UTAMA UNTUK CHAINED AUTO-FILL (Bisa dipakai untuk Tambah & Edit)
function processChainedGeocode(lat, lng, prefix) {
    var btn = document.getElementById(prefix + '-btn-autofill');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    btn.disabled = true;

    fetch('https://nominatim.openstreetmap.org/reverse?lat=' + lat + '&lon=' + lng + '&format=json&addressdetails=1&accept-language=id', {
        headers: { 'User-Agent': 'LSFragrance-CRM/1.0' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data || !data.address) {
            Swal.fire({ title: 'Tidak Ditemukan', text: 'Koordinat tidak menghasilkan alamat.', icon: 'warning', background: '#161b22', color: '#fff' });
            return;
        }

        var addr = data.address;
        
        // 1. Ekstrak data dari Nominatim
        var provRaw = addr.state || addr.province || addr.region || '';
        var kotaRaw = addr.city || addr.regency || addr.county || addr.town || '';
        var kecRaw  = addr.municipality || addr.city_district || addr.suburb || addr.district || '';
        var kelRaw  = addr.village || addr.hamlet || addr.neighbourhood || addr.quarter || '';
        
        var alamatParts = [];
        if (addr.road) alamatParts.push(addr.road);
        if (addr.house_number) alamatParts.push('No. ' + addr.house_number);
        if (alamatParts.length > 0) document.getElementById(prefix + '-alamat').value = alamatParts.join(', ');

        // Map rendering
        if(prefix === 'tambah') {
            renderTambahMap(lat, lng);
            document.getElementById('tambah-geo-source').value = 'MANUAL_INPUT';
            updateTambahGeoBadge('manual');
        } else {
            var wrap = document.getElementById('edit-map-wrap');
            wrap.classList.remove('d-none');
            // Jika ada instance editLeafletMap silakan render disini
        }

        // 2. CHAINING: Cari Provinsi
        var provSelect = document.getElementById(prefix + '-provinsi');
        var provIdx = findBestMatch(provSelect.options, provRaw);
        
        if (provIdx > -1) {
            provSelect.selectedIndex = provIdx;
            var provId = provSelect.options[provIdx].getAttribute('data-id');
            $(provSelect).trigger('change.select2'); // Untuk update Select2 jika ada
            
            // 3. Fetch Kota
            $('#' + prefix + '-kota').html('<option value="">Memuat kota...</option>');
            $.get('/prospek_tampung/region/kota/' + provId, function(resKota) {
                var optKota = '<option value="" data-id="">Pilih Kota</option>';
                resKota.forEach(k => { optKota += `<option value="${k.city_name}" data-id="${k.city_id}">${k.city_name}</option>`; });
                var elKota = document.getElementById(prefix + '-kota');
                elKota.innerHTML = optKota;

                var kotaIdx = findBestMatch(elKota.options, kotaRaw);
                if (kotaIdx > -1) {
                    elKota.selectedIndex = kotaIdx;
                    var kotaId = elKota.options[kotaIdx].getAttribute('data-id');
                    $(elKota).trigger('change.select2');
                    
                    // 4. Fetch Kecamatan
                    $('#' + prefix + '-kecamatan').html('<option value="">Memuat kecamatan...</option>');
                    $.get('/prospek_tampung/region/kecamatan/' + kotaId, function(resKec) {
                        var optKec = '<option value="" data-id="">Pilih Kecamatan</option>';
                        resKec.forEach(kc => { optKec += `<option value="${kc.dis_name}" data-id="${kc.dis_id}">${kc.dis_name}</option>`; });
                        var elKec = document.getElementById(prefix + '-kecamatan');
                        elKec.innerHTML = optKec;

                        var kecIdx = findBestMatch(elKec.options, kecRaw);
                        if (kecIdx > -1) {
                            elKec.selectedIndex = kecIdx;
                            var kecId = elKec.options[kecIdx].getAttribute('data-id');
                            $(elKec).trigger('change.select2');

                            // 5. Fetch Kelurahan
                            $('#' + prefix + '-kelurahan').html('<option value="">Memuat kelurahan...</option>');
                            $.get('/prospek_tampung/region/kelurahan/' + kecId, function(resKel) {
                                var optKel = '<option value="" data-id="">Pilih Kelurahan</option>';
                                resKel.forEach(kl => { optKel += `<option value="${kl.subdis_name}" data-id="${kl.subdis_id}">${kl.subdis_name}</option>`; });
                                var elKel = document.getElementById(prefix + '-kelurahan');
                                elKel.innerHTML = optKel;

                                var kelIdx = findBestMatch(elKel.options, kelRaw);
                                if (kelIdx > -1) {
                                    elKel.selectedIndex = kelIdx;
                                    $(elKel).trigger('change.select2');
                                }
                                
                                // Selesai semua Chain
                                showSuccessAlert(provRaw, kotaRaw, kecRaw, kelRaw, prefix);
                            });
                        } else {
                            showSuccessAlert(provRaw, kotaRaw, kecRaw, kelRaw, prefix); // Stop di Kota
                        }
                    });
                } else {
                    showSuccessAlert(provRaw, kotaRaw, kecRaw, kelRaw, prefix); // Stop di Provinsi
                }
            });
        } else {
            Swal.fire({ title: 'Provinsi Tidak Dikenali', text: 'Nominatim: ' + provRaw + '. Isi manual.', icon: 'info', background: '#161b22', color: '#fff' });
        }
    })
    .catch(err => {
        Swal.fire({ title: 'Gagal', text: 'Koneksi API Geocoding gagal.', icon: 'error', background: '#161b22', color: '#fff' });
    })
    .finally(() => {
        btn.innerHTML = '<i class="bi bi-search me-1"></i>';
        btn.disabled = false;
    });
}

function showSuccessAlert(prov, kota, kec, kel, prefix) {
    Swal.fire({
        title: 'Auto-fill Selesai',
        html: '<div style="font-size:0.8rem;text-align:left;line-height:1.8">' +
              '<b>Prov:</b> ' + (prov || '-') + '<br>' +
              '<b>Kota:</b> ' + (kota || '-') + '<br>' +
              '<b>Kec:</b> ' + (kec || '-') + '<br>' +
              '<b>Kel:</b> ' + (kel || '-') + '<br>' +
              '<b>Alamat:</b> ' + (document.getElementById(prefix + '-alamat').value || '-') +
              '</div>',
        icon: 'success', timer: 2500, showConfirmButton: false,
        background: '#161b22', color: '#fff'
    });
}

// Tindihkan fungsi bawaan Modal Tambah ke logic baru
window.doTambahReverseGeocode = function() {
    var lat = parseFloat(document.getElementById('tambah-lat').value);
    var lng = parseFloat(document.getElementById('tambah-lng').value);
    if (!isNaN(lat) && !isNaN(lng)) processChainedGeocode(lat, lng, 'tambah');
};

// Buat fungsi tembakan untuk Modal Edit
window.doEditReverseGeocode = function() {
    var lat = parseFloat(document.getElementById('edit-lat').value);
    var lng = parseFloat(document.getElementById('edit-lng').value);
    if (!isNaN(lat) && !isNaN(lng)) processChainedGeocode(lat, lng, 'edit');
};

// =========================================================================
// MODAL APPROVED — TAB SWITCHING & POPULATE
// =========================================================================
window.switchApprovedTab = function(num) {
    for (var i = 1; i <= 3; i++) {
        var el  = document.getElementById('approved-tab-' + i);
        var btn = document.querySelector('.approved-tab-btn[data-aptab="' + i + '"]');
        if (el)  el.classList.toggle('d-none', i !== num);
        if (btn) btn.classList.toggle('active', i === num);
    }
    // Invalidate map saat tab 3 dibuka
    if (num === 3 && apLeafletMap) {
        setTimeout(function() { apLeafletMap.invalidateSize(); }, 150);
    }
};

var apLeafletMap    = null;
var apLeafletMarker = null;

window.openModalApproved = function(id) {
    var container = $('.progress-container[data-id="' + id + '"]').first();
    var data = container.data('json');
    if (typeof data === 'string') data = JSON.parse(data);

    // Set ID
    $('#approved-prospek-id').val(id);

    // Reset tab ke 1
    switchApprovedTab(1);

    // ── TAB 1: DATA PIC ──
    // Status badges
    $('#ap-badge-visit, #ap-badge-transaksi, #ap-badge-no-status').addClass('d-none');
    if (data.visit == 1 && data.transaksi == 1) {
        $('#ap-badge-visit, #ap-badge-transaksi').removeClass('d-none');
    } else if (data.visit == 1) {
        $('#ap-badge-visit').removeClass('d-none');
    } else if (data.transaksi == 1) {
        $('#ap-badge-transaksi').removeClass('d-none');
    } else {
        $('#ap-badge-no-status').removeClass('d-none');
    }

    // Identitas
    setApReadonly('ap-nama',    data.nama);
    setApReadonly('ap-jabatan', data.jabatan);
    setApReadonly('ap-phone',   data.phone);

    // Foto
    var fotoWrap = document.getElementById('ap-foto-wrap');
    fotoWrap.innerHTML = '';
    var images = [];
    if (data.image) {
        try { images = Array.isArray(data.image) ? data.image : JSON.parse(data.image); } catch(e) {}
    }
    if (images.length > 0) {
        $('#ap-foto-count').text(images.length + ' foto');
        images.forEach(function(fn) {
            var url = '/prospek_tampung/image/' + encodeURIComponent(fn);
            fotoWrap.innerHTML +=
                '<div style="width:80px;height:80px;border-radius:8px;overflow:hidden;border:1px solid #30363d;flex-shrink:0;">' +
                '<img src="' + url + '" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;" ' +
                'onerror="this.style.display=\'none\'">' +
                '</div>';
        });
    } else {
        $('#ap-foto-count').text('Tidak ada foto');
        fotoWrap.innerHTML =
            '<div class="ap-foto-empty">' +
            '<i class="bi bi-image" style="font-size:1.5rem;color:#3e444b;display:block;margin-bottom:4px;"></i>' +
            '<span style="font-size:0.7rem;color:#6b7280;">Tidak ada foto</span></div>';
    }

    // ── TAB 2: INFO BISNIS ──
    setApReadonly('ap-perusahaan',  data.perusahaan);
    setApReadonly('ap-zone',        data.zone);
    setApReadonly('ap-kategori',    data.kategori);
    setApReadonly('ap-model',       data.model_bisnis);
    setApReadonly('ap-sosmed',      data.media_sosial);
    setApReadonly('ap-marketplace', data.marketplace);
    setApReadonly('ap-multicabang', data.toko_multicabang);
    setApReadonly('ap-pengajuan',   data.pengajuan);

    // ── TAB 3: LOKASI & GEOTAG ──
    if (data.latitude && data.longitude) {
        // Ada geotag
        $('#ap-geo-info-wrap').removeClass('d-none');
        $('#ap-geo-pending-wrap').addClass('d-none');
        $('#ap-map-wrap').removeClass('d-none');

        // Info row
        var geoSource = data.geo_source || 'GPS';
        var accVal    = data.geo_accuracy ? Math.round(data.geo_accuracy) + ' meter' : '-';
        var capAt     = data.geo_captured_at || '-';

        // Warna badge akurasi
        var accColor = '#4ade80';
        var accLabel = 'Normal';
        if (data.geo_source === 'SUSPECTED_FAKE') { accColor = '#ef4444'; accLabel = '⚠ Suspected Fake'; }
        else if (data.geo_source === 'LOW_ACCURACY') { accColor = '#f59e0b'; accLabel = 'Sinyal Lemah'; }
        else if (data.geo_source === 'MANUAL_INPUT') { accColor = '#60a5fa'; accLabel = 'Manual Input'; }

        $('#ap-geo-info-row').html(
            '<span style="font-size:0.72rem;color:' + accColor + ';">' +
            '<i class="bi bi-bullseye me-1"></i>Akurasi: ' + accVal + ' — ' + accLabel + '</span>' +
            '<span style="font-size:0.72rem;color:#8b949e;"><i class="bi bi-clock me-1"></i>' + capAt + '</span>' +
            '<span style="font-size:0.72rem;color:#8b949e;"><i class="bi bi-flag me-1"></i>Sumber: ' + geoSource + '</span>'
        );

        // Koordinat
        setApReadonly('ap-lat', data.latitude);
        setApReadonly('ap-lng', data.longitude);
        setApReadonly('ap-accuracy', accVal);

        // Render peta
        var lat = parseFloat(data.latitude);
        var lng = parseFloat(data.longitude);
        renderApMap(lat, lng);

    } else {
        // Tidak ada geotag
        $('#ap-geo-info-wrap').addClass('d-none');
        $('#ap-geo-pending-wrap').removeClass('d-none');
        $('#ap-map-wrap').addClass('d-none');
        setApReadonly('ap-lat', null);
        setApReadonly('ap-lng', null);
        setApReadonly('ap-accuracy', null);

        // Destroy map jika ada
        if (apLeafletMap) {
            apLeafletMap.remove();
            apLeafletMap    = null;
            apLeafletMarker = null;
        }
    }

    // Wilayah
    setApReadonly('ap-provinsi',  data.provinsi);
    setApReadonly('ap-kota',      data.kota);
    setApReadonly('ap-kecamatan', data.kecamatan);
    setApReadonly('ap-kelurahan', data.kelurahan);
    setApReadonly('ap-alamat',    data.alamat);

    // Buka modal
    new bootstrap.Modal(document.getElementById('modalApprovedProspek')).show();
};

// Helper set value readonly
function setApReadonly(id, val) {
    var el = document.getElementById(id);
    if (!el) return;
    if (val && val.toString().trim() !== '') {
        el.textContent = val;
        el.classList.remove('empty');
    } else {
        el.textContent = '—';
        el.classList.add('empty');
    }
}

// Render Leaflet map (readonly)
function renderApMap(lat, lng) {
    var wrap = document.getElementById('ap-map-wrap');
    wrap.classList.remove('d-none');

    if (!apLeafletMap) {
        apLeafletMap = L.map('ap-map', {
            zoomControl:       true,
            scrollWheelZoom:   false,
            dragging:          false,  // readonly: tidak bisa drag
            doubleClickZoom:   false,
            touchZoom:         false,
        }).setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap', maxZoom: 19
        }).addTo(apLeafletMap);

        apLeafletMarker = L.marker([lat, lng]).addTo(apLeafletMap);
    } else {
        apLeafletMap.setView([lat, lng], 16);
        apLeafletMarker.setLatLng([lat, lng]);
        setTimeout(function() { apLeafletMap.invalidateSize(); }, 150);
    }
}

// ── Aksi Tolak ──
window.actionTolakApproved = function() {
    var id = $('#approved-prospek-id').val();
    Swal.fire({
        title: 'Tolak & Kembalikan?',
        text: 'Data akan dikembalikan ke AO untuk direvisi.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#484f58',
        confirmButtonText: '<i class="bi bi-arrow-return-left me-1"></i> Ya, Tolak',
        cancelButtonText: 'Batal',
        background: '#161b22', color: '#fff'
    }).then(function(r) {
        if (!r.isConfirmed) return;
        Swal.fire({ title: 'Memproses...', allowOutsideClick: false, background: '#161b22', color: '#fff', didOpen: function() { Swal.showLoading(); } });

        $.ajax({
            url: '/prospek_tampung/reject-l2/' + id,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', action_type: 'revisi' },
            success: function(res) {
                if (res.success) {
                    Swal.fire({ title: 'Berhasil!', text: res.message, icon: 'success', timer: 1800, showConfirmButton: false, background: '#161b22', color: '#fff' });
                    bootstrap.Modal.getInstance(document.getElementById('modalApprovedProspek')).hide();
                    $('#row-prospek-' + id).fadeOut(300, function() { $(this).remove(); });
                } else {
                    Swal.fire({ title: 'Gagal', text: res.message, icon: 'error', background: '#161b22', color: '#fff' });
                }
            },
            error: function() {
                Swal.fire({ title: 'Error', text: 'Terjadi kesalahan sistem.', icon: 'error', background: '#161b22', color: '#fff' });
            }
        });
    });
};

// ── Aksi Approve ke PIC (L2) ──
// Tambahkan parameter (idParam)
window.actionApproveToL2 = function(idParam = null) {
    
    // Ambil ID dari parameter (jika diklik dari tabel list) 
    // ATAU dari hidden input (jika diklik dari dalam modal)
    let id = idParam || $('#approved-prospek-id').val();
    
    if (!id) return Swal.fire('Error', 'ID tidak ditemukan', 'error');

    Swal.fire({
        title: 'Konfirmasi Review',
        text: "Data akan disetujui dan dipindahkan ke Tab PIC.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            
            Swal.fire({
                title: 'Memproses...',
                text: 'Memindahkan data ke Tab PIC',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            $.post('/prospek_tampung/approve-review-l2/' + id, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(res) {
                if (res.success) {
                    Swal.fire('Berhasil', res.message, 'success');
                    
                    // Tutup modal JIKA modal sedang terbuka
                    if ($('#modalApprovedProspek').hasClass('show')) {
                        $('#modalApprovedProspek').modal('hide');
                    }
                    
                    // Refresh Tab L2
                    window.loadProspekPartial(1, 'L2'); 
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            }).fail(function() {
                Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
            });
        }
    });
};

// TAB PIC (5 TAB)

// =========================================================================
// MODAL PIC — 5 TAB
// =========================================================================
var picLeafletMap    = null;
var picLeafletMarker = null;

window.switchPicTab = function(num) {
    for (var i = 1; i <= 5; i++) {
        var el  = document.getElementById('pic-tab-' + i);
        var btn = document.querySelector('.pic-tab-btn[data-pictab="' + i + '"]');
        if (el)  el.classList.toggle('d-none', i !== num);
        if (btn) btn.classList.toggle('active', i === num);
    }
    if (num === 3 && picLeafletMap) {
        setTimeout(function() { picLeafletMap.invalidateSize(); }, 150);
    }
    // Saat masuk tab 5 — tampilkan summary insight
    if (num === 5) buildPicInsightSummary();
};

// =========================================================================
// 1. FUNGSI UTAMA: MENGAMBIL DATA DAN MEMICU PEMBUKAAN MODAL 5 TAB PIC
// =========================================================================
window.openModalPic = function(id) {
    var container = $('.progress-container[data-id="'+id+'"]').first();
    var data = null;

    // Fungsi helper untuk mengecek apakah PIC/Officer kosong
    // Kita anggap kosong jika nilainya null, undefined, '', atau string '-'
    function isDataKosong(d) {
        var pic = d.pic;
        return (!pic || pic === '-' || pic === null || String(pic).trim() === '');
    }

    if (container.length && container.data('json')) {
        data = container.data('json');
        if (typeof data === 'string') data = JSON.parse(data);

         // LOGIKA PENENTUAN:
        if (isDataKosong(data)) {
            window.openModalSetPicAwal(id);
        } else {
            // Jika sudah ada, langsung buka modal 5 tab
            window._executeOpenModalPic(data, id);
        }

    } else {
        // Jika data tidak ada di DOM, ambil via AJAX dulu
        $.get('/prospek_tampung/get-data/' + id, function(res) {
            if (res.success) {
                var d = res.data;
                
                // LOGIKA PENENTUAN via AJAX:
                if (isDataKosong(d)) {
                    window.openModalSetPicAwal(id);
                } else {
                    window._executeOpenModalPic(d, id);
                }
            }
        });
    }
};

// =========================================================================
// 2. FUNGSI INTI PEMBANTU: POPULASI DATA & PENERAPAN WORKFLOW PENGUNCIAN FORM
// =========================================================================
window._executeOpenModalPic = function(data, id) {
    var $modal = $('#modalPicProspek').length ? $('#modalPicProspek') : $('#modalPic');
    if (!$modal.length) return;

    // A. SET ID PROSPEK
    $modal.find('#pic-prospek-id').val(data.id || id);

    // B. ISI DATA READONLY (TAB 1, 2, 3) - MENGGUNAKAN ID pic-ro-...
    // Tab 1: Identitas
    setPicReadonly('pic-ro-nama', data.nama);
    setPicReadonly('pic-ro-jabatan', data.jabatan);
    setPicReadonly('pic-ro-phone', data.phone);

    // Tab 2: Info Bisnis
    setPicReadonly('pic-ro-perusahaan', data.perusahaan || data.nama_perusahaan);
    setPicReadonly('pic-ro-zone', data.zone);
    setPicReadonly('pic-ro-kategori', data.kategori);
    setPicReadonly('pic-ro-model', data.model_bisnis);
    setPicReadonly('pic-ro-sosmed', data.media_sosial);
    setPicReadonly('pic-ro-marketplace', data.marketplace);
    setPicReadonly('pic-ro-multicabang', data.toko_multicabang);
    setPicReadonly('pic-ro-pengajuan', data.pengajuan);

    // Tab 3: Lokasi
    setPicReadonly('pic-ro-provinsi', data.provinsi);
    setPicReadonly('pic-ro-kota', data.kota);
    setPicReadonly('pic-ro-kecamatan', data.kecamatan);
    setPicReadonly('pic-ro-kelurahan', data.kelurahan);
    setPicReadonly('pic-ro-alamat', data.alamat);

    // C. HANDLING FOTO (TAB 1)
    var $fotoWrap = $('#pic-ro-foto-wrap');
    $fotoWrap.empty();
    var images = [];
    try {
        images = typeof data.image === 'string' ? JSON.parse(data.image) : (data.image || []);
    } catch(e) { images = []; }

    if (images && images.length > 0) {
        images.forEach(function(img) {
            $fotoWrap.append(`
                <div class="pic-foto-item">
                    <img src="/storage/${img}" class="img-fluid rounded" 
                         style="height:80px;width:80px;object-fit:cover;cursor:pointer;" 
                         onclick="window.open(this.src)">
                </div>`);
        });
        $('#pic-ro-foto-count').text(images.length + '/3 Foto');
    } else {
        $fotoWrap.append(`
            <div class="pic-foto-empty">
                <i class="bi bi-image" style="font-size:1.5rem;color:#3e444b;display:block;margin-bottom:4px;"></i>
                <span style="font-size:0.7rem;color:#6b7280;">Tidak ada foto</span>
            </div>`);
        $('#pic-ro-foto-count').text('0/3 Foto');
    }

    // D. HANDLING MAP & GEOTAG (TAB 3)
    if (data.latitude && data.longitude) {
        $('#pic-geo-info-wrap').removeClass('d-none');
        $('#pic-geo-pending-wrap').addClass('d-none');
        $('#pic-geo-info-row').html(`
            <div style="font-size:0.7rem;color:#c9d1d9;"><b>Lat:</b> ${data.latitude}</div>
            <div style="font-size:0.7rem;color:#c9d1d9;"><b>Lng:</b> ${data.longitude}</div>
        `);
        renderPicMap(data.latitude, data.longitude);
    } else {
        $('#pic-geo-info-wrap').addClass('d-none');
        $('#pic-geo-pending-wrap').removeClass('d-none');
        $('#pic-map-wrap').addClass('d-none');
    }

    // E. ISI DATA FORM (TAB 4 & 5)
    $modal.find('#pic-produk-laris').val(data.produk_paling_laris || '');
    $modal.find('#pic-range-harga').val(data.range_harga_jual || '');
    $modal.find('#pic-traffic-toko').val(data.traffic_toko || '');
    $modal.find('#pic-channel-dominan').val(data.channel_penjualan_dominan || '');
    $modal.find('#pic-output-market').val(data.output_market || '');
    $modal.find('#pic-platform-dominan').val(data.platform_dominan || '');
    $modal.find('#pic-brand-dominan').val(data.brand_dominan || '');
    $modal.find('#pic-promo-kompetitor').val(data.aktivitas_promo_kompetitor || '');
    
    $modal.find('#pic-input-pic').val(data.pic || '');
    $modal.find('#pic-input-officer').val(data.officer || '');
    
    // F. WORKFLOW PROTEKSI & LOGIKA MUTASI VS UPDATE INSIGHT
    var sourceData = data.source ? data.source.toUpperCase() : '';
    var btnSimpan = $modal.find('#pic-btn-simpan');
    var btnApproveFinal = $modal.find('#pic-btn-approve-final'); // Tangkap tombol baru

    // Reset attribut tombol ke default dulu
    btnSimpan.show().prop('disabled', false);
    btnApproveFinal.addClass('d-none').prop('disabled', false); // Sembunyikan default
    $modal.find('input, select, textarea').prop('disabled', false);

    if (sourceData === 'APM' || sourceData === 'SPG') {
        // Jika dari APM/SPG, form market insight web dikunci (karena mereka isi via aplikasi/API)
        $modal.find('#pic-tab-4 input, #pic-tab-4 select').prop('disabled', true);
    } else {
        $modal.find('#pic-tab-4 input, #pic-tab-4 select').prop('disabled', false);
    }

    // LOGIKA PENENTUAN STATUS:
    if (data.is_final_approved == 2) {
        // SKENARIO 1: SUDAH FINAL PUSAT (KUNCI TOTAL)
        $modal.find('input, select, textarea').prop('disabled', true);
        btnSimpan.hide();
        btnApproveFinal.addClass('d-none'); 
        $('#pic-final-action-area').html('<div class="alert alert-success"><i class="bi bi-shield-lock"></i> Terkunci (Final)</div>');
        
    } else if (data.status_request == 6 || data.is_mutated == 1) {
        // SKENARIO 2: SUDAH MUTASI
        $modal.find('#pic-input-pic, #pic-input-officer, #pic-input-zone, #pic-input-kategori').prop('disabled', true).css({'background-color': '#1a1d21', 'cursor': 'not-allowed'});
        
        btnSimpan.html('<i class="bi bi-graph-up me-1"></i> Update Market Insight')
                 .removeClass('btn-warning').addClass('btn-info')
                 .attr('onclick', 'window.doUpdateMarketInsight()');
                 
        // TAMPILKAN TOMBOL APPROVE FINAL (Karena belum 2)
        btnApproveFinal.removeClass('d-none');

        // Opsional: Jika is_final_approved == 1 (Berarti APM yg submit)
        if (data.is_final_approved == 1) {
            // Kasih notif ke Admin bahwa ini kiriman APM
            // Bikin tombolnya lebih mencolok
            btnApproveFinal.html('<i class="bi bi-shield-check me-1"></i> Finalisasi Data');
        }
                 
    } else {
        // SKENARIO 3: BELUM MUTASI
        $modal.find('#pic-input-pic, #pic-input-officer, #pic-input-zone, #pic-input-kategori').prop('disabled', false).css({'background-color': '', 'cursor': ''});
        
        btnSimpan.html('<i class="bi bi-check2-all me-1"></i> Simpan & Mutasi')
                 .removeClass('btn-info').addClass('btn-warning')
                 .attr('onclick', 'window.doSavePic()');
    }
    
    // =========================================================
    // SMART ROUTING: AUTO-DIRECT TAB BERDASARKAN STATUS
    // =========================================================
    if (data.is_final_approved == 2) {
        // Jika sudah di-approve final, buka dari awal (Tab 1)
        switchPicTab(1); 
    } else if (data.status_request == 6 || data.is_mutated == 1 || data.is_final_approved == 1) {
        // Jika sudah dimutasi tapi belum final, langsung tembak ke Tab 4
        switchPicTab(4); 
    } else {
        // Jika status baru/draft, buka dari awal (Tab 1)
        switchPicTab(1); 
    }

    // Tampilkan Modal
    new bootstrap.Modal($modal[0]).show();
};

function setPicReadonly(id, val) {
    var el = document.getElementById(id);
    if (!el) return;
    if (val && val.toString().trim() !== '' && val !== '-') {
        el.textContent = val;
        el.classList.remove('text-secondary');
    } else {
        el.textContent = '—';
        el.classList.add('text-secondary');
    }
}

function renderPicMap(lat, lng) {
    document.getElementById('pic-map-wrap').classList.remove('d-none');
    if (!picLeafletMap) {
        picLeafletMap = L.map('pic-map', {
            zoomControl: true, scrollWheelZoom: false,
            dragging: false, doubleClickZoom: false, touchZoom: false
        }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OSM', maxZoom: 19
        }).addTo(picLeafletMap);
        picLeafletMarker = L.marker([lat, lng]).addTo(picLeafletMap);
    } else {
        picLeafletMap.setView([lat, lng], 16);
        picLeafletMarker.setLatLng([lat, lng]);
        setTimeout(function() { picLeafletMap.invalidateSize(); }, 150);
    }
}

function resetPicInsight() {
    ['pic-produk-laris','pic-range-harga','pic-brand-dominan'].forEach(function(id) {
        var el = document.getElementById(id); if (el) el.value = '';
    });
    document.getElementById('pic-traffic-toko').value = '';
    document.querySelectorAll('#pic-tab-4 input[type=checkbox]').forEach(function(cb) { cb.checked = false; });
    document.querySelectorAll('#pic-tab-4 input[type=radio]').forEach(function(rb) { rb.checked = false; });
    document.getElementById('pic-insight-summary').classList.add('d-none');
}

function buildPicInsightSummary() {
    var produk   = document.getElementById('pic-produk-laris').value;
    var harga    = document.getElementById('pic-range-harga').value;
    var traffic  = document.getElementById('pic-traffic-toko').value;
    var brand    = document.getElementById('pic-brand-dominan').value;

    var om = [];
    document.querySelectorAll('input[name="output_market[]"]:checked').forEach(function(cb) { om.push(cb.value); });
    var ch = document.querySelector('input[name="channel_penjualan"]:checked');
    var plat = [];
    document.querySelectorAll('input[name="platform_dominan[]"]:checked').forEach(function(cb) { plat.push(cb.value); });
    var promo = [];
    document.querySelectorAll('input[name="aktivitas_promo[]"]:checked').forEach(function(cb) { promo.push(cb.value); });

    var hasData = produk || harga || traffic || brand || om.length || ch || plat.length || promo.length;
    var summary = document.getElementById('pic-insight-summary');
    var content = document.getElementById('pic-insight-summary-content');

    if (!hasData) { summary.classList.add('d-none'); return; }

    var html = '';
    if (produk)      html += '<b>Produk laris:</b> ' + produk + '<br>';
    if (harga)       html += '<b>Range harga:</b> ' + harga + '<br>';
    if (traffic)     html += '<b>Traffic:</b> ' + traffic + '<br>';
    if (om.length)   html += '<b>Output market:</b> ' + om.join(', ') + '<br>';
    if (ch)          html += '<b>Channel:</b> ' + ch.value + '<br>';
    if (plat.length) html += '<b>Platform:</b> ' + plat.join(', ') + '<br>';
    if (brand)       html += '<b>Brand kompetitor:</b> ' + brand + '<br>';
    if (promo.length) html += '<b>Promo kompetitor:</b> ' + promo.join(', ') + '<br>';

    content.innerHTML = html;
    summary.classList.remove('d-none');
}

// ── Simpan & Mutasi Final ──
window.doSavePic = function() {
    var id   = $('#pic-prospek-id').val();
    var pic  = $('#pic-input-pic').val().trim();
    var zone = $('#pic-input-zone').val();

    if (!pic) {
        Swal.fire({ title: 'Wajib Diisi', text: 'Nama PIC wajib diisi!', icon: 'warning', background: '#161b22', color: '#fff' });
        switchPicTab(5);
        return;
    }

    // Kumpulkan market insight
    var om = [];
    document.querySelectorAll('input[name="output_market[]"]:checked').forEach(function(cb) { om.push(cb.value); });
    var ch = document.querySelector('input[name="channel_penjualan"]:checked');
    var plat = [];
    document.querySelectorAll('input[name="platform_dominan[]"]:checked').forEach(function(cb) { plat.push(cb.value); });
    var promo = [];
    document.querySelectorAll('input[name="aktivitas_promo[]"]:checked').forEach(function(cb) { promo.push(cb.value); });

    Swal.fire({
        title: 'Simpan & Mutasi Final?',
        text: 'Data akan dimutasi ke sistem utama dengan PIC dan market insight.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#484f58',
        confirmButtonText: '<i class="bi bi-check2-all me-1"></i> Ya, Simpan!',
        cancelButtonText: 'Batal',
        background: '#161b22', color: '#fff'
    }).then(function(r) {
        if (!r.isConfirmed) return;

        var btn = document.getElementById('pic-btn-simpan');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
        btn.disabled = true;

        $.ajax({
            url: '/prospek_tampung/approve-l3/' + id,
            type: 'POST',
            data: {
                _token:              '{{ csrf_token() }}',
                pic:                 pic,
                kategori:            $('#pic-input-kategori').val(),
                zone:                zone,
                account_representative: $('#pic-input-ar').val(),
                pengajuan:           $('#pic-input-pengajuan').val(),
                // Market insight
                produk_laris:        $('#pic-produk-laris').val(),
                range_harga:         $('#pic-range-harga').val(),
                traffic_toko:        $('#pic-traffic-toko').val(),
                output_market:       om.join(','),
                channel_penjualan:   ch ? ch.value : '',
                platform_dominan:    plat.join(','),
                brand_dominan:       $('#pic-brand-dominan').val(),
                aktivitas_promo:     promo.join(','),
            },
            success: function(res) {
                if (res.success) {
                    Swal.fire({ title: 'Berhasil!', text: res.message, icon: 'success', timer: 2000, showConfirmButton: false, background: '#161b22', color: '#fff' });
                    bootstrap.Modal.getInstance(document.getElementById('modalPicProspek')).hide();
                    $('#row-prospek-' + id).fadeOut(300, function() { $(this).remove(); });
                } else {
                    Swal.fire({ title: 'Gagal', text: res.message, icon: 'warning', background: '#161b22', color: '#fff' });
                }
            },
            error: function() {
                Swal.fire({ title: 'Error', text: 'Terjadi kesalahan sistem.', icon: 'error', background: '#161b22', color: '#fff' });
            },
            complete: function() {
                btn.innerHTML = '<i class="bi bi-check2-all me-1"></i> Simpan & Mutasi Final';
                btn.disabled = false;
            }
        });
    });
};

window.doUpdateMarketInsight = function() {
    var id = $('#pic-prospek-id').val();
    
    // Kumpulkan market insight
    var om = [];
    document.querySelectorAll('input[name="output_market[]"]:checked').forEach(function(cb) { om.push(cb.value); });
    var ch = document.querySelector('input[name="channel_penjualan"]:checked');
    var plat = [];
    document.querySelectorAll('input[name="platform_dominan[]"]:checked').forEach(function(cb) { plat.push(cb.value); });
    var promo = [];
    document.querySelectorAll('input[name="aktivitas_promo[]"]:checked').forEach(function(cb) { promo.push(cb.value); });

    var btn = document.getElementById('pic-btn-simpan');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
    btn.disabled = true;

    $.ajax({
        url: '/prospek_tampung/update-insight/' + id, // Route baru
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            produk_laris: $('#pic-produk-laris').val(),
            range_harga: $('#pic-range-harga').val(),
            traffic_toko: $('#pic-traffic-toko').val(),
            output_market: om.join(','),
            channel_penjualan: ch ? ch.value : '',
            platform_dominan: plat.join(','),
            brand_dominan: $('#pic-brand-dominan').val(),
            aktivitas_promo: promo.join(','),
        },
        success: function(res) {
            if (res.success) {
                Swal.fire({ title: 'Berhasil!', text: 'Market Insight diperbarui.', icon: 'success', background: '#161b22', color: '#fff' });
                $('#modalPicProspek').modal('hide');
                // Jika ingin barisnya hilang dari L3 setelah insight diisi:
                $('#row-prospek-' + id).fadeOut(); 
            } else {
                Swal.fire('Gagal', res.message, 'warning');
            }
        },
        complete: function() {
            btn.innerHTML = '<i class="bi bi-graph-up me-1"></i> Update Market Insight';
            btn.disabled = false;
        }
    });
};

window.doApproveFinal = function() {
    var id = $('#pic-prospek-id').val();
    
    Swal.fire({
        title: 'Approve Final Data?',
        text: "Setelah di-Approve Final, seluruh data akan dikunci permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#484f58',
        confirmButtonText: '<i class="bi bi-shield-check me-1"></i> Ya, Kunci Data!',
        cancelButtonText: 'Batal',
        background: '#161b22', color: '#fff'
    }).then(function(result) {
        if (result.isConfirmed) {
            var btn = document.getElementById('pic-btn-approve-final');
            var oriHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
            btn.disabled = true;

            // Catatan: Pastikan _token CSRF terkirim dengan benar.
            // Jika script ini ada di file terpisah (.js), ambil token dari meta tag.
            // Jika menyatu di file .blade.php, bisa pakai '{{ csrf_token() }}'
            var csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';

            $.ajax({
                url: '/prospek_tampung/approve-final/' + id,
                type: 'POST',
                data: {
                    _token: csrfToken
                },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({ title: 'Berhasil!', text: res.message, icon: 'success', timer: 2000, showConfirmButton: false, background: '#161b22', color: '#fff' });
                        $('#modalPicProspek').modal('hide');
                        // Refresh halaman agar badge di list/tabel terupdate
                        setTimeout(function(){ location.reload(); }, 1500); 
                    } else {
                        Swal.fire({ title: 'Gagal', text: res.message, icon: 'warning', background: '#161b22', color: '#fff' });
                    }
                },
                error: function(err) {
                    var msg = err.responseJSON ? err.responseJSON.message : 'Terjadi kesalahan sistem.';
                    Swal.fire({ title: 'Error', text: msg, icon: 'error', background: '#161b22', color: '#fff' });
                },
                complete: function() {
                    btn.innerHTML = oriHtml;
                    btn.disabled = false;
                }
            });
        }
    });
};

// Reset saat modal ditutup
document.getElementById('modalPicProspek')?.addEventListener('hidden.bs.modal', function() {
    if (picLeafletMap) { picLeafletMap.remove(); picLeafletMap = null; picLeafletMarker = null; }
    switchPicTab(1);
    resetPicInsight();
    $('#pic-input-pic, #pic-input-officer, #pic-input-ar').val('');
    $('#pic-input-pengajuan, #pic-input-zone, #pic-input-kategori').val('');
});

// Reset map saat modal ditutup
document.getElementById('modalApprovedProspek')?.addEventListener('hidden.bs.modal', function() {
    if (apLeafletMap) {
        apLeafletMap.remove();
        apLeafletMap    = null;
        apLeafletMarker = null;
    }
    switchApprovedTab(1);
});

$(document).ready(function() {
    window.refreshAllRowProgress();

    $(document).off('click','.btn-ajukan-l2').on('click','.btn-ajukan-l2',function(e){
        e.preventDefault(); e.stopPropagation();
        window.mutasiProspek($(this).data('id'));
    });
    $(document).off('click','.btn-review-spv').on('click','.btn-review-spv',function(e){
        e.preventDefault(); e.stopPropagation();
        window.openModalSpv($(this).data('id'));
    });
    
    $(document).on('click', '.l1-dynamic-row', function(e) {
        // Jangan trigger jika klik tombol
        if ($(e.target).closest('.btn').length) {
            e.stopPropagation();
            return;
        }
        
        var rowId = $(this).attr('id').replace('row-prospek-', '');
        console.log('Row diklik! ID:', rowId);
        
        if (rowId && typeof window.openModalEdit === 'function') {
            window.openModalEdit(rowId);
        } else {
            console.warn('openModalEdit function tidak ditemukan!');
        }
    });
    
    // =========================================================================
    // LOGIKA UI L1: CARD TERPUSAT BERTINGKAT (ZONA -> KATEGORI)
    // =========================================================================
    
    // Ambil mapping Zona -> Kategori dari PHP
    var jsZoneCatMap = {!! json_encode($zoneCatMap ?? []) !!};

    // State pagination L1 — disimpan global agar bisa diakses tombol gotoL1Page
    window.l1PaginationState = {
        currentPage: 1,
        perPage: 10,
        totalPages: 1
    };
    
    window.processFilter = function(resetPage) {
        // resetPage = true setiap kali filter/search berubah → balik ke halaman 1
        if (resetPage !== false) {
            window.l1PaginationState.currentPage = 1;
        }
    
        var zoneVal = $('#uiSelectZone').val();
        var katVal  = $('#uiSelectKat').val();
        var searchVal = $('#uiSearchInput').val().toLowerCase().trim();
    
        // RESET ALL
        $('.l1-dynamic-row').hide();
        $('#uiTableContainer, #uiEmptyWaitZone, #uiEmptyWaitKat, #uiNoResult, #uiL1PaginationWrap')
            .addClass('d-none');
        $('#uiInfoCount').addClass('d-none').text('0 Data');
    
        // STATE 1: Zona belum dipilih
        if (zoneVal === '') {
            $('#uiSubHeader').addClass('d-none');
            $('#uiEmptyWaitZone').removeClass('d-none');
            $('#uiSelectKat, #uiSearchInput').prop('disabled', true).val('');
            return;
        }
    
        // STATE 2: Zona dipilih
        $('#uiSubHeader').removeClass('d-none');
        $('#uiSelectKat').prop('disabled', false);
        $('#uiSearchInput').prop('disabled', false);
    
        if (katVal === '' && searchVal === '') {
            $('#uiEmptyWaitKat').removeClass('d-none');
            return;
        }
    
        // STATE 3: Kumpulkan baris yang MATCH filter (belum tentu ditampilkan semua — tergantung halaman)
        $('#uiTableContainer').removeClass('d-none');
        var matchedRows = [];
    
        $('.l1-dynamic-row').each(function() {
            var rowZone = $(this).attr('data-zone');
            var rowKat  = $(this).attr('data-kat');
            var rowSrch = $(this).attr('data-search');
    
            var matchZone = (rowZone === zoneVal);
            var matchKat  = (katVal === 'ALL' || katVal === '' || rowKat === katVal);
            var matchSrch = (searchVal === '' || rowSrch.indexOf(searchVal) > -1);
    
            if (matchZone && matchKat && matchSrch) {
                matchedRows.push(this);
            }
        });
    
        var totalMatched = matchedRows.length;
    
        if (totalMatched === 0) {
            $('#uiNoResult').removeClass('d-none');
            $('.ci-tbl').addClass('d-none');
            return;
        }
    
        // Hitung total halaman berdasarkan hasil filter
        var perPage = window.l1PaginationState.perPage;
        var totalPages = Math.ceil(totalMatched / perPage) || 1;
        window.l1PaginationState.totalPages = totalPages;
    
        if (window.l1PaginationState.currentPage > totalPages) window.l1PaginationState.currentPage = totalPages;
        if (window.l1PaginationState.currentPage < 1) window.l1PaginationState.currentPage = 1;
    
        var currentPage = window.l1PaginationState.currentPage;
        var startIdx = (currentPage - 1) * perPage;
        var endIdx = startIdx + perPage;
    
        // Tampilkan HANYA baris pada rentang halaman aktif
        matchedRows.forEach(function(rowEl, idx) {
            var rowId = $(rowEl).attr('id').replace('row-prospek-', '');
            if (idx >= startIdx && idx < endIdx) {
                $(rowEl).show()
                    .attr('onclick', 'window.openModalEdit(' + rowId + ')')
                    .css('cursor', 'pointer');
            }
        });
    
        $('.ci-tbl').removeClass('d-none');
        $('#uiInfoCount').text(totalMatched + ' Data').removeClass('d-none');
    
        // Render kontrol pagination jika lebih dari 1 halaman
        if (totalPages > 1) {
            $('#uiL1PaginationWrap').removeClass('d-none');
            $('#uiL1PgInfo').text(currentPage + '/' + totalPages);
            $('#uiL1PgFirst, #uiL1PgPrev').prop('disabled', currentPage <= 1);
            $('#uiL1PgNext, #uiL1PgLast').prop('disabled', currentPage >= totalPages);
        }
    };
    
    // Pindah halaman L1 — TANPA AJAX, TANPA reload apapun, filter tetap utuh
    window.gotoL1Page = function(page) {
        var state = window.l1PaginationState;
        if (page < 1) page = 1;
        if (page > state.totalPages) page = state.totalPages;
        state.currentPage = page;
        window.processFilter(false); // false = jangan reset ke halaman 1
    };

    // TRIGGER: Saat Zona Diubah
    $('#uiSelectZone').on('change', function() {
        var zone = $(this).val();
        
        // Populate Dropdown Kategori
        var $kat = $('#uiSelectKat');
        if (zone !== '' && jsZoneCatMap[zone]) {
            var opts = '<option value="">[ PILIH KATEGORI ]</option>';
            opts += '<option value="ALL">SEMUA KATEGORI DI ZONA INI</option>';
            jsZoneCatMap[zone].forEach(function(c) {
                var lbl = (c === 'NONE') ? 'NONE' : c;
                var style = (c === 'NONE') ? 'color:#f59e0b; font-weight:bold;' : '';
                opts += '<option value="'+c+'" style="'+style+'">'+lbl+'</option>';
            });
            $kat.html(opts);
        } else {
            $kat.html('<option value="">[ PILIH KATEGORI ]</option>');
        }
        
        // Kosongkan search & Proses UI
        $('#uiSearchInput').val('');
        window.processFilter();
    });

    // TRIGGER: Saat Kategori Diubah atau Search Diketik
    $('#uiSelectKat, #uiSearchInput').on('change keyup', function() {
        window.processFilter();
    });
    
    $('#btn-save-tambah').on('click', function() {
        let btn = $(this);
        btn.prop('disabled', true).html('Menyimpan...');
        
        $.ajax({
            url: "{{ route('master.prospek_tampung.manual_entry') }}",
            type: 'POST',
            data: $('#form-tambah-prospek').serialize(),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(res) {
                if(res.success) {
                    Swal.fire('Berhasil', res.message, 'success');
                    $('#modalTambahProspek').modal('hide');
                    $('#form-tambah-prospek')[0].reset();
                    window.loadProspekPartial(1, 'L1');
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            complete: function() { btn.prop('disabled', false).html('Simpan Data Baru'); }
        });
    });
});
</script>