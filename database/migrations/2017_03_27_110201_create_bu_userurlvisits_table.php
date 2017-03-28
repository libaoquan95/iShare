<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuUserurlvisitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bu_userurlvisits', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('short_url');
			$table->string('user_name');
			$table->string('user_ip');
			$table->integer('state');
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
		Schema::drop('bu_userurlvisits');
	}

}
