<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnginesizeArchived extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        //
        Schema::table('archived_listings', function ($table) {
            $table->integer('engine_size')->unsigned()->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        //
        Schema::table('archived_listings', function ($table) {
            $table->dropColumn('engine_size');
        });
    }
}
