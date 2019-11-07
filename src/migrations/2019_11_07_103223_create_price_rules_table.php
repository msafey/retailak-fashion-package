<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePriceRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('price_rules', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('product_id', 191);
			$table->string('price_rule_name', 60);
			$table->dateTime('valid_from')->nullable();
			$table->dateTime('valid_to')->nullable();
			$table->string('type', 10);
			$table->integer('margin_type')->nullable();
			$table->integer('margin_rate')->nullable();
			$table->integer('discount_type');
			$table->integer('discount_rate')->nullable();
			$table->integer('min_qty');
			$table->integer('max_qty');
			$table->float('discount_price', 10, 0)->nullable();
			$table->integer('price_list_id')->unsigned()->nullable();
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
		Schema::drop('price_rules');
	}

}
