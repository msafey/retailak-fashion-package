<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVariantsMetasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('variants_metas', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('variant_data_id')->unsigned()->nullable();
			$table->string('variation_value', 191);
			$table->string('variation_value_en', 191);
			$table->string('code', 191)->nullable();
			$table->string('item_code', 191)->nullable();
			$table->timestamps();
			$table->string('variant_code', 191)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('variants_metas');
	}

}
