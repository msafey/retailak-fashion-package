<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWarehousesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('warehouses', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('name', 191);
			$table->string('name_en', 191);
			$table->text('description', 65535)->nullable();
			$table->text('description_en', 65535)->nullable();
			$table->boolean('status')->default(1);
			$table->string('district_id', 191);
			$table->softDeletes();
			$table->timestamps();
			$table->string('warehouse_code', 191)->unique();
			$table->boolean('default_warehouse')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('warehouses');
	}

}
