<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuidToMessages extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        //
        Schema::table('messages', function ($table) {
            $table->string('uuid', 64)->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        //
        Schema::table('messages', function ($table) {
            $table->dropColumn('uuid');
        });
    }
}
