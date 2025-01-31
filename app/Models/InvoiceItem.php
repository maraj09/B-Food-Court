<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'item_description',
        'quantity',
        'price',
        'tax_value',
        'total',
        'invoice_id',
        'invoice_tax_id'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoiceTax()
    {
        return $this->belongsTo(InvoiceTax::class, 'invoice_tax_id');
    }
}
