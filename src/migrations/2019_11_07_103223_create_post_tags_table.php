<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('post_tags', function(Blueprint $table)
		{
			$table->integer('post_id')->unsigned();
			$table->integer('tag_id')->unsigned()->index('post_tags_tag_id_foreign');
			$table->primary(['post_id','tag_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('post_tags');
	}

}
