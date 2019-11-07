<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sales_reports', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('product_id')->unsigned();
			$table->integer('category_id')->unsigned();
			$table->integer('qty');
			$table->date('date');
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
		Schema::drop('sales_reports');
	}

}
