<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContactTemplateExport;
use App\Imports\ContactImport;
use Illuminate\Support\Facades\Session;

class ContactExportImportController extends Controller
{
    // Fungsi untuk mengekspor template Excel
    public function exportTemplate()
    {
        return Excel::download(new ContactTemplateExport, 'contact_template.xlsx');
    }

    // Fungsi untuk mengimpor data dari Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $import = new ContactImport();
            Excel::import($import, $request->file('file'));

            if ($import->getErrorMessages()) {
                return back()->with('error', 'Beberapa data gagal diimport: <br>' . implode('<br>', $import->getErrorMessages()));
            }

            return back()->with('success', 'Data berhasil diimport!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

}