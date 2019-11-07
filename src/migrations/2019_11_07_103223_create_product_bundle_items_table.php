<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductBundleItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_bundle_items', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('item_id')->unsigned();
			$table->integer('bundle_id');
			$table->integer('qty');
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
		Schema::drop('product_bundle_items');
	}

}
