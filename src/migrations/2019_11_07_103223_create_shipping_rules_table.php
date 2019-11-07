<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShippingRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shipping_rules', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('shipping_rule_label');
			$table->string('key');
			$table->boolean('disabled')->default(0);
			$table->float('rate', 10, 0);
			$table->string('calculate_based_on');
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
		Schema::drop('shipping_rules');
	}

}
