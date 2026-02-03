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
        'shipping_address',
        'shipping_address_completed_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'shipping_address' => 'array',
        'shipping_address_completed_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(GiftExchangeEvent::class, 'event_id');
    }

    // Helper methods
    public function hasShippingAddress(): bool
    {
        return ! empty($this->shipping_address) &&
               ! empty($this->shipping_address['full_name']) &&
               ! empty($this->shipping_address['address_line_1']) &&
               ! empty($this->shipping_address['city']) &&
               ! empty($this->shipping_address['postal_code']) &&
               ! empty($this->shipping_address['country']);
    }

    public function needsShippingAddress(): bool
    {
        return $this->event->requiresShippingAddress() && ! $this->hasShippingAddress();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
