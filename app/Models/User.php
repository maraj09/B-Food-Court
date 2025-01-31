<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Coderflex\LaravelTicket\Concerns\HasTickets;
use Coderflex\LaravelTicket\Contracts\CanUseTickets;

class User extends Authenticatable implements CanUseTickets
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasTickets;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'phone_verified_at',
        'onesignal_subs_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_code'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function point()
    {
        return $this->hasOne(CustomerPoint::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function pointLogs()
    {
        return $this->hasMany(CustomerPointLog::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class);
    }
}
