<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBundlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bundles', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('code', 191);
			$table->timestamps();
			$table->boolean('updated')->nullable();
			$table->string('org_qty', 191);
			$table->string('parent_item_code', 191);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bundles');
	}

}
