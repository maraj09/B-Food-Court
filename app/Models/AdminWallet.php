<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminWallet extends Model
{
    use HasFactory;

    protected $table = 'admin_wallet';

    protected $fillable = [
        'amount_withdraw',
        'balance',
    ];
}
