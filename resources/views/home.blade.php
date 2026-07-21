@extends('layouts.app')

@section('title', 'Customer') 

@section('content')
<style>
<<<<<<< HEAD
<<<<<<< HEAD
    .max-width-lg { max-width: 992px; }
    body {
        background-color: #1e2227;
    }
    
    /* =========================================
       CSS KHUSUS NAVIGASI HEADER (CLONE SYS)
       ========================================= */
    .btn-dark-outline {
        background: rgba(255, 255, 255, 0.05); /* Sesuai kode sys */
        border: 1px solid rgba(255, 255, 255, 0.1); /* Sesuai kode sys */
        color: #e5e7eb; /* Sesuai kode sys */
        
        /* Font System agar bentuk huruf rapat */
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
        
        height: 34px; /* Sesuai kode sys */
        padding: 0 12px; /* Sesuai kode sys */
        
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s ease;
        
        /* Kita gunakan 700 karena di Foto 1 terlihat sangat tebal */
        font-weight: 700 !important; 
        line-height: 1 !important;
        border-radius: 4px !important;
        font-size: 0 !important; 
    }
    
    .btn-dark-outline span { 
        /* Ukuran 12.5px sesuai permintaan kamu */
        font-size: 12.5px !important; 
        font-weight: 700 !important; 
        color: inherit; 
        
        /* KUNCI: Karena di CRM teksnya terlihat lebih renggang dibanding SYS, 
           kita paksa rapat dengan nilai ini agar identik dengan Foto 1 */
        letter-spacing: 0.5px !important; 
        
        text-transform: uppercase;
        line-height: 1;
        display: inline-block;
    }
    
    .btn-dark-outline:hover:not(:disabled), 
    .btn-dark-outline.active-nav {
        background-color: #0c82f9; /* Sesuai kode sys */
        border-color: #0c82f9; /* Sesuai kode sys */
        color: #ffffff;
    }

    .nav-grid {
        display: flex;
        align-items: center;
        gap: 5px;
        height: 34px !important;
        width: 100%;
    }

    /* Bagian Officer / User Bar disesuaikan tipis sesuai sys */
    .control-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    
    /* Tambahkan atau perbarui bagian ini */
    #dynamicContainer { 
        /* Menggunakan angka tertinggi dari temuanmu agar stabil */
        min-height: 450px !important; 
        background-color: #111; /* Sesuai background dark-mu */
        transition: none; /* Matikan transisi tinggi agar tidak ada gerakan melompat */
        overflow: hidden;
    }

    /* Pastikan row di dalamnya tidak menambah margin negatif yang merusak ukuran */
    #dynamicContainer .row {
        margin-right: 0;
        margin-left: 0;
    }
    
    #dynamicContainer { 
        min-height: 60vh; 
        position: relative; 
    }
    
    /* Styling Filter Tabs agar berbentuk "Pill" */
    .ci-filter {
        padding: 4px 14px; border-radius: 20px; font-size: 11px; font-weight: 700;
        cursor: pointer; border: 1px solid rgba(255,255,255,0.12);
        background: transparent; color: #6b7280; transition: all .12s;
    }
    .ci-filter:hover { color: #e5e7eb; background: rgba(255,255,255,0.06); }
    .ci-filter.cf-active { background: #0c82f9; border-color: #0c82f9; color: #fff; }
    
    /* Styling Tabel Kustom (Force Override Bootstrap) */
    .ci-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
    .ci-tbl thead th {
        background: #1a1e24; color: #6b7280; font-weight: 600; font-size: 10px;
        text-transform: uppercase; letter-spacing: .5px; padding: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .ci-tbl td { padding: 10px; vertical-align: middle; color: #d1d5db; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .ci-data-row:hover { background: rgba(255,255,255,0.03); }
    .td-name { font-weight: 700; color: #f3f4f6; font-size: 12px; }
    .td-sub { font-size: 11px; color: #6b7280; margin-top: 1px; }
    .td-small { font-size: 11px; color: #9ca3af; }
</style>

<div class="container max-width-lg pb-2 mx-auto" style="background-color:#1e2227; min-height:100vh; padding: 0px 12px;">
    
    {{-- ====== TOP MENU (IDENTIK SYS) ====== --}}
    <div class="header-section mb-1 pt-2"> 
        <div class="nav-grid w-100 mb-1">
            <button id="loadAgenda" type="button" class="btn btn-dark-outline nav-button active-nav">
                <span>AGENDA</span>
            </button>
            <button id="loadProspek" type="button" class="btn btn-dark-outline nav-button">
                <span>DATA PROSPEK</span>
            </button>
            <button id="loadCustomer" type="button" class="btn btn-dark-outline nav-button">
                <span>DATA EXIST</span>
            </button>
            <button id="loadContact" type="button" class="btn btn-dark-outline nav-button">
                <span>CONTACT</span>
            </button>
            <button id="loadProduct" type="button" class="btn btn-dark-outline nav-button">
                <span>PRODUCT - EXTRA</span>
            </button>
            
        </div>
    
        <div class="control-flex d-flex justify-content-between align-items-center w-100 border-top pt-1 mt-1" style="border-color: rgba(255,255,255,0.1) !important;">
            
            <div class="d-flex align-items-center gap-2">
                <div class="text-truncate" style="flex: 0 0 auto; max-width: 140px;">
                    <button class="btn btn-light d-flex align-items-center rounded-1 border-0 shadow-sm px-2 w-100" type="button" style="pointer-events: none; gap: 6px; height: 26px; justify-content: flex-start;">
                        <i class="bi bi-person-circle text-primary flex-shrink-0"></i>
                        <span class="fw-bold text-truncate text-muted" style="font-size: 11px;">{{ Auth::user()->name ?? 'User' }}</span>
                    </button>
                </div>
                
                <button type="button" id="btnEntryHardcopy" class="btn btn-sm btn-primary d-none align-items-center fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahProspek" style="height: 26px; font-size: 11px; padding: 0 10px;">
                    <i class="bi bi-plus-circle me-1"></i> Form Entry
                </button>
                
                <button type="button" id="btnImportExportProspek" class="btn btn-sm btn-info d-none align-items-center fw-bold" data-bs-toggle="modal" data-bs-target="#modalImportExport" style="height: 26px; font-size: 11px; padding: 0 10px;">
                    <i class="bi bi-arrow-left-right me-1"></i> Import/Export
                </button>
            </div>
        
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-danger fw-bold px-0 d-flex align-items-center justify-content-center text-decoration-none flex-shrink-0" style="height: 26px; font-size: 11px; width: 75px; gap: 4px;">
                <span style="font-size: 12.5px;">LOGOUT</span>
            </a>
=======
    .max-width-lg { max-width: 936px; }
    body {
        overflow-y: scroll !important;
        background-color: #1e2227;
    }
</style>

<div class="container max-width-lg pb-2" style="background-color:#1e2227; min-height:100vh;">

    {{-- ====== TOP MENU ====== --}}
    <div class="header-section pt-2">
        <div class="mb-2">
            <div class="row text-center g-2 align-items-stretch">

                <div class="col-6 col-md-3 d-flex">
                    <button id="loadAgenda" class="btn btn-light text-dark fw-semibold w-100 shadow-sm py-1
                        d-flex flex-column justify-content-center align-items-center">
                        AGENDA
                    </button>
                </div>
=======
    .max-width-lg { max-width: 936px; }
    body {
        overflow-y: scroll !important;
        background-color: #1e2227;
    }
</style>

<div class="container max-width-lg pb-2" style="background-color:#1e2227; min-height:100vh;">

    {{-- ====== TOP MENU ====== --}}
    <div class="header-section pt-2">
        <div class="mb-2">
            <div class="row text-center g-2 align-items-stretch">

                <div class="col-6 col-md-3 d-flex">
                    <button id="loadAgenda" class="btn btn-light text-dark fw-semibold w-100 shadow-sm py-1
                        d-flex flex-column justify-content-center align-items-center">
                        AGENDA
                    </button>
                </div>
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

                <div class="col-6 col-md-3 d-flex">
                    <button id="loadCustomer" class="btn btn-light text-dark fw-semibold w-100 shadow-sm py-1
                        d-flex flex-column justify-content-center align-items-center">
                        CUSTOMER
                    </button>
                </div>

                <div class="col-6 col-md-3 d-flex">
                    <button id="loadContact" class="btn btn-light text-dark fw-semibold w-100 shadow-sm py-1
                        d-flex flex-column justify-content-center align-items-center">
                        CONTACT
                    </button>
                </div>

                <div class="col-6 col-md-3 d-flex">
                    <a class="btn btn-light text-dark fw-semibold w-100 shadow-sm py-1 
                        d-flex flex-column justify-content-center align-items-center"
                       href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        LOGOUT
                    </a>
                </div>

            </div>
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        </div>
    </div>

    {{-- ===== DYNAMIC AREA ===== --}}
    <div class="bg-dark p-1 rounded-2 mt-1" id="dynamicContainer">
        <div class="row justify-content-center">
            <div class="col">
                @if (session()->has('welcome_message'))
                    <div id="welcome-card" class="card shadow-lg border-0 rounded-4 bg-white">
                        <div class="card-body text-center p-4">
                            <h5 class="mb-3">Selamat datang, <strong>{{ Auth::user()->name }}</strong>! ðŸ‘‹</h5>
                            <p class="text-muted">Anda berhasil login ke sistem.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
<<<<<<< HEAD
<<<<<<< HEAD

@include('master.tampung_prospek.modal_import_export')

@endsection

@push('scripts')
{{-- =========================================================
     DEPENDENCIES UNTUK MENU PRODUCT & EXTRA
     ========================================================= --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    /**
     * Load modul EXTRA ke dalam #dynamicContainer
     * Dipanggil oleh tombol sub-nav di partial_product.blade.php
     */
    window.loadExtraPartial = function () {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#customerTable')) {
            $('#customerTable').DataTable().destroy();
        }
        $("#dynamicContainer").fadeOut(100, function () {
            $(this).html('<div class="text-center text-white p-3"><div class="spinner-border spinner-border-sm me-2"></div>Memuat Extra...</div>');
            $.get("{{ route('master.product.partial_extra') }}")
                .done(function (res) {
                    $("#dynamicContainer").html(res).fadeIn(150);
                })
                .fail(function (xhr) {
                    $("#dynamicContainer").html(`
                        <div class="text-center text-white py-5">
                            <i class="bi bi-exclamation-triangle-fill text-warning mb-2" style="font-size:2rem; display:block;"></i>
                            <p class="small mt-2">Gagal memuat Extra.<br>
                            <code>Status: ${xhr.status}</code></p>
                        </div>`).fadeIn(150);
                });
        });
    };

    /**
     * PASTIKAN loadProductPartial sudah ada dan tetap seperti ini
     * (tidak perlu diubah, hanya konfirmasi)
     */
    window.loadProductPartial = function () {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#customerTable')) {
            $('#customerTable').DataTable().destroy();
        }
        $("#dynamicContainer").fadeOut(100, function () {
            $(this).html('<div class="text-center text-white p-3"><div class="spinner-border spinner-border-sm me-2"></div>Memuat Product...</div>');
            $.get("{{ route('master.product.partial') }}")
                .done(function (res) {
                    $("#dynamicContainer").html(res).fadeIn(150);
                })
                .fail(function (xhr) {
                    $("#dynamicContainer").html(`
                        <div class="text-center text-white py-5">
                            <i class="bi bi-exclamation-triangle-fill text-warning mb-2" style="font-size:2rem; display:block;"></i>
                            <p class="small mt-2">Gagal memuat Product.<br>
                            <code>Status: ${xhr.status}</code></p>
                        </div>`).fadeIn(150);
                });
        });
    };

    // =========================================================================
    // MENU DATA PROSPEK (STAGE L1 - L3)
    // =========================================================================
    
    // 1. Simpan state tab aktif secara global agar pagination tetap di tab yang benar
    window.currentProspekTab = 'L1'; 
    
    window.loadProspekPartial = function (page = 1, tab = null) {
        // 2. Jika ada parameter tab baru yang dilempar dari tombol toggle, update statenya
        if (tab) {
            window.currentProspekTab = tab;
        }
    
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#customerTable')) {
            $('#customerTable').DataTable().destroy();
        }
        
        $("#dynamicContainer").fadeOut(150, function () {
            $(this).html('<div class="text-center text-white p-3"><div class="spinner-border spinner-border-sm me-2"></div>Memuat Data Prospek...</div>');
            
            // 3. Tambahkan parameter page DAN tab ke dalam URL request
            let fetchUrl = "{{ route('master.prospek_tampung.partial') }}?page=" + page + "&tab=" + window.currentProspekTab;
    
            $.get(fetchUrl)
                .done(function (res) {
                    $("#dynamicContainer").html(res).fadeIn(150);
                })
                .fail(function (xhr) {
                    $("#dynamicContainer").html(`
                        <div class="text-center text-white py-5">
                            <i class="bi bi-exclamation-triangle-fill text-warning mb-2" style="font-size:2rem; display:block;"></i>
                            <p class="small mt-2">Gagal memuat Data Prospek.<br>
                            <code>Status: ${xhr.status}</code></p>
                        </div>`).fadeIn(150);
                });
        });
    };

    // Trigger klik tombol menu Data Prospek
    $(document).off("click", "#loadProspek").on("click", "#loadProspek", function(e){
        e.preventDefault();
        window.loadProspekPartial(1); // Default load halaman 1
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
=======
@endsection

@push('scripts')
=======
@endsection

@push('scripts')
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    #dynamicContainer { 
        min-height: 60vh; 
        position: relative; 
    }
</style>

<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
<script>
$(document).ready(function(){

    // ========== Fade-out welcome card ==========
    setTimeout(() => {
        let welcomeCard = document.getElementById('welcome-card');
        if (welcomeCard) {
            welcomeCard.style.transition = "opacity 1s";
            welcomeCard.style.opacity = "0";
            setTimeout(() => welcomeCard.remove(), 1000);
        }
    }, 4000);

    // ================== GENERAL FUNCTIONS ==================
    function loadPartial(url, data = {}) {
<<<<<<< HEAD
<<<<<<< HEAD
        // Tetap tampilkan loading tapi jangan hapus tinggi kontainer
        $("#dynamicContainer").css('min-height', '568px'); 
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        $("#dynamicContainer").html('<div class="text-center text-white p-3">Loading...</div>');
        return $.get(url, data);
    }

    function resetDynamicContainer() {
        if ($.fn.DataTable.isDataTable('#customerTable')) {
            $('#customerTable').DataTable().destroy();
        }
    }

    function initCustomerTable() {
        if ($.fn.DataTable.isDataTable('#customerTable')) {
            $('#customerTable').DataTable().destroy();
        }
        $('#customerTable').DataTable({
            pageLength: 5,
            ordering: false,
            responsive: false
        });
    }

    // ================== CUSTOMER ==================
    window.currentCustomerType = "all"; // menyimpan filter aktif
<<<<<<< HEAD
<<<<<<< HEAD
    window.currentCustomerPage = 1;     // menyimpan page terakhir
    window.currentCustomerSearch = "";  // BARU: menyimpan keyword pencarian terakhir
    
    // Tambahkan parameter 'search' sebagai parameter ke-3
    window.loadCustomerList = function(type = null, page = 1, search = null) { 
        if(type !== null) window.currentCustomerType = type;
        window.currentCustomerPage = page;
        
        // Jika parameter search diisi, perbarui variabel globalnya
        if(search !== null) window.currentCustomerSearch = search;
    
        $("#dynamicContainer").fadeOut(150, function() {
            // Perhatikan URL route di bawah ini. 
            // Pastikan Controller yang kamu isi kode Paginasi Manual sebelumnya 
            // adalah method yang melayani route 'master.customer_prospek.partial' ini!
            loadPartial("{{ route('master.customer_prospek.partial') }}", { 
                type: window.currentCustomerType,
                page: window.currentCustomerPage,
                q: window.currentCustomerSearch // BARU: Kirim keyword pencarian ke Controller
            }).done(function(res){
                $("#dynamicContainer").html(res).fadeIn(150);
                
                if (typeof initCustomerTable === 'function') {
                    initCustomerTable();
                }
                
                // Catatan: Script binding $(document).on("click", ".customer-pagination"...) 
                // SAYA HAPUS dari sini karena sudah ditangani oleh script 
                // $(document).on('click','.ci-pgn'...) yang ada di dalam partial_index.blade.php
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    window.currentCustomerPage = 1; // menyimpan page terakhir
    
    function loadCustomerList(type = null, page = 1) {
        if(type !== null) window.currentCustomerType = type;
        window.currentCustomerPage = page;
    
        $("#dynamicContainer").fadeOut(150, function() {
            loadPartial("{{ route('master.customer_prospek.partial') }}", { 
                type: window.currentCustomerType,
                page: window.currentCustomerPage
            }).done(function(res){
                $("#dynamicContainer").html(res).fadeIn(150);
                initCustomerTable();
    
                // Bind pagination di partial
                $(document).off("click", ".customer-pagination").on("click", ".customer-pagination", function(e){
                    e.preventDefault();
                    let page = $(this).data("page");
                    loadCustomerList(window.currentCustomerType, page);
                });
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
            });
        });
    }
    
    // Tombol filter All / Existing / Prospek
    $(document).on("click", ".filter-btn", function(e){
        e.preventDefault();
        let type = $(this).data("type");
<<<<<<< HEAD
<<<<<<< HEAD
        window.loadCustomerList(type, 1); // Tambahkan window.
=======
        loadCustomerList(type, 1);
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
        loadCustomerList(type, 1);
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    });
    
    // Tombol Create Customer
    window.loadCustomerCreateForm = function() {
        resetDynamicContainer();
        loadPartial("{{ route('master.customer_prospek.partial_create') }}")
            .done(function(res){
                $("#dynamicContainer").html(res);
                if(typeof initCreateFormEvents === 'function') initCreateFormEvents();
    
                // Tombol Cancel kembali ke list sesuai filter terakhir
                $(document).off("click", "#cancelCreateCustomer").on("click", "#cancelCreateCustomer", function(){
                    loadCustomerList(window.currentCustomerType, window.currentCustomerPage);
                });
            });
    };
    
    window.loadCustomerIndex = function() {
        resetDynamicContainer();
        loadPartial("{{ route('master.customer_prospek.partial') }}")
            .done(function(res){
                $("#dynamicContainer").html(res);
                if (typeof initCustomerIndexEvents === 'function') initCustomerIndexEvents();
            });
    };
<<<<<<< HEAD
<<<<<<< HEAD
    
    window.initCreateFormEvents = function () {

        // Re-init Select2
        $('.js-select2').select2({
            width: '100%',
            dropdownAutoWidth: true,
            theme: "default",
            dropdownParent: $('#dynamicContainer')
        });
    
        // Change Province → Load Cities
        // di initCreateFormEvents(), ketika province berubah:
        $(document).off("change", "#province").on("change", "#province", function () {
            let provId = $(this).val();
            $("input[name=text_provinsi]").val($("#province option:selected").text());
        
            if (!provId) {
                $("#city").html('<option value="">Pilih Kota</option>');
                $("input[name=text_kota]").val("");
                return;
            }
        
            $.ajax({
                url: "{{ route('master.customer_prospek.getkabupaten') }}",
                type: "POST",
                data: {
                    prov_id: provId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend() {
                    $("#city").html('<option>Loading...</option>');
                },
                success(res) {
                    // jika controller masih meng-echo HTML, pakai $('#city').html(res);
                    // sebaiknya controller kembalikan JSON (lihat contoh controller di bawah)
                    let html = '<option value="">Pilih Kota</option>';
                    res.forEach(row => {
                        html += `<option value="${row.id}">${row.name}</option>`;
                    });
                    $("#city").html(html);
                    // re-init select2 jika perlu
                    if ($('#city').hasClass('select2-hidden-accessible')) {
                        $('#city').select2('destroy').select2({
                            width: '100%',
                            dropdownParent: $('#dynamicContainer')
                        });
                    }
                },
                error(xhr) {
                    console.error(xhr);
                    $("#city").html('<option value="">Pilih Kota</option>');
                }
            });
        });

    
        // Change City
        $(document).off("change", "#city").on("change", "#city", function () {
            $("input[name=text_kota]").val($("#city option:selected").text());
        });
    
        // Submit form
        $(document).off("submit", "#createCustomerForm").on("submit", "#createCustomerForm", function (e) {
            e.preventDefault();
    
            let formData = new FormData(this);
    
            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend() {
                    Swal.fire({
                        title: "Processing...",
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success() {
                    Swal.close();
                    Swal.fire("Berhasil", "Data customer prospek disimpan.", "success");
                    window.loadCustomerList(); // Tambahkan window.
                },
                error(xhr) {
                    Swal.close();
                    Swal.fire("Gagal", "Terjadi kesalahan pada server.", "error");
                }
            });
        });
    };
=======

>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======

>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

    // ================== CONTACT ==================
    window.loadContactList = function(page = 1, search = '') {
        loadPartial("{{ route('master.contact.partialIndex') }}", { page, search })
            .done(function(res) {
                $("#dynamicContainer").html(res);
                $('html, body').animate({ scrollTop: 0 }, 150);
            });
    };

    window.loadCreateContactForm = function(customerId) {
        loadPartial("{{ route('master.contact.partial_create') }}", { customer_id: customerId })
            .done(function(res){ 
                $("#dynamicContainer").html(res); 
                initCreateContactFormEvents();
            });
    };

    function loadCreateContactNew() {
        loadPartial("{{ route('master.contact.new') }}")
        .done(function(res){
            if($('#selectCustomerModal').length) $('#selectCustomerModal').remove();
            $("body").append(res);
            $('#selectCustomerModal').modal('show');
            $('.js-select2').select2({ dropdownParent: $('#selectCustomerModal') });

            $('#btnSelectCustomer').off('click').on('click', function(){
                let customerId = $('#customerSelect').val();
                if(!customerId){ 
                    Swal.fire('Pilih Customer terlebih dahulu!'); 
                    return; 
                }
                $('#selectCustomerModal').modal('hide').remove();
                loadCreateContactForm(customerId);
            });
        });
    }

    function initCreateContactFormEvents() {
        $(document).off('submit', '#createContactForm').on('submit', '#createContactForm', function(e){
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(){
                    Swal.fire({ title:'Menyimpan...', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });
                },
                success: function(res){
                    Swal.close();
                    Swal.fire('Berhasil','Data kontak berhasil disimpan.','success');
                    window.loadContactList();
                },
                error: function(xhr){
                    Swal.close();
                    if(xhr.status === 422){
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = Object.values(errors).map(v => v.join('<br>')).join('<br>');
                        Swal.fire('Gagal', errorMessages, 'error');
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan pada server.', 'error');
                    }
                }
            });
        });

        $('#cancelCreateContact').off('click').on('click', function(){
            window.loadContactList();
        });
    }

    // ================== EVENT BUTTONS ==================
    $(document).on("click", "#loadCustomer", function(e){
        e.preventDefault();
        loadCustomerList();
    });

    $(document).on("click", "#loadContact", function(e){
        e.preventDefault();
        window.loadContactList();
    });

    $(document).on("click", "#loadCreateContactForm", function(e){
        e.preventDefault();
        loadCreateContactNew();
    });
<<<<<<< HEAD
<<<<<<< HEAD
    
    // ✅ Tombol PRODUCT
    $(document).on("click", "#loadProduct", function(e){
        e.preventDefault();
        window.loadProductPartial();
    });
    
    // ✅ TAMBAHAN: Tombol EXTRA
    $(document).on("click", "#loadExtra", function(e){
        e.preventDefault();
        window.loadExtraPartial();
    });
    
    // ========== Active Menu Highlighter & Tombol Entry ==========
    $('.nav-button').on('click', function() {
        // Hapus warna biru dari semua tombol menu
        $('.nav-button').removeClass('active-nav');
        // Tambahkan warna biru hanya pada tombol yang sedang diklik
        $(this).addClass('active-nav');
    
        // Logika memunculkan tombol Entry Hardcopy & Import/Export
        if ($(this).attr('id') === 'loadProspek') {
            $('#btnEntryHardcopy').removeClass('d-none').addClass('d-flex');
            $('#btnImportExportProspek').removeClass('d-none').addClass('d-flex');  // TAMBAHKAN INI
        } else {
            $('#btnEntryHardcopy').addClass('d-none').removeClass('d-flex');
            $('#btnImportExportProspek').addClass('d-none').removeClass('d-flex');  // TAMBAHKAN INI
        }
    });
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

    // ================== CONTACT PAGINATION & SEARCH ==================
    $(document).off("click", ".ajax-pagination").on("click", ".ajax-pagination", function(e){
        e.preventDefault();
        let page = $(this).data("page");
        let search = $('input[name="search"]').val();
        window.loadContactList(page, search);
    });

    $(document).off("submit", "#contactSearchForm").on("submit", "#contactSearchForm", function(e){
            e.preventDefault();
            let search = $(this).find('input[name="search"]').val();
            window.loadContactList(1, search);
        });
        
        $(document).on("click", "#loadAgenda", function(e){
        e.preventDefault();
        $("#dynamicContainer").fadeOut(150, function() {
            $(this).html(`
                <div class="row justify-content-center">
                    <div class="col">
                        <div class="card shadow-lg border-0 rounded-4 bg-white">
                            <div class="card-body text-center p-4">
                                <h5 class="mb-3">Fitur Agenda Belum Tersedia</h5>
                                <p class="text-muted">Fitur ini masih dalam pengembangan dan akan tersedia di versi mendatang.</p>
                            </div>
                        </div>
                    </div>
                </div>
            `).fadeIn(150);
        });
    });
<<<<<<< HEAD
<<<<<<< HEAD
    
    // ========== Active Menu Highlighter ==========
    $('.nav-button').on('click', function() {
        // Hapus warna biru dari semua tombol menu
        $('.nav-button').removeClass('active-nav');
        // Tambahkan warna biru hanya pada tombol yang sedang diklik
        $(this).addClass('active-nav');
    });
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
});
</script>
@endpush