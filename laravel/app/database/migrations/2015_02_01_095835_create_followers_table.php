<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('followers', function($table){
			$table->increments('id');
			$table->integer('user_actor')->unsigned();
			$table->integer('user_victim')->unsigned();
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
		Schema::dropIfExists('followers');
	}

}
