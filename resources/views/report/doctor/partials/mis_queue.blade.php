<div id="misQueueWrap" style="font-size:0.8rem;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-bold text-white" style="font-size:0.85rem;">
            <i class="bi bi-inbox me-1"></i> Antrian Setting PIC (MIS)
        </span>
        <span class="badge bg-primary rounded-pill">{{ $queue->count() }} Data</span>
    </div>

    @if($queue->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-check2-circle text-secondary" style="font-size:2.2rem; opacity:0.5; display:block; margin-bottom:8px;"></i>
            <span class="text-secondary" style="font-size:0.8rem;">Tidak ada antrian. Semua data sudah di-assign.</span>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-sm table-dark align-middle mb-0" style="font-size:0.75rem;">
                <thead>
                    <tr>
                        <th>Nama & Perusahaan</th>
                        <th>Lokasi</th>
                        <th>Source</th>
                        <th style="width:110px;">Dikirim</th>
                        <th style="width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($queue as $row)
                        <tr id="mis-row-{{ $row->id }}">
                            <td>
                                <div class="fw-bold text-white">{{ $row->nama ?? '-' }}</div>
                                <div class="text-secondary">{{ $row->perusahaan ?? '-' }}</div>
                            </td>
                            <td>{{ $row->kota ?? '-' }}<br><span class="text-secondary">{{ $row->zone ?? '-' }}</span></td>
                            <td><span class="badge bg-secondary">{{ $row->source ?? '-' }}</span></td>
                            <td>{{ $row->mis_requested_at ? \Carbon\Carbon::parse($row->mis_requested_at)->format('d/m H:i') : '-' }}</td>
                            <td>
                                <!-- Parameter perusahaan ditambahkan di sini -->
                                <button type="button" class="btn btn-sm btn-warning fw-bold" style="font-size:0.7rem;"
                                        onclick="window.openMisSetModal({{ $row->id }}, '{{ addslashes($row->nama ?? '') }}', '{{ addslashes($row->perusahaan ?? '') }}')">
                                    <i class="bi bi-person-gear"></i> Set
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Tambahkan style ini di bagian atas modal atau di file CSS Anda --}}
<style>
    /* Kustomisasi warna fokus (klik) agar senada dengan warna kuning (warning), bukan biru bawaan Bootstrap */
    #modalMisSet .form-control:focus, 
    #modalMisSet .form-select:focus {
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.15) !important;
        background-color: #1a1d20 !important;
    }
    
    /* Menyatukan warna border input group agar terlihat lebih mulus */
    #modalMisSet .input-group-text {
        background-color: #2b3035;
        border-color: #495057;
        color: #adb5bd;
    }
    #modalMisSet .form-control, 
    #modalMisSet .form-select {
        background-color: #212529;
        border-color: #495057;
        color: #e9ecef;
    }
</style>

