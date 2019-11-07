<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesOrderPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sales_order_payments', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('order_id')->unsigned();
			$table->integer('sales_invoice_id')->unsigned();
			$table->integer('payment_mode_id')->unsigned();
			$table->date('date');
			$table->float('paid_amount', 10, 0)->unsigned();
			$table->float('unallocated_amount', 10, 0)->unsigned();
			$table->float('final_total_amount', 10, 0)->unsigned();
			$table->integer('status')->default(1);
			$table->float('final_total_amount_after_discount', 10, 0)->unsigned();
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
		Schema::drop('sales_order_payments');
	}

}
