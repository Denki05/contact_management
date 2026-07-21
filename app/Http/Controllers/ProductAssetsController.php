<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Exception;

class ProductAssetsController extends Controller
{
    public function index()
    {
        return view('product_assets.index');
    }
    
    // 1. Fungsi Proxy menggunakan Guzzle (Untuk Data JSON Merek/Brand)
    public function fetchFromTrans(Request $request)
    {
        $queryParams = $request->all();
        $client = new Client();
        $targetUrl = 'https://trans.lssoft88.xyz/api/product-assets';

        try {
            $response = $client->request('GET', $targetUrl, [
                'query' => $queryParams,
                'verify' => false 
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            return response()->json($data);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke server pusat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // 2. ✅ FUNGSI PROXY BARU UNTUK GOOGLE DRIVE (Mengatasi Error CORS Drive)
    public function fetchFromDrive(Request $request)
    {
        $path = $request->query('path');
        
        if (!$path) {
            return response()->json(['error' => 'Path parameter is missing'], 400);
        }

        $client = new Client();
        $targetUrl = 'https://drive.lssoft88.xyz/api/list?path=' . $path;

        try {
            $response = $client->request('GET', $targetUrl, [
                'verify' => false 
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            return response()->json($data);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke server drive',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function fetchDesignThumbnails(Request $request)
    {
        $queryParams = array_filter([
            'merek'        => $request->get('merek'),
            'brand'        => $request->get('brand'),
            'searah'       => $request->get('searah'),
            'product_name' => $request->get('product_name'),
        ]);
    
        $client    = new Client();
        $targetUrl = 'https://drive.lssoft88.xyz/drive-app/assets/thumbnails';
    
        try {
            $response = $client->request('GET', $targetUrl, [
                'query'   => $queryParams,
                'verify'  => false,
                'timeout' => 15
            ]);
    
            $data = json_decode($response->getBody()->getContents(), true);
            return response()->json($data);
    
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke server design',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}