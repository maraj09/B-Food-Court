<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'balance',
        'amount_paid',
        'total_earning'
    ];

    // Define the relationship with the Vendor model
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
