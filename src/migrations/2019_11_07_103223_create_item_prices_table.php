<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateItemPricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_prices', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('product_id')->unsigned();
			$table->integer('price_list_id')->unsigned();
			$table->float('rate', 10, 0);
			$table->string('currency', 191)->default('EGP');
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
		Schema::drop('item_prices');
	}

}
