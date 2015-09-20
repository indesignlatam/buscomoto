<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfirmedAtColumn extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        //
        Schema::table('users', function ($table) {
            $table->timestamp('confirmed_at')->nullable()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        //
        Schema::table('users', function ($table) {
            $table->dropColumn('confirmed_at');
        });
    }
}
