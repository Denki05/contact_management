@extends('layouts.app')

@section('content')
<style>
    /* Styling Anda yang ada */
    .table-customer {
        border-collapse: separate;
        border-spacing: 0 6px;
        width: 100%;
    }
    .table-customer thead th {
        background: #f8f9fa;
        font-weight: 600;
        padding: 10px;
        border-bottom: 2px solid #dee2e6;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-customer tbody tr.main-row {
        cursor: pointer;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: background-color 0.2s;
    }
    .table-customer tbody tr.main-row:hover {
        background-color: #f5f5f5;
    }
    .table-customer tbody td {
        vertical-align: top;
        padding: 8px 10px;
        font-size: 0.92rem;
    }
    .customer-name {
        font-weight: 600;
        font-size: 1rem;
        display: flex; /* Tambahkan flex untuk badge */
        /*align-items: center;*/
    }
    .customer-address {
        color: #555;
        font-size: 0.86rem;
        margin-top: 2px;
    }
    .customer-city {
        color: #777;
        font-size: 0.84rem;
    }
    td, th {
        white-space: normal;
    }

    /* Styling tambahan untuk DataTables buttons agar sejajar */
    .dataTables_wrapper .dataTables_length {
        display: inline-flex;
        align-items: center;
        margin-right: 1rem;
    }
    .dataTables_wrapper .dt-buttons {
        float: none !important;
        display: inline-flex;
        margin-right: 1rem;
    }
    .dataTables_wrapper .dt-buttons .btn-sm {
        line-height: 1.5;
        padding: 0.25rem 0.5rem;
    }
</style>

