<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePromocodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('promocodes', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('code', 32);
			$table->float('reward', 10);
			$table->integer('userscount')->nullable();
			$table->dateTime('sfrom')->nullable();
			$table->dateTime('sto')->nullable();
			$table->boolean('active')->default(0);
			$table->string('org_qty', 191)->nullable();
			$table->boolean('freeShipping')->default(0);
			$table->enum('type', array('persentage','amount'));
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
		Schema::drop('promocodes');
	}

}
