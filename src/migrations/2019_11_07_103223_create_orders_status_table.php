<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders_status', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('order_id')->unsigned();
			$table->string('status', 191);
			$table->timestamps();
			$table->string('user_type', 191);
			$table->integer('user_id')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders_status');
	}

}
