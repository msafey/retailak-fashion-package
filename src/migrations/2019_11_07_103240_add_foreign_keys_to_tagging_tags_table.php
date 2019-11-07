<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTaggingTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tagging_tags', function(Blueprint $table)
		{
			$table->foreign('tag_group_id')->references('id')->on('tagging_tag_groups')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tagging_tags', function(Blueprint $table)
		{
			$table->dropForeign('tagging_tags_tag_group_id_foreign');
		});
	}

}
