<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRunsheetOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('runsheet_orders', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('order_id')->unsigned();
			$table->integer('delivery_order_id')->unsigned();
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
		Schema::drop('runsheet_orders');
	}

}
