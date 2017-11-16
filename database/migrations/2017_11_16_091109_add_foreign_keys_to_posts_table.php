<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('posts', function(Blueprint $table)
		{
			$table->foreign('link_id', 'posts_link_id_fkey')->references('id')->on('links')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('publisher_id', 'posts_publisher_id_fkey')->references('id')->on('jedi_users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('posts', function(Blueprint $table)
		{
			$table->dropForeign('posts_link_id_fkey');
			$table->dropForeign('posts_publisher_id_fkey');
		});
	}

}
