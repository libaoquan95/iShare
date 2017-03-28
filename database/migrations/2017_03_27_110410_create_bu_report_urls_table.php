<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuReportUrlsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bu_report_urls', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('short_url')->nullable();
			$table->string('url_user')->nullable();
			$table->integer('invalid');
			$table->integer('harmful');
			$table->string('report_man');
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
		Schema::drop('bu_report_urls');
	}

}
