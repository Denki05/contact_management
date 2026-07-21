<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>List Market Report</title>
    <style>
<<<<<<< HEAD
        body { 
            font-family: DejaVu Sans, Arial, sans-serif; 
            font-size: 9pt; 
            margin: 20px; 
            color: #000;
        }

        /* === HEADER === */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 { 
            font-size: 13pt; 
            margin: 0;
            font-weight: bold;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 9pt;
        }

        /* === ZONA === */
        .group-zona {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 18px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            text-transform: uppercase;
        }

        /* === PROVINSI === */
        .group-prov {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #F0F0F0;
            border-radius: 4px;
        }

        /* === KOTA === */
        .group-kota {
            font-size: 9.5pt;
            font-weight: bold;
            margin-top: 6px;
            margin-left: 10px;
            color: #333;
        }

        /* === TABEL === */
        table.customer-table {
            border-collapse: collapse;
            width: 92%;
            margin-left: 25px;
            margin-top: 3px;
            font-size: 8.8pt;
            table-layout: fixed;
        }

        table.customer-table th, 
        table.customer-table td {
            padding: 3px 5px;
            vertical-align: top;
=======
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8pt;
            margin: 12px 10px;
            color: #000;
        }

        /* === GROUP HEADERS === */
        .group-zona {
            font-size: 10pt;
            font-weight: bold;
            background: #f2f2f2;
            border-top: 1px solid #999;
            border-bottom: 1px solid #999;
            padding: 4px 6px;
            margin-top: 8px;
            text-transform: uppercase;
        }

        .group-prov {
            font-size: 9pt;
            font-weight: bold;
            margin-top: 8px;
            padding-left: 5px;
            color: #111;
        }

        .group-kota {
            font-size: 8.5pt;
            font-weight: bold;
            margin-top: 4px;
            padding-left: 15px;
            color: #444;
        }

        /* --- PERUBAHAN UTAMA DI SINI: group-status --- */
        .group-status {
            font-size: 8pt;
            font-weight: bold;
            margin-top: 3px;
            
            /* HILANGKAN padding-left untuk indentasi (Kita akan ganti dengan margin) */
            padding-left: 5px; /* Tetapkan padding internal yang kecil */
            padding-right: 5px;
            
            padding-top: 2px;
            padding-bottom: 2px;
            color: white; /* Teks Putih */
            
            /* PENTING: Gunakan margin-left 25px untuk INDENTASI agar sejajar dengan tabel */
            margin-left: 25px;
            
            /* KUNCI SOLUSI: display: inline-block untuk membuat lebar hanya selebar konten */
            display: inline-block;
            
            /*border-radius: 3px; */
            text-shadow: 0 0 1px #00000030;
        }
        /* ----------------------------------------------------- */

        /* Warna latar belakang Status */
        .status-existing {
            background-color: #f44336; /* Merah */
        }
        
        .status-prospek {
            background-color: #4CAF50; /* Hijau */
        }

        .status-unknown {
             background-color: #ccc; 
             color: #333; 
        }


        /* === TABLE === */
        table.customer-table {
            width: 96%; 
            /* PERUBAHAN DI SINI: Hapus margin-left agar ikut padding-left dari group-status (25px) */
            /* KARENA ANDA INGIN SEJAJAR DENGAN STATUS, MARGIN-LEFT DIHAPUS DULU */
            /* MARGIN-LEFT ASLI ANDA ADALAH 25px */
            margin-left: 25px; 
            margin-top: 2px;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8pt;
        }
        
        table.customer-table th,
        table.customer-table td {
            border-bottom: 0.5px solid #ddd;
            padding: 2px 3px;
            vertical-align: top;
            text-align: left;
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
            word-wrap: break-word;
        }

        table.customer-table th {
<<<<<<< HEAD
            text-align: left;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        table.customer-table td {
            border-bottom: 0.5px solid #ddd;
=======
            border-bottom: 1px solid #000;
            font-weight: bold;
            background-color: #fafafa;
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        }

        table.customer-table tr:last-child td {
            border-bottom: none;
        }

<<<<<<< HEAD
        .col-labels   { width: 4%;  text-align: center; }
        .col-pic       { width: 18%; }
        .col-mapping   { width: 28%; }
        .col-customer  { width: 32%; }
        .col-pengajuan { width: 18%; }

        /* === Warna Baris Berdasarkan Label === */
        .row-e { background-color: #d4edda; } /* Hijau muda */
        /* Hilangkan warna biru untuk P */
        .row-p { background-color: transparent; }

        /* === Teks Tidak Ada Data === */
        .no-data {
            margin-left: 60px;
            color: #999;
            font-style: italic;
            font-size: 9pt;
        }
=======
        /* Kolom proporsional */
        .col-customer  { width: 38%; }
        .col-pengajuan { width: 12%; }
        .col-mapping   { width: 26%; }
        .col-pic       { width: 12%; }
        .col-officer   { width: 12%; }

        table.customer-table tr:nth-child(even) td {
            background: #f9f9f9;
        }

        .no-data {
            margin-left: 25px; 
            color: #999;
            font-style: italic;
            font-size: 8pt;
        }

        /*.page-break { page-break-after: always; }*/
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    </style>
</head>
<body>

<<<<<<< HEAD
<div class="header">
    <h1>Data Customer {{ strtoupper($officerName ?? '-') }} per {{ $tanggalCetak }}</h1>
    <p>
        {{ strtoupper($zona ?: 'SEMUA ZONA') }} 
        @if($provinsi) • {{ strtoupper($provinsi) }} @endif 
        @if($kota) • {{ strtoupper($kota) }} @endif
    </p>
</div>

@php
    // Grouping zona → provinsi → kota
=======
@php
    // Manual grouping sesuai hierarki
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    $grouped = [];
    foreach ($data as $row) {
        $zona = $row['zona'] ?? 'ZONA LAIN';
        $prov = $row['provinsi'] ?? 'TIDAK ADA PROVINSI';
        $kota = $row['kota'] ?? 'TIDAK ADA KOTA';
<<<<<<< HEAD
        $grouped[$zona][$prov][$kota][] = $row;
=======
        // Simpan status dalam huruf kapital untuk key
        $status = strtoupper($row['status_customer'] ?? 'Unknown'); 

        $grouped[$zona][$prov][$kota][$status][] = $row;
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    }
@endphp

@forelse($grouped as $zona => $provGroups)
    <div class="group-zona">{{ $zoneLabels[$zona] ?? strtoupper($zona) }}</div>

    @foreach($provGroups as $prov => $kotaGroups)
        <div class="group-prov">{{ strtoupper($prov) }}</div>

<<<<<<< HEAD
        @foreach($kotaGroups as $kota => $customers)
            <div class="group-kota">{{ strtoupper($kota) }}</div>

            @if(count($customers) > 0)
                <table class="customer-table">
                    <thead>
                        <tr>
                            <th class="col-labels">#</th>
                            <th class="col-mapping">Mapping</th>
                            <th class="col-customer">Customer</th>
                            <th class="col-pengajuan">Pengajuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            @php
                                $label = strtoupper($customer['labels'] ?? '');
                                $rowClass = $label === 'E' ? 'row-e' : '';
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td class="col-labels">{{ $label }}</td>
                                <td class="col-mapping">{{ $customer['mapping'] ?? '-' }}</td>
                                <td class="col-customer">{{ $customer['customer'] ?? '-' }}</td>
                                <td class="col-pengajuan">{{ $customer['pengajuan'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Tidak ada customer pada kota ini.</div>
            @endif
        @endforeach
    @endforeach
@empty
    <p class="no-data" style="text-align:center; margin-top:50px;">Tidak ada data Customer yang ditemukan.</p>
=======
        @foreach($kotaGroups as $kota => $statusGroups)
            <div class="group-kota">{{ strtoupper($kota) }}</div>

            @foreach($statusGroups as $status => $customers)
                {{-- Normalisasi status menjadi nama kelas CSS yang valid --}}
                @php
                    $statusClass = strtolower(str_replace(' ', '-', $status)); 
                @endphp

                <div class="group-status status-{{ $statusClass }}">
                    {{ $status }} 
                </div>

                @if(count($customers) > 0)
                    <table class="customer-table">
                        <thead>
                            <tr>
                                <th class="col-customer">Customer</th>
                                <th class="col-pengajuan">Pengajuan</th>
                                <th class="col-mapping">Mapping</th>
                                <th class="col-pic">PIC</th>
                                <th class="col-officer">Officer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $c)
                                <tr>
                                    <td class="col-customer">{{ $c['customer'] ?? '-' }}</td>
                                    <td class="col-pengajuan">{{ $c['pengajuan'] ?? '-' }}</td>
                                    <td class="col-mapping">{{ $c['mapping'] ?? '-' }}</td>
                                    <td class="col-pic">{{ $c['pic'] ?? '-' }}</td>
                                    <td class="col-officer">{{ $c['officer'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-data">Tidak ada customer untuk status ini.</div>
                @endif
            @endforeach
        @endforeach
    @endforeach

    @empty
    <p class="no-data" style="text-align:center; margin-top:50px; margin-left: 0;">
        Tidak ada data Customer ditemukan.
    </p>
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
@endforelse

</body>
</html>