<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFoodExtasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('food_extas', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('extra_product_id');
			$table->float('food_extra_price', 10, 0);
			$table->boolean('is_optional')->default(0);
			$table->integer('related_product_id');
			$table->boolean('is_deleted')->default(0);
			$table->integer('user_deleted')->nullable();
			$table->softDeletes();
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
		Schema::drop('food_extas');
	}

}
