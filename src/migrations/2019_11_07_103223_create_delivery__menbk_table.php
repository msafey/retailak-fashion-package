<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryMenbkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery__menbk', function(Blueprint $table)
		{
			$table->integer('id')->unsigned();
			$table->string('name', 191);
			$table->string('password', 191);
			$table->string('mobile', 191);
			$table->boolean('status')->default(1);
			$table->string('token', 191);
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
		Schema::drop('delivery__menbk');
	}

}
