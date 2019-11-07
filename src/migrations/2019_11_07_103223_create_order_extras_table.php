<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderExtrasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_extras', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('order_item_id')->unsigned();
			$table->integer('item_id')->unsigned();
			$table->integer('extra_id')->unsigned();
			$table->integer('qty');
			$table->float('rate', 10, 0);
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
		Schema::drop('order_extras');
	}

}
