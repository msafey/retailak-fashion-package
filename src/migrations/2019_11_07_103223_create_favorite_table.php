<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFavoriteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('favorite', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('product_name', 191);
			$table->string('product_code', 191)->nullable();
			$table->integer('user_id')->unsigned();
			$table->integer('product_id')->nullable();
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
		Schema::drop('favorite');
	}

}
