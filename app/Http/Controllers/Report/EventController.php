<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Master\Events;
use App\Master\EventGuestbook;
use DB;

class EventController extends Controller
{
    // Fungsi untuk menyimpan Event Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'invitation_start_date' => 'required|date',
            'invitation_end_date' => 'required|date|after_or_equal:invitation_start_date|before_or_equal:event_end_date',
        ]);

        $event = Events::create([
            'name' => $request->name,
            'event_start_date' => $request->event_start_date,
            'event_end_date' => $request->event_end_date,
            'invitation_start_date' => $request->invitation_start_date,
            'invitation_end_date' => $request->invitation_end_date,
            'is_global' => true, // Default true sesuai skema Rules-Based kita
            'status' => Events::STATUS_ACTIVE,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event berhasil dibuat dan otomatis aktif untuk semua customer!',
            'data' => $event
        ]);
    }

    // Fungsi untuk mengambil daftar Event (untuk ditampilkan di tabel UI)
    public function getList()
    {
        $events = Events::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:1,2' // Validasi untuk angka 1 dan 2
        ]);
    
        $event = Events::find($id);
        
        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event tidak ditemukan'], 404);
        }
    
        $event->status = $request->status;
        $event->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui'
        ]);
    }
    
    // === FUNGSI BARU: UPLOAD TEMPLATE UNDANGAN ===
    public function storeTemplate(Request $request, $id)
    {
        // Validasi file: wajib diisi, maksimal 5MB, format pdf/jpeg/jpg/png
        $request->validate([
            'template_file' => 'required|file|mimes:pdf,jpeg,jpg,png|max:5120',
        ]);

        $event = Events::find($id);

        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event tidak ditemukan'], 404);
        }

        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            
            // Simpan ke storage/app/events
            // Kamu bisa ubah 'local' ke 'public' jika file ini butuh diakses langsung lewat URL publik
            $path = $file->store('events', 'local'); 
            
            // Hapus file lama jika ada (Opsional, agar storage tidak penuh)
            if ($event->template_path && \Storage::disk('local')->exists($event->template_path)) {
                \Storage::disk('local')->delete($event->template_path);
            }

            // Update database
            $event->template_path = $path;
            $event->save();

            return response()->json([
                'success' => true,
                'message' => 'Template berhasil diunggah dan disimpan!',
                'path' => $path
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengunggah file'], 400);
    }
    
    // ==============================================================================
    // FUNGSI 1: MENG-GENERATE LISTING (TARIK DATA MASTER KE TABEL INVITATION)
    // ==============================================================================
    public function generateListing(Request $request, $id)
    {
        $event = Events::find($id);

        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event tidak ditemukan'], 404);
        }

        // 1. Tarik Data Target Existing
        $existing = DB::table('master_customer_other_addresses as s')
            ->leftJoin('master_customers as c', 'c.id', '=', 's.customer_id')
            ->where('c.status', 1)
            ->whereNull('s.deleted_at')
            ->whereNotNull('s.officer')
            ->select('s.id as customer_id', 's.officer')
            ->get()
            ->map(function($item) {
                // Pastikan tipe data DB Anda menampung string utuh, 
                // jika DB Anda maunya 1 huruf, ganti jadi 'E'
                $item->customer_type = 'E'; 
                return $item;
            });

        // 2. Tarik Data Target Prospek
        $prospek = DB::table('master_customer_other_addresses_prospek as s')
            ->leftJoin('master_customers_prospek as c', 'c.id', '=', 's.customer_id')
            ->where('c.status', 1)
            ->whereNull('s.deleted_at')
            ->whereNotNull('s.officer')
            ->select('s.id as customer_id', 's.officer')
            ->get()
            ->map(function($item) {
                // Sama seperti di atas, jika DB maunya 1 huruf, ganti jadi 'P'
                $item->customer_type = 'P'; 
                return $item;
            });

        $allTargets = $existing->merge($prospek);

        // 3. Ambil ID yang SUDAH di-generate sebelumnya 
        $existingInvites = DB::table('master_event_invitations')
            ->where('event_id', $event->id)
            ->pluck('customer_id')
            ->toArray();

        $insertData = [];
        $now = now();

        // 4. Siapkan Array Data Baru
        foreach ($allTargets as $target) {
            if (!in_array($target->customer_id, $existingInvites)) {
                $insertData[] = [
                    'event_id'      => $event->id,
                    'customer_id'   => $target->customer_id,
                    'customer_type' => $target->customer_type, 
                    'officer'       => strtolower(trim($target->officer)),
                    'sent_at'       => null,
                    'status'        => 1,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }
        }

        // 5. Insert Massal dengan Pengaman (insertOrIgnore)
        if (!empty($insertData)) {
            foreach (array_chunk($insertData, 500) as $chunk) {
                // insertOrIgnore akan mengabaikan error duplikat dari MySQL
                // dan melanjutkan insert data yang belum ada
                DB::table('master_event_invitations')->insertOrIgnore($chunk);
            }
            
            return response()->json([
                'success' => true,
                'message' => count($insertData) . ' target customer berhasil ditambahkan!',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Semua target customer yang tersedia sudah ada di dalam Listing.',
        ]);
    }

    // ==============================================================================
    // FUNGSI 2: MENAMPILKAN DATA LISTING DI TABEL MODAL CRM
    // ==============================================================================
    // ==============================================================================
    // FUNGSI 2: MENAMPILKAN DATA LISTING DI TABEL MODAL CRM
    // ==============================================================================
    public function getInvitationList(Request $request, $id)
    {
        // Terapkan logika "Waterfall" pada SQL Join (Tanpa membatasi tipe E / P)
        $list = DB::table('master_event_invitations as i')
            ->where('i.event_id', $id)
            
            // 1. Join ke Existing (Hanya cocokan ID)
            ->leftJoin('master_customer_other_addresses as exist_addr', 'i.customer_id', '=', 'exist_addr.id')
            ->leftJoin('master_customers as exist_cust', 'exist_cust.id', '=', 'exist_addr.customer_id')
            ->leftJoin('master_customer_categories as exist_cat', 'exist_cat.id', '=', 'exist_cust.category_id')
            
            // 2. Join ke Prospek (Hanya cocokan ID)
            ->leftJoin('master_customer_other_addresses_prospek as prosp_addr', 'i.customer_id', '=', 'prosp_addr.id')
            ->leftJoin('master_customers_prospek as prosp_cust', 'prosp_cust.id', '=', 'prosp_addr.customer_id')
            ->leftJoin('master_customer_categories as prosp_cat', 'prosp_cat.id', '=', 'prosp_cust.category_id')
            
            ->select(
                'i.id',
                'i.customer_id',
                'i.status',
                'i.invitation_file',
                'i.officer',
                'i.sent_at', 
                // Ambil data Existing, jika null ambil Prospek, jika masih null tulis "Tanpa Nama"
                DB::raw('COALESCE(exist_addr.name, prosp_addr.name, "Tanpa Nama") as nama_customer'),
                DB::raw('UPPER(COALESCE(exist_addr.text_kota, prosp_addr.text_kota, "-")) as kota'),
                DB::raw('UPPER(COALESCE(exist_addr.text_provinsi, prosp_addr.text_provinsi, "-")) as provinsi'),
                DB::raw('COALESCE(exist_cat.name, prosp_cat.name, "Tanpa Kategori") as kategori'),
                // Tentukan tipe secara dinamis: jika ID existing ada, berarti Existing, sisanya Prospek
                DB::raw("IF(exist_addr.id IS NOT NULL, 'Existing', 'Prospek') as tipe_customer")
            )
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $list
        ]);
    }
    
    // 1. Function untuk mengecek apakah event memiliki template & mengambil URL-nya
    public function checkTemplate($id)
    {
        $event = Events::find($id); 
        
        // FIX: Ganti template_file menjadi template_path
        if (!$event || empty($event->template_path)) {
            return response()->json(['success' => false]);
        }

        $extension = pathinfo($event->template_path, PATHINFO_EXTENSION);
        return response()->json([
            'success' => true,
            'extension' => strtolower($extension),
            'file_url' => url("report/doctor/events/{$id}/template-file") . '?t=' . time() 
        ]);
    }

    // 2. Function untuk merender / menampilkan file aslinya ke browser
    public function showTemplateFile($id)
    {
        $event = Events::find($id);
        
        // FIX: Ganti template_file menjadi template_path
        if (!$event || empty($event->template_path)) {
            abort(404, 'Data template tidak ditemukan');
        }

        // FIX: Ganti template_file menjadi template_path
        $path = storage_path('app/' . $event->template_path);

        if (!file_exists($path)) {
            abort(404, 'File fisik tidak ditemukan di server');
        }

        return response()->file($path);
    }
    
    /**
     * FUNGSI BATCH GENERATE: Memproses beberapa gambar sekaligus dengan cepat
     */
    public function generateBatchImages(Request $request, $id)
    {
        // 1. Validasi Event & Template
        $event = Events::find($id);
        if (!$event || empty($event->template_path)) {
            return response()->json(['success' => false, 'message' => 'Template belum diunggah.']);
        }
    
        $templatePath = storage_path('app/' . $event->template_path);
        if (!file_exists($templatePath)) {
            return response()->json(['success' => false, 'message' => 'File template fisik tidak ditemukan.']);
        }
    
        // 2. Ambil Target
        $targets = $request->input('targets', []); 
        if (empty($targets)) {
            return response()->json(['success' => true, 'processed' => 0]);
        }
    
        // 3. Persiapan Gambar (Load Template 1x ke RAM)
        $ext = strtolower(pathinfo($templatePath, PATHINFO_EXTENSION));
        $baseImage = ($ext == 'png') ? imagecreatefrompng($templatePath) : imagecreatefromjpeg($templatePath);
        if ($ext == 'png') {
            imagealphablending($baseImage, true);
            imagesavealpha($baseImage, true);
        }
    
        $imageWidth  = imagesx($baseImage);
        $imageHeight = imagesy($baseImage);
        $fontFile    = public_path('fonts/Roboto-Bold.ttf');
        $saveFolder  = public_path('invitations');
        
        if (!file_exists($saveFolder)) { mkdir($saveFolder, 0755, true); }
    
        // Koordinat Area Nama (Presisi)
        $x1 = ($imageWidth * 0.24) + 10;
        $x2 = ($imageWidth * 0.76) - 10;
        $y1 = ($imageHeight * 0.715) + 6;
        $y2 = ($imageHeight * 0.805) - 6;
    
        $processedCount = 0;
    
        // 4. Proses Looping Target
        foreach ($targets as $t) {
            try {
                // A. Cari berdasarkan Primary Key (tabel undangan)
                // Saya tambahkan fallback $t['id'] berjaga-jaga jika payload JS belum sempat Anda ubah
                $invId = $t['invitation_id'] ?? $t['id']; 
                
                $inv = DB::table('master_event_invitations')
                        ->where('id', $invId)
                        ->where('status', 1) 
                        ->first();
    
                if (!$inv) continue;
    
                // B. Waterfall Search untuk Nama Customer (Existing -> Prospek)
                $customerName = '';
    
                // Cek di tabel Existing dulu
                $cust = DB::table('master_customer_other_addresses')
                          ->where('id', $inv->customer_id)
                          ->first();
    
                if ($cust && !empty($cust->name)) {
                    $customerName = $cust->name;
                } else {
                    // Jika tidak ada di Existing, cari di tabel Prospek
                    $custProspek = DB::table('master_customer_other_addresses_prospek')
                                    ->where('id', $inv->customer_id)
                                    ->first();
                                    
                    if ($custProspek && !empty($custProspek->name)) {
                        $customerName = $custProspek->name;
                    }
                }
    
                // Fallback: Jika di database kosong, pakai nama dari JS atau set ke 'GUEST'
                if (empty($customerName)) {
                    $customerName = !empty($t['name']) ? $t['name'] : 'GUEST';
                }
    
                // C. CLONE DARI BASE (Sangat Cepat)
                $image = imagecreatetruecolor($imageWidth, $imageHeight);
                imagecopy($image, $baseImage, 0, 0, 0, 0, $imageWidth, $imageHeight);
    
                // D. RENDER AREA NAMA (Tipp-ex)
                $white = imagecolorallocate($image, 255, 255, 255);
                imagefilledrectangle($image, $x1, $y1, $x2, $y2, $white); 
    
                // Overlay Warna Natural
                $rgb = imagecolorat($image, $x1, $y1 - 10);
                $r = ($rgb >> 16) & 0xFF; $g = ($rgb >> 8) & 0xFF; $b = $rgb & 0xFF;
                $softColor = imagecolorallocatealpha($image, $r, $g, $b, 20);
                imagefilledrectangle($image, $x1, $y1, $x2, $y2, $softColor);
    
                // E. TULIS TEXT NAMA (Aman dari null)
                $text = strtoupper($customerName);
                $textColor = imagecolorallocate($image, 30, 41, 59);
                $fontSize = $imageWidth * 0.040;
    
                // Auto-shrink text
                $bbox = imagettfbbox($fontSize, 0, $fontFile, $text);
                $tw = $bbox[2] - $bbox[0];
                $mw = ($x2 - $x1) - 40;
                while ($tw > $mw && $fontSize > 16) {
                    $fontSize--;
                    $bbox = imagettfbbox($fontSize, 0, $fontFile, $text);
                    $tw = $bbox[2] - $bbox[0];
                }
    
                $th = $bbox[1] - $bbox[7];
                imagettftext($image, $fontSize, 0, ($imageWidth - $tw) / 2, (($y1 + $y2) / 2) + ($th / 2) - 3, $textColor, $fontFile, $text);
    
                // F. SIMPAN FILE (Gunakan uniqid agar aman saat batching cepat)
                $fileName = 'inv_' . $inv->id . '_' . uniqid() . '.jpg';
                
                if (imagejpeg($image, $saveFolder . '/' . $fileName, 90)) {
                    // Update Status ke 2 (Siap Kirim)
                    DB::table('master_event_invitations')->where('id', $inv->id)->update([
                        'invitation_file' => 'invitations/' . $fileName,
                        'status' => 2,
                        'updated_at' => now()
                    ]);
                    $processedCount++;
                }
                
                imagedestroy($image); // Hapus kloningan dari RAM
    
            } catch (\Exception $e) {
                // Mencegah aplikasi mati jika ada 1 gambar yang gagal
                \Log::error("Gagal generate batch: " . $e->getMessage());
                continue; 
            }
        }
    
        imagedestroy($baseImage); // Hapus template utama dari RAM
        return response()->json(['success' => true, 'processed' => $processedCount]);
    }
    
    // ==============================================================================
    // FUNGSI 3: MENGAMBIL DATA GUESTBOOK UNTUK EVENT TERTENTU
    // ==============================================================================
    public function getGuestbookList($id)
    {
        $event = Events::find($id);

        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event tidak ditemukan'], 404);
        }

        // Ambil data buku tamu berdasarkan event_id dan urutkan dari check-in terbaru
        $guestbook = DB::table('master_events_guestbook')
            ->where('event_id', $id)
            ->orderBy('check_in', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $guestbook
        ]);
    }
    
    // ==============================================================================
    // FUNGSI 4: UPDATE DATA GUESTBOOK (INLINE EDIT)
    // ==============================================================================
    public function updateGuestbook(Request $request, $id)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'company'    => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:50',
            'alamat'     => 'nullable|string',
            'zone'       => 'nullable|string',
            'provinsi'   => 'nullable|string',
            'kota'       => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);
    
        $guestbook = EventGuestbook::find($id);
    
        if (!$guestbook) {
            return response()->json([
                'success' => false,
                'message' => 'Data guestbook tidak ditemukan'
            ], 404);
        }
    
        // Tambahkan 'kategori' ke dalam array pembaruan
        $guestbook->update($request->only([
            'nama', 'company', 'phone', 'alamat',
            'zone', 'provinsi', 'kota', 'keterangan', 'kategori' // <--- TAMBAHKAN INI
        ]));
    
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui'
        ]);
    }
}