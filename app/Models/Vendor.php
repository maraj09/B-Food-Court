<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avatar',
        'brand_name',
        'commission',
        'contact_no',
        'fassi_no',
        'stall_no',
        'approve',
        'details',
        'documents',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function vendorBank()
    {
        return $this->hasOne(VendorBank::class);
    }

    public function vendorRating()
    {
        return $this->hasOne(VendorRating::class);
    }
}
