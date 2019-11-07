<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('invoice_id', 191);
			$table->float('payment_mode_id', 10, 0);
			$table->float('reference', 10, 0)->nullable();
			$table->integer('status')->default(1);
			$table->dateTime('posting_date');
			$table->timestamps();
			$table->integer('warehouse_id')->unsigned();
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
		Schema::drop('payments');
	}

}
