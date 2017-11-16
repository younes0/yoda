<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOriginsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('origins', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('type')->default('home');
			$table->bigInteger('account_id');
			$table->timestamps();
			$table->string('name')->default('untitled');
			$table->integer('list_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('origins');
	}

}
