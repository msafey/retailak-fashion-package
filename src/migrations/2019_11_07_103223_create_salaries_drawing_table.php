<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalariesDrawingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('salaries_drawing', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->integer('admin_id')->unsigned();
			$table->string('employee', 150);
			$table->float('cost', 10, 0);
			$table->boolean('type');
			$table->text('Reason', 65535);
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
		Schema::drop('salaries_drawing');
	}

}
