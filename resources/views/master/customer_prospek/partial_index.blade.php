<style>
/* ── Wrap ── */
.ci-wrap { padding: 6px 4px; }

/* ── Toolbar ── */
.ci-toolbar {
    display: flex; justify-content: space-between;
    align-items: center; flex-wrap: wrap; gap: 6px; margin-bottom: 8px;
}
.ci-toolbar-left  { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
.ci-toolbar-right { display: flex; gap: 5px; flex-wrap: wrap; align-items: center; }

/* Filter pills */
.ci-filter {
    padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: 700;
    cursor: pointer; border: 1px solid rgba(255,255,255,0.12);
    background: transparent; color: #6b7280; transition: all .12s;
}
.ci-filter:hover { color: #e5e7eb; background: rgba(255,255,255,0.06); }
.ci-filter.cf-active { background: #0c82f9; border-color: #0c82f9; color: #fff; }
.ci-filter.cf-existing.cf-active { background: #059669; border-color: #059669; }
.ci-filter.cf-prospek.cf-active  { background: #d97706; border-color: #d97706; }

/* Buttons */
.btn-ci {
    display: inline-flex; align-items: center; gap: 4px;
    border-radius: 5px; padding: 4px 12px; font-size: 12px;
    font-weight: 700; cursor: pointer; border: none; transition: all .12s;
    white-space: nowrap;
}
.btn-ci-add     { background: #059669; color: #fff; }
.btn-ci-add:hover { background: #047857; }
.btn-ci-import  { background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); color: #9ca3af; }
.btn-ci-import:hover { background: rgba(255,255,255,0.12); color: #e5e7eb; }
.btn-ci-pdf     { background: rgba(220,38,38,0.15); border: 1px solid rgba(220,38,38,0.3); color: #f87171; }
.btn-ci-pdf:hover { background: rgba(220,38,38,0.25); }

/* Search */
.ci-search {
    background: #2a2f35; border: 1px solid rgba(255,255,255,0.1);
    border-radius: 5px; color: #e5e7eb; padding: 4px 10px; font-size: 12px;
    min-width: 160px;
}
.ci-search::placeholder { color: #4b5563; }

/* ── Table ── */
.ci-tbl-wrap { overflow-x: auto; }
.ci-tbl { width: 100%; border-collapse: collapse; font-size: 12px; min-width: 520px; }
.ci-tbl thead th {
    background: #1a1e24; color: #6b7280; font-weight: 600; font-size: 10px;
    text-transform: uppercase; letter-spacing: .5px;
    padding: 8px 10px; border-bottom: 1px solid rgba(255,255,255,0.08); white-space: nowrap;
}
.ci-tbl tbody tr.ci-data-row {
    border-bottom: 1px solid rgba(255,255,255,0.05); transition: background .1s;
}
.ci-tbl tbody tr.ci-data-row:hover { background: rgba(255,255,255,0.03); }
.ci-tbl td { padding: 8px 10px; vertical-align: middle; color: #d1d5db; }
.td-name  { font-weight: 700; color: #f3f4f6; font-size: 12px; }
.td-sub   { font-size: 11px; color: #6b7280; margin-top: 1px; }
.td-small { font-size: 10px; color: #9ca3af; }

/* ── Badges ── */
.type-badge {
    display: inline-block; padding: 1px 7px; border-radius: 20px;
    font-size: 10px; font-weight: 700;
}
.tb-existing { background: #022c22; color: #34d399; border: 1px solid #065f46; }
.tb-prospek  { background: #451a03; color: #fbbf24; border: 1px solid #92400e; }

/* ── Edit button ── */
.btn-edit-row {
    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
    color: #9ca3af; border-radius: 5px; padding: 3px 8px; cursor: pointer;
    font-size: 11px; display: inline-flex; align-items: center; gap: 3px; transition: all .12s;
}
.btn-edit-row:hover { background: #1d4ed8; border-color: #1d4ed8; color: #fff; }
.btn-edit-row.active { background: #374151; border-color: #4b5563; color: #e5e7eb; }

/* ── Inline Edit Panel ── */
.ci-edit-row td { padding: 0 !important; }
.ci-edit-panel {
    background: #16191f; border-bottom: 1px solid rgba(255,255,255,0.1);
    padding: 12px 10px;
}
.ep-title {
    font-size: 10px; color: #6b7280; text-transform: uppercase;
    letter-spacing: .5px; margin-bottom: 10px; font-weight: 700;
}
.ep-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
.ep-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
.ep-field { display: flex; flex-direction: column; gap: 3px; }
.ep-field.span2 { grid-column: span 2; }
.ep-field label {
    font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: .4px;
}
.ep-field input, .ep-field select, .ep-field textarea {
    background: #2a2f35; border: 1px solid rgba(255,255,255,0.1);
    border-radius: 5px; color: #e5e7eb; padding: 5px 8px; font-size: 12px;
}
.ep-field input:focus, .ep-field select:focus { outline: none; border-color: #0c82f9; }
.ep-field input::placeholder { color: #4b5563; }
.ep-divider {
    font-size: 10px; color: #4b5563; text-transform: uppercase; letter-spacing: .5px;
    margin: 10px 0 8px; display: flex; align-items: center; gap: 8px;
}
.ep-divider::before, .ep-divider::after {
    content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.07);
}
.ep-footer { display: flex; justify-content: flex-end; gap: 6px; margin-top: 10px; }
.btn-ep-cancel {
    background: transparent; border: 1px solid rgba(255,255,255,0.12);
    border-radius: 5px; color: #9ca3af; padding: 4px 12px; font-size: 12px; cursor: pointer;
}
.btn-ep-cancel:hover { background: rgba(255,255,255,0.07); color: #e5e7eb; }
.btn-ep-save {
    background: #0c82f9; border: none; border-radius: 5px; color: #fff;
    padding: 4px 16px; font-size: 12px; font-weight: 700; cursor: pointer;
}
.btn-ep-save:hover { background: #0970d8; }

/* ── Pagination ── */
.ci-pgn-wrap {
    display: flex; justify-content: center; align-items: center;
    gap: 4px; margin-top: 12px; padding: 4px 0;
}
.ci-pgn-wrap .pgn-info {
    font-size: 11px; color: #4b5563; margin: 0 8px;
}
.pgn-btn {
    background: #2a2f35; border: 1px solid rgba(255,255,255,0.1); color: #9ca3af;
    border-radius: 5px; padding: 4px 10px; font-size: 12px; cursor: pointer;
    display: inline-flex; align-items: center; min-width: 32px; justify-content: center;
    transition: all .12s;
}
.pgn-btn:hover:not([disabled]) { background: #374151; color: #e5e7eb; }
.pgn-btn[disabled] { opacity: .3; cursor: not-allowed; }
.pgn-indicator {
    background: #1a1e24; border: 1px solid rgba(255,255,255,0.1);
    color: #e5e7eb; border-radius: 5px; padding: 4px 14px;
    font-size: 12px; font-weight: 700; min-width: 70px; text-align: center;
}

/* ── Empty ── */
.ci-empty { text-align: center; padding: 40px 20px; }
.ci-empty i { font-size: 32px; display: block; margin-bottom: 8px; color: #374151; }
.ci-empty p { font-size: 12px; color: #4b5563; margin: 0; }

/* ── Mobile responsive ── */
@media (max-width: 576px) {
    .ep-grid { grid-template-columns: 1fr 1fr; }
    .ci-toolbar { flex-direction: column; align-items: flex-start; }
}
</style>

<div class="ci-wrap">

    {{-- ── Toolbar ── --}}
    <div class="ci-toolbar">
        <div class="ci-toolbar-left">
            <button class="btn-ci btn-ci-add" id="loadCreateForm">
                <i class="bi bi-plus-lg"></i> Tambah
            </button>
            <button class="btn-ci btn-ci-import"
                data-bs-toggle="modal" data-bs-target="#importExportModal">
                <i class="bi bi-file-earmark-excel"></i> Import/Export
            </button>
            <input type="text" class="ci-search" id="ciSearch"
                placeholder="&#128269; Cari nama, kota...">
        </div>
        <div class="ci-toolbar-right">
            <button class="ci-filter cf-active" data-type="all">All</button>
            <button class="ci-filter cf-existing {{ $data_type=='existing'?'cf-active':'' }}"
                data-type="existing">Existing</button>
            <button class="ci-filter cf-prospek {{ $data_type=='prospek'?'cf-active':'' }}"
                data-type="prospek">Prospek</button>
            <a href="{{ route('master.customer_prospek.export_pdf') }}"
               class="btn-ci btn-ci-pdf" target="_blank">
                <i class="bi bi-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="ci-tbl-wrap">
        <table class="ci-tbl">
            <thead>
                <tr>
                    <th style="width:30%">Customer</th>
                    <th style="width:8%">Tipe</th>
                    <th style="width:14%">Kota / Provinsi</th>
                    <th style="width:14%">Kategori</th>
                    <th style="width:10%">PIC</th>
                    <th style="width:14%">Pengajuan</th>
                    <th style="width:5%"></th>
                </tr>
            </thead>
            <tbody id="ciTbody">
                @forelse($customers as $c)
                {{-- Data row --}}
                <tr class="ci-data-row" id="row-{{ $c->id }}-{{ $c->type }}">
                    <td>
                        <div class="td-name">{{ $c->name }}</div>
                        @if(!empty($c->owner_name))
                            <div class="td-sub">{{ $c->owner_name }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="type-badge {{ $c->type=='existing'?'tb-existing':'tb-prospek' }}">
                            {{ $c->type == 'existing' ? 'Existing' : 'Prospek' }}
                        </span>
                    </td>
                    <td>
                        <div class="td-small">{{ $c->text_kota ?? '-' }}</div>
                        <div class="td-sub">{{ $c->text_provinsi ?? '' }}</div>
                    </td>
                    <td class="td-small">{{ $c->category_name ?? '-' }}</td>
                    <td class="td-small">{{ $c->pic ?? '-' }}</td>
                    <td class="td-small">
                        @if($c->type == 'prospek')
                            {{ $c->pengajuan_label ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <button class="btn-edit-row"
                        onclick="toggleCiEdit('{{ $c->id }}','{{ $c->type }}','{{ $c->customer_id ?? $c->id }}')"
                        id="btn-edit-{{ $c->id }}-{{ $c->type }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </td>
                </tr>

                {{-- Inline Edit row --}}
                <tr class="ci-edit-row" id="edit-row-{{ $c->id }}-{{ $c->type }}" style="display:none;">
                    <td colspan="8">
                        <div class="ci-edit-panel">
                            @if($c->type === 'existing')
                            {{-- ── EXISTING EDIT ── --}}
                            <div class="ep-title">Edit Data Existing — {{ $c->name }}</div>
                            <div class="ep-grid">
                                <div class="ep-field">
                                    <label>Nama</label>
                                    <input type="text" id="ep-{{ $c->id }}-ex-name"
                                        value="{{ $c->name }}">
                                </div>
                                <div class="ep-field">
                                    <label>Telepon</label>
                                    <input type="text" id="ep-{{ $c->id }}-ex-phone"
                                        value="{{ $c->phone ?? '' }}">
                                </div>
                                <div class="ep-field">
                                    <label>Email</label>
                                    <input type="text" id="ep-{{ $c->id }}-ex-email"
                                        value="{{ $c->email ?? '' }}">
                                </div>
                                <div class="ep-field">
                                    <label>Kota</label>
                                    <input type="text" id="ep-{{ $c->id }}-ex-kota"
                                        value="{{ $c->text_kota ?? '' }}">
                                </div>
                                <div class="ep-field">
                                    <label>Provinsi</label>
                                    <input type="text" id="ep-{{ $c->id }}-ex-provinsi"
                                        value="{{ $c->text_provinsi ?? '' }}">
                                </div>
                                <div class="ep-field">
                                    <label>Website</label>
                                    <input type="text" id="ep-{{ $c->id }}-ex-website"
                                        value="{{ $c->website ?? '' }}">
                                </div>
                            </div>
                            <div class="ep-divider"></div>
                            <div class="ep-grid">
                                <div class="ep-field">
                                    <label>PIC</label>
                                    <input type="text" id="ep-{{ $c->id }}-ex-pic"
                                        value="{{ $c->pic ?? '' }}"
                                        placeholder="Nama PIC">
                                </div>
                                <div class="ep-field">
                                    <label>Officer</label>
                                    <input type="text" id="ep-{{ $c->id }}-ex-officer"
                                        value="{{ $c->officer ?? '' }}"
                                        placeholder="Nama Officer">
                                </div>
                                <div class="ep-field">
                                    <label>Kategori</label>
                                    <select id="ep-{{ $c->id }}-ex-kat">
                                        <option value="">-- Pilih --</option>
                                        @foreach($kategori as $k)
                                        <option value="{{ $k->id }}"
                                            {{ ($c->category_id ?? '') == $k->id ? 'selected' : '' }}>
                                            {{ $k->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="ep-footer">
                                <button type="button" class="btn btn-secondary" 
                                    onclick="window.toggleCiEdit('{{ $c->id }}', '{{ $c->type }}', '{{ $c->customer_id ?? $c->id }}')">
                                    Batal
                                </button>
                                <button class="btn-ep-save"
                                    onclick="saveCiEdit('{{ $c->id }}','existing','{{ $c->customer_id ?? $c->id }}')">
                                    <i class="bi bi-check-lg"></i> Simpan
                                </button>
                            </div>

                            @else
                            {{-- ── PROSPEK EDIT (tahap 1) ── --}}
                            <div class="ep-title">Edit Data Prospek — {{ $c->name }}</div>
                            <div class="ep-grid">
                                <div class="ep-field">
                                    <label>Nama Store</label>
                                    <input type="text" id="ep-{{ $c->id }}-pr-name"
                                        value="{{ $c->name }}">
                                </div>
                                <div class="ep-field">
                                    <label>Owner / CP</label>
                                    <input type="text" id="ep-{{ $c->id }}-pr-owner"
                                        value="{{ $c->owner_name ?? $c->contact_person ?? '' }}">
                                </div>
                                <div class="ep-field">
                                    <label>No. HP</label>
                                    <input type="text" id="ep-{{ $c->id }}-pr-phone"
                                        value="{{ $c->phone ?? '' }}"
                                        placeholder="Nomor HP">
                                </div>
                            </div>
                            <div class="ep-divider">Update dari master_customers_prospek</div>
                            <div class="ep-grid-2">
                                <div class="ep-field">
                                    <label>PIC</label>
                                    <input type="text" id="ep-{{ $c->id }}-pr-pic"
                                        value="{{ $c->pic ?? '' }}"
                                        placeholder="Nama PIC">
                                </div>
                                <div class="ep-field" style="opacity:.5; pointer-events:none;">
                                    <label>Officer <span style="font-size:9px;color:#f87171">(via status_request)</span></label>
                                    <input type="text" value="{{ $c->officer ?? '-' }}" disabled>
                                </div>
                            </div>
                            <div class="ep-footer">
                                <button type="button" class="btn btn-secondary" 
                                    onclick="window.toggleCiEdit('{{ $c->id }}', '{{ $c->type }}', '{{ $c->customer_id ?? $c->id }}')">
                                    Batal
                                </button>
                                <button class="btn-ep-save"
                                    onclick="saveCiEdit('{{ $c->id }}','prospek','{{ $c->customer_id ?? $c->id }}')">
                                    <i class="bi bi-check-lg"></i> Simpan
                                </button>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8">
                        <div class="ci-empty">
                            <i class="bi bi-inbox"></i>
                            <p>Tidak ada data ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Pagination tengah bawah (Manual Collection) ── --}}
    @if(isset($total_pages) && $total_pages > 0)
    <div class="ci-pgn-wrap">
        {{-- Tombol First --}}
        <button class="pgn-btn ci-pgn" data-page="1"
            {{ $current_page <= 1 ? 'disabled' : '' }}
            title="Halaman pertama">&laquo;</button>

        {{-- Tombol Previous --}}
        <button class="pgn-btn ci-pgn" data-page="{{ $current_page - 1 }}"
            {{ $current_page <= 1 ? 'disabled' : '' }}
            title="Sebelumnya">&lsaquo;</button>

        {{-- Indicator --}}
        <span class="pgn-indicator">
            {{ $current_page }} / {{ $total_pages }}
        </span>

        {{-- Tombol Next --}}
        <button class="pgn-btn ci-pgn" data-page="{{ $current_page + 1 }}"
            {{ $current_page >= $total_pages ? 'disabled' : '' }}
            title="Berikutnya">&rsaquo;</button>

        {{-- Tombol Last --}}
        <button class="pgn-btn ci-pgn" data-page="{{ $total_pages }}"
            {{ $current_page >= $total_pages ? 'disabled' : '' }}
            title="Halaman terakhir">&raquo;</button>

        {{-- Info Total Data --}}
        <span class="pgn-info">{{ $total_items ?? 0 }} data</span>
    </div>
    @endif

</div>

{{-- ── Modal Import/Export (tidak berubah) ── --}}
<div class="modal fade" id="importExportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#1e2227; border:1px solid rgba(255,255,255,0.1); color:#e5e7eb;">
            <div class="modal-header" style="border-color:rgba(255,255,255,0.1);">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-file-earmark-excel"></i> Batch Data Operations
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 style="color:#60a5fa;font-size:13px;" class="mb-2">1. Export Template</h6>
                <div class="alert alert-info" style="font-size:12px;">
                    Unduh file template Excel dan isi dengan data Store Prospek baru.
                </div>
                <a href="{{ route('master.customer_prospek.export_template') }}"
                   class="btn btn-outline-success w-100 mb-3" target="_blank" style="font-size:12px;">
                    <i class="bi bi-download"></i> Unduh Template Import
                </a>
                <h6 style="color:#60a5fa;font-size:13px;" class="mb-2">2. Import Data</h6>
                <form action="{{ route('master.customer_prospek.import_batch') }}"
                    method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <input class="form-control form-control-sm mb-2"
                        style="background:#2a2f35;border-color:rgba(255,255,255,0.1);color:#e5e7eb;"
                        type="file" name="file" required accept=".xlsx,.xls,.csv">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-upload"></i> Upload & Import
                    </button>
                </form>
            </div>
            <div class="modal-footer" style="border-color:rgba(255,255,255,0.1);">
                <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
// ── Filter pills ──
$(document).off('click','.ci-filter').on('click','.ci-filter', function(){
    let type = $(this).data('type');
    window.loadCustomerList(type, 1); // Tambahkan window.
});

// ── Search (debounce 400ms) ──
// let ciSearchTimer;
$(document).off('input','#ciSearch').on('input','#ciSearch', function(){
    // Gunakan window.ciSearchTimer
    clearTimeout(window.ciSearchTimer); 
    let q = $(this).val();
    window.ciSearchTimer = setTimeout(()=> window.loadCustomerList(window.currentCustomerType, 1, q), 400);
});

// Pagination
$(document).off('click','.ci-pgn').on('click','.ci-pgn', function(){
    if($(this).prop('disabled')) return;
    let page = $(this).data('page');
    window.loadCustomerList(window.currentCustomerType, page); // Tambahkan window.
});

// ── Tambah button ──
$(document).off('click','#loadCreateForm').on('click','#loadCreateForm', function(){
    loadCustomerCreateForm();
});

// ── Toggle inline edit ──
window.toggleCiEdit = function(id, type, parentId){
    // Gunakan selektor atribut [id="..."] agar karakter titik (.) pada id tidak dianggap sebagai class CSS
    let editRow = $('[id="edit-row-' + id + '-' + type + '"]');
    let btn     = $('[id="btn-edit-' + id + '-' + type + '"]');

    if (editRow.length === 0) {
        console.error("Elemen edit row tidak ditemukan untuk ID: " + id);
        return;
    }

    $('.ci-edit-row').not(editRow).hide();
    $('.btn-edit-row').not(btn).removeClass('active');

    if(editRow.is(':visible')){
        editRow.hide(); btn.removeClass('active');
    } else {
        editRow.show(); btn.addClass('active');
        // Pastikan offset() tersedia sebelum mengambil .top
        let targetOffset = editRow.offset();
        if (targetOffset) {
            $('html,body').animate({ scrollTop: targetOffset.top - 80 }, 200);
        }
    }
};

window.closeCiEdit = function(id, type){
    $('#edit-row-' + id + '-' + type).hide();
    $('#btn-edit-' + id + '-' + type).removeClass('active');
};

// ── Save inline edit ──
window.saveCiEdit = function(id, type, parentId){
    id = String(id).trim();

    // ← Helper ini aman untuk id varchar seperti "158.3"
    // karena document.getElementById tidak interpret titik sebagai CSS class
    function val(elId){
        let el = document.getElementById(elId);
        return el ? el.value : '';
    }

    let payload = { _token: $('meta[name="csrf-token"]').attr('content') };
    let url;

    if(type === 'existing'){
        url = "{{ route('master.customer_prospek.update_existing', ['id' => '__ID__']) }}"
                .replace('__ID__', id);
        payload.parent_id           = parentId;
        payload.name                = val('ep-' + id + '-ex-name');
        payload.phone               = val('ep-' + id + '-ex-phone');
        payload.email               = val('ep-' + id + '-ex-email');
        payload.text_kota           = val('ep-' + id + '-ex-kota');
        payload.text_provinsi       = val('ep-' + id + '-ex-provinsi');
        payload.website             = val('ep-' + id + '-ex-website');
        payload.pic                 = val('ep-' + id + '-ex-pic');
        payload.officer             = val('ep-' + id + '-ex-officer');
        payload.category_id         = val('ep-' + id + '-ex-kat');
    } else {
        url = "{{ route('master.customer_prospek.update_prospek', ['id' => '__ID__']) }}"
                .replace('__ID__', id);
        payload.parent_id  = parentId;
        payload.name       = val('ep-' + id + '-pr-name');
        payload.owner_name = val('ep-' + id + '-pr-owner');
        payload.phone      = val('ep-' + id + '-pr-phone');
        payload.pic        = val('ep-' + id + '-pr-pic');
    }

    Swal.fire({title:'Menyimpan...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});
    $.ajax({
        url, type:'POST', data: payload,
        success(){
            Swal.fire('Berhasil','Data berhasil diperbarui.','success')
                .then(()=> window.loadCustomerList(window.currentCustomerType, window.currentCustomerPage));
        },
        error(xhr){
            Swal.close();
            if(xhr.status === 422){
                let msg = Object.values(xhr.responseJSON.errors).map(v=>v.join('<br>')).join('<br>');
                Swal.fire('Validasi Gagal', msg, 'error');
            } else {
                Swal.fire('Gagal','Terjadi kesalahan pada server.','error');
            }
        }
    });
};
</script>