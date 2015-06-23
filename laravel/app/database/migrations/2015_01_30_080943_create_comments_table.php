<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function($table){
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('status_id')->unsigned();
			$table->string('comment',300);
			$table->text('loves');
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('status_id')->references('id')->on('statuses');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('comments');
	}

}
