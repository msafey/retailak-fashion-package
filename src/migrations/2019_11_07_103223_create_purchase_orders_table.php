<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_orders', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->dateTime('required_by_date')->nullable();
			$table->string('tax_and_charges', 191)->nullable();
			$table->string('status', 191)->nullable();
			$table->string('shipping_rule', 191)->nullable();
			$table->float('grand_total_amount', 10, 0)->nullable();
			$table->integer('warehouse_id')->unsigned();
			$table->integer('company_id')->unsigned();
			$table->float('discount', 10);
			$table->enum('discount_type', array('persentage','amount'));
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
		Schema::drop('purchase_orders');
	}

}
