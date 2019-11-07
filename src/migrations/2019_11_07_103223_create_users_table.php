<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('name', 191);
			$table->string('email', 191)->nullable();
			$table->string('token', 191)->nullable();
			$table->string('phone', 191)->nullable();
			$table->dateTime('token_last_renew')->nullable();
			$table->string('facebook_id', 191)->nullable();
			$table->string('device_id', 191)->nullable();
			$table->string('device_os', 191)->nullable();
			$table->string('app_version', 191)->nullable();
			$table->text('password', 65535)->nullable();
			$table->boolean('active')->default(1);
			$table->string('api_token', 191)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->string('type', 191)->nullable()->default('');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
