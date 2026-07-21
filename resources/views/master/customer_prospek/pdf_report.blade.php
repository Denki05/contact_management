<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Laporan Customer' }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; margin:0; padding:20px; }

        /* Header */
        .header { text-align:center; margin-bottom:20px; border-bottom:2px solid #000; padding-bottom:10px; }
        .header h1 { font-size:14pt; margin:0; }
        .header p { font-size:10pt; margin:5px 0 0; }

        /* Zona */
        .group-zona {
            font-size:11pt;
            font-weight:bold;
            margin-top:15px;
            border-bottom:1px solid #000;
            padding-bottom:3px;
        }

        /* Provinsi */
        .group-prov {
            font-size:10pt;
            font-weight:bold;
            margin-top:15px;
            padding:6px 12px;
            background-color: #F0F0F0;
            border-radius: 4px;
        }

        .group-kota {
            font-size:9.5pt;
            font-weight:bold;
            margin-top:6px;
            margin-left:8px;
            color:#444;
        }

        /* Table */
        table.customer-table {
            border-collapse: collapse;
            width: 90%;
            margin-left: 20px;
            margin-top: 3px;
            font-size: 9pt;
        }

        table.customer-table th, table.customer-table td {
            padding: 3px 5px;
            vertical-align: top;
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

        .col-name { width: 35%; }
        .col-pengajuan { width: 12%; }
        .col-category { width: 38%; }
        .col-pic { width: 5%; }
        .col-officer { width: 12%; }
        .col-labels { width: 2%; }

        /* Warna baris berdasarkan label/status */
        .row-e { background-color: #d4edda; } /* hijau muda */
        .row-p { background-color: #d1ecf1; } /* biru langit muda */

        .no-data {
            margin-left: 60px;
            color: #999;
            font-style: italic;
            font-size: 9pt;
        }
    </style>
</head>
<body>
    @forelse($groupedCustomers as $zone => $provGroups)
        <div class="group-zona">{{ $zoneLabels[$zone] ?? strtoupper($zone) }}</div>

        @foreach($provGroups as $prov => $kotaGroups)
            <div class="group-prov">{{ strtoupper($prov) }}</div>

            @foreach($kotaGroups as $kota => $customers)
                <div class="group-kota">{{ strtoupper($kota) }}</div>

                @if($customers->count() > 0)
                    <table class="customer-table">
                        <thead>
                            <tr>
                                <th class="col-labels">#</th>
                                <th class="col-pic">PIC</th>
                                <th class="col-category">Mapping</th>
                                <th class="col-name">Customer</th>
                                <th class="col-pengajuan">Pengajuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                               
                                
                                @php
                                    $label = strtoupper($customer->labels ?? $customer->status ?? '');
                                    $rowClass = $label === 'E' ? 'row-e' : '';
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="col-labels">{{ $label ?: '-' }}</td>
                                    <td class="col-pic">{{ $customer->pic ?? '-' }}</td>
                                    <td class="col-category">{{ $customer->category_name ?? '-' }}</td>
                                    <td class="col-name">{{ $customer->name ?? '-' }}</td>
                                    <td class="col-pengajuan">{{ $customer->pengajuan_text ?? '-' }}</td>
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