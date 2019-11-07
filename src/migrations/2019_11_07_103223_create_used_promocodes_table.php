<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsedPromocodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('used_promocodes', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('code', 191);
			$table->float('discount_rate', 10);
			$table->integer('order_id')->unsigned();
			$table->string('user_id', 191);
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
		Schema::drop('used_promocodes');
	}

}
