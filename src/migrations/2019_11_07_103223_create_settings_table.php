<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->unique('id');
			$table->float('max_amount', 10, 0);
			$table->timestamps();
			$table->float('min_amount', 10, 0);
			$table->string('note_ar')->nullable();
			$table->string('note_en')->nullable();
			$table->integer('max_delivery_time')->default(24);
			$table->integer('min_delivery_time')->default(6);
			$table->boolean('user_activation')->default(0);
			$table->boolean('free_shipping')->default(0);
			$table->float('applied_amount', 10, 0)->nullable();
			$table->integer('expiration_days')->default(14);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
