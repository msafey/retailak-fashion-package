<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePriceListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('price_lists', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('price_list_name', 60);
			$table->string('currency_code', 10)->nullable();
			$table->string('type', 60);
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
		Schema::drop('price_lists');
	}

}
