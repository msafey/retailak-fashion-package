<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTimeSectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('time_sections', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('name');
			$table->string('from');
			$table->string('to');
			$table->string('status')->default('1');
			$table->timestamps();
			$table->string('name_en')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('time_sections');
	}

}
