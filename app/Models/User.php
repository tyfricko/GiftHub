<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Wishlist;
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

    public function wishes() {
        return $this->hasMany(Wishlist::class, 'user_id');

    }
}
