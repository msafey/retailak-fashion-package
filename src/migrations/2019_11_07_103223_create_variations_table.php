<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVariationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('variations', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->boolean('status')->default(1);
			$table->string('name', 191);
			$table->string('name_en', 191);
			$table->boolean('affecting_stock')->default(0);
			$table->boolean('has_special_images')->default(0);
			$table->boolean('is_deleted')->default(0);
			$table->integer('user_deleted')->nullable();
			$table->string('key', 191);
			$table->string('is_color', 191)->default('0');
			$table->string('item_code', 191)->nullable();
			$table->softDeletes();
			$table->timestamps();
			$table->boolean('is_size')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('variations');
	}

}
