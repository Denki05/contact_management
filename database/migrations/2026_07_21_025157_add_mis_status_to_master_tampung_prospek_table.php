<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom untuk tracking alur "Naikkan ke MIS":
 * - mis_status       : null (belum pernah dikirim) | 'requested' (menunggu MIS) | 'setted' (sudah di-assign MIS)
 * - mis_requested_at : kapan data dikirim ke antrian MIS
 * - mis_setted_at    : kapan MIS submit AM/ASM/PIC/SPG
 * - mis_setted_by    : user_id petugas MIS yang mengisi
 *
 * Kolom am, ams, pic, spg TIDAK ditambahkan di sini karena sudah ada
 * di tabel master_tampung_prospek (lihat $fillable pada App\Master\ProspekTampung).
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('master_tampung_prospek', function (Blueprint $table) {
            $table->string('mis_status', 20)->nullable()->after('spg')
                ->comment("null | requested | setted");
            $table->timestamp('mis_requested_at')->nullable()->after('mis_status');
            $table->timestamp('mis_setted_at')->nullable()->after('mis_requested_at');
            $table->unsignedBigInteger('mis_setted_by')->nullable()->after('mis_setted_at');
        });
    }

    public function down()
    {
        Schema::table('master_tampung_prospek', function (Blueprint $table) {
            $table->dropColumn(['mis_status', 'mis_requested_at', 'mis_setted_at', 'mis_setted_by']);
        });
    }
};
