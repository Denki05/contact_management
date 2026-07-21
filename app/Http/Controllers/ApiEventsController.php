<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Master\EventInvitation; 

class ApiEventsController extends Controller
{
    /**
     * API 1: Mengecek status event untuk SATU customer spesifik.
     */
    public function checkCustomerEventStatus(Request $request)
    {
        $customerId = $request->get('customer_id');
        $today = \Carbon\Carbon::today()->toDateString();
        
        if (!$customerId) {
            return response()->json(['success' => false, 'message' => 'Customer ID wajib dikirim.'], 400);
        }

        $events = DB::table('master_events as e')
            ->where('e.status', 1) 
            ->where(function($query) use ($today) {
                $query->whereDate('e.invitation_start_date', '<=', $today)
                      ->whereDate('e.invitation_end_date', '>=', $today);
            })
            ->leftJoin('master_event_invitations as mei', function($join) use ($customerId) {
                $join->on('mei.event_id', '=', 'e.id')
                     ->where('mei.customer_id', '=', $customerId);
            })
            ->select(
                'e.id as event_id',
                'e.name as event_name',
                DB::raw('COALESCE(mei.status, 0) as status'), // 0 = Belum Diundang
                'mei.invitation_file'
            )
            ->get();

        if ($events->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Tidak ada event aktif saat ini.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data event ditemukan.',
            'data' => $events 
        ]);
    }
    
    /**
     * API 2: Menerima aksi klik tombol dari SYS (Undang / Share) & CRM (Generate)
     */
    public function updateInvitationAction(Request $request)
    {
        $request->validate([
            'customer_id'   => 'required|string',
            'event_id'      => 'required|integer',
            'action'        => 'required|in:undang,generate,kirim,share',
            'officer'       => 'nullable|string', // Perlu untuk INSERT baru
            'customer_type' => 'nullable|string'  // E atau P
        ]);

        $invitation = DB::table('master_event_invitations')
            ->where('event_id', $request->event_id)
            ->where('customer_id', $request->customer_id)
            ->first();

        // =========================================================
        // AKSI UNDANG (INSERT BARU)
        // =========================================================
        if ($request->action === 'undang') {
            if ($invitation) {
                return response()->json(['success' => false, 'message' => 'Customer sudah masuk antrian!'], 400);
            }
            
            // dd($request->officer);

            DB::table('master_event_invitations')->insert([
                'event_id'      =>  $request->event_id,
                'customer_id'   =>  $request->customer_id,
                'customer_type' =>  $request->customer_type ?? 'E',
                'officer'       =>  $request->officer,
                'status'        =>  1, // Status 1: Menunggu Dicetak Admin
                'created_at'    =>  now(),
                'updated_at'    =>  now(),
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Berhasil melakukan aksi Undang (Masuk antrian cetak)!',
                'data'    => ['status' => 1]
            ]);
        }

        // =========================================================
        // JIKA AKSI GENERATE / SHARE, DATA HARUS SUDAH ADA (VALIDASI)
        // =========================================================
        if (!$invitation) {
            return response()->json(['success' => false, 'message' => 'Customer ini belum ada di antrian Event!'], 404);
        }

        $updateData = ['updated_at' => now()];
        $newStatus = $invitation->status;
        $message = '';

        // =========================================================
        // AKSI GENERATE (OLEH ADMIN CRM)
        // =========================================================
        if ($request->action === 'generate') {
            $event = DB::table('master_events')->where('id', $request->event_id)->first();
        
            if (!$event || empty($event->template_path)) {
                return response()->json(['success' => false, 'message' => 'Event ini belum memiliki template gambar.'], 400);
            }
        
            $templatePath = storage_path('app/' . $event->template_path);
        
            if (!file_exists($templatePath)) {
                return response()->json(['success' => false, 'message' => 'File template fisik tidak ditemukan di CRM.'], 404);
            }
        
            $ext = strtolower(pathinfo($templatePath, PATHINFO_EXTENSION));
            if ($ext == 'png') {
                $image = imagecreatefrompng($templatePath);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            } else {
                $image = imagecreatefromjpeg($templatePath);
            }
        
            $imageWidth  = imagesx($image);
            $imageHeight = imagesy($image);
        
            $x1 = ($imageWidth * 0.24) + 10;
            $x2 = ($imageWidth * 0.76) - 10;
            $y1 = ($imageHeight * 0.715) + 6;
            $y2 = ($imageHeight * 0.805) - 6;
        
            $baseWhite = imagecolorallocate($image, 255, 255, 255);
            imagefilledrectangle($image, $x1, $y1, $x2, $y2, $baseWhite);
        
            $sampleX = ($x1 + $x2) / 2;
            $sampleY = $y1 - 8;
            $rgb = imagecolorat($image, $sampleX, $sampleY);
            $r = ($rgb >> 16) & 0xFF; $g = ($rgb >> 8) & 0xFF; $b = $rgb & 0xFF;
            $softColor = imagecolorallocatealpha($image, $r, $g, $b, 20);
            imagefilledrectangle($image, $x1, $y1, $x2, $y2, $softColor);
        
            $text = strtoupper($request->customer_name ?? 'NAMA CUSTOMER');
            $fontFile = public_path('fonts/Roboto-Bold.ttf');
        
            if (!file_exists($fontFile)) {
                return response()->json(['success' => false, 'message' => 'Font tidak ditemukan di: ' . $fontFile], 500);
            }
        
            $textColor = imagecolorallocate($image, 30, 41, 59);
            $fontSize = $imageWidth * 0.040;
        
            $bbox = imagettfbbox($fontSize, 0, $fontFile, $text);
            $textWidth = $bbox[2] - $bbox[0];
            $maxWidth = ($x2 - $x1) - 40;
        
            while ($textWidth > $maxWidth && $fontSize > 18) { 
                $fontSize -= 1;
                $bbox = imagettfbbox($fontSize, 0, $fontFile, $text);
                $textWidth = $bbox[2] - $bbox[0];
            }
        
            $textHeight = $bbox[1] - $bbox[7];
            $x = ($imageWidth - $textWidth) / 2;
            $y = ($y1 + $y2) / 2 + ($textHeight / 2) - 3;
        
            imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontFile, $text);
        
            $saveFolder = public_path('invitations');
            if (!file_exists($saveFolder)) { mkdir($saveFolder, 0755, true); }
        
            $fileName = 'invitation_' . $invitation->id . '_' . time() . '.jpg';
            $outputPath = $saveFolder . '/' . $fileName;
        
            imagejpeg($image, $outputPath, 100);
            imagedestroy($image);
        
            $updateData['invitation_file'] = 'invitations/' . $fileName;
            $newStatus = 2; // Status 2: Selesai Dicetak (Siap dikirim Officer)
            $message = 'Berhasil men-generate undangan bergambar!';
            
        // =========================================================
        // AKSI KIRIM (VALIDASI SHARE COUNT)
        // =========================================================
        } elseif ($request->action === 'kirim') {
        
            // if (($invitation->share_count ?? 0) < 1) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Minimal undangan harus di-share 1x sebelum ditandai terkirim!'
            //     ], 400);
            // }
        
            $newStatus = 3;
            $updateData['sent_at'] = now();
        
            $message = 'Undangan berhasil ditandai sebagai Terkirim!';
        
        // =========================================================
        // AKSI SHARE (INCREMENT + RETURN COUNT)
        // =========================================================
        } elseif ($request->action === 'share') {
        
            DB::table('master_event_invitations')
                ->where('id', $invitation->id)
                ->increment('share_count');
        
            // ambil ulang nilai terbaru
            $newCount = DB::table('master_event_invitations')
                ->where('id', $invitation->id)
                ->value('share_count');
        
            return response()->json([
                'success' => true,
                'message' => 'Share berhasil dicatat!',
                'data' => [
                    'share_count' => $newCount
                ]
            ]);
        }

        $updateData['status'] = $newStatus;
        DB::table('master_event_invitations')
            ->where('id', $invitation->id)
            ->update($updateData);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'status' => $newStatus
            ]
        ]);
    }
    
    /**
     * API 3: Menampilkan daftar undangan milik Officer (Untuk Index Insidentil)
     */
    public function getOfficerProgress(Request $request)
    {
        $officer = $request->get('officer'); 
        $today = \Carbon\Carbon::today()->toDateString();
        
        if (!$officer) {
            return response()->json(['success' => false, 'message' => 'Officer wajib dikirim'], 400);
        }
    
        $data = DB::table('master_event_invitations as mei')
            ->join('master_events as e', 'e.id', '=', 'mei.event_id')
            ->where(function($query) use ($today) {
                $query->whereDate('e.invitation_start_date', '<=', $today)
                      ->whereDate('e.invitation_end_date', '>=', $today);
            })
            ->where('mei.officer', $officer)
            ->where('mei.status', '>=', 1) // REVISI: >= 1 agar status "Menunggu Dicetak" muncul di menu Officer
    
            ->select(
                'mei.id as invitation_id',
                'mei.event_id',
                'mei.customer_id',
    
                DB::raw("
                    COALESCE(
                        (SELECT name FROM master_customer_other_addresses WHERE CAST(id AS CHAR) = CAST(mei.customer_id AS CHAR) LIMIT 1),
                        (SELECT name FROM master_customer_other_addresses_prospek WHERE CAST(id AS CHAR) = CAST(mei.customer_id AS CHAR) LIMIT 1),
                        mei.customer_id
                    ) as customer_name
                "),
    
                'e.name as event_name',
                'mei.status',
                'mei.invitation_file',
                'mei.updated_at'
            )
            ->orderBy('mei.updated_at', 'desc')
            ->limit(50)
            ->get();
    
        return response()->json([
            'success' => true,
            'message' => 'Data progress berhasil diambil',
            'data' => $data
        ]);
    }
}