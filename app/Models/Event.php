<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'image',
        'price',
        'seats',
        'details',
        'status',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderEvents()
    {
        return $this->hasMany(OrderItem::class);
    }
}
