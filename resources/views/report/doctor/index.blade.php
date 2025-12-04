@extends('layouts.app')

@section('content')
{{-- Kontainer Utama dengan max-width 992px di desktop, lebar penuh di mobile --}}
<div class="container max-width-lg pb-5" style="background-color:#1e2227; min-height:100vh;">
    
    {{-- Header Utama: Pilihan Officer dan Navigasi dalam satu baris --}}
    <div class="header-section mb-2 pt-2"> 
        
        {{-- Flex container untuk menempatkan Pilih Officer dan Navigasi sejajar --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2">
            
            {{-- Tombol Dropdown Pilih Officer --}}
            <div class="dropdown officer-dropdown flex-shrink-0" id="officerDropdownContainer">
                <button id="btnSelectOfficer" class="btn btn-light fw-semibold py-2 rounded-pill shadow-sm flex-shrink-0 dropdown-toggle" 
                    type="button" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false"
                    style="min-width: 180px;"
                >
                    <i class="bi bi-person-circle me-2"></i> 
                    <span id="selectedOfficer" data-officer-id="" class="fw-bold">Pilih Officer</span> 
                    <i class="bi bi-chevron-down ms-1"></i>
                </button>

                {{-- Konten Dropdown Menu --}}
                <div class="dropdown-menu p-2 shadow-lg officer-list-menu" aria-labelledby="btnSelectOfficer">
                    
                    {{-- Input Pencarian --}}
                    <div class="px-2 pb-2">
                        <div class="input-group shadow-sm rounded-pill overflow-hidden">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchOfficer" class="form-control border-start-0" placeholder="Cari nama officer..." style="border: none;">
                        </div>
                    </div>
                    
                    {{-- Daftar Officer DINAMIS --}}
                    <ul id="officerList" class="list-group list-group-flush officer-list-container">
                        @forelse ($officers as $officer)
                            <li 
                                class="list-group-item list-group-item-action officer-item d-flex justify-content-between align-items-center" 
                                data-id="{{ $officer->officer }}" 
                                data-name="{{ $officer->officer }}"
                            >
                                <span class="fw-medium">{{ $officer->officer }}</span>
                                <i class="bi bi-arrow-right-circle text-primary"></i>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-danger">Data officer tidak ditemukan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Navigasi Kategori (Tab Navigasi Biasa) DITAMBAH Tombol LOGOUT --}}
            {{-- Menggunakan d-grid gap-2 untuk membuat tombol logout di sebelah kanan navigasi --}}
            <div class="d-flex flex-grow-1 gap-1">
                <div class="btn-group nav-tabs-mobile flex-grow-1" role="group">
                    <button type="button" class="btn btn-dark-outline fw-semibold nav-button" id="btnAgenda" disabled>
                        AGENDA
                    </button>
                    <button type="button" class="btn btn-dark-outline fw-semibold nav-button" id="btnMarket" disabled>
                        LIST MARKET
                    </button>
                    <button type="button" class="btn btn-dark-outline fw-semibold nav-button" id="btnBrowser" disabled>
                        BROWSER
                    </button>
                    <button type="button" class="btn btn-dark-outline fw-semibold nav-button" id="btnLaporan" disabled>
                        LAPORAN
                    </button>
                </div>
                
                {{-- DROPDOWN USER (MENGGANTIKAN TOMBOL LOGOUT) --}}
                <!--<div class="dropdown flex-shrink-0">-->
                <!--    <button class="btn btn-danger fw-semibold px-3 py-2 rounded-pill shadow-sm dropdown-toggle"-->
                <!--            type="button"-->
                <!--            id="dropdownUserMenu"-->
                <!--            data-bs-toggle="dropdown"-->
                <!--            aria-expanded="false">-->
                <!--        <i class="bi bi-person-circle me-1"></i>-->
                <!--        {{ Auth::user()->username ?? 'User' }}-->
                <!--    </button>-->
                
                <!--    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownUserMenu">-->
                <!--        <li>-->
                <!--            <a class="dropdown-item text-danger fw-semibold" href="#"-->
                <!--               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">-->
                <!--                <i class="bi bi-box-arrow-right me-2"></i> Logout-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->
                
                <!--    {{-- FORM LOGOUT --}}-->
                <!--    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">-->
                <!--        @csrf-->
                <!--    </form>-->
                <!--</div>-->
                <button type="button" id="btnLogout" class="btn btn-danger fw-semibold px-3 py-2 rounded-pill shadow-sm flex-shrink-0">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>

            </div>
        </div>
    </div>
    
    {{-- Area Konten (Card Utama) --}}
    <div class="card p-2 p-md-2 bg-dark-card shadow-lg rounded-2">
        <div id="contentArea" class="text-center text-muted">
            <p class="text-white">Pilih Officer Dahulu.</p>
            <p class="text-secondary m-0" style="font-size: 0.9rem;">Navigasi akan aktif setelah pemilihan.</p>
        </div>
    </div>
</div>

{{-- FORM LOGOUT TERSEMBUNYI --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>


<div class="modal fade" id="samplingModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black">Detail Sampling</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="samplingModalBody">
                Memuat...
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Pastikan FullCalendar JS dan CSS (termasuk locale 'id') sudah dimuat di layout utama!
const AGENDA_DATA_URL = '{{ route("report.doctor.agenda.data") }}'; 

document.addEventListener("DOMContentLoaded", function () {
    const btnSelectOfficer = document.getElementById("btnSelectOfficer");
    const officerDropdown = new bootstrap.Dropdown(btnSelectOfficer); 
    const selectedOfficerSpan = document.getElementById("selectedOfficer");
    const contentArea = document.getElementById("contentArea");
    const searchInput = document.getElementById("searchOfficer");
    const officerListContainer = document.getElementById("officerList");
    const navButtons = document.querySelectorAll(".nav-button");
    const btnLogout = document.getElementById("btnLogout");

    // --- Pencarian Officer ---
    searchInput.addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();
        const officerItems = officerListContainer.querySelectorAll(".officer-item"); 
        officerItems.forEach(item => {
            const name = item.textContent.toLowerCase();
            if (item.classList.contains('officer-item')) {
                item.style.display = name.includes(keyword) ? "flex" : "none";
            }
        });
    });

    // --- Klik Officer (Menggunakan Event Delegation) ---
    officerListContainer.addEventListener("click", function(event) {
        const item = event.target.closest(".list-group-item.officer-item");
        if (item) {
            const name = item.dataset.name;
            const id = item.dataset.id;
            
            // 1. Update Officer Terpilih
            selectedOfficerSpan.textContent = name;
            selectedOfficerSpan.dataset.officerId = id;
            
            officerDropdown.hide(); 

            // 2. Aktifkan Tombol Navigasi dan hapus highlight nav sebelumnya
            navButtons.forEach(btn => {
                btn.removeAttribute('disabled');
                btn.classList.remove('active-nav');
            });
            
            // 3. Update Area Konten (Reset)
            contentArea.innerHTML = `
                <div class="text-white mt-5">
                    <i class="bi bi-check-circle-fill fs-1 text-success mb-3"></i>
                    <h4>Officer <span class="text-info fw-bold">${name}</span> berhasil dipilih!</h4>
                    <p class="text-secondary">Silakan jelajahi data menggunakan Navigasi di atas.</p>
                </div>
            `;
            
            // 4. Highlight Officer Terpilih di dropdown
            officerListContainer.querySelectorAll(".officer-item").forEach(i => {
                i.classList.remove("active", "bg-primary", "text-white");
                const icon = i.querySelector('i');
                if (icon) {
                    icon.classList.replace('bi-check-circle-fill', 'bi-arrow-right-circle');
                }
            });
            item.classList.add("active", "bg-primary", "text-white");
            const itemIcon = item.querySelector('i');
            if (itemIcon) {
                itemIcon.classList.replace('bi-arrow-right-circle', 'bi-check-circle-fill'); 
            }
        }
    });

    // --- Penanganan Klik Tombol Navigasi ---
    navButtons.forEach(button => {
        button.addEventListener("click", function() {
            if (this.disabled) return; 

            const officerName = selectedOfficerSpan.textContent;
            const officerId = selectedOfficerSpan.dataset.officerId;
            const feature = this.textContent.trim();
            
            if (!officerId || officerName === 'Pilih Officer') {
                alert("Harap pilih Officer terlebih dahulu.");
                return;
            }

            // Highlight tombol yang aktif
            navButtons.forEach(btn => btn.classList.remove('active-nav'));
            this.classList.add('active-nav');
            
            // Tampilkan status loading sebelum memuat konten
            contentArea.innerHTML = `
                <div class="text-white mt-5">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h4>Memuat data ${feature} untuk <span class="text-info fw-bold">${officerName}</span>...</h4>
                    <p class="text-secondary">Tunggu sebentar...</p>
                </div>
            `;
            
            // Panggil fungsi pemuat konten utama
            loadContent(feature, officerId, contentArea);
        });
    });
    
    // --- Penanganan Klik Tombol Logout BARU ---
    if (btnLogout) {
        btnLogout.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });
    }

    // Inisialisasi: Pastikan tombol navigasi disabled saat pertama kali dimuat
    if (selectedOfficerSpan.dataset.officerId === "" || selectedOfficerSpan.textContent === 'Pilih Officer') {
        navButtons.forEach(btn => btn.setAttribute('disabled', ''));
    }
});

// =========================================================================
// === FUNGSI UTAMA UNTUK MEMUAT KONTEN DINAMIS (TERMASUK FULLCALENDAR) ===
// =========================================================================

