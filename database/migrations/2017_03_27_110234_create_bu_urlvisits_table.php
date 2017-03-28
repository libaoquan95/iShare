<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuUrlvisitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bu_urlvisits', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('short_url');
			$table->integer('url_pv');
			$table->integer('visit_year');
			$table->integer('visit_month');
			$table->integer('visit_day');
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
		Schema::drop('bu_urlvisits');
	}

}
