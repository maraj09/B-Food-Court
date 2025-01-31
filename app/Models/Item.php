<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = ['vendor_id', 'item_name', 'price', 'description', 'item_image', 'status', 'approve', 'category_id', 'points_status', 'item_type'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function itemRating()
    {
        return $this->hasOne(ItemRating::class);
    }

    public function category()
    {
        return $this->belongsTo(ItemCategory::class);
    }
}
