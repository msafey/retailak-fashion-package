<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('name', 191);
			$table->string('name_en', 191)->nullable();
			$table->text('description', 65535);
			$table->text('description_en', 65535);
			$table->integer('item_group')->unsigned();
			$table->integer('second_item_group')->unsigned()->nullable();
			$table->string('item_code', 191)->unique();
			$table->float('standard_rate', 10, 0);
			$table->string('uom', 191)->nullable();
			$table->string('weight', 191)->nullable();
			$table->boolean('active')->default(1);
			$table->integer('stock_qty')->nullable();
			$table->boolean('is_bundle')->default(0);
			$table->integer('sorting_no')->default(0);
			$table->integer('brand_id')->unsigned()->nullable();
			$table->boolean('has_variants')->default(0);
			$table->boolean('is_variant')->default(0);
			$table->integer('parent_variant_id')->nullable();
			$table->integer('user_deleted')->nullable();
			$table->boolean('has_attributes')->default(0);
			$table->boolean('is_food_extras')->default(0);
			$table->enum('food_taste_available', array('regular','spicy'));
			$table->string('food_optional_note', 191)->nullable();
			$table->enum('food_type', array('one_size','multi_sizes','options'))->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->boolean('is_deleted')->default(0);
			$table->string('image')->nullable();
			$table->float('cost', 10, 0)->default(0);
			$table->integer('season_id')->nullable();
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
		Schema::drop('products');
	}

}
