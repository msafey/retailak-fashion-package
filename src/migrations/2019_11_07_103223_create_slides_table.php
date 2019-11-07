<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSlidesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('slides', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('type', 191);
			$table->string('img_1', 191);
			$table->string('img_1_url', 191)->nullable();
			$table->string('img_2', 191)->nullable();
			$table->string('img_2_url', 191)->nullable();
			$table->string('img_3', 191)->nullable();
			$table->string('img_3_url', 191)->nullable();
			$table->string('text_1', 191)->nullable();
			$table->string('text_1_url', 191)->nullable();
			$table->string('text_2', 191)->nullable();
			$table->string('text_2_url', 191)->nullable();
			$table->string('text_3', 191)->nullable();
			$table->string('text_3_url', 191)->nullable();
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
		Schema::drop('slides');
	}

}
