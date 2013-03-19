<?php

use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function($table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('name')->unique();
			$table->text('value')->nullable();
			$table->enum('format', array('string', 'json'))->default('string');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExsits('settings');
	}

}