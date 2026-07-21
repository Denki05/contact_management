@extends('layouts.app')

<style>
/* ====== FullCalendar Custom Fit & Day Header Full Name ====== */
/* Mengurangi margin bawah untuk #calendar dan memastikan font umum tetap kecil */
#calendar {
    max-width: 950px; /* Lebar sedikit ditingkatkan */
    margin: 0 auto 10px auto; /* Margin bawah dikurangi */
    font-size: 0.8rem; /* Font dasar kalender sedikit dikecilkan */
}

.fc-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px; /* Margin toolbar dikurangi */
}
/* UKURAN JUDUL KALENDER DIKECILKAN LAGI */
.fc-toolbar-title {
    font-size: 0.85rem; /* Lebih kecil dari 0.9rem */
    font-weight: 600;
    color: #222;
}
/* UKURAN TOMBOL NAVIGASI DIKECILKAN LAGI */
.fc-prev-button, .fc-next-button {
    background-color: #0d6efd !important;
    border: none !important;
    color: white !important;
    padding: 1px 5px !important; /* Padding sangat minimal */
    font-size: 0.7rem !important; /* Font sangat kecil */
}
.fc-prev-button:hover, .fc-next-button:hover {
    background-color: #0b5ed7 !important;
}

/* Sel dan teks kalender dibuat lebih padat */
.fc-daygrid-day {
    padding: 1px !important; /* Padding dikurangi */
    cursor: pointer; 
    line-height: 1.2; /* Ketinggian baris dikurangi untuk kerapihan */
}
.fc-daygrid-day-number {
    font-size: 0.75rem; /* Ukuran tanggal dikecilkan */
    padding: 1px 2px;
}

/* Mengurangi padding pada day header */
.fc-col-header-cell-cushion {
    padding: 3px 0;
    font-size: 0.8rem;
    text-transform: capitalize !important;
}

/* Kartu task dalam modal */
.card-task {
    border-left: 4px solid #0d6efd;
    background-color: #f8f9fa;
    margin-bottom: 5px; /* Margin antar task dikurangi */
    padding: 6px 8px; /* Padding card dikurangi */
    border-radius: 6px;
}
.card-task p {
    margin: 0;
    font-size: 0.8rem; /* Font task dikecilkan */
}

/* Penyesuaian untuk Tombol Navigasi Tanggal pada Modal (Position Fixed) */
.modal-nav-button {
    /* UBAH DARI fixed KE absolute */
    position: absolute; /* Ganti fixed menjadi absolute */
    top: 50%;
    transform: translateY(-50%);
    /* Hapus z-index 1051 karena sekarang di dalam modal (z-index modal ~1050) */
    z-index: 1055; /* Tetap beri z-index tinggi agar di atas modal-content */
    background: rgba(0, 0, 0, 0.65);
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    opacity: 0.9;
    transition: opacity 0.2s;
    border-radius: 50%;
}

.modal-nav-button:hover {
    opacity: 1;
    background: rgba(0, 0, 0, 0.8);
}

#modal-prev-date {
    /* UBAH left dari 20px ke jarak yang lebih kecil */
    left: 250px; /* Jarak negatif membuat tombol berada di luar 'modal-dialog' */
}

#modal-next-date {
    /* UBAH right dari 20px ke jarak yang lebih kecil */
    right: 250px; /* Jarak negatif membuat tombol berada di luar 'modal-dialog' */
}

/* Sembunyikan tanggal dari bulan sebelumnya/selanjutnya */
.fc-day-other .fc-daygrid-day-number {
    visibility: hidden;
}

.fc-day-other {
    background-color: #f7f7f7 !important; 
}

/* REVISI AKHIR: MENGURANGI TINGGI ROW EVENT BAR */
/* Membuat event bar (Agenda) lebih tipis */
.fc-event {
    padding: 0 2px !important;
    margin-top: 1px !important;
    margin-bottom: 1px !important;
    font-size: 0.75rem !important;
    line-height: 1.2 !important;
    height: 16px; /* Tinggi bar event dikunci */
}
</style>

@section('content')
<div class="container-fluid px-3">
    <div id="calendar"></div>
</div>

{{-- Modal Detail Agenda --}}
<div class="modal fade" id="agendaModal" tabindex="-1" aria-labelledby="agendaModalLabel" aria-hidden="true">
    {{-- Tombol Navigasi --}}
    <button id="modal-prev-date" class="modal-nav-button" aria-label="Previous Day" style="display:none;"><i class="fas fa-chevron-left"></i></button>
    <button id="modal-next-date" class="modal-nav-button" aria-label="Next Day" style="display:none;"><i class="fas fa-chevron-right"></i></button>

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title" id="agendaModalLabel">Detail Agenda: <span id="current-modal-date"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="agenda-content-detail">
                    <div id="agenda-tasks"></div>
                </div>
                
                {{-- Pesan jika tidak ada agenda --}}
                <div id="no-agenda-message" class="text-center py-4 d-none">
                    <p class="lead text-muted">Tidak ada agenda pada tanggal ini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const AGENDA_DATA_URL = '{{ route("report.doctor.agenda.data") }}';
const OFFICER_PARAM = '{{ $officer }}';

/**
 * Format tanggal ke format Indonesia lengkap
 */
function formatIndonesianDate(date) {
    return date.toLocaleDateString('id-ID', { 
        weekday: 'long', 
        day: '2-digit', 
        month: 'long', 
        year: 'numeric' 
    });
}

