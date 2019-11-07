<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerComplainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_complains', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('user_id')->unsigned();
			$table->text('message', 65535);
			$table->boolean('resolved')->default(0);
			$table->integer('answered_admin_id')->nullable();
			$table->text('admin_answer', 65535);
			$table->enum('application_type', array('complain','suggestion','other'));
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
		Schema::drop('customer_complains');
	}

}
