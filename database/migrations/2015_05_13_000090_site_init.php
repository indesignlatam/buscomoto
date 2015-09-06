<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SiteInit extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		//
		Schema::create('engine_sizes', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->string('slug')->unique();
		    $table->smallInteger('min')->unsigned();
		    $table->smallInteger('max')->unsigned();
		});

		Schema::create('listing_types', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->string('slug')->unique();
		    $table->string('image_path');
		});

		Schema::create('manufacturers', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->integer('country_id');
		    $table->string('image_path');
			$table->tinyInteger('ordering')->unsigned()->default(10);

		    $table->timestamps();

		    //Foreign Keys - Relationships
	        $table->foreign('country_id')->references('id')->on('countries');
		});

		Schema::create('models', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->unsignedInteger('manufacturer_id');

			$table->timestamps();

		    //Foreign Keys - Relationships
	        $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');;
		});

		Schema::create('transmission_types', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->string('slug')->unique();
		});

		Schema::create('fuel_types', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->string('slug')->unique();
		});

		Schema::create('feature_categories', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->string('slug')->unique();
		});

		Schema::create('features', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->string('slug')->unique();
		    $table->integer('category_id')->unsigned()->nullable();

		    //Foreign Keys - Relationships
	        $table->foreign('category_id')->references('id')->on('feature_categories');
		});

		Schema::create('departments', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->integer('country_id');

		    //Foreign Keys - Relationships
	        $table->foreign('country_id')->references('id')->on('countries');
		});

		Schema::create('cities', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->integer('country_id');
		    $table->integer('department_id')->unsigned()->nullable();
			$table->tinyInteger('ordering')->unsigned()->default(10);

		    //Foreign Keys - Relationships
	        $table->foreign('department_id')->references('id')->on('departments');
	        $table->foreign('country_id')->references('id')->on('countries');
		});

		Schema::create('featured_types', function($table){
		    $table->increments('id');

		    $table->string('name');
		    $table->text('description')->nullable();
		    $table->string('image_path');
		    $table->integer('price');
		    $table->string('icon')->nullable();
		    $table->string('color', 10)->nullable();
		    $table->string('uk_class', 30)->nullable();
		});

		Schema::create('listings', function($table){
		    $table->increments('id');

		    $table->string('code',64)->unique()->nullable();
		    $table->integer('user_id')->unsigned()->nullable();
		    $table->integer('engine_size')->unsigned()->nullable();
		    $table->integer('listing_type')->unsigned()->nullable();
		    $table->integer('manufacturer_id')->unsigned()->nullable();
		    $table->integer('model_id')->unsigned()->nullable();
		    $table->integer('fuel_type')->unsigned()->nullable();
		    $table->integer('transmission_type')->unsigned()->nullable();
		    $table->integer('city_id')->unsigned()->nullable();

		    $table->string('slug');
		    $table->string('title');

		    // Location
			$table->string('district', 100)->nullable();

			// Information
		   	$table->integer('year')->unsigned()->nullable();
		   	$table->integer('odometer')->unsigned()->nullable();
		   	$table->text('description');
		    $table->float('price');
			$table->string('color', 100)->nullable();
			$table->string('license_number', 10)->nullable();
			$table->boolean('unique_owner')->nullable();
			$table->boolean('4x4')->nullable();

		   	// Main image
		    $table->string('image_path')->nullable();

		    // Expiring info
		    $table->timestamp('featured_expires_at')->nullable();
		    $table->integer('featured_type')->unsigned()->nullable();
		    $table->timestamp('expires_at')->nullable();
			$table->boolean('expire_notified')->default(false);

		    // Views info
		    $table->integer('views')->unsigned()->default(0);

		    $table->timestamps();
		    $table->softDeletes();

		    //Foreign Keys - Relationships
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
	        $table->foreign('listing_type')->references('id')->on('listing_types');
	        $table->foreign('engine_size')->references('id')->on('engine_sizes');
	        $table->foreign('city_id')->references('id')->on('cities');
	        $table->foreign('featured_type')->references('id')->on('featured_types');
	        $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
	        $table->foreign('model_id')->references('id')->on('models');
	        $table->foreign('fuel_type')->references('id')->on('fuel_types');
	        $table->foreign('transmission_type')->references('id')->on('transmission_types');
		});

		Schema::create('feature_listing', function($table){
		    $table->increments('id');

		    $table->integer('feature_id')->unsigned();
		    $table->integer('listing_id')->unsigned();

		    //Foreign Keys - Relationships
	        $table->foreign('feature_id')->references('id')->on('features')->onDelete('cascade');;
	        $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');;
		});

		Schema::create('images', function($table){
		    $table->increments('id');

		    $table->integer('listing_id')->unsigned();
		    $table->string('image_path')->unique();
		    $table->tinyInteger('ordering')->unsigned()->nullable();

		    //Foreign Keys - Relationships
	        $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
		});

		Schema::create('messages', function($table){
		    $table->increments('id');

		    $table->integer('user_id')->unsigned()->nullable();
		    $table->integer('listing_id')->unsigned()->nullable();

		    $table->string('name')->nullable();
		    $table->string('email')->nullable();
		    $table->string('phone', 15)->nullable();

		    $table->text('comments')->nullable();
		    $table->boolean('read')->default(false);
		    $table->boolean('answered')->default(false);

		    $table->timestamps();

		    //Foreign Keys - Relationships
	        $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');;
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
		});

		Schema::create('payments', function($table){
		    $table->increments('id');

		    $table->integer('user_id')->unsigned()->nullable();
		    $table->integer('listing_id')->unsigned()->nullable();
		    $table->integer('featured_id')->unsigned();

		    $table->string('description');
		    $table->string('reference_code', 50);
		    $table->float('amount');
		    $table->float('tax');
		    $table->float('tax_return_base');
		    $table->string('currency', 5)->default('COP');
		    $table->string('signature');
		    $table->boolean('confirmed')->default(false);
		    $table->boolean('canceled')->default(false);

		    // PayU Data
		    $table->string('state_pol', 32)->nullable();
		    $table->decimal('risk', 2, 2)->nullable();
		    $table->string('response_code_pol')->nullable();
		    $table->string('reference_pol')->nullable();
		    $table->timestamp('transaction_date')->nullable();
		    $table->string('cus', 64)->nullable();
		    $table->string('pse_bank')->nullable();
		    $table->string('authorization_code', 12)->nullable();
		    $table->string('bank_id')->nullable();
		    $table->string('ip', 20)->nullable();
		    $table->integer('payment_method_id')->nullable();
		    $table->string('transaction_bank_id')->nullable();
		    $table->string('transaction_id', 36)->nullable();
		    $table->string('payment_method_name')->nullable();
			$table->boolean('locked')->default(false);

		    $table->nullableTimestamps();

		    //Foreign Keys - Relationships
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
	        $table->foreign('listing_id')->references('id')->on('listings')->onDelete('set null');
	        $table->foreign('featured_id')->references('id')->on('featured_types');
		});

		Schema::create('archived_listings', function($table){
		    $table->increments('id');

		    $table->string('code',64)->unique()->nullable();
		    $table->integer('user_id')->unsigned()->nullable();
		    $table->integer('listing_type')->unsigned()->nullable();
		    $table->integer('manufacturer_id')->unsigned()->nullable();
		    $table->integer('model_id')->unsigned()->nullable();
		    $table->integer('fuel_type')->unsigned()->nullable();
		    $table->integer('transmission_type')->unsigned()->nullable();
		    $table->integer('city_id')->unsigned()->nullable();

		    $table->string('slug');
		    $table->string('title');

		    // Location
			$table->string('district')->nullable();

			// Information
		   	$table->integer('year')->unsigned()->nullable();
		   	$table->integer('odometer')->unsigned()->nullable();
		   	$table->text('description');
		    $table->float('price');
			$table->string('color', 100)->nullable();
			$table->string('license_number', 10)->nullable();
			$table->boolean('unique_owner')->nullable();
			$table->boolean('4x4')->nullable();

		    // Views info
		    $table->integer('views')->unsigned()->default(0);

		    $table->timestamps();
		});

		Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('listing_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            // Relations
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('listing_id')
                  ->references('id')
                  ->on('listings')
                  ->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
        Schema::drop('likes');
		Schema::drop('archived_listings');
		Schema::drop('payments');
		Schema::drop('messages');
		Schema::drop('images');
		Schema::drop('feature_listing');
		Schema::drop('listings');
		Schema::drop('featured_types');
		Schema::drop('cities');
		Schema::drop('departments');
		Schema::drop('features');
		Schema::drop('feature_categories');
		Schema::drop('fuel_types');
		Schema::drop('transmission_types');
		Schema::drop('models');
		Schema::drop('manufacturers');
		Schema::drop('listing_types');
		Schema::drop('engine_sizes');
	}

}