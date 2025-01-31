<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'request_amount',
        'status',
        'date',
        'transaction_id',
        'payment_mode',
        'remark',
        'payment_image',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
