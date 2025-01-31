<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tags',
        'amount',
        'expense_category_id',
        'payment_mode',
        'details',
    ];

    protected $casts = [
        'tags' => 'array',
        'images' => 'array',
    ];

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public static function getTotalAmountForCurrentMonth()
    {
        return static::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
    }
}
