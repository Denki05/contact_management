<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Master\Events;
use Carbon\Carbon;

class UpdateEventStatus extends Command
{
    // Nama perintah yang akan dipanggil nanti
    protected $signature = 'event:check-expiry';

    // Deskripsi perintah
    protected $description = 'Menonaktifkan event yang sudah melewati tanggal event_end_date';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        // Cari event yang masih ACTIVE (1) tapi end_date sudah LEBIH KECIL dari hari ini
        $expiredEvents = Events::where('status', Events::STATUS_ACTIVE)
            ->where('event_end_date', '<', $today)
            ->update(['status' => Events::STATUS_INACTIVE]);

        $this->info("Berhasil menonaktifkan {$expiredEvents} event yang kadaluwarsa.");
    }
}