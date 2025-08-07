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

    public function assignments()
    {
        return $this->hasMany(GiftAssignment::class, 'event_id');
    }
}