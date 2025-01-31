<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'user_id',
    ];

    /**
     * The promotion that this user belongs to.
     */
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * The user that this promotion is assigned to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
