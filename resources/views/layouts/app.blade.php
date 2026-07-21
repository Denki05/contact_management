<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MIS (Marketing Information System)')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<<<<<<< HEAD
<<<<<<< HEAD
    <!--<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css"/>
=======
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    <style>
    /* Global Dark Mode Base */
    body {
        /* Tetapkan background gelap global */
        background-color: #1e2227; 
        color: #fff;
<<<<<<< HEAD
<<<<<<< HEAD
        font-size: 12px;
=======
        font-size: 14px;
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
        font-size: 14px;
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        /* Penting: Pastikan tidak ada margin/padding default yang mengganggu tampilan full-width */
        margin: 0;
        padding: 0;
    }
    
    /* MODIFIKASI: Gunakan container-fluid di index, jadi hapus modifikasi padding di sini.
       Biarkan container standar jika ingin ada batas di desktop. 
       Untuk index Anda yang menggunakan container-fluid, ini tidak masalah.
    */
    .card {
        border-radius: 0;
        background-color: #111; 
        border: none;
        margin-bottom: 15px;
    }
    
    .card-body {
        padding: 12px 16px;
    }
    
    /* Sisa Gaya Tetap */
    .task-time {
        font-size: 0.8rem;
        color: #aaa;
    }
    .task-status {
        font-size: 0.75rem;
        color: #666;
    }
    .btn-icon {
        background: none;
        border: none;
        color: #fff;
    }
    .btn-icon:hover {
        color: #ffc107;
    }
    
    .header-bar {
        position: sticky;
        top: 0;
        z-index: 1000;
        background-color: #000;
        padding: 10px 15px;
    }

    /* ---------------------------------------------------------------------- */
    /* MEDIA QUERIES UNTUK RESPONSIVITAS */
    /* ---------------------------------------------------------------------- */

    /* Tablet (md breakpoint Bootstrap: >= 768px) */
    @media (min-width: 768px) {
        body {
            font-size: 15px;
        }
        /* Di layar besar, jika Anda menggunakan container standar, border-radius ini berguna */
        .card {
            border-radius: 12px;
        }
    }

    /* Laptop/Desktop (lg breakpoint Bootstrap: >= 992px) */
    @media (min-width: 992px) {
        body {
<<<<<<< HEAD
<<<<<<< HEAD
            font-size: 12px;
=======
            font-size: 16px;
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
            font-size: 16px;
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        }
    }
    </style>
</head>
<body>
    {{-- Karena index Anda menggunakan container-fluid, kita biarkan saja container di sini,
         atau kita ubah menjadi div tanpa class agar index Anda bisa mengontrol container-fluid sendiri. --}}
    <div>
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<<<<<<< HEAD
<<<<<<< HEAD
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
      pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    {{-- Menggunakan @stack untuk memuat script dan CSS dari @push di index --}}
    @stack('scripts') 
</body>
</html>