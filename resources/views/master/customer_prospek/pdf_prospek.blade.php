<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; margin:0; padding:20px; }

        /* Header */
        .header { text-align:center; margin-bottom:20px; border-bottom:2px solid #000; padding-bottom:10px; }
        .header h1 { font-size:14pt; margin:0; }
        .header p { font-size:10pt; margin:5px 0 0; }

        /* Group Level Styles */
        .group-zona {
            font-size:11pt;
            font-weight:bold;
            margin-top:15px;
            border-bottom:1px solid #000;
            padding-bottom:3px;
        }

        .group-provinsi {
            font-size:10pt;
            font-weight:bold;
            margin-top:8px;
            margin-left:15px;
            padding-bottom:2px;
        }

        .group-kota {
            font-size:9.5pt;
            font-weight:bold;
            margin-top:6px;
            margin-left:30px;
            color:#444;
        }

        .group-status {
            font-size:9pt;
            font-weight:bold;
            margin-top:5px;
            margin-left:45px;
            color:#333;
        }

        /* Detail Table */
        table.customer-table {
            border-collapse: collapse;
            width: 90%;
            margin-left: 60px;
            margin-top: 3px;
            font-size: 9pt;
        }

        table.customer-table th, 
        table.customer-table td {
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

        .col-name { width: 40%; }
        .col-category { width: 35%; }
        .col-pic { width: 25%; }

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
    <div class="group-zona">
            {{ $zoneLabels[$zone] ?? strtoupper($zone) }}
        </div>

    @foreach($provGroups as $provinsi => $kotaGroups)
        <div class="group-provinsi">{{ strtoupper($provinsi) }}</div>

        @foreach($kotaGroups as $kota => $customers)
            <div class="group-kota">{{ strtoupper($kota) }}</div>

            <table class="customer-table">
                <!--<thead>-->
                <!--    <tr>-->
                <!--        <th style="width:45%">Customer</th>-->
                <!--        <th style="width:15%">Kategori</th>-->
                <!--        <th style="width:10%">PIC</th>-->
                <!--    </tr>-->
                <!--</thead>-->
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->store_prospek->category->name ?? '-' }}</td>
                            <td>{{ $customer->store_prospek->pic ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endforeach
@empty
    <p class="no-data">Tidak ada data customer prospek ditemukan.</p>
@endforelse

</body>
</html>