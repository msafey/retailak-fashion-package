<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('address', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 191)->nullable();
			$table->string('user_id', 191);
			$table->string('city', 191)->nullable();
			$table->string('building_no', 191)->nullable();
			$table->string('floor_no', 191)->nullable();
			$table->string('apartment_no', 191)->nullable();
			$table->string('street', 191);
			$table->integer('district_id')->unsigned();
			$table->string('nearest_landmark', 191)->nullable();
			$table->string('country', 191)->nullable()->default('Egypt');
			$table->string('address_phone', 191)->nullable();
			$table->timestamps();
			$table->string('lat', 191)->nullable();
			$table->string('lng', 191)->nullable();
			$table->boolean('active')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('address');
	}

}
