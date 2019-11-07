<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersOutOfStockItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_out_of_stock_items', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('product_id')->unsigned();
			$table->integer('warehouse_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->boolean('notification_sent')->nullable();
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
		Schema::drop('users_out_of_stock_items');
	}

}
