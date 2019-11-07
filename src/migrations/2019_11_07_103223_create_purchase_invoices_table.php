<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_invoices', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('purchased_item_details', 191);
			$table->integer('status')->default(1);
			$table->float('grand_total_amount', 10, 0);
			$table->integer('purchase_receipt_id')->unsigned();
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
		Schema::drop('purchase_invoices');
	}

}
