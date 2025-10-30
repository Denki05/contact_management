<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Master\Customer;         
use App\Master\CustomerProspek;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Carbon\Carbon;
use DatePeriod;
use DateInterval;
use PDF;

class FileDoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    const PROSPEK_MODEL = 'App\Master\CustomerProspek';

    public function index(Request $request)
    {
        // Mengambil daftar officer yang akan ditampilkan di dropdown
        $data['officers'] = Customer::select('officer')
            ->whereNotNull('officer')
            ->whereIn('officer', ['Erick', 'Lindy', 'Kumala', 'Kantor'])
            ->distinct()
            ->orderBy('officer')
            ->get();
        
        // Mengambil daftar kategori
        $data['categories'] = DB::table('master_customer_categories')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
        
        // Mengambil parameter dari request (untuk case jika form disubmit)
        $selectedOfficer    = $request->get('store_id');
        $selectedCity       = $request->get('city');
        $selectedCategory   = $request->get('kat');
        $activeTab          = $request->get('tab', 'existing');
        
        // Inisialisasi data kosong jika officer belum dipilih
        $data['customers']      = collect();
        $data['prospekCustomers'] = collect();
        $data['activeTab']      = $activeTab;
        $data['selectedCity']     = $selectedCity;
        $data['selectedCategory'] = $selectedCategory;
        $data['selectedOfficer']  = $selectedOfficer;
        $data['agendas']          = collect();
        
        try {
            // === HANYA JALANKAN QUERY BERAT JIKA OFFICER DIPILIH (misalnya, dari URL) ===
            if ($selectedOfficer) {
                
                // === EXISTING ===
                $customerQuery = Customer::query()->with('store_existing.category');
                
                if ($selectedOfficer !== 'all') {
                    $customerQuery->whereRaw('LOWER(officer) = ?', [strtolower($selectedOfficer)]);
                }
                
                if ($selectedCity) {
                    $customerQuery->where('text_kota', $selectedCity);
                }
                
                if ($selectedCategory) {
                    $customerQuery->whereHas('store_existing.category', function ($q) use ($selectedCategory) {
                        $q->where('id', $selectedCategory);
                    });
                }
                
                $data['customers'] = $customerQuery->orderBy('name', 'asc')->get();
                
                // === PROSPEK ===
                // Asumsi CustomerProspek dan self::PROSPEK_MODEL sudah didefinisikan
                if (class_exists(self::PROSPEK_MODEL)) { 
                    $prospekQuery = CustomerProspek::with('store_prospek.category');
                    
                    if ($selectedOfficer !== 'all') {
                        $prospekQuery->whereRaw('LOWER(officer) = ?', [strtolower($selectedOfficer)]);
                    }
                    
                    if ($selectedCity) {
                        $prospekQuery->where('text_kota', $selectedCity);
                    }
                    
                    if ($selectedCategory) {
                        $prospekQuery->whereHas('store_prospek.category', function ($q) use ($selectedCategory) {
                            $q->where('id', $selectedCategory);
                        });
                    }
                    
                    $data['prospekCustomers'] = $prospekQuery->orderBy('name', 'asc')->get();
                }
                
                // === AGENDA (API Call) ===
                if ($selectedOfficer && $selectedOfficer !== 'all') {
                    try {
                        // Asumsi \GuzzleHttp\Client sudah di-import
                        $client = new \GuzzleHttp\Client([
                            'base_uri' => 'https://program-a.domain',
                            'timeout' => 10.0,
                        ]);
                        
                        $response = $client->request('GET', '/api/agendas');
                        
                        if ($response->getStatusCode() == 200) {
                            $json = json_decode($response->getBody(), true);
                            $allAgendas = $json['data'] ?? [];
                            
                            $filtered = collect($allAgendas)->filter(function ($item) use ($selectedOfficer) {
                                return isset($item['pic']) && strtolower($item['pic']) === strtolower($selectedOfficer);
                            });
                            
                            $data['agendas'] = $filtered->sortByDesc('tanggal')->values();
                        }
                    } catch (\Exception $e) {
                        \Log::error("Agenda API Failed: " . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error("Query Failed: " . $e->getMessage());
        }
        
        return view('report.doctor.index', $data);
    }



    public function getCitiesByOfficer(Request $request)

    {

        $officer = $request->get('officer');

        $tab = $request->get('tab','existing');



        if(!$officer) return response()->json(['cities'=>[]]);



        $officerLower = strtolower($officer);

        $cities = collect();



        if($tab==='existing'){

            try{

                $cities = Customer::select('text_kota')

                    ->whereNotNull('text_kota')

                    ->whereRaw('lower(officer) = ?', [$officerLower])

                    ->groupBy('text_kota')

                    ->orderBy('text_kota')

                    ->pluck('text_kota');

            } catch(\Exception $e){

                Log::error("Existing Cities Query Failed: ".$e->getMessage());

            }

        } elseif($tab==='prospek'){

            if(class_exists(self::PROSPEK_MODEL)){

                try{

                    $cities = CustomerProspek::select('text_kota')

                        ->whereNotNull('text_kota')

                        ->whereRaw('lower(officer) = ?', [$officerLower])

                        ->groupBy('text_kota')

                        ->orderBy('text_kota')

                        ->pluck('text_kota');

                } catch(\Exception $e){

                    Log::error("Prospek Cities Query Failed: ".$e->getMessage());

                }

            }

        }



        return response()->json(['cities'=>$cities->filter()->unique()]);

    }

    

    public function agendaIndex(Request $request)

    {

        $officer = strtolower($request->get('officer'));



        return view('report.doctor.agenda_index', [

            'officer' => $officer

        ]);

    }



    public function agendaData(Request $request)

    {

        $officer = strtolower($request->get('officer'));

        $start   = $request->get('start');

        $end     = $request->get('end');

    

        $events = [];

    

        try {

            $client = new \GuzzleHttp\Client([

                'base_uri' => 'https://sys-af.lsfragrance.id',

                'timeout'  => 10.0,

            ]);

    

            $response = $client->request('GET', '/api/tasks');

            if ($response->getStatusCode() === 200) {

                $json = json_decode($response->getBody(), true);

                $allAgendas = $json['data'] ?? [];

    

                $filtered = collect($allAgendas)

                    ->filter(function ($item) use ($officer, $start, $end) {

                        if (!isset($item['pic']) || strtolower($item['pic']) !== $officer) return false;

                        if (empty($item['tanggal'])) return false;

                        $tgl = \Carbon\Carbon::parse($item['tanggal'])->format('Y-m-d');

                        return $tgl >= $start && $tgl <= $end;

                    });

    

                foreach ($filtered as $agenda) {

                    $taskList = [];

                

                    if (isset($agenda['tasks']) && is_array($agenda['tasks'])) {

                        foreach ($agenda['tasks'] as $task) {

                            if (!empty($task['keterangan_task'])) {

                                $taskList[] = [

                                    'keterangan' => $task['keterangan_task'],

                                    'status'     => $task['status'] ?? '1', // default active

                                ];

                            }

                        }

                    }

                

                    $events[] = [

                        'title' => $agenda['judul'] ?? 'Agenda',

                        'start' => $agenda['tanggal'],

                        'extendedProps' => [

                            'pic'             => $agenda['pic'] ?? '',

                            'keterangan_task' => $taskList,

                            'keterangan'      => $agenda['keterangan'] ?? '',

                        ],

                    ];

                }

            }

        } catch (\Exception $e) {

            \Log::error("Agenda API Failed: " . $e->getMessage());

        }

    

        return response()->json($events);

    }

    

    public function getDoctorByCustomer($customerId, Request $request)

    {

        // Ambil parameter filter tanggal dari request

        $startDate = $request->get('start_date');

        $endDate = $request->get('end_date');



        try {

            $client = new \GuzzleHttp\Client(['timeout'=>10]);

            $response = $client->request('GET', 'https://sys-af.lsfragrance.id/api/doctor');



            if ($response->getStatusCode() !== 200) {

                return response()->json(['success'=>false,'message'=>'Gagal mengambil data doctor']);

            }



            $json = json_decode($response->getBody(), true);

            $allDoctors = $json['data'] ?? [];



            // 1. Filter data Customer berdasarkan customer_id

            $doctorData = collect($allDoctors)->filter(function($item) use ($customerId){

                return isset($item['customer_id']) && $item['customer_id'] == $customerId;

            })->values();



            // 2. Terapkan Filter Tanggal pada array 'detail' jika filter diberikan

            if ($doctorData->isNotEmpty() && ($startDate && $endDate)) {

                

                $customer = $doctorData->first();



                // Ubah format tanggal input filter menjadi objek Carbon

                $start = Carbon::parse($startDate)->startOfDay();

                $end = Carbon::parse($endDate)->endOfDay();

                

                // Filter detail berdasarkan tanggal

                $filteredDetails = collect($customer['detail'] ?? [])->filter(function($detail) use ($start, $end){

                    $tanggal = $detail['tanggal'] ?? null;

                    if (!$tanggal) return false;

                    

                    // PENYESUAIAN PENTING: Menggunakan format Y-m-d (2025-10-02)

                    try {

                        $detailDate = Carbon::createFromFormat('Y-m-d', $tanggal);

                        

                        // Cek apakah tanggal kegiatan berada dalam rentang [start, end]

                        // Note: Karena filter input user adalah tanggal (bukan datetime),

                        // kita bandingkan dengan tanggal kegiatan yang sudah di-parse

                        return $detailDate->greaterThanOrEqualTo($start) && $detailDate->lessThanOrEqualTo($end);

                    } catch (\Exception $e) {

                        // Jika gagal parsing tanggal (format salah), abaikan data ini

                        return false;

                    }

                })->values();



                // Ganti array 'detail' asli dengan yang sudah difilter

                $customer['detail'] = $filteredDetails->toArray();

                

                // Perbarui doctorData

                $doctorData = collect([$customer]); 

            }



            return response()->json([

                'success' => true,

                'data'    => $doctorData

            ]);



        } catch (\Exception $e) {

            \Log::error("Doctor API Failed: ".$e->getMessage());

            return response()->json(['success'=>false,'message'=>'Gagal mengambil data doctor']);

        }

    }

    

    public function marketListPdf(Request $request)

    {

        $officerId = $request->officer_id;

    

        if (!$officerId) {

            abort(400, 'Silakan pilih Officer terlebih dahulu.');

        }

    

        // --- Data Prospek ---

        $prospek = \App\Master\CustomerProspek::with(['store_prospek.category'])

            ->when($officerId, function ($q) use ($officerId) {

                $q->where('officer', $officerId);

            })

            ->select('*', \DB::raw("COALESCE(zone, 'ZONA LAIN') as sortable_zone"), \DB::raw("'Prospek' as status_customer"))

            ->orderBy('text_provinsi')

            ->orderBy('text_kota')

            ->orderBy('name')

            ->get()

            ->map(function ($item) {

                $pengajuanLabel = '-';

                if (!is_null($item->pengajuan) && isset(\App\Master\CustomerProspek::PENGAJUAN[$item->pengajuan])) {

                    $pengajuanLabel = \App\Master\CustomerProspek::PENGAJUAN[$item->pengajuan];

                }

    

                return [

                    'zona' => strtoupper(trim($item->sortable_zone ?: 'ZONA LAIN')),

                    'provinsi' => strtoupper(trim($item->text_provinsi ?: 'TIDAK ADA PROVINSI')),

                    'kota' => strtoupper(trim($item->text_kota ?: 'TIDAK ADA KOTA')),

                    'customer' => $item->name,

                    'pengajuan' => $pengajuanLabel,

                    'mapping' => optional($item->store_prospek->category)->name ?: '-',

                    'pic' => optional($item->store_prospek)->pic ?: '-',

                    'officer' => $item->officer ?: '-',

                    'status_customer' => 'Prospek'

                ];

            });

    

        // --- Data Existing ---

        $existing = \App\Master\Customer::with(['store_existing.category'])

            ->when($officerId, function ($q) use ($officerId) {

                $q->where('officer', $officerId);

            })

            ->whereHas('store_existing', function ($q) {

                $q->where('status', 1);

            })

            ->select('*', \DB::raw("COALESCE(zone, 'ZONA LAIN') as sortable_zone"), \DB::raw("'Existing' as status_customer"))

            ->orderBy('text_provinsi')

            ->orderBy('text_kota')

            ->orderBy('name')

            ->get()

            ->map(function ($item) {

                return [

                    'zona' => strtoupper(trim($item->sortable_zone ?: 'ZONA LAIN')),

                    'provinsi' => strtoupper(trim($item->text_provinsi ?: 'TIDAK ADA PROVINSI')),

                    'kota' => strtoupper(trim($item->text_kota ?: 'TIDAK ADA KOTA')),

                    'customer' => $item->name,

                    'pengajuan' => '-', // tidak ada pengajuan untuk existing

                    'mapping' => optional($item->store_existing->category)->name ?: '-',

                    'pic' => optional($item->store_existing)->pic ?: '-',

                    'officer' => $item->officer ?: '-',

                    'status_customer' => 'Existing'

                ];

            });

    

        // --- Gabungkan semua data

        $data = $existing->concat($prospek)->values()->all(); // ubah ke array biasa

    

        // --- Urutan zona tetap

        $zoneOrder = [

            'JABODETABEK',

            'JABAR',

            'JATENG - JATIM',

            'SUMATRA',

            'BALI - KALIMANTAN - SULAWESI'

        ];

    

        // --- Urutkan secara manual (usort) berdasarkan urutan zona + provinsi + kota + status + customer

        usort($data, function ($a, $b) use ($zoneOrder) {

            $za = isset($a['zona']) ? $a['zona'] : '';

            $zb = isset($b['zona']) ? $b['zona'] : '';

    

            $ia = array_search($za, $zoneOrder);

            $ib = array_search($zb, $zoneOrder);

            $ia = ($ia === false) ? 999 : $ia;

            $ib = ($ib === false) ? 999 : $ib;

            if ($ia !== $ib) {

                return $ia < $ib ? -1 : 1;

            }

    

            // Provinsi

            $pa = isset($a['provinsi']) ? $a['provinsi'] : '';

            $pb = isset($b['provinsi']) ? $b['provinsi'] : '';

            $cmp = strcmp($pa, $pb);

            if ($cmp !== 0) return $cmp;

    

            // Kota

            $ka = isset($a['kota']) ? $a['kota'] : '';

            $kb = isset($b['kota']) ? $b['kota'] : '';

            $cmp = strcmp($ka, $kb);

            if ($cmp !== 0) return $cmp;

    

            // Status (Existing dulu, Prospek setelahnya)

            $sa = ($a['status_customer'] === 'Existing') ? 0 : 1;

            $sb = ($b['status_customer'] === 'Existing') ? 0 : 1;

            if ($sa !== $sb) return $sa - $sb;

    

            // Customer

            return strcmp($a['customer'], $b['customer']);

        });

    

        // Kembalikan ke collection agar tetap kompatibel dengan Blade

        $data = collect($data);

    

        // Label zona (untuk tampilan PDF)

        $zoneLabels = [

            'JABODETABEK' => 'ZONA 1 : JABODETABEK',

            'JABAR' => 'ZONA 2 : JABAR',

            'JATENG - JATIM' => 'ZONA 3 : JATENG - JATIM',

            'SUMATRA' => 'ZONA 4 : SUMATERA',

            'BALI - KALIMANTAN - SULAWESI' => 'ZONA 5 : BALI - KALIMANTAN - SULAWESI',

            'ZONA LAIN' => 'ZONA LAIN'

        ];

    

        // --- Generate PDF

        $pdf = \PDF::loadView('report.doctor.market-list-pdf', compact('data', 'zoneLabels'));

        return $pdf->stream('List_Market_Report.pdf');

    }

    

    public function samplingReport(Request $request)

    {

        $storeId = $request->get('store_id');

    

        try {

            $client = new Client();

            $response = $client->request('GET', 'https://sys-af.lsfragrance.id/api/sampling/headers', [

                'headers' => [

                    'Accept' => 'application/json',

                    // Jika API perlu token, bisa ditambahkan:

                    // 'Authorization' => 'Bearer ' . $token,

                ],

                'timeout' => 10, // opsional, dalam detik

            ]);

    

            $result = json_decode($response->getBody(), true);

    

            if ($result['status']) {

                $data = $result['data'];

    

                if ($storeId && $storeId !== 'all') {

                    $data = collect($data)->filter(function ($item) use ($storeId) {

                        return strtolower($item['pic']) === strtolower($storeId);

                    })->values()->all();

                }

    

                return view('report.doctor.sampling', compact('data', 'storeId'));

            } else {

                return view('report.doctor.sampling')->with('error', $result['message']);

            }

        } catch (\Exception $e) {

            return view('report.doctor.sampling')->with('error', $e->getMessage());

        }

    }

}