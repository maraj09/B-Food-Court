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
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->float('value');
            $table->float('minimum_amount')->default(0);
            $table->json('signup_points')->nullable();
            $table->json('login_points')->nullable();
            $table->json('order_points')->nullable();
            $table->json('review_points')->nullable();
            $table->json('birthday_points')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
