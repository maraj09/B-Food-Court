<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount',
        'discount_type',
        'status',
        'limit',
        'limit_type',
        'minimum_amount',
        'maximum_amount',
        'share_discount',
        'approved',
        'expire_date',
        'vendor_id',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function itemCategories()
    {
        return $this->belongsToMany(ItemCategory::class, 'coupon_category', 'coupon_id', 'category_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'coupon_item', 'coupon_id', 'item_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
