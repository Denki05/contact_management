<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerNationalExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $data;

    public function collection()
    {
        // -------------------------------
        // Existing Customers
        // -------------------------------
        $existing = DB::table('master_customer_other_addresses as s')
            ->leftJoin('master_customers as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('master_customer_categories as cat', 'cat.id', '=', 'c.category_id')
            ->where('c.status', 1)
            ->whereNull('s.deleted_at')
            ->select(
                'c.pic',
                DB::raw('UPPER(TRIM(s.text_provinsi)) as provinsi'),
                DB::raw('UPPER(TRIM(s.text_kota)) as kota'),
                DB::raw('COALESCE(cat.name, "Tanpa Kategori") as category'),
                DB::raw('"EXISTING" as status'),
                DB::raw('"E" as labels'),
                's.name',
                DB::raw('"KANTOR" as pengajuan')
            )
            ->get()
            ->map(function ($item) {
                return (object)[
                    '#' => $item->labels,
                    'pic' => $item->pic ?? '-',
                    'provinsi' => $item->provinsi ?? '-',
                    'kota' => $item->kota ?? '-',
                    'category_status' => $item->category ?? '-',
                    'name' => $item->name ?? '-',
                    'pengajuan' => $item->pengajuan ?? '-',
                ];
            });

        // -------------------------------
        // Prospek Customers
        // -------------------------------
        $prospek = DB::table('master_customer_other_addresses_prospek as s')
            ->leftJoin('master_customers_prospek as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('master_customer_categories as cat', 'cat.id', '=', 'c.category_id')
            ->where('c.status', 1)
            ->whereNull('s.deleted_at')
            ->select(
                'c.pic',
                DB::raw('UPPER(TRIM(s.text_provinsi)) as provinsi'),
                DB::raw('UPPER(TRIM(s.text_kota)) as kota'),
                DB::raw('COALESCE(cat.name, "Tanpa Kategori") as category'),
                DB::raw('"PROSPEK" as status'),
                DB::raw('"P" as labels'),
                's.name',
                DB::raw("
                    CASE s.pengajuan
                        WHEN 1 THEN 'KANTOR'
                        WHEN 2 THEN 'ERICK'
                        WHEN 3 THEN 'LINDY'
                        WHEN 4 THEN 'KUMALA'
                        WHEN 5 THEN 'NIA'
                        ELSE '-'
                    END as pengajuan
                ")
            )
            ->get()
            ->map(function ($item) {
                return (object)[
                    '#' => $item->labels,
                    'pic' => $item->pic ?? '-',
                    'provinsi' => $item->provinsi ?? '-',
                    'kota' => $item->kota ?? '-',
                    'category_status' => $item->category ?? '-',
                    'name' => $item->name ?? '-',
                    'pengajuan' => $item->pengajuan ?? '-',
                ];
            });

        // Gabungkan Existing & Prospek
        $combined = $existing->concat($prospek)->values();

        // Normalisasi nilai agar konsisten
        $normalized = $combined->map(function ($item) {
            return (object)[
                '#' => $item->{'#'},
                'pic' => strtoupper(trim($item->pic ?? '')),
                'provinsi' => strtoupper(trim($item->provinsi ?? '')),
                'kota' => strtoupper(trim($item->kota ?? '')),
                'category_status' => $item->category_status ?? '',
                'name' => strtoupper(trim($item->name ?? '')),
                'pengajuan' => strtoupper(trim($item->pengajuan ?? '')),
            ];
        });

        // Konversi ke array untuk pengurutan manual
        $arr = $normalized->toArray();

        // Urutkan berdasarkan PIC -> PROVINSI -> KOTA -> NAME
        usort($arr, function ($a, $b) {
            $keys = ['pic', 'provinsi', 'kota', 'name'];
            foreach ($keys as $key) {
                $va = $a->$key ?? '';
                $vb = $b->$key ?? '';
                $cmp = strcmp($va, $vb);
                if ($cmp !== 0) {
                    return $cmp;
                }
            }
            return 0;
        });

        // Kembalikan ke collection
        $this->data = collect($arr)->values();

        return $this->data;
    }

    public function headings(): array
    {
        return [
            '#',
            'PIC',
            'PROVINSI',
            'KOTA',
            'MAPPING',
            'NAMA CUSTOMER',
            'PENGAJUAN',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => 'center'],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFFFFF'],
            ],
        ]);

        // Style baris data
        $rowStart = 2;
        foreach ($this->data as $index => $item) {
            $row = $rowStart + $index;
            if ($item->{'#'} === 'E') {
                $sheet->getStyle("A{$row}:G{$row}")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D4EDDA');
            }
        }
    }

    // Atur lebar kolom
    public function columnWidths(): array
    {
        return [
            'A' => 2,
            'B' => 8,
            'C' => 25,
            'D' => 19,
            'E' => 28,
            'F' => 48,
            'G' => 10,
        ];
    }
}