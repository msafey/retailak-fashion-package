<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSlabsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('slabs', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('slab_name', 191);
			$table->float('min_amount_money', 10, 0);
			$table->float('discount_rate', 10, 0)->default(0);
			$table->string('discount_type', 191)->default('amount');
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
		Schema::drop('slabs');
	}

}
