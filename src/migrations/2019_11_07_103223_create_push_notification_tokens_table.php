<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePushNotificationTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('push_notification_tokens', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('device_token', 191);
			$table->string('user_id', 191)->nullable();
			$table->integer('push_notification_id')->unsigned();
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
		Schema::drop('push_notification_tokens');
	}

}
