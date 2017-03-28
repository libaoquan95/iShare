<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuBlackListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bu_black_lists', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('short_demo');
			$table->string('long_demo');
			$table->string('demo_name');
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
		Schema::drop('bu_black_lists');
	}

}
