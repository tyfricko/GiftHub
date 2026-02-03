<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftExchangeEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'end_date',
        'budget_max',
        'created_by',
        'requires_shipping_address',
    ];

    protected $casts = [
        'end_date' => 'datetime',
        'requires_shipping_address' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(GiftExchangeParticipant::class, 'event_id');
    }

    public function invitations()
    {
        return $this->hasMany(GiftExchangeInvitation::class, 'event_id');
    }

    // Helper method
    public function requiresShippingAddress(): bool
    {
        return $this->requires_shipping_address;
    }

    public function getShippingAddressProgress(): array
    {
        if (! $this->requires_shipping_address) {
            return ['required' => false];
        }

        $participants = $this->participants()->where('status', 'accepted')->get();
        $completed = $participants->filter(fn ($p) => $p->hasShippingAddress())->count();

        return [
            'required' => true,
            'total' => $participants->count(),
            'completed' => $completed,
            'percentage' => $participants->count() > 0 ? round(($completed / $participants->count()) * 100) : 0,
        ];
    }

    public function assignments()
    {
        return $this->hasMany(GiftAssignment::class, 'event_id');
    }
}
