<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLinksMetricsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('links_metrics', function(Blueprint $table)
		{
			$table->foreign('link_id', 'links_metrics_link_id_fkey')->references('id')->on('links')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('links_metrics', function(Blueprint $table)
		{
			$table->dropForeign('links_metrics_link_id_fkey');
		});
	}

}
