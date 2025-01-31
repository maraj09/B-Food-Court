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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount', 8, 2); // Assuming discount can be in percentage or a fixed value
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->boolean('status')->nullable();
            $table->integer('limit')->nullable();
            $table->decimal('minimum_amount', 8, 2);
            $table->enum('limit_type', ['global', 'per_user', 'on_order'])->default('global');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
