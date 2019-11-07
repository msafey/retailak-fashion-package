<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_logs', function(Blueprint $table)
		{
			$table->text('text', 65535);
			$table->integer('order_id');
			$table->string('success', 191);
			$table->integer('id')->unsigned()->primary();
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
		Schema::drop('payment_logs');
	}

}
