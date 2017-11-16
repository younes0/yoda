<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCollectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('collects', function(Blueprint $table)
		{
			$table->foreign('origin_id', 'collects_origin_id_fkey')->references('id')->on('origins')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('collects', function(Blueprint $table)
		{
			$table->dropForeign('collects_origin_id_fkey');
		});
	}

}
