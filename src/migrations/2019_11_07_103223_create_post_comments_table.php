<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('post_comments', function(Blueprint $table)
		{
			$table->integer('post_id')->unsigned();
			$table->integer('comment_id')->unsigned()->index('post_comments_comment_id_foreign');
			$table->primary(['post_id','comment_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('post_comments');
	}

}
