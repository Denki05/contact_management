{{-- MODAL IMPORT/EXPORT PROSPEK - SIMPLIFIED VERSION --}}
<div class="modal fade" id="modalImportExport" tabindex="-1" aria-labelledby="modalImportExportLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary text-white" style="border-radius: 8px;">
            
            <div class="modal-header border-bottom border-secondary py-2 px-3">
                <h6 class="modal-title d-flex align-items-center gap-2 fw-bold" id="modalImportExportLabel" style="font-size: 14px;">
                    <i class="bi bi-arrow-left-right text-info"></i> INTEGRASI DATA PROSPEK
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 10px;"></button>
            </div>

            <div class="modal-body p-3">
                
                {{-- KONTEN 1: DOWNLOAD TEMPLATE --}}
                <div class="p-3 mb-3 rounded-2" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-file-earmark-excel text-primary" style="font-size: 1.25rem;"></i>
                        <div>
                            <div class="fw-bold small" style="letter-spacing: 0.5px;">1. DOWNLOAD TEMPLATE KOSONG</div>
                            <small class="text-muted" style="font-size: 11px; display: block; margin-top: 2px;">
                                Gunakan format standar sistem agar proses import berjalan lancar dan tidak ditolak oleh validasi database.
                            </small>
                        </div>
                    </div>
                    <button type="button" id="btnExportTemplate" class="btn btn-sm btn-primary w-100 fw-bold border-0 mt-1 d-flex align-items-center justify-content-center gap-2" style="height: 32px; font-size: 11.5px; border-radius: 4px;">
                        <i class="bi bi-download"></i> EXPORT TEMPLATE KOSONG
                    </button>
                </div>

                {{-- KONTEN 2: UPLOAD & IMPORT FILE --}}
                <div class="p-3 rounded-2" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-cloud-upload text-success" style="font-size: 1.25rem;"></i>
                        <div>
                            <div class="fw-bold small" style="letter-spacing: 0.5px;">2. UPLOAD & PROSES FILE EXCEL</div>
                            <small class="text-muted" style="font-size: 11px; display: block; margin-top: 2px;">
                                Pilih file Excel (.xlsx / .xls) yang sudah Anda isi datanya berdasarkan template sistem.
                            </small>
                        </div>
                    </div>

                    <form id="formImportProspek" enctype="multipart/form-data" class="mt-2">
                        @csrf
                        {{-- Hidden/Default mode agar tidak membingungkan user di UI --}}
                        <input type="hidden" name="mode" value="add_only">

                        <div class="mb-3">
                            <input type="file" class="form-control form-control-sm text-white border-secondary" id="fileImport" name="file" accept=".xlsx,.xls" required style="background: rgba(0,0,0,0.2); font-size: 11.5px; height: 32px; padding: 4px 8px;">
                            <small class="text-muted d-block mt-1" style="font-size: 10px;">
                                * Kolom Wajib: PIC, HP, Perusahaan. Duplikasi HP otomatis di-skip. Max 5MB.
                            </small>
                        </div>

                        {{-- PROGRESS BAR (SELEK & TIPIS NYATU DENGAN DESIGN) --}}
                        <div id="importProgress" class="d-none mb-2">
                            <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1);">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small id="progressText" class="text-muted" style="font-size: 10px;">Memproses...</small>
                        </div>

                        <button type="submit" id="btnImportStart" class="btn btn-sm btn-success w-100 fw-bold border-0 d-flex align-items-center justify-content-center gap-2" style="height: 32px; font-size: 11.5px; border-radius: 4px;">
                            <i class="bi bi-upload"></i> UPLOAD & PROSES IMPORT
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Tambahkan @push('scripts') di sini --}}
@push('scripts')
<script>
$(document).ready(function() {
    
    // ====== FIX BUG: EVENT DELEGATION UNTUK EXPORT TEMPLATE ======
    $(document).off('click', '#btnExportTemplate').on('click', '#btnExportTemplate', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Mengunduh Template...',
            html: 'Mohon tunggu sebentar file sedang disiapkan.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        // KUNCI: Pastikan nama route ini sama persis dengan di routes/web.php Anda
        window.location.href = "{{ route('master.prospek_tampung.export_template') }}";

        setTimeout(() => Swal.close(), 1200);
    });

    // ====== EVENT DELEGATION UNTUK SUBMIT IMPORT ======
    $(document).off('submit', '#formImportProspek').on('submit', '#formImportProspek', function(e) {
        e.preventDefault();

        let file = $('#fileImport')[0].files[0];
        if (!file) {
            Swal.fire('Error', 'Silakan pilih file terlebih dahulu!', 'error');
            return;
        }

        let formData = new FormData();
        formData.append('file', file);
        formData.append('mode', 'add_only');
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        // Tampilkan element progress
        $('#importProgress').removeClass('d-none');
        $('#progressBar').css('width', '15%');
        $('#progressText').text('Menghubungkan ke server...');
        $('#btnImportStart').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> MEMPROSES...');

        $.ajax({
            url: "{{ route('master.prospek_tampung.import') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                let xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        let percent = Math.round((e.loaded / e.total) * 100);
                        // Amankan agar progress bar bertahap naik manis
                        if(percent > 15 && percent < 90) {
                            $('#progressBar').css('width', percent + '%');
                            $('#progressText').text('Mengunggah file: ' + percent + '%');
                        }
                    }
                });
                return xhr;
            },
            success: function(res) {
                $('#progressBar').css('width', '100%');
                $('#progressText').text('Selesai, memperbarui data sistem...');

                setTimeout(() => {
                    Swal.fire({
                        title: 'Import Selesai!',
                        html: `
                            <div class="text-start small p-2 rounded bg-light text-dark">
                                <span class="text-success">✔</span> Sukses Masuk: <strong>${res.success_count} data</strong><br>
                                <span class="text-warning">⚠</span> Skip (Kosong/Duplikat): <strong>${res.skip_count} data</strong><br>
                                <span class="text-danger">✘</span> Gagal/Error: <strong>${res.error_count} data</strong>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'Mantap'
                    }).then(() => {
                        $('#modalImportExport').modal('hide');
                        // Jika aplikasi Anda SPA / Partial load, panggil fungsi load list data Anda:
                        if (typeof window.loadProspekPartial === 'function') {
                            window.loadProspekPartial(1); 
                        } else {
                            location.reload(); 
                        }
                    });
                    
                    // Reset tombol & form
                    resetImportForm();
                }, 800);
            },
            error: function(xhr) {
                resetImportForm();
                let errMsg = 'Gagal memproses file pada server.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }
                Swal.fire('Import Gagal', errMsg, 'error');
            }
        });
    });

    function resetImportForm() {
        $('#importProgress').addClass('d-none');
        $('#progressBar').css('width', '0%');
        $('#fileImport').val('');
        $('#btnImportStart').prop('disabled', false).html('<i class="bi bi-upload"></i> UPLOAD & PROSES IMPORT');
    }
});
</script>
{{-- Tambahkan @endpush di sini --}}
@endpush