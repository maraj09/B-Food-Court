<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'details',
        'price',
        'security_deposit',
        'max_duration',
        'max_player',
        'status',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderPlayAreas()
    {
        return $this->hasMany(OrderItem::class);
    }
}
