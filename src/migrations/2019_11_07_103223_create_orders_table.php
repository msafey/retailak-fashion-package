<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('user_id', 191);
			$table->string('payment_method', 191);
			$table->integer('address_id');
			$table->integer('timesection_id')->nullable();
			$table->dateTime('date')->nullable();
			$table->string('salesorder_id', 191)->nullable();
			$table->string('status', 191);
			$table->float('shipping_rate', 10, 0)->nullable();
			$table->string('device_id', 191)->nullable();
			$table->string('device_os', 191)->nullable();
			$table->string('app_version', 191)->nullable();
			$table->boolean('is_deleted')->nullable();
			$table->integer('user_deleted')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->string('external_reciept_id', 191)->nullable();
			$table->text('note', 65535)->nullable();
			$table->integer('shipping_role_id')->nullable();
			$table->integer('payment_order_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('orders');
	}

}
