<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoimagenotifiedatColunm extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        //
        Schema::table('listings', function ($table) {
            $table->timestamp('no_image_notified_at')->nullable()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        //
        Schema::table('listings', function ($table) {
            $table->dropColumn('no_image_notified_at');
        });
    }
}
