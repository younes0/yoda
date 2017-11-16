<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLinksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('links', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('url');
			$table->boolean('is_human_approved')->nullable();
			$table->boolean('is_machine_approved')->nullable();
			$table->decimal('rating', 10, 0)->nullable();
			$table->dateTime('rated_at')->nullable();
			$table->string('host')->nullable();
			$table->string('type')->nullable();
			$table->string('title')->nullable();
			$table->string('description')->nullable();
			$table->string('images_url')->nullable();
			$table->char('lang', 2)->nullable();
			$table->boolean('has_paywall')->nullable();
			$table->timestamps();
			$table->string('content')->nullable();
			$table->string('html')->nullable();
			$table->dateTime('published_at')->nullable();
			$table->boolean('is_nlpdoc_checked')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('links');
	}

}
