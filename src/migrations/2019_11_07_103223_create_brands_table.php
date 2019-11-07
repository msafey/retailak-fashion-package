<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBrandsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('brands', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 191);
			$table->string('name_en', 191);
			$table->text('description', 65535)->nullable();
			$table->text('description_en', 65535)->nullable();
			$table->integer('company_id')->unsigned()->nullable();
			$table->integer('user_deleted')->nullable();
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
		Schema::drop('brands');
	}

}
