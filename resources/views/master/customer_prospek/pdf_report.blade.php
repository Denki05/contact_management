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

<<<<<<< HEAD
        /* Zona */
=======
        /* Group Level Styles */
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        .group-zona {
            font-size:11pt;
            font-weight:bold;
            margin-top:15px;
            border-bottom:1px solid #000;
            padding-bottom:3px;
        }

<<<<<<< HEAD
        /* Provinsi */
=======
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        .group-prov {
            font-size:10pt;
            font-weight:bold;
            margin-top:15px;
<<<<<<< HEAD
            padding:6px 12px;
            background-color: #F0F0F0;
            border-radius: 4px;
=======
            padding-bottom:3px;
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        }

        .group-kota {
            font-size:9.5pt;
            font-weight:bold;
            margin-top:6px;
            margin-left:8px;
            color:#444;
        }

<<<<<<< HEAD
        /* Table */
=======
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

        /* Detail Table */
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
        table.customer-table {
            border-collapse: collapse;
            width: 90%;
            margin-left: 20px;
            margin-top: 3px;
            font-size: 9pt;
        }

<<<<<<< HEAD
        table.customer-table th, table.customer-table td {
=======
        table.customer-table th, 
        table.customer-table td {
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
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

<<<<<<< HEAD
        .col-name { width: 35%; }
        .col-pengajuan { width: 12%; }
        .col-category { width: 38%; }
        .col-pic { width: 5%; }
        .col-officer { width: 12%; }
        .col-labels { width: 2%; }

        /* Warna baris berdasarkan label/status */
        .row-e { background-color: #d4edda; } /* hijau muda */
        .row-p { background-color: #d1ecf1; } /* biru langit muda */
=======
        .col-name { width: 38%; }
        .col-pengajuan { width: 12%; }
        .col-category { width: 26%; }
        .col-pic { width: 12%; }
        .col-officer { width: 12%; }
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

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
<<<<<<< HEAD
        <div class="group-zona">{{ $zoneLabels[$zone] ?? strtoupper($zone) }}</div>
=======
        <div class="group-zona">
            {{ $zoneLabels[$zone] ?? strtoupper($zone) }}
        </div>
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744

        @foreach($provGroups as $prov => $kotaGroups)
            <div class="group-prov">{{ strtoupper($prov) }}</div>

<<<<<<< HEAD
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
=======
            @foreach($kotaGroups as $kota => $statusGroups)
                <div class="group-kota">{{ strtoupper($kota) }}</div>

                @foreach($statusGroups as $status => $customers)
                    <!--<div class="group-status">{{ ucfirst($status) }}</div>-->
                    
                    {{-- Normalisasi status menjadi nama kelas CSS yang valid --}}
                    @php
                        $statusClass = strtolower(str_replace(' ', '-', $status)); 
                    @endphp
    
                    <div class="group-status status-{{ $statusClass }}">
                        {{ $status }} 
                    </div>

                    @if($customers->count() > 0)
                        <table class="customer-table">
                           <thead>
                                <tr>
                                    <th style="width:38%">Customer</th>
                                    <th style="width:12%">Pengajuan</th>
                                    <th style="width:26%">Mapping</th>
                                    <th style="width:12%">PIC</th>
                                    <th style="width:12%">Officer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                    <tr>
                                        <td class="col-name">{{ $customer->name ?? '-' }}</td>
                                        <td class="col-pengajuan">
                                            @if($customer->status === 'Prospek')
                                                {{ $customer->pengajuan_text ?? '-' }} {{-- Ganti pengajuan() dengan pengajuan_text --}}
                                            @elseif($customer->status === 'Existing') {{-- Pastikan status 'Existing' sama persis dengan yang di controller --}}
                                                KANTOR
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="col-category">
                                             {{ $customer->status === 'Prospek' 
                                                ? ($customer->store_prospek->category->name ?? '-') 
                                                : ($customer->store_existing->category->name ?? '-') }}
                                        </td>
                                        <td class="col-pic">
                                            {{ $customer->status === 'Prospek' 
                                                ? ($customer->store_prospek->pic ?? '-') 
                                                : ($customer->store_existing->pic ?? '-') }}
                                        </td>
                                        <td class="col-officer">
                                            {{ $customer->status === 'Prospek' 
                                                ? ($customer->officer ?? '-') 
                                                : ($customer->officer ?? '-') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="no-data">Tidak ada customer pada status ini.</div>
                    @endif
                @endforeach
            @endforeach
        @endforeach
    @empty
        <p class="no-data" style="text-align:center; margin-top:50px;">
            Tidak ada data Customer yang ditemukan.
        </p>
>>>>>>> ff72a8505dacce6c7d2e638a1881df17b008d744
    @endforelse
</body>
</html>