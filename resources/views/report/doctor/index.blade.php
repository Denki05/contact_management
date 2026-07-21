@extends('layouts.app')

@section('content')
<<<<<<< HEAD
<<<<<<< HEAD
<style>
/* --- Utilities & Table Styles --- */
.max-width-lg { max-width: 992px; margin: 0 auto; }
body { 
    background-color: #1e2227; 
    overflow-x: hidden; /* Mencegah geser kanan-kiri yang tidak disengaja */
}
.bg-dark-card { background-color: #2a3036; }
.bg-gray-dark { background-color: #3e444b; }

.row-existing td { background-color: #d4edda !important; color: #000 !important; font-weight: 500; }
.row-prospek td { background-color: #ffffff !important; color: #000 !important; font-weight: 500; }
.row-existing:hover td, .row-prospek:hover td { filter: brightness(0.95); transition: 0.2s ease; }

/* =========================================
   CSS KHUSUS NAVIGASI HEADER (GOLDEN SYS CLONE)
   ========================================= */
.btn-dark-outline {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
    
    /* 100% Identik Sys */
    height: 34px !important; 
    padding: 0 12px !important; 
    
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
    font-weight: 700 !important; 
    line-height: 1 !important;
    border-radius: 4px !important;
    font-size: 0 !important; 
}

.btn-dark-outline span { 
    font-size: 12.5px !important; 
    font-weight: 700 !important; 
    color: inherit; 
    letter-spacing: 0.5px !important; 
    text-transform: uppercase;
    line-height: 1;
    display: inline-block;
}

.btn-dark-outline:hover:not(:disabled), 
.btn-dark-outline.active-nav,
.more-btn[aria-expanded="true"] {
    background-color: #0c82f9 !important; 
    border-color: #0c82f9 !important; 
    color: #ffffff !important;
}

.nav-grid {
    display: flex;
    align-items: center;
    gap: 5px;
    height: 34px !important;
    width: 100%;
}

/* --- Menu More & Dropdown --- */
.more-btn { height: 34px !important; }
.dropdown-menu-dark .dropdown-item { font-size: 12px !important; padding: 6px 16px; font-weight: 500; }
.btn-dark-outline i { font-size: 12.5px !important; }

/* =========================================
   CONTROL FLEX & OFFICER BAR
   ========================================= */
.control-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.officer-dropdown { flex: 0 0 auto; max-width: 160px; }

/* Officer Button ditekan ke 26px agar identik dengan User Profile Sys */
.officer-btn {
    height: 26px !important;
    background-color: #f8f9fa; 
    color: #212529;
    font-size: 11px !important;
    transition: background-color 0.2s ease;
}
.officer-btn:hover { background-color: #e2e6ea; }

.officer-list-menu {
    min-width: 250px;
    max-width: 90vw; 
    max-height: 300px; 
    overflow-y: auto;
    font-size: 12px;
    background-color: #ffffff; 
}
.officer-item { cursor: pointer; transition: 0.2s ease; }
.officer-item:hover { background-color: #f1f3f5; }
.officer-item.active { background-color: #0c82f9 !important; color: #ffffff !important; font-weight: bold; }
.officer-item.active i { color: #ffffff !important; font-size: 12px; }

/* --- Dynamic Container Fleksibel & Kunci Max Sys --- */
.card.bg-dark-card#dynamicContainer {
    /* 1. Saat belum klik menu / kosong, tinggi kotak mengecil (misal 120px) */
    min-height: 450px !important; 
    
    /* (HAPUS height: 559.33px !important; di sini agar bisa fleksibel) */
    
    /* 2. Saat menu diklik, kotak membesar mengikuti isi, tapi MENTOK di angka Sys */
    max-height: 559.33px !important; 
    
    /* 3. Scroll HANYA muncul otomatis di dalam jika datanya sangat panjang (melebihi 559px) */
    overflow-y: auto !important; 
    overflow-x: hidden !important;
    
    /* Opsional: Tambahkan efek transisi agar saat kotak membesar terlihat halus (tidak patah) */
    transition: all 0.3s ease-in-out;
}

/* Mempercantik scrollbar di dalam card */
#dynamicContainer::-webkit-scrollbar { 
    width: 6px; 
}
#dynamicContainer::-webkit-scrollbar-thumb { 
    background-color: rgba(255, 255, 255, 0.2); 
    border-radius: 10px; 
}
#dynamicContainer::-webkit-scrollbar-thumb:hover { 
    background-color: rgba(255, 255, 255, 0.4); 
}

/* --- Responsive Mobile Layout (<= 768px) --- */
@media (max-width: 768px) {
    .nav-grid { flex-wrap: wrap !important; height: auto !important; gap: 4px !important; }
    .nav-button { flex: 1 1 calc(50% - 4px); }
    .dropdown.ms-md-auto { flex: 1 1 100%; margin-top: 2px; }
    .more-btn { width: 100%; }
    
    .control-flex { 
        flex-direction: row !important; 
        align-items: center !important;
        flex-wrap: nowrap !important; 
        gap: 8px !important;
    }
    
    .officer-dropdown { flex-grow: 1; max-width: none; min-width: 0; }
    .officer-btn { width: 100%; }
    #btnLogout { width: auto !important; flex-shrink: 0; }
    
    #officerList { max-height: 250px; overflow-y: auto; }
    #officerList::-webkit-scrollbar { width: 4px; }
    #officerList::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
}
</style>

{{-- Kontainer Utama: Hapus min-height:100vh agar proporsi sama dengan Sys --}}
<div class="container max-width-lg pb-2 mx-auto" style="background-color:#1e2227; padding: 0px 12px;">
    
    {{-- Header Utama: Navigasi dan Pilihan Officer --}}
    <div class="header-section mb-1 pt-2"> 
        {{-- BARIS 1: Navigasi Utama (Selalu Aktif) --}}
        <div class="nav-grid d-flex flex-wrap align-items-center w-100 mb-1">
            <button type="button" class="btn btn-dark-outline nav-button rounded-1" id="btnAgenda">
                <span>AGENDA</span>
            </button>
            <button type="button" class="btn btn-dark-outline nav-button rounded-1" id="btnBrowser">
                <span>BROWSER</span>
            </button>
            <button type="button" class="btn btn-dark-outline nav-button rounded-1" id="btnMarket">
                <span>LIST MARKET</span>
            </button>
            <button type="button" class="btn btn-dark-outline nav-button rounded-1" id="btnLaporan">
                <span>LAPORAN</span>
            </button>
    
            {{-- More: Pojok Kanan --}}
            <div class="dropdown ms-md-auto">
                <button class="btn btn-dark-outline more-btn rounded-1 d-flex align-items-center px-2" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-grid-fill"></i><span>MORE</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border-0 mt-1">
                    <li><button type="button" class="dropdown-item nav-button py-1" id="btnEvent">EVENT</button></li>
                    <li><button type="button" class="dropdown-item nav-button py-1" id="btnProduct">PRODUCT</button></li>
                    <li><button type="button" class="dropdown-item nav-button py-1" id="btnSettingPic">SETTING PIC</button></li>
                </ul>
            </div>
        </div>
    
        {{-- BARIS 2: Pilihan Officer & Logout (Identik Sys) --}}
        <div class="control-flex d-flex justify-content-between align-items-center w-100 border-top pt-1 mt-1" style="border-color: rgba(255,255,255,0.1) !important;">
            
            <div class="dropdown officer-dropdown" id="containerSelectOfficer">
                <button id="btnSelectOfficer" class="btn btn-light officer-btn d-flex align-items-center justify-content-between px-2 rounded-1 border-0 shadow-sm w-100" type="button" data-bs-toggle="dropdown" style="gap: 6px;">
                    <div class="officer-label d-flex align-items-center gap-2 text-truncate">
                        <i class="bi bi-person-circle text-primary flex-shrink-0" style="font-size: 13px;"></i>
                        <span id="selectedOfficer" data-officer-id="" class="fw-bold text-truncate text-muted" style="font-size: 11px;">Pilih Officer</span>
                    </div>
                    <i class="bi bi-chevron-down text-muted flex-shrink-0" style="font-size: 10px;"></i>
                </button>
                <div class="dropdown-menu p-2 shadow-lg officer-list-menu border-0">
                    <div class="px-1 pb-2">
                        <input type="text" id="searchOfficer" class="form-control form-control-sm border-0 bg-light" placeholder="Pilih Officer">
                    </div>
                    <ul id="officerList" class="list-group list-group-flush">
                        {{-- Opsi All --}}
                        <li class="list-group-item list-group-item-action officer-item py-1 rounded-1 d-flex justify-content-between align-items-center active" id="itemAll" data-id="all" data-name="All">
                            <span>All</span><i class="bi bi-check-circle text-primary icon-check"></i>
                        </li>
                        {{-- Loop Officer dari Controller --}}
                        @foreach ($officers as $officer)
                            @if($officer->officer != 'All')
                            <li class="list-group-item list-group-item-action officer-item py-1 rounded-1" data-id="{{ $officer->officer }}" data-name="{{ $officer->officer }}">
                                {{ $officer->officer }}
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
    
            {{-- Tombol Logout direvisi menjadi 26px dengan span 12.5px --}}
            <button type="button" class="btn btn-outline-danger fw-bold px-0 d-flex align-items-center justify-content-center flex-shrink-0" id="btnLogout" style="height: 26px; font-size: 11px; width: 75px; gap: 4px;">
                <span style="font-size: 12.5px;">LOGOUT</span>
            </button>
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        </div>
    </div>
    
    {{-- Area Konten (Card Utama) --}}
<<<<<<< HEAD
<<<<<<< HEAD
    <div class="card p-2 p-md-2 bg-dark-card shadow-lg rounded-2 mt-1" id="dynamicContainer">
        <div id="contentArea" class="text-center text-muted">
            <p class="text-white" style="font-size: 11.5px;"></p>
            <p class="text-secondary m-0" style="font-size: 11.5px;"></p>
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    <div class="card p-2 p-md-2 bg-dark-card shadow-lg rounded-2">
        <div id="contentArea" class="text-center text-muted">
            <p class="text-white">Pilih Officer Dahulu.</p>
            <p class="text-secondary m-0" style="font-size: 0.9rem;">Navigasi akan aktif setelah pemilihan.</p>
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        </div>
    </div>
</div>

{{-- FORM LOGOUT TERSEMBUNYI --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======

>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
<<<<<<< HEAD
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Pastikan FullCalendar JS dan CSS (termasuk locale 'id') sudah dimuat di layout utama!
const AGENDA_DATA_URL = '{{ route("report.doctor.agenda.data") }}';

document.addEventListener("DOMContentLoaded", function () {
    // 1. Ambil semua elemen yang dibutuhkan
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
<script>
// Pastikan FullCalendar JS dan CSS (termasuk locale 'id') sudah dimuat di layout utama!
const AGENDA_DATA_URL = '{{ route("report.doctor.agenda.data") }}'; 

document.addEventListener("DOMContentLoaded", function () {
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    const btnSelectOfficer = document.getElementById("btnSelectOfficer");
    const officerDropdown = new bootstrap.Dropdown(btnSelectOfficer); 
    const selectedOfficerSpan = document.getElementById("selectedOfficer");
    const contentArea = document.getElementById("contentArea");
    const searchInput = document.getElementById("searchOfficer");
    const officerListContainer = document.getElementById("officerList");
    const navButtons = document.querySelectorAll(".nav-button");
    const btnLogout = document.getElementById("btnLogout");
<<<<<<< HEAD
<<<<<<< HEAD
    
    // Elemen tambahan untuk logika baru
    const itemAll = document.getElementById("itemAll");
    const containerSelectOfficer = document.getElementById("containerSelectOfficer");

    // --- FUNGSI RENDER (Jembatan ke sistem Anda) ---
    // Di file lama Anda, fungsi pemuat datanya adalah loadContent(feature, officerId, contentArea)
    function triggerRender(featureName) {
        const officerName = selectedOfficerSpan.textContent;
        const officerId = selectedOfficerSpan.dataset.officerId;

        // Tampilkan loading state
        contentArea.innerHTML = `
            <div class="text-white mt-5">
                <div class="spinner-border text-info" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h4>Memuat data ${featureName} untuk <span class="text-info fw-bold">${officerName === 'All' ? 'Semua Officer' : officerName}</span>...</h4>
                <p class="text-secondary">Tunggu sebentar...</p>
            </div>
        `;
        
        // Panggil fungsi pemuat konten utama yang ada di sistem Anda
        if (typeof loadContent === 'function') {
            loadContent(featureName, officerId, contentArea);
        } else {
             console.log(`Mensimulasikan render untuk ${featureName} dengan Officer: ${officerId}`);
        }
    }
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

    // --- Pencarian Officer ---
    searchInput.addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();
        const officerItems = officerListContainer.querySelectorAll(".officer-item"); 
        officerItems.forEach(item => {
<<<<<<< HEAD
<<<<<<< HEAD
            const name = item.dataset.name.toLowerCase();
            if (item.classList.contains('officer-item')) {
                // Jangan sembunyikan item jika dia punya class d-none (seperti item 'All' saat mode Agenda)
                if(!item.classList.contains('d-none')) {
                    item.style.setProperty('display', name.includes(keyword) ? "flex" : "none", "important");
                }
            }
        });
    });
    
    // --- Penanganan Klik Tombol Navigasi Utama ---
    navButtons.forEach(button => {
        button.addEventListener("click", function() {
            const menuId = this.id;
            
            // 1. Highlight tombol yang aktif
            navButtons.forEach(btn => btn.classList.remove('active-nav'));
            this.classList.add('active-nav');

            // 2. Mapping Nama agar cocok dengan loadContent di sistem lama
            let featureName = "";
            if (menuId === 'btnAgenda') featureName = 'agenda';
            if (menuId === 'btnBrowser') featureName = 'browser';
            if (menuId === 'btnMarket') featureName = 'market';
            if (menuId === 'btnLaporan') featureName = 'laporan';
            if (menuId === 'btnEvent') featureName = 'event';
            if (menuId === 'btnProduct') featureName = 'product';
            if (menuId === 'btnSettingPic') featureName = 'setting_pic';

            const displayName = this.textContent.trim();

            // 3. Atur Visibilitas Dropdown Officer
            if (menuId === 'btnEvent' || menuId === 'btnProduct' || menuId === 'btnSettingPic') {
                // Menu Bebas: Sembunyikan officer
                containerSelectOfficer.style.visibility = 'hidden';
                selectedOfficerSpan.dataset.officerId = 'all'; 
            } else {
                // Semua Menu Utama (Agenda, Browser, Market, Laporan): Tampilkan officer
                containerSelectOfficer.style.visibility = 'visible';
                if (itemAll) itemAll.classList.remove('d-none');
                
                // Normalisasi warna teks jika sebelumnya merah
                selectedOfficerSpan.style.color = ''; 
                selectedOfficerSpan.classList.remove('text-muted');

                // JIKA KOSONG -> LANGSUNG PAKSA JADI "ALL"
                if (!selectedOfficerSpan.dataset.officerId || selectedOfficerSpan.dataset.officerId === '') {
                    selectedOfficerSpan.textContent = 'All';
                    selectedOfficerSpan.dataset.officerId = 'all';
                }

                // Pastikan class 'active' di list sesuai dengan dataset saat ini
                const currentId = selectedOfficerSpan.dataset.officerId;
                officerListContainer.querySelectorAll(".officer-item").forEach(i => {
                    if (i.dataset.id === currentId) {
                        i.classList.add("active");
                        if (!i.querySelector('.icon-check')) {
                            const name = i.dataset.name;
                            i.innerHTML = `<span>${name}</span><i class="bi bi-check-circle text-primary icon-check"></i>`;
                        }
                    } else {
                        i.classList.remove("active");
                        const icon = i.querySelector('.icon-check');
                        if (icon) icon.remove();
                    }
                });
            }

            // 4. LANGSUNG RENDER! (Tidak ada lagi blokir 'return')
            triggerRender(displayName, featureName);
        });
    });

    // --- Penanganan Pemilihan Officer dari List ---
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
            const name = item.textContent.toLowerCase();
            if (item.classList.contains('officer-item')) {
                item.style.display = name.includes(keyword) ? "flex" : "none";
            }
        });
    });

    // --- Klik Officer (Menggunakan Event Delegation) ---
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    officerListContainer.addEventListener("click", function(event) {
        const item = event.target.closest(".list-group-item.officer-item");
        if (item) {
            const name = item.dataset.name;
            const id = item.dataset.id;
            
<<<<<<< HEAD
<<<<<<< HEAD
            // 1. Update Label Terpilih
            selectedOfficerSpan.textContent = name;
            selectedOfficerSpan.dataset.officerId = id;
            selectedOfficerSpan.style.color = ''; // Hapus warna merah
            
            officerDropdown.hide(); 

            // 2. Highlight List (Centang)
            officerListContainer.querySelectorAll(".officer-item").forEach(i => {
                i.classList.remove("active");
                const icon = i.querySelector('i');
                if (icon) icon.remove(); // Hapus icon centang lama
            });

            item.classList.add("active");
            // Tambahkan icon centang baru
            item.innerHTML = `<span>${name}</span><i class="bi bi-check-circle text-primary icon-check"></i>`;

            // 3. Render ulang berdasarkan menu yang sedang aktif
            const activeNavBtn = document.querySelector('.nav-button.active-nav');
            if (activeNavBtn) {
                const featureName = activeNavBtn.textContent.trim();
                triggerRender(featureName);
            } else {
                 contentArea.innerHTML = `
                    <div class="text-white mt-5 text-center" style="font-size: 11px;">
                        <i class="bi bi-check-circle-fill fs-1 text-success mb-3"></i>
                        <h6>Officer <span class="text-info fw-bold">${name}</span> berhasil dipilih!</h6>
                    </div>
                `;
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
            }
        }
    });

<<<<<<< HEAD
<<<<<<< HEAD
    // --- Logout ---
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    if (btnLogout) {
        btnLogout.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });
    }
<<<<<<< HEAD
<<<<<<< HEAD
    
    // =====================================================================
    // AUTOLOAD / FIRST LOAD
    // =====================================================================
    // Tunggu sebentar (100ms) agar UI selesai merender CSS, lalu otomatis klik Agenda
    setTimeout(function() {
        const btnAgenda = document.getElementById('btnAgenda');
        if (btnAgenda) {
            btnAgenda.click();
        }
    }, 100);

    // Inisialisasi: Hapus blokir navigasi awal
    // (Kode lama yang men-disable navigasi sudah dihapus)
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

    // Inisialisasi: Pastikan tombol navigasi disabled saat pertama kali dimuat
    if (selectedOfficerSpan.dataset.officerId === "" || selectedOfficerSpan.textContent === 'Pilih Officer') {
        navButtons.forEach(btn => btn.setAttribute('disabled', ''));
    }
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
<<<<<<< HEAD
    
    if (!window.pdfjsLib) {
        const s = document.createElement('script');
        s.src = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js";
        s.onload = () => {
            // PENTING: Worker harus didefinisikan agar library berfungsi
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
        };
        document.head.appendChild(s);
    }

    // ===============================
    // HANDLE EVENT MENU
    // ===============================
    if (feature === 'EVENT') {
        // Variabel Global untuk menyimpan data
        window.cachedInvitationData = [];
        
        window.paginationState = {
            currentPage: 1,
            perPage: 10,
            totalPage: 1,
            currentStatus: 2
        };
    
        window.gbState = {
            data: [],
            grouped: {},
            dates: [],
            currentDateIdx: 0,
            currentPage: 1,
            perPage: 10,
            eventId: null
        };

        // =========================================================================
        // 0. CONFIG API & FUNGSI FOTO GUESTBOOK (BARU)
        // =========================================================================
        const GUESTBOOK_API_URL = 'https://gb.lsfragrance.id/api/guestbook-photo/'; 

        window.bukaModalFoto = function(base64ImageStr, namaGuest) {
            $('#title-nama-guest').text(namaGuest);
            let container = $('#container-foto-guestbook');
            container.html('<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border text-info"></div></div>');
            
            const modal = new bootstrap.Modal(document.getElementById('modalFotoGuestbook'));
            modal.show();
        
            // Load Library Swiper jika belum ada
            if (!$('#swiper-css').length) {
                $('head').append('<link id="swiper-css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>');
                $.getScript('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', initSwiper);
            } else {
                setTimeout(initSwiper, 100);
            }
        
            function initSwiper() {
                try {
                    let jsonStr = base64ImageStr ? decodeURIComponent(escape(atob(base64ImageStr))) : '[]';
                    let images = JSON.parse(jsonStr);
                    container.empty();
        
                    if (Array.isArray(images) && images.length > 0) {
                        images.forEach(filename => {
                            container.append(`
                                <div class="swiper-slide d-flex align-items-center justify-content-center">
                                    <img src="${GUESTBOOK_API_URL + filename}" 
                                         style="max-width: 100%; max-height: 100%; object-fit: contain;" 
                                         onclick="window.open('${GUESTBOOK_API_URL + filename}', '_blank')">
                                </div>
                            `);
                        });
                    }
        
                    // Inisialisasi Swiper
                    if (window.mySwiper) window.mySwiper.destroy();
                    window.mySwiper = new Swiper(".mySwiper", {
                        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
                        pagination: { el: ".swiper-pagination", clickable: true },
                        zoom: true // Fitur Zoom aktif
                    });
                } catch (e) {
                    container.html('<div class="text-center text-danger p-5">Format foto tidak valid</div>');
                }
            }
        };
    
        // =========================================================================
            // 1. INJECT CUSTOM CSS
            // =========================================================================
            if (!document.getElementById('event-custom-style')) {
                // Inject flatpickr
                const flatLink = document.createElement('link');
                flatLink.rel = 'stylesheet';
                flatLink.href = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css';
                document.head.appendChild(flatLink);
    
                // Inject style — pakai textContent bukan insertAdjacentHTML
                // agar backtick / karakter khusus di dalam CSS tidak memecah template literal JS
                const styleEl = document.createElement('style');
                styleEl.id = 'event-custom-style';
                styleEl.textContent = `
                    /* === RESET & FOUNDATION === */
                    .event-card { background-color: #1c2128; border-radius: 12px; }
                    .event-input {
                        background-color: #0d1117 !important;
                        color: #e6edf3 !important;
                        border: 1px solid #30363d !important;
                        border-radius: 8px;
                        color-scheme: dark;
                    }
                    .event-input::placeholder { color: rgba(230,237,243,.5) !important; opacity: 1; }
    
                    /* === GUESTBOOK CANVAS CARD === */
                    .gb-canvas-card {
                        display: flex;
                        flex-direction: column;
                        background-color: #1c2128;
                        border-radius: 12px;
                        border: 1px solid #30363d;
                        
                        /* KUNCI 1: Fallback vh standar untuk Tablet/Monitor Lama */
                        max-height: calc(100vh - 130px);
                        /* KUNCI 2: dvh untuk HP modern (akan menimpa vh jika disupport) */
                        max-height: calc(100dvh - 130px); 
                        
                        min-height: 350px;
                        overflow: hidden; 
                        box-shadow: 0 10px 30px rgba(0,0,0,0.15); 
                    }
                    @media (max-width: 991.98px) {
                        .gb-canvas-card { 
                            max-height: calc(100vh - 110px) !important; 
                            max-height: calc(100dvh - 110px) !important;
                            min-height: 350px !important; 
                        }
                    }
                    @media (max-width: 576px) {
                        .gb-canvas-card { 
                            max-height: calc(100vh - 100px) !important; 
                            max-height: calc(100dvh - 100px) !important;
                        }
                    }

                    /* === TABLE SCROLL AREA === */
                    .table-scroll-area {
                        /* KUNCI 3: auto agar elastis menyesuaikan ruang yang tersisa */
                        flex: 1 1 auto; 
                        overflow-y: auto; 
                        overflow-x: auto; 
                        background: #1c2128;
                        min-height: 0;
                        -webkit-overflow-scrolling: touch;
                        position: relative;
                        padding-bottom: 12px; 
                    }
                    
                    /* Custom Scrollbar Modern */
                    .table-scroll-area::-webkit-scrollbar { width: 8px; height: 8px; }
                    .table-scroll-area::-webkit-scrollbar-track { background: #161b22; }
                    .table-scroll-area::-webkit-scrollbar-thumb { background: #30363d; border-radius: 4px; }
                    .table-scroll-area::-webkit-scrollbar-thumb:hover { background: #484f58; }

                    /* === MARKET TABLE & STICKY HEADER === */
                    .market-table {
                        font-size: .8rem;
                        width: 100%;
                        table-layout: fixed;
                        border-collapse: separate;
                        border-spacing: 0;
                        min-width: 620px;
                    }
                    .market-table th {
                        background-color: #161b22 !important;
                        color: #8b949e !important;
                        font-size: .72rem;
                        font-weight: 600;
                        letter-spacing: .04em;
                        text-transform: uppercase;
                        padding: 12px 10px;
                        /* STICKY HEADER */
                        position: -webkit-sticky !important;
                        position: sticky !important;
                        top: 0 !important;
                        z-index: 10 !important;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.1), 0 1px 0 #30363d; /* Shadow pemisah header dan tabel */
                        border-bottom: none !important;
                        white-space: nowrap;
                    }
                    .market-table td {
                        background-color: #1c2128;
                        color: #e6edf3;
                        vertical-align: middle;
                        padding: 12px 10px;
                        border-bottom: 1px solid #21262d;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        transition: background-color .15s ease;
                    }
                    .market-table tbody tr:hover td { background-color: #21262d; }
    
                    /* === GB DATE HEADER === */
                    .gb-date-header {
                        flex: 0 0 auto;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 10px 16px;
                        background: #161b22;
                        border-bottom: 1px solid #30363d;
                        position: relative;
                        z-index: 20;
                    }
    
                    /* === GB TOOLBAR === */
                    .gb-toolbar {
                        flex: 0 0 auto;
                        background: #0d1117;
                        padding: 8px 14px;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        border-bottom: 1px solid #30363d;
                        gap: 10px;
                        position: relative;
                        z-index: 15;
                    }
    
                    /* === PAGINATION === */
                    .gb-pagination-container {
                        flex: 0 0 auto; /* Cegah elemen ini mengecil/tertekan */
                        padding: 12px 16px;
                        background: #161b22;
                        border-top: 1px solid #30363d;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        gap: 6px;
                        position: relative;
                        z-index: 20;
                        box-shadow: 0 -4px 15px rgba(0,0,0,0.2); /* Shadow tegas ke atas */
                    }
                    .gb-page-btn {
                        background: #21262d;
                        border: 1px solid #30363d;
                        color: #8b949e;
                        border-radius: 8px;
                        width: 34px; height: 34px;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        font-size: .8rem;
                        cursor: pointer;
                        transition: all .15s;
                        padding: 0;
                    }
                    .gb-page-btn:hover:not(:disabled) { background: #30363d; color: #e6edf3; border-color: #484f58; }
                    .gb-page-btn:disabled { opacity: .3; cursor: not-allowed; }
                    .gb-page-info {
                        background: #0d1117;
                        border: 1px solid #30363d;
                        border-radius: 8px;
                        padding: 0 18px;
                        height: 34px;
                        display: inline-flex;
                        align-items: center;
                        color: #e6edf3;
                        font-size: .78rem;
                        font-weight: 600;
                        white-space: nowrap;
                        min-width: 100px;
                        justify-content: center;
                    }
    
                    /* === MODAL INVITATION === */
                    #modalInvitation .modal-dialog { height: calc(100dvh - 3rem); margin-top: 1.5rem; margin-bottom: 1.5rem; }
                    #modalInvitation .modal-content { height: 100%; max-height: 100%; display: flex; flex-direction: column; overflow: hidden; background-color: #1c2128; color: #e6edf3; border: 1px solid #30363d; border-radius: 14px; }
                    #modalInvitation .modal-body { display: flex !important; flex-direction: column !important; padding: 0 !important; flex-grow: 1; overflow: hidden; }
                    #modalInvitation .invitation-top-header { padding: 12px 16px; border-bottom: 1px solid #30363d; flex-shrink: 0; background: #161b22; display: flex !important; align-items: center !important; justify-content: space-between !important; gap: 10px; width: 100%; }
                    #modalInvitation .nav-pills-custom { display: flex !important; flex-wrap: nowrap !important; white-space: nowrap !important; gap: 5px; }
                    #modalInvitation .nav-pills-custom .nav-link { color: #8b949e; border-radius: 8px; font-size: .75rem; border: 1px solid #30363d; background-color: #21262d; padding: 6px 12px; white-space: nowrap; transition: all .2s; }
                    #modalInvitation .nav-pills-custom .nav-link.active { background-color: #1f6feb !important; color: #fff !important; border-color: #1f6feb !important; font-weight: 600; }
                    #filter-btn-container .nav-link.active { background-color: #1f6feb !important; color: #fff !important; border-color: #1f6feb !important; }
                    @media (min-width: 768px) {
                        #modalInvitation .invitation-top-header > div:nth-child(1) { flex: 0 0 auto !important; }
                        #modalInvitation #filter-btn-container { flex: 1 !important; display: flex; justify-content: center; }
                        #modalInvitation .invitation-top-header > div:nth-child(3) { flex: 0 0 auto !important; }
                        #modalInvitation .modal-dialog { max-width: 95% !important; }
                    }
                    #modalInvitation #pills-tabContent-invitation { flex-grow: 1; display: flex !important; flex-direction: column !important; overflow: hidden; }
                    #modalInvitation .tab-pane { display: none !important; }
                    #modalInvitation .tab-pane.active { display: flex !important; flex-direction: column !important; height: 100%; flex-grow: 1; overflow: hidden; }
                    .paging-freeze-footer { flex-shrink: 0; background: #161b22; border-top: 1px solid #30363d; padding: 10px 16px; position: relative; z-index: 20; box-shadow: 0 -4px 12px rgba(0,0,0,.35); }
    
                    /* === TOP NAV BUTTONS === */
                    .top-nav-btn { background-color: #21262d; color: #8b949e; border: 1px solid #30363d; transition: all .2s; font-weight: 500; display: inline-flex !important; align-items: center; border-radius: 8px; }
                    .top-nav-btn:hover:not(.btn-dimmed) { background-color: #30363d; color: #e6edf3; }
                    .btn-dimmed { background-color: #161b22 !important; color: #484f58 !important; border-color: #21262d !important; opacity: .55; cursor: not-allowed; pointer-events: none; }
                    .modal-dark .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
    
                    /* === INLINE EDIT ROW === */
                    .gb-edit-row { background-color: #1c2128; }
                    .gb-edit-row > td { padding: 0 !important; border-bottom: 2px solid #1f6feb !important; background-color: transparent !important; }
                    .edit-form-container {
                        background: linear-gradient(160deg, #161b22 0%, #0d1117 100%);
                        border: 1px solid #30363d;
                        border-top: 2px solid #1f6feb;
                        border-radius: 0 0 12px 12px;
                        padding: 20px;
                        margin: 0;
                    }
                    .edit-form-container .form-label {
                        font-size: .72rem !important;
                        color: #8b949e !important;
                        font-weight: 600;
                        letter-spacing: .04em;
                        text-transform: uppercase;
                        margin-bottom: 6px !important;
                        display: block;
                    }
                    .edit-form-container .modern-input {
                        background-color: #0d1117 !important;
                        border: 1px solid #30363d !important;
                        color: #e6edf3 !important;
                        border-radius: 8px !important;
                        padding: .5rem .85rem !important;
                        font-size: .85rem !important;
                        height: 40px;
                        width: 100%;
                        transition: border-color .2s, box-shadow .2s;
                    }
                    .edit-form-container .modern-input:focus {
                        border-color: #1f6feb !important;
                        box-shadow: 0 0 0 3px rgba(31,111,235,.15) !important;
                        outline: none;
                        background-color: #0d1117 !important;
                    }
                    .edit-form-container textarea.modern-input {
                        height: auto !important;
                        min-height: 80px;
                        resize: vertical;
                        line-height: 1.5;
                        padding-top: 10px !important;
                    }
                    .edit-form-header {
                        display: flex; align-items: center; justify-content: space-between;
                        margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid #30363d;
                    }
                    .edit-form-header .edit-title {
                        font-size: .8rem; font-weight: 700; color: #58a6ff;
                        letter-spacing: .03em; display: flex; align-items: center; gap: 8px;
                    }
                    .edit-form-footer {
                        display: flex; justify-content: flex-end; gap: 10px;
                        margin-top: 18px; padding-top: 14px; border-top: 1px solid #30363d;
                    }
    
                    /* === CSS GRID LAYOUT INLINE EDIT === */
                    .edit-grid-row { display: grid; gap: 14px; margin-bottom: 14px; }
                    .edit-grid-row-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
                    .edit-grid-row-custom { grid-template-columns: 1fr 1fr 1.8fr 1fr; }
                    @media (max-width: 1100px) {
                        .edit-grid-row-4 { grid-template-columns: 1fr 1fr; }
                        .edit-grid-row-custom { grid-template-columns: 1fr 1fr; }
                    }
                    @media (max-width: 640px) {
                        .edit-grid-row-4 { grid-template-columns: 1fr; }
                        .edit-grid-row-custom { grid-template-columns: 1fr; }
                        .edit-form-container { padding: 14px; }
                    }
    
                    /* === SELECT2 DARK THEME === */
                    .select2-container--default .select2-selection--single {
                        background-color: #0d1117 !important; border: 1px solid #30363d !important;
                        border-radius: 8px !important; height: 40px !important; transition: border-color .2s;
                    }
                    .select2-container--default.select2-container--open .select2-selection--single {
                        border-color: #1f6feb !important; box-shadow: 0 0 0 3px rgba(31,111,235,.15) !important;
                    }
                    .select2-container--default .select2-selection--single .select2-selection__rendered {
                        color: #e6edf3 !important; line-height: 38px !important;
                        padding-left: 12px !important; font-size: .85rem !important;
                    }
                    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 38px !important; right: 8px !important; }
                    .select2-container { width: 100% !important; }
                    .select2-dropdown {
                        background-color: #161b22 !important; border: 1px solid #30363d !important;
                        border-radius: 10px !important; box-shadow: 0 12px 30px rgba(0,0,0,.5) !important; overflow: hidden;
                    }
                    .select2-search--dropdown .select2-search__field {
                        background-color: #0d1117 !important; color: #e6edf3 !important;
                        border: 1px solid #30363d !important; border-radius: 6px !important; padding: 6px 10px !important;
                    }
                    .select2-results__option { color: #8b949e !important; font-size: .83rem !important; padding: 8px 14px !important; }
                    .select2-results__option--highlighted[aria-selected] { background-color: #1f6feb !important; color: #fff !important; }
                    .select2-results__option[aria-selected=true] { background-color: #21262d !important; color: #58a6ff !important; font-weight: 600; }
    
                    /* === UTILITIES === */
                    .text-truncate-2 {
                        display: -webkit-box; -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical; overflow: hidden;
                        white-space: normal; word-break: break-word;
                    }
                    @media (max-width: 576px) {
                        .gb-date-header { padding: 8px 12px; }
                        .gb-pagination-container { padding: 8px 12px; }
                        .market-table { font-size: .72rem; }
                        .gb-page-btn { width: 30px; height: 30px; }
                        .gb-page-info { padding: 0 12px; font-size: .72rem; }
                    }
                `;
                document.head.appendChild(styleEl);
            }
    
        // =========================================================================
        // 2. RENDER TAMPILAN HTML UTAMA & MODAL
        // =========================================================================
        targetElement.innerHTML = `
            <div class="container-fluid px-1 mt-1 text-start fade-in">
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded-3 shadow-sm" style="background-color: #161b22; border: 1px solid #30363d;">
                    <div style="width: 150px;">
                        <select class="form-select form-select-sm border-0 event-input fw-bold" id="globalEventSelector" onchange="window.handleGlobalEventChange()" style="font-size: 0.82rem;">
                            <option value="">-- Memuat Event... --</option>
                        </select>
                    </div>
                    <div class="btn-group shadow-sm" role="group">
                        <button type="button" class="btn btn-sm top-nav-btn px-3 btn-dimmed" id="btnNavInvitation" onclick="window.checkAndOpenModal('modalInvitation')">
                            <i class="bi bi-envelope-paper me-1"></i> Invitation
                        </button>
                        <button type="button" class="btn btn-sm top-nav-btn px-3 btn-dimmed" id="btnNavGuestbook" onclick="window.openGuestbookModal()">
                            <i class="bi bi-book me-1"></i> Guestbook
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-sm fw-bold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalCreateEvent" style="border-radius: 8px;">
                            <i class="bi bi-plus-circle me-1"></i> Buat Event
                        </button>
                    </div>
                </div>
    
                <div id="eventContentArea">
                    <div class="card event-card border-0 shadow-sm d-flex align-items-center justify-content-center" style="min-height: 40vh;">
                        <div class="text-center p-5" style="color: #484f58;">
                            <i class="bi bi-calendar-event fs-1 d-block mb-3"></i>
                            <p class="mb-0 small">Pilih event dan klik menu di atas.</p>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="modal fade modal-dark" id="modalCreateEvent" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg" style="background: #161b22; border: 1px solid #30363d; border-radius: 14px;">
                        <div class="modal-header py-2 border-0" style="background: #0d1117; border-radius: 14px 14px 0 0;">
                            <h6 class="modal-title fw-bold mb-0 text-white"><i class="bi bi-calendar-plus text-primary me-2"></i>Buat Event Baru</h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form id="formCreateEvent">
                                <input type="hidden" id="event_start_date" name="event_start_date" required>
                                <input type="hidden" id="event_end_date" name="event_end_date" required>
                                <input type="hidden" id="invitation_start_date" name="invitation_start_date" required>
                                <input type="hidden" id="invitation_end_date" name="invitation_end_date" required>
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-sm event-input py-2" id="eventName" name="name" required placeholder="Nama Event">
                                </div>
                                <div class="mb-3">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-0" style="background:#0d1117; color: #58a6ff;"><i class="bi bi-calendar-event"></i></span>
                                        <input type="text" class="form-control event-input border-start-0 py-2" id="eventDateRange" required placeholder="Pilih Mulai - Selesai Event">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-0" style="background:#0d1117; color: #d29922;"><i class="bi bi-envelope-paper"></i></span>
                                        <input type="text" class="form-control event-input border-start-0 py-2" id="invitationDateRange" required placeholder="Pilih Mulai - Selesai Invitation">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2" id="btnSubmitEvent" style="border-radius: 10px;">BUAT EVENT</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="modal fade modal-dark" id="modalInvitation" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header py-2 border-0" style="background: #0d1117;">
                            <h6 class="modal-title fw-bold mb-0">
                                <span class="badge ms-2" id="lblEventNameInv" style="font-size: 0.7rem; background: #21262d; color: #8b949e; border: 1px solid #30363d;"></span>
                            </h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0 d-flex flex-column">
                            <div class="d-flex w-100 align-items-center invitation-top-header">
                                <div>
                                    <ul class="nav nav-pills nav-pills-custom" id="pills-tab-invitation" role="tablist">
                                        <li class="nav-item ms-1" role="presentation">
                                            <button class="nav-link shadow-sm" data-bs-toggle="pill" data-bs-target="#pills-template" type="button" role="tab" onclick="window.toggleTabUI('design')">
                                                <i class="bi bi-chat-square-text me-1"></i> Design
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active shadow-sm" data-bs-toggle="pill" data-bs-target="#pills-list" type="button" role="tab" onclick="window.loadInvitationList(); window.toggleTabUI('list')">
                                                <i class="bi bi-card-list me-1"></i> List Undangan
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mx-auto" id="filter-btn-container">
                                    <div class="nav nav-pills nav-pills-custom d-flex gap-2">
                                        <button type="button" class="nav-link active shadow-sm" onclick="window.applyFilterPill(this, 2)">
                                            <i class="bi bi-hourglass-split me-1"></i> Belum Terkirim
                                        </button>
                                        <button type="button" class="nav-link shadow-sm" onclick="window.applyFilterPill(this, 3)">
                                            <i class="bi bi-check2-all me-1"></i> Terkirim
                                        </button>
                                    </div>
                                </div>
                                <div class="text-end" style="min-width: 170px;">
                                    <button type="button" id="btnActionBuatList" class="btn btn-sm btn-primary fw-bold shadow-sm" onclick="window.startBatchProcess()" style="border-radius: 8px;">
                                        <i class="bi bi-magic me-1"></i> Generate Undangan
                                    </button>
                                </div>
                            </div>
    
                            <div class="tab-content flex-grow-1 d-flex flex-column" id="pills-tabContent-invitation">
                                <div class="tab-pane fade show active" id="pills-list" role="tabpanel">
                                    <div id="invitationAccordionArea">
                                        <div class="text-center py-5" style="color: #484f58;"><i class="bi bi-info-circle fs-4 d-block mb-1"></i>Memuat data...</div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-template" role="tabpanel" style="overflow-y:auto; padding: 20px;">
                                    <div class="row px-2">
                                        <div class="col-md-5 mb-4">
                                            <div class="card border-0 shadow-sm h-100" style="background-color: #161b22; border-radius: 16px; border: 1px solid #30363d !important;">
                                                <div class="card-body p-4">
                                                    <form id="formTemplateEvent">
                                                        <div class="mb-4">
                                                            <div class="upload-zone p-4 text-center rounded-3 mb-2" style="border: 2px dashed #30363d; background: rgba(255,255,255,0.02);">
                                                                <i class="bi bi-image mb-2" style="font-size: 2.5rem; color: #484f58;"></i>
                                                                <input type="file" class="form-control form-control-sm event-input mt-3" id="fileTemplate" name="template_file" accept=".pdf, image/jpeg, image/png" required>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-lg py-2" id="btnSubmitTemplate" style="border-radius: 10px;">
                                                            <i class="bi bi-check2-circle me-1"></i> SIMPAN TEMPLATE
                                                        </button>
                                                    </form>
                                                    <div id="lockedTemplateMessage" class="d-none text-center p-4 mt-2 rounded-3" style="background: rgba(25, 135, 84, 0.05); border: 1px solid rgba(25, 135, 84, 0.3);">
                                                        <i class="bi bi-shield-lock-fill text-success mb-2 d-block" style="font-size: 2.5rem;"></i>
                                                        <h6 class="text-success fw-bold">Template Terkunci</h6>
                                                        <p class="small mb-0" style="color: #8b949e;">Template sudah diatur untuk event ini.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7 mb-4">
                                            <div class="card border-0 shadow-sm h-100" style="background-color: #161b22; border-radius: 16px; border: 1px solid #30363d !important;">
                                                <div class="card-body p-4 d-flex flex-column">
                                                    <div id="previewContainer" class="flex-grow-1 d-flex align-items-center justify-content-center position-relative rounded-3" style="background: #0d1117; border: 1px solid #30363d; min-height: 350px; overflow: hidden;">
                                                        <div id="previewEmpty" class="text-center" style="color: #484f58;">
                                                            <i class="bi bi-file-earmark-image d-block mb-2" style="font-size: 3rem; opacity: 0.5;"></i>
                                                            <span class="small">Belum ada template aktif</span>
                                                        </div>
                                                        <div id="previewLoader" class="position-absolute top-50 start-50 translate-middle d-none">
                                                            <div class="spinner-border text-primary" role="status"></div>
                                                        </div>
                                                        <img id="previewImage" src="" class="img-fluid d-none shadow" style="max-height: 400px; border-radius: 8px;">
                                                        <iframe id="previewPdf" src="" class="w-100 h-100 d-none" style="min-height: 400px; border: none; border-radius: 8px;"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="modal fade" id="modalPreviewCRM" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 18px; background: #f8f9fa;">
                        <div class="d-flex justify-content-between align-items-center px-3 pt-3 pb-2">
                            <span class="fw-bold text-dark" style="font-size: 14px;">Preview Undangan</span>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="px-3 pb-2">
                            <div class="bg-white rounded-4 p-2 border shadow-sm d-flex align-items-center justify-content-center" style="min-height: 320px; max-height: 70vh; overflow: hidden;">
                                <img id="imgPreviewUndanganCRM" src="" class="img-fluid rounded-3" style="max-height: 65vh; object-fit: contain;">
                            </div>
                        </div>
                        <div class="px-3 pb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Share minimal 1x untuk tercatat</small>
                                <span id="shareCountLabel" class="badge bg-light text-dark border">0x</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <button id="btnShareCRM" class="btn btn-info btn-sm w-100 fw-bold rounded-pill py-1 shadow-sm" style="font-size: 11px;">
                                        <i class="bi bi-share-fill"></i> Share
                                    </button>
                                </div>
                                <div class="col-6">
                                    <a id="btnDownloadCRM" href="" target="_blank" class="btn btn-outline-secondary btn-sm w-100 fw-bold rounded-pill py-1 shadow-sm" style="font-size: 11px;">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            // EDTI GB (Data Row)
            <div class="modal fade modal-dark" id="modalEditGuestbook" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content shadow-lg" style="background: #161b22; border: 1px solid #30363d; border-radius: 12px;">
                        
                        <div class="modal-header py-2 px-3 border-0" style="background: #0d1117; border-radius: 12px 12px 0 0; border-bottom: 1px solid #30363d !important;">
                            <h6 class="modal-title fw-bold mb-0 text-white" style="font-size: 0.85rem;">
                                <i class="bi bi-pencil-square text-primary me-2"></i>
                                <span id="modalEdit-titleName">Edit Data Guestbook</span>
                            </h6>
                            <button type="button" class="btn-close btn-close-white" style="font-size: 0.7rem;" data-bs-dismiss="modal"></button>
                        </div>
                        
                        <div class="modal-body p-3 text-start">
                            <input type="hidden" id="modalEdit-id">
                            
                            <div class="row g-2 mb-2">
                                <div class="col-md-3">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Nama <span class="text-danger">*</span></label>
                                    <input type="text" id="modalEdit-nama" class="form-control event-input form-control-sm" placeholder="Nama PIC">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">No. HP / WA <span class="text-danger">*</span></label>
                                    <input type="text" id="modalEdit-phone" class="form-control event-input form-control-sm" placeholder="08xxx">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Perusahaan <span class="text-danger">*</span></label>
                                    <input type="text" id="modalEdit-company" class="form-control event-input form-control-sm" placeholder="Nama Perusahaan">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Model Bisnis</label>
                                    <select id="modalEdit-model" class="form-select event-input form-select-sm">
                                        <option value="">Pilih Model</option>
                                        <option value="Open Label">Open Label</option>
                                        <option value="Close Label">Close Label</option>
                                    </select>
                                </div>
                            </div>
            
                            <div class="row g-2 mb-2">
                                <div class="col-md-3">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Kategori <span class="text-danger">*</span></label>
                                    <select id="modalEdit-kategori" class="form-select event-input form-select-sm">
                                        <option value="">Pilih Kategori</option>
                                        <option value="NONE">NONE</option>
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
                                <div class="col-md-3">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Zone <span class="text-danger">*</span></label>
                                    <select id="modalEdit-zone" class="form-select event-input form-select-sm">
                                        <option value="">Pilih Zone</option>
                                        <option value="JABODETABEK">JABODETABEK</option>
                                        <option value="JABAR">JABAR</option>
                                        <option value="JATENG - JATIM">JATENG - JATIM</option>
                                        <option value="SUMATERA">SUMATERA</option>
                                        <option value="BALI - KALIMANTAN - SULAWESI">BALI - KALIMANTAN - SULAWESI</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Provinsi <span class="text-danger">*</span></label>
                                    <select id="modalEdit-provinsi" class="form-select event-input form-select-sm select2-modal-edit" onchange="window.fetchGbRegionModal(this)">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Kota <span class="text-danger">*</span></label>
                                    <select id="modalEdit-kota" class="form-select event-input form-select-sm select2-modal-edit">
                                        <option value="">Pilih Kota</option>
                                    </select>
                                </div>
                            </div>
            
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Alamat Lengkap</label>
                                    <textarea id="modalEdit-alamat" class="form-control event-input" rows="2" style="font-size: 0.8rem; min-height: 50px;" placeholder="Jl. Contoh No.123..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Toko Multicabang</label>
                                    <textarea id="modalEdit-multicabang" class="form-control event-input" rows="2" style="font-size: 0.8rem; min-height: 50px;" placeholder="Daftar Cabang Lain (Jika ada)..."></textarea>
                                </div>
                            </div>
                            
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Media Sosial</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-0" style="background:#21262d; color:#8b949e;"><i class="bi bi-instagram"></i></span>
                                        <input type="text" id="modalEdit-sosmed" class="form-control event-input border-start-0" placeholder="IG / FB / TikTok">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Marketplace</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-0" style="background:#21262d; color:#8b949e;"><i class="bi bi-shop-window"></i></span>
                                        <input type="text" id="modalEdit-marketplace" class="form-control event-input border-start-0" placeholder="Shopee / Tokopedia">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-uppercase fw-bold mb-1" style="font-size: 0.65rem; color: #8b949e;">Catatan / Keterangan</label>
                                    <input type="text" id="modalEdit-keterangan" class="form-control event-input form-control-sm" placeholder="Opsional...">
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer py-2 px-3 border-0" style="background: #0d1117; border-top: 1px solid #30363d !important; border-radius: 0 0 12px 12px;">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-1" style="font-size: 0.75rem;" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary rounded-pill px-4 py-1 fw-bold shadow-sm" id="btnSaveModalEdit" onclick="window.saveGuestbookModal()" style="font-size: 0.75rem;">
                                <i class="bi bi-save2 me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="modal fade modal-dark" id="modalFotoGuestbook" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content shadow-lg" style="background-color: #000; border: 1px solid #333; color: #fff;">
                        <div class="modal-header py-2 border-0" style="background-color: #000;">
                            <h6 class="modal-title fw-bold text-info"><i class="bi bi-images me-2"></i> <span id="title-nama-guest"></span></h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="swiper mySwiper" style="width: 100%; height: 50vh;">
                                <div class="swiper-wrapper" id="container-foto-guestbook">
                                    </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    
        // =========================================================================
        // 3. INISIALISASI FLATPICKR & HELPER
        // =========================================================================
        setTimeout(() => {
            if (typeof flatpickr !== 'undefined') {
                flatpickr("#eventDateRange", { mode: "range", dateFormat: "Y-m-d", altInput: true, altFormat: "d M Y", onChange: function(selectedDates, dateStr, instance) { if (selectedDates.length === 2) { document.getElementById('event_start_date').value = instance.formatDate(selectedDates[0], "Y-m-d"); document.getElementById('event_end_date').value = instance.formatDate(selectedDates[1], "Y-m-d"); } else { document.getElementById('event_start_date').value = ''; document.getElementById('event_end_date').value = ''; } } });
                flatpickr("#invitationDateRange", { mode: "range", dateFormat: "Y-m-d", altInput: true, altFormat: "d M Y", onChange: function(selectedDates, dateStr, instance) { if (selectedDates.length === 2) { document.getElementById('invitation_start_date').value = instance.formatDate(selectedDates[0], "Y-m-d"); document.getElementById('invitation_end_date').value = instance.formatDate(selectedDates[1], "Y-m-d"); } else { document.getElementById('invitation_start_date').value = ''; document.getElementById('invitation_end_date').value = ''; } } });
            }
        }, 100);
    
        const showAppToast = (title, message, type='success') => {
            let toastContainer = document.getElementById('app-toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'app-toast-container';
                toastContainer.className = 'toast-container position-fixed top-0 start-50 translate-middle-x p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }
            const bgClass = type === 'success' ? 'bg-success' : (type === 'danger' ? 'bg-danger' : 'bg-warning');
            const icon = type === 'success' ? 'check-circle-fill' : (type === 'danger' ? 'x-circle-fill' : 'exclamation-triangle-fill');
            toastContainer.insertAdjacentHTML('beforeend', `<div class="toast align-items-center text-white ${bgClass} border-0 show shadow-lg" style="border-radius: 10px;"><div class="d-flex"><div class="toast-body fw-medium" style="font-size: 0.85rem;"><i class="bi bi-${icon} me-2 fs-6"></i> ${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.closest('.toast').remove()"></button></div></div>`);
            setTimeout(() => { const t = toastContainer.lastElementChild; if(t) { t.style.opacity = '0'; t.style.transition = 'opacity 0.4s'; setTimeout(() => t.remove(), 400); } }, 4000);
        };
    
        window.currentPreviewCustomerId = null;
        window.currentShareCount = 0;
    
        // =========================================================================
        // 4. LOGIKA GLOBAL EVENT SELECTOR
        // =========================================================================
        window.loadGlobalEvents = function() {
            const selectEl = document.getElementById('globalEventSelector');
            fetch('{{ route("report.doctor.events.list") }}', { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                if(data.success && data.data.length > 0) {
                    const activeEvents = data.data.filter(ev => parseInt(ev.status) === 1);
                    if(activeEvents.length > 0) {
                        let optionsHtml = '<option value="">Pilih Event</option>';
                        activeEvents.forEach(ev => { optionsHtml += `<option value="${ev.id}">${ev.name}</option>`; });
                        selectEl.innerHTML = optionsHtml;
                    } else { selectEl.innerHTML = '<option value="">-- Tidak ada event aktif --</option>'; }
                } else { selectEl.innerHTML = '<option value="">-- Tidak ada event aktif --</option>'; }
                window.handleGlobalEventChange();
            }).catch(err => console.error(err));
        };
    
        window.handleGlobalEventChange = function() {
            const selectEl = document.getElementById('globalEventSelector');
            const btnInv = document.getElementById('btnNavInvitation');
            const btnGb = document.getElementById('btnNavGuestbook');
            const eventId = selectEl.value;
            const eventName = selectEl.options[selectEl.selectedIndex]?.text || '';
    
            if(eventId) {
                btnInv.classList.remove('btn-dimmed');
                btnGb.classList.remove('btn-dimmed');
                document.getElementById('lblEventNameInv').innerText = eventName;
            } else {
                btnInv.classList.add('btn-dimmed');
                btnGb.classList.add('btn-dimmed');
                document.getElementById('lblEventNameInv').innerText = '';
                document.getElementById('eventContentArea').innerHTML = `
                    <div class="card event-card border-0 shadow-sm d-flex align-items-center justify-content-center" style="min-height: 40vh;">
                        <div class="text-center p-5" style="color: #484f58;">
                            <i class="bi bi-calendar-event fs-1 d-block mb-3"></i>
                            <p class="mb-0 small">Pilih event dan klik menu di atas.</p>
                        </div>
                    </div>`;
            }
        };
    
        window.checkAndOpenModal = function(modalId) {
            const eventId = document.getElementById('globalEventSelector').value;
            if(!eventId) {
                showAppToast('Informasi', 'Silakan pilih event terlebih dahulu!', 'warning');
                document.getElementById('globalEventSelector').focus(); return;
            }
            const modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById(modalId));
            modalInstance.show();
            if(modalId === 'modalInvitation') {
                window.loadInvitationList();
                window.loadTemplatePreview();
            }
        };
    
        // =========================================================================
        // 5. LOGIKA PREVIEW TEMPLATE
        // =========================================================================
        window.loadTemplatePreview = function() {
            const eventId = document.getElementById('globalEventSelector').value;
            const previewEmpty = document.getElementById('previewEmpty');
            const previewLoader = document.getElementById('previewLoader');
            const previewImage = document.getElementById('previewImage');
            const previewPdf = document.getElementById('previewPdf');
            const formTemplate = document.getElementById('formTemplateEvent');
            const lockedMessage = document.getElementById('lockedTemplateMessage');
            if(!eventId) return;
            const urlInfo = `{{ url('report/doctor/events') }}/${eventId}/template-info`;
            previewImage.classList.add('d-none');
            previewPdf.classList.add('d-none');
            previewEmpty.classList.remove('d-none');
            previewLoader.classList.remove('d-none');
            fetch(urlInfo, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                previewLoader.classList.add('d-none');
                if(data.success && data.file_url) {
                    previewEmpty.classList.add('d-none');
                    if (data.extension === 'pdf') {
                        previewPdf.src = data.file_url;
                        previewPdf.classList.remove('d-none');
                    } else {
                        previewImage.src = data.file_url;
                        previewImage.classList.remove('d-none');
                    }
                    formTemplate.classList.add('d-none');
                    lockedMessage.classList.remove('d-none');
                } else {
                    previewEmpty.classList.remove('d-none');
                    previewImage.src = "";
                    previewPdf.src = "";
                    formTemplate.classList.remove('d-none');
                    lockedMessage.classList.add('d-none');
                }
            }).catch(err => {
                console.error("Gagal load preview info:", err);
                previewLoader.classList.add('d-none');
            });
        };
    
        // =========================================================================
        // 6. LOGIKA LISTING INVITATION
        // =========================================================================
        window.loadInvitationList = function() {
            const eventId = document.getElementById('globalEventSelector').value;
            const area = document.getElementById('invitationAccordionArea');
            if(!eventId) return;
            area.innerHTML = `<div class="text-center py-5"><div class="spinner-border spinner-border-sm text-primary mb-2"></div><div class="small" style="color:#484f58">Memuat data...</div></div>`;
            const url = `{{ url('report/doctor/events') }}/${eventId}/invitations`;
            fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(res => { if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`); return res.json(); })
            .then(data => {
                if(data.success) {
                    window.cachedInvitationData = data.data || [];
                    window.renderInvitationCards();
                }
            }).catch(err => {
                area.innerHTML = `<div class="text-center text-danger py-5"><i class="bi bi-exclamation-triangle fs-2 d-block mb-2"></i><h6 class="fw-bold">Gagal Memuat Data</h6></div>`;
            });
        };
    
        window.renderInvitationCards = function() {
            const area = document.getElementById('invitationAccordionArea');
            const data = window.cachedInvitationData;
            const { currentPage, perPage, currentStatus } = window.paginationState;
            const filtered = data.filter(x => parseInt(x.status) === currentStatus);
            const totalPage = Math.ceil(filtered.length / perPage) || 1;
            window.paginationState.totalPage = totalPage;
            const start = (currentPage - 1) * perPage;
            const paginated = filtered.slice(start, start + perPage);
    
            let html = `<div class="d-flex flex-column" style="height: 100%;">
                            <div class="table-scroll-area">
                                <table class="table market-table mb-0 w-100">
                                    <thead>
                                        <tr>
                                            <th style="width:44px" class="text-center">#</th>
                                            <th style="width:150px">Kota</th>
                                            <th>Customer</th>
                                            <th style="width:100px" class="text-center">Officer</th>
                                            <th style="width:110px" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
    
            if (paginated.length === 0) {
                html += `<tr><td colspan="5" class="text-center py-5" style="color:#484f58">Tidak ada data.</td></tr>`;
            } else {
                html += paginated.map((c, i) => {
                    let btnAksi = '';
                    if(parseInt(c.status) === 2) {
                        btnAksi = `<button class="btn btn-success btn-sm rounded-pill py-0 px-2 shadow-sm" onclick="event.stopPropagation(); window.markAsSentCRM('${c.customer_id}')" style="font-size: 10px;"><i class="bi bi-send-check"></i> Terkirim</button>`;
                    } else if (parseInt(c.status) >= 3) {
                        btnAksi = `<span class="badge bg-primary rounded-pill shadow-sm" style="font-size: 9px;"><i class="bi bi-check-all"></i> Terkirim</span>`;
                    }
                    return `
                        <tr onclick="window.openPreviewCRM('${c.invitation_file}', '${c.customer_id}')" style="cursor: pointer;">
                            <td class="text-center">${start + i + 1}</td>
                            <td>${c.kota || '-'}</td>
                            <td class="fw-bold">${c.nama_customer}</td>
                            <td class="text-center">${c.officer || '-'}</td>
                            <td class="text-center">${btnAksi}</td>
                        </tr>`;
                }).join('');
            }
            html += `</tbody></table></div>
                    <div class="paging-freeze-footer">
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            <button class="gb-page-btn" onclick="window.changePage(1)" ${currentPage === 1 ? 'disabled' : ''}>«</button>
                            <button class="gb-page-btn" onclick="window.changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>‹</button>
                            <span class="gb-page-info">Halaman ${currentPage} / ${totalPage}</span>
                            <button class="gb-page-btn" onclick="window.changePage(${currentPage + 1})" ${currentPage === totalPage ? 'disabled' : ''}>›</button>
                            <button class="gb-page-btn" onclick="window.changePage(${totalPage})" ${currentPage === totalPage ? 'disabled' : ''}>»</button>
                        </div>
                    </div></div>`;
            area.innerHTML = html;
        };
    
        window.changePage = function(page) {
            const state = window.paginationState;
            if (page < 1) page = 1;
            if (page > state.totalPage) page = state.totalPage;
            state.currentPage = page;
            window.renderInvitationCards();
        };
    
        // =========================================================================
        // 7. ACTIONS INVITATION
        // =========================================================================
        window.markAsSentCRM = function(customerId) {
            const eventId = document.getElementById('globalEventSelector').value;
            if (!eventId) return;
            Swal.fire({
                title: 'Tandai Terkirim?', text: "Pastikan undangan sudah dikirim ke WhatsApp Customer.",
                icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Tandai!', cancelButtonText: 'Batal',
                background: '#161b22', color: '#e6edf3'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ url("api/events/action") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify({ action: 'kirim', event_id: eventId, customer_id: customerId })
                    }).then(res => res.json()).then(res => {
                        if (res.success) { showAppToast('Berhasil', 'Status undangan diubah ke terkirim!'); window.loadInvitationList(); }
                        else { showAppToast('Warning', res.message, 'warning'); }
                    }).catch(err => showAppToast('Error', 'Gagal memproses ke server', 'danger'));
                }
            });
        };
    
        window.openPreviewCRM = function(filePath, customerId) {
            if (!filePath) return;
            const fullUrl = 'https://crm.lsfragrance.id/' + filePath;
            window.currentPreviewCustomerId = customerId;
            window.currentShareCount = 0;
            document.getElementById('imgPreviewUndanganCRM').src = fullUrl;
            document.getElementById('btnDownloadCRM').href = fullUrl;
            document.getElementById('shareCountLabel').innerText = '0x';
            const modal = new bootstrap.Modal(document.getElementById('modalPreviewCRM'));
            modal.show();
            setTimeout(() => { window.initPreviewModalActions(); }, 200);
        };
    
        window.startBatchProcess = async function() {
            const eventId = document.getElementById('globalEventSelector').value;
            if(!eventId) return;
            let allTargets = window.cachedInvitationData.filter(c => parseInt(c.status) === 1);
            if(allTargets.length === 0) {
                Swal.fire({ title: 'Info', text: 'Tidak ada undangan baru yang perlu dicetak.', icon: 'info', background: '#161b22', color: '#e6edf3' });
                return;
            }
            const confirm = await Swal.fire({
                title: 'Buat List Undangan',
                html: `Anda akan men-generate <b>${allTargets.length}</b> gambar undangan.<br><small class="text-warning">Jangan tutup tab ini sampai selesai.</small>`,
                icon: 'warning', background: '#161b22', color: '#e6edf3', showCancelButton: true, confirmButtonText: 'Ya, Mulai!', reverseButtons: true
            });
            if (!confirm.isConfirmed) return;
            Swal.fire({
                title: 'Memproses Undangan',
                html: `<div class="mt-3">
                    <div class="d-flex justify-content-between mb-1" style="font-size: 12px;">
                        <span>Progress: <b id="swal-count">0 / ${allTargets.length}</b></span>
                        <span>Estimasi: <b id="swal-eta" class="text-warning">Menghitung...</b></span>
                    </div>
                    <div class="progress" style="height: 20px; background: #30363d; border-radius: 10px;">
                        <div id="swal-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%"></div>
                    </div>
                    <div id="swal-current-name" class="mt-2 small" style="color:#8b949e">Menyiapkan antrian...</div>
                </div>`,
                allowOutsideClick: false, showConfirmButton: false, background: '#161b22', color: '#e6edf3'
            });
            const chunkSize = 10;
            let totalProcessed = 0;
            let startTime = Date.now();
            for (let i = 0; i < allTargets.length; i += chunkSize) {
                let chunk = allTargets.slice(i, i + chunkSize);
                let payload = chunk.map(t => ({ invitation_id: t.id, name: t.nama_customer }));
                document.getElementById('swal-current-name').innerText = `Memproses: ${chunk[0].nama_customer}...`;
                try {
                    let res = await fetch(`{{ url('report/doctor/events') }}/${eventId}/generate-batch`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify({ targets: payload })
                    });
                    let result = await res.json();
                    if (result.success) {
                        totalProcessed += chunk.length;
                        let percent = Math.round((totalProcessed / allTargets.length) * 100);
                        let timeElapsed = Date.now() - startTime;
                        let timePerItem = timeElapsed / totalProcessed;
                        let remainingItems = allTargets.length - totalProcessed;
                        let etaSeconds = Math.round((remainingItems * timePerItem) / 1000);
                        let etaText = etaSeconds > 60 ? Math.floor(etaSeconds/60) + 'm ' + (etaSeconds%60) + 's' : etaSeconds + 's';
                        document.getElementById('swal-progress-bar').style.width = percent + '%';
                        document.getElementById('swal-count').innerText = `${totalProcessed} / ${allTargets.length}`;
                        document.getElementById('swal-eta').innerText = etaText;
                    }
                } catch (err) { console.error("Batch error:", err); }
            }
            Swal.fire({ title: 'Berhasil!', text: `${totalProcessed} Undangan telah selesai dicetak.`, icon: 'success', background: '#161b22', color: '#e6edf3' });
            window.loadInvitationList();
        };
    
        window.toggleTabUI = function(activeTab) {
            const btnBuatList = document.getElementById('btnActionBuatList');
            const containerFilter = document.getElementById('filter-btn-container');
            if (activeTab === 'list') {
                btnBuatList.classList.remove('d-none');
                containerFilter.style.visibility = 'visible';
            } else {
                btnBuatList.classList.add('d-none');
                containerFilter.style.visibility = 'hidden';
            }
        };
    
        window.applyFilterPill = function(btnElement, statusFlag) {
            document.querySelectorAll('#filter-btn-container .nav-link').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');
            window.paginationState.currentPage = 1;
            window.paginationState.currentStatus = statusFlag;
            window.renderInvitationCards();
        };
    
        // =========================================================================
        // 8. EVENT LISTENERS FORM
        // =========================================================================
        window.loadGlobalEvents();
    
        document.getElementById('formCreateEvent').addEventListener('submit', function(e) {
            e.preventDefault();
            const btnSubmit = document.getElementById('btnSubmitEvent');
            const originalText = btnSubmit.innerHTML;
            btnSubmit.disabled = true; btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...`;
            fetch('{{ route("report.doctor.events.store") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'), 'Accept': 'application/json' },
                body: new FormData(this)
            }).then(async res => { if (!res.ok) throw await res.json(); return res.json(); })
            .then(data => {
                showAppToast('Berhasil', data.message); this.reset();
                bootstrap.Modal.getInstance(document.getElementById('modalCreateEvent'))?.hide();
                window.loadGlobalEvents();
            }).catch(error => showAppToast('Gagal', 'Terjadi kesalahan.', 'danger'))
            .finally(() => { btnSubmit.disabled = false; btnSubmit.innerHTML = originalText; });
        });
    
        document.getElementById('formTemplateEvent').addEventListener('submit', function(e) {
            e.preventDefault();
            const eventId = document.getElementById('globalEventSelector').value;
            if(!eventId) { showAppToast('Peringatan', 'Silakan pilih event terlebih dahulu!', 'warning'); return; }
            const btnSubmit = document.getElementById('btnSubmitTemplate');
            const originalText = btnSubmit.innerHTML;
            btnSubmit.disabled = true; btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Mengunggah...`;
            const formData = new FormData(this);
            formData.append('event_id', eventId);
            fetch(`{{ url('report/doctor/events') }}/${eventId}/template`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'), 'Accept': 'application/json' },
                body: formData
            }).then(async res => { if (!res.ok) throw await res.json(); return res.json(); })
            .then(data => {
                showAppToast('Berhasil', 'Template disimpan & diupdate!');
                this.reset();
                window.loadTemplatePreview();
            }).catch(error => showAppToast('Gagal', error.message || 'Kesalahan server', 'danger'))
            .finally(() => { btnSubmit.disabled = false; btnSubmit.innerHTML = originalText; });
        });
    
        window.initPreviewModalActions = function() {
            const btnShare = document.getElementById('btnShareCRM');
            if (btnShare) {
                btnShare.onclick = async function() {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Proses...';
                    this.disabled = true;
                    const imgUrl = document.getElementById('btnDownloadCRM').href;
                    try {
                        const response = await fetch(imgUrl);
                        const blob = await response.blob();
                        let isShared = false;
                        const isMobileOrTablet = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                        if (isMobileOrTablet && navigator.canShare) {
                            const ext = blob.type.split('/')[1] || 'jpeg';
                            const file = new File([blob], `Undangan.${ext}`, { type: blob.type });
                            if (navigator.canShare({ files: [file] })) {
                                try {
                                    await navigator.share({ title: 'Undangan Event', text: 'Berikut adalah undangan Anda.', files: [file] });
                                    isShared = true;
                                } catch (e) { console.log("Share dibatalkan user", e); }
                            }
                        }
                        if (!isShared) {
                            if (navigator.clipboard && navigator.clipboard.write) {
                                const getPngBlob = new Promise((resolve, reject) => {
                                    const img = new Image();
                                    img.crossOrigin = "Anonymous";
                                    img.onload = () => {
                                        const canvas = document.createElement('canvas');
                                        canvas.width = img.width; canvas.height = img.height;
                                        canvas.getContext('2d').drawImage(img, 0, 0);
                                        canvas.toBlob((pngBlob) => { resolve(pngBlob); }, 'image/png');
                                    };
                                    img.onerror = () => reject(new Error("Gagal meload gambar"));
                                    img.src = URL.createObjectURL(blob);
                                });
                                const finalPngBlob = await getPngBlob;
                                const clipboardItem = new ClipboardItem({ 'image/png': finalPngBlob });
                                await navigator.clipboard.write([clipboardItem]);
                                isShared = true;
                                showAppToast('Berhasil', '<i class="bi bi-clipboard-check"></i> Gambar disalin.');
                            } else {
                                showAppToast('Info', 'Browser Anda tidak mendukung copy otomatis. Gunakan tombol Download.', 'warning');
                            }
                        }
                        if (isShared) {
                            const res = await fetch('{{ url("api/events/action") }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                                body: JSON.stringify({ action: 'share', event_id: document.getElementById('globalEventSelector').value, customer_id: window.currentPreviewCustomerId })
                            });
                            const data = await res.json();
                            if (data.success) {
                                window.currentShareCount = data.data.share_count;
                                document.getElementById('shareCountLabel').innerText = window.currentShareCount + 'x';
                            }
                        }
                    } catch (err) {
                        showAppToast('Error', 'Gagal memproses gambar.', 'danger');
                    } finally {
                        this.innerHTML = originalHtml;
                        this.disabled = false;
                    }
                };
            }
        };
    
        // =========================================================================
        // 9. LOGIKA GUESTBOOK — CANVAS & INLINE EDIT
        // =========================================================================
        window.globalProvinces = [];
    
        // A. Render Canvas Guestbook
        window.openGuestbookModal = function() {
            const selectEl = document.getElementById('globalEventSelector');
            const eventId = selectEl.value;
            const eventName = selectEl.options[selectEl.selectedIndex]?.text || '';
            if (!eventId) { showAppToast('Informasi', 'Pilih event di kiri atas dulu!', 'warning'); return; }
            window.gbState.eventId = eventId;
            const canvasArea = document.getElementById('eventContentArea');
    
            canvasArea.innerHTML = `
                <div class="gb-canvas-card shadow-sm fade-in">
    
                    <div class="gb-date-header">
                        <button type="button" class="gb-page-btn" id="btnGbPrevDate" onclick="window.gbChangeDate(-1)" title="Sebelumnya">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <div class="text-center">
                            <div class="fw-bold text-white" id="gbDisplayDate" style="font-size: 0.85rem; line-height: 1.2;">Memuat...</div>
                            <div class="mt-1" id="lblEventNameGB" style="font-size: 0.68rem; color: #484f58; line-height: 1;">${eventName}</div>
                        </div>
                        <button type="button" class="gb-page-btn" id="btnGbNextDate" onclick="window.gbChangeDate(1)" title="Berikutnya">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
    
                    <div class="gb-toolbar">
                        <div class="form-check form-check-inline mb-0 ms-1">
                            <input class="form-check-input" type="checkbox" id="checkAllGb"
                                style="background-color: #21262d; border-color: #484f58;"
                                onchange="window.toggleCheckAllGb(this)">
                            <label class="form-check-label small" for="checkAllGb" style="color: #8b949e;">
                                Pilih Semua di Halaman ini
                            </label>
                        </div>
                        <button class="btn btn-sm btn-info text-white fw-bold rounded-pill shadow-sm px-3"
                            onclick="window.submitMutasiBatch()" style="font-size: 0.75rem;">
                            <i class="bi bi-arrow-right-circle me-1"></i> Mutasi ke Tampung
                        </button>
                    </div>
    
                    <div class="table-scroll-area">
                        <table class="table market-table w-100 mb-0">
                            <thead>
                                <tr>
                                    
                                    <th style="width: 40px;" class="text-center">
                                        <i class="bi bi-check2-square"></i>
                                    </th>
                                    <th style="min-width:170px;">Nama & Perusahaan</th>
                                    <th style="width:50px;" class="text-center"><i class="bi bi-camera"></i></th>
                                    <th style="min-width:160px;">Alamat</th>
                                    <th style="min-width:110px; width:120px;">HP</th>
                                    <th style="min-width:90px; width:100px;" class="text-center">AO</th>
                                </tr>
                            </thead>
                            <tbody id="listGuestbookTable">
                                <tr><td colspan="6" class="text-center py-5">
                                    <div class="spinner-border text-primary"></div>
                                </td></tr>
                            </tbody>
                        </table>
                    </div>
    
                    <div class="gb-pagination-container">
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="gb-page-btn" id="btnGbFirst" onclick="window.gbChangePage(1)">«</button>
                            <button type="button" class="gb-page-btn" id="btnGbPrev" onclick="window.gbChangePage('prev')">‹</button>
                            <span class="gb-page-info" id="gbPageInfo">1 / 1</span>
                            <button type="button" class="gb-page-btn" id="btnGbNext" onclick="window.gbChangePage('next')">›</button>
                            <button type="button" class="gb-page-btn" id="btnGbLast" onclick="window.gbChangePage('last')">»</button>
                        </div>
                    </div>
    
                </div>
            `;
    
            document.querySelectorAll('.top-nav-btn').forEach(btn => btn.classList.remove('btn-primary', 'text-white'));
            document.getElementById('btnNavGuestbook').classList.add('btn-primary', 'text-white');
    
            if (window.globalProvinces.length === 0) {
                $.get(`/customer_prospek/getProvinsi`, function(res) {
                    window.globalProvinces = res;
                    window.fetchGuestbookData(eventId);
                }).fail(function() {
                    console.warn("Gagal memuat provinsi");
                    window.fetchGuestbookData(eventId);
                });
            } else {
                window.fetchGuestbookData(eventId);
            }
        };
    
        // B. Tarik Data Server
        window.fetchGuestbookData = function(eventId) {
            let fetchUrl = "{{ route('report.doctor.events.guestbook.list', ':id') }}".replace(':id', eventId);
            fetch(fetchUrl)
                .then(res => { if(!res.ok) throw new Error("HTTP " + res.status); return res.json(); })
                .then(res => {
                    if (res.success) { window.processGuestbookData(res.data); }
                    else {
                        const tb = document.getElementById('listGuestbookTable');
                        if(tb) tb.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger">Gagal memuat data.</td></tr>`;
                    }
                })
                .catch(err => {
                    const tb = document.getElementById('listGuestbookTable');
                    if(tb) tb.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger">Terjadi kesalahan koneksi.</td></tr>`;
                });
        };
    
        // C. Proses Data
        window.processGuestbookData = function(rawData) {
            let grouped = {};
            let safeData = Array.isArray(rawData) ? rawData : [];
            safeData.forEach(item => {
                let dateStr = item.check_in ? item.check_in.split(' ')[0] : 'Unknown';
                if(!grouped[dateStr]) grouped[dateStr] = [];
                grouped[dateStr].push(item);
            });
            window.gbState.grouped = grouped;
            window.gbState.dates = Object.keys(grouped).sort();
            window.gbState.currentDateIdx = window.gbState.dates.length > 0 ? (window.gbState.dates.length - 1) : -1;
            window.gbState.currentPage = 1;
            window.renderGuestbookUI();
        };
    
        // D. Render Tabel UI
        window.renderGuestbookUI = function() {
            let state = window.gbState;
            let tbody = document.getElementById('listGuestbookTable');
            if (!tbody) return;
    
            const checkAllEl = document.getElementById('checkAllGb');
            if(checkAllEl) checkAllEl.checked = false;
    
            if (state.dates.length === 0) {
                document.getElementById('gbDisplayDate').innerText = "Belum Ada Kehadiran";
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5" style="color:#484f58"><i class="bi bi-folder-x fs-1 d-block mb-2"></i>Data Kosong</td></tr>`;
                window.updateGbPagination(0, 0);
                document.getElementById('btnGbPrevDate').disabled = true;
                document.getElementById('btnGbNextDate').disabled = true;
                return;
            }
    
            let activeDateStr = state.dates[state.currentDateIdx];
            // FIX: parse tanggal dengan replace '-' agar kompatibel semua browser
            let dateParts = activeDateStr.split('-');
            let dateObj = dateParts.length === 3 ? new Date(dateParts[0], dateParts[1]-1, dateParts[2]) : new Date(activeDateStr);
            let displayDate = isNaN(dateObj) ? activeDateStr : dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById('gbDisplayDate').innerText = displayDate;
            document.getElementById('btnGbPrevDate').disabled = (state.currentDateIdx <= 0);
            document.getElementById('btnGbNextDate').disabled = (state.currentDateIdx >= state.dates.length - 1);
    
            let activeData = state.grouped[activeDateStr];
            let totalPages = Math.ceil(activeData.length / state.perPage) || 1;
            if (state.currentPage > totalPages) state.currentPage = totalPages;
            if (state.currentPage < 1) state.currentPage = 1;
    
            let startIdx = (state.currentPage - 1) * state.perPage;
            let paginatedData = activeData.slice(startIdx, startIdx + state.perPage);
    
            let provOptions = window.globalProvinces
                ? window.globalProvinces.map(p => `<option value="${p.prov_name}" data-id="${p.prov_id}">${p.prov_name}</option>`).join('')
                : '';
    
            let html = '';
            if (paginatedData.length === 0) {
                html = `<tr><td colspan="6" class="text-center py-5" style="color:#484f58">Tidak ada data di halaman ini.</td></tr>`;
            } else {
                paginatedData.forEach((guest) => {
                    let company = guest.company || '';
                    let companyHtml = company
                        ? `<div class="small text-truncate" style="color:#8b949e; max-width:180px;" title="${company}">${company}</div>`
                        : `<div class="small fst-italic" style="color:#484f58;">Tanpa Perusahaan</div>`;
                    let alamat = guest.alamat || '-';
                    let wilayahText = [guest.kota, guest.provinsi].filter(Boolean).join(', ');
                    let rawPhone = guest.phone || '-';
                    let phoneHtml = rawPhone.split(',').map(p => `<div class="text-truncate" style="font-size:0.78rem;">${p.trim()}</div>`).join('');
                    let aoData = guest.ao || guest.officer || '-';
                    let isMutated = guest.is_mutated == 1 || guest.status_mutasi == 1;
                    
                    // VALIDASI STRICT: Hanya field wajib
                    // let isLengkap = (guest.nama && guest.nama.toString().trim() !== '') && 
                    //         (guest.phone && guest.phone.toString().trim() !== '') && 
                    //         (guest.company && guest.company.toString().trim() !== '') && 
                    //         (guest.provinsi && guest.provinsi.toString().trim() !== '') && 
                    //         (guest.kota && guest.kota.toString().trim() !== '') && 
                    //         (guest.zone && guest.zone.toString().trim() !== '') && 
                    //         (guest.kategori && guest.kategori.toString().trim() !== '');
                    
                    let isLengkap = (guest.nama && guest.nama.toString().trim() !== '') && 
                                (guest.company && guest.company.toString().trim() !== '') && 
                                (guest.phone && guest.phone.toString().trim() !== '');
                    
                    let checkHtml = '';
                    
                    if (isMutated) {
                        checkHtml = `<i class="bi bi-check-circle-fill text-success fs-6" title="Sudah Dimutasi"></i>`;
                    } else if (isLengkap) {
                        // Tombol Checkbox Muncul!
                        checkHtml = `<input class="form-check-input gb-checkbox" type="checkbox" value="${guest.id}"
                                      style="background-color:#21262d; border-color:#484f58; cursor:pointer;"
                                      onclick="event.stopPropagation()">`;
                    } else {
                        // Tombol Warning
                        checkHtml = `<button type="button" class="btn btn-warning btn-sm py-0 px-1 shadow-sm"
                                      onclick="event.stopPropagation(); window.openEditGuestModal(${guest.id});" 
                                      title="Data belum lengkap! Klik di sini untuk melengkapi." 
                                      style="border-radius: 6px; font-size: 0.85rem; line-height: 1;">
                                      <i class="bi bi-exclamation-triangle-fill text-dark"></i>
                                     </button>`;
                    }

                    // UBAH: Trigger klik row memanggil Modal Popup
                    let rowClick = isMutated
                        ? ''
                        : `onclick="window.openEditGuestModal(${guest.id});" style="cursor:pointer;" title="Klik untuk edit"`;

                    const esc = (v) => (v||'').replace(/"/g,'&quot;').replace(/</g,'&lt;');

                    // FOTO LOGIC: Mengubah JSON String ke format aman (Base64) untuk disisipkan ke HTML Inline Event
                    let safeImageBase64 = guest.image ? btoa(unescape(encodeURIComponent(guest.image))) : '';
                    let fotoHtml = guest.image && guest.image !== 'null' && guest.image !== '[]' && guest.image !== ''
                        ? `<button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2 py-0 shadow-sm"
                              onclick="event.stopPropagation(); window.bukaModalFoto('${safeImageBase64}', '${esc(guest.nama)}')"
                              title="Lihat Foto" style="font-size: 0.75rem;"><i class="bi bi-camera-fill"></i></button>`
                        : `<span class="text-white-50" style="font-size: 0.7rem;"><i class="bi bi-dash"></i></span>`;

                    html += `
                        <tr id="row-data-${guest.id}" class="guestbook-data-row hover-bg" ${rowClick}>
                            <td class="text-center" onclick="event.stopPropagation()">${checkHtml}</td>
                            <td>
                                <div class="fw-semibold text-truncate" style="color:#e6edf3; max-width:180px;" title="${esc(guest.nama)}">${guest.nama}</div>
                                ${companyHtml}
                            </td>
                            <td class="text-center" onclick="event.stopPropagation()">${fotoHtml}</td>
                            <td>
                                <div class="text-truncate-2" style="max-width:200px; font-size:0.77rem; color:#c9d1d9;" title="${esc(alamat)}">${alamat}</div>
                                <div class="mt-1 text-truncate" style="font-size:0.67rem; color:#58a6ff;">
                                    <i class="bi bi-geo-alt"></i> ${wilayahText || '<span style="color:#484f58">Wilayah kosong</span>'}
                                </div>
                            </td>
                            <td>${phoneHtml}</td>
                            <td class="text-center">
                                <span class="fw-bold" style="color:#d29922; font-size:0.78rem;">${aoData}</span>
                            </td>
                        </tr>
                    `;
                });
            }
            tbody.innerHTML = html;
            window.updateGbPagination(state.currentPage, totalPages);
        };
    
        // Fungsi pembantu untuk memuat Region di Modal
        window.fetchGbRegionModal = function(element, initialCity = null) {
            let selectedOption = element.options[element.selectedIndex];
            let regionId = selectedOption ? selectedOption.getAttribute('data-id') : null;
            let targetId = 'modalEdit-kota';
            
            if(!regionId) {
                $(`#${targetId}`).html('<option value="">Pilih Kota</option>').trigger('change.select2');
                return;
            }
            $(`#${targetId}`).html('<option value="">Memuat...</option>').trigger('change.select2');
            
            $.get(`/prospek_tampung/region/kota/${regionId}`, function(res) {
                let options = '<option value="">Pilih Kota</option>';
                res.forEach(item => { 
                    let isSelected = (initialCity && initialCity === item.city_name) ? 'selected' : '';
                    options += `<option value="${item.city_name}" data-id="${item.city_id}" ${isSelected}>${item.city_name}</option>`; 
                });
                $(`#${targetId}`).html(options).trigger('change.select2');
            });
        };
    
        // Buka Modal Edit dan Isi Data
        window.openEditGuestModal = function(id) {
            let activeDateStr = window.gbState.dates[window.gbState.currentDateIdx];
            
            // PERBAIKAN: Gunakan == (bukan ===) agar String "1" dan Number 1 dianggap sama
            let guest = window.gbState.grouped[activeDateStr].find(x => x.id == id); 
            
            if(!guest) {
                showAppToast('Error', 'Data tamu tidak ditemukan!', 'danger');
                return;
            }
            
            // --- TAMBAHKAN BARIS INI UNTUK MENGUBAH JUDUL MODAL ---
            let namaJudul = guest.nama ? guest.nama : 'Tanpa Nama';
            document.getElementById('modalEdit-titleName').innerText = 'Edit: ' + namaJudul;
            // ------------------------------------------------------

            // 1. Set text input standard
            document.getElementById('modalEdit-id').value = guest.id;
            document.getElementById('modalEdit-nama').value = guest.nama || '';
            document.getElementById('modalEdit-company').value = guest.company || '';
            document.getElementById('modalEdit-phone').value = guest.phone || '';
            document.getElementById('modalEdit-alamat').value = guest.alamat || '';
            document.getElementById('modalEdit-keterangan').value = guest.keterangan || '';
            document.getElementById('modalEdit-zone').value = guest.zone || '';
            document.getElementById('modalEdit-kategori').value = guest.kategori || '';
            document.getElementById('modalEdit-multicabang').value = guest.toko_multicabang || '';
            document.getElementById('modalEdit-sosmed').value = guest.media_sosial || '';
            document.getElementById('modalEdit-marketplace').value = guest.marketplace || '';
            document.getElementById('modalEdit-model').value = guest.model_bisnis || '';

            // 2. Load opsi Provinsi ke Modal
            let provSelect = document.getElementById('modalEdit-provinsi');
            provSelect.innerHTML = '<option value="">Pilih Provinsi</option>' + 
                window.globalProvinces.map(p => `<option value="${p.prov_name}" data-id="${p.prov_id}">${p.prov_name}</option>`).join('');

            // 3. Init Select2 di Modal
            $('#modalEdit-provinsi, #modalEdit-kota').select2({
                dropdownParent: $('#modalEditGuestbook'),
                width: '100%'
            });

            // 4. Set Value Provinsi & Trigger Kota
            if (guest.provinsi) {
                $('#modalEdit-provinsi').val(guest.provinsi).trigger('change.select2');
                // Panggil manual fungsi region untuk meload kota
                window.fetchGbRegionModal(document.getElementById('modalEdit-provinsi'), guest.kota);
            } else {
                $('#modalEdit-provinsi').val('').trigger('change.select2');
                $('#modalEdit-kota').html('<option value="">Pilih Kota</option>').trigger('change.select2');
            }

            // 5. Tampilkan Modal
            const modalInstance = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditGuestbook'));
            modalInstance.show();
        };
    
        // Simpan Data dari Modal
        window.saveGuestbookModal = function() {
            const id = document.getElementById('modalEdit-id').value;
            const nama = document.getElementById('modalEdit-nama').value;
            const company = document.getElementById('modalEdit-company').value;
            const phone = document.getElementById('modalEdit-phone').value;
            const alamat = document.getElementById('modalEdit-alamat').value;
            const provinsi = $('#modalEdit-provinsi').val();
            const kota = $('#modalEdit-kota').val();
            const zone = document.getElementById('modalEdit-zone').value;
            const keterangan = document.getElementById('modalEdit-keterangan').value;
            const kategori = document.getElementById('modalEdit-kategori').value;
            const toko_multicabang = document.getElementById('modalEdit-multicabang').value;
            const media_sosial = document.getElementById('modalEdit-sosmed').value;
            const marketplace = document.getElementById('modalEdit-marketplace').value;
            const model_bisnis = document.getElementById('modalEdit-model').value; // Tangkap data

            if(!nama.trim()) { showAppToast('Validasi', 'Nama PIC wajib diisi!', 'warning'); return; }

            const btnSave = document.getElementById('btnSaveModalEdit');
            const originalText = btnSave.innerHTML;
            btnSave.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...`;
            btnSave.disabled = true;

            let updateUrl = "{{ route('report.doctor.events.guestbook.update', ':id') }}".replace(':id', id);

            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    nama, company, phone, alamat, provinsi, kota, zone, keterangan, kategori,
                    toko_multicabang, media_sosial, marketplace,
                    model_bisnis // <-- Kirim ke server
                })
            })
            .then(async res => {
                if (!res.ok) { const errData = await res.json(); throw errData; }
                return res.json();
            })
            .then(data => {
                if(data.success) {
                    showAppToast('Berhasil', data.message, 'success');
                    
                    // Update state lokal agar tidak perlu reload API
                    let activeDateStr = window.gbState.dates[window.gbState.currentDateIdx];
                    let itemIndex = window.gbState.grouped[activeDateStr].findIndex(x => x.id == id);
                    if(itemIndex > -1) {
                        Object.assign(window.gbState.grouped[activeDateStr][itemIndex], { 
                            nama, company, phone, alamat, provinsi, kota, zone, keterangan, kategori,
                            toko_multicabang, media_sosial, marketplace, model_bisnis
                        });
                    }
                    
                    // Render ulang tabel & tutup modal
                    window.renderGuestbookUI();
                    bootstrap.Modal.getInstance(document.getElementById('modalEditGuestbook')).hide();
                } else {
                    showAppToast('Gagal', data.message, 'danger');
                }
            })
            .catch(err => {
                if (err.errors) {
                    let firstError = Object.values(err.errors)[0][0];
                    showAppToast('Validasi Gagal', firstError, 'warning');
                } else {
                    showAppToast('Error', err.message || 'Terjadi kesalahan sistem', 'danger');
                }
                console.error("Error Update:", err);
            })
            .finally(() => {
                btnSave.innerHTML = originalText;
                btnSave.disabled = false;
            });
        };
    
        // G. Navigasi Tanggal
        window.gbChangeDate = function(step) {
            let state = window.gbState;
            let newIdx = state.currentDateIdx + step;
            if (newIdx >= 0 && newIdx < state.dates.length) {
                state.currentDateIdx = newIdx;
                state.currentPage = 1;
                window.renderGuestbookUI();
            }
        };
    
        // H. Toggle Semua Checkbox
        window.toggleCheckAllGb = function(element) {
            document.querySelectorAll('.gb-checkbox').forEach(cb => cb.checked = element.checked);
        };
    
        // I. Submit Batch Mutasi
        window.submitMutasiBatch = function() {
            let checkedBoxes = document.querySelectorAll('.gb-checkbox:checked');
            if (checkedBoxes.length === 0) {
                showAppToast('Peringatan', 'Pilih minimal 1 data untuk dimutasi!', 'warning');
                return;
            }
            let guestbookIds = Array.from(checkedBoxes).map(cb => cb.value);
            const eventId = window.gbState.eventId;
            Swal.fire({
                title: 'Mutasi Data?',
                text: `Anda akan memutasi ${guestbookIds.length} data ke Prospek L1.`,
                icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Mutasi!',
                cancelButtonText: 'Batal', background: '#161b22', color: '#e6edf3'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }, background: '#161b22', color: '#e6edf3' });
                    $.ajax({
                        url: `{{ route('master.prospek_tampung.mutasi_guestbook_batch') }}`,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}', event_id: eventId, guestbook_ids: guestbookIds },
                        success: function(res) {
                            if(res.success) {
                                Swal.fire({ title: 'Berhasil!', text: res.message, icon: 'success', timer: 1500, background: '#161b22', color: '#e6edf3' });
                                window.refreshGuestbook();
                            } else {
                                Swal.fire({ title: 'Gagal!', text: res.message, icon: 'warning', background: '#161b22', color: '#e6edf3' });
                            }
                        },
                        error: function() {
                            Swal.fire({ title: 'Error!', text: 'Terjadi kesalahan pada server.', icon: 'error', background: '#161b22', color: '#e6edf3' });
                        }
                    });
                }
            });
        };
    
        // J. Paging Guestbook
        window.gbChangePage = function(action) {
            let state = window.gbState;
            if(state.dates.length === 0) return;
            let totalPages = Math.ceil(state.grouped[state.dates[state.currentDateIdx]].length / state.perPage);
            if (action === 'prev') state.currentPage = Math.max(1, state.currentPage - 1);
            else if (action === 'next') state.currentPage = Math.min(totalPages, state.currentPage + 1);
            else if (action === 'last') state.currentPage = totalPages;
            else state.currentPage = parseInt(action) || 1;
            window.renderGuestbookUI();
        };
    
        window.updateGbPagination = function(current, total) {
            const infoEl = document.getElementById('gbPageInfo');
            if(infoEl) infoEl.innerText = total === 0 ? `0 / 0` : `${current} / ${total}`;
            const btnFirst = document.getElementById('btnGbFirst');
            const btnPrev = document.getElementById('btnGbPrev');
            const btnNext = document.getElementById('btnGbNext');
            const btnLast = document.getElementById('btnGbLast');
            if(btnFirst) btnFirst.disabled = (current <= 1);
            if(btnPrev) btnPrev.disabled = (current <= 1);
            if(btnNext) btnNext.disabled = (current >= total || total === 0);
            if(btnLast) btnLast.disabled = (current >= total || total === 0);
        };
    
        window.refreshGuestbook = function() {
            if (window.gbState.eventId) window.fetchGuestbookData(window.gbState.eventId);
        };
    } else if (featureName === 'AGENDA') { 
    
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
                
                /* Hilangkan scroll, auto-wrap vertikal */
                .fc-daygrid-day-events {
                    max-height: none !important;
                    overflow: visible !important;
                }
                
                /* Event tampil fleksibel & tidak memaksa cell melebar */
                .fc-daygrid-event,
                .fc-event {
                    overflow: visible !important;
                    white-space: normal !important;
                    height: auto !important;
                    padding: 0 !important;
                    border: none !important;
                    background: transparent !important;
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
                    },
                   eventDidMount: function(info) {
                        var raw = info.event.title || "";
                    
                        var labels = Array.from(new Set(
                            raw.split(",").map(function(v){ return v.trim(); }).filter(function(v){ return v !== ""; })
                        ));
                    
                        var colorMap = {
                            "Agenda": "#0d6efd",
                            "Tagihan": "#f4b400",
                            "Sampling": "#0f9d58"
                        };
                    
                        var html = "";
                    
                        for (var i = 0; i < labels.length; i++) {
                            var text = labels[i];
                            var bg = colorMap[text] || "#6c757d";
                    
                            html += 
                                '<div style="margin:1px 0;">' +
                                    '<span style="' +
                                        'display:inline-block;' +
                                        'padding:3px 10px;' +            /* ← UBAH PANJANG BADGE DI SINI  */
                                        'border-radius:5px;' +
                                        'font-size:0.70rem;' +           /* ← UBAH UKURAN FONT DI SINI   */
                                        'font-weight:600;' +
                                        'background:' + bg + ';' +
                                        'color:#fff;' +
                                        'white-space:nowrap;' +
                                    '">' +
                                    text +
                                    '</span>' +
                                '</div>';
                        }
                    
                        info.el.innerHTML = html;
                    
                        // Hilangkan background default event
                        info.el.style.background = "transparent";
                        info.el.style.border = "0";
                        info.el.style.padding = "0";
                        info.el.style.height = "auto";
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
        let cachedMarketData = [];
        let isDataLoaded = false;
        
        const officerId = selectedOfficerSpan.dataset.officerId;
        const officerName = selectedOfficerSpan.textContent.trim() || 'Officer';
    
        // --- 1. RENDER STRUKTUR UI (TANPA FILTER) ---
        targetElement.innerHTML = `
            <style>
                .market-tab-btn { 
                    font-size: 0.75rem; 
                    transition: all 0.2s; 
                    border: 1px solid #444; 
                    color: #aaa; 
                    background: transparent; 
                }
                .market-tab-btn:hover {
                    background-color: #f8f9fa;
                    color: #212529 !important;
                }
                .market-tab-btn.active { background-color: #f8f9fa !important; color: #212529 !important; font-weight: bold; border-color: #f8f9fa; }
                .market-card { background: #fff; border-radius: 8px; border: none; margin-bottom: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
                .market-header { padding: 10px 15px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #fff; }
                .market-header:hover { background: #fdfdfd; }
                .market-header .title { font-size: 0.85rem; font-weight: 700; color: #333; }
                .market-table { font-size: 0.72rem; margin-bottom: 0; width: 100%; }
                .market-table th { background: #f8f9fa; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border-top: 1px solid #eee; padding: 8px; }
                .market-table td { vertical-align: middle; padding: 10px 8px; border-bottom: 1px solid #f1f1f1; }
                .badge-count { font-size: 0.7rem; padding: 4px 8px; border-radius: 20px; }
                .search-results-floating { position: absolute; top: 100%; left: 0; right: 0; z-index: 1060; display: none; max-height: 350px; overflow-y: auto; background: #212529; border: 1px solid #444; box-shadow: 0 10px 25px rgba(0,0,0,0.5); border-radius: 4px; }
                .btn-xs { padding: 2px 8px; font-size: 0.65rem; border-radius: 4px; }
            </style>
    
            <div class="d-flex gap-1 mb-3 bg-dark p-1 rounded shadow-sm">
                <button id="btnListMarket" class="btn btn-sm flex-fill market-tab-btn active text-uppercase">List</button>
                <button id="btnZone" class="btn btn-sm flex-fill market-tab-btn text-uppercase">Zone</button>
                <button id="btnFollowUp" class="btn btn-sm flex-fill market-tab-btn text-uppercase">Search</button>
            </div>
    
            <div id="marketView">
                <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                    <span class="text-white-50 small fw-bold"><i class="bi bi-globe2 me-1"></i> NASIONAL</span>
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
                <div id="listMarketArea"></div>
            </div>
    
            <div id="zoneView" style="display:none;">
                <div id="zoneResultArea"></div>
            </div>
    
            <div id="followUpView" style="display:none;">
                <div class="position-relative mb-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-dark border-secondary text-white-50"><i class="bi bi-search"></i></span>
                        <input type="text" id="citySearch" class="form-control bg-dark text-white border-secondary" placeholder="Cari nama toko atau kota...">
                    </div>
                    <div id="autocomplete-list" class="list-group search-results-floating"></div>
                </div>
                <div id="searchInfo" class="text-center mt-4 text-white-50">
                    <i class="bi bi-lightning-charge-fill display-6 d-block mb-2 text-warning"></i>
                    <p class="small">Hasil pencarian akan muncul otomatis</p>
                </div>
            </div>
        `;
    
        // --- 2. LOGIKA NAVIGASI TAB ---
        const vList = document.getElementById('marketView');
        const vZone = document.getElementById('zoneView');
        const vSearch = document.getElementById('followUpView');
        const bList = document.getElementById('btnListMarket');
        const bZone = document.getElementById('btnZone');
        const bSearch = document.getElementById('btnFollowUp');
    
        function resetTabs() {
            [vList, vZone, vSearch].forEach(v => v.style.display = 'none');
            [bList, bZone, bSearch].forEach(b => b.classList.remove('active'));
        }
    
        bList.onclick = () => { resetTabs(); vList.style.display = 'block'; bList.classList.add('active'); loadData('listMarketArea'); };
        bZone.onclick = () => { resetTabs(); vZone.style.display = 'block'; bZone.classList.add('active'); loadData('zoneResultArea'); };
        bSearch.onclick = () => { resetTabs(); vSearch.style.display = 'block'; bSearch.classList.add('active'); };
    
        // --- 3. HELPER FUNCTIONS ---
        function base64UrlEncode(str) { return btoa(str).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, ''); }
        
        function normalizeKategori(kat) {
            return (kat || '').toLowerCase().replace(/\s+/g, ' ').trim();
        }
    
        function loadData(targetId) {
            const area = document.getElementById(targetId);
            area.innerHTML = `<div class="text-center py-5">
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <div class="text-white-50 small mt-2">Memuat data...</div>
            </div>`;
            
            fetch(`{{ route('report.doctor.getListMarket') }}?officer=${officerId}`)
                .then(res => res.json())
                .then(data => {
        
                    // 🔥 SIMPAN SEMUA DATA UNTUK SEARCH
                    cachedMarketData = [
                        ...(data.existing || []),
                        ...(data.prospek || [])
                    ];
        
                    isDataLoaded = true;
        
                    const isZone = (targetId === 'zoneResultArea');
                    renderCards(data, area, isZone);
                })
                .catch(() => {
                    area.innerHTML = `<div class="alert alert-danger py-2 small mx-1 text-center">Koneksi bermasalah.</div>`;
                });
        }
    
        function renderCards(response, targetArea, isZoneView = false) {
            let allData = [...(response.existing || []), ...(response.prospek || [])];
            if (allData.length === 0) {
                targetArea.innerHTML = `<div class="alert alert-dark text-center small text-white-50 mt-3">Data tidak ditemukan</div>`;
                return;
            }
    
            // --- PROSES GROUPING ---
            const grouped = allData.reduce((acc, curr) => {
                let key = '';
                if (isZoneView) {
                    // Logika Penamaan Zona Sesuai Request
                    const rawZone = (curr.zona || curr.zone || '').toUpperCase();
                    if (rawZone.includes('JABODETABEK')) key = 'ZONA 1 : JABODETABEK';
                    else if (rawZone.includes('JABAR')) key = 'ZONA 2 : JABAR';
                    else if (rawZone.includes('JATENG') || rawZone.includes('JATIM')) key = 'ZONA 3 : JATENG - JATIM';
                    else if (rawZone.includes('SUMATRA')) key = 'ZONA 4 : SUMATERA';
                    else if (rawZone.includes('BALI') || rawZone.includes('KALIMANTAN') || rawZone.includes('SULAWESI')) key = 'ZONA 5 : BALI - KALIMANTAN - SULAWESI';
                    else key = 'LAINNYA / NASIONAL';
                } else {
                    key = (curr.kategori || 'UNCATEGORIZED').trim();
                }
    
                if (!acc[key]) acc[key] = [];
                acc[key].push(curr);
                return acc;
            }, {});
            
            const kategoriOrderRaw = [
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
            
            const kategoriOrder = kategoriOrderRaw.map(k => normalizeKategori(k));
    
            // Urutkan key jika di Tab Zone agar Zona 1, 2, 3... berurutan
            let keys = Object.keys(grouped);

            if (isZoneView) {
                keys = keys.sort();
            } else {
                keys = keys.sort((a, b) => {
                    const indexA = kategoriOrder.indexOf(normalizeKategori(a));
                    const indexB = kategoriOrder.indexOf(normalizeKategori(b));
            
                    if (indexA !== -1 && indexB !== -1) return indexA - indexB;
                    if (indexA !== -1) return -1;
                    if (indexB !== -1) return 1;
            
                    return a.localeCompare(b);
                });
            }
    
            let html = '';
            keys.forEach((groupName, idx) => {
                const safeId = `group-${idx}-${targetArea.id}`;
                html += `
                    <div class="market-card shadow-sm">
                        <div class="market-header collapsed" data-bs-toggle="collapse" data-bs-target="#collapse-${safeId}">
                            <span class="title">${groupName}</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle badge-count">${grouped[groupName].length}</span>
                        </div>
                        <div class="collapse" id="collapse-${safeId}">
                            <div class="table-responsive">
                                <table class="table market-table table-hover">
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
                                    <tbody>
                                        ${grouped[groupName].map((c, index) => {

                                            const rowClass = 
                                                (c.status || '').toLowerCase() === 'existing' ? 'row-existing' :
                                                (c.status || '').toLowerCase() === 'prospek' ? 'row-prospek' : '';
                                        
                                            const customerId = c.customer_id ?? c.id;
                                            const encodedId = base64UrlEncode((customerId || '').toString());
                                            
                                            // --- PERBAIKAN 1: Ambil ID officer ASLI milik customer tersebut ---
                                            // (Pastikan key ini sesuai dengan response API, biasanya c.officer atau c.officer_id)
                                            const actualOfficer = c.officer || ''; 
                                            const encodedActualOfficer = encodeURIComponent(actualOfficer);
                                        
                                            // --- PERBAIKAN 2: Deteksi jika dropdown sedang memilih "All" ---
                                            // Sesuaikan value "All" ini dengan value dari dropdown HTML Anda (bisa 'all', '', atau '0')
                                            const isDropdownAll = !officerId || officerId.toLowerCase() === 'all' || officerId === '0';
                                        
                                            // --- PERBAIKAN 3: Izinkan klik jika dropdown "All" ATAU officer-nya cocok ---
                                            const isAuthorized = isDropdownAll || ((c.officer || '').toLowerCase().trim() === (officerId || '').toLowerCase().trim());
                                        
                                            const url = `https://sys-af.lsfragrance.id/file-doctor/customer-one-time/${encodedId}?officer=${encodedActualOfficer}`;

                                            return `
                                                <tr class="${rowClass} clickable-row" data-href="${isAuthorized ? url : ''}" style="${isAuthorized ? 'cursor:pointer;' : 'cursor:not-allowed;'}">
                                                    <td>${index + 1}</td>
                                                    <td>${c.text_provinsi || '-'}</td>
                                                    <td>${c.text_kota || '-'}</td>
                                                    <td>${c.kategori || '-'}</td>
                                                    <td>
                                                        ${
                                                            isAuthorized
                                                            ? `
                                                                <a href="https://sys-af.lsfragrance.id/file-doctor/customer-one-time/${encodedId}?officer=${encodedActualOfficer}"
                                                                    target="_blank"
                                                                    class="open-customer fw-bold text-dark text-decoration-none"
                                                                    data-customer-id="${encodedId}"
                                                                    data-officer="${encodedActualOfficer}">
                                                                    ${c.name || c.nama || '-'}
                                                                </a>
                                                            `
                                                            : `
                                                                <span class="fw-bold text-muted" style="cursor:not-allowed;" title="Tidak memiliki akses">
                                                                    ${c.name || c.nama || '-'}
                                                                </span>
                                                            `
                                                        }
                                                    </td>
                                                    <td>
                                                        <span class="badge ${c.pengajuan ? 'bg-success' : 'bg-secondary'}" style="font-size:0.65rem;">
                                                            ${c.pengajuan || '-'}
                                                        </span>
                                                    </td>
                                                </tr>
                                            `;
                                        }).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>`;
            });
            targetArea.innerHTML = html;
        }
        
        document.addEventListener('click', function(e) {
            const row = e.target.closest('.clickable-row');
            if (row && row.dataset.href) {
                window.open(row.dataset.href, '_blank');
            }
        });
    
        // --- 4. LOGIKA SEARCH AUTOCOMPLETE ---
        let debounceTimer;
        const searchInput = document.getElementById('citySearch');
        const autoList = document.getElementById('autocomplete-list');
    
        if (searchInput) {
            searchInput.oninput = function() {
                clearTimeout(debounceTimer);
                const q = this.value.trim().toLowerCase();
            
                if (q.length < 2) { 
                    autoList.style.display = 'none'; 
                    return; 
                }
            
                // 🔒 Pastikan data sudah ada
                if (!isDataLoaded) {
                    autoList.innerHTML = `<div class="list-group-item bg-dark text-warning border-secondary small py-2 text-center">Data belum siap...</div>`;
                    autoList.style.display = 'block';
                    return;
                }
            
                debounceTimer = setTimeout(() => {
            
                    autoList.innerHTML = `<div class="list-group-item bg-dark text-white border-secondary small py-2 text-center">Mencari...</div>`;
                    autoList.style.display = 'block';
            
                    // 🔥 FILTER LOKAL (FAST)
                    const results = cachedMarketData.filter(c => {
                        const name = (c.name || '').toLowerCase();
                        const kota = (c.text_kota || '').toLowerCase();
                        const kategori = (c.kategori || '').toLowerCase();
            
                        return (
                            name.includes(q) ||
                            kota.includes(q) ||
                            kategori.includes(q)
                        );
                    });
            
                    if (results.length === 0) {
                        autoList.innerHTML = `<div class="list-group-item bg-dark text-muted border-secondary small py-2">
                            Tidak ditemukan "${q}"
                        </div>`;
                        return;
                    }
            
                    // 🔥 OPTIONAL: sort agar lebih relevan
                    results.sort((a, b) => {
                        const nameA = (a.name || '').toLowerCase();
                        const nameB = (b.name || '').toLowerCase();
                        return nameA.indexOf(q) - nameB.indexOf(q);
                    });
            
                    autoList.innerHTML = results.slice(0, 10).map(c => {
                        const cId = c.customer_id || c.id;
            
                        return `
                            <a href="https://sys-af.lsfragrance.id/file-doctor/customer-one-time/${base64UrlEncode(cId.toString())}?officer=${officerId}" 
                               target="_blank" class="list-group-item bg-dark text-white border-secondary py-2 px-3">
                                
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold text-info" style="font-size:0.8rem;">
                                            ${c.name}
                                        </div>
                                        <small class="text-white-50" style="font-size:0.65rem;">
                                            <i class="bi bi-geo-alt me-1"></i>${c.text_kota || '-'}
                                        </small>
                                    </div>
            
                                    <span class="badge rounded-pill ${c.labels === 'P' ? 'bg-success' : 'bg-secondary'}" style="font-size:0.6rem;">
                                        ${c.labels}
                                    </span>
                                </div>
            
                            </a>`;
                    }).join('');
            
                }, 300);
            };
        }
    
        // Klik di luar menutup search
        document.addEventListener('click', (e) => { if (autoList && !autoList.contains(e.target) && e.target !== searchInput) autoList.style.display = 'none'; });
    
        // Initial Load
        loadData('listMarketArea');
    } else if (featureName === 'BROWSER') {
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

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
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
<<<<<<< HEAD
=======
                        
                        <!-- Header -->
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
                        
                        <!-- Header -->
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
                        <div id="reviewCaption" class="mb-1 flex-shrink-1" style="font-size: 0.90em;">
                            <div class="fw-semibold text-primary" id="reviewHeaderTitle"></div>
                            <div class="text-muted fst-italic small text-center" id="reviewHeaderHint">
                                Silakan tentukan tanggal dan pilih salah satu menu di kiri untuk menampilkan data.
                            </div>
                        </div>
<<<<<<< HEAD
<<<<<<< HEAD
                        <div id="reviewCardsWrapper" class="flex-grow-1" style="overflow-y: auto; max-height: 70vh; padding-right:4px; padding-left:4px;">
                            <div id="reviewContent"></div>
                        </div>
                    </div>
                </div>
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

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

<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
<<<<<<< HEAD
        
            // --- Header sticky ---
            const reviewHeaderTitle = document.getElementById('reviewHeaderTitle');
            // Header sticky
            reviewHeaderTitle.innerHTML = `
            <div class="d-flex justify-content-between align-items-start bg-white px-2 border-bottom shadow-sm" 
                 style="position: sticky; top: 0; z-index: 20;">
                <div class="small fw-semibold text-start">
                    Periode: <span class="text-primary">${formatDateDisplay(startDate)} s/d ${formatDateDisplay(endDate)}</span>
                    <br>
                    Officer: <span class="text-dark fw-bold">${selectedOfficer.toUpperCase()}</span>
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
        
            // --- Kosongkan frame konten ---
            frameB.innerHTML = "";
        
            // --- Group Data ---
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

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
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
            const grouped = {};
            data.forEach(item => {
                const tgl = item.tanggal || 'unknown';
                if (!grouped[tgl]) grouped[tgl] = [];
                grouped[tgl].push(item);
            });
<<<<<<< HEAD
<<<<<<< HEAD
        
            const sortedDates = Object.keys(grouped).sort((a, b) => new Date(a) - new Date(b));
        
            // --- Pagination ---
            const perPage = 5;
            let currentPage = 1;
            const totalPages = Math.ceil(sortedDates.length / perPage);
        
            function renderPage(page) {
                frameB.querySelectorAll('.agenda-page').forEach(el => el.remove()); // hapus halaman sebelumnya
                const startIdx = (page - 1) * perPage;
                const endIdx = startIdx + perPage;
                const pageDates = sortedDates.slice(startIdx, endIdx);
        
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

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

<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
<<<<<<< HEAD
        
=======

>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======

>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
                    const picGroups = {};
                    grouped[tanggal].forEach(item => {
                        const pic = item.pic_key || 'Tanpa PIC';
                        if (!picGroups[pic]) picGroups[pic] = [];
                        picGroups[pic].push(item);
                    });
<<<<<<< HEAD
<<<<<<< HEAD
        
                    Object.keys(picGroups).forEach(picKey => {
                        picGroups[picKey].forEach(agenda => {
                            html += `<div class="mb-2 pb-2 border-bottom text-start">`;
        
                            if (agenda.tasks && agenda.tasks.length > 0) {
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

                    Object.keys(picGroups).forEach(picKey => {
                        picGroups[picKey].forEach(agenda => {
                            html += `<div class="mb-2 pb-2 border-bottom text-start">`;

                            if (agenda.tasks?.length) {
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
                                html += `<ul class="small ps-3 mt-1 text-start">`;
                                agenda.tasks.forEach(t => {
                                    const createdAt = formatCreatedAtToWIB(t.created_at);
                                    const status = Number(t.status);
                                    let color = status === 3 ? 'red' : status === 2 ? 'green' : 'black';
<<<<<<< HEAD
<<<<<<< HEAD
                                    html += `<li style="color:${color} !important">
                                                <span class="fw-semibold">${t.keterangan}</span>
                                                <br><small class="text-muted">${createdAt}</small>
                                             </li>`;
                                });
                                html += `</ul>`;
                            } else {
                                html += `<div class="small text-muted text-start">Tidak ada task.</div>`;
                            }
        
                            html += `</div>`;
                        });
                    });
        
                    html += `</div></div>`;
                    frameB.insertAdjacentHTML('beforeend', html);
                });
        
                renderPagination();
            }
        
            function renderPagination() {
                const oldPag = document.getElementById('agendaPagination');
                if (oldPag) oldPag.remove();
                if (totalPages <= 1) return;
        
                const pagWrapper = document.createElement('div');
                pagWrapper.id = 'agendaPagination';
                pagWrapper.className = 'd-flex justify-content-center align-items-center gap-1  sticky-bottom';
                pagWrapper.style.position = 'sticky';
                pagWrapper.style.bottom = '0';
                pagWrapper.style.background = '#fff';
                pagWrapper.style.zIndex = '15';
        
                // Prev
                const prevBtn = document.createElement('button');
                prevBtn.className = `btn btn-sm ${currentPage === 1 ? 'btn-secondary' : 'btn-outline-primary'}`;
                prevBtn.disabled = currentPage === 1;
                prevBtn.innerHTML = `<i class="bi bi-chevron-left"></i>`;
                prevBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderPage(currentPage); window.scrollTo({top:0,behavior:'smooth'}); } });
                pagWrapper.appendChild(prevBtn);
        
                // Page numbers
                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.className = `btn btn-sm ${i === currentPage ? 'btn-primary text-white' : 'btn-outline-primary'}`;
                    btn.addEventListener('click', () => { currentPage = i; renderPage(currentPage); window.scrollTo({top:0,behavior:'smooth'}); });
                    pagWrapper.appendChild(btn);
                }
        
                // Next
                const nextBtn = document.createElement('button');
                nextBtn.className = `btn btn-sm ${currentPage === totalPages ? 'btn-secondary' : 'btn-outline-primary'}`;
                nextBtn.disabled = currentPage === totalPages;
                nextBtn.innerHTML = `<i class="bi bi-chevron-right"></i>`;
                nextBtn.addEventListener('click', () => { if (currentPage < totalPages) { currentPage++; renderPage(currentPage); window.scrollTo({top:0,behavior:'smooth'}); } });
                pagWrapper.appendChild(nextBtn);
        
                frameB.appendChild(pagWrapper);
            }
        
            renderPage(currentPage);
        }
    
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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

<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
<<<<<<< HEAD
                    let paginationDiv = document.getElementById('reviewPagination');
                    if(!paginationDiv){
                        paginationDiv = document.createElement('div');
                        paginationDiv.id = 'reviewPagination';
                        paginationDiv.style.position = 'sticky';
                        paginationDiv.style.bottom = '0';
                        paginationDiv.style.backgroundColor = 'rgba(255,255,255,0.95)';
                        paginationDiv.style.padding = '0px 0'; // space agar tidak terlalu mepet
                        paginationDiv.style.textAlign = 'center';
                        paginationDiv.style.zIndex = '5';
                        frameB.appendChild(paginationDiv);
                    }
        
                    paginationDiv.innerHTML = '';
        
=======
                    const paginationDiv = document.getElementById('reviewPagination');
                    paginationDiv.innerHTML = '';

>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
                    const paginationDiv = document.getElementById('reviewPagination');
                    paginationDiv.innerHTML = '';

>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
                    // Prev
                    const prevBtn = document.createElement('button');
                    prevBtn.className = `btn btn-sm ${currentPage===1 ? 'btn-secondary' : 'btn-outline-primary'} mx-1`;
                    prevBtn.disabled = currentPage===1;
                    prevBtn.innerHTML = `<i class="bi bi-chevron-left"></i>`;
<<<<<<< HEAD
<<<<<<< HEAD
                    prevBtn.addEventListener('click', () => {
                        if(currentPage>1){ renderPage(currentPage-1); window.scrollTo({top:0, behavior:'smooth'}); }
                    });
                    paginationDiv.appendChild(prevBtn);
        
                    // Page numbers
                    for(let i=1; i<=totalPages; i++){
                        const btn = document.createElement('button');
                        btn.textContent = i;
                        btn.className = `btn btn-sm mx-1 ${i===currentPage ? 'btn-primary text-white' : 'btn-outline-primary'}`;
                        btn.addEventListener('click', () => { renderPage(i); window.scrollTo({top:0, behavior:'smooth'}); });
                        paginationDiv.appendChild(btn);
                    }
        
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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

<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
                    // Next
                    const nextBtn = document.createElement('button');
                    nextBtn.className = `btn btn-sm ${currentPage===totalPages ? 'btn-secondary' : 'btn-outline-primary'} mx-1`;
                    nextBtn.disabled = currentPage===totalPages;
                    nextBtn.innerHTML = `<i class="bi bi-chevron-right"></i>`;
<<<<<<< HEAD
<<<<<<< HEAD
                    nextBtn.addEventListener('click', () => {
                        if(currentPage<totalPages){ renderPage(currentPage+1); window.scrollTo({top:0, behavior:'smooth'}); }
                    });
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
                    nextBtn.onclick = () => {
                        if(currentPage < totalPages){
                            renderPage(currentPage + 1);
                            frameB.scrollTop = 0;
                        }
                    };
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
<<<<<<< HEAD

=======
        
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
        
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
<<<<<<< HEAD
            frameB.style.paddingBottom = '50px';
=======
            frameB.style.paddingBottom = "70px";
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
            frameB.style.paddingBottom = "70px";
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        
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
<<<<<<< HEAD
<<<<<<< HEAD

=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    
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
<<<<<<< HEAD
<<<<<<< HEAD
    } else if (featureName === 'LAPORAN') {

        const today = new Date().toISOString().split("T")[0];
        const monthID = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
    
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    
    } else if (featureName === 'LAPORAN') {
        const today = new Date().toISOString().split("T")[0];
        const monthID = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        const formatID = (d) => {
            const x = new Date(d);
            return `${String(x.getDate()).padStart(2,"0")} ${monthID[x.getMonth()]} ${String(x.getFullYear()).slice(-2)}`;
        };
<<<<<<< HEAD
<<<<<<< HEAD
    
        if (window.laporanAbortController) window.laporanAbortController.abort();
        window.laporanAbortController = new AbortController();
        const { signal } = window.laporanAbortController;
    
        contentArea.innerHTML = `
    
        <style>
            #pdfRenderCanvas { max-width: 100%; height: auto !important; display: none; border: 1px solid rgba(255,255,255,0.08); border-radius: 6px; }
    
            #btnPdfDownload {
                border-radius: 20px; font-size: 0.78rem; font-weight: 600; padding: 5px 14px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.4); transition: opacity .2s, transform .2s; display: none;
            }
            #btnPdfDownload:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(0,0,0,0.5); }
    
            .btn-pdf-nav { min-width: 80px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; letter-spacing: .3px; }
            #pdfPageInfo { font-size: 0.78rem; font-weight: 700; color: #fff; background: rgba(255,255,255,0.12); padding: 4px 14px; border-radius: 20px; white-space: nowrap; }
    
            #pdfLoadingOverlay {
                position: absolute; inset: 0; background: rgba(0,0,0,0.60); z-index: 50; border-radius: 8px;
                display: none; align-items: center; justify-content: center; flex-direction: column; gap: 10px;
            }
            #pdfEmptyState { padding: 60px 20px; color: rgba(255,255,255,0.35); text-align: center; }
    
            /* ── Kartu pilihan sub laporan (dipakai semua jenis laporan) ── */
            .tipe-section { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.65rem; }
            .tipe-section-label {
                font-size: 0.75rem; font-weight: 700; color: rgba(255,255,255,0.5);
                text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 0.6rem;
                display: flex; align-items: center; gap: 0.4rem;
            }
            .tipe-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; }
            @media (max-width: 576px) {
                .tipe-options { grid-template-columns: 1fr !important; }
            }
            .tipe-option {
                display: flex; align-items: center; gap: 0.6rem; padding: 0.6rem 0.65rem;
                background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);
                border-radius: 6px; cursor: pointer; transition: all 0.2s ease;
            }
            .tipe-option:hover { background: rgba(255,255,255,0.07); }
            .tipe-option input[type="radio"] { cursor: pointer; accent-color: #3d9cf0; flex-shrink: 0; }
            .tipe-option label { flex: 1; cursor: pointer; margin: 0; font-size: 0.76rem; color: rgba(255,255,255,0.75); }
            .tipe-option.selected { background: rgba(61,156,240,0.14); border-color: #3d9cf0; }
    
            .filter-section { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 10px 14px; }
            .filter-row { display: flex; align-items: center; gap: 12px; }
            .filter-row-label {
                flex: 0 0 120px; font-size: 0.78rem; font-weight: 700; color: rgba(255,255,255,0.55);
                text-transform: uppercase; letter-spacing: .4px; display: flex; align-items: center; justify-content: space-between;
            }
            .filter-row-label i { font-size: .8rem; }
            .filter-row-control { flex: 1; min-width: 0; }
    
            .select2-container--default .select2-selection--multiple {
                background: rgba(255,255,255,0.06) !important; border-color: rgba(255,255,255,0.12) !important;
                border-radius: 6px !important; min-height: 38px !important; max-height: 44px;
                overflow-y: auto !important; overflow-x: hidden !important; padding: 2px 4px !important;
            }
            .select2-container--default .select2-selection--multiple::-webkit-scrollbar { width: 4px; height: 0px !important; }
            .select2-container--default .select2-selection--multiple::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); border-radius: 4px; }
            .select2-container--default .select2-selection--multiple::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
            .select2-container--default .select2-selection--multiple .select2-search__field { max-width: 100% !important; margin-top: 5px !important; color: #fff !important; }
            .select2-container--default .select2-selection--multiple .select2-selection__choice {
                background: #3d9cf0 !important; border: none !important; color: #fff !important; font-size: 0.75rem !important;
                border-radius: 4px !important; padding: 2px 8px 2px 24px !important; position: relative !important; margin-top: 5px !important;
            }
            .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                color: rgba(255,255,255,0.8) !important; position: absolute !important; left: 6px !important; top: 50% !important;
                transform: translateY(-50%) !important; border: none !important; padding: 0 !important; margin-right: 0 !important;
            }
            .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover { color: #fff !important; background: transparent !important; }
            .select2-dropdown { background: #1a1d2e !important; border-color: rgba(255,255,255,0.12) !important; }
            .select2-search--dropdown .select2-search__field { background: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.15) !important; color: #fff !important; border-radius: 4px; }
            .select2-results__option { font-size: 0.83rem !important; color: #e8eaf6 !important; }
            .select2-results__option--highlighted { background: #3d9cf0 !important; }
            .select2-results__option[aria-selected=true] { background: rgba(61,156,240,0.25) !important; }
            .select2-container--default .select2-results__option--disabled { color: rgba(255,255,255,0.25) !important; }
            .select2-container { width: 100% !important; }
    
            .quick-range.active { background: rgba(255,255,255,0.18) !important; color: #fff !important; }
    
            /* ============================================================
               ACCORDION 2-STEP
               ============================================================ */
            .laporan-accordion { display: flex; flex-direction: column; gap: 2px; }
            .step-card {
                background: linear-gradient(145deg, #1c2030, #15171f);
                border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; overflow: hidden;
                transition: box-shadow .25s ease, border-color .25s ease;
            }
            .step-card:has(.accordion-button:not(.collapsed)) {
                border-color: rgba(61,156,240,0.4); box-shadow: 0 4px 18px rgba(61,156,240,0.12);
            }
            .step-card .accordion-button {
                background: transparent; color: #fff; padding: 14px 16px; display: flex; align-items: center;
                gap: 12px; border: none; box-shadow: none !important; width: 100%; text-align: left;
            }
            .step-card .accordion-button:not(.collapsed) { background: rgba(61,156,240,0.07); }
            .step-card .accordion-button::after { filter: invert(1); margin-left: auto; flex-shrink: 0; }
            .step-num {
                width: 28px; height: 28px; flex-shrink: 0; border-radius: 50%; background: rgba(255,255,255,0.08);
                display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .78rem;
                color: rgba(255,255,255,0.6); transition: all .25s ease;
            }
            .accordion-button:not(.collapsed) .step-num { background: #3d9cf0; color: #fff; box-shadow: 0 0 0 4px rgba(61,156,240,0.18); }
    
            .step-title { display: flex; flex-direction: row; flex-wrap: wrap; align-items: baseline; gap: 6px; min-width: 0; flex: 1; }
            .step-title-main { font-size: .86rem; font-weight: 700; color: #fff; flex-shrink: 0; }
            .step-summary {
                font-size: .76rem; font-weight: 400; color: rgba(255,255,255,0.45);
                white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;
            }
            .step-summary::before { content: "•"; margin-right: 6px; opacity: .5; }
    
            .step-card .accordion-body { padding: 8px 12px 10px; background: rgba(255,255,255,0.015); }
            .step-card .btn { border-radius: 10px; font-weight: 600; }
            .step-card .quick-range, .step-card .quarter-btn, .step-card #btnQuarterMode { border-radius: 8px; }
    
            @media (min-width: 768px) {
                .step-card .accordion-body { padding: 14px 16px 16px; }
            }
    
            .step-card .accordion-button.step-disabled { pointer-events: none; opacity: 0.4; cursor: not-allowed; }
            .step-card .accordion-button.step-disabled .step-summary::after { content: " (pilih laporan dahulu)"; }
    
            /* ── Toolbar Periode: responsif — horizontal di desktop, menumpuk di HP ── */
            .periode-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                flex-wrap: nowrap;
                padding: 8px 10px;
                background: rgba(255,255,255,0.03);
                border: 1px solid rgba(255,255,255,0.07);
                border-radius: 10px;
                margin-bottom: 10px;
            }
            .periode-toolbar .quick-group { display: flex; gap: 6px; flex-shrink: 0; }
            .periode-toolbar .manual-date-wrap { flex: 1; display: flex; justify-content: center; min-width: 110px; }
            .periode-toolbar #btnRangeDate { white-space: nowrap; }
            .periode-toolbar .quarter-group { display: flex; gap: 6px; flex-shrink: 0; }
    
            @media (max-width: 640px) {
                .periode-toolbar {
                    flex-direction: column;
                    align-items: stretch;
                    flex-wrap: wrap;
                }
                .periode-toolbar .quick-group { justify-content: center; flex-wrap: wrap; }
                .periode-toolbar .manual-date-wrap { justify-content: center; }
                .periode-toolbar .quarter-group { justify-content: center; flex-wrap: wrap; }
            }
    
            .sub-laporan-divider { border-top: 1px solid rgba(255,255,255,0.08); margin: 10px 0 10px; }
    
            /* ============================================================
               MODAL HASIL LAPORAN — ukuran sedang, terpusat (gaya kartu
               seperti modal preview produk), bukan fullscreen.
               ============================================================ */
            .modal-hasil .modal-dialog { max-width: 760px; margin: 2rem auto; }
            .modal-hasil .modal-content {
                background: #15171f; border: 1px solid rgba(255,255,255,0.08);
                border-radius: 18px; overflow: hidden;
                max-height: 85vh; display: flex; flex-direction: column;
                box-shadow: 0 12px 40px rgba(0,0,0,0.45);
            }
            .modal-hasil .modal-header {
                background: #f4f5f8; color: #16181d;
                border-bottom: 1px solid rgba(0,0,0,0.08);
                padding: 12px 18px; flex-shrink: 0;
            }
            .modal-hasil .modal-header .step-title-main { color: #16181d; }
            .modal-hasil .modal-header .step-summary { color: rgba(0,0,0,0.45); }
            .modal-hasil .modal-header .step-summary::before { content: none; }
    
            .btn-pdf-download-pill {
                background: rgba(0,0,0,0.06); border: none; color: #16181d;
                border-radius: 20px; font-size: .8rem; font-weight: 600; padding: 7px 16px;
                display: inline-flex; align-items: center; gap: 6px;
            }
            .btn-pdf-download-pill:hover { background: rgba(0,0,0,0.1); }
    
            .btn-pdf-close-circle {
                width: 38px; height: 38px; border-radius: 50%;
                background: rgba(220,53,69,0.12); border: none; color: #dc3545;
                display: inline-flex; align-items: center; justify-content: center; font-size: 1rem;
            }
            .btn-pdf-close-circle:hover { background: rgba(220,53,69,0.2); }
    
            .modal-hasil .modal-body {
                flex: 1; min-height: 0; position: relative;
                display: flex; align-items: center; justify-content: center;
                padding: 16px 56px;
                background: #0b0d12;
            }
            .pdf-frame-wrap {
                width: 100%; max-width: 620px; height: 60vh;
                display: flex; flex-direction: column; align-items: center; gap: 10px;
            }
            #framePdfViewer {
                flex: 1; width: 100%; min-height: 0; overflow-y: auto; overflow-x: hidden;
                -webkit-overflow-scrolling: touch; border-radius: 10px; background: #1b1e29;
                position: relative; display: flex; flex-direction: column;
            }
            .pdf-page-pill {
                background: rgba(255,255,255,0.12); color: #fff; font-size: .78rem; font-weight: 700;
                padding: 5px 16px; border-radius: 20px; flex-shrink: 0;
            }
    
            .pdf-side-nav {
                position: absolute; top: 50%; transform: translateY(-50%);
                width: 42px; height: 42px; border-radius: 50%;
                background: rgba(255,255,255,0.08); border: none; color: #fff;
                display: flex; align-items: center; justify-content: center; font-size: 1.05rem;
                transition: background .2s;
            }
            .pdf-side-nav:hover:not(:disabled) { background: rgba(255,255,255,0.18); }
            .pdf-side-nav:disabled { opacity: 0.25; cursor: not-allowed; }
            .pdf-side-nav-left { left: 10px; }
            .pdf-side-nav-right { right: 10px; }
    
            @media (max-width: 768px) {
                .modal-hasil .modal-dialog { max-width: 94%; margin: 1rem auto; }
                .modal-hasil .modal-content { max-height: 92vh; border-radius: 14px; }
                .modal-hasil .modal-body { padding: 14px 38px; }
                .pdf-frame-wrap { height: 55vh; max-width: 100%; }
                .pdf-side-nav { width: 36px; height: 36px; }
                .pdf-side-nav-left { left: 2px; }
                .pdf-side-nav-right { right: 2px; }
            }
        </style>
    
        <div class="laporan-container text-start">
    
            <div class="accordion laporan-accordion" id="laporanAccordion">
    
                <!-- ============== STEP 1 — PILIH LAPORAN ============== -->
                <div class="accordion-item step-card" data-step="1">
                    <h2 class="accordion-header" id="headingStep1">
                        <button id="btnStep1Toggle" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#stepReport" aria-expanded="false" aria-controls="stepReport">
                            <span class="step-num">1</span>
                            <span class="step-title">
                                <span class="step-title-main">Pilih Laporan</span>
                                <span class="step-summary" id="summaryReport">Belum dipilih</span>
                            </span>
                        </button>
                    </h2>
                    <div id="stepReport" class="accordion-collapse collapse" data-bs-parent="#laporanAccordion">
                        <div class="accordion-body">
                            <select id="reportType" class="form-select form-select-sm">
                                <option value="" selected>-- Pilih Laporan --</option>
                                <option value="omset">Omset</option>
                                <option value="target">Penjualan</option>
                                <option value="aktivitas">Management</option>
                                <option value="management">Market</option>
                            </select>
                        </div>
                    </div>
                </div>
    
                <!-- ============== STEP 2 — PERIODE & FILTER ============== -->
                <div class="accordion-item step-card" data-step="2">
                    <h2 class="accordion-header" id="headingStep2">
                        <button id="btnStep2Toggle" class="accordion-button collapsed step-disabled" type="button" data-bs-toggle="collapse" data-bs-target="#stepPeriode" aria-expanded="false" aria-controls="stepPeriode">
                            <span class="step-num">2</span>
                            <span class="step-title">
                                <span class="step-title-main">Periode &amp; Filter</span>
                                <span class="step-summary" id="summaryPeriode">${formatID(today)} – ${formatID(today)}</span>
                            </span>
                        </button>
                    </h2>
                    <div id="stepPeriode" class="accordion-collapse collapse" data-bs-parent="#laporanAccordion">
                        <div class="accordion-body">
    
                            <div class="periode-toolbar">
                                <div class="quick-group">
                                    <button class="btn btn-outline-light btn-sm quick-range" data-range="TDY">TDY</button>
                                    <button class="btn btn-outline-light btn-sm quick-range" data-range="WTD">WTD</button>
                                    <button class="btn btn-outline-light btn-sm quick-range" data-range="MTD">MTD</button>
                                    <button class="btn btn-outline-light btn-sm quick-range" data-range="YTD">YTD</button>
                                </div>
    
                                <div class="manual-date-wrap">
                                    <button id="btnRangeDate" class="btn btn-secondary btn-sm">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <span id="rangeLabel">${formatID(today)} – ${formatID(today)}</span>
                                    </button>
                                    <input id="datePicker" style="position:fixed;top:0;left:0;width:0;height:0;padding:0;border:0;opacity:0;">
                                </div>
    
                                <div id="quarterWrapper" class="quarter-group"></div>
                            </div>
    
                            <div id="subLaporanBody"></div>
    
                            <div class="sub-laporan-divider"></div>
    
                            <button id="btnFilterReport" class="btn btn-info btn-sm w-100">
                                <i class="bi bi-funnel-fill me-1"></i> Tampilkan Laporan
                            </button>
                        </div>
                    </div>
                </div>
    
            </div><!-- /#laporanAccordion -->
    
        </div><!-- /.laporan-container -->
    
        <!-- ============== MODAL HASIL LAPORAN (PDF) — FREEZE, ukuran sedang ============== -->
        <div class="modal fade modal-hasil" id="modalHasilLaporan" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
    
                    <div class="modal-header">
                        <div class="modal-title-row">
                            <span class="step-title-main" id="summaryResult">Belum ada laporan</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button id="btnPdfDownload" class="btn-pdf-download-pill">
                                <i class="bi bi-download"></i> Unduh
                            </button>
                            <button id="btnTutupHasil" class="btn-pdf-close-circle">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
    
                    <div class="modal-body">
    
                        <button id="btnPdfPrev" class="pdf-side-nav pdf-side-nav-left" disabled>
                            <i class="bi bi-chevron-left"></i>
                        </button>
    
                        <div class="pdf-frame-wrap">
                            <div id="pdfLoadingOverlay">
                                <div class="spinner-border text-info" role="status" style="width:2.2rem;height:2.2rem;"></div>
                                <div class="text-white small fw-semibold">Memuat laporan...</div>
                            </div>
    
                            <div id="framePdfViewer">
                                <div id="pdfContentArea" class="flex-grow-1 d-flex flex-column align-items-center justify-content-center p-2">
                                    <div id="pdfEmptyState">
                                        <i class="bi bi-file-earmark-bar-graph fs-1 d-block mb-3"></i>
                                        <div class="fw-semibold mb-1" style="font-size:.9rem;color:rgba(255,255,255,.5);">Belum ada laporan</div>
                                        <div style="font-size:.78rem;color:rgba(255,255,255,.28);">Memuat dokumen...</div>
                                    </div>
                                    <canvas id="pdfRenderCanvas"></canvas>
                                    <div id="pdfFallback" class="text-center p-4 text-white d-none">
                                        <i class="bi bi-exclamation-triangle-fill fs-2 text-warning d-block mb-2"></i>
                                        <p class="small mb-3">Gagal memproses preview PDF di perangkat ini.</p>
                                        <a id="pdfFallbackLink" href="#" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>Buka PDF di Tab Baru
                                        </a>
                                    </div>
                                </div>
                            </div>
    
                            <span id="pdfPageInfo" class="pdf-page-pill">Hal 1 / 1</span>
                        </div>
    
                        <button id="btnPdfNext" class="pdf-side-nav pdf-side-nav-right" disabled>
                            <i class="bi bi-chevron-right"></i>
                        </button>
    
                    </div>
    
                </div>
            </div>
        </div>
        `;
    
        // ============================================================
        // RE-BIND ELEMEN
        // ============================================================
        const reportType       = document.getElementById("reportType");
        const btnStep2Toggle    = document.getElementById("btnStep2Toggle");
        const summaryReport    = document.getElementById("summaryReport");
        const summaryPeriode   = document.getElementById("summaryPeriode");
        const summaryResult    = document.getElementById("summaryResult");
        const stepReport       = document.getElementById("stepReport");
        const stepPeriode      = document.getElementById("stepPeriode");
        const btnRangeDate     = document.getElementById("btnRangeDate");
        const rangeLabel       = document.getElementById("rangeLabel");
        const datePicker       = document.getElementById("datePicker");
        const btnFilterReport  = document.getElementById("btnFilterReport");
        const canvas           = document.getElementById("pdfRenderCanvas");
        const pdfPageInfo      = document.getElementById("pdfPageInfo");
        const btnPdfPrev       = document.getElementById("btnPdfPrev");
        const btnPdfNext       = document.getElementById("btnPdfNext");
        const pdfEmptyState    = document.getElementById("pdfEmptyState");
        const pdfFallback      = document.getElementById("pdfFallback");
        const pdfFallbackLink  = document.getElementById("pdfFallbackLink");
        const subLaporanBody   = document.getElementById("subLaporanBody");
        const btnPdfDownload   = document.getElementById("btnPdfDownload");
        const btnTutupHasil    = document.getElementById("btnTutupHasil");
    
        const modalHasilEl   = document.getElementById("modalHasilLaporan");
        const modalHasil     = new bootstrap.Modal(modalHasilEl, { backdrop: "static", keyboard: false });
    
        // ============================================================
        // STATE
        // ============================================================
        window.__laporanState = window.__laporanState || {};
        let startDate = window.__laporanState.startDate || today;
        let endDate   = window.__laporanState.endDate   || today;
    
        const getAccountOfficer = () => {
            try {
                const selectedOfficerSpan = document.getElementById("selectedOfficer");
                if (selectedOfficerSpan && selectedOfficerSpan.dataset.officerId) {
                    const officerId = selectedOfficerSpan.dataset.officerId;
                    if (officerId && officerId !== "") return officerId;
                }
                const activeOfficerItem = document.querySelector("#officerList .officer-item.active");
                if (activeOfficerItem) {
                    const officerId = activeOfficerItem.getAttribute("data-id");
                    if (officerId) return officerId;
                }
                return "all";
            } catch (e) {
                console.error("Gagal mengambil AO:", e);
                return "all";
            }
        };
    
        let quarterMode          = 3;
        let selectedSub          = "";
        let hasRenderedOnce      = false;
        let currentTipeLabel     = "";
    
        let filterBrands    = [];
        let filterVarians   = [];
        let filterCustomers = [];
        let filterOfficers  = [];
    
        let pdfDoc         = null;
        let currentPage    = 1;
        let pdfBlob        = null;
        let pageRendering  = false;
        let pageNumPending = null;
        const PDF_SCALE = () => window.innerWidth < 600 ? 1.4 : window.innerWidth < 1024 ? 1.6 : 1.8;
    
        // ============================================================
        // STEP NAVIGATION & SUMMARY
        // ============================================================
        const reportLabels = { omset: "Omset", target: "Penjualan", aktivitas: "Management", management: "Market" };
    
        // Label tiap pilihan sub laporan, per jenis laporan (radio name → label map)
        const subLaporanLabels = {
            tipeRadio:        { v1: "Varian - Customer", v2: "Customer - Varian", v3: "Varian - PIC" },
            omsetRadio:       { penjualan: "Rekap Invoice", pembayaran: "Pelunasan" },
            aktivitasRadio:   { followup: "Follow Up" },
            managementRadio:  { management_by_brand: "By Market Brand", management_by_zone: "By Zone" }
        };
    
        function goToStep(stepEl) {
            bootstrap.Collapse.getOrCreateInstance(stepEl, { toggle: false }).show();
        }
    
        function updateSummaryReport() {
            summaryReport.textContent = reportLabels[reportType.value] || "Belum dipilih";
        }
    
        function updateStep2Summary() {
            const dateText = rangeLabel.innerText;
            summaryPeriode.textContent = currentTipeLabel ? `${currentTipeLabel} • ${dateText}` : dateText;
        }
    
        function updateSummaryResult(text) {
            summaryResult.textContent = text;
        }
    
        function setStep2Enabled(enabled) {
            if (enabled) {
                btnStep2Toggle.classList.remove("step-disabled");
            } else {
                btnStep2Toggle.classList.add("step-disabled");
                bootstrap.Collapse.getOrCreateInstance(stepPeriode, { toggle: false }).hide();
            }
        }
    
        // ============================================================
        // RENDER PAGE
        // ============================================================
        const renderPage = async (pageNum) => {
            if (!pdfDoc) return;
    
            if (pageRendering) { pageNumPending = pageNum; return; }
    
            pageRendering = true;
            document.getElementById("pdfLoadingOverlay").style.display = "flex";
            canvas.style.display = "none";
    
            try {
                const page     = await pdfDoc.getPage(pageNum);
                const scale    = PDF_SCALE();
                const viewport = page.getViewport({ scale });
    
                canvas.width  = viewport.width;
                canvas.height = viewport.height;
    
                await page.render({ canvasContext: canvas.getContext("2d"), viewport }).promise;
    
                currentPage = pageNum;
                pdfPageInfo.textContent = `Hal ${currentPage} / ${pdfDoc.numPages}`;
                btnPdfPrev.disabled = currentPage <= 1;
                btnPdfNext.disabled = currentPage >= pdfDoc.numPages;
    
                const hasMultiplePages = pdfDoc.numPages > 1;
                btnPdfPrev.style.visibility = hasMultiplePages ? "visible" : "hidden";
                btnPdfNext.style.visibility = hasMultiplePages ? "visible" : "hidden";
    
                canvas.style.display   = "block";
                pdfEmptyState.style.display = "none";
    
            } catch (err) {
                console.error("Render page error:", err);
            } finally {
                pageRendering = false;
                document.getElementById("pdfLoadingOverlay").style.display = "none";
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            }
        };
    
        btnPdfPrev.onclick = () => { if (currentPage > 1) renderPage(currentPage - 1); };
        btnPdfNext.onclick = () => { if (pdfDoc && currentPage < pdfDoc.numPages) renderPage(currentPage + 1); };
    
        btnPdfDownload.onclick = () => {
            if (!pdfBlob) return alert("Belum ada laporan yang dimuat.");
            const namaFile = `laporan_${reportType.value}_${selectedSub}_${startDate}_sd_${endDate}.pdf`;
            const url      = URL.createObjectURL(pdfBlob);
            const a        = document.createElement("a");
            a.href         = url;
            a.download     = namaFile;
            a.click();
            URL.revokeObjectURL(url);
        };
    
        btnTutupHasil.onclick = () => modalHasil.hide();
    
        document.addEventListener("keydown", (e) => {
            if (!pdfDoc || !modalHasilEl.classList.contains("show")) return;
            if (e.key === "ArrowRight") btnPdfNext.click();
            if (e.key === "ArrowLeft")  btnPdfPrev.click();
        }, { signal });
    
        // ============================================================
        // RENDER PDF FETCH (payload tidak diubah)
        // ============================================================
        const renderPDF = async () => {
            if (!reportType.value || !selectedSub) return;
    
            pdfDoc      = null;
            pdfBlob     = null;
            currentPage = 1;
            canvas.style.display        = "none";
            pdfEmptyState.style.display = "none";
            pdfFallback.classList.add("d-none");
            btnPdfDownload.style.display = "none";
    
            document.getElementById("pdfLoadingOverlay").style.display = "flex";
            updateSummaryResult("Memuat laporan...");
    
            const isFollowUp = (reportType.value === "aktivitas" && selectedSub === "followup");
    
            try {
                const res = isFollowUp
                    ? await fetch(`/report/doctor/export-pdf-all-v2/${getAccountOfficer()}?start_date=${startDate}&end_date=${endDate}`, {
                        method: "GET"
                    })
                    : await fetch("/report/doctor/report/preview", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content")
                        },
                        body: JSON.stringify({
                            type      : reportType.value,
                            sub       : selectedSub,
                            start     : startDate,
                            end       : endDate,
                            officer   : (reportType.value === "target" && getAccountOfficer() === "all")
                                            ? ((filterOfficers && filterOfficers.length > 0) ? filterOfficers : ['all'])
                                            : getAccountOfficer(),
                            brands    : (filterBrands && filterBrands.length > 0) ? filterBrands : ['all'],
                            varians   : (filterVarians && filterVarians.length > 0) ? filterVarians : ['all'],
                            customers : (filterCustomers && filterCustomers.length > 0) ? filterCustomers : ['all']
                        })
                    });
    
                if (!res.ok) {
                    let msg = `Terjadi kesalahan pada server (${res.status})`;
                    if (!isFollowUp) {
                        const errData = await res.json().catch(() => ({}));
                        msg = errData.message || msg;
                    }
                    throw new Error(msg);
                }
    
                pdfBlob           = await res.blob();
                const arrayBuffer = await pdfBlob.arrayBuffer();
    
                pdfDoc = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
    
                hasRenderedOnce = true;
                document.getElementById("pdfLoadingOverlay").style.display = "none";
                btnPdfDownload.style.display = "inline-block";
    
                await renderPage(1);
                updateSummaryResult(`${reportLabels[reportType.value]} • ${formatID(startDate)} – ${formatID(endDate)}`);
    
            } catch (err) {
                console.error("PDF Error:", err);
                document.getElementById("pdfLoadingOverlay").style.display = "none";
                canvas.style.display = "none";
    
                alert(err.message);
    
                pdfFallback.classList.remove("d-none");
                pdfFallbackLink.href = isFollowUp
                    ? `/report/doctor/export-pdf-all-v2/${getAccountOfficer()}?start_date=${startDate}&end_date=${endDate}`
                    : `/report/doctor/report/preview-get?type=${reportType.value}&sub=${selectedSub}&start=${startDate}&end=${endDate}&officer=${getAccountOfficer()}`;
                updateSummaryResult("Gagal memuat laporan");
            }
        };
    
        function reloadCurrentReport() {
            renderPDF();
        }
    
        // ============================================================
        // KUMPULKAN SUB LAPORAN TERPILIH (semua jenis pakai radio card)
        // ============================================================
        function collectSelectedSub() {
            const type = reportType.value;
            const radioNameMap = {
                aktivitas:  "aktivitasRadio",
                target:     "tipeRadio",
                omset:      "omsetRadio",
                management: "managementRadio"
            };
            const radioName = radioNameMap[type];
            if (!radioName) return "";
            const checked = document.querySelector(`input[name="${radioName}"]:checked`);
            return checked ? checked.value : "";
        }
    
        btnFilterReport.onclick = () => {
            if (!reportType.value) return alert("Pilih laporan terlebih dahulu.");
    
            selectedSub = collectSelectedSub();
            if (!selectedSub) return alert("Sub laporan belum dipilih.");
    
            if (reportType.value === "target") {
                filterBrands    = $("#selectBrand").val()     || [];
                filterVarians   = $("#selectVarian").val()    || [];
                filterCustomers = $("#selectCustomer").val()  || [];
                filterOfficers  = $("#selectAo").val()        || [];
            }
    
            updateSummaryResult("Memuat laporan...");
            modalHasil.show();
            renderPDF();
        };
    
        // ============================================================
        // FETCH HELPERS
        // ============================================================
        const fetchCustomers = async (officer) => {
            try {
                const aoList = (officer || getAccountOfficer() || "all")
                    .toString().split(",").map(s => s.trim()).filter(Boolean);
    
                if (aoList.length <= 1) {
                    const ao  = aoList[0] || "all";
                    const res = await fetch(`/report/doctor/get-list-market?officer=${encodeURIComponent(ao)}`);
                    const json = await res.json();
                    return (json.existing || []).map(c => {
                        const cityName = c.text_kota ? ` (${c.text_kota})` : '';
                        return { id: c.id, name: `${c.name}${cityName}` };
                    });
                }
    
                const results = await Promise.all(
                    aoList.map(ao =>
                        fetch(`/report/doctor/get-list-market?officer=${encodeURIComponent(ao)}`)
                            .then(res => res.json())
                            .then(json => json.existing || [])
                            .catch(() => [])
                    )
                );
    
                const merged = new Map();
                results.flat().forEach(c => {
                    if (!merged.has(c.id)) {
                        const cityName = c.text_kota ? ` (${c.text_kota})` : '';
                        merged.set(c.id, { id: c.id, name: `${c.name}${cityName}` });
                    }
                });
    
                return Array.from(merged.values());
    
            } catch (e) {
                console.error("Fetch customer error:", e);
                return [];
            }
        };
    
        const fetchVarians = async () => {
            try {
                const res  = await fetch("/report/doctor/proxy/product-pack");
                const json = await res.json();
                return json.map(p => ({
                    id: p.id,
                    code: p.product_code,
                    name: p.product_name,
                    pack: p.packaging || p.pack_name || '',
                    brand: p.brand_name || ''
                }));
            } catch (e) {
                console.error("Fetch varian error:", e);
                return [];
            }
        };
    
        // ============================================================
        // SUB LAPORAN TEMPLATES — SEMUA JENIS PAKAI KARTU RADIO YANG SAMA
        // ============================================================
        const modalAktivitasHTML = `
            <div class="tipe-section">
                <div class="tipe-section-label"><i class="bi bi-clipboard-check"></i> Sub Laporan</div>
                <div class="tipe-options" style="grid-template-columns: repeat(1, 1fr);">
                    <div class="tipe-option" data-tipe-wrap="followup">
                        <input type="radio" id="aktivitasFollowup" name="aktivitasRadio" value="followup">
                        <label for="aktivitasFollowup">Follow Up</label>
                    </div>
                </div>
            </div>`;
    
        const modalOmsetHTML = `
            <div class="tipe-section">
                <div class="tipe-section-label"><i class="bi bi-receipt"></i> Sub Laporan</div>
                <div class="tipe-options" style="grid-template-columns: repeat(2, 1fr);">
                    <div class="tipe-option" data-tipe-wrap="penjualan">
                        <input type="radio" id="omsetPenjualan" name="omsetRadio" value="penjualan">
                        <label for="omsetPenjualan">Rekap Invoice</label>
                    </div>
                    <div class="tipe-option" data-tipe-wrap="pembayaran">
                        <input type="radio" id="omsetPembayaran" name="omsetRadio" value="pembayaran">
                        <label for="omsetPembayaran">Pelunasan</label>
                    </div>
                </div>
            </div>`;
    
        const modalManagementHTML = `
            <div class="tipe-section">
                <div class="tipe-section-label"><i class="bi bi-diagram-3"></i> Sub Laporan</div>
                <div class="tipe-options" style="grid-template-columns: repeat(2, 1fr);">
                    <div class="tipe-option" data-tipe-wrap="management_by_brand">
                        <input type="radio" id="mgmtBrand" name="managementRadio" value="management_by_brand">
                        <label for="mgmtBrand">By Market Brand</label>
                    </div>
                    <div class="tipe-option" data-tipe-wrap="management_by_zone">
                        <input type="radio" id="mgmtZone" name="managementRadio" value="management_by_zone">
                        <label for="mgmtZone">By Zone</label>
                    </div>
                </div>
            </div>`;
    
        const modalPenjualanHTML = `
            <div class="d-flex flex-column gap-2 text-start">
    
                <div class="tipe-section">
                    <div class="tipe-section-label"><i class="bi bi-file-bar-graph"></i> Tipe Laporan</div>
                    <div class="tipe-options">
                        <div class="tipe-option" data-tipe-wrap="v1">
                            <input type="radio" id="tipeV1" name="tipeRadio" value="v1">
                            <label for="tipeV1">Varian - Customer</label>
                        </div>
                        <div class="tipe-option" data-tipe-wrap="v2">
                            <input type="radio" id="tipeV2" name="tipeRadio" value="v2">
                            <label for="tipeV2">Customer - Varian</label>
                        </div>
                        <div class="tipe-option" data-tipe-wrap="v3">
                            <input type="radio" id="tipeV3" name="tipeRadio" value="v3">
                            <label for="tipeV3">Varian - PIC</label>
                        </div>
                    </div>
                </div>
    
                <div class="filter-section" id="aoSectionWrapper">
                    <div class="filter-row">
                        <div class="filter-row-label">
                            <span><i class="bi bi-person-badge me-1"></i>AO / Officer</span>
                            <i id="infoAo" class="bi bi-info-circle-fill text-warning" style="font-size:.8rem; cursor:pointer; display:none;" data-bs-toggle="tooltip"></i>
                        </div>
                        <div class="filter-row-control">
                            <select id="selectAo" class="form-select form-select-sm" multiple></select>
                        </div>
                    </div>
                    <div id="aoLockInfo" class="small mt-2" style="display:none; color: rgba(255,255,255,0.45);">
                        <i class="bi bi-lock-fill"></i>
                        Mengikuti AO aktif: <strong id="aoLockName">-</strong>.
                        <a href="#" id="lnkGantiAoGlobal" class="text-decoration-underline">Ganti di filter global</a>
                        <div style="font-size:.7rem; opacity:.7;">Catatan: mengganti AO akan mereset pilihan tanggal &amp; laporan.</div>
                    </div>
                </div>
    
                <div class="filter-section">
                    <div class="filter-row">
                        <div class="filter-row-label">
                            <span><i class="bi bi-bookmark me-1"></i>Brand</span>
                            <i id="infoBrand" class="bi bi-info-circle-fill text-warning" style="font-size:.8rem; cursor:pointer; display:none;" data-bs-toggle="tooltip"></i>
                        </div>
                        <div class="filter-row-control">
                            <select id="selectBrand" class="form-select form-select-sm" multiple>
                                <option value="all">Semua</option>
                                <option value="Senses">Senses</option>
                                <option value="GCF">GCF</option>
                                <option value="PPI FF">PPI FF</option>
                                <option value="PPI NON FF">PPI NON FF</option>
                            </select>
                        </div>
                    </div>
                </div>
    
                <div class="filter-section" id="varianSectionWrapper">
                    <div class="filter-row">
                        <div class="filter-row-label">
                            <span><i class="bi bi-box-seam me-1"></i>Varian</span>
                            <i id="infoVarian" class="bi bi-info-circle-fill text-warning" style="font-size:.8rem; cursor:pointer; display:none;" data-bs-toggle="tooltip"></i>
                        </div>
                        <div class="filter-row-control">
                            <select id="selectVarian" class="form-select form-select-sm" multiple></select>
                        </div>
                    </div>
                </div>
    
                <div class="filter-section" id="customerSectionWrapper">
                    <div class="filter-row">
                        <div class="filter-row-label">
                            <span><i class="bi bi-people me-1"></i>Customer</span>
                            <i id="infoCustomer" class="bi bi-info-circle-fill text-warning" style="font-size:.8rem; cursor:pointer; display:none;" data-bs-toggle="tooltip"></i>
                        </div>
                        <div class="filter-row-control">
                            <select id="selectCustomer" class="form-select form-select-sm" multiple></select>
                        </div>
                    </div>
                </div>
    
            </div>`;
    
        // ============================================================
        // GENERALISASI: bind interaksi kartu radio (sensitivitas klik +
        // highlight terpilih + update summary) untuk SEMUA grup radio,
        // apapun jenis laporannya.
        // ============================================================
        function bindRadioCardGroup(radioName) {
            const labelsMap = subLaporanLabels[radioName] || {};
    
            document.querySelectorAll(`input[name="${radioName}"]`).forEach(radio => {
                radio.addEventListener('change', (e) => {
                    document.querySelectorAll(`input[name="${radioName}"]`).forEach(r => {
                        const opt = r.closest('.tipe-option');
                        if (opt) opt.classList.toggle('selected', r === e.target);
                    });
    
                    if (radioName === 'tipeRadio') {
                        const customerWrapper = document.getElementById('customerSectionWrapper');
                        if (customerWrapper) {
                            customerWrapper.style.display = (e.target.value === 'v3') ? 'none' : '';
                        }
                    }
    
                    currentTipeLabel = labelsMap[e.target.value] || "";
                    updateStep2Summary();
                    window.__laporanState.selectedSub = e.target.value;
                });
    
                // Perbaikan sensitivitas klik: seluruh area kartu (padding,
                // background) ikut memicu radio, bukan cuma tepat di atas
                // teks/lingkaran radio.
                const opt = radio.closest('.tipe-option');
                if (opt) {
                    opt.addEventListener('click', (e) => {
                        if (e.target.closest('label') || e.target.tagName === 'INPUT') return;
                        if (!radio.checked) {
                            radio.checked = true;
                            radio.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                }
            });
        }
    
        // ============================================================
        // RENDER SUB LAPORAN (INLINE) — konsisten untuk semua jenis
        // ============================================================
        function renderSubLaporan(type) {
            selectedSub = "";
            currentTipeLabel = "";
            updateStep2Summary();
    
            if (type === "aktivitas") {
                subLaporanBody.innerHTML = modalAktivitasHTML;
                bindRadioCardGroup('aktivitasRadio');
            } else if (type === "target") {
                subLaporanBody.innerHTML = modalPenjualanHTML;
                initPenjualanFilters();
                bindRadioCardGroup('tipeRadio');
            } else if (type === "omset") {
                subLaporanBody.innerHTML = modalOmsetHTML;
                bindRadioCardGroup('omsetRadio');
            } else if (type === "management") {
                subLaporanBody.innerHTML = modalManagementHTML;
                bindRadioCardGroup('managementRadio');
            } else {
                subLaporanBody.innerHTML = "";
            }
        }
    
        async function initPenjualanFilters() {
            filterBrands = filterVarians = filterCustomers = filterOfficers = [];
    
            const bindSelectInfo = (selectId, iconId) => {
                const $select = $(`#${selectId}`);
                const iconEl = document.getElementById(iconId);
                if (!iconEl) return;
    
                let bsTooltip = new bootstrap.Tooltip(iconEl, { html: true, title: "..." });
    
                $select.on("change", function() {
                    const selectedData = $(this).select2("data");
                    if (selectedData.length > 0) {
                        iconEl.style.display = "inline-block";
                        const texts = selectedData.map(item => item.text).join('<br>• ');
                        const tooltipContent = `<div class="text-start" style="font-size:0.75rem; max-width:250px;"><strong>Terpilih (${selectedData.length}):</strong><br>• ${texts}</div>`;
    
                        bsTooltip.dispose();
                        iconEl.setAttribute('title', tooltipContent);
                        bsTooltip = new bootstrap.Tooltip(iconEl, { html: true, placement: 'top' });
                    } else {
                        iconEl.style.display = "none";
                        bsTooltip.hide();
                    }
                });
            };
    
            const handleExclusiveAll = (selectId) => {
                $(`#${selectId}`).on('select2:select', function (e) {
                    const data = e.params.data;
                    if (data.id === 'all') {
                        $(this).val(['all']).trigger('change');
                    } else {
                        let currentVal = $(this).val() || [];
                        if (currentVal.includes('all')) {
                            currentVal = currentVal.filter(val => val !== 'all');
                            $(this).val(currentVal).trigger('change');
                        }
                    }
                });
            };
    
            const configSelect2 = { allowClear: true, width: '100%' };
    
            // ── AO / Officer ──
            const globalAoId  = getAccountOfficer();
            const isGlobalAll = (globalAoId === "all" || globalAoId === "" || !globalAoId);
            const $ao         = $("#selectAo");
            const aoLockInfo  = document.getElementById("aoLockInfo");
            const aoLockName  = document.getElementById("aoLockName");
    
            if ($ao.hasClass("select2-hidden-accessible")) $ao.select2('destroy');
            $ao.empty();
    
            if (isGlobalAll) {
                $ao.append(new Option("Semua", "all", false, false));
                document.querySelectorAll("#officerList .officer-item").forEach(item => {
                    const id = item.getAttribute("data-id");
                    if (id && id !== "all") {
                        $ao.append(new Option(item.getAttribute("data-name") || id, id, false, false));
                    }
                });
                $ao.prop("disabled", false).select2({ placeholder: "Pilih AO...", ...configSelect2 }).val(['all']).trigger('change.select2');
                bindSelectInfo("selectAo", "infoAo");
                handleExclusiveAll("selectAo");
                aoLockInfo.style.display = "none";
            } else {
                $ao.append(new Option(globalAoId, globalAoId, true, true));
                $ao.prop("disabled", true).select2({ ...configSelect2 });
                aoLockName.textContent = globalAoId;
                aoLockInfo.style.display = "block";
            }
    
            $ao.off('change.aoCustomerDep').on('change.aoCustomerDep', async function () {
                await refreshCustomerOptions();
            });
    
            // ── Brand (default: Semua) ──
            const $brand = $("#selectBrand");
            if ($brand.hasClass("select2-hidden-accessible")) $brand.select2('destroy');
            $brand.select2({ placeholder: "Pilih brand...", ...configSelect2 });
            bindSelectInfo("selectBrand", "infoBrand");
            handleExclusiveAll("selectBrand");
            $brand.val(['all']).trigger('change.select2');
    
            $brand.off('change.brandVarianDep').on('change.brandVarianDep', function () {
                applyBrandFilterToVarian();
            });
    
            // ── Varian (terkunci dulu) ──
            const $varian = $("#selectVarian");
            if ($varian.hasClass("select2-hidden-accessible")) $varian.select2('destroy');
    
            $varian.empty().append(new Option("Pilih brand terlebih dahulu...", "", true, true));
            $varian.prop("disabled", true).select2({ placeholder: "Pilih brand terlebih dahulu...", ...configSelect2 });
    
            try {
                const varians = await fetchVarians();
                window.__varianRawData = varians;
                applyBrandFilterToVarian();
            } catch (e) {
                console.error("Error loading varians:", e);
            }
    
            // ── Customer (default: Semua) ──
            const $customer = $("#selectCustomer");
            if ($customer.hasClass("select2-hidden-accessible")) $customer.select2('destroy');
            $customer.empty().append(new Option("Memuat data...", "", true, true));
            $customer.prop("disabled", true).select2({ placeholder: "Memuat data...", ...configSelect2 });
    
            try {
                const customers = await fetchCustomers(getAccountOfficer() || "all");
                if ($customer.hasClass("select2-hidden-accessible")) $customer.select2('destroy');
                $customer.empty();
                if (customers.length === 0) {
                    $customer.append(new Option("Data tidak tersedia", "", true, true));
                    $customer.prop("disabled", false).select2({ placeholder: "Pilih customer...", ...configSelect2 });
                } else {
                    $customer.append(new Option("Semua", "all", false, false));
                    customers.forEach(c => $customer.append(new Option(c.name, c.id, false, false)));
                    $customer.prop("disabled", false).select2({ placeholder: "Pilih customer...", ...configSelect2 }).val(['all']).trigger('change.select2');
                }
                bindSelectInfo("selectCustomer", "infoCustomer");
                handleExclusiveAll("selectCustomer");
            } catch (e) {
                console.error("Error loading customers:", e);
            }
        }
    
        function getEffectiveAoForCustomer() {
            const $ao = $("#selectAo");
            if ($ao.length && !$ao.prop("disabled")) {
                const val = $ao.val() || [];
                if (val.length === 0 || val.includes("all")) return "all";
                return val.join(",");
            }
            return getAccountOfficer() || "all";
        }
    
        async function refreshCustomerOptions() {
            const $customer = $("#selectCustomer");
            if ($customer.hasClass("select2-hidden-accessible")) $customer.select2('destroy');
            $customer.empty().append(new Option("Memuat data...", "", true, true));
            $customer.prop("disabled", true).select2({ placeholder: "Memuat data...", width: '100%' });
    
            try {
                const ao = getEffectiveAoForCustomer();
                const customers = await fetchCustomers(ao);
                if ($customer.hasClass("select2-hidden-accessible")) $customer.select2('destroy');
                $customer.empty();
                if (customers.length === 0) {
                    $customer.append(new Option("Data tidak tersedia", "", true, true));
                    $customer.prop("disabled", false).select2({ placeholder: "Pilih customer...", width: '100%' });
                } else {
                    $customer.append(new Option("Semua", "all", false, false));
                    customers.forEach(c => $customer.append(new Option(c.name, c.id, false, false)));
                    $customer.prop("disabled", false).select2({ placeholder: "Pilih customer...", width: '100%' }).val(['all']).trigger('change.select2');
                }
            } catch (e) {
                console.error("Error loading customers:", e);
            }
        }
    
        function applyBrandFilterToVarian() {
            const $varian = $("#selectVarian");
            if ($varian.length === 0 || !window.__varianRawData) return;
    
            const selectedBrands = $("#selectBrand").val() || [];
    
            if (selectedBrands.length === 0) {
                if ($varian.hasClass("select2-hidden-accessible")) $varian.select2('destroy');
                $varian.empty().append(new Option("Pilih brand terlebih dahulu...", "", true, true));
                $varian.prop("disabled", true).select2({ placeholder: "Pilih brand terlebih dahulu...", width: '100%' });
                return;
            }
    
            const noFilterActive = selectedBrands.includes('all');
    
            $varian.empty();
            $varian.prop("disabled", false);
            $varian.append(new Option("Semua", "all", false, false));
    
            window.__varianRawData.forEach(v => {
                const vBrand = (v.brand || "").trim().toUpperCase();
                const matches = noFilterActive || selectedBrands.some(sb => sb.trim().toUpperCase() === vBrand);
    
                if (matches) {
                    const packSuffix = v.pack ? ` / ${v.pack}` : '';
                    const opt = new Option(`${v.code} - ${v.name}${packSuffix}`, v.id, false, false);
                    opt.setAttribute('data-brand', v.brand);
                    $varian.append(opt);
                }
            });
    
            $varian.select2({ allowClear: true, width: '100%', placeholder: "Pilih varian..." }).val(['all']).trigger('change.select2');
        }
    
        document.addEventListener("click", (e) => {
            if (e.target && e.target.id === "lnkGantiAoGlobal") {
                e.preventDefault();
                goToStep(stepReport);
                document.getElementById("btnSelectOfficer")?.click();
            }
        }, { signal });
    
        // ============================================================
        // REPORT TYPE CHANGE → enable/disable Step 2 + render sub laporan
        // ============================================================
        reportType.addEventListener("change", () => {
            updateSummaryReport();
            window.__laporanState.reportType = reportType.value; // ← tambahkan ini
    
            if (!reportType.value) {
                setStep2Enabled(false);
                subLaporanBody.innerHTML = "";
                currentTipeLabel = "";
                updateStep2Summary();
                return;
            }
    
            setStep2Enabled(true);
            renderSubLaporan(reportType.value);
            goToStep(stepPeriode);
        });
    
        updateSummaryReport();
        updateStep2Summary();
        setStep2Enabled(false);
        
        // ============================================================
        // DEFAULT: set laporan ke "Omset" saat menu pertama dibuka
        // ============================================================
        reportType.value = window.__laporanState.reportType || "omset";
        reportType.dispatchEvent(new Event("change"));
        goToStep(window.__laporanState.reportType ? stepPeriode : stepReport);
    
        // ============================================================
        // QUARTER BUTTONS & DATE PICKER
        // ============================================================
        const quarterWrapper = document.getElementById("quarterWrapper");
        function renderQuarterButtons() {
            quarterWrapper.innerHTML = "";
            const quarters = quarterMode === 3 ? ["Q1","Q2","Q3","Q4"] : quarterMode === 4 ? ["Q1","Q2","Q3"] : ["Q1","Q2"];
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

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

<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
            quarters.forEach(q => {
                const btn = document.createElement("button");
                btn.className = "btn btn-outline-info btn-sm quarter-btn";
                btn.dataset.quarter = q;
                btn.innerText = q;
                quarterWrapper.appendChild(btn);
            });
<<<<<<< HEAD
<<<<<<< HEAD
            const modeBtn = document.createElement("button");
            modeBtn.id = "btnQuarterMode";
            modeBtn.className = "btn btn-warning btn-sm";
            modeBtn.innerText = quarterMode + "M";
            quarterWrapper.appendChild(modeBtn);
    
            document.querySelectorAll(".quarter-btn").forEach(btn => {
                btn.onclick = () => {
                    const y = new Date().getFullYear(), q = btn.dataset.quarter;
                    let start, end;
                    if (quarterMode === 3) {
                        if (q==="Q1"){start=`${y}-01-01`;end=`${y}-03-31`;}
                        if (q==="Q2"){start=`${y}-04-01`;end=`${y}-06-30`;}
                        if (q==="Q3"){start=`${y}-07-01`;end=`${y}-09-30`;}
                        if (q==="Q4"){start=`${y}-10-01`;end=`${y}-12-31`;}
                    } else if (quarterMode === 4) {
                        if (q==="Q1"){start=`${y}-01-01`;end=`${y}-04-30`;}
                        if (q==="Q2"){start=`${y}-05-01`;end=`${y}-08-31`;}
                        if (q==="Q3"){start=`${y}-09-01`;end=`${y}-12-31`;}
                    } else {
                        if (q==="Q1"){start=`${y}-01-01`;end=`${y}-06-30`;}
                        if (q==="Q2"){start=`${y}-07-01`;end=`${y}-12-31`;}
                    }
                    startDate = start; endDate = end;
                    rangeLabel.innerText = `${formatID(start)} – ${formatID(end)}`;
                    window.__laporanState.startDate = startDate;
                    window.__laporanState.endDate   = endDate;
                    updateStep2Summary();
                    if (hasRenderedOnce) reloadCurrentReport();
                };
            });
            document.getElementById("btnQuarterMode").onclick = () => {
                quarterMode = quarterMode === 3 ? 4 : quarterMode === 4 ? 6 : 3;
                renderQuarterButtons();
            };
        }
        renderQuarterButtons();
    
        function toYMD(d) { return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,"0")}-${String(d.getDate()).padStart(2,"0")}`; }
        function getStartOfWeek(date) {
            const d = new Date(date), day = d.getDay();
            return new Date(d.setDate(d.getDate() - day + (day === 0 ? -6 : 1)));
        }
    
        let fpInstance = null;
        function initFlatpickr() {
            if (fpInstance && typeof fpInstance.destroy === "function") fpInstance.destroy();
            fpInstance = flatpickr(datePicker, {
                mode: "range", dateFormat: "Y-m-d", allowInput: false, clickOpens: false,
                showMonths: window.innerWidth < 600 ? 1 : 2, defaultDate: [startDate, endDate],
                onChange: (dates) => {
                    if (dates.length === 2) {
                        const [a, b] = dates[0] <= dates[1] ? dates : [dates[1], dates[0]];
                        startDate = toYMD(a); endDate = toYMD(b);
                        rangeLabel.innerText = `${formatID(startDate)} – ${formatID(endDate)}`;
                        window.__laporanState.startDate = startDate;
                        window.__laporanState.endDate   = endDate;
                        updateStep2Summary();
                        if (hasRenderedOnce) renderPDF();
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

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
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
                    }
                }
            });
        }
<<<<<<< HEAD
<<<<<<< HEAD
        setTimeout(initFlatpickr, 50);
        btnRangeDate.addEventListener("click", () => {
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

        setTimeout(initFlatpickr, 50);

        btnRangeDate.addEventListener("click", function () {
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
            if (!fpInstance) initFlatpickr();
            try { fpInstance.setDate([startDate, endDate], false); } catch(e){}
            fpInstance.open();
        });
<<<<<<< HEAD
<<<<<<< HEAD
    
        document.querySelectorAll(".quick-range").forEach(btn => {
            btn.onclick = () => {
                const now = new Date(), range = btn.dataset.range;
                let start;
                if (range === "TDY") start = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                if (range === "WTD") start = getStartOfWeek(now);
                if (range === "MTD") start = new Date(now.getFullYear(), now.getMonth(), 1);
                if (range === "YTD") start = new Date(now.getFullYear(), 0, 1);
    
                startDate = toYMD(start); endDate = toYMD(now);
                rangeLabel.innerText = `${formatID(startDate)} – ${formatID(endDate)}`;
                window.__laporanState.startDate = startDate;
                window.__laporanState.endDate   = endDate;
                updateStep2Summary();
                
                document.querySelectorAll(".quick-range").forEach(b => b.classList.remove("active"));
                btn.classList.add("active");
                if (hasRenderedOnce) renderPDF();
            };
        });
    
    } else if (feature === 'PRODUCT') {
                // =========================================================================
                // 1. STATE & GLOBAL VARIABLES
                // =========================================================================
                window.currentModul        = 'product';
                window.currentProductMode  = 'gambar'; // 'gambar' atau 'dokumen'
                window.currentBrandFilter  = '';       // 'gcf', 'senses', 'project'
                window.allProductsCache    = [];
                window.lastPreviewSrc      = '';
                window.proxyPreviewSrc     = '';
                window.galleryItems        = [];
                window.currentGalleryIndex = 0;
                
                // Tetap gunakan route proxy asli Anda
                const apiBaseUrl = '/proxy/product-assets'; 
                const designThumbnailUrl   = '/proxy/design-thumbnails'; // TAMBAH INI
        
                // STATE KHUSUS EXTRA
                window.extraState = {
                    apiProxyUrl: '/proxy/extra-assets',
                    rootFolders: [],
                    activeFolder: '',
                    currentPath: '', // Menandakan posisi path saat ini (bisa di dalam sub-folder)
                    currentFolderSubFolders: [], // Menyimpan data sub-folder
                    activeMonthKey: '',
                    currentFolderFiles: [],
                    filteredFiles: [],
                    currentIndex: -1,
                    currentPage: 1,
                    ITEMS_PER_PAGE: 12,
                    currentPreviewUrl: '',
                    currentPreviewTitle: '',
                    availableMonths: [],
                    initialized: false 
                };
        
                const PRODUCT_DOWNLOAD_HTML = '<i class="bi bi-download"></i> <span class="mv4-btn-label d-none d-md-inline">Unduh</span>';
                const PRODUCT_SHARE_HTML    = '<i class="bi bi-share"></i> <span class="mv4-btn-label d-none d-md-inline">Bagikan</span>';
                const EXTRA_SHARE_HTML      = '<i class="bi bi-box-arrow-up"></i> <span class="mv4-btn-label d-none d-md-inline">Bagikan</span>';
        
                // =========================================================================
                // 2. INJECT CUSTOM CSS & DEPENDENCIES
                // =========================================================================
                if (!document.getElementById('product-custom-style')) {
                    if (typeof $.fn.select2 === 'undefined') {
                        document.head.insertAdjacentHTML('beforeend', `<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /><script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"><\/script>`);
                    }
        
                    document.head.insertAdjacentHTML('beforeend', `
                        <style id="product-custom-style">
                            :root { 
                                --bg:            #ffffff;
                                --surface:       #f8f9fa;
                                --surface-2:     #f1f3f5;
                                --border:        #e5e7eb;
                                --border-dark:   #d1d5db;
                                --text-main:     #1f2937;
                                --text-sub:      #4b5563;
                                --accent-blue:   #0d6efd;
                                --overlay-grad:  linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0) 60%);
                            }
        
                            /* Base Masterpiece UI */
                            .product-light-canvas {
                                background-color: var(--bg);
                                color: var(--text-main);
                                border-radius: 16px;
                                margin-top: 5px;
                                padding: 8px;
                                height: calc(100vh - 110px);
                                overflow: hidden;
                                box-shadow: 0 8px 30px rgba(0,0,0,0.15);
                                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                                display: flex;
                                flex-direction: column;
                            }
        
                            .tap-effect { transition: all 0.1s ease; cursor: pointer; -webkit-tap-highlight-color: transparent;}
                            .tap-effect:active { transform: scale(0.97); }
                            .fade-in { animation: fadeIn 0.2s ease-out; }
                            @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        
                            /* Navigasi Atas */
                            .top-nav-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; padding: 0 4px; }
                            .nav-group { display: flex; background: var(--surface-2); padding: 2px; border-radius: 8px; border: 1px solid var(--border); }
                            .nav-item { padding: 3px 12px; font-size: 10px; font-weight: 700; border-radius: 6px; cursor: pointer; transition: all 0.2s; color: var(--text-sub); text-transform: uppercase; border: none; background: transparent; }
                            .nav-item.active { background: var(--accent-blue); color: white; }
        
                            /* Filter Section */
                            .filter-section { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 8px 12px; }
                            .filter-grid { display: grid; grid-template-columns: 75px 175px 1fr 180px; gap: 8px; align-items: center; width: 100%; }
                            .filter-item { width: 100%; min-width: 0; }
                            .filter-item label { display: none !important; }
                            
                            /* Menambahkan Responsivitas untuk Tablet dan HP */
                            @media (max-width: 991px) {
                                /* Untuk Tablet / Layar Menengah: Buat jadi 2 Baris, 2 Kolom */
                                .filter-grid {
                                    grid-template-columns: repeat(2, 1fr);
                                }
                            }
                            
                            @media (max-width: 575px) {
                                /* Untuk HP / Layar Kecil: Buat jadi 1 Kolom (Menumpuk ke bawah) agar rapi */
                                .filter-grid {
                                    grid-template-columns: 1fr;
                                }
                            }
        
                            /* Select2 Overrides & FIX Dropdown Visibility */
                            .filter-grid .select2-container { width: 100% !important; flex: unset !important; min-width: unset !important; max-width: unset !important; }
                            .filter-grid .select2-container--default .select2-selection--single { height: 28px !important; border: 1px solid var(--border-dark) !important; border-radius: 5px !important; font-size: 11px !important; background: #fff !important; display: flex !important; align-items: center !important; }
                            .filter-grid .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 26px !important; padding-left: 8px !important; font-weight: 700 !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; display: block !important; color: #374151 !important; width: 100%;}
                            .filter-grid .select2-container--default .select2-selection--single .select2-selection__arrow { height: 26px !important; }
                            
                            /* FIX: Warna Dropdown List & Search Box Select2 */
                            .select2-dropdown { background-color: #ffffff !important; border: 1px solid var(--border-dark) !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border-radius: 6px !important; z-index: 9999; }
                            .select2-search__field { color: #1f2937 !important; background-color: #f9fafb !important; font-size: 11px !important; border: 1px solid var(--border) !important; border-radius: 4px !important; padding: 4px 8px !important; outline: none !important;}
                            .select2-search__field:focus { border-color: var(--accent-blue) !important; }
                            .select2-container--default .select2-results__option { white-space: normal !important; word-wrap: break-word !important; font-size: 11px; line-height: 1.4; padding: 6px 10px; color: #374151 !important; background-color: #ffffff; border-bottom: 1px solid #f3f4f6; }
                            .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: var(--accent-blue) !important; color: #ffffff !important; }
                            .select2-container--default .select2-results__option[aria-selected="true"] { background-color: #e5e7eb !important; color: #111827 !important; font-weight: bold; }
        
                            /* Canvas Container & Assets */
                            .canvas-container { flex: 1; overflow-y: auto; background: #fff; border-radius: 10px; border: 1px solid var(--border); padding: 8px; }
                            .canvas-container::-webkit-scrollbar { width: 5px; }
                            .canvas-container::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }
        
                            .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: var(--text-sub); text-align: center; }
                            .empty-state i { font-size: 2rem; opacity: 0.15; margin-bottom: 5px; }
        
                            .image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 8px; }
                            .asset-img { width: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 8px; background: #e2e8f0; }
                            .video-thumbnail { position: relative; overflow: hidden; border-radius: 8px; background: #1e293b; width: 100%; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; }
                            
                            .folder-section-header { background: #f8fafc; padding: 4px 8px; border-radius: 5px; margin-bottom: 6px; font-size: 10px; font-weight: 800; color: var(--text-main); border-left: 3px solid var(--accent-blue); display: flex; justify-content: space-between; }
        
                            .skeleton-loader { background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%); background-size: 200% 100%; animation: loadingSkeleton 1.5s infinite; }
                            @keyframes loadingSkeleton { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        
                            /* Modul Extra */
                            .extra-folder-nav-container { display: flex; gap: 8px; overflow-x: auto; scrollbar-width: none; flex-grow: 1; align-items: center; }
                            .extra-folder-nav-container::-webkit-scrollbar { display: none; }
                            .extra-folder-tab { padding: 4px 12px; border-radius: 30px; font-size: 10px; font-weight: 600; background: var(--surface-2); color: var(--text-sub); border: 1px solid var(--border); cursor: pointer; transition: all 0.2s ease; white-space: nowrap; }
                            .extra-folder-tab.active { background: var(--accent-blue); color: white; border-color: var(--accent-blue); }
                            .extra-canvas-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; }
                            .extra-canvas-item { position: relative; border-radius: 12px; overflow: hidden; aspect-ratio: 1/1; cursor: pointer; background: #e2e8f0; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
                            .extra-item-image { width: 100%; height: 100%; object-fit: cover; }
                            .extra-item-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 30px 12px 12px; background: var(--overlay-grad); color: white; display: flex; flex-direction: column; justify-content: flex-end; pointer-events: none;}
                            .extra-item-title { font-size: 11px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        
                            /* Document UI */
                            .folder-year { background: #f8fafc; border-radius: 6px; padding: 6px 14px; font-weight: 700; color: #334155; display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; font-size: 12px; border: 1px solid #e2e8f0; }
                            .doc-tree-wrapper { border-left: 1.5px dashed #cbd5e1; margin-left: 12px; padding-left: 16px; padding-top: 5px; }
                            .folder-month { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin: 8px 0; display: flex; align-items: center; gap: 5px; position: relative; }
                            .folder-month::before { content: ''; position: absolute; left: -16px; top: 50%; width: 12px; height: 1.5px; background-color: #cbd5e1; }
                            .doc-item { background: white; border: 1px solid #edf2f7; border-radius: 8px; transition: all 0.2s; width: 100%; margin-bottom: 6px; padding: 8px 12px; display: flex; align-items: center; cursor: pointer;}
                            .doc-item.latest-version { border-left: 4px solid #198754; box-shadow: 0 2px 5px rgba(0,0,0,0.04); }
        
                            /* Modals V4 Styles */
                            .modal-dark-backdrop { background-color: rgba(0,0,0,0.8); }
                            .mv4-topbar { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-bottom: 1px solid #f0f0f0; background: #fff; }
                            .mv4-caption { flex: 1; min-width: 0; }
                            .mv4-caption-name { font-size: 14px; font-weight: 700; color: #111; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
                            .mv4-caption-sub { font-size: 11px; color: #9ca3af; display: block; margin-top: 2px; }
                            .mv4-actions { display: flex; gap: 8px; flex-shrink: 0; }
                            .mv4-btn { height: 40px; min-width: 40px; padding: 0 14px; border-radius: 20px; border: 1px solid #e5e7eb; background: #f9fafb; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 12px; font-weight: 700; color: #374151; cursor: pointer; transition: 0.2s; }
                            .mv4-btn.mv4-share { background: #0d6efd; border-color: #0d6efd; color: #fff; }
                            .mv4-btn.mv4-close { background: #fef2f2; border-color: #fecaca; color: #dc2626; }
                            .mv4-btn.mv4-success { background: #16a34a; border-color: #16a34a; color: #fff; }
                            
                            .mv4-nav-row { display: flex; align-items: stretch; background: #0f172a; min-height: 300px; position: relative; }
                            .mv4-nav-btn { width: 50px; background: rgba(255,255,255,0.05); border: none; color: white; cursor: pointer; transition: 0.2s; z-index: 10; }
                            .mv4-nav-btn:hover { background: rgba(255,255,255,0.15); }
                            .mv4-media { flex: 1; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
                            .mv4-counter { position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.6); color: #fff; font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 20px; z-index: 10; pointer-events: none; }
                        </style>
                    `);
                }
        
                // =========================================================================
                // 3. RENDER UI UTAMA (INJECT HTML)
                // =========================================================================
                targetElement.innerHTML = `
                    <div class="product-light-canvas fade-in">
                        
                        {{-- TOP NAVIGATION --}}
                        <div class="top-nav-bar">
                            <div class="nav-group">
                                <button class="nav-item active" id="btnNavProduct" onclick="window.switchModul('product', this)">Product</button>
                                <button class="nav-item" id="btnNavExtra" onclick="window.switchModul('extra', this)">Extra</button>
                            </div>
                            <div class="nav-group" id="typeNavGroup">
                                <button class="nav-item active" id="btnModeGambar" onclick="window.switchTipeMode('gambar', this)">Gambar</button>
                                <button class="nav-item" id="btnModeDokumen" onclick="window.switchTipeMode('dokumen', this)">Dokumen</button>
                            </div>
                        </div>
        
                        {{-- FILTER SECTION (PRODUCT) --}}
                        <div class="filter-section fade-in" id="productFilterSection">
                            <div class="filter-grid">
                                <div class="filter-item">
                                    <select id="brandSelectGlobal" class="form-select">
                                        <option value="">Merek</option>
                                        <option value="gcf">GCF</option>
                                        <option value="senses">Senses</option>
                                        <option value="project">Project</option>
                                    </select>
                                </div>
                                <div class="filter-item">
                                    <select id="searchBrand" class="form-select" disabled><option value="">Brand</option></select>
                                </div>
                                <div class="filter-item">
                                    <select id="searchSearah" class="form-select" disabled><option value="">Searah</option></select>
                                </div>
                                <div class="filter-item">
                                    <select id="searchVariant" class="form-select" disabled><option value="">Product</option></select>
                                </div>
                            </div>
                        </div>
        
                        {{-- FILTER SECTION (EXTRA) --}}
                        <div class="filter-section fade-in d-none" id="extraFilterSection">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 120px; flex-shrink: 0;">
                                    <input type="text" id="extraMonthPicker" class="form-control form-control-sm text-center fw-bold" placeholder="Pilih Bulan" style="height: 26px; font-size: 10px; border-radius: 5px;">
                                </div>
                                <div class="d-flex gap-1 overflow-auto" id="extraFolderNavContainer" style="scrollbar-width: none;">
                                    <span class="text-muted small">Memuat menu...</span>
                                </div>
                            </div>
                        </div>
        
                        {{-- CANVAS CONTAINER --}}
                        <div class="canvas-container">
                            <div id="productCanvasArea">
                                <div class="empty-state"><i class="bi bi-search"></i><p class="fw-bold">Pilih Merek Terlebih Dahulu</p></div>
                            </div>
                            <div id="extraCanvasArea" class="d-none">
                                <div class="extra-canvas-grid" id="extraCanvasGrid"></div>
                                <div id="extraPaginationContainer" class="d-none mt-3 d-flex justify-content-center align-items-center gap-3"></div>
                            </div>
                        </div>
                    </div>
        
                    {{-- MODAL PREVIEW (PRODUCT) --}}
                    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true" style="z-index:1060;">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 bg-transparent shadow-none">
                                <div class="bg-white shadow-lg d-flex flex-column overflow-hidden fade-in" style="border-radius:18px;">
                                    <div class="mv4-topbar">
                                        <div class="mv4-caption">
                                            <span class="mv4-caption-name" id="previewTitle">Nama File</span>
                                            <span class="mv4-caption-sub" id="previewSubtitle">&nbsp;</span>
                                        </div>
                                        <div class="mv4-actions">
                                            <button class="mv4-btn tap-effect" id="btnProductDownload" onclick="window.processDownload(this)" title="Unduh">
                                                <i class="bi bi-download"></i> <span class="mv4-btn-label d-none d-md-inline">Unduh</span>
                                            </button>
                                            <button class="mv4-btn mv4-share tap-effect" id="btnProductShare" onclick="window.processShare(this)" title="Bagikan">
                                                <i class="bi bi-share"></i> <span class="mv4-btn-label d-none d-md-inline">Bagikan</span>
                                            </button>
                                            <button class="mv4-btn mv4-close tap-effect" data-bs-dismiss="modal" title="Tutup">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mv4-nav-row" id="previewMediaContainer">
                                        <button class="mv4-nav-btn" id="btnProductPrev" onclick="window.navigateGallery(-1)"><i class="bi bi-chevron-left"></i></button>
                                        <div class="mv4-media" id="previewMediaContent"></div>
                                        <button class="mv4-nav-btn" id="btnProductNext" onclick="window.navigateGallery(1)"><i class="bi bi-chevron-right"></i></button>
                                        <div class="mv4-counter" id="previewCounter" style="display:none;">1 / 1</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    {{-- MODAL PREVIEW (EXTRA) --}}
                    <div class="modal fade" id="extraPreviewModal" tabindex="-1" aria-hidden="true" style="z-index:1060;">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0 bg-transparent shadow-none">
                                <div class="bg-white shadow-lg d-flex flex-column overflow-hidden fade-in" style="border-radius:18px;">
                                    <div class="mv4-topbar">
                                        <div class="mv4-caption">
                                            <span class="mv4-caption-name" id="extraPreviewTitle">Nama File</span>
                                            <span class="mv4-caption-sub" id="extraPreviewSubtitle">&nbsp;</span>
                                        </div>
                                        <div class="mv4-actions">
                                            <button class="mv4-btn mv4-share tap-effect" id="btnExtraShare" onclick="window.extra_shareCurrentFile(this)" title="Bagikan">
                                                <i class="bi bi-box-arrow-up"></i> <span class="mv4-btn-label d-none d-md-inline">Bagikan</span>
                                            </button>
                                            <button class="mv4-btn mv4-close tap-effect" data-bs-dismiss="modal" title="Tutup">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mv4-nav-row">
                                        <button class="mv4-nav-btn" id="btnExtraPrevImg" onclick="window.extra_changePreview(-1)"><i class="bi bi-chevron-left"></i></button>
                                        <div class="mv4-media" id="extraMediaContent"></div>
                                        <button class="mv4-nav-btn" id="btnExtraNextImg" onclick="window.extra_changePreview(1)"><i class="bi bi-chevron-right"></i></button>
                                        <div class="mv4-counter" id="extraCounter">1 / 1</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
        
                // =========================================================================
                // 4. INISIALISASI AWAL (SELECT2 & CACHE)
                // =========================================================================
                setTimeout(() => {
                    // Inisialisasi semua Select2
                    $('#brandSelectGlobal, #searchBrand, #searchSearah, #searchVariant').select2({ width: '100%', dropdownAutoWidth: true });
        
                    $('#brandSelectGlobal').on('change', function() {
                        window.currentBrandFilter = $(this).val();
                        window.populateSelect('searchBrand',  [], 'Brand',    true);
                        window.populateSelect('searchSearah', [], 'Searah',   true);
                        window.populateSelect('searchVariant',[], 'Product',  true);
                        window.updateSearchDropdowns();
                        window.renderMainCanvas();
                    });
        
                    $('#searchBrand').on('change', function() {
                        window.populateSelect('searchSearah', [], 'Searah',  true);
                        window.populateSelect('searchVariant',[], 'Product', true);
                        window.updateSearchDropdowns();
                    });
        
                    $('#searchSearah').on('change', function() {
                        window.populateSelect('searchVariant',[], 'Product', true);
                        window.updateSearchDropdowns();
                    });
        
                    $('#searchVariant').on('change', function() {
                        if ($(this).val()) window.executeSearch();
                    });
        
                    window.loadInitialProducts();
        
                    // Handle video saat modal ditutup
                    $('#previewModal, #extraPreviewModal').on('hidden.bs.modal', function () {
                        const vid = this.querySelector('video');
                        if (vid) { vid.pause(); vid.src = ''; }
                    });
        
                }, 150);
        
                window.populateSelect = function(id, items, def, disabled) {
                    const $el = $('#' + id);
                    if (!$el.length) return;
                    const cur = $el.val();
                    $el.empty().append(new Option(def, '')).prop('disabled', !!disabled);
                    items.forEach(i => $el.append(new Option(i, i, i === cur, i === cur)));
                    if ($.fn.select2) $el.trigger('change.select2');
                };
        
                window.loadInitialProducts = async function() {
                    try {
                        const response = await fetch(`${apiBaseUrl}?limit=1000`);
                        const result = await response.json();
                        if (result.success && result.data) { window.allProductsCache = result.data; }
                    } catch (error) { console.error("Gagal memuat cache produk:", error); }
                };
        
                window.updateSearchDropdowns = function() {
                    const b = $('#searchBrand').val();
                    const s = $('#searchSearah').val();
                    let data = window.allProductsCache;
                    
                    if (window.currentBrandFilter) {
                        data = data.filter(i => i.merek && i.merek.toLowerCase() === window.currentBrandFilter.toLowerCase());
                    }
        
                    const brands = [...new Set(data.map(i => i.brand).filter(Boolean))].sort((a, b) => a.localeCompare(b));
                    window.populateSelect('searchBrand', brands, 'Brand', false);
        
                    let searahs = [];
                    if (b) { searahs = [...new Set(data.filter(i => i.brand === b).map(i => i.searah).filter(Boolean))].sort((a, b) => a.localeCompare(b)); }
                    window.populateSelect('searchSearah', searahs, 'Searah', !b);
        
                    let variants = [];
                    if (b && s) { variants = [...new Set(data.filter(i => i.brand === b && i.searah === s).map(i => i.product_name).filter(Boolean))].sort((a, b) => a.localeCompare(b)); }
                    window.populateSelect('searchVariant', variants, 'Product', !(b && s));
                };
        
                // =========================================================================
                // 5. LOGIKA NAVIGASI (UI SWITCHER)
                // =========================================================================
                window.switchModul = function(modulName, btn) {
                    window.currentModul = modulName;
                    document.querySelectorAll('.top-nav-bar .nav-group:first-child .nav-item').forEach(el => el.classList.remove('active'));
                    btn.classList.add('active');
        
                    const productFilter = document.getElementById('productFilterSection');
                    const extraFilter   = document.getElementById('extraFilterSection');
                    const typeNavGroup  = document.getElementById('typeNavGroup');
                    const productCanvas = document.getElementById('productCanvasArea');
                    const extraCanvas   = document.getElementById('extraCanvasArea');
        
                    if (modulName === 'extra') {
                        productFilter.classList.add('d-none');
                        extraFilter.classList.remove('d-none');
                        typeNavGroup.style.visibility = 'hidden';
                        productCanvas.classList.add('d-none');
                        extraCanvas.classList.remove('d-none');
        
                        if (!window.extraState.initialized) {
                            window.loadExtraDependencies(() => {
                                window.initExtraFlatpickr();
                                window.extra_loadRootMenu();
                            });
                            window.extraState.initialized = true;
                        } else {
                            window.extra_applyFiltersAndRender();
                        }
                    } else {
                        productFilter.classList.remove('d-none');
                        extraFilter.classList.add('d-none');
                        typeNavGroup.style.visibility = 'visible';
                        productCanvas.classList.remove('d-none');
                        extraCanvas.classList.add('d-none');
                        window.renderMainCanvas();
                    }
                };
        
                window.switchTipeMode = function(mode, btn) {
                    window.currentProductMode = mode;
                    document.querySelectorAll('#typeNavGroup .nav-item').forEach(el => el.classList.remove('active'));
                    btn.classList.add('active');
                    window.renderMainCanvas();
                };
        
                window.renderMainCanvas = function() {
                    const canvas = document.getElementById('productCanvasArea');
                    if (!window.currentBrandFilter) {
                        canvas.innerHTML = '<div class="empty-state"><i class="bi bi-search"></i><p class="fw-bold">Pilih Merek</p></div>';
                        return;
                    }
        
                    if (window.currentProductMode === 'dokumen') {
                        canvas.innerHTML = '<div class="empty-state"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
                        window.fetchDocumentsList(); 
                    } else {
                        if ($('#searchVariant').val()) {
                            window.executeSearch();
                        } else {
                            canvas.innerHTML = '<div class="empty-state"><i class="bi bi-arrow-up"></i><p class="fw-bold">Lengkapi filter</p></div>';
                        }
                    }
                };
        
                // =========================================================================
                // 6. LOGIKA PENCARIAN & DRIVE API (GAMBAR) PRODUCT
                // =========================================================================
                window.executeSearch = async function() {
                    const canvas  = document.getElementById('productCanvasArea');
                    const merek   = window.currentBrandFilter;
                    const brand   = $('#searchBrand').val();
                    const searah  = $('#searchSearah').val();
                    const variant = $('#searchVariant').val();
                
                    if (!merek || !variant) return;
                    canvas.innerHTML = '<div class="empty-state"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
                
                    try {
                        // ── Paralel: trans API + design thumbnail ──
                        const urlSearch = `${apiBaseUrl}?merek=${encodeURIComponent(merek)}&brand=${encodeURIComponent(brand)}&searah=${encodeURIComponent(searah)}&product_name=${encodeURIComponent(variant)}`;
                        const urlDesign = `${designThumbnailUrl}?merek=${encodeURIComponent(merek)}&brand=${encodeURIComponent(brand)}&searah=${encodeURIComponent(searah)}&product_name=${encodeURIComponent(variant)}`;
                
                        const [response, designResponse] = await Promise.all([
                            fetch(urlSearch),
                            fetch(urlDesign).catch(() => null)
                        ]);
                
                        const result       = await response.json();
                        const designResult = designResponse ? await designResponse.json().catch(() => null) : null;
                
                        if (!result.success || result.data.length === 0) {
                            canvas.innerHTML = `<div class="empty-state"><i class="bi bi-folder-x"></i><p class="fw-bold">Tidak ada aset</p></div>`;
                            return;
                        }
                
                        const item = result.data[0];
                
                        // ── Reset gallery ──
                        window.galleryItems        = [];
                        window.currentGalleryIndex = 0;
                
                        // ── STEP 1: Render Product Thumbnail dari design ──
                        const thumbData  = (designResult && designResult.success && Array.isArray(designResult.data)) ? designResult.data : [];
                        const thumbnails = [];
                        thumbData.forEach(p => {
                            if (p.image_url)    thumbnails.push({ ...p, _src: p.image_url,    _label: 'Reguler' });
                            if (p.image_hd_url) thumbnails.push({ ...p, _src: p.image_hd_url, _label: 'HD' });
                        });
                
                        let thumbItems = '';
                        if (thumbnails.length > 0) {
                            thumbnails.forEach(p => {
                                window.galleryItems.push({
                                    src: p._src, title: p.code + ' — ' + (p.name || '') + ' (' + p._label + ')',
                                    isVideo: false, origin: 'design'
                                });
                            });
                
                            thumbItems = thumbnails.map((p, i) => {
                                return `<div class="gallery-asset asset-img shadow-sm tap-effect position-relative skeleton-loader"
                                             onclick="window.openGallery(${i})" style="overflow:hidden;">
                                    <img src="${p._src}" class="w-100 h-100 position-relative" loading="lazy"
                                         style="object-fit:cover;z-index:2;opacity:0;transition:opacity 0.4s;pointer-events:none;"
                                         onload="this.style.opacity=1;this.parentElement.classList.remove('skeleton-loader');"
                                         onerror="this.src='https://placehold.co/300x300?text=No+Preview';this.style.opacity=1;this.parentElement.classList.remove('skeleton-loader');">
                                    <div style="position:absolute;bottom:0;left:0;right:0;padding:18px 6px 5px;background:linear-gradient(to top,rgba(0,0,0,0.72) 0%,transparent 100%);z-index:3;pointer-events:none;">
                                        <div style="font-size:9px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${p.code}</div>
                                        <div style="font-size:8px;color:rgba(255,255,255,0.75);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${p._label}</div>
                                    </div>
                                </div>`;
                            }).join('');
                        } else {
                            thumbItems = `
                                <div style="grid-column:1/-1;padding:20px;background:#f1f3f5;border:1px dashed #d1d5db;border-radius:10px;text-align:center;">
                                    <i class="bi bi-image text-secondary mb-2" style="font-size:1.5rem;"></i>
                                    <div class="text-muted fw-bold" style="font-size:11px;">Belum ada thumbnail</div>
                                </div>`;
                        }
                
                        let html = `<div class="mb-3">
                                        <div class="folder-section-header">
                                            <span>Product Thumbnail</span>
                                            <span>${thumbnails.length}</span>
                                        </div>
                                        <div class="image-grid">${thumbItems}</div>
                                    </div>`;
                
                        // ── STEP 2: Fetch folder Drive ──
                        canvas.innerHTML = `<div id="assets-${item.id}" class="fade-in">${html}<div class="empty-state py-3"><div class="spinner-border text-primary spinner-border-sm mb-2"></div><p class="small text-muted mb-0">Menarik aset Drive...</p></div></div>`;
                
                        window.fetchDriveImages(item);
                
                    } catch (error) {
                        canvas.innerHTML = '<div class="empty-state text-danger">Gagal terhubung ke server.</div>';
                    }
                };
        
                window.fetchDriveImages = async function(item) {
                    const assetContainer = document.getElementById('assets-' + item.id);
                    if (!assetContainer) return;
                
                    try {
                        let originalUrl = new URL(item.drive_list_url);
                        let pathParam   = originalUrl.searchParams.get('path');
                        const response  = await fetch('/proxy/drive-assets?path=' + pathParam);
                        const rootItems = await response.json();
                        if (rootItems.error) throw new Error("Gagal");
                        
                        // Daftar nama folder yang ingin disembunyikan
                        const hiddenFolders = ['02_LSFRAGRANCE_VISUAL', '03_CLEAN_VISUAL'];
                
                        const subFolders = rootItems.filter(f => f.type === 'folder' && !hiddenFolders.includes(f.name));
                
                        // Ambil existing thumbnail HTML dulu, jangan dihapus
                        const thumbSection = assetContainer.querySelector('.mb-3');
                        const thumbHtml    = thumbSection ? thumbSection.outerHTML : '';
                
                        if (subFolders.length > 0) {
                            let folderHtml = '';
                            for (const folder of subFolders) {
                                const files     = await window.fetchFilesFromFolder(folder.path);
                                let cleanTitle  = folder.name.replace(/^\d+_/, '').replace(/_/g, ' ');
                
                                files.forEach(file => {
                                    const isVideo = /\.(mp4|webm|ogg|mov)$/i.test(file.name);
                                    window.galleryItems.push({ src: file.url, title: file.name, isVideo, origin: 'product' });
                                });
                
                                folderHtml += window.buildFolderSectionHTML(cleanTitle, files);
                            }
                            assetContainer.innerHTML = thumbHtml + folderHtml;
                        } else {
                            assetContainer.innerHTML = thumbHtml + '<div class="empty-state"><p class="small text-muted">Folder produk belum diatur di Drive pusat</p></div>';
                        }
                    } catch (error) {
                        const assetContainer2 = document.getElementById('assets-' + item.id);
                        if (assetContainer2) assetContainer2.innerHTML += '<div class="empty-state text-danger">Gagal membaca folder Drive.</div>';
                    }
                };
        
                window.fetchFilesFromFolder = async function(encodedPath) {
                    try {
                        const response = await fetch('/proxy/drive-assets?path=' + encodedPath);
                        const data = await response.json();
                        return data.error ? [] : data.filter(f => f.type === 'file');
                    } catch (e) { return []; }
                };
        
                window.getThumbnailUrl = function(url) {
                    const match = url.match(/id=([a-zA-Z0-9_-]+)/) || url.match(/\/d\/([a-zA-Z0-9_-]+)/);
                    if (match && match[1] && url.includes('drive.google.com')) { return `https://drive.google.com/thumbnail?id=${match[1]}&sz=w300`; }
                    return url; 
                };
        
                window.buildFolderSectionHTML = function(title, files) {
                    if (!files || files.length === 0) {
                        return `<div class="mb-3"><div class="folder-section-header"><span>${title}</span><span>0</span></div><div style="padding: 12px 8px; text-align: center; color: #94a3b8; font-size: 10px; font-weight: 600; background: #f8fafc; border-radius: 6px; border: 1px dashed #e2e8f0;">Belum ada isi</div></div>`;
                    }
                    let itemsHtml = '';
                    files.forEach(file => {
                        const isVideo = /\.(mp4|webm|ogg|mov)$/i.test(file.name);
                        const idx = window.galleryItems.findIndex(g => g.src === file.url && g.title === file.name);
        
                        if (isVideo) {
                            itemsHtml += `<div class="gallery-asset video-thumbnail tap-effect shadow-sm" data-gidx="${idx}" onclick="window.openGallery(${idx})"><i class="bi bi-play-circle-fill text-white opacity-75" style="font-size: 1.5rem;"></i></div>`;
                        } else {
                            let thumbUrl = window.getThumbnailUrl(file.url);
                            itemsHtml += `<div class="gallery-asset asset-img shadow-sm tap-effect position-relative skeleton-loader" data-gidx="${idx}" onclick="window.openGallery(${idx})" style="overflow: hidden;"><img src="${thumbUrl}" class="w-100 h-100 position-relative" loading="lazy" style="object-fit: cover; z-index: 2; opacity: 0; transition: opacity 0.4s ease-in-out;" onload="this.style.opacity=1; this.parentElement.classList.remove('skeleton-loader');" onerror="this.src='https://placehold.co/300x300?text=No+Preview';this.style.opacity=1;this.parentElement.classList.remove('skeleton-loader');"></div>`;
                        }
                    });
                    return `<div class="mb-3"><div class="folder-section-header"><span>${title}</span><span>${files.length}</span></div><div class="image-grid">${itemsHtml}</div></div>`;
                };
        
                // =========================================================================
                // 7. GALERI PREVIEW & SHARE (PRODUCT)
                // =========================================================================
                window.openGallery = function(idx) {
                    if (typeof idx !== 'number') return;
                    window.currentGalleryIndex = idx;
                    window.renderCurrentGalleryItem();
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('previewModal')).show();
                };
                
                window.navigateGallery = function(direction) {
                    if (!window.galleryItems || window.galleryItems.length === 0) return;
                    window.currentGalleryIndex += direction;
                    if (window.currentGalleryIndex < 0) window.currentGalleryIndex = window.galleryItems.length - 1;
                    else if (window.currentGalleryIndex >= window.galleryItems.length) window.currentGalleryIndex = 0;
                    window.renderCurrentGalleryItem();
                };
                
                window.renderCurrentGalleryItem = function() {
                    if (!window.galleryItems || window.galleryItems.length === 0) return;
                    const item = window.galleryItems[window.currentGalleryIndex];
                    if (!item) return;
                    const { src: originalSrc, title, isVideo, isPdf } = item;
                    const total = window.galleryItems.length;
                    const cur   = window.currentGalleryIndex + 1;
                
                    let displaySrc = originalSrc;
                    if (isVideo) {
                        displaySrc = originalSrc;
                    } else if (item.origin === 'design') {
                        displaySrc = originalSrc; // URL publik langsung
                    } else {
                        displaySrc = '/proxy-image?url=' + encodeURIComponent(originalSrc);
                    }
                
                    window.lastPreviewSrc  = originalSrc;
                    window.proxyPreviewSrc = displaySrc;
                
                    document.getElementById('previewTitle').innerText = title.replace(/\.[^/.]+$/, '').replace(/_/g, ' ');
                    const ext = (title.match(/\.([^.]+)$/) || ['', ''])[1].toUpperCase();
                    document.getElementById('previewSubtitle').innerText = (ext ? ext + ' · ' : '') + cur + ' dari ' + total;
                
                    const counter = document.getElementById('previewCounter');
                    if (counter) {
                        counter.innerText = cur + ' / ' + total;
                        counter.style.display = total > 1 ? 'block' : 'none';
                    }
                
                    document.getElementById('btnProductPrev').disabled = (cur <= 1);
                    document.getElementById('btnProductNext').disabled = (cur >= total);
                
                    const contentDiv = document.getElementById('previewMediaContent');
                    if (isVideo) {
                        contentDiv.innerHTML = `<video src="${displaySrc}" controls autoplay playsinline style="width:100%;max-height:75vh;object-fit:contain;background:#000;"></video>`;
                    } else if (isPdf) {
                        const pdfSrc = `${displaySrc}#view=FitH`;
                        contentDiv.innerHTML = `
                            <div id="loadingPreview" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:1;">
                                <div class="spinner-border text-secondary mb-2" role="status"></div><span class="text-muted fw-bold" style="font-size:11px;">Memuat...</span>
                            </div>
                            <iframe src="${pdfSrc}" onload="var lp=document.getElementById('loadingPreview');if(lp)lp.style.display='none';" style="width:100%;height:75vh;border:none;border-radius:4px;background:#fff;position:relative;z-index:2;"></iframe>`;
                    } else {
                        contentDiv.innerHTML = `
                            <div id="loadingPreview" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;z-index:1;">
                                <div class="spinner-border text-secondary mb-2" role="status"></div><span class="text-muted fw-bold" style="font-size:11px;">Memuat...</span>
                            </div>
                            <img src="${displaySrc}" crossorigin="anonymous" style="max-width:100%;max-height:75vh;object-fit:contain;border-radius:4px;z-index:2;position:relative;opacity:0;transition:opacity 0.3s;" onload="var lp=document.getElementById('loadingPreview');if(lp)lp.style.display='none';this.style.opacity=1;">`;
                    }
                    if (counter) contentDiv.appendChild(counter);
                };
                
                window.processDownload = async function(btn) {
                    const isVideo = document.querySelector('#previewMediaContent video') !== null;
                    const proxyUrl = window.proxyPreviewSrc;   
                    const title = document.getElementById('previewTitle').innerText || 'Aset_Produk';
                    const origHtml = PRODUCT_DOWNLOAD_HTML;
                
                    try {
                        if (btn) { btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; btn.disabled = true; }
                        const response = await fetch(proxyUrl);
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        const extension = blob.type.split('/')[1] || (isVideo ? 'mp4' : 'jpg');
                        a.download = `${title.replace(/\s+/g, '_')}-${Date.now()}.${extension}`;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                        showSuccessBtn(btn, origHtml, "Sukses");
                    } catch (error) { resetShareBtn(btn, origHtml); alert('Gagal mengunduh file.'); }
                };
                
                window.processShare = async function(btn) {
                    const imgElement = document.querySelector('#previewMediaContent img');
                    const imageTitle = document.getElementById('previewTitle').innerText || 'Aset Produk';
                    const originalUrl = window.lastPreviewSrc; 
                    const proxyUrl = window.proxyPreviewSrc;   
                    const origHtml = PRODUCT_SHARE_HTML;
                
                    try {
                        if (btn) { btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; btn.disabled = true; }
                        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                
                        if (isMobile && navigator.share && imgElement) {
                            const response = await fetch(proxyUrl);
                            const blob = await response.blob();
                            if (!blob || blob.type.includes('text/html')) throw new Error("Gagal mengambil file gambar.");
                
                            const extension = blob.type.split('/')[1] || 'jpg';
                            const file = new File([blob], `produk-${Date.now()}.${extension}`, { type: blob.type });
                            const shareData = { files: [file], title: imageTitle, text: imageTitle };
                
                            if (navigator.canShare && navigator.canShare(shareData)) {
                                await navigator.share(shareData);
                                resetShareBtn(btn, origHtml);
                            } else {
                                window.copyImageToClipboard(imgElement, originalUrl, btn, origHtml);
                            }
                        } else {
                            if (imgElement) { window.copyImageToClipboard(imgElement, originalUrl, btn, origHtml); }
                            else { window.executeCopyText(originalUrl, btn, origHtml); }
                        }
                    } catch (error) {
                        if (error.name === 'AbortError' || error.message.toLowerCase().includes('cancel')) { resetShareBtn(btn, origHtml); }
                        else { window.executeCopyText(originalUrl, btn, origHtml); }
                    }
                };
                
                window.copyImageToClipboard = async function(imgElement, fallbackUrl, btn, originalHtml) {
                    try {
                        const canvas = document.createElement('canvas');
                        canvas.width = imgElement.naturalWidth;
                        canvas.height = imgElement.naturalHeight;
                        canvas.getContext('2d').drawImage(imgElement, 0, 0);
                        canvas.toBlob(async (blob) => {
                            if (blob) {
                                try {
                                    await navigator.clipboard.write([new ClipboardItem({ "image/png": blob })]);
                                    showSuccessBtn(btn, originalHtml, "Disalin!");
                                } catch (e) { window.executeCopyText(fallbackUrl, btn, originalHtml); }
                            } else { window.executeCopyText(fallbackUrl, btn, originalHtml); }
                        }, 'image/png');
                    } catch (error) { window.executeCopyText(fallbackUrl, btn, originalHtml); }
                };
                
                window.executeCopyText = function(text, btn, originalHtml) {
                    navigator.clipboard.writeText(text).then(() => { showSuccessBtn(btn, originalHtml, "Disalin!"); }).catch(err => { resetShareBtn(btn, originalHtml); });
                };
                
                function showSuccessBtn(btn, originalHtml, message) {
                    if (!btn) return;
                    btn.innerHTML = `<i class="bi bi-check2"></i> <span class="d-none d-md-inline">${message}</span>`;
                    btn.classList.add('mv4-success');
                    btn.disabled = false;
                    setTimeout(function() { btn.innerHTML = originalHtml; btn.classList.remove('mv4-success'); }, 2500);
                }
                
                function resetShareBtn(btn, originalHtml) {
                    if (!btn) return;
                    btn.innerHTML = originalHtml;
                    btn.disabled  = false;
                    btn.classList.remove('mv4-loading', 'mv4-success');
                }
        
                // =========================================================================
                // 8. DOKUMEN PRICE LIST / PRODUCT LIST
                // =========================================================================
                window.fetchDocumentsList = async function() {
                    const container = document.getElementById('productCanvasArea');
                    const brand = window.currentBrandFilter;
                    const folderMap = { 'gcf': '04_PL_GCF', 'senses': '05_PL_SENSES', 'project': '06_PL_PROJECT' };
                    const targetFolder = folderMap[brand.toLowerCase()];
                    if (!targetFolder) { container.innerHTML = '<div class="empty-state">Folder belum dikonfigurasi.</div>'; return; }
        
                    try {
                        const encodedPath = btoa(targetFolder);
                        const driveApiUrl = `/proxy/drive-assets?path=${encodedPath}`; 
                        const response = await fetch(driveApiUrl);
                        const result = await response.json();
        
                        if (result.error || !Array.isArray(result) || result.length === 0) {
                            container.innerHTML = `<div class="empty-state"><i class="bi bi-folder-x"></i><p class="fw-bold">Belum ada file.</p></div>`;
                            return;
                        }
        
                        let html = '';
                        const documents = { 'Price List': [], 'Product List': [] };
                        result.forEach(file => {
                            if (file.type !== 'file') return;
                            const nameLower = file.name.toLowerCase();
                            let docCategory = null;
                            if (nameLower.includes('price_list')) docCategory = 'Price List';
                            else if (nameLower.includes('product_list')) docCategory = 'Product List';
        
                            if (docCategory) documents[docCategory].push(file);
                        });
        
                        for (const [category, files] of Object.entries(documents)) {
                            if (files.length === 0) continue;
                            files.sort((a, b) => b.name.localeCompare(a.name));
                            
                            html += `<div class="mb-3"><div class="folder-section-header">${category}</div>`;
                            files.forEach((f, idx) => {
                                const isLatest = idx === 0;
                                html += `<div class="doc-item ${isLatest ? 'latest-version' : ''}" onclick="window.openDoc('${f.url}', '${f.name}')">
                                            <i class="bi bi-file-pdf text-danger me-2" style="font-size:20px;"></i>
                                            <span class="text-truncate" style="font-size:12px; font-weight: 600;">${f.name}</span>
                                            ${isLatest ? '<span class="badge bg-success ms-auto" style="font-size:9px;">TERBARU</span>' : '<span class="badge bg-secondary ms-auto" style="font-size:9px;">LAMA</span>'}
                                         </div>`;
                            });
                            html += `</div>`;
                        }
        
                        container.innerHTML = html || '<div class="empty-state">Tidak ada dokumen sesuai standar penamaan.</div>';
                    } catch (error) { container.innerHTML = '<div class="empty-state text-danger">Gagal memuat dokumen.</div>'; }
                };
        
                window.openDoc = function(url, name) {
                    window.galleryItems = [{ src: url, title: name, isVideo: false, isPdf: true }];
                    window.currentGalleryIndex = 0;
                    window.renderCurrentGalleryItem();
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('previewModal')).show();
                };
        
                // =========================================================================
                // 9. LOGIKA MODUL EXTRA 
                // =========================================================================
                window.loadExtraDependencies = function(callback) {
                    if (typeof flatpickr !== 'undefined' && typeof monthSelectPlugin !== 'undefined') { callback(); return; }
                    document.head.insertAdjacentHTML('beforeend', `<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">`);
                    const loadScript = (src) => new Promise((resolve) => { const script = document.createElement('script'); script.src = src; script.onload = resolve; document.head.appendChild(script); });
                    loadScript("https://cdn.jsdelivr.net/npm/flatpickr").then(() => loadScript("https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js")).then(() => loadScript("https://npmcdn.com/flatpickr/dist/l10n/id.js")).then(callback);
                };
                
                window.extraIndonesianLocale = { name: "id", weekdays: { shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"], longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"] }, months: { shorthand: ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agt","Sep","Okt","Nov","Des"], longhand: ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"] } };
        
                window.initExtraFlatpickr = function() {
                    const now = new Date();
                    window.extraState.activeMonthKey = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
                    window.extraMonthPickerInstance = flatpickr("#extraMonthPicker", {
                        locale: window.extraIndonesianLocale, disableMobile: true, altInput: true, altFormat: "F Y", dateFormat: "Y-m",
                        plugins: [new monthSelectPlugin({ shorthand: false, dateFormat: "Y-m", altFormat: "F Y" })],
                        maxDate: "today", defaultDate: window.extraState.activeMonthKey,
                        onChange: function(selectedDates, dateStr) {
                            window.extraState.activeMonthKey = dateStr; window.extraState.currentPage = 1; window.extra_applyFiltersAndRender();
                        }
                    });
                };
        
                window.extra_loadRootMenu = async function() {
                    try {
                        const response = await fetch(`${window.extraState.apiProxyUrl}?path=`);
                        const data = await response.json();
                        if(data.error) throw new Error(data.error);
                        window.extraState.rootFolders = data.filter(item => item.type === 'folder');
                        window.extra_renderFolderNav();
                        if(window.extraState.rootFolders.length > 0) {
                            window.extraState.activeFolder = '';
                            window.extra_selectFolder(window.extraState.rootFolders[0].name);
                        } else { document.getElementById('extraFolderNavContainer').innerHTML = '<span class="text-muted small py-1">Belum ada folder</span>'; }
                    } catch (error) { document.getElementById('extraFolderNavContainer').innerHTML = '<span class="text-danger small py-1">Gagal memuat menu</span>'; }
                };
        
                window.extra_renderFolderNav = function() {
                    const container = document.getElementById('extraFolderNavContainer');
                    let html = '';
                    window.extraState.rootFolders.forEach(folder => {
                        const cleanName = folder.name.replace(/^\d+_/, '').replace(/_/g, ' ');
                        const isActive = folder.name === window.extraState.activeFolder ? 'active' : '';
                        html += `<div class="extra-folder-tab ${isActive}" onclick="window.extra_selectFolder('${folder.name}')">${cleanName}</div>`;
                    });
                    container.innerHTML = html;
                };
        
                window.extra_selectFolder = function(folderName) {
                    window.extraState.activeFolder = folderName;
                    window.extra_renderFolderNav(); 
                    // Mulai dari root folder
                    window.extra_openPath(folderName);
                };
                
                // FUNGSI BARU: Untuk masuk ke path/sub-folder tertentu
                window.extra_openPath = async function(path) {
                    window.extraState.currentPath = path;
                    
                    const grid = document.getElementById('extraCanvasGrid');
                    const pagination = document.getElementById('extraPaginationContainer');
                    if (grid) grid.innerHTML = '<div style="grid-column: 1 / -1;" class="empty-state py-4"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
                    if (pagination) pagination.classList.add('d-none');
                    
                    try {
                        const encodedPath = btoa(path);
                        const response = await fetch(`${window.extraState.apiProxyUrl}?path=${encodedPath}`);
                        const data = await response.json();
                        if(data.error) throw new Error(data.error);

                        // Pisahkan mana yang folder dan mana yang file
                        window.extraState.currentFolderSubFolders = data.filter(item => item.type === 'folder');
                        window.extraState.currentFolderFiles = data.filter(item => item.type === 'file');
                        
                        // Default bulan saat ini jika belum terisi
                        const now = new Date();
                        if (!window.extraState.activeMonthKey) {
                            window.extraState.activeMonthKey = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
                            if (window.extraMonthPickerInstance) window.extraMonthPickerInstance.setDate(window.extraState.activeMonthKey, false);
                        }

                        window.extraState.currentPage = 1;
                        window.extra_applyFiltersAndRender();
                    } catch (error) {
                        if (grid) grid.innerHTML = `<div class="empty-state text-danger w-100 py-4" style="grid-column: 1 / -1;"><i class="bi bi-x-circle mb-2"></i><br>Gagal memuat isi folder.</div>`;
                    }
                };
        
                window.extra_applyFiltersAndRender = function() {
                    // Filter BULAN hanya berlaku untuk FILE, bukan Folder.
                    if (!window.extraState.activeMonthKey) {
                        window.extraState.filteredFiles = window.extraState.currentFolderFiles;
                    } else {
                        window.extraState.filteredFiles = window.extraState.currentFolderFiles.filter(file => {
                            const match = file.name.match(/^(\d{6})_/);
                            if (match) return (match[1].substring(0, 4) + '-' + match[1].substring(4, 6)) === window.extraState.activeMonthKey;
                            if (!file.timestamp) return false;
                            const date = new Date(file.timestamp * 1000);
                            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}` === window.extraState.activeMonthKey;
                        });
                    }
                    window.extra_renderGrid();
                };
        
                window.extra_renderGrid = function() {
                    const grid = document.getElementById('extraCanvasGrid');
                    const pagination = document.getElementById('extraPaginationContainer');
                    
                    // Deteksi apakah kita sedang di dalam sub-folder
                    const isSubFolder = window.extraState.currentPath !== window.extraState.activeFolder;
                    
                    if (window.extraState.filteredFiles.length === 0 && window.extraState.currentFolderSubFolders.length === 0 && !isSubFolder) {
                        grid.innerHTML = `<div class="empty-state py-5 w-100" style="grid-column: 1 / -1;"><i class="bi bi-folder-x mb-2"></i><span class="small">Tidak ada folder atau aset pada bulan ini.</span></div>`;
                        pagination.classList.add('d-none'); return;
                    }

                    let html = '';

                    // 1. RENDER TOMBOL KEMBALI (Jika berada di dalam sub-folder)
                    if (isSubFolder) {
                        const parentPath = window.extraState.currentPath.substring(0, window.extraState.currentPath.lastIndexOf('/')) || window.extraState.activeFolder;
                        html += `<div class="extra-canvas-item fade-in bg-light" style="border: 2px dashed #cbd5e1; cursor: pointer;" onclick="window.extra_openPath('${parentPath}')">
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-secondary">
                                        <i class="bi bi-arrow-90deg-up" style="font-size: 2rem;"></i>
                                        <span style="font-size: 11px; margin-top: 8px; font-weight: 700;">Kembali</span>
                                    </div>
                                 </div>`;
                    }

                    // 2. RENDER SUB-FOLDERS
                    window.extraState.currentFolderSubFolders.forEach(folder => {
                        const nextPath = `${window.extraState.currentPath}/${folder.name}`;
                        const cleanTitle = folder.name.replace(/^\d+_/, '').replace(/_/g, ' ');
                        html += `<div class="extra-canvas-item fade-in" style="background:#f1f5f9; border:1px solid #e2e8f0; cursor: pointer;" onclick="window.extra_openPath('${nextPath}')">
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center">
                                        <i class="bi bi-folder-fill" style="font-size: 3rem; color:#94a3b8;"></i>
                                    </div>
                                    <div class="extra-item-overlay" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%); padding-top: 30px;">
                                        <span class="extra-item-title text-center w-100 d-block">${cleanTitle}</span>
                                    </div>
                                 </div>`;
                    });

                    // 3. RENDER FILES (Dengan Pagination)
                    const startIndex = (window.extraState.currentPage - 1) * window.extraState.ITEMS_PER_PAGE;
                    const endIndex = startIndex + window.extraState.ITEMS_PER_PAGE;
                    const filesToRender = window.extraState.filteredFiles.slice(startIndex, endIndex);

                    filesToRender.forEach((file, relativeIdx) => {
                        const cleanTitle = file.name.replace(/\.[^/.]+$/, ""); 
                        const absoluteIdx = startIndex + relativeIdx;
                        const isVideo = file.name.toLowerCase().match(/\.(mp4|webm|ogg|mov)$/);
                
                        if (isVideo) {
                            html += `<div class="extra-canvas-item fade-in bg-dark" onclick="window.extra_openPreview(${absoluteIdx})">
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-play-circle-fill text-white opacity-75" style="font-size: 2.5rem;"></i>
                                        </div>
                                        <div class="extra-item-overlay"><span class="extra-item-title">${cleanTitle}</span></div>
                                     </div>`;
                        } else {
                            html += `<div class="extra-canvas-item fade-in" onclick="window.extra_openPreview(${absoluteIdx})">
                                        <img src="/proxy/extra-assets/stream?path=${new URL(file.url).searchParams.get('path') || ''}" class="extra-item-image" loading="lazy" onerror="this.src='https://placehold.co/300x300?text=No+Preview';">
                                        <div class="extra-item-overlay"><span class="extra-item-title">${cleanTitle}</span></div>
                                     </div>`;
                        }
                    });

                    grid.innerHTML = html;
                    
                    // Jika di dalam sub-folder tapi tidak ada isi
                    if (html === '' && isSubFolder) {
                        grid.innerHTML += `<div class="empty-state py-5 w-100" style="grid-column: 1 / -1;"><i class="bi bi-folder2-open mb-2 text-muted" style="font-size:2rem;"></i><span class="small text-muted">Folder ini kosong</span></div>`;
                    }

                    window.extra_renderPagination();
                };
        
                window.extra_renderPagination = function() {
                    const pagination = document.getElementById('extraPaginationContainer');
                    const totalPages = Math.ceil(window.extraState.filteredFiles.length / window.extraState.ITEMS_PER_PAGE);
                    if (totalPages <= 1) { pagination.classList.add('d-none'); return; }
        
                    pagination.classList.remove('d-none');
                    pagination.innerHTML = `
                        <button class="mv4-btn tap-effect" ${window.extraState.currentPage === 1 ? 'disabled' : ''} onclick="window.extra_changePage(-1)"><i class="bi bi-chevron-left"></i></button>
                        <span class="fw-bold text-muted small" style="font-size:11px;">Hal ${window.extraState.currentPage} dari ${totalPages}</span>
                        <button class="mv4-btn tap-effect" ${window.extraState.currentPage === totalPages ? 'disabled' : ''} onclick="window.extra_changePage(1)"><i class="bi bi-chevron-right"></i></button>
                    `;
                };
        
                window.extra_changePage = function(direction) {
                    window.extraState.currentPage += direction;
                    window.extra_renderGrid();
                };
        
                window.extra_openPreview = function(index) {
                    window.extraState.currentIndex = index;
                    window.extra_updateModalUI();
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('extraPreviewModal')).show();
                };
        
                window.extra_changePreview = function(direction) {
                    const newIndex = window.extraState.currentIndex + direction;
                    if (newIndex >= 0 && newIndex < window.extraState.filteredFiles.length) {
                        window.extraState.currentIndex = newIndex;
                        window.extra_updateModalUI();
                    }
                };
        
                window.extra_updateModalUI = function() {
                    const file = window.extraState.filteredFiles[window.extraState.currentIndex];
                    if (!file) return;
                 
                    window.extraState.currentPreviewUrl   = file.url;
                    window.extraState.currentPreviewTitle = file.name;
                 
                    const isVideo = /\.(mp4|webm|ogg|mov)$/i.test(file.name);
                    const cur     = window.extraState.currentIndex + 1;
                    const total   = window.extraState.filteredFiles.length;
                 
                    const titleEl = document.getElementById('extraPreviewTitle');
                    if (titleEl) titleEl.innerText = file.name.replace(/\.[^/.]+$/, '');
                 
                    const extEl  = document.getElementById('extraPreviewSubtitle');
                    const extStr = (file.name.match(/\.([^.]+)$/) || ['', ''])[1].toUpperCase();
                    if (extEl) extEl.innerText = (extStr ? extStr + ' · ' : '') + cur + ' dari ' + total;
                 
                    const ctrEl = document.getElementById('extraCounter');
                    if (ctrEl) ctrEl.innerText = cur + ' / ' + total;
                 
                    document.getElementById('btnExtraPrevImg').disabled = (cur <= 1);
                    document.getElementById('btnExtraNextImg').disabled = (cur >= total);
                 
                    const mediaDiv = document.getElementById('extraMediaContent');
                    const pathParam = new URL(file.url).searchParams.get('path');
                    const proxiedSrc = `/proxy/extra-assets/stream?path=${pathParam}`;
                 
                    if (isVideo) {
                        const oldVid = mediaDiv.querySelector('video');
                        if (oldVid) { oldVid.pause(); oldVid.remove(); }
                        const oldImg = mediaDiv.querySelector('img');
                        if (oldImg) oldImg.style.display = 'none';
                 
                        const vid = document.createElement('video');
                        vid.src      = proxiedSrc;
                        vid.controls = true;
                        vid.autoplay = true;
                        vid.setAttribute('playsinline', '');
                        vid.style.cssText = 'width:100%;max-height:75vh;object-fit:contain;background:#000;z-index:2;position:relative;';
                        mediaDiv.insertBefore(vid, mediaDiv.firstChild);
                    } else {
                        const oldVid = mediaDiv.querySelector('video');
                        if (oldVid) { oldVid.pause(); oldVid.remove(); }
                 
                        let img = mediaDiv.querySelector('img#extraPreviewImage');
                        if (!img) {
                            img = document.createElement('img');
                            img.id        = 'extraPreviewImage';
                            img.className = 'img-fluid fade-in';
                            img.style.cssText = 'max-width:100%;max-height:75vh;object-fit:contain;border-radius:4px;z-index:2;position:relative;transition:opacity 0.25s;';
                            mediaDiv.insertBefore(img, mediaDiv.firstChild);
                        }
                        img.style.display = '';
                        img.style.opacity = '0';
                        img.src = proxiedSrc;
                        img.onload = function() { img.style.opacity = '1'; };
                    }
                 
                    if (ctrEl && !mediaDiv.contains(ctrEl)) mediaDiv.appendChild(ctrEl);
                };
        
                window.extra_shareCurrentFile = async function(btn) {
                    if (!window.extraState.currentPreviewUrl) return;
                
                    const originalUrl = window.extraState.currentPreviewUrl;
                    const title       = window.extraState.currentPreviewTitle || 'Aset Extra';
                    const isVideo     = /\.(mp4|webm|ogg|mov)$/i.test(title);
                    const isMobile    = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
                
                    const pathParam = new URL(originalUrl).searchParams.get('path');
                    const proxyUrl  = `/proxy/extra-assets/stream?path=${pathParam}`;
                    const origHtml  = EXTRA_SHARE_HTML;
                
                    if (btn && btn.classList.contains('mv4-loading')) return;
                
                    const setBusy = () => { if (!btn) return; btn.classList.add('mv4-loading'); btn.disabled = true; };
                    const setReady = () => { if (!btn) return; btn.classList.remove('mv4-loading', 'mv4-success'); btn.innerHTML = origHtml; btn.disabled  = false; };
                    const setSuccess = () => { if (!btn) return; btn.classList.remove('mv4-loading'); btn.classList.add('mv4-success'); btn.innerHTML = '<i class="bi bi-check2"></i><span class="mv4-btn-label d-none d-md-inline">Terkirim</span>'; btn.disabled  = false; setTimeout(setReady, 2500); };
                
                    setBusy();
                
                    try {
                        const resp = await fetch(proxyUrl);
                        if (!resp.ok) throw new Error('Proxy gagal: ' + resp.status);
                
                        const blob = await resp.blob();
                        if (!blob || blob.size === 0 || blob.type.includes('text/html')) { throw new Error('Blob tidak valid: ' + blob.type); }
                
                        let mimeType = blob.type;
                        let ext = mimeType.split('/')[1] || (isVideo ? 'mp4' : 'jpg');
                        ext = ext.replace('quicktime', 'mov').replace('x-matroska', 'mkv');
                        if (isVideo && !mimeType.startsWith('video/')) { mimeType = 'video/mp4'; ext = 'mp4'; }
                
                        const cleanTitle = title.replace(/\.[^/.]+$/, '');
                
                        if (isMobile && navigator.share) {
                            const file = new File([blob], `${cleanTitle}-${Date.now()}.${ext}`, { type: mimeType });
                            const shareData = { files: [file] };
                
                            if (navigator.canShare && navigator.canShare(shareData)) { await navigator.share(shareData); } 
                            else { await navigator.share({ title: title, url: originalUrl }); }
                            setSuccess();
                
                        } else {
                            const a = document.createElement('a');
                            a.href     = URL.createObjectURL(blob);
                            a.download = `${cleanTitle}-${Date.now()}.${ext}`;
                            a.style.display = 'none';
                            document.body.appendChild(a);
                            a.click();
                            URL.revokeObjectURL(a.href);
                            document.body.removeChild(a);
                            setSuccess();
                        }
                
                    } catch (err) {
                        if (err.name === 'AbortError') { setReady(); } else {
                            navigator.clipboard.writeText(originalUrl).then(() => {
                                if (btn) { btn.classList.remove('mv4-loading'); btn.classList.add('mv4-success'); btn.innerHTML = '<i class="bi bi-check2"></i><span class="mv4-btn-label d-none d-md-inline">Link disalin</span>'; btn.disabled  = false; setTimeout(setReady, 2500); }
                            }).catch(() => { setReady(); alert('Gagal: ' + err.message); });
                        }
                    }
                };
        
                return;

    } else if (feature === 'SETTING PIC') {
        // ═════════════════════════════════════════════════════════
        // MENU: SETTING PIC (Antrian MIS)
        // Load partial dari MisQueueController::partial() via AJAX.
        // Data yang tampil = prospek dari modul Customer yang sudah
        // di-"Naikkan ke MIS" dan menunggu diisi AM/ASM/PIC/SPG.
        // ═════════════════════════════════════════════════════════
        targetElement.innerHTML = `
            <div class="text-center text-white py-4">
                <div class="spinner-border text-warning" role="status"></div>
                <p class="mt-2 mb-0" style="font-size:0.8rem;">Memuat antrian Setting PIC...</p>
            </div>
        `;

        fetch("{{ route('report.doctor.mis_queue.partial') }}")
            .then(function(res) {
                if (!res.ok) throw new Error('Gagal memuat antrian (HTTP ' + res.status + ')');
                return res.text();
            })
            .then(function(html) {
                // Gunakan jQuery untuk menyisipkan HTML sekaligus mengeksekusi script di dalamnya
                $(targetElement).html(html);
            })
            .catch(function(err) {
                targetElement.innerHTML = `
                    <div class="text-center text-danger py-4" style="font-size:0.8rem;">
                        <i class="bi bi-exclamation-triangle"></i> Gagal memuat antrian: ${err.message}
                    </div>
                `;
            });

        return;

=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

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
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    } else {
        setTimeout(() => {
            targetElement.innerHTML = `
                <div class="p-3 text-start">
                    
                </div>
            `;
        }, 800);
    }
}
<<<<<<< HEAD
<<<<<<< HEAD

</script>
=======
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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
<<<<<<< HEAD
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
@endpush