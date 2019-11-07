<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePriceRuleProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('price_rule_product', function(Blueprint $table)
		{
			$table->bigInteger('id')->unsigned()->primary();
			$table->integer('product_id')->unsigned()->index('price_rule_product_product_id_foreign');
			$table->string('rule_name', 200);
			$table->integer('min_qty')->unsigned();
			$table->integer('max_qty')->unsigned();
			$table->dateTime('valid_from');
			$table->dateTime('valid_to');
			$table->enum('discount_type', array('price','percentage'));
			$table->float('discount_price');
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
		Schema::drop('price_rule_product');
	}

}
