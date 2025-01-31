<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('razorpay_order_id');
            $table->string('razorpay_payment_id');
            $table->decimal('order_amount', 8, 2);
            $table->integer('points')->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('coupon_discount', 8, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
            $table->string('status')->default('pending'); // Modify status as needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
