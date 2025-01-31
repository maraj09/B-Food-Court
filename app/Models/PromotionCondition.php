<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'filter',
        'comparison',
        'value',
        'operator',
        'sequence',
    ];

    /**
     * The promotion that this condition belongs to.
     */
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
