<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeasonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seasons', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('name', 191);
			$table->string('name_en', 191);
			$table->text('description', 65535)->nullable();
			$table->text('description_en', 65535)->nullable();
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
		Schema::drop('seasons');
	}

}
