<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWishlist extends Model
{
    use HasFactory;

    protected $table = 'user_wishlists';

    protected $fillable = [
        'name',
        'description',
        'visibility',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'visibility' => \App\Enums\WishlistVisibility::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->belongsToMany(Wishlist::class, 'wishlist_item_user_wishlist', 'user_wishlist_id', 'wishlist_item_id')
                    ->withTimestamps();
    }

    // Keep the old relationship for backward compatibility during migration
    public function itemsOld()
    {
        return $this->hasMany(Wishlist::class, 'wishlist_id');
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', \App\Enums\WishlistVisibility::Public);
    }

    public function scopePrivate($query)
    {
        return $query->where('visibility', \App\Enums\WishlistVisibility::Private);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($wishlist) {
            if ($wishlist->is_default) {
                self::where('user_id', $wishlist->user_id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });

        static::updating(function ($wishlist) {
            if ($wishlist->is_default && $wishlist->isDirty('is_default')) {
                self::where('user_id', $wishlist->user_id)
                    ->where('id', '!=', $wishlist->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });
    }
}