<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>List Market Report</title>
    <style>
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
            word-wrap: break-word;
        }

        table.customer-table th {
            border-bottom: 1px solid #000;
            font-weight: bold;
            background-color: #fafafa;
        }

        table.customer-table tr:last-child td {
            border-bottom: none;
        }

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
    </style>
</head>
<body>

@php
    // Manual grouping sesuai hierarki
    $grouped = [];
    foreach ($data as $row) {
        $zona = $row['zona'] ?? 'ZONA LAIN';
        $prov = $row['provinsi'] ?? 'TIDAK ADA PROVINSI';
        $kota = $row['kota'] ?? 'TIDAK ADA KOTA';
        // Simpan status dalam huruf kapital untuk key
        $status = strtoupper($row['status_customer'] ?? 'Unknown'); 

        $grouped[$zona][$prov][$kota][$status][] = $row;
    }
@endphp

@forelse($grouped as $zona => $provGroups)
    <div class="group-zona">{{ $zoneLabels[$zona] ?? strtoupper($zona) }}</div>

    @foreach($provGroups as $prov => $kotaGroups)
        <div class="group-prov">{{ strtoupper($prov) }}</div>

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
@endforelse

</body>
</html>