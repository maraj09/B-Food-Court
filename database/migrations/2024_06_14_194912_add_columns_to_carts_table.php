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
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('play_area_id')->nullable();
            $table->date('play_area_date')->nullable();
            $table->time('play_area_start_time')->nullable();
            $table->time('play_area_end_time')->nullable();

            // Add foreign key constraints
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('play_area_id')->references('id')->on('play_areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            //
        });
    }
};
