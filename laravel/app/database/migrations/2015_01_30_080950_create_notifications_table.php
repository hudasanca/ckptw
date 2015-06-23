<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function($table){
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('user_sender');
			$table->text('user_involved');
			/* type notifikasi
			1. comments a status 
			2. waw status
			3. waw comments 
			4. user difollow 
			5. mention*/
			$table->integer('type');
			$table->integer('effected');
			$table->integer('seen');
			$table->integer('clicked');
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('notifications');
	}

}
