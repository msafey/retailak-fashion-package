<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLineHaulBatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('line_haul_batches', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('driver_name', 191);
			$table->string('car_plate_number', 191);
			$table->float('weight')->nullable();
			$table->integer('purchase_order_number');
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
		Schema::drop('line_haul_batches');
	}

}
