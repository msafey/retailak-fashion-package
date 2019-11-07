<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('name_en', 191)->nullable();
			$table->integer('parent_item_group')->unsigned()->nullable();
			$table->boolean('has_sub')->nullable();
			$table->integer('sorting_no')->nullable();
			$table->integer('status')->default(1);
			$table->integer('user_deleted')->nullable();
			$table->string('item_code', 191)->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->string('slug_ar', 150);
			$table->string('slug_en', 150)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categories');
	}

}
