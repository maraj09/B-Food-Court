<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'phone',
        'email',
        'address',
        'gst_no',
        'billed_category_id',
    ];

    public function billingCategory()
    {
        return $this->belongsTo(BillingCategory::class, 'billed_category_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'bill_to');
    }
}
