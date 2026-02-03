<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'giver_id',
        'recipient_id',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(GiftExchangeEvent::class, 'event_id');
    }

    public function giver()
    {
        return $this->belongsTo(GiftExchangeParticipant::class, 'giver_id');
    }

    public function recipient()
    {
        return $this->belongsTo(GiftExchangeParticipant::class, 'recipient_id');
    }
}
