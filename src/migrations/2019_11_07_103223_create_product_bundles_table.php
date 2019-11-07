<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductBundlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_bundles', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('product_id')->unsigned();
			$table->string('name', 191);
			$table->text('description', 65535)->nullable();
			$table->integer('item_group')->unsigned();
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
		Schema::drop('product_bundles');
	}

}
