<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class PicReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // tampilkan form filter
    public function index(Request $request)
    {
        $data['customers'] = \App\Master\Store::get();
        $data['products'] = \App\Master\Product::get();

        return view('report.pic.index', $data);
    }

    // fungsi baru untuk export
    public function generateReport(Request $request)
    {
        $payload = $request->only([
            'date_start', 'date_end', 'pic_id', 'customer_id', 'product_id', 'type_report'
        ]);
    
        try {
            $client = new Client();
            $response = $client->post("http://ppiapps.sytes.net:8000/api/generate-report", [
                'form_params' => $payload,
            ]);
    
            $pdfContent = $response->getBody()->getContents();
    
            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="report.pdf"',
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}