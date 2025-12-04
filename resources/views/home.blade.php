@extends('layouts.app')

@section('title', 'Customer') 

@section('content')
<style>
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
@endsection

@push('scripts')
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
            });
        });
    }
    
    // Tombol filter All / Existing / Prospek
    $(document).on("click", ".filter-btn", function(e){
        e.preventDefault();
        let type = $(this).data("type");
        loadCustomerList(type, 1);
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
});
</script>
@endpush