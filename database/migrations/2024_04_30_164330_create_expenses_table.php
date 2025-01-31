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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('tags')->nullable();
            $table->decimal('amount', 10, 2);
            $table->unsignedBigInteger('expense_category_id');
            $table->string('payment_mode');
            $table->text('details')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('expense_category_id')->references('id')->on('expense_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
