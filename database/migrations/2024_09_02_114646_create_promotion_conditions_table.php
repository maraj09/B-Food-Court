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
        Schema::create('promotion_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->string('filter'); // e.g., 'Total Orders'
            $table->string('comparison'); // e.g., 'Is Less Than'
            $table->string('value'); // e.g., the value to compare against
            $table->string('operator')->nullable(); // 'And' or 'Or'
            $table->unsignedInteger('sequence')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_conditions');
    }
};
