<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaggingTagGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tagging_tag_groups', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug', 125)->index();
			$table->string('name', 125);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tagging_tag_groups');
	}

}
