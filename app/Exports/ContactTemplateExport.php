<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['', '', '', '', ''],
        ];
    }

    public function headings(): array
    {
        return ['nama', 'posisi', 'store', 'telepon', 'dob', 'email', 'npwp', 'ktp'];
    }
}