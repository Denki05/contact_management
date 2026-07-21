<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'master_events';
    
    // === TAMBAHKAN KONSTANTA STATUS DI SINI ===
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    
    protected $fillable = [
        'name',
        'event_start_date',
        'event_end_date',
        'invitation_start_date',
        'invitation_end_date',
        'is_global',
        'template_path',
        'status',
    ];

    // Mengubah string tanggal dari database menjadi object tanggal (Carbon)
    protected $casts = [
        'event_start_date' => 'date',
        'event_end_date' => 'date',
        'invitation_start_date' => 'date',
        'invitation_end_date' => 'date',
        'is_global' => 'boolean',
    ];

    public function invitations()
    {
        return $this->hasMany(EventInvitation::class);
    }
}