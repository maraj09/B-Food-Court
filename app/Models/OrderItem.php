<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'price',
        'refunded',
        'event_id',
        'play_area_id',
        'play_area_date',
        'play_area_start_time',
        'play_area_end_time',
        'event_attendee_arrived',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class); // Assuming you have an Item model
    }

    public function event()
    {
        return $this->belongsTo(Event::class); // Assuming you have an Item model
    }

    public function playArea()
    {
        return $this->belongsTo(PlayArea::class); // Assuming you have an Item model
    }

    public function rating()
    {
        return $this->hasOne(Rating::class); // Assuming you have an Item model
    }
}
