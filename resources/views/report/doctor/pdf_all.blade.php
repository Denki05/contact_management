<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .title { 
            font-size: 16px; 
            font-weight: bold; 
            margin-bottom: 15px; 
            text-align: center;  /* tambahkan ini */
        }
        .date-title { font-size: 14px; font-weight: bold; margin-top: 20px; border-top: 1px solid #000; padding-top: 5px; }
        ul { margin: 5px 0 10px 20px; padding: 0; }
        .task { margin-bottom: 3px; }
        .footer { position: fixed; bottom: 20px; width: 100%; text-align: right; font-size: 10px; border-top: 1px solid #000; padding-top: 5px; }
    </style>
</head>
<body>

<div class="title" style="text-align: center;">
    Laporan Agenda {{ strtoupper($officer) }}<br>
    Periode :
    <span class="text-primary">
        {{ \Carbon\Carbon::parse($start)->format('d M y') }} - {{ \Carbon\Carbon::parse($end)->format('d M y') }}
    </span><br>
</div>

@foreach($data as $tgl => $items)

    <div class="date-title">
        {{ \Carbon\Carbon::parse($tgl)
            ->setTimezone('Asia/Jakarta')
            ->locale('id')           // menggunakan lokal bahasa Indonesia
            ->isoFormat('dddd, D MMM YY') 
        }}
    </div>

    @foreach($items as $agenda)
        <ul>
            @foreach($agenda['tasks'] as $t)
                <li class="task">{{ $t['keterangan'] }}</li>
            @endforeach
        </ul>
    @endforeach

@endforeach

<div class="footer">
    Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
</div>

</body>
</html>
