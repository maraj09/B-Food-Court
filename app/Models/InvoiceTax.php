<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTax extends Model
{
    use HasFactory;

    protected $fillable = ['tax_title', 'tax_rate'];

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_tax_id');
    }
}
