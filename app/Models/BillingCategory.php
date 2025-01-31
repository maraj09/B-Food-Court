<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'gst_no',
        'address',
        'color_class',
    ];
}
