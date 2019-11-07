<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryMenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery__men', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('name', 191);
			$table->string('password', 191);
			$table->string('mobile', 191);
			$table->boolean('status')->default(1);
			$table->string('token', 191);
			$table->string('email', 191)->nullable();
			$table->date('date_of_birth');
			$table->date('date_of_joining');
			$table->string('gender', 191);
			$table->string('salesPersonCode', 191)->nullable();
			$table->string('employeeCode', 191);
			$table->integer('district_id')->unsigned()->nullable();
			$table->string('route', 191)->nullable();
			$table->integer('delivery_car_id')->unsigned()->nullable();
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
		Schema::drop('delivery__men');
	}

}
