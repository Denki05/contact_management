<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Master\ProductProspek;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;

class ProductProspekController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Melindungi semua method di controller ini
    }
    
    public function index()
    {
        $products = ProductProspek::all();
        return view('master.product_prospek.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'          => 'required|string|max:50',
            'nama'          => 'required|string|max:150',
            'brand'         => 'nullable|string|max:100',
            'searah'        => 'nullable|string|max:100',
            'harga'         => 'required|numeric|min:0',
        ]);
    
        ProductProspek::create($validated);
    
        return redirect()
            ->route('master.product_prospek.index')
            ->with('success', 'Produk prospek berhasil ditambahkan.');
    }
    
    public function show($id)
    {
        $product = ProductProspek::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.']);
        }
        return response()->json(['success' => true, 'data' => $product]);
    }

    public function update(Request $request, $id)
    {
        try {
        
            $product = ProductProspek::find($id);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.']);
            }
        
            $validated = $request->validate([
                'kode'   => 'required|string|max:50|unique:master_products_prospek,kode,' . $id,
                'nama'   => 'required|string|max:150',
                'brand'  => 'nullable|string|max:100',
                'searah' => 'nullable|string|max:100',
                'harga'  => 'required|numeric|min:0',
            ]);
        
            $product->update([
                'kode'   => $validated['kode'],
                'nama'   => $validated['nama'],
                'brand'  => $validated['brand'],
                'searah' => $validated['searah'],
                'harga'  => $validated['harga'],
            ]);
        
            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
            
        } catch (\Throwable $th) {
            Log::error('Error update ProductProspek: '.$th->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.']);
        }
    }
    
    public function getExistingProducts()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get('https://lssoft88.xyz/api/productSearah', [
                'headers' => [
                    'x-api-key' => 'warungkopi123'
                ],
                'timeout' => 10
            ]);
    
            $data = json_decode($response->getBody(), true);
    
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API: ' . $th->getMessage()
            ]);
        }
    }

}