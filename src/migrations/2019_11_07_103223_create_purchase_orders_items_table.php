<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseOrdersItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_orders_items', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('item_id')->unsigned();
			$table->integer('qty')->nullable();
			$table->integer('purchase_order_id')->unsigned();
			$table->float('item_rate', 10, 0);
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
		Schema::drop('purchase_orders_items');
	}

}
