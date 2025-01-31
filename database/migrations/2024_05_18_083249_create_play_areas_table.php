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
        Schema::create('play_areas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->text('details');
            $table->decimal('price', 8, 2);
            $table->decimal('security_deposit', 8, 2)->default(0);
            $table->integer('max_duration');
            $table->integer('max_player');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('play_areas');
    }
};
