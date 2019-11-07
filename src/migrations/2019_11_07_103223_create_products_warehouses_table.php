<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsWarehousesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_warehouses', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->bigInteger('product_id')->unsigned();
			$table->string('item_code', 191);
			$table->integer('warehouse_id')->unsigned();
			$table->integer('projected_qty')->default(0);
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
		Schema::drop('products_warehouses');
	}

}
