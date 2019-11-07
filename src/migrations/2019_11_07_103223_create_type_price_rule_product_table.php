<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTypePriceRuleProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('type_price_rule_product', function(Blueprint $table)
		{
			$table->bigInteger('id')->unsigned()->primary();
			$table->string('type')->index();
			$table->bigInteger('price_rule_product_id')->unsigned()->index('type_price_rule_product_price_rule_product_id_foreign');
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
		Schema::drop('type_price_rule_product');
	}

}
