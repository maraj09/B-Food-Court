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
        Schema::create('coupon_item', function (Blueprint $table) {
            $table->unsignedBigInteger('coupon_id');
            $table->unsignedBigInteger('item_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

            // To ensure unique combinations of coupon_id and item_id
            $table->primary(['coupon_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_item');
    }
};
