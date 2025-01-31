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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('footer_logo')->nullable();
            $table->string('footer_banner_heading')->nullable();
            $table->string('footer_banner_sub_heading')->nullable();
            $table->string('footer_banner_sub_heading_url')->nullable();
            $table->text('footer_desc')->nullable();
            $table->string('company_contact_email')->nullable();
            $table->text('company_contact_phone')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_address_map_link')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('copyright')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
