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
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('gst', 8, 2)->default(0.00);
            $table->decimal('sgt', 8, 2)->default(0.00);
            $table->decimal('service_tax', 8, 2)->default(0.00);
            $table->boolean('payment_mode_upi_status')->default(true);
            $table->boolean('payment_mode_cash_status')->default(true);
            $table->boolean('payment_mode_card_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
