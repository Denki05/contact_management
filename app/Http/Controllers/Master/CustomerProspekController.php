<?php

namespace App\Http\Controllers\Master;

use App\Master\StoreProspek;
use App\Master\Store;
use App\Master\CustomerProspek;
use App\Master\Customer;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Imports\StoreProspekImport;
use App\Exports\StoreProspekTemplateExport;
use App\Exports\StatusUpdateExport; 
use App\Imports\StatusUpdateImport;
use Maatwebsite\Excel\Facades\Excel; // Pastikan ini di-use
use PDF;

class CustomerProspekController extends Controller
{
    public function index(Request $request)
    {

        if (!auth()->check()) {

            abort(403, 'Unauthorized access');

        }

    

        $type = $request->get('type', 'all');

        $view_data_type = $type;

    

        if ($type === 'existing') {

            $customers = Customer::with(['store_existing.category'])

                ->whereHas('store_existing', function ($query) {

                    $query->where('status', 1);

                })

                ->orderBy('name', 'asc')

                ->get()

                ->map(function ($item) {

                    $item->type = 'existing';

                    return $item;

                });

    

        } elseif ($type === 'prospek') {

            $customers = CustomerProspek::with(['store_prospek.category'])

                ->whereHas('store_prospek', function ($query) {

                    $query->where('status', 1);

                })

                ->orderBy('name', 'asc')

                ->get()

                ->map(function ($item) {

                    $item->type = 'prospek';

                    // Tambahkan label pengajuan agar mudah dipakai di Blade

                    $pengajuanList = CustomerProspek::PENGAJUAN;

                    $item->pengajuan_label = isset($pengajuanList[$item->pengajuan]) ? $pengajuanList[$item->pengajuan] : '-';

                    return $item;

                });

    

        } elseif ($type === 'all') {

            $existing = Customer::with(['store_existing.category'])

                ->whereHas('store_existing', function ($query) {

                    $query->where('status', 1);

                })

                ->orderBy('name', 'asc')

                ->get()

                ->map(function ($item) {

                    $item->type = 'existing';

                    return $item;

                });

    

            $prospek = CustomerProspek::with(['store_prospek.category'])

                ->orderBy('name', 'asc')

                ->get()

                ->map(function ($item) {

                    $item->type = 'prospek';

                    $pengajuanList = CustomerProspek::PENGAJUAN;

                    $item->pengajuan_label = isset($pengajuanList[$item->pengajuan]) ? $pengajuanList[$item->pengajuan] : '-';

                    return $item;

                });

    

            // Gabungkan dan urutkan ulang

            $customers = $existing->concat($prospek)->sortBy('name')->values();

        }

    

        $data['customers'] = $customers;

        $data['data_type'] = $view_data_type;

        $data['kategori'] = DB::table('master_customer_categories')->get();

    

        return view('master.customer_prospek.index', $data);

    }





    public function create()

    {

       if (!auth()->check()) {

            abort(403, 'Unauthorized access');

        }

        

        $data['provinces'] = DB::table('provinsi')->get();

        $data['kategori'] = DB::table('master_customer_categories')->get();

        $data['pengajuanList'] = \App\Master\CustomerProspek::PENGAJUAN;



        return view('master.customer_prospek.create', $data);

    }



    public function getkabupaten(request $request)

    {

        $prov_id = $request->prov_id;



        $kabupatens = DB::table('kabupaten')->where('prov_id', $prov_id)->get();



        foreach ($kabupatens as $kabupaten){

            echo "<option value='$kabupaten->city_id'>$kabupaten->city_name</option>";

        }

    }



    public function store(Request $request)

