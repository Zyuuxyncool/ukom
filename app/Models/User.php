<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    const LIST_AKSES = ['Administrator', 'Seller', 'Buyer', 'Shipper', 'Shipper Sub', 'Courier'];
    const BASE_ROUTES = [
        'Administrator' => 'admin',
        'Seller' => 'seller',
        'Buyer' => 'buyer',
        'Shipper' => 'shipper',
        'Shipper Sub' => 'shipper_sub',
        'Courier' => 'courier',
    ];
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'reset_token',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function akses()
    {
        return $this->hasOne(UserAkses::class);
    }

    public function list_akses()
    {
        return $this->hasMany(UserAkses::class);
    }

    public function buyer()
    {
        return $this->hasOne(ProfilBuyer::class);
    }

    public function seller()
    {
        return $this->hasOne(ProfilSeller::class);
    }
}
