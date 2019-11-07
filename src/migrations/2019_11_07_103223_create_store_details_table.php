<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoreDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('store_details', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('shop_type_id')->unsigned()->nullable();
			$table->string('store_name', 191)->nullable();
			$table->string('store_address', 191)->nullable();
			$table->string('tax_card', 191)->nullable();
			$table->string('commercial_register', 191)->nullable();
			$table->string('user_id', 191);
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
		Schema::drop('store_details');
	}

}
