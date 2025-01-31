<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('smtp_host')->nullable();
            $table->unsignedInteger('smtp_port')->nullable();
            $table->string('email')->nullable();
            $table->string('api_id')->nullable();
            $table->string('key')->nullable();
            $table->string('secret')->nullable();
            $table->string('wp_phone_number_id')->nullable();
            $table->string('wp_business_account_id')->nullable();
            $table->string('permanent_access_token')->nullable();
            $table->string('businesses')->nullable();
            $table->string('members')->nullable();
            $table->string('events')->nullable();
            $table->string('our_clients')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('smtp_host');
            $table->dropColumn('smtp_port');
            $table->dropColumn('email');
            $table->dropColumn('api_id');
            $table->dropColumn('key');
            $table->dropColumn('secret');
            $table->dropColumn('wp_phone_number_id');
            $table->dropColumn('wp_business_account_id');
            $table->dropColumn('permanent_access_token');
            $table->dropColumn('businesses');
            $table->dropColumn('members');
            $table->dropColumn('events');
            $table->dropColumn('our_clients');
        });
    }
}
