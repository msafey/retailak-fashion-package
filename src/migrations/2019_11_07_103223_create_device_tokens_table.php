<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeviceTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('device_tokens', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('user_id')->unsigned()->nullable();
			$table->string('device_id', 191)->nullable();
			$table->string('device_token', 191)->nullable();
			$table->string('device_os', 191)->nullable();
			$table->string('app_version', 191)->nullable();
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
		Schema::drop('device_tokens');
	}

}
