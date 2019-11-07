<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stocks', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->bigInteger('product_id')->unsigned()->nullable();
			$table->integer('warehouse_id')->unsigned();
			$table->integer('stock_qty');
			$table->string('status', 191);
			$table->string('moved_from_warehouse', 191)->nullable();
			$table->string('destination_warehouse', 191)->nullable();
			$table->integer('purchase_order_reference_id')->unsigned()->nullable();
			$table->boolean('active')->default(1);
			$table->integer('supplier_id')->unsigned()->nullable();
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
		Schema::drop('stocks');
	}

}
