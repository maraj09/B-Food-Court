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
        Schema::create('vendor_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->decimal('balance', 10, 2)->default(0); // Change the precision and scale as needed
            $table->decimal('amount_paid', 10, 2)->default(0); // Change the precision and scale as needed
            $table->decimal('total_earning', 10, 2)->default(0); // Change the precision and scale as needed
            $table->timestamps();
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_banks');
    }
};
