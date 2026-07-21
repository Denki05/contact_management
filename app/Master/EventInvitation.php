<?php

namespace App\Master;

use Illuminate\Database\Eloquent\Model;

class EventInvitation extends Model
{
    
    protected $table = 'master_event_invitations';

    protected $fillable = [
        'event_id',
        'customer_id',
        'customer_type',
        'officer',
        'sent_at',
        'invitation_file',
        'share_count',
        'status',
    ];
    
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }
}