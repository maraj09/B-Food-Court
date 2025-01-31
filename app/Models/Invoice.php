<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_id',
        'date',
        'due_date',
        'bill_from',
        'bill_to',
        'bill_from_name',
        'bill_from_email',
        'bill_from_description',
        'bill_to_name',
        'bill_to_email',
        'bill_to_description',
        'status',
        'tax_rate',
        'tax_value',
        'total_amount',
        'recurring',
        'late_fees',
        'notes',
        'invoice_notes',
        'attachments',
        'discount_value',
        'discount_rate'
    ];

    protected $casts = [
        'attachments' => 'array',
        'recurring' => 'boolean',
        'late_fees' => 'boolean',
        'notes' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function billFrom()
    {
        return $this->belongsTo(BillingCategory::class, 'bill_from');
    }

    public function billTo()
    {
        return $this->belongsTo(Client::class, 'bill_to');
    }
}