<div class="container-fluid px-3">
    
    @if(session('failed_imports'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Peringatan Import!</strong> Import selesai, namun ditemukan {{ count(session('failed_imports')) }} data yang gagal.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <hr>
            <p class="mb-0">Detail Kegagalan:</p>
            <ul class="list-unstyled">
                @foreach(session('failed_imports') as $failure)
                    <li>
                        <strong>Baris {{ $failure['row'] }}:</strong>
                        <ul class="list-inline">
                            @foreach($failure['errors'] as $error)
                                <li class="list-inline-item text-danger">⚠️ {{ $error }}</li>
                            @endforeach
                        </ul>
                        <small class="text-muted">Data: {{ implode(', ', array_filter($failure['data'])) }}</small>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    
    {{-- Tampilkan Pesan Success dari session flash --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle"></i> <strong>Sukses!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    {{-- Tampilkan Pesan Error dari session flash --}}
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-times-circle"></i> <strong>Gagal!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    {{-- Tampilkan Error Validasi --}}
    @if ($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Perhatian!</strong> Ada kesalahan pada input Anda.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    {{-- Blok untuk Tombol Aksi Tambah dan Import/Export --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            
            <a href="{{ route('master.customer_prospek.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> Tambah
            </a>
            
            <button type="button" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#importExportModal">
                <i class="fa fa-file-excel"></i> Import / Export
            </button>
            
            @if(auth::id() == 1)
            <button type="button" class="btn btn-info btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                <i class="fa fa-sync-alt"></i> Update Status Massal
            </button>
            @endif

            @if(auth::id() == 1)
            <form action="{{ route('master.customer_prospek.normalize') }}"
                method="POST"
                class="d-inline-block ms-2"
                onsubmit="return confirm('Apakah Anda yakin ingin menormalisasi seluruh nama? Proses ini permanen!')">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm">
                    <i class="bi bi-check2-square"></i> Normalisasi Nama
                </button>
            </form>
            @endif
        </div>
    </div>

    <table class="table table-bordered align-middle table-customer" id="customer_table">
        <thead class="table-light">
            <tr>
                <th style="width:43%">Customer</th>
                <th style="width:5%">Pengajuan</th>
                <th style="width:5%">Website</th>
                <th style="width:16%">Mapping</th>
                <th style="width:5%">PIC</th>
                <th style="width:5%">Officer</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr class="main-row" 
                data-id="{{ $customer->id }}" 
                data-type="{{ $customer->type }}" 
                data-bs-toggle="modal" 
                data-bs-target="#dataModal">
    
                {{-- Customer --}}
                <td>
                    <div class="customer-name fw-semibold">
                        {{ $customer->name }}
                        @if ($data_type == 'all')
                            @if ($customer->type == 'existing')
                                <span class="badge bg-success ms-2" style="font-size: 0.7em;">Existing</span>
                            @elseif ($customer->type == 'prospek')
                                <span class="badge bg-warning ms-2" style="font-size: 0.7em;">Prospek</span>
                            @endif
                        @endif
                    </div>
                    @if($customer->address)
                        <div class="text-muted small">{{ $customer->address }}</div>
                    @endif
                    <div class="text-muted small">
                        {{ $customer->text_kota ?? $customer->city ?? '' }}
                        {{ ($customer->text_kota || $customer->city) && ($customer->text_provinsi || $customer->province) ? ', ' : '' }}
                        {{ $customer->text_provinsi ?? $customer->province ?? '' }}
                    </div>
                </td>
    
                {{-- Pengajuan --}}
                <td>
                    @if($customer->type == 'prospek')
                        {{ $customer->pengajuan_label ?? '-' }}
                    @elseif($customer->type == 'existing')
                        KANTOR
                    @endif
                </td>
    
                {{-- Website --}}
                <td>
                    @php
                        $website = null;
                        if ($customer->type == 'prospek') {
                            $website = optional($customer->store_prospek)->website;
                        } elseif ($customer->type == 'existing') {
                            $website = optional($customer->store_existing)->website;
                        }
    
                        // Normalisasi agar "-" atau kosong dianggap null
                        if ($website === '-' || trim((string)$website) === '') {
                            $website = null;
                        }
                    @endphp
    
                    @if($website)
                        <a href="{{ $website }}" target="_blank" class="btn btn-sm btn-primary" style="min-width: 50px;">
                            Link
                        </a>
                    @else
                        <button type="button" class="btn btn-sm btn-secondary" style="min-width: 50px;" disabled>
                            Link
                        </button>
                    @endif
                </td>
    
                {{-- Mapping & PIC --}}
                @if ($customer->type == 'prospek')
                    <td>{{ optional(optional($customer->store_prospek)->category)->name ?? '-' }}</td>
                    <td>{{ optional($customer->store_prospek)->pic ?? '-' }}</td>
                @else
                    <td>{{ optional(optional($customer->store_existing)->category)->name ?? '-' }}</td>
                    <td>{{ optional($customer->store_existing)->pic ?? '-' }}</td>
                @endif
    
                {{-- Officer --}}
                <td>{{ $customer->officer ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Modal Detail & Edit --}}
<div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail & Edit Data Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Konten detail/edit akan dimuat di sini oleh AJAX --}}
            </div>
            <div class="modal-footer">
                {{-- Tombol Delete akan disisipkan di sini oleh JS --}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importExportModal" tabindex="-1" aria-labelledby="importExportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importExportModalLabel"><i class="fa fa-file-excel"></i> Batch Data Operations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <h6 class="text-primary mb-3">1. Export Template (Unduh Format Input)</h6>
                <div class="alert alert-info" role="alert">
                    Unduh file template Excel ini dan isi dengan data Store Prospek yang baru.
                </div>
                <a href="{{ route('master.customer_prospek.export_template') }}" class="btn btn-outline-success w-100 mb-4" target="_blank">
                    <i class="fa fa-download"></i> Unduh Template Import
                </a>

                <h6 class="text-primary mb-3">2. Import Data (Upload File yang Sudah Diisi)</h6>
                <form action="{{ route('master.customer_prospek.import_batch') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel (.xlsx)</label>
                        <input class="form-control" type="file" id="file" name="file" required accept=".xlsx, .xls, .csv">
                    </div>
                    <button type="submit" class="btn btn-primary w-100" id="importSubmitBtn">
                        <i class="fa fa-upload"></i> Upload & Import
                    </button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk Update Status Massal --}}
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="updateStatusModalLabel"><i class="fa fa-sync-alt"></i> Update Status Massal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <h6 class="text-info mb-3">1. Unduh Template Status</h6>
                <div class="alert alert-warning" role="alert">
                    Unduh template ini untuk mendapatkan data Customer (Existing & Prospek). Isi kolom **STATUS** sesuai kebutuhan.
                </div>
                <a href="{{ route('master.customer_prospek.export_status_template') }}" class="btn btn-outline-info w-100 mb-4" target="_blank">
                    <i class="fa fa-download"></i> Unduh Template Update Status
                </a>

                <hr>

                <h6 class="text-info mb-3">2. Import File Status Update</h6>
                <form action="{{ route('master.customer_prospek.import_status_update') }}" method="POST" enctype="multipart/form-data" id="importStatusForm">
                    @csrf
                    <div class="mb-3">
                        <label for="status_file" class="form-label">Pilih File Excel (.xlsx) Status Update</label>
                        <input class="form-control" type="file" id="status_file" name="file" required accept=".xlsx, .xls, .csv">
                    </div>
                    <button type="submit" class="btn btn-info w-100" id="importStatusSubmitBtn">
                        <i class="fa fa-upload"></i> Upload & Update Status
                    </button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const KATEGORI_OPTIONS = @json($kategori);
const CURRENT_DATA_TYPE = '{{ $data_type }}';
const INDEX_ROUTE = '{{ route('master.customer_prospek.index') }}';

let exportPdfRoute = '';
let exportPdfText = 'Export PDF All';

if (CURRENT_DATA_TYPE === 'existing') {
    exportPdfRoute = '{{ route('master.customer_prospek.export_pdf_existing') }}';
    exportPdfText = 'Export PDF Existing';
} else if (CURRENT_DATA_TYPE === 'prospek') {
    exportPdfRoute = '{{ route('master.customer_prospek.export_pdf_prospek') }}';
    exportPdfText = 'Export PDF Prospek';
} else {
    exportPdfRoute = '{{ route('master.customer_prospek.export_pdf') }}';
    exportPdfText = 'Export PDF All';
}

function getNestedValue(obj, path) {
    if (!obj || !path) return null;
    const parts = path.split('.');
    let current = obj;
    for (let i = 0; i < parts.length; i++) {
        if (current === null || current === undefined || !current.hasOwnProperty(parts[i])) {
            return null;
        }
        current = current[parts[i]];
    }
    return current;
}

// ===================================================
// === OFFLINE QUEUE HANDLER
// ===================================================
let offlineQueue = JSON.parse(localStorage.getItem('offlineQueue') || '[]');

function processOfflineQueue() {
    if (!offlineQueue.length) return;
    const next = offlineQueue.shift();
    $.ajax({
        url: next.url,
        method: 'POST',
        data: next.data,
        success: function() {
            console.log(`Retry success for ID ${next.data.id}`);
            localStorage.setItem('offlineQueue', JSON.stringify(offlineQueue));
            processOfflineQueue();
        },
        error: function() {
            // gagal, simpan kembali dan hentikan proses
            offlineQueue.push(next);
            localStorage.setItem('offlineQueue', JSON.stringify(offlineQueue));
        }
    });
}
window.addEventListener('online', processOfflineQueue);

// ===================================================
// === MAIN SCRIPT
// ===================================================
$(document).ready(function() {
    const filterButtons = `
        <div class="dt-buttons btn-group">
            <a href="${INDEX_ROUTE}?type=all" class="btn btn-sm ${CURRENT_DATA_TYPE === 'all' ? 'btn-primary' : 'btn btn-primary'}">All</a>
            <a href="${INDEX_ROUTE}?type=existing" class="btn btn-sm ${CURRENT_DATA_TYPE === 'existing' ? 'btn-success' : 'btn btn-success'}">Existing</a>
            <a href="${INDEX_ROUTE}?type=prospek" class="btn btn-sm ${CURRENT_DATA_TYPE === 'prospek' ? 'btn-warning' : 'btn btn-warning'}">Prospek</a>
        </div>
        <div class="dt-buttons btn-group">
            <a href="${exportPdfRoute}" class="btn btn-sm btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> ${exportPdfText}
            </a>
        </div>
    `;

    // ===============================================
    // === 1. Init DataTables
    // ===============================================
    var table = $('#customer_table').DataTable({
        ordering: false,
        searching: true,
        autoWidth: false,
        info: false,
        dom: 'lfrtip',
        initComplete: function() {
            const lengthContainer = $('#customer_table_wrapper .dataTables_length');
            lengthContainer.before(filterButtons);
        }
    });

    // ===============================================
    // === 2. Load Modal
    // ===============================================
    $('#customer_table').on('click', '.main-row', function() {
        let id = $(this).data('id');
        const rowType = $(this).data('type');
        const modalBody = $('#dataModal .modal-body');
        const modalFooter = $('#dataModal .modal-footer');

        modalBody.html(`<div class="d-flex justify-content-center"><div class="spinner-border" role="status"></div></div>`);
        modalFooter.html(`<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>`);

        $.ajax({
            url: '{{ route('master.customer_prospek.handle_ajax') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                action: 'show',
                id: id,
                data_type: rowType
            },
            success: function(customer) {
                if (customer.phone && customer.phone.includes(',')) {
                    const phones = customer.phone.split(',');
                    customer.phone = phones[0].trim();
                    if (!customer.phone2 && phones.length > 1) {
                        customer.phone2 = phones[1].trim();
                    }
                }

                const modalTitle = rowType === 'prospek'
                    ? 'Detail & Edit Data Customer Prospek'
                    : 'Detail Data Customer Existing';
                $('#dataModal .modal-title').text(modalTitle);

                let formHtml = `<form id="editForm" class="row">`;
                const fieldPairs = [
                    ['Nama', 'name', 'Contact', 'contact_person'],
                    ['Telepon 1', 'phone', 'Telepon 2', 'phone2'],
                    ['Email', 'email', 'Alamat', 'address'],
                    ['Provinsi', 'text_provinsi', 'Kota', 'text_kota'],
                    ['Kecamatan', 'text_kecamatan', 'Kelurahan', 'text_kelurahan'],
                    ['PIC', (rowType === 'prospek' ? 'store_prospek.pic' : 'store_existing.pic'), 'Officer', 'officer'],
                    ['AR', 'account_representative', 'Mapping', 'kategori_select']
                ];
                const isUpdateAllowed = rowType === 'prospek';

                fieldPairs.forEach(pair => {
                    const [label1, fieldName1, label2, fieldName2] = pair;
                    let value1 = getNestedValue(customer, fieldName1) || '';
                    let value2 = getNestedValue(customer, fieldName2) || '';

                    const inputName1 = fieldName1.includes('.') ? fieldName1.split('.').pop() : fieldName1;
                    const inputName2 = fieldName2.includes('.') ? fieldName2.split('.').pop() : fieldName2;

                    let inputField1 = `<span class="form-text-view me-2">${value1 || '-'}</span>`;
                    if (isUpdateAllowed) {
                        inputField1 = `<input type="text" class="form-control form-control-sm modal-input" data-original-value="${value1}" name="${inputName1}" id="input_${inputName1}" value="${value1}" ${inputName1 === 'officer' ? 'disabled' : ''}>`;
                    }

                    formHtml += `
                        <div class="col-md-6">
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">${label1}</label>
                                <div class="col-sm-8 d-flex align-items-center">${inputField1}</div>
                            </div>
                        </div>
                    `;

                    if (fieldName2) {
                        if (fieldName2 === 'kategori_select') {
                            const currentKategoriName = getNestedValue(customer, (rowType === 'prospek' ? 'store_prospek.category.name' : 'store_existing.category.name'));
                            let inputField2;
                            if (isUpdateAllowed) {
                                const currentKategoriId = getNestedValue(customer, 'store_prospek.category_id');
                                let kategoriOptions = '<option value="">Pilih Mapping</option>';
                                KATEGORI_OPTIONS.forEach(k => {
                                    const isSelected = (currentKategoriId && currentKategoriId == k.id) ? 'selected' : '';
                                    kategoriOptions += `<option value="${k.id}" ${isSelected}>${k.name}</option>`;
                                });
                                inputField2 = `<select class="form-select form-select-sm modal-input" name="kategori" id="input_kategori" data-original-value="${currentKategoriId || ''}">${kategoriOptions}</select>`;
                            } else {
                                inputField2 = `<span class="form-text-view me-2">${currentKategoriName || '-'}</span>`;
                            }

                            formHtml += `
                                <div class="col-md-6">
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label">${label2}</label>
                                        <div class="col-sm-8 d-flex align-items-center">${inputField2}</div>
                                    </div>
                                </div>
                            `;
                        } else {
                            let inputField2 = `<span class="form-text-view me-2">${value2 || '-'}</span>`;
                            if (isUpdateAllowed) {
                                inputField2 = `<input type="text" class="form-control form-control-sm modal-input" data-original-value="${value2}" name="${inputName2}" id="input_${inputName2}" value="${value2}">`;
                            }

                            formHtml += `
                                <div class="col-md-6">
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label">${label2}</label>
                                        <div class="col-sm-8 d-flex align-items-center">${inputField2}</div>
                                    </div>
                                </div>
                            `;
                        }
                    }
                });

                formHtml += `</form>`;
                modalBody.html(formHtml);

                if (isUpdateAllowed) {
                    const saveButton = `
                        <button type="button" class="btn btn-primary" id="saveAllChangesBtn" data-id="${id}">
                            <i class="fa fa-save"></i> Simpan Perubahan
                        </button>`;
                    const deleteFormHtml = `
                        <form action="/customer_prospek/destroy/${id}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data customer ${customer.name}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger me-2"><i class="fa fa-trash"></i> Hapus Customer</button>
                        </form>`;
                    modalFooter.html(saveButton + deleteFormHtml + `<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>`);
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'Gagal memuat data.';
                modalBody.html(`<p class="text-danger">${msg}</p>`);
            }
        });
    });

    // ===============================================
    // === 3. Save Perubahan (Optimistic + Offline)
    // ===============================================
    $(document).on('click', '#saveAllChangesBtn', function() {
        const id = String($(this).data('id'));
        const saveBtn = $(this);
        const updates = [];

        $('#editForm').find('.modal-input').each(function() {
            const input = $(this);
            const fieldName = input.attr('name');
            const currentValue = input.val()?.trim() || '';
            const originalValue = String(input.data('original-value') || '').trim();

            if (currentValue !== originalValue) {
                let fieldToSend = fieldName === 'kategori' ? 'kategori_id' : fieldName;
                updates.push({ field: fieldToSend, value: currentValue });
            }
        });

        if (!updates.length) {
            alert('Tidak ada perubahan yang terdeteksi.');
            return;
        }

        // 🟢 Optimistic update: update langsung di tabel
        const rowSelector = `tr[data-id="${id}"]`;
        const row = table.row(rowSelector);
        if (row.length) {
            let rowData = row.data();
            updates.forEach(update => { rowData[update.field] = update.value; });
            row.data(rowData).invalidate().draw(false);
        }

        // Indikator sedang simpan
        saveBtn.html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...').prop('disabled', true);

        const requestData = {
            _token: '{{ csrf_token() }}',
            action: 'update_batch',
            id: id,
            updates: updates
        };

        $.ajax({
            url: '{{ route('master.customer_prospek.handle_ajax') }}',
            method: 'POST',
            data: requestData,
            success: function(response) {
                console.log('Update success:', response);
                saveBtn.html('<i class="fa fa-save"></i> Simpan Perubahan').prop('disabled', false);
                $('#dataModal').modal('hide');
            },
            error: function(xhr) {
                console.warn('Offline mode, queued:', id);
                offlineQueue.push({
                    url: '{{ route('master.customer_prospek.handle_ajax') }}',
                    data: requestData
                });
                localStorage.setItem('offlineQueue', JSON.stringify(offlineQueue));

                alert('Koneksi tidak stabil. Data disimpan sementara dan akan dikirim ulang saat online.');
                saveBtn.html('<i class="fa fa-save"></i> Simpan Perubahan').prop('disabled', false);
                $('#dataModal').modal('hide');
            }
        });
    });
});
</script>

@endsection