<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPostCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('post_comments', function(Blueprint $table)
		{
			$table->foreign('comment_id')->references('id')->on('comments')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('post_id')->references('id')->on('posts')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('post_comments', function(Blueprint $table)
		{
			$table->dropForeign('post_comments_comment_id_foreign');
			$table->dropForeign('post_comments_post_id_foreign');
		});
	}

}
