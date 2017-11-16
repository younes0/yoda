<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTweepsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tweeps', function(Blueprint $table)
		{
			$table->string('id')->primary('tweeps_pkey');
			$table->boolean('is_human_approved')->nullable();
			$table->integer('score')->nullable();
			$table->decimal('tweets_per_day', 10, 0)->nullable();
			$table->decimal('links_per_tweet', 10, 0)->nullable();
			$table->timestamps();
			$table->dateTime('metrics_updated_at')->nullable();
			$table->decimal('proper_lang_per_tweet', 10, 0)->nullable();
			$table->boolean('is_machine_approved')->nullable();
			$table->string('description')->nullable();
			$table->text('tweets_urls')->nullable();
			$table->decimal('proper_domain_per_link', 10, 0)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tweeps');
	}

}
