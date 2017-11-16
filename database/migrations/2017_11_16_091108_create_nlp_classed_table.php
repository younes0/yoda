<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNlpClassedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nlp_classed', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('model_type');
			$table->bigInteger('model_id');
			$table->string('class')->nullable();
			$table->string('method')->nullable();
			$table->decimal('score', 10, 0)->nullable();
			$table->string('more')->nullable();
			$table->timestamps();
			$table->string('nlp_model')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('nlp_classed');
	}

}