    {

        try {

            // Validasi input

            $request->validate([

                'name' => 'required|string',

                'category_id' => 'required',

                'pengajuan' => 'nullable',

                'owner_name' => 'nullable|string',

                'phone1' => 'nullable|string',

                'phone2' => 'nullable|string',

                'email' => 'nullable|email',

                'website' => 'nullable|string',

                'image_store' => 'nullable|image|max:2048',

                'pic' => 'required|string',

                'officer' => 'required|string',

                'address' => 'required|string',

                'province' => 'nullable|string',

                'city' => 'nullable|string',

                'lat' => 'nullable|numeric',

                'lng' => 'nullable|numeric',

                'npwp' => 'nullable|string',

                'ktp' => 'nullable|string',

                'limit_credit' => 'nullable|numeric',

                'payment_term' => 'nullable|string',

            ]);



            // --- 2. Pengecekan Duplikat Fuzzy Berdasarkan Nama dan Kota ---

            $nameToCheck = strtolower(trim($request->name));

            $cityId = $request->city;

            $threshold = 3; // toleransi kemiripan nama

    

            // Ambil daftar nama dari StoreProspek aktif di kota terkait

            $existingProspekNames = \App\Master\StoreProspek::where('kota', $cityId)

                ->where('status', \App\Master\StoreProspek::STATUS["ACTIVE"])

                ->pluck('name')

                ->toArray();

    

            // Ambil daftar nama dari Store aktif di kota terkait

            $existingStoreNames = \App\Master\Store::where('kota', $cityId)

                ->where('status', \App\Master\Store::STATUS["ACTIVE"])

                ->pluck('name')

                ->toArray();

    

            $isDuplicate = false;

            $duplicateSource = null;

            $duplicateName = null;

    

            // Cek duplikat terhadap StoreProspek

            foreach ($existingProspekNames as $existingName) {

                $distance = levenshtein($nameToCheck, strtolower(trim($existingName)));

                if ($distance <= $threshold) {

                    $isDuplicate = true;

                    $duplicateSource = 'Prospek';

                    $duplicateName = $existingName;

                    break;

                }

            }

    

            // Jika belum duplikat, cek terhadap Store

            if (!$isDuplicate) {

                foreach ($existingStoreNames as $existingName) {

                    $distance = levenshtein($nameToCheck, strtolower(trim($existingName)));

                    if ($distance <= $threshold) {

                        $isDuplicate = true;

                        $duplicateSource = 'Existing';

                        $duplicateName = $existingName;

                        break;

                    }

                }

            }

    

            // Jika ditemukan kemiripan nama

            if ($isDuplicate) {

                return response()->json([

                    'notification' => [

                        'alert' => 'block',

                        'type' => 'alert-danger',

                        'header' => 'Peringatan',

                        'content' => "Nama toko '{$request->name}' terlalu mirip dengan {$duplicateSource} '{$duplicateName}' di kota ini. Data dianggap duplikat.",

                    ],

                ], 400);

            }



            // Gabungkan telepon

            $phone = trim(implode(",", array_filter([$request->phone1, $request->phone2])), ',');



            // 1. Buat dan isi data untuk StoreProspek (PIC masuk di sini)

            $storeProspek = new StoreProspek();

            $storeProspek->count_member = 1;

            $storeProspek->category_id = $request->category_id;

            $storeProspek->name = $request->name;

            $storeProspek->owner_name = $request->owner_name;

            $storeProspek->phone = $phone;

            $storeProspek->email = $request->email;

            $storeProspek->website = $request->website;

            $storeProspek->address = $request->address;

            $storeProspek->provinsi = $request->province;

            $storeProspek->kota = $request->city;

            $storeProspek->text_provinsi = $request->text_provinsi;

            $storeProspek->text_kota = $request->text_kota;

            $storeProspek->npwp = $request->npwp;

            $storeProspek->ktp = $request->ktp;

            $storeProspek->pic = $request->pic; // PIC masuk ke StoreProspek

            $storeProspek->status = StoreProspek::STATUS['ACTIVE'];

            $storeProspek->existence = 1;

            $storeProspek->count_member = 1;



            // Mengatasi upload gambar

            if ($request->hasFile('image_store')) {

                $path = $request->file('image_store')->store('public/images/prospek');

                $storeProspek->image_store = $path;

            }



            // Simpan data StoreProspek

            $storeProspek->save();

            

            // 2. Buat dan isi data untuk CustomerProspek (Officer masuk di sini)

            $customerProspek = new CustomerProspek();

            $customerProspek->id = $storeProspek->id.'.'.$storeProspek->count_member;

            $customerProspek->name = $request->name;

            $customerProspek->pengajuan = $request->pengajuan;

            $customerProspek->customer_id = $storeProspek->id;

            $customerProspek->contact_person = $request->owner_name;

            $customerProspek->phone = $phone;

            $customerProspek->address = $request->address;

            $customerProspek->provinsi = $request->province;

            $customerProspek->kota = $request->city;

            $customerProspek->text_provinsi = $request->text_provinsi;

            $customerProspek->text_kota = $request->text_kota;

            $customerProspek->zone = $request->zone;

            $customerProspek->npwp = $request->npwp;

            $customerProspek->ktp = $request->ktp;

            $customerProspek->officer = $request->officer; // Officer masuk ke CustomerProspek

            $customerProspek->member_default = 1;

            $customerProspek->status = CustomerProspek::STATUS['ACTIVE'];



            // Simpan data CustomerProspek

            $customerProspek->save();



            return redirect()->route('master.customer_prospek.index')->with('success', 'Data berhasil ditambahkan.');



        } catch (\Illuminate\Validation\ValidationException $e) {

            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {

            \Log::error('Gagal menyimpan data contact: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();

        }

    }

    

    public function handleAjax(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $action = $request->input('action');
        $id = $request->input('id');

        if ($action === 'show') {
            // ... (LOGIKA SHOW TIDAK BERUBAH) ...
            $dataType = $request->input('data_type', 'prospek');
            $customer = null;

            if ($dataType === 'existing') {
                $customer = Customer::with(['store_existing', 'store_existing.category'])->find($id);
            } else {
                $customer = CustomerProspek::with(['store_prospek', 'store_prospek.category'])->find($id);
            }

            if (!$customer) {
                return response()->json(['message' => 'Data not found'], 404);
            }

            return response()->json($customer);

        } elseif ($action === 'update_batch') { // Aksi BARU untuk update massal
            // === LOGIKA UPDATE BATCH (HANYA UNTUK PROSPEK) ===
            $request->validate([
                // UBAH: Gunakan 'string' atau hapus |integer. Biarkan 'required'.
                'id' => 'required|string', 
                'updates' => 'required|array',
                'updates.*.field' => 'required|string',
                'updates.*.value' => 'nullable|string',
            ]);

            $id = $request->input('id'); // ID diambil sebagai string
            $customer = CustomerProspek::with('store_prospek')->find($id);

            if (!$customer) {
                return response()->json(['message' => 'Data not found for update (Update hanya diizinkan untuk Prospek)'], 404);
            }

            $updates = $request->input('updates');
            $prospekUpdates = [];
            $storeProspekUpdates = [];
            $fillableCustomerProspek = (new CustomerProspek)->getFillable();
    
            foreach ($updates as $update) {
                $field = $update['field'];
                $value = $update['value'];

                // Pisahkan antara field di CustomerProspek dan StoreProspek
                if ($field === 'pic') {
                    $storeProspekUpdates['pic'] = $value;
                } elseif ($field === 'kategori_id') {
                    // Perhatikan: JavaScript mengirim 'kategori', di sini kita harus memetakan ke 'category_id'
                    $storeProspekUpdates['category_id'] = $value;
                } elseif (in_array($field, $fillableCustomerProspek)) {
                    $prospekUpdates[$field] = $value;
                } else {
                    // Logika opsional: abaikan field yang tidak valid atau kembalikan error
                    // return response()->json(['message' => 'Invalid field: ' . $field], 400); 
                }

            }

            // 1. Update CustomerProspek
            if (!empty($prospekUpdates)) {
                $customer->update($prospekUpdates);
            }

            // 2. Update StoreProspek (jika ada)
            if (!empty($storeProspekUpdates)) {
                if ($customer->store_prospek) {
                    $customer->store_prospek->update($storeProspekUpdates);
                } else {
                    // Jika store_prospek belum ada dan mencoba update PIC/Kategori
                    return response()->json(['message' => 'Relasi Store Prospek tidak ditemukan.'], 400);
                }
            }

            return response()->json(['message' => 'Semua data customer berhasil diupdate!']);
        }

        // Jika aksi tidak valid
        return response()->json(['message' => 'Invalid action'], 400);
    }

    

    public function importBatch(Request $request)

    {

        $request->validate([

            'file' => 'required|file|mimes:xlsx,xls|max:10240', // Max 10MB

        ]);



        try {

            $import = new StoreProspekImport();

            

            // Lakukan import

            Excel::import($import, $request->file('file'));



            // Setelah import selesai, cek apakah ada baris yang gagal

            $failedRows = $import->getFailedRows();



            if (!empty($failedRows)) {

                // Jika ada kegagalan, simpan pesan kegagalan di session

                return redirect()->back()->with('error', 'Import selesai, namun ada data yang gagal dimasukkan.')->with('failed_imports', $failedRows);

            }



            // Jika berhasil semua

            return redirect()->back()->with('success', 'Semua data Store Prospek berhasil di-import.');



        } catch (\Exception $e) {

            dd($e);

            // Tangani error umum (misalnya file rusak, memory limit)

            \Log::error("General Import Error: " . $e->getMessage());

            return redirect()->back()->with('error', 'Import gagal total karena masalah teknis: ' . $e->getMessage());

        }

    }

    

    public function exportTemplate()

    {

        $fileName = 'template_import_store_prospek_' . now()->format('Ymd_His') . '.xlsx';

        

        // Menggunakan Excel facade dan StoreProspekTemplateExport class

        // Pastikan nama file yang diunduh jelas.

        return Excel::download(new StoreProspekTemplateExport(), $fileName);

    }

    

    public function destroy($id)

    {

        // 1. Cari Customer Prospek

        $customer = CustomerProspek::find($id);

    

        if (!$customer) {

            return redirect()->route('master.customer_prospek.index')

                             ->with('error', 'Customer Prospek tidak ditemukan.');

        }

    

        // 2. Cek apakah Customer ini memiliki Store Prospek (induk)

        $store = $customer->store_prospek; // pastikan relasi belongsTo ada di model CustomerProspek

    

        if ($store) {

            // Hitung jumlah member sebelum delete

            $memberCount = $store->member_prospek()->count();

    

            // 3. Soft delete semua member yang berelasi (termasuk Customer ini sendiri)

            $store->member_prospek()->delete();

    

            // 4. Soft delete Store Prospek

            $store->delete();

    

            return redirect()->route('master.customer_prospek.index')

                             ->with('success', "Customer Prospek {$store->name} berhasil dihapus.");

        } else {

            // Jika tidak punya store induk, hapus customer-nya saja

            $customer->delete();

    

            return redirect()->route('master.customer_prospek.index')

                             ->with('success', "Customer Prospek **{$customer->name}** berhasil diarsipkan.");

        }

    }



    public function exportPdf()

    {

        // Ambil data Prospek

        $prospek = CustomerProspek::with(['store_prospek.category'])

            ->select('*', DB::raw("COALESCE(zone, 'ZONA LAIN') as sortable_zone"), DB::raw("'Prospek' as status"))

            ->orderBy('text_provinsi')

            ->orderBy('text_kota')

            ->orderBy('name')

            ->get();

    

        // Ambil data Existing

        $existing = Customer::with(['store_existing.category'])

            ->whereHas('store_existing', function($q) {

                $q->where('status', 1);

            })

            ->select('*', DB::raw("COALESCE(zone, 'ZONA LAIN') as sortable_zone"), DB::raw("'Existing' as status"))

            ->orderBy('text_provinsi')

            ->orderBy('text_kota')

            ->orderBy('name')

            ->get();

    

        // Gabungkan

        $customers = $existing->concat($prospek);

    

        // Normalisasi huruf besar

        $customers = $customers->map(function ($item) {

            $item->text_provinsi = strtoupper(trim($item->text_provinsi ?? 'TIDAK ADA PROVINSI'));

            $item->text_kota = strtoupper(trim($item->text_kota ?? 'TIDAK ADA KOTA'));

            $item->sortable_zone = strtoupper(trim($item->sortable_zone ?? 'ZONA LAIN'));

            return $item;

        });

    

        // Grouping

        $groupedCustomers = $customers->groupBy('sortable_zone')

            ->map(function ($zoneGroup) {

                return $zoneGroup->groupBy('text_provinsi')

                    ->map(function ($provGroup) {

                        return $provGroup->groupBy('text_kota')

                            ->map(function ($kotaGroup) {

                                return $kotaGroup->groupBy('status');

                            });

                    });

            });

    

        // Urutan zona tetap berdasarkan nama asli

        $zoneOrder = [

            'JABODETABEK',

            'JABAR',

            'JATENG - JATIM',

            'SUMATRA',

            'BALI - KALIMANTAN - SULAWESI'

        ];

    

        // Label tampilan zona

        $zoneLabels = [

            'JABODETABEK' => 'ZONA 1 : JABODETABEK',

            'JABAR' => 'ZONA 2 : JABAR',

            'JATENG - JATIM' => 'ZONA 3 : JATENG - JATIM',

            'SUMATRA' => 'ZONA 4 : SUMATERA',

            'BALI - KALIMANTAN - SULAWESI' => 'ZONA 5 : BALI - KALIMANTAN - SULAWESI',

        ];

    

        // Urutkan berdasarkan urutan tetap

        $sortedCustomers = collect();

        foreach ($zoneOrder as $zone) {

            if (isset($groupedCustomers[$zone])) {

                $sortedCustomers[$zone] = $groupedCustomers[$zone];

            }

        }

    

        // Tambah zona lain (di luar daftar utama)

        foreach ($groupedCustomers as $zone => $data) {

            if (!isset($sortedCustomers[$zone])) {

                $sortedCustomers[$zone] = $data;

            }

        }

    

        // Generate PDF

        $pdf = PDF::loadView('master.customer_prospek.pdf_report', [

            'groupedCustomers' => $sortedCustomers,

            'zoneLabels' => $zoneLabels,

            'title' => 'Laporan Customer Berdasarkan Zona, Provinsi, Kota, dan Status'

        ]);

    

        return $pdf->stream('report-customer-' . now()->format('Ymd_His') . '.pdf');

    }



    

    public function exportExistingPdf()

    {

        // Ambil data Existing

        $existing = Customer::with(['store_existing.category'])

            ->whereHas('store_existing', function($q) {

                $q->where('status', 1);

            })

            ->select('*', DB::raw("COALESCE(zone, 'ZONA LAIN') as sortable_zone"))

            ->orderBy('text_provinsi')

            ->orderBy('text_kota')

            ->orderBy('name')

            ->get();

    

        // Normalisasi huruf besar dan nilai default

        $existing = $existing->map(function ($item) {

            $item->text_provinsi = strtoupper(trim($item->text_provinsi ?? 'TIDAK ADA PROVINSI'));

            $item->text_kota = strtoupper(trim($item->text_kota ?? 'TIDAK ADA KOTA'));

            $item->sortable_zone = strtoupper(trim($item->sortable_zone ?? 'ZONA LAIN'));

            return $item;

        });

    

        // Grouping: Zona → Provinsi → Kota

        $groupedCustomers = $existing->groupBy('sortable_zone')

            ->map(function ($zoneGroup) {

                return $zoneGroup->groupBy('text_provinsi')

                    ->map(function ($provGroup) {

                        return $provGroup->groupBy('text_kota');

                    });

            });

    

        // Urutan zona utama

        $zoneOrder = [

            'JABODETABEK',

            'JABAR',

            'JATENG - JATIM',

            'SUMATRA',

            'BALI - KALIMANTAN - SULAWESI'

        ];

    

        // Label zona untuk tampilan

        $zoneLabels = [

            'JABODETABEK' => 'ZONA 1 : JABODETABEK',

            'JABAR' => 'ZONA 2 : JABAR',

            'JATENG - JATIM' => 'ZONA 3 : JATENG - JATIM',

            'SUMATRA' => 'ZONA 4 : SUMATERA',

            'BALI - KALIMANTAN - SULAWESI' => 'ZONA 5 : BALI - KALIMANTAN - SULAWESI',

        ];

    

        // Urutkan sesuai daftar tetap

        $sortedCustomers = collect();

        foreach ($zoneOrder as $zone) {

            if (isset($groupedCustomers[$zone])) {

                $sortedCustomers[$zone] = $groupedCustomers[$zone];

            }

        }

    

        // Tambahkan zona lain (di luar urutan tetap)

        foreach ($groupedCustomers as $zone => $data) {

            if (!isset($sortedCustomers[$zone])) {

                $sortedCustomers[$zone] = $data;

            }

        }

    

        // Generate PDF

        $pdf = PDF::loadView('master.customer_prospek.pdf_existing', [

            'groupedCustomers' => $sortedCustomers,

            'zoneLabels' => $zoneLabels,

            'title' => 'Laporan Customer Existing Berdasarkan Zona, Provinsi, dan Kota'

        ]);

    

        return $pdf->stream('report-customer-existing-' . now()->format('Ymd_His') . '.pdf');

    }

    

    public function exportProspekPdf()

    {

        // Ambil data Prospek

        $prospek = CustomerProspek::with(['store_prospek.category'])

            ->select('*', DB::raw("COALESCE(zone, 'ZONA LAIN') as sortable_zone"))

            ->orderBy('text_provinsi')

            ->orderBy('text_kota')

            ->orderBy('name')

            ->get();

    

        // Normalisasi huruf besar dan nilai default

        $prospek = $prospek->map(function ($item) {

            $item->text_provinsi = strtoupper(trim($item->text_provinsi ?? 'TIDAK ADA PROVINSI'));

            $item->text_kota = strtoupper(trim($item->text_kota ?? 'TIDAK ADA KOTA'));

            $item->sortable_zone = strtoupper(trim($item->sortable_zone ?? 'ZONA LAIN'));

            return $item;

        });

    

        // Grouping: Zona → Provinsi → Kota

        $groupedCustomers = $prospek->groupBy('sortable_zone')

            ->map(function ($zoneGroup) {

                return $zoneGroup->groupBy('text_provinsi')

                    ->map(function ($provGroup) {

                        return $provGroup->groupBy('text_kota');

                    });

            });

    

        // Urutan zona utama

        $zoneOrder = [

            'JABODETABEK',

            'JABAR',

            'JATENG - JATIM',

            'SUMATRA',

            'BALI - KALIMANTAN - SULAWESI'

        ];

    

        // Label zona untuk tampilan

        $zoneLabels = [

            'JABODETABEK' => 'ZONA 1 : JABODETABEK',

            'JABAR' => 'ZONA 2 : JABAR',

            'JATENG - JATIM' => 'ZONA 3 : JATENG - JATIM',

            'SUMATRA' => 'ZONA 4 : SUMATERA',

            'BALI - KALIMANTAN - SULAWESI' => 'ZONA 5 : BALI - KALIMANTAN - SULAWESI',

        ];

    

        // Urutkan sesuai daftar tetap

        $sortedCustomers = collect();

        foreach ($zoneOrder as $zone) {

            if (isset($groupedCustomers[$zone])) {

                $sortedCustomers[$zone] = $groupedCustomers[$zone];

            }

        }

    

        // Tambahkan zona lain (di luar urutan tetap)

        foreach ($groupedCustomers as $zone => $data) {

            if (!isset($sortedCustomers[$zone])) {

                $sortedCustomers[$zone] = $data;

            }

        }

    

        // Generate PDF

        $pdf = PDF::loadView('master.customer_prospek.pdf_prospek', [

            'groupedCustomers' => $sortedCustomers,

            'zoneLabels' => $zoneLabels,

            'title' => 'Laporan Customer Prospek Berdasarkan Zona, Provinsi, dan Kota'

        ]);

    

        return $pdf->stream('report-customer-prospek-' . now()->format('Ymd_His') . '.pdf');

    }

    

    // ALL EXPORT EXISTING & PROSPEK

    // private function getCombinedCustomerData()

    // {

    //     // Definisikan Model Parent Existing untuk konstanta STATUS

    //     $StoreModel = new \App\Master\Store(); 

    

    //     // Bagian EXISTING: Ambil Customer/Member, lalu petakan ke Parent (Store) yang unik.

    //     $existing = \App\Master\Customer::with(['store_existing'])

    //         ->whereHas('store_existing', function ($query) use ($StoreModel) {

    //             $query->where('status', $StoreModel::STATUS['ACTIVE']);

    //         })

    //         ->get()

    //         ->map(function($member) {

    //             $parent = $member->store_existing;

                

    //             if (!$parent) return null; 

    

    //             return (object) [

    //                 'ID' => $parent->id,  

    //                 'TYPE' => 'EXISTING', 

    //                 'NAMA' => $parent->name,

    //                 'MAPPING_KATEGORI' => $parent->category->name ?? 'N/A',

    //                 'PENGAJUAN' => 'KANTOR', 

    //                 'PIC_STORE' => $parent->pic ?? 'N/A',

    //                 'OFFICER_MEMBER' => $member->officer ?? 'N/A',

    //                 'TEXT_KOTA' => $parent->text_kota,

    //                 'TEXT_PROVINSI' => $parent->text_provinsi,

    //                 'STATUS_SAAT_INI' => array_search($parent->status, $parent::STATUS) ?? 'N/A', 

    //             ];

    //         })

    //         ->filter()

    //         // SOLUSI: Hapus duplikasi berdasarkan ID Store (Parent)

    //         ->unique('ID'); 

    

    //     // Definisikan Model Anak Prospek (untuk konstanta PENGAJUAN)

    //     $CustomerProspekModel = new \App\Master\CustomerProspek(); 

    //     // Definisikan Model Parent Prospek (untuk konstanta STATUS)

    //     $StoreProspekModel = new \App\Master\StoreProspek(); 

    

    //     // Bagian PROSPEK: Ambil CustomerProspek/Member, lalu petakan ke Parent (StoreProspek) yang unik.

    //     $prospek = \App\Master\CustomerProspek::with(['store_prospek'])

    //         ->whereHas('store_prospek', function ($query) use ($StoreProspekModel) {

    //             $query->where('status', $StoreProspekModel::STATUS['ACTIVE']);

    //         })

    //         ->get()

    //         ->map(function($member) use ($CustomerProspekModel) {

    //             $parent = $member->store_prospek;

                

    //             if (!$parent) return null; 

                

    //             $pengajuanList = $CustomerProspekModel::PENGAJUAN; 

                

    //             $pengajuanValue = $parent->pengajuan ?? $member->pengajuan ?? null; 

    

    //             return (object) [

    //                 'ID' => $parent->id, 

    //                 'TYPE' => 'PROSPEK', 

    //                 'NAMA' => $parent->name,

    //                 'MAPPING_KATEGORI' => $parent->category->name ?? 'N/A',

    //                 'PENGAJUAN' => $pengajuanList[$pengajuanValue] ?? 'N/A', 

    //                 'PIC_STORE' => $parent->pic ?? 'N/A',

    //                 'OFFICER_MEMBER' => $member->officer ?? 'N/A',

    //                 'TEXT_KOTA' => $parent->text_kota,

    //                 'TEXT_PROVINSI' => $parent->text_provinsi,

    //                 'STATUS_SAAT_INI' => array_search($parent->status, $parent::STATUS) ?? 'N/A', 

    //             ];

    //         })

    //         ->filter()

    //         // SOLUSI: Hapus duplikasi berdasarkan ID Store Prospek (Parent)

    //         ->unique('ID'); 

    

    //     // Gabungkan data Existing dan Prospek, lalu urutkan.

    //     return $existing->merge($prospek)->sortBy(function ($item) {

    //         // Mengubah string nama menjadi huruf kecil untuk memastikan pengurutan abjad yang benar (case-insensitive)

    //         return strtolower($item->NAMA); 

    //     })->values(); // Reset keys setelah pengurutan (opsional, tapi disarankan)

    // }

    

    private function getCombinedCustomerData()
    {
        $CustomerProspekModel = new \App\Master\CustomerProspek();
        $StoreProspekModel = new \App\Master\StoreProspek();

        $prospek = \App\Master\CustomerProspek::with(['store_prospek', 'store_prospek.category'])
            ->whereHas('store_prospek', function ($query) use ($StoreProspekModel) {
                $query->where('status', '!=', $StoreProspekModel::STATUS['DELETED']);
            })
            ->get()
            ->map(function($member) use ($CustomerProspekModel) {
                $parent = $member->store_prospek;
                if (!$parent) return null;

                $pengajuanList = $CustomerProspekModel::PENGAJUAN;
                $pengajuanValue = $parent->pengajuan ?? $member->pengajuan ?? null;
                $statusString = $parent->status ?? 'N/A';

                return (object) [
                    'ID' => $parent->id,
                    'TYPE' => 'PROSPEK',
                    'NAMA' => $parent->name,
                    'MAPPING_KATEGORI' => $parent->category->name ?? 'N/A',
                    'PENGAJUAN' => $pengajuanList[$pengajuanValue] ?? 'N/A',
                    'PIC_STORE' => $parent->pic ?? 'N/A',
                    'OFFICER_MEMBER' => $member->officer ?? 'N/A',
                    'TEXT_KOTA' => $parent->text_kota,
                    'TEXT_PROVINSI' => $parent->text_provinsi,
                    'STATUS_SAAT_INI' => $statusString,
                ];
            })
            ->filter()
            ->unique('ID')
            ->sortBy(function ($item) {
                return mb_strtolower(trim($item->PIC_STORE ?? '')) . '|' .
                       mb_strtolower(trim($item->TEXT_PROVINSI ?? '')) . '|' .
                       mb_strtolower(trim($item->TEXT_KOTA ?? '')) . '|' .
                       mb_strtolower(trim($item->NAMA ?? ''));
            })->values();

        return $prospek;
    }

    public function exportStatusTemplate()
    {
        $data = $this->getCombinedCustomerData();
        $filename = 'template_update_status_all_member_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new StatusUpdateExport($data), $filename);
    }

    public function importStatusUpdate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240'
        ]);

