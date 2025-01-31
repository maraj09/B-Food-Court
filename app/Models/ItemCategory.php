<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $table = 'item_categories';
    protected $fillable = ['name', 'ribbon_color'];

    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class);
    }
}
