<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftExchangeParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'joined_at',
    ];

    public function event()
    {
        return $this->belongsTo(GiftExchangeEvent::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}