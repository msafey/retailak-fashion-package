<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePushNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('push_notifications', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('push_title', 191);
			$table->string('push_message', 191);
			$table->string('push_os', 191);
			$table->timestamp('push_time')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('push_success', 191)->nullable();
			$table->string('push_failure', 191)->nullable();
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
		Schema::drop('push_notifications');
	}

}
