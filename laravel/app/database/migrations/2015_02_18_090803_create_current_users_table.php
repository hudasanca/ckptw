<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('current_users', function($table){
			$table->increments('id');
			$table->integer('user_actor')->unsigned();
			$table->integer('user_victim')->unsigned();
			$table->integer('type');
			$table->timestamps();


			$table->foreign('user_actor')->references('id')->on('users');
			$table->foreign('user_victim')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('current_users');
	}

}
