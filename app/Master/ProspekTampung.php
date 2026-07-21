<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;

class ProspekTampung extends Model
{
    protected $table = 'master_tampung_prospek';
    
    protected $fillable = [
        'nama',
        'jabatan',
        'perusahaan',
        'phone',
        'alamat',
        'email',
        'website',
        'provinsi',
        'kota',
        'kecamatan',
        'kelurahan',
        'zone',
        'kategori',
        'image',
        'source',
        'assigned_to',
        'pengajuan',
        'status_request',
        'mutasi_by',
        'user_by',
        'am',
        'asm',
        'pic',        // <--- TAMBAHKAN INI
        'spg',    // <--- TAMBAHKAN INI
        // ── Tracking alur "Naikkan ke MIS" (source selain APM/SPG) ──
        'mis_status',
        'mis_requested_at',
        'mis_setted_at',
        'mis_setted_by',
        'keterangan',
        'visit',
        'transaksi',
        'toko_multicabang',
        'media_sosial',
        'marketplace',
        'model_bisnis',
        'latitude',
        'longitude',
        'geo_accuracy',
        'geo_captured_at',
        'geo_source',
        'range_harga_jual',
        'traffic_toko',
        'produk_paling_laris',
        'output_market',
        'channel_penjualan_dominan',
        'platform_dominan',
        'brand_dominan',
        'aktivitas_promo_kompetitor',
        'mutasi_at',
        'is_mutated',
        'is_final_approved',
    ];
    
    // Inisialisasi Source agar seragam
    const SOURCE_LIST = [
        'APM/SPG' => 'apm_spg',
        'AO'      => 'ao',
        'GUESTBOOK' => 'guestbook',
        'OFFLINE' => 'offline_admin',
    ];
    
    const STATUS_REQUEST = [
        'PENDING VALIDASI' => 0,
        'DIAJUKAN' => 1,
        'DISETUJI' => 2,
        'DITOLAK REVISI' => 3,
        'DINONAKTIFKAN' => 4,
        'SUDAH TERDAFTAR' => 5,
        'MUTATED'          => 6, // Tambahkan status baru di sini
    ];

    // Status alur "Naikkan ke MIS" (khusus source selain APM/SPG)
    const MIS_STATUS_REQUESTED = 'requested'; // sudah dikirim, menunggu MIS isi AM/ASM/PIC/SPG
    const MIS_STATUS_SETTED    = 'setted';    // MIS sudah selesai isi
    
    // Helper untuk mendapatkan nama source
    public function getSourceNameAttribute()
    {
        return array_search($this->source, self::SOURCE_LIST);
    }
    
    public function status_request()
    {
        return array_search($this->status_request, self::STATUS_REQUEST);
    }
}