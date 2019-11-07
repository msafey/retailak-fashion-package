<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBundleitemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bundleitems', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('bundle_id')->unsigned()->index('bundleitems_bundle_id_foreign');
			$table->integer('product_id')->unsigned()->index('bundleitems_product_id_foreign');
			$table->string('item_code', 191);
			$table->integer('qty');
			$table->timestamps();
			$table->boolean('updated')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bundleitems');
	}

}
