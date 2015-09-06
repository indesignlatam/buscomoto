<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('users', function(Blueprint $table){
			$table->increments('id');
			$table->string('name');
			$table->string('username')->unique();
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->boolean('confirmed')->default(false);
            $table->string('confirmation_code', 64)->nullable();
            $table->string('phone_1', 15)->nullable();
		    $table->string('phone_2', 15)->nullable();
		    $table->text('description')->nullable();
		    $table->string('image_path')->nullable();
		    $table->timestamp('tips_sent_at')->nullable();

		    $table->boolean('email_notifications')->default(true);
            $table->boolean('privacy_name')->default(true);
            $table->boolean('privacy_phone')->default(true);

			$table->rememberToken();
			$table->timestamps();
			$table->softDeletes();			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		Schema::drop('users');
	}

}
