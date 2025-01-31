<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBankLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action', 'amount', 'vendor_id'
    ];
}
