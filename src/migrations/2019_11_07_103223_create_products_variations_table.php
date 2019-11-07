<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsVariationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products_variations', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('products_id')->unsigned()->nullable();
			$table->integer('parent_variant_id')->nullable();
			$table->string('variations_data', 191);
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
		Schema::drop('products_variations');
	}

}
