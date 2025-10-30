@extends('layouts.app')

@section('content')
{{-- Kontainer Utama dengan max-width 992px di desktop, lebar penuh di mobile --}}
<div class="container max-width-lg pb-5" style="background-color:#1e2227; min-height:100vh;">
    
    {{-- Header Utama: Pilihan Officer dan Navigasi dalam satu baris --}}
    <div class="header-section mb-4 pt-4"> 
        
        {{-- Flex container untuk menempatkan Pilih Officer dan Navigasi sejajar --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">
            
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

            {{-- Navigasi Kategori (Tab Navigasi Biasa) --}}
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
        </div>

    </div>
    
    {{-- Area Konten (Card Utama) --}}
    <div class="card p-3 p-md-5 bg-dark-card shadow-lg rounded-4">
        <div id="contentArea" class="text-center text-muted py-5">
            <p class="text-white">Pilih Officer Dahulu.</p>
            <p class="text-secondary m-0" style="font-size: 0.9rem;">Navigasi akan aktif setelah pemilihan.</p>
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

    // Pastikan untuk menghapus elemen dinamis lama saat berpindah tab
    const oldStyle = document.getElementById('fullCalendarStyle');
    const oldScript = document.getElementById('fullCalendarScript');
    if (oldStyle) oldStyle.remove();
    if (oldScript) oldScript.remove();

    if (featureName === 'AGENDA') { 
        
        // --- 1. Definisi Konten ---
        const agendaStyle = `
            /* ====== FullCalendar Styles - Lebih Stylish ====== */
            #calendar { 
                max-width: 950px; 
                margin: -70px auto 10px auto; 
                font-size: 0.9rem; 
            }
            .fc-toolbar { margin-bottom: 5px; }
            .fc-toolbar-title { font-size: 1rem; font-weight: 700; color: #f8f9fa; } 
            
            /* Tombol Navigasi Kalender */
            .fc-prev-button, .fc-next-button { 
                background-color: #0d6efd !important; 
                border: none !important; 
                padding: 5px 8px !important; 
                font-size: 0.8rem !important;
                border-radius: 6px !important;
            }
            .fc-col-header-cell-cushion { 
                padding: 6px 0; 
                font-size: 0.85rem; 
                color: #000000; 
                background-color: #ffffff; 
            } 
            
            /* Sel kalender */
            .fc-daygrid-day { padding: 1px !important; cursor: pointer; line-height: 1.2; border: 1px solid #3e444b; }
            .fc-daygrid-day-number { font-size: 0.8rem; padding: 4px; color: #f8f9fa; font-weight: 500;} 
            .fc-day-today { background-color: rgba(25, 135, 84, 0.4) !important; } 
            .fc-day-other .fc-daygrid-day-number { visibility: hidden; }
            .fc-day-other { background-color: #24292f !important; } 

            /* Event Bar */
            .fc-event { 
                padding: 0 4px !important; 
                margin-top: 1px !important; 
                margin-bottom: 1px !important; 
                font-size: 0.7rem !important; 
                line-height: 1.2 !important; 
                height: 18px;
                border-radius: 4px !important;
                font-weight: 500;
            }
            
            /* Card task dalam modal */
            .card-task { 
                border-left: 5px solid #0d6efd; 
                background-color: #24292f; 
                margin-bottom: 8px; 
                padding: 10px; 
                border-radius: 8px; 
                color: #f8f9fa; 
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }
            .card-task p { margin: 0; font-size: 0.9rem; }
            
            /* Navigasi Modal */
            .modal-dialog { 
                position: relative; 
                padding-left: 30px; 
                padding-right: 30px; 
            } 
            
            .modal-nav-button { 
                position: absolute; 
                top: 50%; 
                transform: translateY(-50%); 
                z-index: 1055; 
                background: rgba(0, 0, 0, 0.75); 
                color: white; border: none; padding: 10px 12px; cursor: pointer; 
                opacity: 1; transition: opacity 0.2s; border-radius: 50%; 
                box-shadow: 0 4px 6px rgba(0,0,0,0.4);
            }
            #modal-prev-date { 
                left: -20px; 
            } 
            #modal-next-date { 
                right: -20px; 
            }
        `;
        
        const agendaDom = ` 
            <div class="container-fluid px-3">
                <div id="calendar"></div>
            </div>

            <div class="modal fade" id="agendaModal" tabindex="-1" aria-labelledby="agendaModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg position-relative">
                    
                    <button id="modal-prev-date" type="button" class="modal-nav-button" aria-label="Previous Day">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button id="modal-next-date" type="button" class="modal-nav-button" aria-label="Next Day">
                        <i class="bi bi-chevron-right"></i>
                    </button>

                    <div class="modal-content bg-dark-card border-0">
                        <div class="modal-header bg-primary text-white py-2 position-relative">
                            
                            <h6 class="modal-title" id="agendaModalLabel">Detail Agenda: <span id="current-modal-date"></span></h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="agenda-content-detail">
                                <div id="agenda-tasks"></div>
                            </div>
                            <div id="no-agenda-message" class="text-center py-4 d-none">
                                <p class="lead text-muted">Tidak ada agenda pada tanggal ini.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // =========================================================================
        // === SCRIPT FULLCALENDAR DENGAN PERBAIKAN LOGIKA NAVIGASI MODAL ===
        // =========================================================================
        const agendaScript = ` 
            (function() { 
                const OFFICER_PARAM = "${officerId}"; 
                
                if (typeof FullCalendar === 'undefined' || typeof bootstrap === 'undefined') {
                    console.error('FullCalendar atau Bootstrap 5 belum dimuat di layout utama!');
                    return;
                }
                
                const calendarEl = document.getElementById('calendar');
                // VARIABEL LOKAL BARU: currentModalDate dan agendaModalInstance
                let currentModalDate = null; 
                const modalElement = document.getElementById('agendaModal');
                const agendaModalInstance = new bootstrap.Modal(modalElement);
                
                const prevBtn = document.getElementById('modal-prev-date');
                const nextBtn = document.getElementById('modal-next-date');

                function formatIndonesianDate(date) {
                    return date.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
                }

                function formatDateToISO(date) {
                    const d = new Date(date);
                    // Sesuaikan dengan zona waktu lokal (offset) agar tanggal tidak lompat
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
                    dayHeaders: true,
                    dayHeaderFormat: { weekday: 'long' },
                    events: {
                        url: AGENDA_DATA_URL,
                        extraParams: function() {
                            return { officer: OFFICER_PARAM };
                        },
                        failure: function() {
                            alert('Gagal memuat data agenda.');
                        }
                    },
                    dateClick: function(info) {
                        if (info.dayEl.classList.contains('fc-day-other')) return; 
                        currentModalDate = info.date; 
                        showAgendaModal(currentModalDate, calendar);
                    },
                    eventClick: function(info) {
                        info.jsEvent.preventDefault();
                        // Penting: pastikan event.start adalah objek Date yang valid
                        currentModalDate = new Date(info.event.start); 
                        showAgendaModal(currentModalDate, calendar);
                    }
                });

                calendar.render();

                function showAgendaModal(dateObject, calendarInstance) {
                    // 1. UPDATE currentModalDate
                    currentModalDate = dateObject; 
                    
                    const formattedDate = formatDateToISO(dateObject);
                    const displayDate = formatIndonesianDate(dateObject);

                    document.getElementById('current-modal-date').innerText = displayDate; 

                    // 2. Filter Events berdasarkan Tanggal
                    const events = calendarInstance.getEvents().filter(event => {
                        if (!event.start) return false;
                        const evDate = formatDateToISO(new Date(event.start)); 
                        return evDate === formattedDate;
                    });

                    const taskContainer = document.getElementById('agenda-tasks');
                    taskContainer.innerHTML = '';
                    
                    // 3. Isi Konten Modal
                    if (events.length > 0) {
                        let taskNumber = 1;
                        const tasksByPic = {}; 

                        events.forEach(event => {
                            const props = event.extendedProps || {};
                            const pic = props.pic || OFFICER_PARAM || 'N/A'; 
                            
                            if (!tasksByPic[pic]) {
                                tasksByPic[pic] = [];
                            }

                            if (props.keterangan_task && Array.isArray(props.keterangan_task) && props.keterangan_task.length > 0) {
                                tasksByPic[pic].push(...props.keterangan_task);
                            } else {
                                tasksByPic[pic].push({
                                    keterangan: props.keterangan || event.title || 'Tidak ada keterangan',
                                    status: props.status || '1'
                                });
                            }
                        });

                        for (const pic in tasksByPic) {
                            const titleEl = document.createElement('h5');
                            titleEl.innerText = 'Agenda (PIC: ' + pic + ')';
                            titleEl.classList.add('mt-3','mb-2', 'text-warning', 'fw-semibold');
                            taskContainer.appendChild(titleEl);
                            
                            tasksByPic[pic].forEach(task => {
                                const card = document.createElement('div');
                                card.classList.add('card-task');

                                let bgColor = '#24292f', textColor = '#f8f9fa', borderColor = '#0d6efd';
                                if (task.status == '0') { // Cancel
                                    bgColor='#3e1d20'; textColor='#f1aeb5'; borderColor='#842029'; 
                                } else if (task.status == '2') { // Complete
                                    bgColor='#1d3c26'; textColor='#ace4ba'; borderColor='#0f5132'; 
                                }

                                card.style.backgroundColor = bgColor;
                                card.style.color = textColor;
                                card.style.borderLeftColor = borderColor;

                                card.innerHTML = '<p class="fw-medium"><strong>' + taskNumber + '.</strong> ' + task.keterangan + '</p>';
                                taskContainer.appendChild(card);
                                taskNumber++;
                            });
                        }

                        document.getElementById('agenda-content-detail').classList.remove('d-none');
                        document.getElementById('no-agenda-message').classList.add('d-none');

                    } else {
                        document.getElementById('agenda-content-detail').classList.add('d-none');
                        document.getElementById('no-agenda-message').classList.remove('d-none');
                    }
                    
                    // 4. Tampilkan Modal (hanya jika belum tampil)
                    if (!modalElement.classList.contains('show')) {
                        agendaModalInstance.show();
                    }
                }

                // --- LOGIKA NAVIGASI MODAL (GESER HARI) ---
                if (prevBtn && nextBtn) {
                    prevBtn.addEventListener('click', function() {
                        if (currentModalDate) {
                            const newDate = new Date(currentModalDate); 
                            newDate.setDate(newDate.getDate() - 1);
                            currentModalDate = newDate; // update tanggal saat ini
                            showAgendaModal(newDate, calendar);
                        }
                    });

                    nextBtn.addEventListener('click', function() {
                        if (currentModalDate) {
                            const newDate = new Date(currentModalDate); 
                            newDate.setDate(newDate.getDate() + 1);
                            currentModalDate = newDate; // update tanggal saat ini
                            showAgendaModal(newDate, calendar);
                        }
                    });
                }
                
                // Logika Tampil/Sembunyi tombol saat modal dibuka/ditutup
                if (modalElement) {
                    modalElement.addEventListener('show.bs.modal', function() {
                        prevBtn.style.display = 'block';
                        nextBtn.style.display = 'block';
                    });
                    modalElement.addEventListener('hidden.bs.modal', function() {
                        prevBtn.style.display = 'none';
                        nextBtn.style.display = 'none';
                        currentModalDate = null;
                    });
                }
            })();
        `;
        
        // --- 2. Tampilkan DOM dan Style ---
        const styleElement = document.createElement('style');
        styleElement.id = 'fullCalendarStyle';
        styleElement.textContent = agendaStyle;
        document.head.appendChild(styleElement);
        
        targetElement.innerHTML = agendaDom;
        
        // --- 3. Eksekusi Script (Penting!) ---
        const newScript = document.createElement('script');
        newScript.id = 'fullCalendarScript'; 
        newScript.textContent = agendaScript;
        document.body.appendChild(newScript); 

    } else if (featureName === 'LIST MARKET') {
        // Logika LIST MARKET
        const pdfUrl = `/report/market-list-pdf?officer_id=${officerId}`; 
        targetElement.innerHTML = `
            <div class="text-white mt-5">
                <i class="bi bi-file-earmark-pdf-fill fs-1 text-danger mb-3"></i>
                <p class="lead">Laporan List Market <span class="text-info">${officerId}</span></p>
                <a href="${pdfUrl}" target="_blank" class="btn btn-info mt-2 rounded-pill px-4 shadow-sm">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka PDF
                </a>
                <p class="mt-3 text-secondary" style="font-size: 0.85rem;">Dokumen akan dibuka di tab baru.</p>
            </div>
        `;
    } else {
        // Logika placeholder untuk BROWSER atau LAPORAN
        setTimeout(() => {
            targetElement.innerHTML = `
                <div class="p-3 text-start">
                    <h5 class="text-white mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Ringkasan ${featureName}</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-gray-dark border-0 shadow-sm p-3 text-white">
                                <p class="mb-1 text-secondary">Aktivitas Bulan Ini</p>
                                <h3 class="fw-bold text-warning">15</h3>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-gray-dark border-0 shadow-sm p-3 text-white">
                                <p class="mb-1 text-secondary">Target Tercapai</p>
                                <h3 class="fw-bold text-success">85%</h3>
                            </div>
                        </div>
                    </div>
                    <small class="d-block mt-3 text-secondary">Data ditampilkan untuk Officer **${officerId}**.</small>
                    <div class="alert alert-info mt-3 p-2 text-center" style="font-size: 0.9rem;">
                        Ini adalah konten **${featureName}** yang dimuat secara dinamis.
                    </div>
                </div>
            `;
        }, 800);
    }
}
</script>

<style>
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
}
</style>
@endpush