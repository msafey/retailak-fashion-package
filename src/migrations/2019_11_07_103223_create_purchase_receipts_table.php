<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_receipts', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->text('accepted_quantity', 65535);
			$table->float('grand_total_amount', 10, 0);
			$table->integer('purchase_order_id')->unsigned();
			$table->integer('status')->default(1);
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
		Schema::drop('purchase_receipts');
	}

}
