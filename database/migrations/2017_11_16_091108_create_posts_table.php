<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('link_id');
			$table->string('url')->nullable();
			$table->string('description')->nullable();
			$table->string('content')->nullable();
			$table->dateTime('publish_at')->nullable();
			$table->string('publish_on')->nullable();
			$table->integer('publisher_id')->nullable();
			$table->bigInteger('published_id')->nullable();
			$table->string('published_url')->nullable();
			$table->boolean('has_failed')->nullable();
			$table->softDeletes();
			$table->timestamps();
			$table->boolean('is_ignored')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
	}

}
