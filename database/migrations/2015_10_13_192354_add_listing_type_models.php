<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddListingTypeModels extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        //
        Schema::table('models', function (Blueprint $table) {
            $table->integer('listing_type')->unsigned()->nullable()->after('manufacturer_id');

            //Foreign Keys - Relationships
            $table->foreign('listing_type')->references('id')->on('listing_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        //
        Schema::table('models', function (Blueprint $table) {
            //Foreign Keys - Relationships
            $table->dropForeign('listing_type');

            $table->dropColumn('listing_type');
        });
    }
}
