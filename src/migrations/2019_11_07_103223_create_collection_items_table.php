<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCollectionItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collection_items', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('product_id');
			$table->integer('collection_id')->unsigned();
			$table->string('item_code', 191);
			$table->integer('sortno')->nullable();
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
		Schema::drop('collection_items');
	}

}
