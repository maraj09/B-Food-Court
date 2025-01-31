<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'razorpay_order_id', 'razorpay_payment_id', 'order_amount', 'status', 'discount', 'payment_method', 'coupon_id', 'coupon_discount', 'custom_id', 'points', 'net_amount', 'service_tax', 'gst_amount', 'sgt_amount', 'payment_card_network', 'payment_card_last_4'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
