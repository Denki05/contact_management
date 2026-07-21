<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller; // Pastikan ini ada
use App\Master\ProspekTampung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Master\StoreProspek; // Model Parent
use App\Master\CustomerProspek; // Model Child
use App\Repositories\CodeRepo;
use App\Master\EventGuestbook;
use Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls as ReaderXls;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ProspekTampungController extends Controller
{
    public function partial(Request $request) 
    {
        $tab = $request->input('tab', 'L1');
    
        if ($tab === 'L2') {
            $filter_status = [1];
        } elseif ($tab === 'L3') {
            $filter_status = [2, 6];
        } else {
            $filter_status = [0, 3];
        }
    
        $query = ProspekTampung::whereIn('status_request', $filter_status)
            ->orderBy('zone', 'asc')
            ->orderBy('provinsi', 'asc')
            ->orderBy('kota', 'asc');
    
        // L3: sembunyikan data yang sudah Final Approved (is_final_approved = 2)
        // karena data tersebut sudah "lulus" dan tampil di menu Data Exist
        if ($tab === 'L3') {
            $query->where(function ($q) {
                $q->where('status_request', '!=', 6)
                  ->orWhere(function ($q2) {
                      $q2->where('status_request', 6)
                         ->whereIn('is_final_approved', [0, 1]);
                  });
            });
        }
    
        $data_prospek = $query->paginate(5);
    
        $data_prospek->appends([
            'tab' => $tab
        ]);
    
        // Untuk L1 juga gunakan pagination
        $data_by_zone = null;
        if ($tab === 'L1') {
            $data_by_zone = ProspekTampung::whereIn('status_request', $filter_status)
                ->orderBy('zone', 'asc')
                ->orderBy('kota', 'asc')
                ->orderBy('nama', 'asc')
                ->get();
        }
    
        return view('master.tampung_prospek.partial_prospek', [
            'data_prospek' => $data_prospek,
            'currentTab'   => $tab,
            'data_by_zone' => $data_by_zone,
        ]);
    }

    public function getProspekData(Request $request) {
        $data = ProspekTampung::where('status_request', 0)->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function mutasiDariGuestbook(Request $request) 
    {
        try {
            // Tentukan status awal berdasarkan flag transaksi
            // Jika transaksi == 1, langsung kirim ke L2 (status 1)
            $statusAwal = ($request->transaksi == 1) ? 2 : 0;
    
            $prospek = ProspekTampung::create([
                'nama' => $request->nama,
                'perusahaan' => $request->perusahaan,
                'phone' => $request->phone,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'transaksi' => $request->transaksi,
                'status_request' => $statusAwal,
                'source' => 'GUESTBOOK',
                
                // ---> TAMBAHKAN INI <---
                'toko_multicabang' => $request->toko_multicabang,
                'media_sosial'     => $request->media_sosial,
                'marketplace'      => $request->marketplace,
                'model_bisnis'     => $request->model_bisnis,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => ($statusAwal == 1) ? 'Data langsung masuk ke Review SPV!' : 'Data masuk ke Form Update.',
                'data' => $prospek
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 4. Fungsi untuk update kelengkapan data oleh AO (Inline Edit)
    public function updateKelengkapanData(Request $request, $id)
    {
        try {
            $prospek = ProspekTampung::findOrFail($id);
        
            $data = $request->only([
                'nama', 'jabatan', 'perusahaan', 'phone', 'alamat', 'email', 'website',
                'provinsi', 'kota', 'kecamatan', 'kelurahan', 'zone', 'kategori', 
                'pengajuan', 'keterangan', 'visit', 'transaksi', 
                'toko_multicabang', 'media_sosial', 'marketplace', 'model_bisnis', 'latitude', 'longitude'
            ]);
        
            // Handle array gambar
            if ($request->has('images')) {
                $decoded = json_decode($request->input('images'), true);
                $data['image'] = is_array($decoded) ? $decoded : [];
            }
        
            // ==========================================
            // LOGIKA BARU: OTOMATIS PINDAH TAB (ROUTING)
            // ==========================================
            // Jika asalnya di Tab Review (0) dan ditandai Sudah Transaksi (1), 
            // langsung ubah status menjadi 2 (Tab PIC)
            if (isset($data['transaksi']) && $data['transaksi'] == 1 && $prospek->status_request == 0) {
                $data['status_request'] = 2; 
            }
        
            $prospek->update($data);
        
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
                'data'    => $prospek->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 5. Fungsi untuk Action Button: Ajukan L1 ke L2
    public function ajukanKeL2(Request $request, $id)
    {
        try {
            $prospek = ProspekTampung::findOrFail($id);
            
            // Validasi Ekstra Backend: Pastikan No. HP terisi sebelum bisa ke L2
            if (empty($prospek->phone)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Nomor Handphone wajib diisi sebelum diajukan ke L2!'
                ]);
            }

            // Ubah status ke 1 (DIAJUKAN)
            $prospek->update(['status_request' => 1]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil! Data telah dilanjutkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Fungsi menerima lemparan BATCH data dari Guestbook
    public function mutasiBatchDariGuestbook(Request $request) 
    {
        if (!$request->has('guestbook_ids') || empty($request->guestbook_ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data yang dipilih']);
        }
    
        try {
            DB::beginTransaction();
            $countMutasi = 0;
    
            foreach ($request->guestbook_ids as $gb_id) {
                $guest = EventGuestbook::find($gb_id); 
                
                if ($guest && $guest->is_mutated != 1) {
                    // $statusAwal = ($guest->transaksi == 1) ? 1 : 0;
                    $statusAwal = ($guest->transaksi == 1) ? 2 : 0;
                    
                    // 1. VALIDASI BACKEND (Hanya Required Field: Nama, Perusahaan, Phone)
                    if (empty($guest->nama) || empty($guest->phone) || empty($guest->company)) {
                        continue; // Skip jika belum lengkap
                    }
    
                    // 2. Insert ke ProspekTampung
                    ProspekTampung::create([
                        'nama'             => $guest->nama,
                        'perusahaan'       => $guest->company, // Required
                        'phone'            => $guest->phone,
                        'alamat'           => $guest->alamat,  // Opsional
                        'provinsi'         => $guest->provinsi,
                        'kota'             => $guest->kota,
                        'zone'             => $guest->zone,
                        'kategori'         => $guest->kategori,
                        'keterangan'       => $guest->keterangan,
                        // Field Opsional Tambahan
                        'toko_multicabang' => $guest->toko_multicabang,
                        'media_sosial'     => $guest->media_sosial,
                        'marketplace'      => $guest->marketplace,
                        'model_bisnis'     => $guest->model_bisnis,
                        
                        'status_request'   => $statusAwal,
                        'source'           => 'GUESTBOOK',
                        'mutasi_by'        => Auth::id(),
                    ]);
    
                    // 3. Tandai sudah dimutasi
                    $guest->is_mutated = 1;
                    $guest->save();
    
                    $countMutasi++;
                }
            }
    
            DB::commit();
    
            if ($countMutasi == 0) {
                return response()->json(['success' => false, 'message' => 'Gagal mutasi. Pastikan field required (Perusahaan, Zona, dsb) sudah diisi.']);
            }
    
            return response()->json(['success' => true, 'message' => "Berhasil memutasi $countMutasi data ke Prospek L1."]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memutasi data: ' . $e->getMessage()], 500);
        }
    }
    
    public function getKota($provinsi_id)
    {
        // Cari kabupaten berdasarkan prov_id
        $kota = \App\Models\Regency::where('prov_id', $provinsi_id)->orderBy('city_name', 'asc')->get();
        return response()->json($kota);
    }

    public function getKecamatan($kota_id)
    {
        // Cari kecamatan berdasarkan city_id
        $kecamatan = \App\Models\District::where('city_id', $kota_id)->orderBy('dis_name', 'asc')->get();
        return response()->json($kecamatan);
    }

    public function getKelurahan($kecamatan_id)
    {
        // Cari kelurahan berdasarkan dis_id (atau subdis_id sesuai DB Anda)
        // Jika pakai dis_id error, ubah jadi 'subdis_id'
        $kelurahan = \App\Models\Village::where('dis_id', $kecamatan_id)->orderBy('subdis_name', 'asc')->get();
        return response()->json($kelurahan);
    }
    
    // =================================================================
    // 1. SPV Setuju & Mutasi ke L3
    // =================================================================
    public function approveL3(Request $request, $id)
    {
        // Validasi
        if (empty($request->pic)) {
            return response()->json([
                'success' => false,
                'message' => 'Nama PIC wajib diisi oleh SPV!'
            ]);
        }
    
        DB::beginTransaction();
    
        try {
            $prospek = ProspekTampung::findOrFail($id);
    
            // Mapping Data
            $zoneText = $request->zone ?? $prospek->zone;
            $kategoriText = $request->kategori ?? $prospek->kategori;
    
            $zoneId = array_search(strtoupper($zoneText), CustomerProspek::ZONING) ?: null;
            $pengajuanId = array_search(strtoupper($prospek->pengajuan), CustomerProspek::PENGAJUAN) ?: null;
            $kategoriDb = DB::table('master_customer_categories')->where('name', $kategoriText)->value('id');
    
            $companyName = !empty($prospek->perusahaan) ? $prospek->perusahaan : $prospek->nama;
            $normalizedInput = $this->normalizeCompanyName($companyName);
    
            // 1. CEK EXISTING COMPANY
            $existingParent = null;
            $allParents = StoreProspek::select('id', 'name')->get();
    
            foreach ($allParents as $item) {
                if ($this->normalizeCompanyName($item->name) == $normalizedInput) {
                    $existingParent = $item;
                    break;
                }
            }
    
            // Data update yang seragam untuk prospek tampung
            $updateProspekData = [
                'pic'            => $request->pic,
                'officer'        => $request->officer,
                'kategori'       => $kategoriText,
                'zone'           => $zoneText,
                'status_request' => 6, // 6 = MUTATED
                'is_mutated'     => 1,
                'mutasi_at'      => now(),
            ];
    
            // 2. JIKA PERUSAHAAN SUDAH ADA
            if ($existingParent) {
                $prospek->update($updateProspekData);
                DB::commit();
    
                return response()->json([
                    'success' => true, // Ubah ke true agar JS tidak menganggap error
                    'is_duplicate' => true,
                    'parent_id' => $existingParent->id,
                    'message' => 'Perusahaan sudah pernah terdaftar. Status telah diperbarui ke Mutated.'
                ]);
            }
    
            // 3. INSERT PARENT & CHILD (Jika belum ada)
            $parent = new StoreProspek();
            $parent->code = CodeRepo::generateCustomer();
            $parent->name = $companyName;
            $parent->owner_name = $prospek->nama;
            $parent->category_id = $kategoriDb;
            $parent->count_member = 1;
            $parent->zone = $zoneId;
            $parent->pic = $request->pic;
            $parent->phone = $prospek->phone;
            $parent->address = $prospek->alamat;
            // Default keuangan
            $parent->plafon_piutang = 0; $parent->saldo = 0; $parent->has_ppn = 0; $parent->has_tempo = 0; $parent->tempo_limit = 0;
            // Wilayah
            $parent->text_provinsi = $prospek->provinsi; $parent->text_kota = $prospek->kota;
            $parent->text_kecamatan = $prospek->kecamatan; $parent->text_kelurahan = $prospek->kelurahan;
            $parent->status = StoreProspek::STATUS['ACTIVE'];
            $parent->existence = StoreProspek::EXISTENCE['ENABLE'];
            $parent->save();
    
            $child = new CustomerProspek();
            $child->id = $parent->id . '.1';
            $child->customer_id = $parent->id;
            $child->member_default = 1;
            $child->name = $companyName;
            $child->contact_person = $prospek->nama;
            $child->phone = $prospek->phone;
            $child->address = $prospek->alamat;
            $child->officer = $request->officer;
            $child->pengajuan = $pengajuanId;
            $child->zone = $zoneId;
            $child->status = CustomerProspek::STATUS['ACTIVE'];
            $child->situation = CustomerProspek::SITUATION['ACTIVE'];
            $child->status_key = CustomerProspek::STATUS_KEY['ENABLE'];
            $child->status_request = 2; // Asumsi status internal customer
            $child->source = $prospek->source;
            $child->save();
    
            // 4. UPDATE STATUS TAMPUNG (MUTATION SUCCESS)
            $prospek->update($updateProspekData);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'parent_id' => $parent->id,
                'child_id' => $child->id,
                'message' => 'Data berhasil dimutasi ke sistem utama!'
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mutasi data: ' . $e->getMessage()
            ], 500);
        }
    }

    // 2. SPV Tolak Data (Revisi ke AO atau Buang ke L0)
    public function rejectL2(Request $request, $id)
    {
        try {
            $prospek = ProspekTampung::findOrFail($id);
            $action = $request->action_type; // 'revisi' atau 'hapus'

            if ($action === 'revisi') {
                // Kembalikan ke AO untuk diperbaiki (Status 3)
                $prospek->update(['status_request' => 3]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil dikembalikan ke AO untuk direvisi.'
                ]);

            } elseif ($action === 'hapus') {
                // Data Dinonaktifkan (Status 4) 
                $prospek->update(['status_request' => 4]);

                // OPSI TAMBAHAN: 
                // Jika Anda ingin data ini BENAR-BENAR TERHAPUS dari database tabel prospek_tampung,
                // aktifkan perintah di bawah ini:
                // $prospek->delete();

                // (Opsional) Jika ingin mengembalikan status is_mutated = 0 di tabel Guestbook
                // berdasarkan nomor HP / nama agar bisa ditarik ulang oleh AO:
                // \App\Master\EventGuestbook::where('phone', $prospek->phone)->update(['is_mutated' => 0]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data ditolak dan dikembalikan ke antrean Guestbook L0.'
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Aksi penolakan tidak valid.']);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // =========================================================
    // SERVE IMAGE (Pengganti storage:link)
    // =========================================================
    public function serveImage($filename)
    {
        // Sanitasi nama file — cegah path traversal
        $filename  = basename($filename);
        $filepath  = storage_path('app/prospek_tampung/' . $filename);
    
        if (!file_exists($filepath)) {
            abort(404, 'Gambar tidak ditemukan.');
        }
    
        $mimeType = mime_content_type($filepath);
    
        return response()->file($filepath, [
            'Content-Type'  => $mimeType,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
    
    // =========================================================
    // UPLOAD IMAGE (AJAX)
    // =========================================================
    public function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json(['success' => false, 'message' => 'Tidak ada file yang dikirim.']);
        }
    
        $file    = $request->file('image');
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext     = strtolower($file->getClientOriginalExtension());
    
        if (!in_array($ext, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Format tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.']);
        }
    
        // Maks 2MB per file
        if ($file->getSize() > 2 * 1024 * 1024) {
            return response()->json(['success' => false, 'message' => 'Ukuran file melebihi 2MB.']);
        }
    
        $dir = storage_path('app/prospek_tampung');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $file->move($dir, $filename);
    
        return response()->json([
            'success'  => true,
            'filename' => $filename,
            'url'      => '/prospek_tampung/image/' . $filename,
        ]);
    }
    
    // =========================================================
    // DELETE IMAGE (AJAX)
    // =========================================================
    public function deleteImage(Request $request, $id)
    {
        try {
            $prospek  = ProspekTampung::findOrFail($id);
            $filename = basename($request->input('filename', ''));
    
            if (empty($filename)) {
                return response()->json(['success' => false, 'message' => 'Nama file tidak valid.']);
            }
    
            $images = $prospek->image ?? [];
            $images = array_values(array_filter($images, function ($f) use ($filename) {
                return $f !== $filename;
            }));
    
            $prospek->update(['image' => $images]);
    
            // Hapus file fisik
            $filepath = storage_path('app/prospek_tampung/' . $filename);
            if (file_exists($filepath)) {
                unlink($filepath);
            }
    
            return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus.']);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function storeManualEntry(Request $request)
    {
        try {
            $prospek = ProspekTampung::create([
                'nama'             => $request->nama,
                'jabatan'          => $request->jabatan,
                'perusahaan'       => $request->perusahaan,
                'phone'            => $request->phone,
                'alamat'           => $request->alamat,
                'provinsi'         => $request->provinsi,
                'kota'             => $request->kota,
                'kecamatan'        => $request->kecamatan,
                'kelurahan'        => $request->kelurahan,
                'zone'             => $request->zone,
                'kategori'         => $request->kategori,
                'pengajuan'        => $request->pengajuan,
                'toko_multicabang' => $request->toko_multicabang,
                'media_sosial'     => $request->media_sosial,
                'marketplace'      => $request->marketplace,
                'model_bisnis'     => $request->model_bisnis,
                'visit'            => $request->input('visit', 0),
                'transaksi'        => $request->input('transaksi', 0),
                'latitude'         => $request->latitude ?: null,
                'longitude'        => $request->longitude ?: null,
                'geo_source'       => $request->input('geo_source', 'PENDING_GEOTAG'),
                'geo_accuracy'     => $request->geo_accuracy ?: null,
                'geo_captured_at'  => $request->geo_captured_at ?: null,
                'status_request' => ($request->input('transaksi', 0) == 1) ? 2 : 0,
                'source'           => 'ADMIN(OFFLINE)',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil di approved dan masuk setitng pic',
                'data'    => $prospek
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function ajukanLangsungPic(Request $request, $id)
    {
        try {
            $prospek = ProspekTampung::findOrFail($id);
            $prospek->update(['status_request' => 2]);
            return response()->json([
                'success' => true,
                'message' => 'Data langsung masuk ke tab PIC!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function setPicL3(Request $request, $id)
    {
        try {
            $prospek = ProspekTampung::findOrFail($id);
            $prospek->update([
                'pic' => $request->pic,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'PIC berhasil ditugaskan.',
                'data' => $prospek
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal set PIC: ' . $e->getMessage()]);
        }
    }

    // =========================================================
    // NAIKKAN KE MIS (khusus source selain APM/SPG)
    // Data TIDAK disimpan ganda — cukup tandai mis_status='requested'
    // supaya muncul di antrian menu "Setting PIC" milik MIS.
    // Begitu MIS submit balik (am/ams/pic/spg terisi), row ini
    // otomatis lanjut ke tombol "Mutasi" seperti alur PIC biasa.
    // =========================================================
    public function naikkanKeMis(Request $request, $id)
    {
        try {
            $prospek = ProspekTampung::findOrFail($id);

            if ($prospek->status_request != 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data ini bukan berada di tahap Setting PIC.'
                ], 422);
            }

            $src = strtoupper(trim($prospek->source ?? ''));
            if (in_array($src, ['APM', 'SPG'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dari source APM/SPG tidak melalui alur MIS, silakan gunakan Set PIC langsung.'
                ], 422);
            }

            if ($prospek->mis_status === \App\Master\ProspekTampung::MIS_STATUS_REQUESTED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data ini sudah dikirim ke MIS sebelumnya, tinggal menunggu di-assign.'
                ], 422);
            }

            $prospek->update([
                'mis_status'       => \App\Master\ProspekTampung::MIS_STATUS_REQUESTED,
                'mis_requested_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dikirim ke MIS untuk ditentukan AM, ASM, PIC, dan SPG.',
                'data'    => $prospek->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim ke MIS: ' . $e->getMessage()], 500);
        }
    }
    
    private function normalizeCompanyName($name)
    {
        if (!$name) return '';

        // Ubah ke uppercase
        $name = strtoupper($name);

        // Hapus prefix umum perusahaan
        $search = ['PT.', 'PT ', 'CV.', 'CV ', 'UD.', 'UD ', 'TOKO ', 'TB.', 'TB ', 'PD.', 'PD '];
        $name = str_replace($search, '', $name);

        // Ambil hanya karakter alfanumerik untuk perbandingan yang akurat
        $name = preg_replace('/[^A-Z0-9]/', '', $name);

        return trim($name);
    }
    
    public function approveReviewL2(Request $request, $id)
    {
        try {
            $prospek = ProspekTampung::findOrFail($id);
            
            // Cukup ubah status ke 2 agar masuk ke Tab PIC
            $prospek->update([
                'status_request' => 2
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disetujui dan diteruskan ke Tab PIC.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal memproses data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // 1. Tambahkan Method Approve Final
    // 1. Method Approve Final (UBAH KE 2)
    public function approveFinal(Request $request, $id)
    {
        try {
            $prospek = ProspekTampung::findOrFail($id);
            
            // Update ke final (Angka 2)
            $prospek->update(['is_final_approved' => 2]);
            
            return response()->json(['success' => true, 'message' => 'Data telah di-approve final dan dikunci!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengunci data: ' . $e->getMessage()], 500);
        }
    }
    
    public function updateMarketInsight(Request $request, $id)
    {
        try {
            $prospek = ProspekTampung::findOrFail($id);
            
            if ($prospek->is_final_approved == 2) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal! Data sudah final dan terkunci (Approved Final).'
                ], 403);
            }
            
            $prospek->update([
                'produk_paling_laris'        => $request->produk_laris,
                'range_harga_jual'           => $request->range_harga,
                'traffic_toko'               => $request->traffic_toko,
                'output_market'              => $request->output_market,
                'channel_penjualan_dominan'  => $request->channel_penjualan,
                'platform_dominan'           => $request->platform_dominan,
                'brand_dominan'              => $request->brand_dominan,
                'aktivitas_promo_kompetitor' => $request->aktivitas_promo,
                'is_final_approved'             => 1,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Market Insight berhasil diperbarui.'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui insight: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * EXPORT TEMPLATE KOSONG
     */
    public function exportTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set nama sheet
        $sheet->setTitle('Template Prospek');
        
        // Header kolom
        $headers = ['PIC', 'HP', 'Jabatan', 'Perusahaan', 'Visit', 'Transaksi', 'Assigned To', 'User By'];
        $col = 'A';
        
        foreach ($headers as $header) {
            $cell = $col . '1';
            $sheet->setCellValue($cell, $header);
            
            // ✅ Style header (Perbaikan Color)
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF0C82F9'); // Tambahkan FF di depan kode hex
            
            $sheet->getStyle($cell)->getFont()
                ->setBold(true)
                ->getColor()->setARGB(Color::COLOR_WHITE); // Menggunakan konstanta Color::COLOR_WHITE
            
            $sheet->getStyle($cell)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            
            $col++;
        }
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(25); // PIC
        $sheet->getColumnDimension('B')->setWidth(15); // HP
        $sheet->getColumnDimension('C')->setWidth(20); // Jabatan
        $sheet->getColumnDimension('D')->setWidth(25); // Perusahaan
        $sheet->getColumnDimension('E')->setWidth(10); // Visit
        $sheet->getColumnDimension('F')->setWidth(12); // Transaksi
        $sheet->getColumnDimension('G')->setWidth(15); // Assigned To
        $sheet->getColumnDimension('H')->setWidth(18); // User By
        
        // Freeze pane (baris header)
        $sheet->freezePane('A2');
        
        // Add sample rows dengan keterangan
        $sheet->setCellValue('A2', '(Nama Kontak)');
        $sheet->setCellValue('B2', '(08xx-xxxx)');
        $sheet->setCellValue('C2', '(Posisi/Jabatan)');
        $sheet->setCellValue('D2', '(Nama Perusahaan)');
        $sheet->setCellValue('E2', '0 atau 1');
        $sheet->setCellValue('F2', '0 atau 1');
        $sheet->setCellValue('G2', '(APM)');
        $sheet->setCellValue('H2', '(SPG1SBY, SPG2JKT, dll)');
        
        // ✅ Style sample rows (italic, abu-abu - Perbaikan Color)
        for ($col = 'A'; $col <= 'H'; $col++) {  // ← UBAH 'G' MENJADI 'H'
            $sheet->getStyle($col . '2')->getFont()
                  ->setItalic(true)
                  ->getColor()->setARGB('FF9CA3AF'); // Tambahkan FF
        }
        
        // Add data validation untuk Visit & Transaksi
        for ($row = 3; $row <= 100; $row++) {
            // Visit column (E)
            $sheet->getDataValidation('E' . $row)
                ->setType('list')
                ->setFormula1('"0,1"')
                ->setShowDropDown(true);
            
            // Transaksi column (F)
            $sheet->getDataValidation('F' . $row)
                ->setType('list')
                ->setFormula1('"0,1"')
                ->setShowDropDown(true);
        }
        
        // Download file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Prospek_' . date('YmdHis') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $writer->save('php://output');
        exit;
    }
    
    /**
     * EXPORT DATA EXISTING
     */
    public function exportData()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set nama sheet
        $sheet->setTitle('Data Prospek');
        
        // Header kolom
        $headers = ['PIC', 'HP', 'Jabatan', 'Perusahaan', 'Visit', 'Transaksi', 'Assigned To'];
        $col = 'A';
        
        foreach ($headers as $header) {
            $cell = $col . '1';
            $sheet->setCellValue($cell, $header);
            
            // Style header
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->setStartColor(['rgb' => '10B981']);
            
            $sheet->getStyle($cell)->getFont()
                ->setBold(true)
                ->setColor(['rgb' => 'FFFFFF']);
            
            $sheet->getStyle($cell)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            
            $col++;
        }
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(15);
        
        // Freeze pane
        $sheet->freezePane('A2');
        
        // Get data
        $data = ProspekTampung::select('nama', 'phone', 'jabatan', 'perusahaan', 'visit', 'transaksi', 'assigned_to')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Fill data
        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item->nama ?? '');
            $sheet->setCellValue('B' . $row, $item->phone ?? '');
            $sheet->setCellValue('C' . $row, $item->jabatan ?? '');
            $sheet->setCellValue('D' . $row, $item->perusahaan ?? '');
            $sheet->setCellValue('E' . $row, $item->visit ?? 0);
            $sheet->setCellValue('F' . $row, $item->transaksi ?? 0);
            $sheet->setCellValue('G' . $row, $item->assigned_to ?? '');
            
            // Alternating row colors
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':G' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(['rgb' => 'F3F4F6']);
            }
            
            $row++;
        }
        
        // Download file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Export_Prospek_' . date('YmdHis') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $writer->save('php://output');
        exit;
    }
    
    /**
     * IMPORT DATA DARI EXCEL
     */
    public function import(Request $request)
    {
        // Validasi file menggunakan fitur bawaan Laravel (Lebih rapi)
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120'
        ], [
            'file.required' => 'File tidak ditemukan.',
            'file.mimes'    => 'Format file harus .xlsx atau .xls',
            'file.max'      => 'Ukuran file tidak boleh lebih dari 5MB.'
        ]);
    
        try {
            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());
            
            // Load Reader sesuai ekstensi
            $reader = ($ext === 'xlsx') ? new ReaderXlsx() : new ReaderXls();
            $spreadsheet = $reader->load($file->getPathname());
            
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            // Minimal 2 baris: Header (index 0) dan Data Utama (index 1)
            if (count($rows) < 2) {
                return response()->json(['success' => false, 'message' => 'File Excel kosong atau tidak ada data baru.'], 422);
            }
            
            $successCount = 0;
            $skipCount = 0;
            $errorCount = 0;
            $mode = $request->input('mode', 'add_only');
            $errors = [];
            
            DB::beginTransaction();
            
            // ✅ DIUBAH MENJADI 1: Agar sistem mulai memproses data dari Baris 2 Excel (index 1)
            $startRow = 1; 
            
            foreach ($rows as $index => $row) {
                if ($index < $startRow) continue;
                
                // Parse kolom
                $nama        = trim($row[0] ?? '');
                $phoneRaw    = trim($row[1] ?? '');
                $jabatan     = trim($row[2] ?? '');
                $perusahaan  = trim($row[3] ?? '');
                $visit       = (int) ($row[4] ?? 0);
                $transaksi   = (int) ($row[5] ?? 0);
                $assigned_to = trim($row[6] ?? '');
                $user_by     = trim($row[7] ?? '');
                
                // ✅ SAFETY CHECK: Jika baris ini adalah baris contoh bawaan template kosong, langsung dilewati
                if ($nama === '(Nama Kontak)' || $phoneRaw === '(08xx-xxxx)' || $user_by === '(SPG1SBY, SPG2JKT, dll)') {
                    continue;
                }
                
                // Skip jika semua kosong (user mungkin tidak sengaja save baris kosong di excel)
                if (empty($nama) && empty($phoneRaw) && empty($perusahaan)) {
                    if ($mode === 'skip_empty') {
                        $skipCount++;
                    }
                    continue;
                }
                
                // Validasi field wajib
                if (empty($nama) || empty($phoneRaw) || empty($perusahaan)) {
                    $errorCount++;
                    $errors[] = "Baris " . ($index + 1) . ": PIC, HP, dan Perusahaan wajib diisi";
                    continue;
                }
                
                // Validasi boolean
                if (!in_array($visit, [0, 1]) || !in_array($transaksi, [0, 1])) {
                    $errorCount++;
                    $errors[] = "Baris " . ($index + 1) . ": Nilai Visit & Transaksi harus 0 atau 1";
                    continue;
                }
    
                // ==========================================
                // STANDARISASI NOMOR HP
                // ==========================================
                // 1. Hapus semua karakter selain angka (spasi, strip, +, dll)
                $phone = preg_replace('/[^0-9]/', '', $phoneRaw);
                // 2. Jika berawalan 62, ubah ke 0 (Atau sesuaikan dengan standar database Anda)
                if (str_starts_with($phone, '62')) {
                    $phone = '0' . substr($phone, 2);
                }
                
                // CEK DUPLIKASI (Berdasarkan nomor HP yang sudah bersih)
                $existing = ProspekTampung::where('phone', $phone)->first();
                if ($existing) {
                    $skipCount++;
                    continue;
                }
                
                // Tentukan status & source
                $statusAwal = ($transaksi == 1) ? 2 : 0;
                $source = (strtoupper($assigned_to) === 'APM') ? 'APM' : 'APM'; 
                
                // Insert data
                ProspekTampung::create([
                    'nama'           => $nama,
                    'phone'          => $phone,
                    'jabatan'        => $jabatan,
                    'perusahaan'     => $perusahaan,
                    'visit'          => $visit,
                    'transaksi'      => $transaksi,
                    'assigned_to'    => $assigned_to ?: null,
                    'status_request' => $statusAwal,
                    'source'         => $source,
                    'mutasi_by'      => Auth::id(),
                    'user_by'        => $user_by ?: Auth::user()->name,  // ← TAMBAH INI
                ]);
                
                $successCount++;
            }
            
            DB::commit();
            
            return response()->json([
                'success'       => true,
                'message'       => 'Import selesai',
                'success_count' => $successCount,
                'skip_count'    => $skipCount,
                'error_count'   => $errorCount,
                'errors'        => count($errors) > 0 ? array_slice($errors, 0, 10) : [] // Max 10 error agar response tidak kepanjangan
            ]);
            
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            // Tangkap error spesifik jika file excel corrupt / diluar standar
            return response()->json([
                'success' => false, 
                'message' => 'File Excel tidak dapat dibaca, pastikan formatnya benar.'
            ], 422);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}