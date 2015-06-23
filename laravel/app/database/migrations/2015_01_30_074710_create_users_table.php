<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table){
			$table->increments('id');
			$table->string('username', 16)->unique();
			$table->string('name', 32);
			$table->string('email', 120)->unique();
			$table->string('password', 300);
			$table->string('bio', 200);
			$table->string('photo', 200);
			$table->string('header',200);
			$table->string('instagram_access_token')->after('header')->nullable();
			$table->integer('blur');
			$table->text('followers');
			$table->text('following');
			$table->string('confirmation_token', 200);
			$table->integer('confirmed');
			$table->integer('step');
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}

}