{{-- Modal isi AM / ASM / PIC / SPG --}}
<div class="modal fade" id="modalMisSet" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-secondary" data-bs-theme="dark" style="border-radius: 0.75rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.5);">
            
            <div class="modal-header border-secondary align-items-center pb-3">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="bi bi-person-lines-fill text-warning me-2"></i> Assign PIC & Tim
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body pt-3 pb-4">
                <input type="hidden" id="misSetId">

                <!-- Kotak Info dengan Aksen Garis Kiri (Fokus ke Perusahaan) -->
                <div class="alert d-flex align-items-start py-2 px-3 mb-4 shadow-sm" style="background-color: #212529; border: 1px solid #373b3e; border-left: 4px solid #0dcaf0; font-size: 0.85rem; border-radius: 0.4rem; text-align: left;">
                    <i class="bi bi-building-check text-info me-3 mt-1 fs-5"></i>
                    <div>
                        <span class="text-secondary">Menetapkan tim untuk perusahaan:</span><br>
                        <strong class="text-white fs-6" id="misSetPerusahaan"></strong><br>
                        <i class="bi bi-person text-secondary me-1" style="font-size: 0.75rem;"></i>
                        <span class="text-secondary" style="font-size: 0.8rem;" id="misSetNama"></span>
                    </div>
                </div>

                <!-- Grid Form 2x2 -->
                <div class="row g-3">
                    <!-- AM -->
                    <div class="col-md-6">
                        <label class="form-label mb-1 text-start d-block" style="font-size:0.75rem; color:#adb5bd;">
                            AM (Area Manager) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <input type="text" id="misSetAm" class="form-control" placeholder="Nama AM..." autocomplete="off">
                        </div>
                    </div>

                    <!-- ASM -->
                    <div class="col-md-6">
                        <label class="form-label mb-1 text-start d-block" style="font-size:0.75rem; color:#adb5bd;">
                            ASM (Sales Manager) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                            <input type="text" id="misSetAms" class="form-control" placeholder="Nama ASM..." autocomplete="off">
                        </div>
                    </div>

                    <!-- PIC -->
                    <div class="col-md-6">
                        <label class="form-label mb-1 text-start d-block" style="font-size:0.75rem; color:#adb5bd;">
                            PIC <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-person-check"></i></span>
                            <input type="text" id="misSetPic" class="form-control" placeholder="Nama PIC..." autocomplete="off">
                        </div>
                    </div>

                    <!-- SPG -->
                    <div class="col-md-6">
                        <label class="form-label mb-1 text-start d-block" style="font-size:0.75rem; color:#adb5bd;">
                            SPG <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bi bi-shop"></i></span>
                            <select id="misSetSpg" class="form-select">
                                <option value="" disabled selected>-- Pilih SPG --</option>
                                @foreach($spgList as $spg)
                                    <option value="{{ $spg }}">{{ $spg }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mengubah tombol batal menjadi btn-secondary agar ada fill abu-abu gelap -->
            <div class="modal-footer border-secondary pt-2 pb-3 d-flex justify-content-between">
                <button type="button" class="btn btn-sm btn-secondary px-4 text-light" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" id="btnMisSetSave" class="btn btn-sm btn-warning fw-bold px-4 shadow-sm" onclick="window.submitMisSet()">
                    <i class="bi bi-send-check me-1"></i> Simpan & Kirim
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Pastikan tag script ini tidak ganda dengan yang ada di halaman induk jika Anda menerapkan Opsi 2 sebelumnya -->
<script>
    (function() {
        // Parameter perusahaan ditambahkan di fungsi ini
        window.openMisSetModal = function(id, nama, perusahaan) {
            $('#misSetId').val(id);
            $('#misSetNama').text(nama || '-');
            $('#misSetPerusahaan').text(perusahaan || '-');
            
            $('#misSetAm, #misSetAms, #misSetPic').val('');
            $('#misSetSpg').val('');
            new bootstrap.Modal(document.getElementById('modalMisSet')).show();
        };

        window.submitMisSet = function() {
            var id  = $('#misSetId').val();
            var am  = $('#misSetAm').val().trim();
            var ams = $('#misSetAms').val().trim();
            var pic = $('#misSetPic').val().trim();
            var spg = $('#misSetSpg').val();

            if (!am || !ams || !pic) {
                Swal.fire({ title: 'Lengkapi Data', text: 'AM, ASM, PIC, dan SPG wajib diisi semua.', icon: 'warning', background: '#1a1d21', color: '#fff' });
                return;
            }

            var btn = $('#btnMisSetSave');
            var oriText = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

            $.ajax({
                url: '{{ route("report.doctor.mis_queue.set", ["id" => "__ID__"]) }}'.replace('__ID__', id),
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    am: am, ams: ams, pic: pic, spg: spg
                },
                success: function(res) {
                    if (res.success) {
                        var modalEl = document.getElementById('modalMisSet');
                        var modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if (modalInstance) modalInstance.hide();

                        Swal.fire({ title: 'Berhasil', text: res.message, icon: 'success', background: '#1a1d21', color: '#fff', timer: 1800, showConfirmButton: false });

                        $('#mis-row-' + id).fadeOut(250, function() { $(this).remove(); });
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                },
                error: function(xhr) {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                    Swal.fire('Gagal', msg, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html(oriText);
                }
            });
        };
    })();
</script>