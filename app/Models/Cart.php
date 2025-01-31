<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'quantity',
        'event_id',
        'play_area_id',
        'play_area_date',
        'play_area_start_time',
        'play_area_end_time',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the event associated with the cart.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the play area associated with the cart.
     */
    public function playArea()
    {
        return $this->belongsTo(PlayArea::class);
    }
}
