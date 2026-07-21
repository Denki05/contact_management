<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Follow Up Report - {{ $officerId }}</title>
    <style>
        @page { 
            margin: 25px 30px 25px 50px; 
            size: landscape; 
        }

        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 11px; 
            color: #333; 
        }

        h1 { 
            text-align: center; 
            margin-bottom: 2px; 
            color: #222; 
        }

        .sub-title { 
            text-align: center; 
            font-size: 11px; 
            color: #555; 
            margin-bottom: 15px; 
            font-style: italic; 
        }

        .officer-title {
            margin-top: 25px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            color: #000;
            border-bottom: 1px solid #999;
            padding-bottom: 4px;
        }

        .page-break {
            page-break-before: always;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        th, td { 
            border: 0.5px solid #ccc; 
            vertical-align: top; 
            word-wrap: break-word; 
            white-space: normal;
        }

        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
            text-align: center; 
            color: #111; 
            font-size: 13px;
            padding: 4px 6px; 
        }

        td {
            padding: 4px 6px 4px 10px; 
        }

        .customer { 
            font-weight: bold; 
            font-size: 11px; 
            color: #111; 
        }

        .kegiatan { 
            font-size: 10px; 
            color: #555; 
            margin-top: 2px; 
        }

        .footer { 
            text-align: center; 
            font-size: 9px; 
            color: #777; 
            margin-top: 30px; 
            border-top: 1px solid #ddd; 
            padding-top: 5px; 
        }

        .report-table th:nth-child(1),
        .report-table td:nth-child(1) {
            width: 15%;
        }

        .report-table th:nth-child(2),
        .report-table td:nth-child(2) {
            width: 32.5%;
        }

        .report-table th:nth-child(3),
        .report-table td:nth-child(3) {
            width: 20%;
        }

        .report-table th:nth-child(4),
        .report-table td:nth-child(4) {
            width: 32.5%;
        }
    </style>
</head>
<body>

    <h1>
        Follow Up Report 
        {{ $officerId === 'all' ? 'All AO' : strtoupper($officerId) }}
    </h1>

    <div class="sub-title">
        Periode 
        {{ \Carbon\Carbon::parse($startDate)
            ->setTimezone('Asia/Jakarta')
            ->locale('id')
            ->translatedFormat('l, d-m-Y') }}
        s/d
        {{ \Carbon\Carbon::parse($endDate)
            ->setTimezone('Asia/Jakarta')
            ->locale('id')
            ->translatedFormat('l, d-m-Y') }}
    </div>

    @php
        $groupedVisits = collect($visits)->groupBy('pic_customer');
    @endphp
    
    @foreach ($groupedVisits as $pic => $items)
    
        @if (!$loop->first)
            <div class="page-break"></div>
        @endif
    
        <div class="officer-title">
            AO : {{ strtoupper($pic) }}
        </div>
    
        <table class="report-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Deskripsi</th>
                    <th>Produk</th>
                    <th>Respon</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $v)
                    <tr>
                        <td>
                            @if(!empty($v['created_at']))
                                <div class="timestamp" style="font-size: 9px; color: #999;">
                                    {{ \Carbon\Carbon::parse($v['created_at'])
                                        ->setTimezone('Asia/Jakarta')
                                        ->locale('id')
                                        ->translatedFormat('l, d-m-Y H:i:s') }}
                                </div>
                            @endif
    
                            <div class="customer">
                                {{ $v['customer'] ?? '-' }}
                                @if(!empty($v['text_kota']))
                                    <span style="font-weight: normal; color:#666; font-size: 9px;">
                                        - {{ $v['text_kota'] }}
                                    </span>
                                @endif
                            </div>
    
                            @if(!empty($v['kegiatan']))
                                <div class="kegiatan">{{ $v['kegiatan'] }}</div>
                            @endif
                        </td>
                        <td>{{ $v['kegiatan_text'] ?? '-' }}</td>
                        <td>{{ $v['produk'] ?? '-' }}</td>
                        <td>{{ $v['respon'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    
    @endforeach

    <div class="footer">
        Dicetak pada {{ now()->format('d M Y') }}
    </div>

</body>
</html>