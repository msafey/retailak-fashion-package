<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryCarsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_cars', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('title', 191);
			$table->string('car_model', 191);
			$table->string('car_plate', 191);
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
		Schema::drop('delivery_cars');
	}

}
