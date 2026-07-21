<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;

class EventGuestbook extends Model
{

    protected $table = 'master_events_guestbook';

    protected $fillable = [
        'event_id',
        'nama',
        'jabatan',
        'alamat',
        'kategori',
        'provinsi',
        'kota',
        'zone',
        'phone',
        'company',
        'image',
        'check_in',
        'source',
        'toko_multicabang',
        'media_sosial',
        'marketplace',
        'model_bisnis',
        'officer',
        'ao',
        'keterangan',
        'is_mutated',
    ];

    protected $casts = [
        'check_in' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }
}