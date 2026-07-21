<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        .title { 
            font-size: 16px; 
            font-weight: bold; 
            margin-bottom: 15px; 
            text-align: center;  /* Center judul */
        }
        ul { margin: 5px 0 10px 20px; padding-left: 0; }
        .task { margin-bottom: 3px; }
        .footer {
            border-top: 1px solid #000;
            margin-top: 20px;
            padding-top: 5px;
            font-size: 10px;
            text-align: right;
            color: #555;
        }
    </style>
</head>
<body>

<div class="title">
    Laporan Agenda {{ strtoupper($officer) }}<br>
    Tanggal: {{ \Carbon\Carbon::parse($tanggal)
        ->setTimezone('Asia/Jakarta')
        ->locale('id')
        ->isoFormat('dddd, D MMM YY') 
    }}
</div>

@foreach($data as $agenda)
    <ul>
        @foreach($agenda['tasks'] as $t)
            <li class="task">{{ $t['keterangan'] }}</li>
        @endforeach
    </ul>
@endforeach

<div class="footer">
    Dicetak tanggal: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}
</div>

</body>
</html>