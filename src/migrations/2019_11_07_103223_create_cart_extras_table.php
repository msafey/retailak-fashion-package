<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCartExtrasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cart_extras', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cart_item_id')->unsigned();
			$table->integer('item_id')->unsigned();
			$table->integer('extra_id')->unsigned();
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
		Schema::drop('cart_extras');
	}

}
