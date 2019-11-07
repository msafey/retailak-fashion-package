<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sales_invoices', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('order_id')->unsigned();
			$table->integer('delivery_note_id')->unsigned();
			$table->string('user_id', 191);
			$table->text('productlist', 65535);
			$table->date('date')->nullable();
			$table->integer('shipping_role_id')->nullable();
			$table->integer('status')->default(1);
			$table->integer('delivery_order_id')->unsigned();
			$table->boolean('is_deleted')->default(0);
			$table->integer('user_deleted')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sales_invoices');
	}

}
