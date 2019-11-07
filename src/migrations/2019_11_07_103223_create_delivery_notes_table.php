<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_notes', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('order_id')->unsigned();
			$table->string('user_id', 191);
			$table->text('productlist', 65535);
			$table->date('date')->nullable();
			$table->integer('shipping_role_id')->nullable();
			$table->timestamps();
			$table->integer('status')->default(1);
			$table->boolean('is_deleted')->default(0);
			$table->softDeletes();
			$table->integer('user_deleted')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('delivery_notes');
	}

}
