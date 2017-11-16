<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLinksMetricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('links_metrics', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('link_id')->nullable();
			$table->integer('shares')->nullable();
			$table->integer('retweets')->nullable();
			$table->integer('favorites')->nullable();
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
		Schema::drop('links_metrics');
	}

}
