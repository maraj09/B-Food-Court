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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('promotion_title');
            $table->dateTime('promotion_date_time');
            $table->boolean('email_status')->default(true);
            $table->boolean('push_status')->default(true);
            $table->string('email_title')->nullable();
            $table->text('email_description')->nullable(); // This will store the content from Quill editor
            $table->string('push_title')->nullable();
            $table->text('push_description')->nullable();
            $table->string('push_link')->nullable();
            $table->string('push_banner')->nullable(); // URL or path to the banner image
            $table->string('push_avatar')->nullable(); // URL or path to the avatar image
            $table->string('user_type'); // To store the type of user (e.g., admin, subscriber)
            $table->timestamps(); // To track creation and update times
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
