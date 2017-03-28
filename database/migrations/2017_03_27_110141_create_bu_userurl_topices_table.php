<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuUserurlTopicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bu_userurl_topices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('bu_userurls_id');
			$table->integer('bu_topics_id');
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
		Schema::drop('bu_userurl_topices');
	}

}
