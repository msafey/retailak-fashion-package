<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResetPasswordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reset_password', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('email', 191);
			$table->string('token', 191);
			$table->timestamps();
			$table->dateTime('token_expired')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reset_password');
	}

}
