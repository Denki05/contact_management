<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StoreProspekTemplateExport implements WithHeadings
{
    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'name',                 // Nama Store/Prospek (Wajib)
            'contact_person',       // Nama Owner (Wajib)
            'phone1',               // Telepon 1
            'phone2',               // Telepon 2
            'email',
            'website',
            'ao',           
            'address',
            'category_id',          // Nama Kategori (Lihat Master)
            'province',             // Nama Provinsi (Lihat Master)
            'city',                 // Nama Kota/Kabupaten (Lihat Master)
            'officer',              // Nama Officer/Sales
            'pengajuan',
            'zone',                 // <--- FIELD BARU: Zone/Area Pemasaran
            'catatan_tambahan',
            'informasi_tambahan'
        ];
    }
}