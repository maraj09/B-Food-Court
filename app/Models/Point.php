<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Point extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'signup_points',
        'login_points',
        'order_points',
        'review_points',
        'birthday_points',
        'play_area_points',
        'event_points',
        'minimum_amount',
        'max_points',
    ];

    protected function signupPoints(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value, JSON_UNESCAPED_SLASHES),
        );
    }

    protected function loginPoints(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value, JSON_UNESCAPED_SLASHES),
        );
    }

    protected function orderPoints(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value, JSON_UNESCAPED_SLASHES),
        );
    }

    protected function reviewPoints(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value, JSON_UNESCAPED_SLASHES),
        );
    }

    protected function birthdayPoints(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value, JSON_UNESCAPED_SLASHES),
        );
    }

    protected function playAreaPoints(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value, JSON_UNESCAPED_SLASHES),
        );
    }

    protected function eventPoints(): Attribute
    {
        return new Attribute(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value, JSON_UNESCAPED_SLASHES),
        );
    }
}