        try {
            Excel::import(new StatusUpdateImport, $request->file('file'));
            return redirect()->back()->with('success', 'Update status customer berhasil dilakukan.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $message = 'Gagal update: ' . $failures[0]->errors()[0] . ' pada baris ' . $failures[0]->row();
            \Illuminate\Support\Facades\Log::error("Excel Validation Error: " . json_encode($failures));
            return redirect()->back()->with('error', $message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Excel Import Status Update Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage());
        }
    }

    private function formatCompanyName($name)

    {

        if (!$name) return $name;

    

        $name = trim($name);

        $name = preg_replace('/\s+/', ' ', $name); // Multiple spaces → single

        $name = strtolower($name);

    

        $legalWords = ['PT', 'CV', 'UD', 'TBK'];

    

        $prefix = '';

        $hasLegal = false;

    

        foreach ($legalWords as $word) {

    

            // Cocokkan badan hukum di awal atau tengah (dengan/tanpa titik)

            $pattern = '/\b' . strtolower($word) . '\b\.?/i';

    

            if (preg_match($pattern, $name)) {

                $hasLegal = true;

                $prefix = strtoupper($word) . '.'; // WAJIB TITIK

                $name = preg_replace($pattern, '', $name);

                break;

            }

        }

    

        // Rapikan kembali nama setelah dibersihkan

        $name = trim($name);

        $name = preg_replace('/\s+/', ' ', $name);

        $name = ucwords($name);

    

        // Gabungkan lagi bila badan usaha ada

        if ($hasLegal) {

            $name = $prefix . ' ' . $name;

        }



        return $name;

    }

    

    public function normalized(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Normalisasi Parent (StoreProspek)
            StoreProspek::whereNotNull('name')->chunk(200, function ($records) {
                foreach ($records as $store) {
                    $normalized = $this->formatCompanyName($store->name);

                    if ($normalized !== $store->name) {
                        $store->update(['name' => $normalized]);
                    }

                    // Sync child yg member_default = YES
                    CustomerProspek::where('customer_id', $store->id)
                        ->where('member_default', 1)
                        ->whereNotNull('name')
                        ->update(['name' => $normalized]);
                }
            });

    

            // 2️⃣ Normalisasi Child Non-Default
            CustomerProspek::whereNotNull('name')
                ->where('member_default', 0)
                ->chunk(200, function ($children) {
                    foreach ($children as $child) {
                        $normalized = $this->formatCompanyName($child->name);

                        if ($normalized !== $child->name) {
                            $child->update(['name' => $normalized]);
                        }
                    }
                });

            DB::commit();

            return back()->with('success', 'Normalisasi nama berhasil dijalankan.');

    

        } catch (\Throwable $t) {

            DB::rollBack();

            return back()->with('error', 'Gagal normalisasi: ' . $t->getMessage());

        }

    }

}