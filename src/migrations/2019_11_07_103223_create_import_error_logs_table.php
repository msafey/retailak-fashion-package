<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImportErrorLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('import_error_logs', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('index');
			$table->string('error', 191);
			$table->string('phone', 191);
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
		Schema::drop('import_error_logs');
	}

}
