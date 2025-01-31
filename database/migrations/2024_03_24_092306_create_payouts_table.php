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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->decimal('request_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->date('date')->default(now());
            $table->string('transaction_id')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('remark')->nullable();
            $table->string('payment_image')->nullable();
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
