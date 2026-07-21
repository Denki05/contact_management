<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Exception;

class ExtraAssetsController extends Controller
{
    // Jika Anda butuh view terpisah untuk Extra (Opsional)
    public function index()
    {
        return view('extra_assets.index'); 
    }
    
    // Fungsi Proxy menggunakan Guzzle khusus untuk EXTRA (Ini yang dipanggil oleh JS)
    public function fetchProxy(Request $request)
    {
        $queryParams = $request->all();

        $client = new Client();
        // Sesuaikan target URL dengan domain server tempat API Extra berada
        $targetUrl = 'https://drive.lssoft88.xyz/api/extra/list'; 

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
                'error' => 'Gagal terhubung ke server storage',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    // Tambahkan method ini di dalam class ExtraAssetsController
    public function streamFile(Request $request)
    {
        $path = $request->query('path');
        
        if (!$path) {
            return response()->json(['error' => 'Parameter path tidak ada'], 400);
        }
    
        $client = new Client();
        $targetUrl = 'https://drive.lssoft88.xyz/api/extra/file?path=' . $path;
    
        try {
            $response = $client->request('GET', $targetUrl, [
                'verify' => false,
                'stream' => true, // Penting untuk file besar
            ]);
    
            $contentType = $response->getHeaderLine('Content-Type') ?: 'application/octet-stream';
            $contentLength = $response->getHeaderLine('Content-Length');
            $body = $response->getBody();
    
            $headers = [
                'Content-Type'              => $contentType,
                'Access-Control-Allow-Origin' => '*',
                'Cache-Control'             => 'public, max-age=3600',
            ];
    
            if ($contentLength) {
                $headers['Content-Length'] = $contentLength;
            }
    
            return response()->stream(function () use ($body) {
                while (!$body->eof()) {
                    echo $body->read(1024 * 64); // Baca 64KB per chunk
                    flush();
                }
            }, 200, $headers);
    
        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Gagal stream file',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}