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
        Schema::create('admin_wallet', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('amount_withdraw', 10, 2)->default(0);
            $table->decimal('total_earning', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_wallet');
    }
};
