<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_attributes', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('products_id')->unsigned();
			$table->integer('attribute_id');
			$table->integer('value_id');
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
		Schema::drop('product_attributes');
	}

}