/**
 * Format Date ke YYYY-MM-DD lokal
 */
function formatDateToISO(date) {
    const d = new Date(date);
    return d.getFullYear() + '-' +
        String(d.getMonth() + 1).padStart(2,'0') + '-' +
        String(d.getDate()).padStart(2,'0');
}

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    let currentModalDate = null;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        themeSystem: 'bootstrap5',
        locale: 'id',
        firstDay: 1,
        showNonCurrentDates: false,
        headerToolbar: {
            left: 'prev',
            center: 'title',
            right: 'next'
        },
        dayHeaders: true,
        dayHeaderFormat: { weekday: 'long' },
        dayCellDidMount: function(info) {
            if (!info.el.classList.contains('fc-day-other')) {
                const displayDate = formatIndonesianDate(info.date);
                info.el.setAttribute('title', `Klik untuk melihat agenda ${displayDate}`);
            }
        },
        events: {
            url: AGENDA_DATA_URL,
            extraParams: { officer: OFFICER_PARAM }
        },
        dateClick: function(info) {
            if (info.dayEl.classList.contains('fc-day-other')) return;
            currentModalDate = new Date(info.date);
            showAgendaModal(currentModalDate, calendar);
        },
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            currentModalDate = new Date(info.event.start);
            showAgendaModal(currentModalDate, calendar);
        }
    });

    calendar.render();

    // -----------------------------
    // Fungsi utama untuk modal
    // -----------------------------
    function showAgendaModal(dateObject, calendarInstance) {
        const formattedDate = formatDateToISO(dateObject);
        const displayDate = formatIndonesianDate(dateObject);

        const events = calendarInstance.getEvents().filter(event => {
            if (!event.start) return false;
            const ev = event.start;
            const evDate = ev.getFullYear() + '-' +
                String(ev.getMonth()+1).padStart(2,'0') + '-' +
                String(ev.getDate()).padStart(2,'0');
            return evDate === formattedDate;
        });

        const taskContainer = document.getElementById('agenda-tasks');
        taskContainer.innerHTML = '';

        if (events.length > 0) {
            let taskNumber = 1;
            const shownPics = new Set();

            events.forEach(event => {
                const props = event.extendedProps;
                const pic = props.pic || '-';

                // Tampilkan judul PIC hanya sekali
                if (!shownPics.has(pic)) {
                    const titleEl = document.createElement('h5');
                    titleEl.innerText = `Agenda (PIC: ${pic})`;
                    titleEl.classList.add('mt-2','mb-1');
                    taskContainer.appendChild(titleEl);
                    shownPics.add(pic);
                }

                const tasks = props.keterangan_task;
                if (tasks && Array.isArray(tasks) && tasks.length > 0) {
                    tasks.forEach(task => {
                        const card = document.createElement('div');
                        card.classList.add('card-task');

                        // Warna berdasarkan status
                        let bgColor = '#ffffff', textColor = '#000', borderColor = '#0d6efd';
                        if (task.status == '0') { bgColor='#f8d7da'; textColor='#842029'; borderColor='#842029'; }
                        else if (task.status == '2') { bgColor='#d1e7dd'; textColor='#0f5132'; borderColor='#0f5132'; }

                        card.style.backgroundColor = bgColor;
                        card.style.color = textColor;
                        card.style.borderLeftColor = borderColor;

                        card.innerHTML = `<p><strong>${taskNumber}.</strong> ${task.keterangan}</p>`;
                        taskContainer.appendChild(card);
                        taskNumber++;
                    });
                } else {
                    // fallback jika tidak ada task
                    const card = document.createElement('div');
                    card.classList.add('card-task');
                    card.innerHTML = `<p><strong>${taskNumber}.</strong> ${props.keterangan || 'Tidak ada keterangan'}</p>`;
                    taskContainer.appendChild(card);
                    taskNumber++;
                }
            });

            document.getElementById('agenda-content-detail').classList.remove('d-none');
            document.getElementById('no-agenda-message').classList.add('d-none');

        } else {
            document.getElementById('agenda-content-detail').classList.add('d-none');
            document.getElementById('no-agenda-message').classList.remove('d-none');
        }

        // Tampilkan modal
        const modalElement = document.getElementById('agendaModal');
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
        modalInstance.show();
    }

    // -----------------------------
    // Navigasi tanggal prev/next
    // -----------------------------
    const modalEl = document.getElementById('agendaModal');
    const prevBtn = document.getElementById('modal-prev-date');
    const nextBtn = document.getElementById('modal-next-date');

    modalEl.addEventListener('show.bs.modal', function() {
        prevBtn.style.display = 'block';
        nextBtn.style.display = 'block';
    });

    modalEl.addEventListener('hide.bs.modal', function() {
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
        currentModalDate = null;
    });

    prevBtn.addEventListener('click', function() {
        if (currentModalDate) {
            const newDate = new Date(currentModalDate);
            newDate.setDate(newDate.getDate() - 1);
            currentModalDate = newDate;
            showAgendaModal(newDate, calendar);
        }
    });

    nextBtn.addEventListener('click', function() {
        if (currentModalDate) {
            const newDate = new Date(currentModalDate);
            newDate.setDate(newDate.getDate() + 1);
            currentModalDate = newDate;
            showAgendaModal(newDate, calendar);
        }
    });
});
</script>
@endsection