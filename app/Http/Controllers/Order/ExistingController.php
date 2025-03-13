<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Master\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Auth;
use PDF;
use DB;

class ExistingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Melindungi semua method di controller ini
    }

    public function get_product_pack(Request $request)
    {
        if ($request->ajax()) {
                $data = [];
                
                $product = DB::table('master_products')
                        ->where('master_products.brand_name', $request->id)
                        ->where('master_products.status', 1)
                        ->where('master_products_packaging.condition', 0)
                        ->leftJoin('master_products_packaging', 'master_products.id', '=', 'master_products_packaging.product_id')
                        ->leftJoin('master_packaging', 'master_products_packaging.packaging_id', '=', 'master_packaging.id')
                        ->leftJoin('master_product_types', 'master_products_packaging.type_id', '=', 'master_product_types.id')
                        ->leftJoin('master_warehouses', 'master_products_packaging.warehouse_id', '=', 'master_warehouses.id')
                        ->select('master_products_packaging.id as id' ,
                                    'master_products_packaging.code as ProductCode', 
                                    'master_products_packaging.name as productName', 
                                    'master_products_packaging.price as productPrice', 
                                    'master_packaging.id as  productPackagingID', 
                                    'master_packaging.pack_name as productPackaging', 
                                    'master_warehouses.name as warehouseName',
                                    'master_product_types.name as typeName',
                        )
                        ->get();

                foreach($product as $key){
                    $data[] = [
                        'id' => $key->id,
                        'code' => $key->ProductCode,
                        'name' => $key->productName,
                        'price' => $key->productPrice,
                        'packName' => $key->productPackaging,
                        'packID' => $key->productPackagingID,
                        'warehouseName' => $key->warehouseName,
                        'typeName' => $key->typeName,
                    ];
                }

                return response()->json(['code' => 200, 'data' => $data]);
        }
    }

    public function search_kontrak(Request $request, $id, $merek)
    {
        // Validasi input request
        $validatedData = $request->validate([
            'q' => 'nullable|string|max:255',
        ]);

        // Additional validation for $id and $merek
        if (!is_numeric($id) || empty($merek)) {
            return response()->json([
                'message' => 'Invalid request data.',
                'errors' => [
                    'id' => 'The ID must be a number.',
                    'merek' => 'The brand name is required.'
                ]
            ], 422);
        }

        try {
            // Query mencari kontrak
            $sales_kontrak = DB::table('penjualan_so_kontrak')
                ->where('penjualan_so_kontrak.status', 2)
                ->where('penjualan_so_kontrak.customer_other_address_id', $id)
                ->where('master_products.brand_name', $merek)
                ->when(!empty($validatedData['q']), function ($query) use ($validatedData) {
                    return $query->where('master_products_packaging.name', 'LIKE', '%' . $validatedData['q'] . '%');
                })
                ->leftJoin('penjualan_so_kontrak_item', 'penjualan_so_kontrak.id', '=', 'penjualan_so_kontrak_item.so_kontrak_id')
                ->leftJoin('master_products_packaging', 'penjualan_so_kontrak_item.product_packaging_id', '=', 'master_products_packaging.id')
                ->leftJoin('master_products', 'master_products.id', '=', 'master_products_packaging.product_id')
                ->leftJoin('penjualan_so_kontrak_log', 'penjualan_so_kontrak.id', '=', 'penjualan_so_kontrak_log.so_kontrak_id')
                ->select(
                    'penjualan_so_kontrak.id',
                    'penjualan_so_kontrak.code AS kontrak_code',
                    'master_products_packaging.code AS product_code',
                    'master_products_packaging.name AS product_name',
                    'penjualan_so_kontrak_item.qty AS product_qty',
                    'penjualan_so_kontrak_item.qty_sent AS product_qty_sent',
                    DB::raw('COALESCE(SUM(penjualan_so_kontrak_log.qty_worked), 0) AS total_qty_worked')
                )
                ->groupBy(
                    'penjualan_so_kontrak.id',
                    'penjualan_so_kontrak.code',
                    'master_products_packaging.code',
                    'master_products_packaging.name',
                    'penjualan_so_kontrak_item.qty',
                    'penjualan_so_kontrak_item.qty_sent'
                )
                ->havingRaw('COALESCE(SUM(penjualan_so_kontrak_log.qty_worked), 0) < penjualan_so_kontrak_item.qty')
                ->get();

            // Format response
            $results = $sales_kontrak->map(function ($row) {
                return [
                    'id' => $row->id,
                    'text' => "{$row->product_code} - {$row->product_name} / ({$row->kontrak_code})",
                    'product_qty' => $row->product_qty,
                    'total_qty_worked' => $row->total_qty_worked
                ];
            });

            return response()->json(['results' => $results], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function get_product_kontrak(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'so_kontrak' => 'required|string',
            ]);

            $sales_kontrak_item = DB::table('penjualan_so_kontrak_item')
                ->where('penjualan_so_kontrak_item.so_kontrak_id', $request->so_kontrak)
                ->leftJoin('master_products_packaging', 'penjualan_so_kontrak_item.product_packaging_id', '=', 'master_products_packaging.id')
                ->leftJoin('master_packaging', 'master_products_packaging.packaging_id', '=', 'master_packaging.id')
                ->leftJoin('penjualan_so_kontrak', 'penjualan_so_kontrak_item.so_kontrak_id', '=', 'penjualan_so_kontrak.id')
                ->select(
                    'master_products_packaging.name AS product_name',
                    'master_products_packaging.code AS product_code',
                    'penjualan_so_kontrak.id AS kontrak_id',
                    'penjualan_so_kontrak_item.price AS product_price',
                    'penjualan_so_kontrak_item.disc_usd AS product_disc',
                    'penjualan_so_kontrak_item.product_packaging_id AS product_id',
                    'master_packaging.id AS packaging_id',
                    'master_packaging.pack_name AS packaging_name'
                )->get();

            $data = $sales_kontrak_item->map(function ($row) {
                return [
                    'product_id' => $row->product_id,
                    'product_code' => $row->product_code,
                    'product_name' => $row->product_name,
                    'product_price' => $row->product_price,
                    'product_disc' => $row->product_disc,
                    'packaging_id' => $row->packaging_id,
                    'packaging_name' => $row->packaging_name,
                    'kontrak_id' => $row->kontrak_id,
                ];
            });

            return response()->json(['code' => 200, 'data' => $data]);
        }

        return response()->json(['code' => 400, 'message' => 'Invalid request.'], 400);
    }

    public static function generateSoAwal(){
        $count = DB::table('penjualan_so')
            ->where('status', '>', 0)
            ->whereYear('created_at',date('Y'))
            ->whereMonth('created_at',date('m'))
            ->get();
                                   
        if(count($count) > 0 ){
            $count = count($count) + 1;

            $code = 'SO-' .date('ym').sprintf('%03d', $count);
        }
        else{
            $code = 'SO-' .date('ym').sprintf('%03d', 1);
        }
        return $code;
    }

    public function index()
    {
        $customers = Customer::get();
        $brand = DB::table('master_brand_lokal')->get();
        
        $so = DB::table('penjualan_so')
            ->where('penjualan_so.type_so', 'nonppn')
            ->where('penjualan_so.created_by', Auth::id())
            ->where('penjualan_so.so_indent', 0)
            ->whereIn('penjualan_so.status', [1, 2, 3, 4])
            ->leftJoin('master_customer_other_addresses', 'penjualan_so.customer_other_address_id', '=', 'master_customer_other_addresses.id')
            ->select(
                'penjualan_so.id', 
                'penjualan_so.so_code', 
                'penjualan_so.code', 
                'penjualan_so.brand_name', 
                'master_customer_other_addresses.name AS customer_name', 
                'master_customer_other_addresses.text_kota AS customer_kota', 
                'penjualan_so.customer_other_address_id AS customer_id', 
                'penjualan_so.created_at', 
                'penjualan_so.sales_id',
                'penjualan_so.created_by',
                'penjualan_so.status'
            )
            ->get()
            ->map(function ($row) {
                return (object) [
                    'id' => $row->id,
                    'so_code' => $row->so_code,
                    'code' => $row->code ?? '',
                    'nota_brand' => $row->brand_name,
                    'customer_name' => $row->customer_name ?? '-',
                    'customer_kota' => $row->customer_kota ?? '-',
                    'so_created_at' => Carbon::parse($row->created_at)->format('d M Y H:i'),
                    'status_so' => $this->mapStatus($row->status),
                    'sales' => $this->mapSales($row->sales_id),
                    'so_created_by' => $this->mapCreatedBy($row->created_by),
                ];
            });

        $data = [
            'customers' => $customers,
            'brand' => $brand,
            'so' => $so,
        ];

        return view('orders.existing.index', $data);
    }

    private function mapStatus($status)
    {
        $statuses = [
            1 => 'AWAL',
            2 => 'LANJUTAN',
            3 => 'REVISI',
            4 => 'TUTUP',
        ];
        return isset($statuses[$status]) ? $statuses[$status] : 'NONE';
    }

    private function mapSales($salesId)
    {
        $sales = [
            1 => 'Lindy',
            2 => 'Alivi',
            3 => 'S.A',
            4 => 'Santi',
            5 => 'Eric',
        ];
        return isset($sales[$salesId]) ? $sales[$salesId] : '-';
    }

    private function mapCreatedBy($createdBy)
    {
        $creators = [
            26 => 'Lindy',
            38 => 'Alivi',
            32 => 'Nia',
            33 => 'Putri',
            34 => 'Santi',
            35 => 'Eric',
            1 => 'Dev',
        ];
        return isset($creators[$createdBy]) ? $creators[$createdBy] : '-';
    }


    public function create(Request $request, $step, $brand, $customer, $type, $indent)
    {
        $data = [
            'step' => $step,
            'brand' => $brand,
            'customer' => $customer,
            'type' => $type,
            'indent' => $indent,
        ];

        return view('orders.existing.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer' => 'required',
            'brand_name' => 'required',
            'type_transaction' => 'required',
        ]);

        // Get customer
            $customerIds = DB::table('master_customer_other_addresses')
            ->where('id', $request->customer)
            ->first();

        if (!$customerIds) {
            return response()->json([
                'IsError' => true,
                'Notification' => 'Customer tidak ditemukan!'
            ], 400);
        }

        // Set indent status dan status order
        $so_indent = ($request->indent == 1) ? 1 : 0;
        $indent_status = ($request->indent == 1) ? 1 : null;
        $status = ($request->indent == 0 && $request->ajukankelanjutan) ? 2 : 1;
        $code = null;

        DB::beginTransaction();
        try {

            $sales_order = [
                'code' => $code,
                'so_code' => self::generateSoAwal(),
                'brand_name' => $request->brand_name,
                'customer_id' => $customerIds->customer_id,
                'customer_other_address_id' => $request->customer,
                'type_transaction' => $request->type_transaction,
                'so_for' => 1,
                'so_date' => Carbon::now(),
                'type_so' => 'nonppn',
                'idr_rate' => 1,
                'catatan' => $request->catatan,
                'note' => $request->note,
                'created_by' => Auth::id(),
                'status' => $status,
                'so_indent' => $so_indent,
                'indent_status' => $indent_status,
                'condition' => 1,
                'payment_status' => 0,
                'count_rev' => 0,
            ];
    
            // Insert ke database dan ambil ID yang baru saja dibuat
            $salesOrderId = DB::table('penjualan_so')->insertGetId($sales_order);
    
            if($request->sku) {
                foreach($request->sku as $key => $item){
                    $duplicate_product = [];
                    $duplicate = false;
                    $listItem[] = [
                        'sku' => $request->sku[$key],
                        'free_product' => $request->free_product[$key],
                    ];
    
                    foreach($listItem as $row => $value){
                        if(in_array($value, $duplicate_product)) {
                            $duplicate = true;
                            break;
                        } else {
                            array_push($duplicate_product, $value);
                        }
                    }
    
                    if($duplicate){
                        $response['notification'] = [
                            'alert' => 'block',
                            'type' => 'alert-danger',
                            'header' => 'Error',
                            'content' => 'Item sudah ada!',
                        ];
        
                        return $this->response(400, $response);
                    }else{
                        // Find the base product packaging ID if the current product is a clone
                        $baseProductPackagingId = null;
                        $product = DB::table('master_products_packaging')->where('id', $request->sku[$key])->first();
    
                        // dd($product);
                        if ($product) {
                            // Check if it's a clone product and get the base product's packaging ID
                            if (strpos($product->id, '_1') !== false) { 
                                $baseProduct = DB::table('master_products_packaging')->where('id', str_replace('_1', '', $product->id))->first();
    
                                // dd($baseProduct->id);
                                if ($baseProduct) {
                                    $baseProductPackagingId = $baseProduct->id;
                                }
                            } else {
                                // It's a base product, use its packaging ID
                                $baseProductPackagingId = $product->id;
                            }
                        }
    
                        $kontrak_id = ($request->value_kontrak[$key] == 1) ? $request->kontrak_so_id[$key] : null;
    
                        $sales_order_item = [
                            'so_id' => $salesOrderId,
                            'kontrak' => $request->value_kontrak[$key],
                            'product_packaging_id' => $baseProductPackagingId,
                            'price' => $request->price[$key],
                            'qty' => $request->qty[$key],
                            'disc_usd' => $request->disc[$key],
                            'packaging_id' => $request->packaging[$key],
                            'free_product' => $request->free_product[$key],
                            'created_by' => Auth::id(),
                            'kontrak_id' => $kontrak_id,
                        ];
    
                        // input so item 
                        $salesOrderItemId = DB::table('penjualan_so_item')->insertGetId($sales_order_item);
    
                        // Proses Kontrak
                        if ($kontrak_id) {
                            $search_kontrak = DB::table('penjualan_so_kontrak')->where('id', $kontrak_id)->first();
                            $item_kontrak = DB::table('penjualan_so_kontrak_item')->where('so_kontrak_id', $search_kontrak->id)->first();
    
                            if ($search_kontrak) {
                                $log_kontrak = DB::table('penjualan_so_kontrak_log')
                                    ->where('so_kontrak_id', $search_kontrak->id)
                                    ->select(DB::raw('SUM(qty_worked) AS total_qty_kontrak'))
                                    ->first();
    
                                $sisa_qty = $item_kontrak->qty - ($log_kontrak->total_qty_kontrak ?? 0);
    
                                if ($sisa_qty < $request->qty[$key]) {
                                    DB::rollBack();
                                    return response()->json([
                                        'IsError' => true,
                                        'Notification' => 'Sisa kontrak tidak mencukupi!'
                                    ], 500);
                                }
                            }
    
                            $pivot_kontrak = [
                                'so_item_id' => $salesOrderItemId,
                                'so_kontrak_item_id' => $item_kontrak->id,
                            ];
    
                            DB::table('penjualan_so_kontrak_pivot')->insert($pivot_kontrak);
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('orders.existing.index')->with('success', 'Data Orders berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $so = DB::table('penjualan_so')->where('id', $id)->first();

        $so_item = DB::table('penjualan_so_item')
            ->leftJoin('master_products_packaging', 'penjualan_so_item.product_packaging_id', '=', 'master_products_packaging.id')
            ->select(
                'penjualan_so_item.id as id',
                'penjualan_so_item.product_packaging_id as product_id',
                'penjualan_so_item.packaging_id as packaging_id',
                'penjualan_so_item.qty as qty',
                'penjualan_so_item.disc_usd as disc_usd',
                'penjualan_so_item.price as price',
                'penjualan_so_item.free_product as free_product',
                'penjualan_so_item.kontrak as kontrak',
                'penjualan_so_item.kontrak_id as kontrak_id',
                'master_products_packaging.code as product_code',
                'master_products_packaging.name as product_name' // Hapus koma di sini
            )
            ->where('penjualan_so_item.so_id', $so->id)
            ->get();

        $data = [
            'so' => $so,
            'so_item' => $so_item,
        ];

        return view('orders.existing.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction(); // Mulai transaksi

            // Perbarui data utama sales order di tabel `penjualan_so`
            DB::table('penjualan_so')
                ->where('id', $id)
                ->update([
                    'catatan' => $request->input('catatan'),
                    'updated_at' => now(),
                ]);

            // Hapus item yang dihapus di frontend
            if ($request->has('deleted_items')) {
                DB::table('penjualan_so_item')
                    ->where('so_id', $id)
                    ->whereIn('product_packaging_id', $request->deleted_items)
                    ->delete();
            }

            // Loop untuk memperbarui atau menambahkan order item
            foreach ($request->sku as $index => $sku) {
                $freeProduct = in_array($sku, array_keys($request->free ?? [])) ? 1 : 0;

                $existingItem = DB::table('penjualan_so_item')
                    ->where('so_id', $id)
                    ->where('product_packaging_id', $sku)
                    ->first();

                if ($existingItem) {
                    // Jika item sudah ada, update
                    DB::table('penjualan_so_item')
                        ->where('so_id', $id)
                        ->where('product_packaging_id', $sku)
                        ->update([
                            'price' => $request->price[$index],
                            'qty' => $request->qty[$index],
                            'disc_usd' => $request->disc[$index] ?? 0,
                            'free_product' => $freeProduct,
                            'kontrak' => $request->so_kontrak_value[$index],
                            'packaging_id' => $request->packaging[$index],
                            'updated_at' => now(),
                        ]);
                } else {
                    // Jika item tidak ada, insert baru
                    DB::table('penjualan_so_item')->insert([
                        'so_id' => $id,
                        'product_packaging_id' => $sku,
                        'packaging_id' => $request->packaging[$index],
                        'price' => $request->price[$index],
                        'qty' => $request->qty[$index],
                        'disc_usd' => $request->disc[$index] ?? 0,
                        'free_product' => $freeProduct,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit(); // Simpan transaksi jika semua proses berhasil
            \Log::info('Data berhasil diperbarui untuk ID: ' . $id);

            return redirect()->route('orders.existing.index')->with('success', 'Data berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function lanjutkan(Request $request, $id)
    {
        try {
            DB::beginTransaction(); // Mulai transaksi

            $get_so = DB::table('penjualan_so')->where('id', $id)->first();

            if (!$get_so) {
                return back()->with('error', 'Data tidak ditemukan');
            }

            $update_so = DB::table('penjualan_so')->where('id', $id)->update([
                'status' => 2,
                'updated_by' => Auth::id(),
            ]);

            if (!$update_so) {
                throw new \Exception("Gagal memperbarui data");
            }

            DB::commit(); // Simpan transaksi jika semua proses berhasil
            \Log::info('Data berhasil diperbarui untuk ID: ' . $id);

            return redirect()->route('orders.existing.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function print_so($id)
    {
        // Ambil data dari database
        $result = DB::table('penjualan_so')->where('id', $id)->first();
        $customers = DB::table('master_customer_other_addresses')->where('id', $result->customer_other_address_id)->first();
        $result_detail = DB::table('penjualan_so_item')
            ->leftJoin('master_products_packaging', 'penjualan_so_item.product_packaging_id', '=', 'master_products_packaging.id')
            ->leftJoin('master_packaging', 'master_products_packaging.packaging_id', '=', 'master_packaging.id')
            ->select(
                'penjualan_so_item.id as id',
                'penjualan_so_item.price as price',
                'penjualan_so_item.qty as qty',
                'penjualan_so_item.disc_usd as disc_usd',
                'master_products_packaging.code as product_code',
                'master_products_packaging.name as product_name',
                'master_packaging.pack_name as packaging'
            )
            ->where('penjualan_so_item.so_id', $result->id)
            ->get();

        $data = [
            'result' => $result,
            'result_detail' => $result_detail,
            'customers' => $customers,
        ];

        // Konfigurasi DomPDF
        $pdf = PDF::loadView('orders.existing.print_so', $data)
                ->setPaper('A5', 'landscape')
                ->setOptions([
                    'isHtml5ParserEnabled' => true, 
                    'isRemoteEnabled' => true, // Pastikan ini aktif jika Anda menggunakan font atau gambar eksternal
                ]);

        // Menghasilkan file PDF
        return $pdf->stream("{$result->code}.pdf");
    }
}
