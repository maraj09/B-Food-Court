<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPointLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action', 'points', 'details', 'order_id', 'item_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->hasOne(Item::class);
    }
}
