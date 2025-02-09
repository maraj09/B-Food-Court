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
        Schema::create('expense_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('income', 20, 2)->default(0); // Income related to the expense category
            $table->decimal('budget', 20, 2)->default(0); // Budget for the expense category
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_details');
    }
};
