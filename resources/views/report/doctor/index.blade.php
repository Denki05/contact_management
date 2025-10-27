@extends('layouts.app')
<style>
    /* ... (Kode CSS Dropdown Bertingkat Anda yang sudah ada) ... */

    /* === SOLUSI OVERRIDE FULL SCREEN MODAL === */
    /* Menargetkan modal-dialog dengan ID doctorModal */
    #doctorModal .modal-dialog {
        max-width: 100vw !important;
        width: 100% !important;
        margin: 0 !important;
    }

    #doctorModal .modal-content {
        min-height: 100vh !important;
        border-radius: 0 !important;
    }
    
    .list-group-item {
        /* ... (CSS yang sudah ada) ... */
        cursor: pointer; /* Menjadikan kursor tangan di seluruh area item */
    }
    
    /* Opsional: Efek visual lain saat hover */
    .list-group-item:hover {
        background-color: #f8f9fa; /* Misalnya, ubah sedikit warna latar belakang */
    }
    /* === END SOLUSI OVERRIDE === */
</style>
@section('content')
<div class="container">
    {{-- FILTER OFFICER & KOTA --}}
    <div class="row">
        <div class="col-12">
            <form method="GET" action="{{ route('report.doctor.index') }}">
                <input type="hidden" name="tab" id="active_tab_input" value="{{ $activeTab ?? 'existing' }}">

                <div class="d-flex flex-wrap align-items-center g-2 mb-2">

                    {{-- DROPDOWN OFFICER --}}
                    <div class="flex-shrink-0 me-2" style="width: 160px;">
                        <select name="store_id" id="store_id"
                                class="form-select form-select-sm js-select2 w-100">
                            <option value="">Pilih Officer</option>
                            <option value="all" {{ request('store_id') == 'all' ? 'selected' : '' }}>All</option>
                            @foreach($officers as $officer)
                                <option value="{{ strtolower($officer->officer) }}"
                                    {{ request('store_id') == strtolower($officer->officer) ? 'selected' : '' }}>
                                    {{ $officer->officer }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TOMBOL AGENDA --}}
                    <div class="flex-shrink-0 me-2">
                        <a href="{{ request('store_id') ? route('report.doctor.agenda', ['officer' => request('store_id')]) : '#' }}"
                            id="agenda-button"
                            {{-- ðŸ”‘ PERUBAHAN DI SINI: btn-primary (Enable) atau btn-outline-dark disabled (Disabled) --}}
                            class="btn btn-sm {{ request('store_id') ? 'btn-primary' : 'btn-outline-dark disabled' }}" 
                            target="_blank" title="Lihat agenda Pic/Officer">
                            Agenda
                        </a>
                    </div>
                    
                    {{-- TOMBOL EXPORT LIST MARKET --}}
                    <div class="flex-shrink-0 me-2">
                        <button id="btnListMarket" class="btn btn-success btn-sm" disabled>
                            <i class="bi bi-bar-chart-line"></i> List Market
                        </button>
                    </div>
                    
                    {{-- TOMBOL SAMPLING --}}
                    <div class="flex-shrink-0 me-2">
                        <button id="btnSampling" class="btn btn-success btn-sm" disabled>
                            <i class="bi bi-clipboard-data"></i> Sampling
                        </button>
                    </div>

                    {{-- DROPDOWN KOTA --}}
                    <div class="flex-shrink-0 me-2" style="min-width: 150px;">
                        <select name="city" id="city_filter"
                                class="form-select form-select-sm js-select2">
                            <option value="">Pilih Kota</option>
                        </select>
                    </div>

                    {{-- DROPDOWN KATEGORI --}}
                    <div class="flex-shrink-0 me-2" style="min-width: 120px;">
                        <select name="kat" id="kat"
                                class="form-select form-select-sm js-select2">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ (isset($selectedCategory) && $selectedCategory == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TOMBOL CARI --}}
                    <div class="flex-shrink-0 me-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-search"></i> Cari
                        </button>
                    </div>

                    {{-- TOMBOL RESET --}}
                    <div class="flex-shrink-0">
                        <a href="{{ route('report.doctor.index') }}" class="btn btn-danger btn-sm" title="Reset pencarian customer exisiting/prospek">
                            <i class="fa fa-refresh"></i> Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- NAVIGASI TAB EXISTING | PROSPEK --}}
    <ul class="nav nav-tabs mt-4 mb-3">
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? 'existing') === 'existing' ? 'active' : '' }}"
                href="javascript:void(0)" onclick="showTab('existing')" data-tab="existing">
                Existing ({{ count($customers ?? []) }})
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? 'existing') === 'prospek' ? 'active' : '' }}"
                href="javascript:void(0)" onclick="showTab('prospek')" data-tab="prospek">
                Prospek ({{ count($prospekCustomers ?? []) }})
            </a>
        </li>
    </ul>

    {{-- LIST DATA CUSTOMER --}}
    <div id="tab-existing" style="{{ ($activeTab ?? 'existing') === 'existing' ? '' : 'display:none;' }}">
        @if(count($customers ?? []))
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-2">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($customers as $customer)
                            <div class="list-group-item d-block py-2 d-flex justify-content-between align-items-start"
                                data-customer-id="{{ $customer->id }}">
                                <div>
                                    <p class="mb-1 font-weight-bold" style="font-size: 1.1em;">
                                        <b>{{ $customer->name }}</b>
                                    </p>
                                    <p class="mb-1 font-weight-bold" style="font-size: 0.70em;">
                                        {{ $customer->address }}, {{ $customer->text_kota }} - {{ $customer->text_provinsi }}
                                    </p>
                                    
                                    @if ($selectedOfficer === 'all')
                                    <p class="mb-0 text-muted" style="font-size: 0.80em;">
                                        <!--PIC: <b>{{ $customer->officer ?: ($customer->store_existing->pic ?? '-') }}</b>-->
                                        Officer: <b>{{ $customer->officer ?? '-' }}</b>
                                    </p>
                                    @endif
                                </div>

                                <p class="mb-1 font-weight-bold" style="font-size: 0.80em;">
                                    <b>{{ $customer->store_existing && $customer->store_existing->category
                                        ? $customer->store_existing->category->name
                                        : '-' }}</b>
                                </p>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="alert alert-info">Data Existing tidak ditemukan</div>
        @endif
    </div>

    <div id="tab-prospek" style="{{ ($activeTab ?? 'existing') === 'prospek' ? '' : 'display:none;' }}">
        @if(count($prospekCustomers ?? []))
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-2">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($prospekCustomers as $customer)
                            <div class="list-group-item d-block py-2 d-flex justify-content-between align-items-start"
                                data-customer-id="{{ $customer->id }}">
                                <div>
                                    <p class="mb-1 font-weight-bold" style="font-size: 1.2em;">
                                        <b>{{ $customer->name }}</b>
                                    </p>
                                    <p class="mb-1 font-weight-bold" style="font-size: 0.70em;">
                                        {{ $customer->address }}, {{ $customer->text_kota }} - {{ $customer->text_provinsi }}
                                    </p>
                                    
                                    @if ($selectedOfficer === 'all')
                                    <p class="mb-0 text-muted" style="font-size: 0.80em;">
                                        <!--PIC: <b>{{ $customer->officer ?: ($customer->store_prospek->pic ?? '-') }}</b>-->
                                        Officer: <b>{{ $customer->officer ?? '-' }}</b>
                                    </p>
                                    @endif
                                </div>

                                <p class="mb-1 font-weight-bold" style="font-size: 0.90em;">
                                    <b>{{ $customer->store_prospek && $customer->store_prospek->category
                                        ? $customer->store_prospek->category->name
                                        : '-' }}</b>
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="alert alert-info">Data Prospek tidak ditemukan</div>
        @endif
    </div>
