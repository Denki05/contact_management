<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Follow Up Report - {{ $officerId }}</title>
    <style>
        /* 1. PENYESUAIAN MARGIN & ORIENTASI KERTAS (LANDSCAPE) */
        @page { 
            /* Margin: atas 25px, kanan 30px, bawah 25px, kiri 50px (untuk ruang plong) */
            margin: 25px 30px 25px 50px; 
            size: landscape; /* Dokumen disetel ke Landscape */
        }
        
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }

        /* PERUBAHAN: H1 menggantikan H2 */
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
        }

        /* MODIFIKASI Sel Tabel */
        th, td {
            border: 0.5px solid #ccc;
            vertical-align: top;
            word-wrap: break-word;
            white-space: normal;
        }
        
        /* Header Tabel */
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            color: #111;
            font-size: 13px;
            border-bottom: 1px solid #bbb;
            padding: 4px 6px; 
        }

        /* Isi Sel Tabel */
        td {
            /* Padding disesuaikan: atas 4, kanan 6, bawah 4, kiri 10px (untuk menjauh dari border kiri) */
            padding: 4px 6px 4px 10px; 
        }

        /* 2. PENGHAPUSAN WARNA BARIS GENAP */
        /* Blok ini dihilangkan/dikosongkan agar tabel berwarna putih polos */
        /* tr:nth-child(even) td { background-color: #fafafa; } */

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

        /* Penyesuaian lebar kolom */
        .report-table th:nth-child(1),
        .report-table td:nth-child(1) {
            width: 15%; /* Customer */
        }
        .report-table th:nth-child(2),
        .report-table td:nth-child(2) {
            width: 32.5%; /* Deskripsi */
        }
        .report-table th:nth-child(3),
        .report-table td:nth-child(3) {
            width: 20%; /* Produk */
        }
        .report-table th:nth-child(4),
        .report-table td:nth-child(4) {
            width: 32.5%; /* Respon */
        }

        thead { border-bottom: 1px solid #aaa; }
        /* Efek hover dinonaktifkan karena tidak berfungsi di PDF */
        /* tbody tr:hover td { background-color: #f8f8f8; } */
    </style>
</head>
<body>
    
    <h1>Follow Up Report {{ $officerId }}</h1>
    
    <div class="sub-title">
        {{ \Carbon\Carbon::parse($tanggal)
            ->setTimezone('Asia/Jakarta')
            ->locale('id')
            ->translatedFormat('l, d-m-Y') }}
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
            @foreach ($visits as $v)
                <tr>
                    <td>
                        {{-- Waktu (Jam:Menit) dan Tanggal lengkap --}}
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
                           <span style="font-weight: normal; color:#666; font-size: 9px;"> - {{ $v['text_kota'] }}</span>
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

    <div class="footer">
        Dicetak pada {{ now()->format('d M Y') }}
    </div>
</body>
</html>