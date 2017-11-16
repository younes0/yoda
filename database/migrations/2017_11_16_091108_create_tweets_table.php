<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTweetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tweets', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('collect_id');
			$table->string('expanded_url')->nullable();
			$table->bigInteger('source_id');
			$table->bigInteger('user_id')->nullable();
			$table->string('user_name')->nullable();
			$table->string('url');
			$table->dateTime('published_at')->nullable();
			$table->string('content');
			$table->string('image_url')->nullable();
			$table->text('hashtags')->nullable();
			$table->string('lang')->nullable();
			$table->integer('retweet_count')->default(0);
			$table->integer('favorite_count')->default(0);
			$table->boolean('is_retweet')->default(0);
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
		Schema::drop('tweets');
	}

}
