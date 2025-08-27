<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftExchangeInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'email',
        'token',
        'status',
        'sent_at',
        'responded_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(GiftExchangeEvent::class, 'event_id');
    }
}