<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAramexShipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('aramex_shipment', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('shipment_id', 65535);
			$table->integer('order_id');
			$table->text('response', 65535);
			$table->boolean('has_error');
			$table->text('transaction', 65535);
			$table->text('notification', 65535);
			$table->string('label_url');
			$table->timestamps();
			$table->boolean('shipment_track')->default(0);
			$table->string('status', 60)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('aramex_shipment');
	}

}
