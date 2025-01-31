<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateImageColumnInExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Add a temporary column for the new JSON data
            $table->json('images')->nullable();
        });

        // Convert existing data to JSON and store it in the new column
        DB::table('expenses')->get()->each(function ($expense) {
            // Split the image string into an array
            $images = array_filter(explode(',', $expense->image));

            // If images array is empty, set it to an empty array
            if (empty($images)) {
                $images = [];
            }

            // Update the record with the JSON-encoded images array
            DB::table('expenses')->where('id', $expense->id)
                ->update(['images' => json_encode($images)]);
        });

        Schema::table('expenses', function (Blueprint $table) {
            // Drop the old image column
            $table->dropColumn('image');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
