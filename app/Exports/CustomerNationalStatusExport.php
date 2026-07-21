<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerNationalStatusExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
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
                DB::raw('COALESCE(cat.name, "TANPA KATEGORI") as category'),
                DB::raw('"E" as label'),
                's.name',
                DB::raw('"KANTOR" as pengajuan'),
                DB::raw("
                    CASE c.status
                        WHEN 1 THEN '1'
                        WHEN 2 THEN '0'
                        ELSE '-'
                    END as status_flag
                ")
            )
            ->get()
            ->map(function ($item) {
                return (object)[
                    'label'         => $item->label,
                    'pic'           => strtoupper(trim($item->pic ?? '-')),
                    'provinsi'      => strtoupper(trim($item->provinsi ?? '-')),
                    'kota'          => strtoupper(trim($item->kota ?? '-')),
                    'category_status'=> strtoupper(trim($item->category ?? '-')),
                    'name'          => strtoupper(trim($item->name ?? '-')),
                    'pengajuan'     => strtoupper(trim($item->pengajuan ?? '-')),
                    'status_flag'   => strtoupper(trim($item->status_flag ?? '-')),
                ];
            });

        // -------------------------------
        // Prospek Customers
        // -------------------------------
        $prospek = DB::table('master_customer_other_addresses_prospek as s')
            ->leftJoin('master_customers_prospek as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('master_customer_categories as cat', 'cat.id', '=', 'c.category_id')
            ->whereNull('s.deleted_at')
            ->select(
                'c.pic',
                DB::raw('UPPER(TRIM(s.text_provinsi)) as provinsi'),
                DB::raw('UPPER(TRIM(s.text_kota)) as kota'),
                DB::raw('COALESCE(cat.name, "TANPA KATEGORI") as category'),
                DB::raw('"P" as label'),
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
                "),
                'c.status as status_flag'
            )
            ->get()
            ->map(function ($item) {
                return (object)[
                    'label'         => $item->label,
                    'pic'           => strtoupper(trim($item->pic ?? '-')),
                    'provinsi'      => strtoupper(trim($item->provinsi ?? '-')),
                    'kota'          => strtoupper(trim($item->kota ?? '-')),
                    'category_status'=> strtoupper(trim($item->category ?? '-')),
                    'name'          => strtoupper(trim($item->name ?? '-')),
                    'pengajuan'     => strtoupper(trim($item->pengajuan ?? '-')),
                    'status_flag'   => strtoupper(trim($item->status_flag ?? '-')),
                ];
            });

        // -------------------------------
        // Gabung & Urutkan
        // -------------------------------
        $combined = $existing->concat($prospek)->values();
        $arr = $combined->toArray();

        // Urutkan berdasarkan PIC, PROVINSI, KOTA, NAME
        usort($arr, function ($a, $b) {
            $keys = ['pic', 'provinsi', 'kota', 'name'];
            foreach ($keys as $key) {
                $va = $a->$key ?? '';
                $vb = $b->$key ?? '';
                $cmp = strcmp($va, $vb);
                if ($cmp !== 0) return $cmp;
            }
            return 0;
        });

        // Simpan ke property
        $this->data = collect($arr);

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
            'FLAG',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style baris data
        $rowStart = 2;
        foreach ($this->data as $index => $item) {
            $row = $rowStart + $index;
            if (($item->label ?? '') === 'E') {
                $sheet->getStyle("A{$row}:H{$row}")
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('D4EDDA'); // Warna hijau lembut
            }
        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 2,
            'B' => 8,
            'C' => 25,
            'D' => 25,
            'E' => 35,
            'F' => 48,
            'G' => 12,
            'H' => 10,
        ];
    }
}