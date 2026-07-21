<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use App\Master\Store;
use App\Master\StoreProspek;

class StatusUpdateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($row) {

            $mappingKategori = $row->MAPPING_KATEGORI;
            $statusSaatIni = $row->STATUS_SAAT_INI;

            // Ambil model terbaru dari database
            if (isset($row->ID)) {
                if ($row->TYPE === 'PROSPEK') {
                    $model = StoreProspek::with('category')->find($row->ID);
                } elseif ($row->TYPE === 'EXISTING') {
                    $model = Store::with('category')->find($row->ID);
                } else {
                    $model = null;
                }

                if ($model) {
                    // Update kategori
                    if ($model->category) {
                        $mappingKategori = $model->category->name;
                    }

                    // Ambil status numeric dari DB langsung
                    $statusSaatIni = $model->status; // 0,1,2 sesuai database
                }
            }

            return [
                'ID' => $row->ID,
                'TYPE' => $row->TYPE,
                'PIC' => $row->PIC_STORE,
                'PROVINSI' => $row->TEXT_PROVINSI,
                'KOTA' => $row->TEXT_KOTA,
                'NAMA' => $row->NAMA,
                'MAPPING_KATEGORI' => $mappingKategori,
                'STATUS_SAAT_INI' => $statusSaatIni, // angka 0/1/2
                'STATUS_BARU' => '', // Kolom input kosong untuk import
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'TYPE',
            'PIC',
            'PROVINSI',
            'KOTA',
            'NAMA',
            'MAPPING_KATEGORI',
            'STATUS_SAAT_INI',
            'STATUS_BARU',
        ];
    }
}