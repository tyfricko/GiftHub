<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Wishlist;
use App\Models\UserWishlist;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'fullname',
        'surname',
        'email',
        'password',
        'address',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullName()
    {
        return trim($this->fullname . ' ' . $this->surname);
    }

    /**
     * Get the name attribute (for compatibility with registration form).
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->fullname ?? $this->getFullName();
    }

    /**
     * Set the name attribute (maps to fullname for storage).
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['fullname'] = $value;
    }

    public function userWishlists()
    {
        return $this->hasMany(UserWishlist::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getDefaultWishlist()
    {
        return $this->userWishlists()->where('is_default', true)->first();
    }

    public function getOrCreateDefaultWishlist()
    {
        $defaultWishlist = $this->getDefaultWishlist();

        if (!$defaultWishlist) {
            $defaultWishlist = $this->userWishlists()->create([
                'name' => 'My Wishlist',
                'description' => 'My default wishlist',
                'visibility' => \App\Enums\WishlistVisibility::Public,
                'is_default' => true,
            ]);
        }

        return $defaultWishlist;
    }

    /**
     * Return pending GiftExchangeInvitation models for this user's email.
     *
     * These are invitations where the email matches this user's email and the
     * invitation status is still 'pending'. Eager-load the related event and
     * the event creator for display purposes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPendingInvitations()
    {
        return \App\Models\GiftExchangeInvitation::where('email', $this->email)
            ->where('status', 'pending')
            ->with(['event', 'event.creator'])
            ->orderByDesc('sent_at')
            ->get();
    }

    /**
     * Return count of pending invitations for this user's email.
     *
     * @return int
     */
    public function getPendingInvitationsCount()
    {
        return \App\Models\GiftExchangeInvitation::where('email', $this->email)
            ->where('status', 'pending')
            ->count();
    }
}
