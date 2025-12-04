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
use App\Exports\CustomerNationalExport;
use App\Exports\CustomerNationalStatusExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Pdf;

class FileDoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    const PROSPEK_MODEL = 'App\Master\CustomerProspek';
    
    // âœ… Konstanta Zona
    private const ZONA_LIST = [
        'JABODETABEK',
        'JABAR',
        'JATENG - JATIM',
        'SUMATERA',
        'BALI - KALIMANTAN - SULAWESI'
    ];

    // ============================================================
    // INDEX
    // ============================================================
    public function index(Request $request)
    {
        $allowedOfficers = ['Erick', 'Lindy', 'Kumala', 'Kantor'];

        $data['zona'] = self::ZONA_LIST;
        $data['officers'] = Customer::select('officer')
            ->whereNotNull('officer')
            ->whereIn('officer', $allowedOfficers)
            ->distinct()
            ->orderBy('officer')
            ->get();

        $selectedZona      = $request->get('zona');
        $selectedProvinsi  = $request->get('provinsi');
        $selectedKota      = $request->get('kota');
        $selectedOfficer   = $request->get('officer');

        $locations = $this->getNormalizedLocations();

        // Filter provinsi berdasarkan zona yang dipilih
        $data['provinces'] = [];
        if ($selectedZona && isset($locations[strtoupper($selectedZona)])) {
            $data['provinces'] = array_keys($locations[strtoupper($selectedZona)]);
        } else {
             // Tampilkan semua provinsi jika zona tidak dipilih (opsional)
             foreach ($locations as $zonaData) {
                 $data['provinces'] = array_merge($data['provinces'], array_keys($zonaData));
             }
             $data['provinces'] = array_unique($data['provinces']);
             sort($data['provinces']);
        }


        // Filter kota berdasarkan zona dan provinsi yang dipilih
        $data['cities'] = [];
        if ($selectedZona && $selectedProvinsi && isset($locations[strtoupper($selectedZona)][strtoupper($selectedProvinsi)])) {
            $data['cities'] = $locations[strtoupper($selectedZona)][strtoupper($selectedProvinsi)];
        }

        $data['selectedZona']      = $selectedZona;
        $data['selectedProvince'] = $selectedProvinsi;
        $data['selectedCity']      = $selectedKota;
        $data['selectedOfficer']  = $selectedOfficer;

        return view('report.doctor.index', $data);
    }

    // ============================================================
    // GET LIST MARKET (Lengkap dan Aman)
    // ============================================================
    public function getListMarket(Request $request)
    {
        $officer  = $request->get('officer');
        $zona     = $request->get('zona');
        $provinsi = $request->get('provinsi');
        $kota     = $request->get('kota');
    
        if (!$officer) {
            return response()->json([
                'mode' => 'nasional',
                'existing' => [],
                'prospek' => []
            ]);
        }
    
        $officerLower = strtolower(trim($officer));

        // -------------------------------
        // Tentukan filter officer
        // -------------------------------
        $officerFilter = [$officerLower];
    
        // Jika user pilih 'kantor', tambahkan 'nia' sebagai alias
        if ($officerLower === 'kantor') {
            $officerFilter[] = 'nia';
        }
        
        $mode = (!$zona) ? 'nasional' : 'filtered';
    
        // -------------------------------
        // Existing Customers
        // -------------------------------
        $existingQuery = DB::table('master_customer_other_addresses as s')
            ->leftJoin('master_customers as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('master_customer_categories as cat', 'cat.id', '=', 'c.category_id')
            // ->whereRaw('LOWER(TRIM(s.officer)) = ?', [$officerLower])
            ->whereIn(DB::raw('LOWER(TRIM(s.officer))'), $officerFilter)
            ->where('c.status', 1)
            ->whereNull('s.deleted_at');
    
        if ($zona) $existingQuery->whereRaw('UPPER(TRIM(s.zone)) LIKE ?', ['%' . strtoupper(trim($zona)) . '%']);
        if ($provinsi) $existingQuery->whereRaw('UPPER(TRIM(s.text_provinsi)) LIKE ?', ['%' . strtoupper(trim($provinsi)) . '%']);
        if ($kota) $existingQuery->whereRaw('UPPER(TRIM(s.text_kota)) LIKE ?', ['%' . strtoupper(trim($kota)) . '%']);
    
        $existing = $existingQuery->select(
            DB::raw("'Existing' as status"),
            DB::raw("'E' as labels"),
            's.id',
            's.name',
            's.officer',
            DB::raw('COALESCE(s.pengajuan, "KANTOR") as pengajuan'), // cek null
            DB::raw('UPPER(TRIM(s.zone)) as zona'),
            DB::raw('UPPER(TRIM(s.text_provinsi)) as text_provinsi'),
            DB::raw('UPPER(TRIM(s.text_kota)) as text_kota'),
            DB::raw('COALESCE(cat.name, "Tanpa Kategori") as kategori')
        )->get();
    
        // -------------------------------
        // Prospek Customers
        // -------------------------------
        $prospekQuery = DB::table('master_customer_other_addresses_prospek as s')
            ->leftJoin('master_customers_prospek as c', 'c.id', '=', 's.customer_id')
            ->leftJoin('master_customer_categories as cat', 'cat.id', '=', 'c.category_id')
            ->whereRaw('LOWER(TRIM(s.officer)) = ?', [$officerLower])
            ->where('c.status', 1)
            ->whereNull('s.deleted_at');
    
        if ($zona) $prospekQuery->whereRaw('UPPER(TRIM(s.zone)) = ?', [strtoupper(trim($zona))]);
        if ($provinsi) $prospekQuery->whereRaw('UPPER(TRIM(s.text_provinsi)) = ?', [strtoupper(trim($provinsi))]);
        if ($kota) $prospekQuery->whereRaw('UPPER(TRIM(s.text_kota)) = ?', [strtoupper(trim($kota))]);
    
        $prospek = $prospekQuery->select(
            DB::raw("'Prospek' as status"),
            DB::raw("'P' as labels"),
            's.id',
            's.name',
            's.officer',
            DB::raw("CASE s.pengajuan 
                WHEN 1 THEN 'KANTOR'
                WHEN 2 THEN 'ERICK'
                WHEN 3 THEN 'LINDY'
                WHEN 4 THEN 'KUMALA'
                WHEN 5 THEN 'NIA'
                ELSE '-' END as pengajuan"),
            DB::raw('UPPER(TRIM(s.zone)) as zona'),
            DB::raw('UPPER(TRIM(s.text_provinsi)) as text_provinsi'),
            DB::raw('UPPER(TRIM(s.text_kota)) as text_kota'),
            DB::raw('COALESCE(cat.name, "Tanpa Kategori") as kategori')
        )->get();
    
        return response()->json([
            'mode' => $mode,
            'existing' => $existing,
            'prospek' => $prospek
        ]);
    }

    
    // ============================================================
    // AJAX: GET PROVINSI BY ZONA (Lengkap dan Aman)
    // ============================================================
    public function getProvinsiByZona(Request $request)
    {
        $zona = $request->get('zona');
        if (!$zona) return response()->json([]);
    
        $locations = $this->getNormalizedLocations(); // pastikan key & value sudah uppercase & trim
        $provinsi = isset($locations[strtoupper(trim($zona))])
            ? array_keys($locations[strtoupper(trim($zona))])
            : [];
    
        sort($provinsi);
        return response()->json($provinsi);
    }
    
    // ============================================================
    // AJAX: GET KOTA BY PROVINSI (Lengkap dan Aman)
    // ============================================================
    public function getKotaByProvinsi(Request $request)
    {
        $zona = $request->get('zona');
        $provinsi = $request->get('provinsi');
        if (!$zona || !$provinsi) return response()->json([]);
    
        $locations = $this->getNormalizedLocations(); // pastikan key & value sudah uppercase & trim
    
        $zonaKey = strtoupper(trim($zona));
        $provKey = strtoupper(trim($provinsi));
    
        $kota = isset($locations[$zonaKey][$provKey]) ? $locations[$zonaKey][$provKey] : [];
        sort($kota);
    
        return response()->json($kota);
    }


    // ============================================================
    // ðŸ”§ HELPER UTAMA UNTUK NORMALISASI DATA
    // ============================================================
    private function getNormalizedLocations()
    {
        // ... (Fungsi ini tidak berubah, dan diasumsikan sudah bekerja dengan benar)
        $tables = [
            'master_customer_other_addresses',
            'master_customer_other_addresses_prospek'
        ];

        $all = [];

        foreach ($tables as $table) {
            $query = DB::table($table)
                ->select(
                    DB::raw('LOWER(TRIM(zone)) AS zona'),
                    DB::raw('LOWER(TRIM(text_provinsi)) AS provinsi'),
                    DB::raw('LOWER(TRIM(text_kota)) AS kota')
                )
                ->whereNotNull('zone')
                ->whereNotNull('text_provinsi')
                ->whereNotNull('text_kota');

            $all = array_merge($all, $query->get()->toArray());
        }

        $locations = [];

        foreach ($all as $item) {
            $zona = strtoupper(trim($item->zona));
            $prov = strtoupper(trim($item->provinsi));
            $kota = strtoupper(trim($item->kota));

            if (!isset($locations[$zona])) {
                $locations[$zona] = [];
            }
            if (!isset($locations[$zona][$prov])) {
                $locations[$zona][$prov] = [];
            }
            if (!in_array($kota, $locations[$zona][$prov])) {
                $locations[$zona][$prov][] = $kota;
            }
        }

        return $locations;
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
                                // Pastikan created_at dikonversi ke WIB
                                $createdAtWIB = isset($task['created_at']) 
                                    ? Carbon::parse($task['created_at'])
                                        ->timezone('Asia/Jakarta')
                                        ->format('Y-m-d\TH:i:sP') // format ISO 8601 + offset
                                    : null;
                        
                                $taskList[] = [
                                    'keterangan'  => $task['keterangan_task'],
                                    'status'      => $task['status'],
                                    'type_agenda' => $task['type_agenda'],
                                    'created_at'  => $createdAtWIB,
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
    
    public function agendaCalendarData(Request $request)
    {
        $officer = strtolower($request->get('officer'));
        $start   = $request->get('start');
        $end     = $request->get('end');
    
        $finalData = [];
    
        try {
            $client = new Client([
                'base_uri' => 'https://sys-af.lsfragrance.id',
                'timeout'  => 10.0,
            ]);
    
            $response = $client->request('GET', '/api/tasks');
    
            if ($response->getStatusCode() === 200) {
                $json = json_decode($response->getBody(), true);
                $allAgendas = $json['data'] ?? [];
    
                // Filter PIC + tanggal
                $filtered = collect($allAgendas)->filter(function ($item) use ($officer, $start, $end) {
                    if (!isset($item['pic'])) return false;
                    if (strtolower($item['pic']) !== $officer) return false;
                    if (empty($item['tanggal'])) return false;
    
                    $tanggal = Carbon::parse($item['tanggal'])->format('Y-m-d');
                    return $tanggal >= $start && $tanggal <= $end;
                });
    
                foreach ($filtered as $agenda) {
    
                    $taskList = [];
    
                    if (!empty($agenda['tasks']) && is_array($agenda['tasks'])) {
                        foreach ($agenda['tasks'] as $task) {
    
                            // ⛔ Hanya type_agenda = 0
                            if (($task['type_agenda'] ?? null) != 0) continue;
    
                            if (!empty($task['keterangan_task'])) {
                                $createdAtWIB = isset($task['created_at'])
                                    ? Carbon::parse($task['created_at'])
                                        ->timezone('Asia/Jakarta')
                                        ->format('Y-m-d\TH:i:sP') // ISO 8601 + offset
                                    : null;
    
                                $taskList[] = [
                                    'keterangan'  => $task['keterangan_task'],
                                    'status'      => $task['status'] ?? null,
                                    'type_agenda' => $task['type_agenda'],
                                    'created_at'  => $createdAtWIB,
                                ];
                            }
                        }
                    }
    
                    // Hanya tambahkan agenda jika masih ada task type_agenda=0
                    if (count($taskList) > 0) {
                        $finalData[] = [
                            'pic_key'  => strtolower($agenda['pic']),
                            'tanggal'  => $agenda['tanggal'],
                            'judul'    => "Agenda (" . count($taskList) . " Task)",
                            'tasks'    => $taskList
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error("AgendaCalendar API Error: " . $e->getMessage());
        }
    
        return response()->json([
            'success' => true,
            'data'    => $finalData
        ]);
    }
    
    public function getDoctorVisitsByOfficer($officerId, Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');
    
        try {
            $client = new \GuzzleHttp\Client(['timeout' => 10]);
            $response = $client->request('GET', 'https://sys-af.lsfragrance.id/api/doctor');
    
            if ($response->getStatusCode() !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data kunjungan doctor dari API eksternal'
                ], 500);
            }
    
            $json = json_decode($response->getBody(), true);
            $allVisits = $json['data'] ?? [];
            $filteredResults = collect();
    
            // Filter berdasarkan pic_customer (officer)
            $officerData = collect($allVisits)->filter(function ($item) use ($officerId) {
                return isset($item['pic_customer']) && strcasecmp(trim($item['pic_customer']), trim($officerId)) === 0;
            });
    
            foreach ($officerData as $header) {
                $details = collect($header['detail'] ?? []);
    
                // Filter tanggal jika ada
                if ($startDate && $endDate) {
                    $start = \Carbon\Carbon::parse($startDate)->startOfDay();
                    $end   = \Carbon\Carbon::parse($endDate)->endOfDay();
    
                    $details = $details->filter(function ($detail) use ($start, $end) {
                        $tanggal = $detail['tanggal'] ?? null;
                        if (!$tanggal) return false;
    
                        try {
                            $detailDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($tanggal))->startOfDay();
                            return $detailDate->between($start, $end);
                        } catch (\Exception $e) {
                            \Log::warning("Invalid tanggal format: " . $tanggal);
                            return false;
                        }
                    });
                }
    
                // 🔹 Loop detail visit
                $details->each(function ($detail) use ($header, $filteredResults) {
                    $customerId   = $header['customer_id'] ?? null;
                    $customerName = $header['customer_name'] ?? null;
    
                    // 🔍 Cek ke CustomerProspek terlebih dahulu
                    $prospect = \App\Master\CustomerProspek::where('id', $customerId)
                        // ->orWhere('name', $customerName)
                        ->first();
    
                    // Jika tidak ada, baru cek ke Customer
                    $customer = null;
                    if (!$prospect) {
                        $customer = \App\Master\Customer::where('id', $customerId)
                            // ->orWhere('name', $customerName)
                            ->first();
                    }
    
                    // Pilih sumber data yang ditemukan
                    $source = $prospect ?? $customer;
    
                    $filteredResults->push([
                        'tanggal'         => $detail['tanggal'] ?? '-',
                        'created_at'      => $detail['created_at'] ?? '-',
                        'customer'        => $header['customer_name'] ?? 'N/A',
                        'kegiatan'        => $detail['kegiatan'] ?? '-',
                        'kegiatan_text'       => $detail['kegiatan_text'] ?? $detail['keterangan'] ?? '-',
                        'produk'          => $detail['produk'] ?? '-',
                        'respon'          => $detail['respon'] ?? '-',
                        'customer_id'     => $header['customer_id'] ?? 'N/A',
                        'customer_status' => $prospect
                            ? 'Prospek'
                            : ($customer ? 'Customer' : 'Tidak Dikenal'),
                        'text_kota'       => $source->text_kota ?? '-',
                        'text_provinsi'   => $source->text_provinsi ?? '-',
                    ]);
                });
            }
    
            return response()->json([
                'success' => true,
                'data'    => $filteredResults->values()->toArray(),
            ]);
        } catch (\Exception $e) {
            \Log::error("Doctor Visits API Failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data doctor. Silakan coba lagi.'
            ]);
        }
    }
    
    public function marketListPdf(Request $request)
    {
    try {

        // -----------------------------------------------------------
        // 1. VALIDASI INPUT
        // -----------------------------------------------------------
        $officerId = $request->officer_id ?? $request->officer;

        if (!$officerId) {
            return back()->with('error', 'Silakan pilih Officer terlebih dahulu.');
        }

        $zona     = $request->zona;
        $provinsi = $request->provinsi;
        $kota     = $request->kota;

        // -----------------------------------------------------------
        // 2. DATA PROSPEK
        // -----------------------------------------------------------
        $prospek = DB::table('master_customer_other_addresses_prospek as p')
            ->leftJoin('master_customers_prospek as s', 's.id', '=', 'p.customer_id')
            ->leftJoin('master_customer_categories as c', 'c.id', '=', 's.category_id')
            ->where('s.status', 1)
            ->whereNull('p.deleted_at')
            ->when($officerId, function ($q) use ($officerId) {
                return $q->where('p.officer', $officerId);
            })
            ->when($zona, function ($q) use ($zona) {
                return $q->where('p.zone', $zona);
            })
            ->when($provinsi, function ($q) use ($provinsi) {
                return $q->where('p.text_provinsi', $provinsi);
            })
            ->when($kota, function ($q) use ($kota) {
                return $q->where('p.text_kota', $kota);
            })
            ->select(
                DB::raw("COALESCE(p.zone, 'ZONA LAIN') as zona"),
                DB::raw("COALESCE(p.text_provinsi, 'TIDAK ADA PROVINSI') as provinsi"),
                DB::raw("COALESCE(p.text_kota, 'TIDAK ADA KOTA') as kota"),
                'p.name as customer',
                DB::raw("
                    CASE p.pengajuan
                        WHEN 1 THEN 'KANTOR'
                        WHEN 2 THEN 'ERICK'
                        WHEN 3 THEN 'LINDY'
                        WHEN 4 THEN 'KUMALA'
                        WHEN 5 THEN 'NIA'
                        ELSE '-'
                    END as pengajuan
                "),
                'c.name as mapping',
                's.pic',
                'p.officer'
            )
            ->get()
            ->map(function ($item) {
                return [
                    'zona'            => strtoupper(trim($item->zona)),
                    'provinsi'        => strtoupper(trim($item->provinsi)),
                    'kota'            => strtoupper(trim($item->kota)),
                    'customer'        => $item->customer,
                    'pengajuan'       => $item->pengajuan ?: '-',
                    'mapping'         => $item->mapping ?: '-',
                    'pic'             => $item->pic ?: '-',
                    'officer'         => $item->officer ?: '-',
                    'status_customer' => 'Prospek',
                    'labels'          => 'P',
                ];
            });

        // -----------------------------------------------------------
        // 3. DATA EXISTING
        // -----------------------------------------------------------
        $existing = DB::table('master_customer_other_addresses as e')
            ->leftJoin('master_customers as s', 's.id', '=', 'e.customer_id')
            ->leftJoin('master_customer_categories as c', 'c.id', '=', 's.category_id')
            ->where('s.status', 1)
            ->whereNull('e.deleted_at')
            ->when($officerId, function ($q) use ($officerId) {
                return $q->where('e.officer', $officerId);
            })
            ->when($zona, function ($q) use ($zona) {
                return $q->where('e.zone', $zona);
            })
            ->when($provinsi, function ($q) use ($provinsi) {
                return $q->where('e.text_provinsi', $provinsi);
            })
            ->when($kota, function ($q) use ($kota) {
                return $q->where('e.text_kota', $kota);
            })
            ->select(
                DB::raw("COALESCE(e.zone, 'ZONA LAIN') as zona"),
                DB::raw("COALESCE(e.text_provinsi, 'TIDAK ADA PROVINSI') as provinsi"),
                DB::raw("COALESCE(e.text_kota, 'TIDAK ADA KOTA') as kota"),
                'e.name as customer',
                DB::raw("'KANTOR' as pengajuan"),
                'c.name as mapping',
                's.pic',
                'e.officer'
            )
            ->get()
            ->map(function ($item) {
                return [
                    'zona'            => strtoupper(trim($item->zona)),
                    'provinsi'        => strtoupper(trim($item->provinsi)),
                    'kota'            => strtoupper(trim($item->kota)),
                    'customer'        => $item->customer,
                    'pengajuan'       => 'KANTOR',
                    'mapping'         => $item->mapping ?: '-',
                    'pic'             => $item->pic ?: '-',
                    'officer'         => $item->officer ?: '-',
                    'status_customer' => 'Existing',
                    'labels'          => 'E',
                ];
            });

        // -----------------------------------------------------------
        // 4. MERGING
        // -----------------------------------------------------------
        $data = $existing->concat($prospek)->values()->all();

        // -----------------------------------------------------------
        // 5. SORTING
        // -----------------------------------------------------------
        $zoneOrder = [
            'JABODETABEK',
            'JABAR',
            'JATENG - JATIM',
            'SUMATRA',
            'BALI - KALIMANTAN - SULAWESI'
        ];

        usort($data, function ($a, $b) use ($zoneOrder) {
            $ia = array_search($a['zona'], $zoneOrder);
            $ib = array_search($b['zona'], $zoneOrder);

            if ($ia === false) $ia = 999;
            if ($ib === false) $ib = 999;

            if ($ia !== $ib) return $ia - $ib;
            if ($a['provinsi'] !== $b['provinsi']) return strcmp($a['provinsi'], $b['provinsi']);
            if ($a['kota'] !== $b['kota']) return strcmp($a['kota'], $b['kota']);

            return strcmp($a['customer'], $b['customer']);
        });

        $data = collect($data);

        // -----------------------------------------------------------
        // 6. LABEL ZONA
        // -----------------------------------------------------------
        $zoneLabels = [
            'JABODETABEK'                  => 'ZONA 1 : JABODETABEK',
            'JABAR'                        => 'ZONA 2 : JABAR',
            'JATENG - JATIM'               => 'ZONA 3 : JATENG - JATIM',
            'SUMATRA'                      => 'ZONA 4 : SUMATERA',
            'BALI - KALIMANTAN - SULAWESI' => 'ZONA 5 : BALI - KALIMANTAN - SULAWESI',
            'ZONA LAIN'                    => 'ZONA LAIN',
        ];

        // -----------------------------------------------------------
        // 7. OFFICER & TANGGAL
        // -----------------------------------------------------------
        $officerName  = $data->first()['officer'] ?? '-';
        $tanggalCetak = date('d/m/Y');

        // -----------------------------------------------------------
        // 8. GENERATE PDF (DomPDF only)
        // -----------------------------------------------------------
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.doctor.market-list-pdf', [
            'data'         => $data,
            'zoneLabels'   => $zoneLabels,
            'zona'         => $zona,
            'provinsi'     => $provinsi,
            'kota'         => $kota,
            'officerName'  => $officerName,
            'tanggalCetak' => $tanggalCetak,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('List_Market_Report.pdf');

    } catch (\Exception $e) {
        \Log::error('MarketList PDF Error: ' . $e->getMessage());
        return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
    }
}

    public function downloadNationalExcel()
    {
        $fileName = 'customer_nasional_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new CustomerNationalExport(), $fileName);
    }
    
    public function downloadNationalStatusExcel()
    {
        $fileName = 'customer_nasional_status_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new CustomerNationalStatusExport(), $fileName);
    }

    public function exportDoctorPDF($officerId, Request $request)
    {
        $tanggal = $request->get('tanggal');
    
        // 🔹 Ambil data kunjungan berdasarkan officer dan tanggal
        $data = $this->getDoctorVisitsByOfficer($officerId, new Request([
            'start_date' => $tanggal,
            'end_date' => $tanggal,
        ]));
    
        $json = $data->getData(true);
        $visits = $json['data'] ?? [];
    
        // 🔍 Loop pengecekan asal customer (CustomerProspek / Customer)
        foreach ($visits as &$visit) {
            $customerId = $visit['customer_id'] ?? null;
            // dd($customerId);
            $customerName = $visit['customer'] ?? null;
    
            // Cari di CustomerProspek dulu
            $prospect = CustomerProspek::where('id', $customerId)
                ->first();
    
            // Jika tidak ada, baru cari di Customer
            $customer = null;
            if (!$prospect) {
                $customer = Customer::where('id', $customerId)
                    ->first();
                // dd($customer);
            }
    
            // Pilih sumber data yang ditemukan
            $source = $prospect ?? $customer;
    
            $visit['customer_status'] = $prospect
                ? 'Prospek'
                : ($customer ? 'Customer' : 'Tidak Dikenal');
    
            $visit['text_kota'] = $source->text_kota ?? '-';
            $visit['text_provinsi'] = $source->text_provinsi ?? '-';
            
            // dd($visit['text_kota']);
        }
        
        // 🔸 Urutkan data berdasarkan tanggal ASC
        usort($visits, function ($a, $b) {
            return strtotime($a['created_at']) <=> strtotime($b['created_at']);
        });
    
        // 🧾 Generate PDF
        $pdf = Pdf::loadView('report.doctor.doctor_daily', [
            'tanggal' => $tanggal,
            'officerId' => $officerId,
            'visits' => $visits,
        ])
        ->setPaper('A4', 'landscape'); // Ini sudah benar untuk Dompdf
    
        return $pdf->stream("FollowUp_{$officerId}_{$tanggal}.pdf");
    }
    
    public function exportDoctorPDFAll($officerId, Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');
    
        // 🔹 Ambil semua data dalam periode
        $data = $this->getDoctorVisitsByOfficer($officerId, new Request([
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ]));
    
        $json = $data->getData(true);
        $visits = $json['data'] ?? [];
    
        // 🔍 Loop untuk melengkapi data kota/provinsi
        foreach ($visits as &$visit) {
            $customerId = $visit['customer_id'] ?? null;
            $customerName = $visit['customer'] ?? null;
    
            $prospect = CustomerProspek::where('id', $customerId)
                ->orWhere('name', $customerName)
                ->first();
    
            $customer = null;
            if (!$prospect) {
                $customer = Customer::where('id', $customerId)
                    ->orWhere('name', $customerName)
                    ->first();
            }
    
            $source = $prospect ?? $customer;
            $visit['customer_status'] = $prospect
                ? 'Prospek'
                : ($customer ? 'Customer' : 'Tidak Dikenal');
    
            // 💡 PERBAIKAN FATAL ERROR DI SINI
            if ($source) {
                // Jika $source adalah objek, baru akses propertinya
                $visit['text_kota'] = $source->text_kota ?? '-';
                $visit['text_provinsi'] = $source->text_provinsi ?? '-';
            } else {
                // Jika $source null, berikan nilai default
                $visit['text_kota'] = '-';
                $visit['text_provinsi'] = '-';
            }
        }
    
        // 🔸 Urutkan data berdasarkan tanggal ASC
        usort($visits, function ($a, $b) {
            return strtotime($a['created_at']) <=> strtotime($b['created_at']);
        });
    
        // 🧾 Generate PDF ALL
        $pdf = Pdf::loadView('report.doctor.doctor_all', [
            'startDate'  => $startDate,
            'endDate'    => $endDate,
            'officerId'  => $officerId,
            'visits'     => $visits,
        ])
        ->setPaper('A4', 'landscape'); // Ini sudah benar untuk Dompdf
    
        $periode = str_replace(['-', ' '], '_', "{$startDate}_to_{$endDate}");
        return $pdf->stream("FollowUp_All_{$officerId}_{$periode}.pdf");
    }
    
    // public function getSamplingQuotation(Request $request)
    // {
    //     $officerId = $request->get('officer_id');
    //     $startDate = $request->get('start_date')
    //         ? $request->get('start_date') . ' 00:00:00'
    //         : null;
        
    //     $endDate = $request->get('end_date')
    //         ? $request->get('end_date') . ' 23:59:59'
    //         : null;

    //     try {
    //         $client = new Client([
    //             'verify' => false, // jika SSL belum tersertifikasi
    //             'timeout' => 10,
    //         ]);

    //         // 🔹 API endpoint dari Project A
    //         $apiUrl = "https://sys-af.lsfragrance.id/api/sampling/headers";

    //         $response = $client->get($apiUrl, [
    //             'query' => [
    //                 'officer_id' => $officerId,
    //                 'start_date' => $startDate,
    //                 'end_date' => $endDate,
    //             ]
    //         ]);

    //         $body = json_decode($response->getBody(), true);

    //         return response()->json([
    //             'success' => true,
    //             'data' => $body['data'] ?? [],
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal mengambil data API',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    public function getSamplingQuotation(Request $request)
    {
        $officerId = $request->get('officer_id');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');
    
        // Pastikan tanggal tidak kosong dari FE
        if (!$startDate || !$endDate) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal filter tidak boleh kosong',
            ], 422);
        }
    
        // Convert ke format full-day ISO 8601
        $startDate = $startDate . ' 00:00:00';
        $endDate   = $endDate   . ' 23:59:59';
    
        // \Log::info('FILTER DATE READY SEND:', [
        //     'officer_id' => $officerId,
        //     'start_date' => $startDate,
        //     'end_date'   => $endDate
        // ]);
    
        try {
            $client = new Client([
                'verify' => false,
                'timeout' => 15,
            ]);
    
            // Perhatikan! Jangan pakai array_filter agar tanggal tidak hilang
            $params = [
                'officer_id' => $officerId,
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ];
    
            $response = $client->get("https://sys-af.lsfragrance.id/api/sampling/headers", [
                'query' => $params
            ]);
    
            $body = json_decode($response->getBody(), true);
    
            return response()->json([
                'success' => true,
                'data' => $body['data'] ?? [],
            ]);
        } catch (\Exception $e) {
    
            \Log::error("ERROR API SAMPLING:", [
                'msg' => $e->getMessage()
            ]);
    
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data API',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    
    public function getDoctorMarket(Request $request)
    {
        $officerId = $request->officer_id ?? $request->officer;
        $startDate = $request->start_date;
        $endDate   = $request->end_date;
        
        // dd($startDate, $endDate);
    
        if (!$officerId) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan pilih Officer terlebih dahulu.'
            ], 400);
        }
    
        try {
            // 1. Ambil data kunjungan doctor dari API eksternal
            $client = new \GuzzleHttp\Client(['timeout' => 10]);
            $response = $client->request('GET', 'https://sys-af.lsfragrance.id/api/doctor');
    
            if ($response->getStatusCode() !== 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data kunjungan doctor dari API eksternal'
                ], 500);
            }
    
            $json = json_decode($response->getBody(), true);
            $allVisits = $json['data'] ?? [];
            $filteredResults = collect();
    
            // Filter officer
            $officerData = collect($allVisits)->filter(function ($item) use ($officerId) {
                return isset($item['pic_customer']) && strcasecmp(trim($item['pic_customer']), trim($officerId)) === 0;
            });
    
            foreach ($officerData as $header) {
                $details = collect($header['detail'] ?? []);
    
                // Filter tanggal
                if ($startDate && $endDate) {
                    $start = Carbon::parse($startDate)->startOfDay();
                    $end   = Carbon::parse($endDate)->endOfDay();
                    $details = $details->filter(function ($detail) use ($start, $end) {
                        if (!isset($detail['tanggal'])) return false;
                        try {
                            $detailDate = Carbon::createFromFormat('Y-m-d', trim($detail['tanggal']))->startOfDay();
                            return $detailDate->between($start, $end);
                        } catch (\Exception $e) {
                            return false;
                        }
                    });
                }
    
                foreach ($details as $detail) {
                    $customerId = $header['customer_id'] ?? null;
    
                    $prospect = \App\Master\CustomerProspek::find($customerId);
                    $customer = $prospect ? null : \App\Master\Customer::find($customerId);
                    $source = $prospect ?? $customer;
    
                    // Query DB untuk Customer & Prospek
                    $existing = DB::table('master_customer_other_addresses as e')
                        ->leftJoin('master_customers as s', 's.id', '=', 'e.customer_id')
                        ->leftJoin('master_customer_categories as c', 'c.id', '=', 's.category_id')
                        ->where('s.status', 1)
                        ->where('e.officer', $officerId)
                        ->whereIn('e.customer_id', [$customerId])
                        ->select(
                            'e.name as customer',
                            DB::raw("'EXISTING' as customer_status"),
                            'e.customer_id',
                            DB::raw('UPPER(TRIM(e.text_provinsi)) as text_provinsi'),
                            DB::raw('UPPER(TRIM(e.text_kota)) as text_kota'),
                            DB::raw('COALESCE(c.name, "-") as kategori_db'),
                            DB::raw('COALESCE(e.pengajuan, "KANTOR") as pengajuan')
                        )
                        ->distinct('e.customer_id')
                        ->first();
    
                    $prospekDb = DB::table('master_customer_other_addresses_prospek as p')
                        ->leftJoin('master_customers_prospek as s', 's.id', '=', 'p.customer_id')
                        ->leftJoin('master_customer_categories as c', 'c.id', '=', 's.category_id')
                        ->where('s.status', 1)
                        ->whereNull('p.deleted_at')
                        ->where('p.officer', $officerId)
                        ->whereIn('p.customer_id', [$customerId])
                        ->select(
                            'p.name as customer',
                            DB::raw("'PROSPEK' as customer_status"),
                            'p.customer_id',
                            DB::raw('UPPER(TRIM(p.text_provinsi)) as text_provinsi'),
                            DB::raw('UPPER(TRIM(p.text_kota)) as text_kota'),
                            DB::raw('COALESCE(c.name, "-") as kategori_db'),
                            DB::raw("CASE p.pengajuan 
                                WHEN 1 THEN 'KANTOR'
                                WHEN 2 THEN 'ERICK'
                                WHEN 3 THEN 'LINDY'
                                WHEN 4 THEN 'KUMALA'
                                WHEN 5 THEN 'NIA'
                                ELSE '-' END as pengajuan")
                        )
                        ->distinct('e.customer_id')
                        ->first();
    
                    $kategoriDb = $existing->kategori_db ?? ($prospekDb->kategori_db ?? '-');
                    $pengajuanDB = $existing->pengajuan ?? ($prospekDb->pengajuan ?? '-');
    
                    $filteredResults->push([
                        'tanggal'         => $detail['tanggal'] ?? '-',
                        'created_at'      => $detail['created_at'] ?? '-',
                        'customer'        => $header['customer_name'] ?? 'N/A',
                        'kegiatan'        => $detail['kegiatan'] ?? '-',
                        'deskripsi'       => $detail['kegiatan_text'] ?? $detail['keterangan'] ?? '-',
                        'produk'          => $detail['produk'] ?? '-',
                        'respon'          => $detail['respon'] ?? '-',
                        'customer_id'     => $customerId ?? 'N/A',
                        'customer_status' => $prospect ? 'Prospek' : ($customer ? 'Customer' : 'Tidak Dikenal'),
                        'text_kota'       => $source->text_kota ?? '-',
                        'text_provinsi'   => $source->text_provinsi ?? '-',
                        'kategori_db'     => $kategoriDb,
                        'pengajuan'       => $pengajuanDB,
                    ]);
                }
            }
    
            return response()->json([
                'success' => true,
                'data'    => $filteredResults->values(),
            ]);
    
        } catch (\Exception $e) {
            \Log::error("Doctor Market Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data doctor market. Silakan coba lagi.'
            ], 500);
        }
    }
    
    public function pdfAllAgenda(Request $request)
    {
        $officer = strtolower($request->officer);
        $start   = $request->start;
        $end     = $request->end;
    
        // Ambil data dari fungsi API yang sama
        $api = app()->call('App\Http\Controllers\Report\FileDoctorController@agendaCalendarData', [
            'request' => $request
        ]);
    
        $json = $api->getData(true);
    
        $data = collect($json['data'])
            ->sortBy('tanggal')
            ->groupBy('tanggal');
    
        $pdf = \PDF::loadView('report.doctor.pdf_all', [
            'data'    => $data,
            'start'   => $start,
            'end'     => $end,
            'officer' => $officer
        ])->setPaper('A4', 'portrait');
    
        return $pdf->stream("Agenda-{$start}-{$end}.pdf");
    }

    public function pdfPerDateAgenda($tanggal, Request $request)
    {
        $officer = strtolower($request->officer);
    
        // Pakai API dengan START = END = tanggal yang dipilih
        $req = new Request([
            'officer' => $officer,
            'start'   => $tanggal,
            'end'     => $tanggal
        ]);
    
        $api = app()->call('App\Http\Controllers\Report\FileDoctorController@agendaCalendarData', [
            'request' => $req
        ]);
    
        $json = $api->getData(true);
    
        $data = collect($json['data']);
    
        $pdf = \PDF::loadView('report.doctor.pdf_date', [
            'data'    => $data,
            'tanggal' => $tanggal,
            'officer' => $officer
        ])->setPaper('A4', 'portrait');
    
        return $pdf->stream("Agenda-{$tanggal}.pdf");
    }
    
    public function preview(Request $r)
    {
        $payload = [
            'type'  => $r->type,
            'sub'   => $r->sub,
            'start' => $r->start,
            'end'   => $r->end,
            'ao'    => $r->officer ?? null,
        ];

        $client = new Client([
            'timeout'  => 180.0, // Timeout dalam detik (3 menit)
        ]);
        
        try {
            $res = $client->post('https://cat-liked-silkworm.ngrok-free.app/api/request/report', [
                'json' => $payload // Mengirim payload sebagai JSON
            ]);
    
            if ($res->getStatusCode() !== 200) {
                // Jika Program B mengembalikan status non-200
                $errorMessage = json_decode($res->getBody(), true)['message'] ?? 'Gagal generate report (Backend Error)';
                return response($errorMessage, $res->getStatusCode());
            }
    
            // Ambil respons dan decode
            $body = $res->getBody()->getContents();
            $responseData = json_decode($body, true);
            
            $pdfBase64 = $responseData['pdf_base64'];
            $pdfBinary = base64_decode($pdfBase64);
    
            return response($pdfBinary)
                ->header('Content-Type', 'application/pdf');
    
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // Error koneksi (misalnya ngrok mati, timeout)
            \Log::error('Guzzle Connect Error', ['error' => $e->getMessage()]);
            return response("Error Koneksi ke Program B: {$e->getMessage()}", 503);
        } catch (\Exception $e) {
            // Error umum
            \Log::error('Guzzle Generic Error', ['error' => $e->getMessage()]);
            return response("Terjadi kesalahan saat memproses laporan: {$e->getMessage()}", 500);
        }
    }
}