<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'promotion_title',
        'promotion_date_time',
        'email_status',
        'push_status',
        'email_title',
        'email_description',
        'push_title',
        'push_description',
        'push_link',
        'push_banner',
        'push_avatar',
        'user_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'promotion_date_time' => 'datetime',
        'email_status' => 'boolean',
        'push_status' => 'boolean',
    ];
}
