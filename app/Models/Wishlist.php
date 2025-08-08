<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlist_items';

    protected $fillable = [
        'wishlist_id',
        'user_id',
        'itemname',
        'url',
        'price',
        'currency',
        'description',
        'image_url',
        'sort_order'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userWishlists()
    {
        return $this->belongsToMany(UserWishlist::class, 'wishlist_item_user_wishlist', 'wishlist_item_id', 'user_wishlist_id')
                    ->withTimestamps();
    }

    // Keep the old relationship for backward compatibility during migration
    public function userWishlist()
    {
        return $this->belongsTo(UserWishlist::class, 'wishlist_id');
    }
}
