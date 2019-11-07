<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDistrictsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('districts', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('parent_id')->default(0);
			$table->integer('warehouse_id');
			$table->string('district_en');
			$table->string('district_ar');
			$table->boolean('active')->default(1);
			$table->timestamps();
			$table->integer('shipping_role')->unsigned();
			$table->string('territory');
			$table->float('shipping_rate')->default(15.00);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('districts');
	}

}