</div>

<div class="modal fade" id="doctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-lg-down">
        <div class="modal-content">

            <div class="modal-header d-block p-3 border-0" style="background-color: #f1f1f1;">
                <div class="d-flex justify-content-between align-items-start">
                    <h6 class="modal-title fw-bold text-dark">Detail Report Kegiatan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div id="customer-detail-header" class="p-3 mt-2 rounded shadow-sm" style="background-color: #ffffff; border-left: 5px solid #007bff;">
                    <h6 class="mb-2 fw-bold text-primary" id="detail-customer-name">Memuat Nama Customer...</h6>

                    <div class="row small g-2">
                        <div class="col-md-6 col-12">
                            <div class="d-flex mb-1">
                                <i class="fas fa-user-tag text-secondary me-2" style="width: 15px;"></i>
                                <span class="fw-bold text-nowrap" style="width: 70px;">PIC:</span>
                                <span id="detail-customer-pic" class="ms-1">Memuat...</span>
                            </div>
                            <div class="d-flex">
                                <i class="fas fa-phone-alt text-secondary me-2" style="width: 15px;"></i>
                                <span class="fw-bold text-nowrap" style="width: 70px;">Telp:</span>
                                <span id="detail-customer-telp" class="ms-1">Memuat...</span>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="d-flex mb-1">
                                <i class="fas fa-box-open text-secondary me-2" style="width: 15px;"></i>
                                <span class="fw-bold text-nowrap" style="width: 70px;">Kategori:</span>
                                <span id="detail-customer-kategori" class="ms-1">Memuat...</span>
                            </div>
                            <div class="d-flex">
                                <i class="fas fa-map-marker-alt text-secondary me-2" style="width: 15px;"></i>
                                <span class="fw-bold text-nowrap" style="width: 70px;">Alamat:</span>
                                <span id="detail-customer-alamat" class="ms-1">Memuat...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-body p-0" id="doctorModalBody">
                <div id="dateFilterForm" class="p-3 border-bottom">
                    <p class="text-muted small mb-2 fw-bold">Pilih rentang tanggal kegiatan:</p>
                    <div class="d-flex flex-wrap align-items-end g-2">
                        <div class="flex-grow-1 me-2" style="min-width: 120px;">
                            <label for="startDate" class="form-label small mb-0">Tgl. Mulai</label>
                            <input type="date" class="form-control form-control-sm" id="startDate">
                        </div>
                        <div class="flex-grow-1 me-2" style="min-width: 120px;">
                            <label for="endDate" class="form-label small mb-0">Tgl. Akhir</label>
                            <input type="date" class="form-control form-control-sm" id="endDate">
                        </div>
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-primary btn-sm" id="applyDateFilter">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="resetFilterButtonContainer" class="p-3 text-end" style="display: none;">
                    <button type="button" class="btn btn-warning btn-sm" id="resetDateFilter">
                        <i class="fa fa-undo" aria-hidden="true"></i> Cari Tanggal Lain
                    </button>
                </div>

                <div id="activityReportContainer" class="px-3 pb-3" style="display: none;">
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        // ======================================
        // --- GLOBAL VARIABEL & INISIALISASI ---
        // ======================================
        const $officerSelect = $('#store_id');
        const $citySelect = $('#city_filter');
        const $agendaButton = $('#agenda-button');
        const $btnListMarket = $('#btnListMarket');
        const $btnSampling = $('#btnSampling');
        const agendaBaseUrl = "{{ route('report.doctor.agenda', ['officer' => '___OFFICER___']) }}";
        let currentCustomerId = null;

        // ======================================
        // --- TAB SWITCHING ---
        // ======================================
        window.showTab = function(tab) {
            if(!tab) return;
            $('#active_tab_input').val(tab);

            if(tab === 'existing') {
                $('#tab-existing').show();
                $('#tab-prospek').hide();
            } else {
                $('#tab-existing').hide();
                $('#tab-prospek').show();
            }

            // Update active class pada nav
            $('.nav .nav-link').removeClass('active');
            $(".nav .nav-link").each(function(){
                const dt = $(this).data('tab');
                const onclick = $(this).attr('onclick') || '';
                if ((dt && dt === tab) || onclick.indexOf("showTab('"+tab+"')") !== -1) {
                    $(this).addClass('active');
                }
            });

            // Update query param di URL tanpa reload
            try {
                const u = new URL(window.location.href);
                u.searchParams.set('tab', tab);
                history.replaceState(null, '', u.toString());
            } catch(e) {}
        };

        // Set tab awal
        const initialTab = (function() {
            try {
                const q = new URLSearchParams(window.location.search);
                return q.get('tab') || $('#active_tab_input').val() || 'existing';
            } catch(e) {
                return $('#active_tab_input').val() || 'existing';
            }
        })();
        window.showTab(initialTab);

        // ======================================
        // --- AGENDA BUTTON & CITY DROPDOWN ---
        // ======================================
        function updateAgendaButton(officerValue) {
            $agendaButton.removeClass('btn-primary btn-outline-dark disabled').attr('aria-disabled','false');

            if(officerValue && officerValue.toLowerCase() !== 'all') {
                const newUrl = agendaBaseUrl.replace('___OFFICER___', officerValue);
                $agendaButton.addClass('btn-primary').removeClass('disabled').attr('href', newUrl);
            } else {
                $agendaButton.addClass('btn-outline-dark disabled').attr('aria-disabled','true').attr('href','#');
            }
        }

        function loadCities(officerValue, initialCity=null) {
            $citySelect.empty().append('<option>Memuat Kota...</option>').prop('disabled',true);

            if(!officerValue){
                $citySelect.empty().append('<option value="">Pilih Kota</option>').prop('disabled',false);
                return;
            }

            $.ajax({
                url:"{{ route('report.doctor.cities') }}",
                method:'GET',
                data:{officer:officerValue, tab:$('#active_tab_input').val()},
                success:function(resp){
                    $citySelect.empty().append('<option value="">Pilih Kota</option>');
                    if(resp.cities && resp.cities.length>0){
                        $.each(resp.cities,function(i,city){
                            $citySelect.append($('<option>',{
                                value:city,
                                text:city,
                                selected:(initialCity===city)
                            }));
                        });
                    }
                    $citySelect.prop('disabled',false).trigger('change');
                },
                error:function(){
                    $citySelect.empty().append('<option value="">Gagal memuat Kota</option>').prop('disabled',false);
                }
            });
        }

        updateAgendaButton($officerSelect.val());
        $officerSelect.on('change', function(){
            const val = $(this).val();
            updateAgendaButton(val);
            loadCities(val, null);
            toggleButtons(); // update tombol List Market & Sampling
        });

        const initialOfficer = $officerSelect.val();
        const initialCity = "{{ $selectedCity ?? '' }}";
        if(initialOfficer) loadCities(initialOfficer, initialCity);

        // ======================================
        // --- TOGGLE BUTTONS (List Market & Sampling) ---
        // ======================================
        function toggleButtons() {
            const val = $officerSelect.val();
            const isValid = val && val.trim() !== '' && val.toLowerCase() !== 'all';

            // List Market
            $btnListMarket.prop('disabled', !isValid)
                          .toggleClass('btn-success', isValid)
                          .toggleClass('btn-secondary', !isValid);

            // Sampling
            $btnSampling.prop('disabled', !isValid)
                        .toggleClass('btn-danger', isValid)
                        .toggleClass('btn-secondary', !isValid);
        }

        // Inisialisasi tombol
        toggleButtons();

        // Klik List Market
        $btnListMarket.on('click', function(e){
            e.preventDefault();
            const officer = $officerSelect.val();

            if(!officer || officer.toLowerCase() === 'all'){
                alert('Silakan pilih Officer yang spesifik, bukan "All".');
                return;
            }

            const url = '/report/doctor/file-doctor/market-list?officer_id=' + encodeURIComponent(officer);
            window.open(url,'_blank');
        });

        // Klik Sampling
        $btnSampling.on('click', function(e){
            e.preventDefault();
            const officer = $officerSelect.val();

            if(!officer || officer.toLowerCase() === 'all'){
                alert('Silakan pilih Officer yang spesifik, bukan "All".');
                return;
            }

            const url = '{{ route("report.doctor.sampling") }}?store_id=' + encodeURIComponent(officer);
            window.open(url,'_blank');
        });

        // ======================================
        // --- DETAIL CUSTOMER MODAL ---
        // ======================================
        function resetToFilterForm() {
            const today = getTodayDate();
            $('#startDate').val(today);
            $('#endDate').val(today);

            $('#activityReportContainer').hide().empty();
            $('#resetFilterButtonContainer').hide();

            $('#dateFilterForm').show();
            $('#resetDateFilter').off('click');
        }

        function getTodayDate() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2,'0');
            const dd = String(today.getDate()).padStart(2,'0');
            return `${yyyy}-${mm}-${dd}`;
        }

        function formatDate(dateString) {
            if(!dateString) return '-';
            const parts = dateString.split('-');
            if(parts.length===3){
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return dateString;
        }

        function createActivityTableHTML(activities) {
            if(!activities || activities.length===0){
                return '<div class="alert alert-info border-0 p-3">Data kegiatan/follow up tidak ditemukan pada rentang tanggal tersebut.</div>';
            }

            let html = '<div class="table-responsive">';
            html += '<table class="table table-bordered table-sm table-striped table-hover">';
            html += '<thead style="font-size: 14px;"><tr>';
            html += '<th rowspan="2" style="width:6%;">Tanggal</th>';
            html += '<th colspan="2">Kegiatan</th>';
            html += '<th rowspan="2" style="width:30%;">Keterangan</th>';
            html += '<th rowspan="2" style="width:15%;">Produk</th>';
            html += '<th rowspan="2" style="width:20%;">Respon</th>';
            html += '</tr><tr><th style="width:5%;">Jenis</th><th style="width:25%;">Deskripsi</th></tr></thead>';
            html += '<tbody style="font-size:12px;">';

            activities.forEach(function(d){
                const formattedDate = formatDate(d.tanggal);
                let produkText = d.produk || '-';
                if(d.kegiatan && d.kegiatan.toLowerCase()==='sampling'){
                    const kemasan = d.kemasan || '';
                    if(kemasan) produkText = `${produkText} / ${kemasan}`;
                }

                html += '<tr>';
                html += `<td class="text-wrap">${formattedDate}</td>`;
                html += `<td class="text-wrap">${d.kegiatan || '-'}</td>`;
                html += `<td class="text-wrap">${d.kegiatan_text || '-'}</td>`;
                html += `<td class="text-wrap">${d.keterangan || '-'}</td>`;
                html += `<td class="text-wrap">${produkText}</td>`;
                html += `<td class="text-wrap">${d.respon || '-'}</td>`;
                html += '</tr>';
            });

            html += '</tbody></table></div>';
            return html;
        }

        // Klik list group item untuk tampilkan modal customer
        $(document).on('click', '.list-group-item', function(){
            const customerId = $(this).data('customer-id');
            currentCustomerId = customerId;
            if(!customerId) return;

            resetToFilterForm();

            const customerRow = $(this);
            const customerName = customerRow.find('b:first').text().trim();
            const customerKategori = customerRow.find('p:last b').text().trim();
            const customerAddress = customerRow.find('p:nth-child(2)').text().trim();

            $('#detail-customer-name').text(customerName);
            $('#detail-customer-kategori').text(customerKategori);
            $('#detail-customer-pic').text('Memuat...');
            $('#detail-customer-telp').text('Memuat...');
            $('#detail-customer-alamat').text(customerAddress);

            $('#doctorModal').modal('show');

            $.ajax({
                url: "{{ url('report/doctor/detail') }}/"+customerId,
                method:'GET',
                success:function(resp){
                    const customerDetail = resp.data && resp.data.length>0 ? resp.data[0] : null;
                    if(customerDetail){
                        $('#detail-customer-name').text(customerDetail.customer_name || customerName);
                        $('#detail-customer-pic').text(customerDetail.pic_customer || '-');
                        $('#detail-customer-telp').text(customerDetail.phone || '-');
                        $('#detail-customer-kategori').text(customerDetail.kategori_name || customerKategori);
                        $('#detail-customer-alamat').text(customerDetail.address_customer || customerAddress);
                    } else {
                        $('#detail-customer-pic').text('-');
                        $('#detail-customer-telp').text('-');
                        $('#detail-customer-alamat').text('-');
                    }
                },
                error:function(){
                    $('#detail-customer-pic').text('Gagal');
                    $('#detail-customer-telp').text('Gagal');
                    $('#detail-customer-alamat').text('Gagal');
                }
            });
        });

        // ======================================
        // --- DATE FILTER / RESET ---
        // ======================================
        $('#applyDateFilter').on('click', function() {
            if(!currentCustomerId) return;

            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            if(!startDate || !endDate){
                alert('Mohon pilih Tanggal Mulai dan Tanggal Akhir.');
                return;
            }

            const $btn = $(this);
            $btn.prop('disabled',true).html('<span class="spinner-border spinner-border-sm"></span>');

            $('#dateFilterForm').hide();
            $('#activityReportContainer').html('<div class="text-center p-5"><span class="spinner-border text-primary"></span><p class="mt-2">Memuat data kegiatan...</p></div>').show();
            $('#resetFilterButtonContainer').hide();

            $.ajax({
                url: "{{ url('report/doctor/detail') }}/"+currentCustomerId,
                method:'GET',
                data:{ start_date: startDate, end_date: endDate },
                success:function(resp){
                    $btn.prop('disabled',false).html('<i class="fas fa-search"></i>');
                    const customerDetail = resp.data && resp.data.length>0 ? resp.data[0] : null;
                    const activities = customerDetail ? customerDetail.detail : [];
                    const htmlContent = createActivityTableHTML(activities);
                    $('#activityReportContainer').empty().append(htmlContent);
                    $('#resetFilterButtonContainer').show();
                    $('#resetDateFilter').on('click', resetToFilterForm);
                },
                error:function(){
                    $btn.prop('disabled',false).html('<i class="fas fa-search"></i>');
                    $('#activityReportContainer').empty().append('<div class="alert alert-danger p-3">Gagal mengambil data report kegiatan.</div>');
                    $('#resetFilterButtonContainer').show();
                    $('#resetDateFilter').on('click', resetToFilterForm);
                }
            });
        });

    }); // END document.ready
</script>
@endsection
