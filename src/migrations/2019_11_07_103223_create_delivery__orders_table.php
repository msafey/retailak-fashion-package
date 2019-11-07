<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery__orders', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('delivery_id');
			$table->integer('time_section_id');
			$table->text('orders_id', 65535)->nullable();
			$table->string('date', 191);
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
		Schema::drop('delivery__orders');
	}

}
