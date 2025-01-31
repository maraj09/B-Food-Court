<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'points',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
