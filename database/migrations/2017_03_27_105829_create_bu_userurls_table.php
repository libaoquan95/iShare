<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuUserurlsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bu_userurls', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('short_url')->nullable();
			$table->string('long_url')->nullable();
			$table->integer('user_id');
			$table->text('url_describe')->nullable();
			$table->integer('url_status');
			$table->integer('OS_windows');
			$table->integer('OS_mac');
			$table->integer('OS_ipod');
			$table->integer('OS_ipad');
			$table->integer('OS_iphone');
			$table->integer('OS_android');
			$table->integer('OS_unix');
			$table->integer('OS_linux');
			$table->integer('OS_windows_phone');
			$table->integer('OS_other');
			$table->integer('url_pv');
			$table->integer('user_url_pv');
			$table->integer('province_hebei');
			$table->integer('province_shanxi');
			$table->integer('province_liaoning');
			$table->integer('province_jilin');
			$table->integer('province_heilongjiang');
			$table->integer('province_jiangsu');
			$table->integer('province_zhejiang');
			$table->integer('province_anhui');
			$table->integer('province_fujian');
			$table->integer('province_jiangxi');
			$table->integer('province_shandong');
			$table->integer('province_henan');
			$table->integer('province_hubei');
			$table->integer('province_hunan');
			$table->integer('province_guangdong');
			$table->integer('province_hainan');
			$table->integer('province_sichuan');
			$table->integer('province_guizhou');
			$table->integer('province_yunnan');
			$table->integer('province_shaanxi');
			$table->integer('province_gansu');
			$table->integer('province_taiwan');
			$table->integer('province_qinghai');
			$table->integer('province_neimenggu');
			$table->integer('province_xizang');
			$table->integer('province_guangxi');
			$table->integer('province_ningxia');
			$table->integer('province_xinjiang');
			$table->integer('province_beijing');
			$table->integer('province_shanghai');
			$table->integer('province_tianjin');
			$table->integer('province_xianggang');
			$table->integer('province_chongqing');
			$table->integer('province_aomen');
			$table->integer('province_other');
			$table->timestamps();
			$table->integer('url_regions');
			$table->text('url_reserve_url');
			$table->integer('url_ip');
			$table->integer('url_terminals');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bu_userurls');
	}

}
