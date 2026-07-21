<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Master\ProspekTampung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiProspekController extends Controller
{
    public function receive(Request $request)
    {
        // --- TAMBAHKAN INI UNTUK DEBUGGING ---
        \Log::info('API Key Diterima: ' . $request->header('X-API-KEY'));
        \Log::info('API Key Diharapkan: ' . config('services.api_key'));
        // --------------------------------------
        
        // Validasi Key (Gunakan .env API_SECRET_KEY)
        if ($request->header('X-API-KEY') !== config('services.api_key')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            DB::beginTransaction();
            
            // Mapping data agar sesuai dengan model ProspekTampung
            ProspekTampung::create([
                'nama'             => $request->nama_pic,
                'perusahaan'       => $request->perusahaan,
                'phone'            => $request->phone,
                'alamat'           => $request->alamat,
                'provinsi'         => $request->provinsi,
                'kota'             => $request->kota,
                'kecamatan'        => $request->kecamatan,
                'kelurahan'        => $request->kelurahan,
                'zone'             => $request->zone,
                'kategori'         => $request->kategori,
                'model_bisnis'     => $request->model_bisnis,
                'media_sosial'     => $request->media_sosial,
                'marketplace'      => $request->marketplace,
                'toko_multicabang' => $request->toko_multicabang,
                'visit'            => $request->visit,
                'transaksi'        => $request->transaksi,
                'latitude'         => $request->latitude,
                'longitude'        => $request->longitude,
                'source'           => 'APM',
                'status_request'   => ($request->transaksi == 1) ? 2 : 0,
                'mutasi_by'        => $request->mutasi_by, // <-- MENGAMBIL DARI PAYLOAD APM
                'pic'               => $request->pic,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data berhasil diterima.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getMyData(Request $request)
    {
        // Validasi API Key
        if ($request->header('X-API-KEY') !== config('services.api_key')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    
        // Validasi User ID
        if (!$request->has('user_id')) {
            return response()->json(['success' => false, 'message' => 'User ID diperlukan'], 400);
        }
    
        try {
            // 0=Review, 1=Wait SPV, 2=Set PIC, 6=Input Insight/Final
            $userId   = $request->user_id;
            $userName = $request->user_name;
    
            $data = ProspekTampung::whereIn('status_request', [0, 1, 2, 6])
                ->where(function ($q) use ($userId, $userName) {
                    $q->where('mutasi_by', $userId);
    
                    if ($userName) {
                        $q->orWhere('user_by', $userName)
                          ->orWhere('pic', $userName); // ← tambahan: cek username == pic
                    }
                })
                ->orderBy('created_at', 'desc')
                ->get();
    
            return response()->json([
                'success'     => true,
                'data'        => $data,
                'filter_used' => 'combined(mutasi_by OR user_by OR pic)',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function updateInsight(Request $request, $id)
    {
        // 1. Validasi API Key
        if ($request->header('X-API-KEY') !== 'Denki@96') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    
        try {
            $prospek = ProspekTampung::findOrFail($id);
            
            // ---> PROTEKSI BARU: Tolak jika sudah di-Approve Final (Level 2) oleh Pusat <---
            if ($prospek->is_final_approved == 2) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Akses Ditolak: Data sudah di-Approve Final oleh Pusat!'
                ], 403);
            }
            
            // Siapkan data yang diupdate
            $dataUpdate = $request->only([
                'range_harga_jual',
                'traffic_toko',
                'produk_paling_laris',
                'output_market',
                'channel_penjualan_dominan',
                'platform_dominan',
                'brand_dominan',
                'aktivitas_promo_kompetitor',
            ]);

            // Jika APM mengirim status pengajuan final (1), masukkan ke data update
            if ($request->has('is_final_approved')) {
                $dataUpdate['is_final_approved'] = $request->is_final_approved;
            }

            // 3. Eksekusi Update
            $prospek->update($dataUpdate);
    
            return response()->json(['success' => true, 'message' => 'Data Insight diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getMydataFinal()
    {
        
    }
}