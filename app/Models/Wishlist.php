<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'itemname',
        'url',
        'user_id',
        'price',
        'currency',
        'description',
        'image_url'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
