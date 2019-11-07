<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserOrderPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_order_payments', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->integer('user_id');
			$table->integer('order_id');
			$table->integer('sales_order_id');
			$table->string('status');
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
		Schema::drop('user_order_payments');
	}

}
