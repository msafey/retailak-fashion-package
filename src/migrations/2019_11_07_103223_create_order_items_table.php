<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_items', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('item_id')->unsigned();
			$table->float('rate', 10, 0)->unsigned();
			$table->integer('qty');
			$table->integer('order_id')->unsigned();
			$table->string('item_name', 191);
			$table->boolean('returned')->default(0);
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
		Schema::drop('order_items');
	}

}
