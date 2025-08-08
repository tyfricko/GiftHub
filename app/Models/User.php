<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Wishlist;
use App\Models\UserWishlist;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'surname',
        'username',
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

    // Accessor for name attribute (combines fullname and surname)
    public function getNameAttribute() {
        return trim($this->fullname . ' ' . $this->surname);
    }

    // Mutator for name attribute (splits into fullname and surname)
    public function setNameAttribute($value) {
        $parts = explode(' ', $value, 2);
        $this->attributes['fullname'] = $parts[0] ?? '';
        $this->attributes['surname'] = $parts[1] ?? '';
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
}
