<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>List Market Report</title>
    <style>
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
            word-wrap: break-word;
        }

        table.customer-table th {
            text-align: left;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        table.customer-table td {
            border-bottom: 0.5px solid #ddd;
        }

        table.customer-table tr:last-child td {
            border-bottom: none;
        }

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
    </style>
</head>
<body>

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
    $grouped = [];
    foreach ($data as $row) {
        $zona = $row['zona'] ?? 'ZONA LAIN';
        $prov = $row['provinsi'] ?? 'TIDAK ADA PROVINSI';
        $kota = $row['kota'] ?? 'TIDAK ADA KOTA';
        $grouped[$zona][$prov][$kota][] = $row;
    }
@endphp

@forelse($grouped as $zona => $provGroups)
    <div class="group-zona">{{ $zoneLabels[$zona] ?? strtoupper($zona) }}</div>

    @foreach($provGroups as $prov => $kotaGroups)
        <div class="group-prov">{{ strtoupper($prov) }}</div>

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
@endforelse

</body>
</html>