function loadContent(feature, officerId, targetElement) {
    const featureName = feature.trim().toUpperCase();
    
    // ✅ PERBAIKAN: Ambil selectedOfficerSpan di dalam fungsi agar terdefinisi
    const selectedOfficerSpan = document.getElementById("selectedOfficer"); 
    
    // Jika tidak ditemukan (meski tidak mungkin jika kode HTML benar), gunakan officerId
    let officerNameDisplay = officerId;
    if (selectedOfficerSpan && selectedOfficerSpan.textContent) {
        officerNameDisplay = selectedOfficerSpan.textContent;
    }


    // Pastikan untuk menghapus elemen dinamis lama saat berpindah tab
    const oldStyle = document.getElementById('fullCalendarStyle');
    const oldScript = document.getElementById('fullCalendarScript');
    if (oldStyle) oldStyle.remove();
    if (oldScript) oldScript.remove();

    if (featureName === 'AGENDA') { 

        // --- 1. Style ---
        const agendaStyle = `
            /* ==================== FullCalendar Styles ==================== */
            #calendar { 
                max-width: 950px; 
                margin: -5px auto 10px auto; 
                font-size: 0.9rem;
            }
        
            .fc-toolbar { margin-bottom: 2px; }
            .fc-toolbar-title { font-size: 1rem; font-weight: 500; color: #f8f9fa; } 
        
            .fc-prev-button, .fc-next-button { 
                background-color: #0d6efd !important; 
                border: none !important; 
                padding: 5px 8px !important; 
                font-size: 0.8rem !important;
                border-radius: 6px !important;
            }
        
            .fc-col-header-cell-cushion { 
                padding: 2px 0; 
                font-size: 0.85rem; 
                color: #000; 
                background-color: #fff; 
            }
        
            .fc-daygrid-day { 
                padding: 1px !important; 
                cursor: pointer; 
                line-height: 1.2; 
                border: 1px solid #3e444b; 
            }
        
            .fc-daygrid-day-number { 
                font-size: 0.8rem; 
                padding: 4px; 
                color: #f8f9fa; 
                font-weight: 500; 
            }
        
            .fc-day-today { background-color: rgba(25, 135, 84, 0.4) !important; } 
            .fc-day-other { background-color: #24292f !important; } 
            .fc-day-other .fc-daygrid-day-number { visibility: hidden; }
        
            .fc-event { 
                padding: 0 4px !important; 
                margin: 1px 0 !important; 
                font-size: 0.7rem !important; 
                line-height: 1.2 !important; 
                height: 18px;
                border-radius: 4px !important;
                font-weight: 500;
            }
        
            /* ==================== Task Card Colors ==================== */
            .card-task {
                border-radius: 5px;
                padding: 10px 15px;
                margin-bottom: 5px;
                color: #f8f9fa;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                text-align: left;
            }
        
            .card-task.status-1 { background-color: #fff !important; color: #000; }
            .card-task.status-2 { background-color: #198754 !important; color: #fff; }
            .card-task.status-3 { background-color: #dc3545 !important; color: #fff !important; }
        
            .card-task p { margin: 0; font-size: 0.9rem; }
        
            /* ==================== Modal: Tinggi Selalu Stabil ==================== */
            #agendaModal .modal-dialog {
                max-width: 900px;
                width: 100%;
            }
        
            /* Modal harus fix-height agar tidak berubah-ubah */
            #agendaModal .modal-content {
                height: 78vh;
                max-height: 78vh;
                display: flex;
                flex-direction: column;
            }
        
            #agendaModal .modal-header {
                flex-shrink: 0;
            }
        
            /* Body tidak scroll – isi di dalamnya yang scroll */
            #agendaModal .modal-body {
                flex: 1;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                min-height: 0;
            }
        
            /* ==================== Tabs ==================== */
            .custom-agenda-tabs { 
                display: flex; 
                width: 100%; 
                border-bottom: 1px solid #444; 
                flex-shrink: 0;
            }
        
            .custom-agenda-tabs .nav-item { flex: 1; }
        
            .custom-agenda-tabs .nav-link { 
                width: 100%; 
                text-align: center; 
                padding: 10px 0 !important; 
                border-radius: 6px 6px 0 0 !important; 
                background-color: #1e1e1e; 
                color: #cfcfcf; 
                border: 1px solid #333 !important; 
                margin-right: 4px; 
                transition: background 0.15s, color 0.15s; 
                font-size: 0.9rem; 
            }
        
            .custom-agenda-tabs .nav-link:last-child { margin-right: 0; }
        
            .custom-agenda-tabs .nav-link.active { 
                background-color: #0d6efd !important; 
                color: #fff !important; 
                border-bottom: 2px solid #0a58ca !important; 
            }
        
            /* ==================== Empty Message ==================== */
            #no-agenda-per-tab {
                position: absolute;
                top: 30%;                        /* Tidak terlalu tengah agar tidak aneh */
                left: 50%;
                transform: translate(-50%, -50%);
            }
        
            #no-agenda-per-tab p {
                margin: 0;
                font-size: 0.95rem;
            }
        
            /* ==================== Task Layout ==================== */
            .task-container {
                flex: 1;
                display: flex;
                gap: 8px;
                min-height: 0;
            }
        
            .task-container .flex-grow-1 {
                display: flex;
                flex-direction: column;
                min-height: 0;
            }
        
            /* Area task ini yang scroll */
            #agenda-tasks {
                flex: 1;
                overflow-y: auto;
                min-height: 0;
            }
            
            #agendaModal .modal-footer {
                flex-shrink: 0;
            }
        
            /* ==================== Navigation Buttons ==================== */
            .nav-btn-wrapper {
                width: 52px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
        
            #modal-prev-btn,
            #modal-next-btn {
                min-width: 40px;
                height: 40px;
                border-radius: 6px;
                background-color: #0d6efd;
                color: #fff;
                border: none;
                font-size: 1rem;
                cursor: pointer;
            }
        `;
    
        // --- 2. DOM ---
        const agendaDom = `
        <div class="container-fluid px-3">
            <div id="calendar"></div>
        </div>
    
        <div class="modal fade" id="agendaModal" tabindex="-1" aria-labelledby="agendaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg position-relative">
                <div class="modal-content bg-dark-card border-0">
    
                    <div class="modal-header bg-primary text-white py-2">
                        <h6 class="modal-title">
                            Detail Agenda: <span id="current-modal-date"></span>
                        </h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
    
                    <div class="modal-body">
    
                        <!-- TAB MENU -->
                        <ul class="nav nav-tabs custom-agenda-tabs mb-3" id="agendaTabMenu">
                            <li class="nav-item"><button class="nav-link active" data-type="0">Agenda</button></li>
                            <li class="nav-item"><button class="nav-link" data-type="1">Tagihan</button></li>
                            <li class="nav-item"><button class="nav-link" data-type="2">Sampling</button></li>
                        </ul>
    
                        <!-- TASK LIST -->
                        <div class="task-container">
                            <div class="nav-btn-wrapper"><button id="modal-prev-btn">&lt;</button></div>
                        
                            <div class="flex-grow-1 position-relative">
                                <div id="agenda-tasks"></div>
                        
                                <!-- NO AGENDA moved inside task-container -->
                                <div id="no-agenda-per-tab" class="text-center py-2 d-none position-absolute top-50 start-50 translate-middle">
                                    <p class="lead fw-bold" style="color: #ffffff; text-shadow: 0 0 4px rgba(0,0,0,0.7);">
                                        Tidak ada data agenda.
                                    </p>
                                </div>
                            </div>
                        
                            <div class="nav-btn-wrapper"><button id="modal-next-btn">&gt;</button></div>
                        </div>
    
                        <div id="pagination-wrapper" class="text-center mt-1"></div>
    
                    </div>
                </div>
            </div>
        </div>
        `;
    
        // --- 3. Script ---
        const agendaScript = `
        (function() {
            const OFFICER_PARAM = "${officerId}";
            const calendarEl = document.getElementById('calendar');
            let currentModalDate = null;
            let activeTypeAgenda = 0;
    
            let currentPage = 1;
            const itemsPerPage = 10;
            let paginatedTasks = [];
    
            const modalElement = document.getElementById('agendaModal');
            const agendaModalInstance = new bootstrap.Modal(modalElement);
    
            function formatIndonesianDate(date) {
                return date.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
            }
    
            function formatIndonesianDateTime(dateStr) {
                if (!dateStr) return 'Waktu tidak tersedia';
            
                const d = new Date(dateStr); // ISO+offset akan terbaca benar
            
                const weekday = d.toLocaleDateString('id-ID', { weekday: 'long' });
                const day     = String(d.getDate()).padStart(2,'0');
                const month   = d.toLocaleDateString('id-ID', { month: 'long' });
                const year    = d.getFullYear();
                const hours   = String(d.getHours()).padStart(2,'0');
                const minutes = String(d.getMinutes()).padStart(2,'0');
                const seconds = String(d.getSeconds()).padStart(2,'0');
            
                return weekday + ', ' + day + ' ' + month + ' ' + year + ' ' + hours + ':' + minutes + ':' + seconds;
            }

            function formatDateToISO(date) {
                const d = new Date(date);
                d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
                return d.toISOString().split('T')[0];
            }
    
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                themeSystem: 'bootstrap5',
                locale: 'id',
                firstDay: 1,
                showNonCurrentDates: false,
                headerToolbar: { left: 'prev', center: 'title', right: 'next' },
                events: {
                    url: AGENDA_DATA_URL,
                    extraParams: () => ({ officer: OFFICER_PARAM }),
                    failure: () => alert('Gagal memuat data agenda.')
                },
                dateClick: function(info) {
                    if (info.dayEl.classList.contains('fc-day-other')) return;
                    currentModalDate = info.date;
                    showAgendaModal(currentModalDate, calendar);
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    currentModalDate = new Date(info.event.start);
                    showAgendaModal(currentModalDate, calendar);
                }
            });
    
            calendar.render();
    
            document.querySelectorAll('#agendaTabMenu .nav-link').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('#agendaTabMenu .nav-link').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    activeTypeAgenda = this.getAttribute('data-type');
                    if (currentModalDate) showAgendaModal(currentModalDate, calendar);
                });
            });
    
            function showAgendaModal(dateObject, calendarInstance) {
                currentModalDate = dateObject;
                const formattedDate = formatDateToISO(dateObject);
                const displayDate = formatIndonesianDate(dateObject);
                document.getElementById('current-modal-date').innerText = displayDate;
    
                const events = calendarInstance.getEvents().filter(event => {
                    if (!event.start) return false;
                    return formatDateToISO(new Date(event.start)) === formattedDate;
                });
    
                const taskContainer = document.getElementById('agenda-tasks');
                const noAgendaPerTab = document.getElementById('no-agenda-per-tab');
    
                taskContainer.innerHTML = '';
                noAgendaPerTab.classList.add('d-none');
    
                let collectedTasks = [];
                events.forEach(event => {
                    const props = event.extendedProps || {};
                    const listTask = props.keterangan_task || [];
                    listTask.forEach(task => {
                        if (String(task.type_agenda) === String(activeTypeAgenda)) 
                            collectedTasks.push(task);
                    });
                });
    
                paginatedTasks = collectedTasks;
                currentPage = 1;
    
                renderPaginatedTasks();
    
                if (!modalElement.classList.contains('show')) agendaModalInstance.show();
            }
    
            function renderPaginatedTasks() {
                const taskContainer = document.getElementById('agenda-tasks');
                const noAgendaPerTab = document.getElementById('no-agenda-per-tab');
            
                taskContainer.innerHTML = '';
            
                if (paginatedTasks.length === 0) {
                    noAgendaPerTab.classList.remove('d-none');
                    document.getElementById('pagination-wrapper').innerHTML = '';
                    return;
                }
            
                noAgendaPerTab.classList.add('d-none');
            
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const sliced = paginatedTasks.slice(start, end);
            
                let number = start + 1;
            
                sliced.forEach(task => {
                    const card = document.createElement('div');
                    card.classList.add('card-task', 'status-' + task.status);
            
                    const createdAt = task.created_at ? formatIndonesianDateTime(task.created_at) : 'Waktu tidak tersedia';
            
                    const p = document.createElement('p');
                    p.className = 'fw-medium';
                    p.setAttribute('title', createdAt);
            
                    const strong = document.createElement('strong');
                    strong.textContent = number + '. ';
            
                    p.appendChild(strong);
                    p.appendChild(document.createTextNode(task.keterangan || ''));
                    card.appendChild(p);
            
                    taskContainer.appendChild(card);
            
                    setTimeout(() => { try { new bootstrap.Tooltip(p); } catch(err){} }, 20);
            
                    number++;
                });
            
                renderPaginationButtons();
            }

    
            function renderPaginationButtons() {
                const wrapper = document.getElementById('pagination-wrapper');
                const totalPages = Math.ceil(paginatedTasks.length / itemsPerPage);
            
                if (totalPages <= 1) {
                    wrapper.innerHTML = '';
                    return;
                }
            
                // Gunakan string biasa + konkatenasi (hindari backtick di dalam agendaScript yang juga dipakai backtick)
                wrapper.innerHTML =
                    '<button class="btn btn-sm btn-primary me-2" id="page-prev" ' + (currentPage === 1 ? 'disabled' : '') + '>Prev</button>' +
                    '<span class="text-white">' + currentPage + ' / ' + totalPages + '</span>' +
                    '<button class="btn btn-sm btn-primary ms-2" id="page-next" ' + (currentPage === totalPages ? 'disabled' : '') + '>Next</button>';
            
                var prevBtn = document.getElementById('page-prev');
                var nextBtn = document.getElementById('page-next');
            
                if (prevBtn) {
                    prevBtn.onclick = function() {
                        if (currentPage > 1) {
                            currentPage--;
                            renderPaginatedTasks();
                        }
                    };
                }
            
                if (nextBtn) {
                    nextBtn.onclick = function() {
                        if (currentPage < totalPages) {
                            currentPage++;
                            renderPaginatedTasks();
                        }
                    };
                }
            }
    
    
            document.getElementById('modal-prev-btn').addEventListener('click', () => {
                if (currentModalDate) {
                    const d = new Date(currentModalDate);
                    d.setDate(d.getDate() - 1);
                    showAgendaModal(d, calendar);
                }
            });
            document.getElementById('modal-next-btn').addEventListener('click', () => {
                if (currentModalDate) {
                    const d = new Date(currentModalDate);
                    d.setDate(d.getDate() + 1);
                    showAgendaModal(d, calendar);
                }
            });
    
        })();
        `;
    
        // --- 4. Render DOM dan Style ---
        const styleElement = document.createElement('style');
        styleElement.id = 'fullCalendarStyle';
        styleElement.textContent = agendaStyle;
        document.head.appendChild(styleElement);
    
        targetElement.innerHTML = agendaDom;
    
        const newScript = document.createElement('script');
        newScript.id = 'fullCalendarScript'; 
        newScript.textContent = agendaScript;
        document.body.appendChild(newScript); 
    } else if (featureName === 'LIST MARKET') {
        const officerId = selectedOfficerSpan.dataset.officerId;
        const officerName = selectedOfficerSpan.textContent.trim() || 'Officer Tertentu';
    
        const zones = [
            "JABODETABEK",
            "JABAR",
            "JATENG - JATIM",
            "SUMATERA",
            "BALI - KALIMANTAN - SULAWESI"
        ];
    
        const provinces = @json($provinces ?? []);
        const cities = @json($cities ?? []);
    
        // --- Opsi Zona, Provinsi, Kota
        let zoneOptions = `<option value="">Semua Zona</option>`;
        zones.forEach(z => zoneOptions += `<option value="${z}">${z}</option>`);
    
        let provinceOptions = `<option value="">Semua Provinsi</option>`;
        provinces.forEach(p => provinceOptions += `<option value="${p}">${p}</option>`);
    
        let cityOptions = `<option value="">Semua Kota</option>`;
        cities.forEach(c => cityOptions += `<option value="${c}">${c}</option>`);
    
        // --- Render Struktur HTML
        targetElement.innerHTML = `
            <div class="d-flex flex-wrap align-items-center">
                <h6 class="text-white mb-0 me-2">List Nasional :</h6>
                <div class="btn-group">
                    <a href="{{ route('master.customer_prospek.export_pdf') }}" target="_blank" 
                       class="btn btn-danger btn-sm rounded-pill">
                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                    </a>
                    <a href="{{ route('report.doctor.filedoctor.marketListExcel') }}" target="_blank" 
                       class="btn btn-success btn-sm rounded-pill ms-1">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel
                    </a>
                    <a href="{{ route('report.doctor.filedoctor.marketListExcel2') }}" target="_blank" 
                       class="btn btn-success btn-sm rounded-pill ms-1">
                        <i class="bi bi-file-earmark-excel me-1"></i> Excel 2
                    </a>
                </div>
            </div>
    
            <hr class="my-1" style="margin-top:2px; margin-bottom:4px;"/>
    
            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                <h6 class="text-white mb-0 me-2">Officer :</h6>
                <form id="marketFilterForm" 
                      class="d-flex flex-nowrap align-items-center gap-2 flex-grow-1 mb-0"
                      style="margin-top:-3px;">
                    <select class="form-select form-select-sm bg-dark-input text-dark border-secondary" 
                            id="zoneFilter" name="zona" style="width: 150px;">
                        ${zoneOptions}
                    </select>
    
                    <select class="form-select form-select-sm bg-dark-input text-dark border-secondary" 
                            id="provinceFilter" name="provinsi" style="width: 150px;">
                        ${provinceOptions}
                    </select>
    
                    <select class="form-select form-select-sm bg-dark-input text-dark border-secondary" 
                            id="cityFilter" name="kota" style="width: 150px;">
                        ${cityOptions}
                    </select>
    
                    <button type="submit" id="btnFilter" class="btn btn-sm btn-primary rounded-pill">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <button type="button" id="btnDownloadProv" class="btn btn-sm btn-warning rounded-pill">
                        <i class="bi bi-download"></i>
                    </button>
                </form>
            </div>
    
            <hr class="my-1" style="margin-top:2px; margin-bottom:3px;"/>
    
            <div id="pdfActionArea" class="mt-1" style="margin-top:0!important;">
                <div class="alert alert-info text-center py-1 mb-0">
                    Silakan gunakan filter untuk melihat List Market berdasarkan Officer.
                </div>
            </div>
        `;
    
        // ----------------------------------------------------------------------------------
        // START: LOGIC JAVASCRIPT
        // ----------------------------------------------------------------------------------
    
        function base64UrlEncode(str) {
            return btoa(str).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
        }
    
        // --- Event Listener Tombol Download Zoning
        function updateDownloadProvUrl() {
            const officerId = selectedOfficerSpan.dataset.officerId;
            if (officerId) {
                const zona = document.getElementById('zoneFilter').value.trim().toUpperCase();
                const prov = document.getElementById('provinceFilter').value.trim().toUpperCase();
                const kota = document.getElementById('cityFilter').value.trim().toUpperCase();
                const pdfUrl = `{{ route('report.doctor.filedoctor.marketListPdf') }}?officer=${officerId}&zona=${zona}&provinsi=${prov}&kota=${kota}`;
                $('#btnDownloadProv').data('pdf-url', pdfUrl); 
            }
        }
    
        updateDownloadProvUrl();
        document.getElementById('marketFilterForm').addEventListener('change', updateDownloadProvUrl);
    
        $(document).on('click', '#btnDownloadProv', function (e) {
            e.preventDefault();
            const pdfUrl = $(this).data('pdf-url');
            if (!pdfUrl) return alert('Silakan pilih officer terlebih dahulu.');
            window.open(pdfUrl, '_blank');
        });
    
        // --- Event Listener Tombol Filter
        document.getElementById('marketFilterForm').addEventListener('submit', function(e){
            e.preventDefault();
    
            const officerId = selectedOfficerSpan.dataset.officerId;
            const zona = document.getElementById('zoneFilter').value.trim().toUpperCase();
            const prov = document.getElementById('provinceFilter').value.trim().toUpperCase();
            const kota = document.getElementById('cityFilter').value.trim().toUpperCase();
            const actionArea = document.getElementById('pdfActionArea');
    
            actionArea.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-light"></div></div>`;
    
            fetch(`{{ route('report.doctor.getListMarket') }}?officer=${officerId}&zona=${zona}&provinsi=${prov}&kota=${kota}`)
            .then(res => res.json())
            .then(response => {
                let allData = [...response.existing, ...response.prospek];
    
                // --- Sorting data
                allData.sort((a, b) => {
                    const kategoriCompare = (a.kategori ?? '').localeCompare(b.kategori ?? '');
                    if(kategoriCompare !== 0) return kategoriCompare;
    
                    const provCompare = (a.text_provinsi ?? '').localeCompare(b.text_provinsi ?? '');
                    if(provCompare !== 0) return provCompare;
    
                    const kotaCompare = (a.text_kota ?? '').localeCompare(b.text_kota ?? '');
                    if(kotaCompare !== 0) return kotaCompare;
    
                    return (a.name ?? '').localeCompare(b.name ?? '');
                });
    
                if(allData.length === 0){
                    actionArea.innerHTML = `<div class="alert alert-warning text-center mt-3">Tidak ditemukan data List Market untuk filter ini.</div>`;
                    return;
                }
    
                // --- Grup Data berdasarkan Kategori
                const groupedData = allData.reduce((acc, curr) => {
                    const category = curr.kategori || 'Tanpa Kategori';
                    if (!acc[category]) acc[category] = [];
                    acc[category].push(curr);
                    return acc;
                }, {});
    
                // --- Render Card + Table langsung per kategori
                let combinedHtml = ``;
    
                // Urutan kategori yang diinginkan
                const kategoriOrder = [
                    'Umum (semua prospek)',
                    'Agen - perfumery trusted',
                    'Smreseller',
                    'Bigreseller',
                    'Smperfumery',
                    'Bigperfumery',
                    'Umum project (semua prospek)',
                    'Home industri pkrt',
                    'Home industri kosmetik',
                    'Industri pkrt (PPN)',
                    'Industri kosmetik (PPN)'
                ];
    
                const kategoriKeys = Object.keys(groupedData);
    
                // Urutkan berdasarkan custom order
                kategoriKeys.sort((a, b) => {
                    const indexA = kategoriOrder.findIndex(k => k.toLowerCase() === a.toLowerCase());
                    const indexB = kategoriOrder.findIndex(k => k.toLowerCase() === b.toLowerCase());
                    if (indexA === -1 && indexB === -1) return a.localeCompare(b);
                    if (indexA === -1) return 1;
                    if (indexB === -1) return -1;
                    return indexA - indexB;
                });
    
                // Render tabel
                kategoriKeys.forEach(kategori => {
                    const dataArray = groupedData[kategori];
                    const totalCount = dataArray.length;
                    const cardId = 'card-' + kategori.replace(/\s/g, '_');
    
                    combinedHtml += `
                        <div class="mb-3">
                            <div class="card bg-white border-primary shadow-sm cursor-pointer"
                                 id="${cardId}"
                                 data-bs-toggle="collapse"
                                 data-bs-target="#collapse-${cardId}"
                                 aria-expanded="false"
                                 aria-controls="collapse-${cardId}">
                                <div class="card-body py-2 d-flex justify-content-between align-items-center">
                                    <h6 class="card-title text-primary mb-0">${kategori}</h6>
                                    <p class="card-text mb-0">Total Data: <strong>${totalCount}</strong></p>
                                </div>
                            </div>
    
                            <div class="collapse mt-2" id="collapse-${cardId}">
                                <div class="card card-body bg-white">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr style="font-size: 0.9em;">
                                                    <th style="width: 20px;">#</th>
                                                    <th style="width: 120px;">PROVINSI</th>
                                                    <th style="width: 120px;">KOTA</th>
                                                    <th style="width: 180px;">MAPPING</th>
                                                    <th style="width: 200px;">NAMA</th>
                                                    <th style="width: 80px;">SOURCE</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 0.8em;">
                    `;
    
                    dataArray.forEach((c, index) => {
                        const rowClass = c.status?.toLowerCase() === 'existing' ? 'row-existing' :
                                         c.status?.toLowerCase() === 'prospek' ? 'row-prospek' : '';
                        const customerId = c.customer_id ?? c.id;
                        const encodedId = base64UrlEncode(customerId);
                        const officer = encodeURIComponent(officerId);
    
                        combinedHtml += `
                            <tr class="${rowClass}">
                                <td>${index + 1}</td>
                                <td>${c.text_provinsi ?? '-'}</td>
                                <td>${c.text_kota ?? '-'}</td>
                                <td>${c.kategori ?? '-'}</td>
                                <td><a href="#" class="open-customer" data-customer-id="${encodedId}" data-officer="${officer}">${c.name ?? '-'}</a></td>
                                <td>${c.pengajuan ?? '-'}</td>
                            </tr>
                        `;
                    });
    
                    combinedHtml += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
    
                actionArea.innerHTML = combinedHtml;
    
                // --- Pasang event delegation untuk link customer
                actionArea.addEventListener('click', function(e){
                    const target = e.target;
                    if(target && target.classList.contains('open-customer')){
                        e.preventDefault();
                        const encodedId = target.dataset.customerId;
                        const officer = target.dataset.officer;
                        const url = `https://sys-af.lsfragrance.id/file-doctor/customer-one-time/${encodedId}?officer=${officer}`;
                        window.open(url, '_blank');
                    }
                });

            })
            .catch(err=>{
                console.error(err);
                actionArea.innerHTML = `<div class="alert alert-danger text-center mt-3">Terjadi kesalahan saat memuat data.</div>`;
            });
        });
    
        // --- Event: Filter Zona -> Provinsi -> Kota
        document.getElementById('zoneFilter').addEventListener('change', function(){
            const zona = this.value;
            const provSelect = document.getElementById('provinceFilter');
            const citySelect = document.getElementById('cityFilter');
    
            provSelect.innerHTML = `<option value="">Memuat provinsi...</option>`;
            citySelect.innerHTML = `<option value="">Semua Kota</option>`;
    
            if(!zona){
                provSelect.innerHTML = `<option value="">Semua Provinsi</option>`;
                @json($provinces ?? []).forEach(p => {
                    provSelect.innerHTML += `<option value="${p}">${p}</option>`;
                });
                return;
            }
    
            fetch(`{{ route('report.doctor.getProvinsiByZona') }}?zona=${encodeURIComponent(zona)}`)
            .then(res=>res.json())
            .then(data=>{
                provSelect.innerHTML = `<option value="">Semua Provinsi</option>`;
                data.forEach(p => provSelect.innerHTML += `<option value="${p}">${p}</option>`);
            }).catch(err=>{
                console.error(err);
                provSelect.innerHTML = `<option value="">Gagal memuat data</option>`;
            });
        });
    
        document.getElementById('provinceFilter').addEventListener('change', function(){
            const provinsi = this.value;
            const zona = document.getElementById('zoneFilter').value;
            const citySelect = document.getElementById('cityFilter');
    
            citySelect.innerHTML = `<option value="">Memuat kota...</option>`;
    
            if(!provinsi || !zona){
                citySelect.innerHTML = `<option value="">Semua Kota</option>`;
                return;
            }
    
            fetch(`{{ route('report.doctor.getKotaByProvinsi') }}?zona=${encodeURIComponent(zona)}&provinsi=${encodeURIComponent(provinsi)}`)
            .then(res=>res.json())
            .then(data=>{
                citySelect.innerHTML = `<option value="">Semua Kota</option>`;
                data.forEach(k => citySelect.innerHTML += `<option value="${k}">${k}</option>`);
            }).catch(err=>{
                console.error(err);
                citySelect.innerHTML = `<option value="">Gagal memuat data</option>`;
            });
        });
    }  else if (featureName === 'BROWSER') {
        contentArea.innerHTML = `
        <div class="container-fluid px-3">
            <div class="row">
                <!-- Frame A: Sidebar Kiri -->
                <div class="col-12 col-md-3">
                    <div class="p-3 bg-white border rounded d-flex flex-column" style="min-height:80vh;">
                        <div class="mb-3">
                            <div class="input-group input-group-sm">
                                <input type="text" id="dateRangeInput" class="form-control small-date-text" placeholder="Pilih rentang tanggal..." readonly>
                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#dateModal">
                                    <i class="bi bi-calendar-date"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm review-btn text-start" data-type="agenda" style="font-size:0.90em; padding-top:0.25rem; padding-bottom:0.25rem;">Agenda</button>
                            <button type="button" class="btn btn-outline-primary btn-sm review-btn text-start" data-type="kegiatan" style="font-size:0.90em; padding-top:0.25rem; padding-bottom:0.25rem;">Kegiatan</button>
                            <button type="button" class="btn btn-outline-primary btn-sm review-btn text-start" data-type="sampling_quotation" style="font-size:0.90em; padding-top:0.25rem; padding-bottom:0.25rem;">Sampling & Quotation</button>
                            <button type="button" class="btn btn-outline-primary btn-sm review-btn text-start" data-type="customer" style="font-size:0.90em; padding-top:0.25rem; padding-bottom:0.25rem;">Customer</button>
                        </div>
                    </div>
                </div>
    
                <!-- Frame B: Konten Kanan -->
                <div class="col-12 col-md-9">
                    <div class="p-1 bg-white border rounded h-100 d-flex flex-column shadow-sm" style="min-height:80vh;">
                        
                        <!-- Header -->
                        <div id="reviewCaption" class="mb-1 flex-shrink-1" style="font-size: 0.90em;">
                            <div class="fw-semibold text-primary" id="reviewHeaderTitle"></div>
                            <div class="text-muted fst-italic small text-center" id="reviewHeaderHint">
                                Silakan tentukan tanggal dan pilih salah satu menu di kiri untuk menampilkan data.
                            </div>
                        </div>

                        <!-- Bagian konten scroll: fix 70vh -->
                        <div id="reviewCardsWrapper"
                            class="flex-grow-1"
                            style="overflow-y:auto; height:50vh; padding-right:4px; padding-left:4px;">
                            <div id="reviewContent"></div>
                        </div>

                        <!-- Pagination -->
                        <div id="reviewPagination"
                            class="d-flex justify-content-center align-items-center gap-1 p-2 border-top"
                            style="position: sticky; bottom: 0; background:#fff; z-index:50;">
                        </div>

                    </div>
                </div>

            </div>
        </div>
    
        <!-- Modal Pilih Tanggal -->
        <div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="dateModalLabel">Pilih Tanggal</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="modalStartDate" class="form-label text-start d-block">Start</label>
                            <input type="date" class="form-control" id="modalStartDate">
                        </div>
                        <div class="mb-2">
                            <label for="modalEndDate" class="form-label text-start d-block">End</label>
                            <input type="date" class="form-control" id="modalEndDate">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnApplyDate" class="btn btn-primary">OK</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    
    
        <!-- Modal Detail -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="detailModalLabel">Detail</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="detailModalBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        `;
    
        // --- Format Tanggal DD MMM YY ---
        function formatDateDisplay(dateStr){
            const months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            const d = new Date(dateStr);
            const day = d.getDate();
            const month = months[d.getMonth()];
            const year = String(d.getFullYear()).slice(-2);
            return `${day} ${month} ${year}`;
        }
    
        // --- Set default tanggal 7 hari terakhir ---
        const today = new Date();
        const oneWeekAgo = new Date(today);
        oneWeekAgo.setDate(today.getDate() - 7);
    
        document.getElementById('modalStartDate').value = oneWeekAgo.toISOString().split('T')[0];
        document.getElementById('modalEndDate').value = today.toISOString().split('T')[0];
        document.getElementById('dateRangeInput').value = `${formatDateDisplay(oneWeekAgo.toISOString().split('T')[0])} - ${formatDateDisplay(today.toISOString().split('T')[0])}`;
    
        // --- Apply Tanggal ---
        document.getElementById('btnApplyDate').addEventListener('click', ()=>{
            const startISO = document.getElementById('modalStartDate').value;
            const endISO = document.getElementById('modalEndDate').value;
            document.getElementById('dateRangeInput').value = `${formatDateDisplay(startISO)} - ${formatDateDisplay(endISO)}`;
    
            bootstrap.Modal.getInstance(document.getElementById('dateModal')).hide();
    
            const activeBtn = document.querySelector('.review-btn.active') || document.querySelector('.review-btn[data-type="kegiatan"]');
            if(activeBtn) activeBtn.click();
        });
    
        // --- Handle Menu Buttons ---
        document.querySelectorAll('.review-btn').forEach(btn=>{
            btn.addEventListener('click', ()=>{
                document.querySelectorAll('.review-btn').forEach(b=>b.classList.remove('active'));
                btn.classList.add('active');
                const type = btn.dataset.type;
                
                
                if(type==='agenda') loadAgenda();
                else if(type==='kegiatan') loadKegiatan();
                else if(type==='sampling_quotation') loadSamplingQuotation();
                else if(type==='customer') loadCustomer();
            });
        });
    
        // --- Load pertama kali Kegiatan setelah semua listener siap ---
        setTimeout(()=> {
            const firstBtn = document.querySelector('.review-btn[data-type="agenda"]');
            if(firstBtn) firstBtn.click();
        }, 100);
        
        // --- Fungsi Load Agenda ---
        function loadAgenda() {
            const frameB       = document.getElementById('reviewContent');
            const startDate    = document.getElementById('modalStartDate').value;
            const endDate      = document.getElementById('modalEndDate').value;
            const selectedOfficer = document.getElementById('selectedOfficer')?.dataset.officerId || '';
        
            // loading (boleh center)
            frameB.innerHTML = `<div class="py-3 text-primary text-center">Memuat Agenda...</div>`;
        
            fetch(`/report/doctor/calendar-data?officer=${selectedOfficer}&start=${startDate}&end=${endDate}`)
                .then(res => res.json())
                .then(result => {
        
                    if (!result.success || result.data.length === 0) {
                        frameB.innerHTML = `<div class="py-3 text-muted text-center">Tidak ada data.</div>`;
                        return;
                    }
                    
                    renderAgenda(result.data);
                })
                .catch(err => {
                    console.error(err);
                    frameB.innerHTML = `<div class="py-3 text-danger text-center">Gagal memuat data agenda.</div>`;
                });
                
            document.getElementById('reviewHeaderHint').textContent = '';
            document.getElementById('reviewHeaderTitle').classList.add('text-start');
            document.getElementById('reviewHeaderHint').classList.add('text-start');
        }
        
        function loadAgendaFiltered() {
            const frameB       = document.getElementById('reviewContent');
            const startDate    = document.getElementById('modalStartDate').value;
            const endDate      = document.getElementById('modalEndDate').value;
            const selectedOfficer = document.getElementById('selectedOfficer')?.dataset.officerId || '';
        
            frameB.innerHTML = `<div class="py-3 text-primary text-center">Memuat Agenda...</div>`;
        
            fetch(`/report/doctor/calendar-data?officer=${selectedOfficer}&start=${startDate}&end=${endDate}`)
                .then(res => res.json())
                .then(result => {
        
                    if (!result.success || result.data.length === 0) {
                        frameB.innerHTML = `<div class="py-3 text-muted text-center">Tidak ada data.</div>`;
                        return;
                    }
        
                    const activePIC = Array.from(
                        document.querySelectorAll('#agendaFilter input[type="checkbox"]:checked')
                    ).map(cb => cb.value.toLowerCase());
        
                    const filtered = result.data.filter(i =>
                        activePIC.includes((i.pic_key || "").toLowerCase())
                    );
        
                    if (filtered.length === 0) {
                        frameB.innerHTML = `<div class="py-3 text-muted text-center">Tidak ada agenda sesuai filter.</div>`;
                        return;
                    }
        
                    renderAgenda(filtered);
                })
                .catch(err => {
                    console.error(err);
                    frameB.innerHTML = `<div class="py-3 text-danger text-center">Gagal memuat data agenda.</div>`;
                });
        }
        
        function formatCreatedAtToWIB(dateStr) {
            if (!dateStr) return '';
        
            const d = new Date(dateStr);
        
            const options = {
                weekday: 'long',
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            };
        
            let formatted = d.toLocaleString('id-ID', options);
        
            // Hilangkan kata "pukul" jika muncul
            formatted = formatted.replace(/pukul\s*/i, '');
        
            // Ubah 13.47.47 → 13:47:47
            formatted = formatted.replace(/(\d{2})\.(\d{2})\.(\d{2})$/, '$1:$2:$3');
        
            return formatted;
        }
        
        function formatDateWithDay(dateStr) {
            if (!dateStr) return '';
        
            const d = new Date(dateStr);
        
            const options = {
                weekday: 'long',
                day: '2-digit',
                month: 'short',
                year: '2-digit',
                timeZone: 'Asia/Jakarta'
            };
        
            let formatted = d.toLocaleDateString('id-ID', options);
        
            // Hilangkan titik pada bulan, contoh "Nov." → "Nov"
            formatted = formatted.replace('.', '');
        
            return formatted;
        }
        
        function renderAgenda(data) {
            const frameB = document.getElementById('reviewContent');
            const startDate = document.getElementById('modalStartDate')?.value || '';
            const endDate = document.getElementById('modalEndDate')?.value || '';
            const selectedOfficer = document.getElementById('selectedOfficer')?.dataset.officerId || '';

            // --- Header sticky ---
            const reviewHeaderTitle = document.getElementById('reviewHeaderTitle');
            reviewHeaderTitle.innerHTML = `
                <div class="d-flex justify-content-between align-items-start bg-white px-2 border-bottom shadow-sm"
                    style="position: sticky; top: 0; z-index: 20;">
                    <div class="small fw-semibold text-start">
                        Periode: <span class="text-primary">${formatDateDisplay(startDate)} s/d ${formatDateDisplay(endDate)}</span>
                        <br>Officer: <span class="text-dark fw-bold">${selectedOfficer.toUpperCase()}</span>
                    </div>
                    <div>
                        <a href="/report/doctor/pdf-all?officer=${selectedOfficer}&start=${startDate}&end=${endDate}"
                        target="_blank"
                        class="btn btn-danger btn-sm px-3">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF ALL
                        </a>
                    </div>
                </div>
            `;

            frameB.innerHTML = "";

            // --- Group data by tanggal ---
            const grouped = {};
            data.forEach(item => {
                const tgl = item.tanggal || 'unknown';
                if (!grouped[tgl]) grouped[tgl] = [];
                grouped[tgl].push(item);
            });

            const sortedDates = Object.keys(grouped).sort((a, b) => new Date(a) - new Date(b));

            // =========================================================
            //         DYNAMIC PAGINATION BASED ON TEXT LENGTH
            // =========================================================

            const frameWidth = frameB.clientWidth;
            const maxHeightPerPage = window.innerHeight * 0.70;  // 70vh
            const pages = [];
            let currentPage = [];
            let currentHeight = 0;

            // --- Helper: Estimasi tinggi satu card agenda ---
            function estimateAgendaHeight(items) {
                let totalChar = 0;

                items.forEach(i => {
                    if (i.tasks) {
                        i.tasks.forEach(t => {
                            totalChar += (t.keterangan || "").length;
                        });
                    }
                });

                // Formula estimasi
                const charHeight = Math.ceil(totalChar / 35) * 18;
                const baseCard = 80;
                return baseCard + charHeight;
            }

            // --- Bangun halaman berdasarkan batas tinggi ---
            sortedDates.forEach(tgl => {
                const estHeight = estimateAgendaHeight(grouped[tgl]);

                if (currentHeight + estHeight > maxHeightPerPage) {
                    pages.push(currentPage);
                    currentPage = [tgl];
                    currentHeight = estHeight;
                } else {
                    currentPage.push(tgl);
                    currentHeight += estHeight;
                }
            });

            if (currentPage.length > 0) pages.push(currentPage);

            let currentPageIndex = 0;

            // =========================================================
            //         RENDER HALAMAN
            // =========================================================
            function renderPage(idx) {
                frameB.querySelectorAll('.agenda-page').forEach(e => e.remove());

                const pageDates = pages[idx];

                pageDates.forEach(tanggal => {
                    let html = `
                        <div class="agenda-page card mb-2 shadow-sm bg-white">
                            <div class="card-header fw-semibold d-flex justify-content-between align-items-center small">
                                <span>${formatDateWithDay(tanggal)}</span>
                                <a class="btn btn-outline-danger btn-sm" target="_blank" href="/report/doctor/pdf-date/${tanggal}?officer=${selectedOfficer}">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                                </a>
                            </div>
                            <div class="card-body p-2 text-start">
                    `;

                    const picGroups = {};
                    grouped[tanggal].forEach(item => {
                        const pic = item.pic_key || 'Tanpa PIC';
                        if (!picGroups[pic]) picGroups[pic] = [];
                        picGroups[pic].push(item);
                    });

                    Object.keys(picGroups).forEach(picKey => {
                        picGroups[picKey].forEach(agenda => {
                            html += `<div class="mb-2 pb-2 border-bottom text-start">`;

                            if (agenda.tasks?.length) {
                                html += `<ul class="small ps-3 mt-1 text-start">`;
                                agenda.tasks.forEach(t => {
                                    const createdAt = formatCreatedAtToWIB(t.created_at);
                                    const status = Number(t.status);
                                    let color = status === 3 ? 'red' : status === 2 ? 'green' : 'black';
                                    html += `
                                        <li style="color:${color}">
                                            <span class="fw-semibold">${t.keterangan}</span>
                                            <br><small class="text-muted">${createdAt}</small>
                                        </li>`;
                                });
                                html += `</ul>`;
                            } else {
                                html += `<div class="small text-muted">Tidak ada task.</div>`;
                            }

                            html += `</div>`;
                        });
                    });

                    html += `</div></div>`;
                    frameB.insertAdjacentHTML('beforeend', html);
                });

                renderPagination();
            }

            // =========================================================
            //         PAGINATION BUTTON
            // =========================================================
            function renderPagination() {
                const pag = document.getElementById('reviewPagination');
                pag.innerHTML = "";  // bersihkan

                if (pages.length <= 1) return;

                // prev
                const prev = document.createElement('button');
                prev.className = `btn btn-sm ${currentPageIndex === 0 ? 'btn-secondary' : 'btn-outline-primary'}`;
                prev.disabled = currentPageIndex === 0;
                prev.innerHTML = `<i class="bi bi-chevron-left"></i>`;
                prev.onclick = () => {
                    if (currentPageIndex > 0) {
                        currentPageIndex--;
                        renderPage(currentPageIndex);
                        frameB.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                };
                pag.appendChild(prev);

                // page numbers
                pages.forEach((_, i) => {
                    const btn = document.createElement('button');
                    btn.textContent = i + 1;
                    btn.className = `btn btn-sm ${i === currentPageIndex ? 'btn-primary' : 'btn-outline-primary'}`;
                    btn.onclick = () => {
                        currentPageIndex = i;
                        renderPage(currentPageIndex);
                        frameB.scrollTo({ top: 0, behavior: 'smooth' });
                    };
                    pag.appendChild(btn);
                });

                // next
                const next = document.createElement('button');
                next.className = `btn btn-sm ${currentPageIndex === pages.length - 1 ? 'btn-secondary' : 'btn-outline-primary'}`;
                next.disabled = currentPageIndex === pages.length - 1;
                next.innerHTML = `<i class="bi bi-chevron-right"></i>`;
                next.onclick = () => {
                    if (currentPageIndex < pages.length - 1) {
                        currentPageIndex++;
                        renderPage(currentPageIndex);
                        frameB.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                };
                pag.appendChild(next);
            }


            renderPage(currentPageIndex);
        }

        // --- Fungsi Load Kegiatan ---
        function loadKegiatan(){
            const frameB = document.getElementById('reviewContent');
            const officerIdSpan = document.getElementById('selectedOfficer');
            const officerId = officerIdSpan ? officerIdSpan.dataset.officerId : null;
            const startDate = document.getElementById('modalStartDate').value;
            const endDate = document.getElementById('modalEndDate').value;
        
            // --- Header ---
            const reviewHeaderTitle = document.getElementById('reviewHeaderTitle');
            reviewHeaderTitle.innerHTML = `
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <span class="me-3 mb-1">Periodik: ${formatDateDisplay(startDate)} - ${formatDateDisplay(endDate)}</span>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button class="btn btn-light btn-sm fw-semibold text-primary border" onclick="exportPDFAll()">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF ALL
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm border dropdown-toggle" type="button" id="dropdownFilterBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <div class="dropdown-menu p-2" id="reviewFilter" aria-labelledby="dropdownFilterBtn" style="min-width:200px;">
                                <label class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Visit" checked>
                                    <span class="form-check-label">Visit</span>
                                </label>
                                <label class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Follow Up" checked>
                                    <span class="form-check-label">Follow Up</span>
                                </label>
                                <label class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Kendala" checked>
                                    <span class="form-check-label">Kendala</span>
                                </label>
                                <label class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" value="Kompetitor" checked>
                                    <span class="form-check-label">Kompetitor</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.querySelectorAll('#reviewFilter input[type="checkbox"]').forEach(cb => {
                cb.addEventListener('change', () => loadKegiatanFiltered());
            });
            document.getElementById('reviewHeaderHint').textContent = '';
            document.getElementById('reviewHeaderTitle').classList.add('text-start');
            document.getElementById('reviewHeaderHint').classList.add('text-start');
        
            if(!officerId){
                frameB.innerHTML = `<div class="text-center py-3 text-danger">Mohon pilih Officer terlebih dahulu.</div>`;
                return;
            }
        
            frameB.innerHTML = `<div class="text-center py-2 text-primary">Memuat Kegiatan...</div>`;
            frameB.style.paddingBottom = '-10px'; // space bawah untuk pagination
        
            fetch(`/report/doctor/detail/${officerId}?start_date=${startDate}&end_date=${endDate}`)
            .then(res => res.json())
            .then(data => {
                if(!data.success || data.data.length === 0){
                    frameB.innerHTML = `<div class="text-center py-3 text-muted">Tidak ada data dalam periode ini.</div>`;
                    return;
                }
        
                const grouped = {};
                data.data.forEach(item=>{
                    const tgl = item.tanggal;
                    if(!grouped[tgl]) grouped[tgl] = [];
                    grouped[tgl].push(item);
                });
        
                const sortedDates = Object.keys(grouped).sort((a,b)=> new Date(a)-new Date(b));
        
                const itemsPerPage = 5;
                let currentPage = 1;
                const totalPages = Math.ceil(sortedDates.length / itemsPerPage);
        
                function renderPage(page){
                    currentPage = page;
                    frameB.innerHTML = '';
        
                    const startIdx = (page - 1) * itemsPerPage;
                    const endIdx = Math.min(startIdx + itemsPerPage, sortedDates.length);
                    const pageDates = sortedDates.slice(startIdx, endIdx);
        
                    pageDates.forEach(tanggal=>{
                        const section = document.createElement('div');
                        section.style.marginBottom = '6px';
        
                        // Header tanggal
                        const dateHeader = document.createElement('div');
                        dateHeader.className = 'd-flex justify-content-between align-items-center fw-semibold mb-1 p-1 rounded';
                        dateHeader.style.backgroundColor = '#f0f0f0';
                        dateHeader.style.fontSize = '0.9em';
                        dateHeader.innerHTML = `
                            <span>${formatDateWithDay(tanggal)}</span>
                            <button class="btn btn-light btn-sm fw-semibold text-primary border" onclick="exportPDF('${tanggal}')">
                                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                            </button>
                        `;
                        section.appendChild(dateHeader);
        
                        // Gabungkan semua customer dalam satu card
                        const customerGroups = {};
                        grouped[tanggal].forEach(item=>{
                            if(!customerGroups[item.customer]) customerGroups[item.customer] = [];
                            customerGroups[item.customer].push(item);
                        });
        
                        const card = document.createElement('div');
                        card.className = 'card mb-1 shadow-sm bg-white';
                        card.style.fontSize = '0.85em';
        
                        let cardContent = '';
                        Object.keys(customerGroups).forEach((customerName, idx)=>{
                            const firstItem = customerGroups[customerName][0];
                            cardContent += `
                                <div class="d-flex mb-1 pb-1 align-items-start" style="gap:4px; border-bottom:0.9px solid #000;">
                                    <div class="text-center" style="width:30px; flex-shrink:0;">
                                        <div class="fw-bold mb-1">${idx+1}</div>
                                    </div>
                                    <div class="flex-grow-1 text-start">
                                        <p class="mb-1"><strong>${firstItem.customer} - ${firstItem.text_kota || '-'}</strong></p>
                                        <p class="mb-1"><strong>Mapping:</strong> ${firstItem.kegiatan}</p>
                                        <p class="mb-1"><strong>Deskripsi:</strong> ${firstItem.kegiatan_text}</p>
                                        <p class="mb-1"><strong>Produk:</strong> ${firstItem.produk || '-'}</p>
                                        <p class="mb-0"><strong>Respon:</strong> ${firstItem.respon || '-'}</p>
                                    </div>
                                </div>
                            `;
                        });
                        card.innerHTML = cardContent;
                        section.appendChild(card);
        
                        frameB.appendChild(section);
                    });
        
                    renderPagination();
                }
        
                function renderPagination(){
                    const paginationDiv = document.getElementById('reviewPagination');
                    paginationDiv.innerHTML = '';

                    // Prev
                    const prevBtn = document.createElement('button');
                    prevBtn.className = `btn btn-sm ${currentPage===1 ? 'btn-secondary' : 'btn-outline-primary'} mx-1`;
                    prevBtn.disabled = currentPage===1;
                    prevBtn.innerHTML = `<i class="bi bi-chevron-left"></i>`;
                    prevBtn.onclick = () => {
                        if(currentPage > 1){
                            renderPage(currentPage - 1);
                            frameB.scrollTop = 0;
                        }
                    };
                    paginationDiv.appendChild(prevBtn);

                    // Page numbers
                    for(let i=1; i<=totalPages; i++){
                        const btn = document.createElement('button');
                        btn.className = `btn btn-sm mx-1 ${i===currentPage ? 'btn-primary text-white' : 'btn-outline-primary'}`;
                        btn.textContent = i;
                        btn.onclick = () => {
                            renderPage(i);
                            frameB.scrollTop = 0;
                        };
                        paginationDiv.appendChild(btn);
                    }

                    // Next
                    const nextBtn = document.createElement('button');
                    nextBtn.className = `btn btn-sm ${currentPage===totalPages ? 'btn-secondary' : 'btn-outline-primary'} mx-1`;
                    nextBtn.disabled = currentPage===totalPages;
                    nextBtn.innerHTML = `<i class="bi bi-chevron-right"></i>`;
                    nextBtn.onclick = () => {
                        if(currentPage < totalPages){
                            renderPage(currentPage + 1);
                            frameB.scrollTop = 0;
                        }
                    };
                    paginationDiv.appendChild(nextBtn);
                }
        
                window.exportPDF = (tanggal) => { exportPDF(tanggal); };
        
                renderPage(currentPage);
        
            })
            .catch(err=>{
                console.error(err);
                frameB.innerHTML = `<div class="text-center py-3 text-danger">Gagal memuat data.</div>`;
            });
        }
        
        // --- Load kegiatan dengan filter ---
        function loadKegiatanFiltered(){
            const frameB = document.getElementById('reviewContent');
            const officerIdSpan = document.getElementById('selectedOfficer');
            const officerId = officerIdSpan ? officerIdSpan.dataset.officerId : null;
            const startDate = document.getElementById('modalStartDate').value;
            const endDate = document.getElementById('modalEndDate').value;
        
            if(!officerId){
                frameB.innerHTML = `<div class="text-center py-3 text-danger">Mohon pilih Officer terlebih dahulu.</div>`;
                return;
            }
        
            frameB.innerHTML = `<div class="text-center py-3 text-primary">Memuat Kegiatan...</div>`;
            frameB.style.paddingBottom = "70px";
        
            fetch(`/report/doctor/detail/${officerId}?start_date=${startDate}&end_date=${endDate}`)
            .then(res=>res.json())
            .then(data=>{
                if(!data.success || data.data.length===0){
                    frameB.innerHTML = `<div class="text-center py-3 text-muted">Tidak ada data dalam periode ini.</div>`;
                    return;
                }
        
                const activeFilters = Array.from(document.querySelectorAll('#reviewFilter input[type="checkbox"]:checked'))
                    .map(cb => cb.value);
        
                const filteredData = activeFilters.length
                    ? data.data.filter(i => activeFilters.includes(i.kegiatan))
                    : [];
        
                if(filteredData.length === 0){
                    frameB.innerHTML = `<div class="text-center py-3 text-muted">Tidak ada data yang ditampilkan.</div>`;
                    return;
                }
        
                const grouped = {};
                filteredData.forEach(item=>{
                    const tgl = item.tanggal;
                    if(!grouped[tgl]) grouped[tgl] = [];
                    grouped[tgl].push(item);
                });
        
                const sortedDates = Object.keys(grouped).sort((a,b)=> new Date(a)-new Date(b));
                frameB.innerHTML = '';
        
                sortedDates.forEach(tanggal=>{
                    const section = document.createElement('div');
        
                    const dateHeader = document.createElement('div');
                    dateHeader.className = 'd-flex justify-content-between align-items-center fw-semibold mb-2 p-1 rounded';
                    dateHeader.style.backgroundColor = '#f0f0f0';
                    dateHeader.innerHTML = `
                        <span>${formatDateWithDay(tanggal)}</span>
                        <button class="btn btn-light btn-sm fw-semibold text-primary border" onclick="exportPDF('${tanggal}')">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                        </button>
                    `;
                    section.appendChild(dateHeader);
        
                    const customerGroups = {};
                    grouped[tanggal].forEach(item=>{
                        if(!customerGroups[item.customer]) customerGroups[item.customer] = [];
                        customerGroups[item.customer].push(item);
                    });
        
                    const card = document.createElement('div');
                    card.className = 'card mb-2 shadow-sm bg-white';
                    card.style.fontSize = '0.85em';
        
                    let cardContent = '';
                    Object.keys(customerGroups).forEach((customerName, idx)=>{
                        const firstItem = customerGroups[customerName][0];
                        cardContent += `
                            <div class="d-flex mb-1 pb-1 align-items-start" style="gap:4px; border-bottom:0.9px solid #000;">
                                <div class="text-center" style="width:30px; flex-shrink:0;">
                                    <div class="fw-bold mb-1">${idx+1}</div>
                                </div>
                                <div class="flex-grow-1 text-start">
                                    <p class="mb-1"><strong>${firstItem.customer} - ${firstItem.text_kota || '-'}</strong></p>
                                    <p class="mb-1" style="font-size: 0.85em;"><strong>Mapping:</strong> ${firstItem.kegiatan}</p>
                                    <p class="mb-1" style="font-size: 0.85em;"><strong>Deskripsi:</strong> ${firstItem.kegiatan_text}</p>
                                    <p class="mb-1" style="font-size: 0.85em;"><strong>Produk:</strong> ${firstItem.produk || '-'}</p>
                                    <p class="mb-0" style="font-size: 0.85em;"><strong>Respon:</strong> ${firstItem.respon || '-'}</p>
                                </div>
                            </div>
                        `;
                    });
                    card.innerHTML = cardContent;
                    section.appendChild(card);
        
                    frameB.appendChild(section);
                });
            })
            .catch(err=>{
                console.error(err);
                frameB.innerHTML = `<div class="text-center py-3 text-danger">Gagal memuat data.</div>`;
            });
        }
    
        // --- Fungsi export PDF per tanggal ---
        function exportPDF(tanggal){
            const officerIdSpan = document.getElementById('selectedOfficer');
            const officerId = officerIdSpan ? officerIdSpan.dataset.officerId : null;
            if(!officerId){ alert('Officer belum dipilih.'); return; }
        
            const url = `/report/doctor/export-pdf/${officerId}?tanggal=${tanggal}`;
            window.open(url,'_blank');
        }
    
        // --- Fungsi export PDF ALL ---
        function exportPDFAll(){
            const officerIdSpan = document.getElementById('selectedOfficer');
            const officerId = officerIdSpan ? officerIdSpan.dataset.officerId : null;
            if(!officerId){ alert('Officer belum dipilih.'); return; }
        
            const startDate = document.getElementById('modalStartDate').value;
            const endDate = document.getElementById('modalEndDate').value;
        
            const url = `/report/doctor/export-pdf-all/${officerId}?start_date=${startDate}&end_date=${endDate}`;
            window.open(url,'_blank');
        }
    
        window.exportPDF = exportPDF;
        window.exportPDFAll = exportPDFAll;
    
        // --- Fungsi Load Sampling/Quotation ---
        function escapeHtml(s) {
            if (s === null || s === undefined) return '';
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }
        
        // Normalisasi ke format YYYY-MM-DD
        function parseDateToYMD(dateStr) {
            if (!dateStr) return null;
        
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return dateStr;
        
            const d = new Date(dateStr);
            if (!isNaN(d)) {
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                return `${y}-${m}-${dd}`;
            }
        
            // dd MMM YYYY
            const months = {
                "jan":"01","feb":"02","mar":"03","apr":"04","mei":"05","jun":"06",
                "jul":"07","agu":"08","sep":"09","okt":"10","nov":"11","des":"12",
                "january":"01","february":"02","march":"03","april":"04","may":"05","june":"06",
                "july":"07","august":"08","september":"09","october":"10","november":"11","december":"12"
            };
            const parts = String(dateStr).trim().split(/\s+|-/);
            if (parts.length >= 3) {
                let day, mon, year;
                if (/\d{1,2}/.test(parts[0]) && isNaN(parts[1])) {
                    day = parts[0].replace(/\D/g,'');
                    mon = parts[1].toLowerCase();
                    year = parts[2].replace(/\D/g,'');
                } else if (isNaN(parts[0]) && /\d{1,2}/.test(parts[1])) {
                    mon = parts[0].toLowerCase();
                    day = parts[1].replace(/\D/g,'');
                    year = parts[2].replace(/\D/g,'');
                }
                if (day && mon && year && months[mon]) {
                    const mm = months[mon];
                    const dd = String(day).padStart(2,'0');
                    return `${year}-${mm}-${dd}`;
                }
            }
        
            return null;
        }
        
        // Format ke "DD Mon YYYY" (Indo)
        function formatIndoDate(dateStr) {
            const ymd = parseDateToYMD(dateStr);
            if (!ymd) return '-';
            const [y, m, d] = ymd.split('-');
            const bulan = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
            const idx = parseInt(m, 10) - 1;
            return `${parseInt(d,10)} ${bulan[idx]} ${y}`;
        }
        
        function convertToIso(dateStr) {
            if (!dateStr) return "";
            return dateStr + "T00:00:00";
        }
        
        function convertToIsoEnd(dateStr) {
            if (!dateStr) return "";
            return dateStr + "T23:59:59";
        }
        
        // ------------------ Main Function ------------------
        function loadSamplingQuotation() {
            const frameB = document.getElementById('reviewContent');
            const officerIdSpan = document.getElementById('selectedOfficer');
            const officerId = officerIdSpan ? officerIdSpan.dataset.officerId : null;
        
            document.getElementById('reviewHeaderTitle').textContent = `Tipe: Sampling & Quotation`;
        
            if (!officerId) {
                frameB.innerHTML = `<div class="text-center py-3 text-danger">Mohon pilih Officer terlebih dahulu.</div>`;
                return;
            }
        
            frameB.innerHTML = `<div class="text-center py-3 text-primary">Memuat Sampling & Quotation...</div>`;
        
            const startDate = document.getElementById('modalStartDate').value || null;
            const endDate   = document.getElementById('modalEndDate').value || null;

        
            const url = `/report/doctor/file-doctor/sampling-quotation?officer_id=${officerId}`
                + (startDate ? `&start_date=${startDate}` : "")
                + (endDate   ? `&end_date=${endDate}`   : "");
        
            fetch(url)
                .then(res => res.json())
                .then(response => {
                    const data = response.data || {};
                    const sampling  = data.sampling || [];
                    const quotation = data.quotation || [];
                    const samplingQuotation = data.sampling_quotation || [];
        
                    if (sampling.length === 0 && quotation.length === 0 && samplingQuotation.length === 0) {
                        frameB.innerHTML = `<div class="text-center py-3 text-muted">Tidak ada data Sampling / Quotation.</div>`;
                        return;
                    }
        
                    // ====== GROUP BY created_at (YYYY-MM-DD) ======
                    const grouped = {};
        
                    function pushItem(list, type) {
                        list.forEach(item => {
                            const tgl = parseDateToYMD(item.created_at) || "-";
                            if (!grouped[tgl]) grouped[tgl] = [];
                            grouped[tgl].push({ type, item });
                        });
                    }
        
                    pushItem(sampling,  "sampling");
                    pushItem(quotation, "quotation");
                    pushItem(samplingQuotation, "sampling_quotation");
        
                    // ====== RENDER ======
                    frameB.innerHTML = "";
        
                    Object.keys(grouped).sort().forEach(tgl => {
                        const tanggalFormatted = formatDateWithDay(tgl);
        
                        let html = `
                            <div class="card mb-3 shadow-sm bg-white">
                                <div class="card-header fw-semibold text-start">${tanggalFormatted}</div>
                                <div class="card-body p-2">
                        `;
        
                        grouped[tgl].forEach((obj, idx) => {
                            const item = obj.item;
                            const uniq = "smp_" + (Date.now() + Math.random());
                            window[uniq] = item;
        
                            const customer = escapeHtml(item.customer_name || "-");
                            const kota     = escapeHtml(item.customer_city || item.kota || "");
                            const kode     = escapeHtml(item.kode || "-");
                            const type     = obj.type.toUpperCase();
        
                            let btn = "";
                            if (obj.type === "sampling" || obj.type === "sampling_quotation") {
                                btn = `<button class="btn btn-sm btn-outline-primary" onclick="openSamplingModal('${uniq}')">Detail</button>`;
                            }
                            if (obj.type === "quotation" || obj.type === "sampling_quotation") {
                                if (item.path_dir) {
                                    // pastikan path diawali '/', supaya URL valid
                                    const pdfUrl = item.path_dir.startsWith('/') ? item.path_dir : '/' + item.path_dir;
                            
                                    btn += `<a href="${pdfUrl}" target="_blank" class="btn btn-sm btn-outline-danger ms-1">PDF</a>`;
                                }
                            }
        
                            html += `
                                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                    <div>
                                        <div class="fw-semibold text-start">${customer} ${kota ? '('+kota+')' : ''}</div>
                                        <small class="text-muted">${kode} • ${type}</small>
                                    </div>
                                    <div>${btn}</div>
                                </div>
                            `;
                        });
        
                        html += `</div></div>`;
                        frameB.innerHTML += html;
                    });
                })
                .catch(err => {
                    console.error(err);
                    frameB.innerHTML = `<div class="text-center py-3 text-danger">Gagal memuat data.</div>`;
                });
        }
        
        // ------------------ Modal Function ------------------
        window.openSamplingModal = function(key) {
            const data = window[key];
            if (!data) return;
        
            const list = data.sampling_list || [];
        
            let html = `
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Brand</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jumlah (ml)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${
                                list.map(p => `
                                    <tr>
                                        <td>${escapeHtml(p.brand_name)}</td>
                                        <td>${escapeHtml(p.product_name)}</td>
                                        <td>${escapeHtml(p.product_code)}</td>
                                        <td>${escapeHtml(p.kemasan)}</td>
                                    </tr>
                                `).join('')
                            }
                        </tbody>
                    </table>
                </div>
            `;
        
            document.getElementById('samplingModalBody').innerHTML = html;
            const myModal = new bootstrap.Modal(document.getElementById('samplingModal'));
            myModal.show();
        };
        
        // Load Customer
        const getDoctorMarketUrl = "{{ route('report.doctor.getDoctorMarket') }}";
        
        // Helper: base64 URL-safe encode (works with Unicode)
        function base64UrlEncode(input) {
            const s = (typeof input === 'number') ? String(input) : (input == null ? '' : String(input));
            try {
                const base64 = btoa(unescape(encodeURIComponent(s)));
                return base64.replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
            } catch (err) {
                try {
                    const base64 = btoa(s);
                    return base64.replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
                } catch (err2) {
                    console.error('base64UrlEncode error', err2);
                    return '';
                }
            }
        }
        
        // safer text
        function safeText(v, fb = '-') {
            return v === null || v === undefined || v === '' ? fb : v;
        }
        
        function loadCustomer() {
            const frameB = document.getElementById('reviewContent');
            const reviewHeader = document.getElementById('reviewHeaderTitle');
            reviewHeader.textContent = `Tipe: Customer`;
        
            const officerId = selectedOfficerSpan?.dataset.officerId;
            if (!officerId) {
                frameB.innerHTML = `<div class="alert alert-warning text-center py-3">Silakan pilih Officer terlebih dahulu.</div>`;
                return;
            }
        
            const startDate = document.getElementById("modalStartDate")?.value || "";
            const endDate   = document.getElementById("modalEndDate")?.value || "";
        
            frameB.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>`;
        
            const params = new URLSearchParams({ officer_id: officerId });
            if(startDate) params.append('start_date', startDate);
            if(endDate)   params.append('end_date', endDate);
        
            fetch(`${getDoctorMarketUrl}?${params.toString()}`)
                .then(res => {
                    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                    return res.json();
                })
                .then(response => {
                    if (!response.success || !response.data || response.data.length === 0) {
                        frameB.innerHTML = `<div class="alert alert-warning text-center py-3">Tidak ada data Customer dalam periode ini.</div>`;
                        return;
                    }
        
                    const officerEncoded = encodeURIComponent(officerId);
        
                    // --- SORTING PROVINSI → KOTA → CUSTOMER NAME ---
                    const allData = response.data.sort((a, b) => {
                        const provA = (a.text_provinsi || '').toLowerCase();
                        const provB = (b.text_provinsi || '').toLowerCase();
                        if (provA < provB) return -1;
                        if (provA > provB) return 1;
        
                        const kotaA = (a.text_kota || '').toLowerCase();
                        const kotaB = (b.text_kota || '').toLowerCase();
                        if (kotaA < kotaB) return -1;
                        if (kotaA > kotaB) return 1;
        
                        const nameA = (a.customer || a.name || '').toLowerCase();
                        const nameB = (b.customer || b.name || '').toLowerCase();
                        if (nameA < nameB) return -1;
                        if (nameA > nameB) return 1;
        
                        return 0;
                    });
        
                    // --- Group by kategori_db ---
                    const groupedData = allData.reduce((acc, curr) => {
                        const category = curr.kategori_db || 'Tanpa Kategori';
                        if (!acc[category]) acc[category] = new Map();
        
                        const key = (curr.customer_id !== null && curr.customer_id !== undefined)
                            ? String(curr.customer_id)
                            : (curr.id !== undefined ? String(curr.id) : Math.random().toString(36).slice(2));
        
                        acc[category].set(key, curr);
                        return acc;
                    }, {});
        
                    const kategoriOrder = [
                        'Umum (semua prospek)',
                        'Agen - perfumery trusted',
                        'Smreseller',
                        'Bigreseller',
                        'Smperfumery',
                        'Bigperfumery',
                        'Umum project (semua prospek)',
                        'Home industri pkrt',
                        'Home industri kosmetik',
                        'Industri pkrt (PPN)',
                        'Industri kosmetik (PPN)'
                    ];
        
                    let combinedHtml = ``;
        
                    function generateCategoryBlock(kategori, dataArray) {
                        const totalCount = dataArray.length;
                        const cardId = 'card-' + kategori.replace(/\s/g, '_').replace(/[^A-Za-z0-9_\-]/g, '');
        
                        let html = `
                            <div class="mb-3">
                                <div class="card bg-white border-primary shadow-sm cursor-pointer"
                                     id="${cardId}"
                                     data-bs-toggle="collapse"
                                     data-bs-target="#collapse-${cardId}">
                                    <div class="card-body py-2 d-flex justify-content-between align-items-center">
                                        <h6 class="card-title text-primary mb-0">${kategori}</h6>
                                        <p class="card-text mb-0">Total Data: <strong>${totalCount}</strong></p>
                                    </div>
                                </div>
        
                                <div class="collapse mt-2" id="collapse-${cardId}">
                                    <div class="card card-body bg-white">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr style="font-size: 0.9em;">
                                                        <th>#</th>
                                                        <th>PROVINSI</th>
                                                        <th>KOTA</th>
                                                        <th>NAMA</th>
                                                        <th>SOURCE</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-size: 0.8em;">
                        `;
        
                        dataArray.forEach((c, index) => {
                            const status = (c.customer_status || c.status || '').toLowerCase();
                            const rowClass =
                                status === 'customer' || status === 'existing' ? 'row-existing' :
                                status === 'prospek' ? 'row-prospek' : '';
        
                            const customerId = c.customer_id ?? c.id ?? index;
                            const encodedId = btoa(customerId);
        
                            html += `
                                <tr class="${rowClass}">
                                    <td>${index + 1}</td>
                                    <td>${safeText(c.text_provinsi)}</td>
                                    <td>${safeText(c.text_kota)}</td>
                                    <td>
                                        <a href="#" class="getopen-customer"
                                           data-customer-id="${encodedId}"
                                           data-officer="${officerEncoded}">
                                           ${safeText(c.customer ?? c.name)}
                                        </a>
                                    </td>
                                    <td>${safeText(c.pengajuan)}</td>
                                </tr>
                            `;
                        });
        
                        html += `
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
        
                        return html;
                    }
        
                    kategoriOrder.forEach(kategori => {
                        if (!groupedData[kategori]) return;
                        const dataArray = Array.from(groupedData[kategori].values());
                        combinedHtml += generateCategoryBlock(kategori, dataArray);
                    });
        
                    Object.keys(groupedData).forEach(kategori => {
                        if (kategoriOrder.includes(kategori)) return;
                        const dataArray = Array.from(groupedData[kategori].values());
                        combinedHtml += generateCategoryBlock(kategori, dataArray);
                    });
        
                    frameB.innerHTML = combinedHtml;
                })
                .catch(err => {
                    console.error(err);
                    frameB.innerHTML = `<div class="alert alert-danger text-center py-3">Terjadi kesalahan saat memuat data: ${err.message}</div>`;
                });
        }
        
        
        // LINK opener
        document.getElementById('reviewContent').addEventListener('click', function(e){
            const target = e.target;
            if(target && target.classList.contains('getopen-customer')){
                e.preventDefault();
                const encodedId = target.dataset.customerId;
                const officer = target.dataset.officer?.toLowerCase();
                const url = `https://sys-af.lsfragrance.id/file-doctor/customer-one-time/${encodedId}?officer=${officer}`;
                window.open(url, '_blank');
            }
        });
    
    } else if (featureName === 'LAPORAN') {
        const today = new Date().toISOString().split("T")[0];
        const monthID = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
        const formatID = (d) => {
            const x = new Date(d);
            return `${String(x.getDate()).padStart(2,"0")} ${monthID[x.getMonth()]} ${String(x.getFullYear()).slice(-2)}`;
        };

        // ============================================================
        // TEMPLATE HTML
        // ============================================================
        contentArea.innerHTML = `
        <div class="laporan-container text-start">

            <!-- FRAME A -->
            <div class="bg-dark p-3 rounded-2 mb-1 shadow-sm">

                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <select id="reportType" class="form-select form-select-sm">
                            <option value="">Pilih Jenis Laporan</option>
                            <option value="target">Target / Penjualan</option>
                            <option value="aktivitas">Aktivitas / Relasi</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select id="subReportType" class="form-select form-select-sm">
                            <option value="">Sub Laporan</option>
                        </select>
                    </div>

                    <div class="col-md-4" id="dateContainer" style="position: relative;">
                        <button id="btnRangeDate" class="btn btn-secondary btn-sm w-100">
                            <i class="bi bi-calendar"></i>
                            <span id="rangeLabel">${formatID(today)} - ${formatID(today)}</span>
                        </button>
                        <input id="datePicker" class="form-control form-select-sm" 
                            style="position:absolute; top:35px; left:0; width:100%; opacity:0; z-index:-1;">
                    </div>
                </div>

                <div class="row g-2 align-items-center">
                    <div class="col-md-8 d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-light btn-sm quick-range" data-range="1D">1D</button>
                        <button class="btn btn-outline-light btn-sm quick-range" data-range="1M">1M</button>
                        <button class="btn btn-outline-light btn-sm quick-range" data-range="3M">3M</button>
                        <button class="btn btn-outline-light btn-sm quick-range" data-range="YTD">YTD</button>
                    </div>

                    <div class="col-md-4 d-flex gap-2 justify-content-end" id="quarterWrapper"></div>
                </div>

            </div>

            <!-- FRAME B -->
            <div class="bg-dark p-2 rounded-2 shadow-sm">
                <iframe id="pdfViewer" src="" class="w-100" style="height:70vh;border:none;"></iframe>
            </div>

        </div>
        `;

        // ============================================================
        // RE-BIND ELEMENTS
        // ============================================================
        const reportType = document.getElementById("reportType");
        const subReportType = document.getElementById("subReportType");
        const pdfViewer = document.getElementById("pdfViewer");
        const btnRangeDate = document.getElementById("btnRangeDate");
        const rangeLabel = document.getElementById("rangeLabel");
        const datePicker = document.getElementById("datePicker");

        // STATE
        let startDate = today;
        let endDate = today;
        let accountOfficer = selectedOfficerSpan.dataset.officerId;
        let quarterMode = 3;

        // ============================================================
        // MAPPING SUB LAPORAN
        // ============================================================
        const subMap = {
            target: [
                { value: "pembayaran", label: "Pembayaran" },
                { value: "penjualan", label: "Penjualan" },
                { value: "customer", label: "Customer" },
                { value: "varian", label: "Varian" }
            ],
            aktivitas: [
                { value: "prospek_followup", label: "Prospek - Follow Up" },
                { value: "prospek_sampling", label: "Prospek - Sampling" },
                { value: "prospek_visit", label: "Prospek - Visit" },
                { value: "existing_followup", label: "Existing - Follow Up" },
                { value: "existing_sampling", label: "Existing - Sampling" },
                { value: "existing_visit", label: "Existing - Visit" }
            ]
        };

        // UPDATE SUB LAPORAN OTOMATIS
        reportType.addEventListener("change", () => {
            const type = reportType.value;
            subReportType.innerHTML = `<option value="">Sub Laporan</option>`;
            if (subMap[type]) {
                subMap[type].forEach(s => {
                    const opt = document.createElement("option");
                    opt.value = s.value;
                    opt.textContent = s.label;
                    subReportType.appendChild(opt);
                });
            }
            renderPDF();
        });

        // ============================================================
        // RENDER PDF VIA POST FETCH
        // ============================================================
        const renderPDF = async () => {
            const type = reportType.value;
            const sub = subReportType.value;
            if (!type || !sub) return;

            const payload = { type, sub, start: startDate, end: endDate, officer: accountOfficer };

            console.log('📤 Mengirim payload ke backend:', payload); // <- LOG SEBELUM KIRIM

            try {
                const res = await fetch('/report/doctor/report/preview', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                console.log('📥 Respons dari backend:', data); // <- LOG SESUDAH TERIMA

                if(data.status === false){
                    alert('Gagal generate report: ' + data.message);
                } else {
                    alert('Payload berhasil diterima backend. Cek console untuk detail.');
                    pdfViewer.src = 'data:application/pdf;base64,' + data.pdf_base64; // Render di iframe
                }

            } catch (err) {
                console.error('❌ Gagal kirim payload ke backend', err);
                alert('Gagal kirim payload ke backend');
            }
        };

        subReportType.addEventListener("change", renderPDF);

        // ============================================================
        // QUARTER BUTTONS
        // ============================================================
        const quarterWrapper = document.getElementById("quarterWrapper");

        function renderQuarterButtons() {
            quarterWrapper.innerHTML = "";
            let quarters = [];
            if (quarterMode === 3) quarters = ["Q1","Q2","Q3","Q4"];
            if (quarterMode === 4) quarters = ["Q1","Q2","Q3"];
            if (quarterMode === 6) quarters = ["Q1","Q2"];

            quarters.forEach(q => {
                const btn = document.createElement("button");
                btn.className = "btn btn-outline-info btn-sm quarter-btn";
                btn.dataset.quarter = q;
                btn.innerText = q;
                quarterWrapper.appendChild(btn);
            });

            const modeBtn = document.createElement("button");
            modeBtn.id = "btnQuarterMode";
            modeBtn.className = "btn btn-warning btn-sm";
            modeBtn.innerText = quarterMode;
            quarterWrapper.appendChild(modeBtn);

            attachQuarterEvents();
        }

        function attachQuarterEvents() {
            document.querySelectorAll(".quarter-btn").forEach(btn => {
                btn.onclick = () => {
                    const y = new Date().getFullYear();
                    let start, end;
                    const q = btn.dataset.quarter;

                    if (quarterMode === 3) {
                        if (q === "Q1") { start=`${y}-01-01`; end=`${y}-03-31`; }
                        if (q === "Q2") { start=`${y}-04-01`; end=`${y}-06-30`; }
                        if (q === "Q3") { start=`${y}-07-01`; end=`${y}-09-30`; }
                        if (q === "Q4") { start=`${y}-10-01`; end=`${y}-12-31`; }
                    }
                    if (quarterMode === 4) {
                        if (q === "Q1") { start=`${y}-01-01`; end=`${y}-04-30`; }
                        if (q === "Q2") { start=`${y}-05-01`; end=`${y}-08-31`; }
                        if (q === "Q3") { start=`${y}-09-01`; end=`${y}-12-31`; }
                    }
                    if (quarterMode === 6) {
                        if (q === "Q1") { start=`${y}-01-01`; end=`${y}-06-30`; }
                        if (q === "Q2") { start=`${y}-07-01`; end=`${y}-12-31`; }
                    }

                    startDate = start;
                    endDate = end;
                    rangeLabel.innerText = `${formatID(start)} - ${formatID(end)}`;
                    renderPDF();
                };
            });

            document.getElementById("btnQuarterMode").onclick = () => {
                if (quarterMode === 3) quarterMode = 4;
                else if (quarterMode === 4) quarterMode = 6;
                else quarterMode = 3;

                renderQuarterButtons();
            };
        }

        renderQuarterButtons();

        function toYMD(d) {
            return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
        }

        // ============================================================
        // FLATPICKR DATE RANGE PICKER
        // ============================================================
        let fpInstance = null;

        function initFlatpickr() {
            if (fpInstance && typeof fpInstance.destroy === "function") fpInstance.destroy();

            fpInstance = flatpickr(datePicker, {
                mode: "range",
                dateFormat: "Y-m-d",
                allowInput: false,
                clickOpens: false,
                showMonths: 2,
                defaultDate: [startDate, endDate],
                locale: "en",
                appendTo: document.body,
                position: "auto",
                onChange: function(dates) {
                    if (dates.length === 2) {
                        let a = dates[0], b = dates[1];
                        let startObj = a <= b ? a : b;
                        let endObj = a <= b ? b : a;

                        startDate = toYMD(startObj);
                        endDate = toYMD(endObj);

                        rangeLabel.innerText = `${formatID(startDate)} - ${formatID(endDate)}`;
                        renderPDF();
                    }
                }
            });
        }

        setTimeout(initFlatpickr, 50);

        btnRangeDate.addEventListener("click", function () {
            if (!fpInstance) initFlatpickr();
            try { fpInstance.setDate([startDate, endDate], false); } catch(e){}
            fpInstance.open();
        });

        // ============================================================
        // QUICK RANGE BUTTONS
        // ============================================================
        document.querySelectorAll(".quick-range").forEach(btn => {
            btn.onclick = () => {
                const now = new Date();
                let start = new Date();
                const range = btn.dataset.range;

                if (range === "1D") start = now;
                if (range === "1M") start = new Date(now.getFullYear(), now.getMonth(), 1);
                if (range === "3M") start = new Date(now.getFullYear(), now.getMonth() - 2, 1);
                if (range === "YTD") start = new Date(now.getFullYear(), 0, 1);

                startDate = toYMD(start);
                endDate = toYMD(now);

                rangeLabel.innerText = `${formatID(startDate)} - ${formatID(endDate)}`;
                renderPDF();

                if (fpInstance) {
                    try { fpInstance.setDate([startDate, endDate], false); } catch(e){}
                }
            };
        });
    } else {
        setTimeout(() => {
            targetElement.innerHTML = `
                <div class="p-3 text-start">
                    
                </div>
            `;
        }, 800);
    }
}
</script>

<style>
/* Warna baris berdasarkan status */
.row-existing td {
    background-color: #d4edda !important; /* Hijau muda */
    color: #000 !important;
    font-weight: 500;
}

.row-prospek td {
    /*background-color: #cce5ff !important; */
    background-color: #ffffff !important; /* Biru langit */
    color: #000 !important;
    font-weight: 500;
}

/* Tambahan opsional: efek hover */
.row-existing:hover td,
.row-prospek:hover td {
    filter: brightness(0.95);
    transition: 0.2s ease;
}


/* --- Custom Max Width Container (Untuk memperkecil area konten utama) --- */
.max-width-lg {
    max-width: 992px; 
}

/* --- Global Styles --- */
.bg-dark-card {
    background-color: #2a3036;
}
.bg-gray-dark {
    background-color: #3e444b;
}

/* --- Select Officer Button --- */
#btnSelectOfficer {
    background-color: #f8f9fa; 
    color: #212529;
    border: none;
    transition: background-color 0.3s;
}
#btnSelectOfficer:hover {
    background-color: #e2e6ea;
}

/* --- Custom Style Dropdown Officer --- */
.officer-dropdown {
    width: auto; 
}
.officer-list-menu {
    min-width: 300px;
    max-width: 90vw; 
    max-height: 400px; 
    overflow-y: auto;
    background-color: #f8f9fa; 
    border-radius: 10px;
}
.officer-list-menu .list-group-flush {
    margin-top: 0;
    margin-bottom: 0;
}
.officer-list-menu .officer-item {
    background-color: transparent; 
}
.officer-list-menu .officer-item:hover {
    background-color: #e9ecef;
}
.officer-list-menu .officer-item.active {
    background-color: #0c82f9 !important;
    color: #ffffff !important;
    font-weight: bold;
}
.officer-list-menu .officer-item.active i {
    color: #ffffff !important;
}


/* --- Navigation Tabs (Group Style) --- */
.btn-group.nav-tabs-mobile {
    border-radius: 0.5rem;
    overflow: hidden;
}
.btn-dark-outline {
    background-color: transparent;
    border: 1px solid #3e444b;
    color: #f8f9fa; 
    transition: background-color 0.3s, border-color 0.3s, color 0.3s;
    padding: 8px 10px; 
    border-radius: 0; 
}
.btn-dark-outline:not(:last-child) {
    border-right: none;
}
.btn-dark-outline:hover, .btn-dark-outline.active-nav {
    background-color: #0c82f9 !important;
    border-color: #0c82f9 !important;
    color: #ffffff;
    box-shadow: none;
    z-index: 1;
}

/* Khusus untuk tampilan Mobile (<= 768px) */
    @media (max-width: 768px) {
    .max-width-lg {
        max-width: 100%;
    }
    .btn-group.nav-tabs-mobile {
        width: 100%;
    }
    .btn-dark-outline {
        padding: 8px 5px; 
    }
    /* Sembunyikan label text di mobile agar tombol muat */
    .btn-dark-outline span {
        display: none !important; 
    }
    #btnSelectOfficer {
        width: 100%;
        margin-bottom: 10px;
    }
    .header-section > .d-flex {
        flex-direction: column; 
    }

    /* Dropdown Full Width di Mobile */
    .officer-list-menu {
        width: calc(100% - 30px); 
        margin-left: 15px !important; 
        margin-right: 15px !important; 
        transform: translate3d(0, 0, 0) !important; 
    }

    /* Untuk membuat tombol logout di bawah nav di mobile */
    .header-section .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    #btnLogout {
        width: 100%;
    }
    
    #reviewContent {
        max-height: 600px;
        overflow-y: auto;
        padding-bottom: 40px; /* space untuk pagination */
        box-sizing: border-box;
    }
}
/* Maksimal lebar container di desktop, full width di HP */
.max-width-lg {
    max-width: 992px;
    margin: 0 auto;
}

/* --- Responsive Layout --- */
@media (max-width: 768px) {

    /* Stack header item menjadi vertikal */
    .header-section .d-flex {
        flex-direction: column !important;
        align-items: stretch !important;
    }

    /* Dropdown Officer full width */
    #btnSelectOfficer {
        width: 100% !important;
    }

    .officer-dropdown {
        width: 100%;
    }

    /* Navigasi jadi scroll horizontal agar tidak overflow */
    .nav-tabs-mobile {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        scrollbar-width: none; /* Firefox */
    }
    .nav-tabs-mobile::-webkit-scrollbar {
        display: none; /* Chrome & Safari */
    }

    .nav-button {
        flex: 0 0 auto;
        min-width: 130px;
    }

    /* Button logout turun ke bawah dengan lebar penuh */
    #btnLogout {
        width: 100%;
        margin-top: 5px;
    }

    /* Dropdown menu officer mengikuti lebar layar */
    .officer-list-menu {
        width: 100% !important;
        max-height: 300px;
        overflow-y: auto;
    }

    .flatpickr-calendar.flatpickr-in-modal {
        z-index: 99999 !important;
    }

    /* Agar konten tidak tertutup pagination */
    #reviewContent {
        padding-bottom: 70px !important;
    }

    /* Pagination sticky */
    .agenda-pagination-fixed {
        position: fixed !important;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #ffffff;
        border-top: 1px solid #ddd;
        padding: 8px 0;
        z-index: 9999;
    }

    /* FIX agar flatpickr tampil normal meski CSS lain konflik */
    .flatpickr-calendar {
        opacity: 1 !important;
        visibility: visible !important;
        display: block !important;
    }
}
</style>
@endpush