<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Master\ProspekTampung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Menu "Setting PIC" di modul MIS.
 *
 * Menampilkan antrian data dari modul Customer (tab PIC) yang source-nya
 * BUKAN APM/SPG dan sudah diklik "Naikkan ke MIS" (mis_status = 'requested'),
 * lalu MIS mengisi AM, ASM, PIC, dan SPG untuk data tersebut.
 *
 * Karena satu database yang sama dengan modul Customer, "kirim balik ke
 * Customer" di sini cukup berupa update langsung ke tabel yang sama
 * (master_tampung_prospek) — begitu am/ams/pic/spg terisi, row tersebut
 * otomatis lanjut ke alur "Mutasi" di sisi Customer tanpa kode tambahan.
 */
class MisQueueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Daftar SPG — samakan dengan window.SPG_LIST di modul Customer.
    // TODO: pindahkan ke master data / tabel jika daftar SPG makin banyak.
    const SPG_LIST = ['SPG1SBY', 'SPG1MLG', 'SPG1MDN', 'SPG1DIY'];

    /**
     * Partial HTML antrian — di-load via AJAX ke #contentArea saat
     * menu "SETTING PIC" (dropdown MORE) diklik di report/doctor/index.
     */
    public function partial(Request $request)
    {
        $queue = ProspekTampung::where('status_request', 2)
            ->where('mis_status', ProspekTampung::MIS_STATUS_REQUESTED)
            ->whereNotIn(\DB::raw('UPPER(TRIM(source))'), ['APM', 'SPG'])
            ->orderBy('mis_requested_at', 'asc')
            ->get();

        return view('report.doctor.partials.mis_queue', [
            'queue'    => $queue,
            'spgList'  => self::SPG_LIST,
        ]);
    }

    /**
     * MIS submit AM/ASM/PIC/SPG untuk satu data prospek.
     */
    public function set(Request $request, $id)
    {
        $request->validate([
            'am'  => 'required|string|max:255',
            'ams' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
        ], [
            'required' => 'Field :attribute wajib diisi.',
        ]);

        try {
            $prospek = ProspekTampung::findOrFail($id);

            if ($prospek->mis_status !== ProspekTampung::MIS_STATUS_REQUESTED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data ini tidak/tidak lagi berada di antrian MIS.'
                ], 422);
            }

            $prospek->update([
                'am'            => $request->am,
                'asm'           => $request->ams,
                'pic'           => $request->pic,
                'spg'           => $request->spg,
                'mis_status'    => ProspekTampung::MIS_STATUS_SETTED,
                'mis_setted_at' => now(),
                'mis_setted_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil di-assign. Data dikembalikan ke modul Customer untuk lanjut Mutasi.',
                'data'    => $prospek->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}
