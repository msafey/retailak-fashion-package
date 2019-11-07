<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaxsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('taxs', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('type', 191);
			$table->float('rate', 10, 0)->nullable();
			$table->float('amount', 10, 0)->nullable();
			$table->boolean('status')->default(0);
			$table->string('title', 191);
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
		Schema::drop('taxs');
	}

}
