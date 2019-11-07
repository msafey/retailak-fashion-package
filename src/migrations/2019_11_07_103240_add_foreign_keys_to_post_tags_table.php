<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPostTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('post_tags', function(Blueprint $table)
		{
			$table->foreign('post_id')->references('id')->on('posts')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('tag_id')->references('id')->on('tags')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('post_tags', function(Blueprint $table)
		{
			$table->dropForeign('post_tags_post_id_foreign');
			$table->dropForeign('post_tags_tag_id_foreign');
		});
	}

}
