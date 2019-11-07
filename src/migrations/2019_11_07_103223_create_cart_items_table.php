<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCartItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cart_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('item_id')->unsigned();
			$table->integer('qty')->unsigned();
			$table->integer('cart_id')->unsigned();
			$table->string('note', 191)->nullable();
			$table->string('key', 191)->nullable();
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
		Schema::drop('cart_items');
	}

}
