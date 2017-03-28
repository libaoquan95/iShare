<?php namespace App\Http\Controllers\GotoUrl;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_userurl;
use App\Bu_userurlvisit;
use App\Bu_urlvisit;
use App\Bu_user;
use App\Bu_userurl_topic;
use App\Bu_topic;
use App\Bu_report_url;
use Session;

class GotoUrlController extends Controller {

	/**
	 * 跳转
	 *
	 * @return Response
	 */
	public function inedx()
	{
		echo "<script>";
		echo "window.location.href='/'";
		echo "</script>";
	}

	/**
	 * 跳转
	 *
	 * @return Response
	 */
	public function gotoUrl($gotoUrl)
	{
		//	检索数据库
		echo "<p>正在解析短地址...</p>";
		$Url = Bu_userurl::where('short_url', $gotoUrl)->first();
		echo "<p>解析短地址完成！</p>";
		
		//	url信息存在
		if($Url != '')
		{
			//	检测链接是否有效
			$url = $Url->long_url;

			echo "<p>正在验证目标网址有效性...</p>";
			$check = @fopen($url,"r");  
			if($check)
			{
				echo "<p>验证目标网址有效性完成，目标网址有效！</p>";
				echo "<p>正在更新相关网址信息...</p>";
				//	url信息未暂停分享
				if($Url->url_status == 0)
				{
					//	得到跳转地址
					$long_url = $Url->long_url;

					//	更新访问量与已注册用户访问量
					$updated_at = date('Y-m-d H:i:s');
					$userurl_info = Bu_userurl::where('short_url', $gotoUrl)->first();
					$url_pv = $userurl_info->url_pv + 1;
					if(Session::has('auth_state')) {
						$user_url_pv = $userurl_info->user_url_pv + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('url_pv' => $url_pv, 'user_url_pv' => $user_url_pv, 'updated_at' => $updated_at));
					}
					else {
						Bu_userurl::where('short_url', $gotoUrl)->update(array('url_pv' => $url_pv, 'updated_at' => $updated_at));
					}

					//	更新访问系统量
					$platform = $this->getOS();

					if($platform == 'windows') {
						$OS_count = $userurl_info->OS_windows + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('OS_windows' => $OS_count));
					}
					elseif($platform == 'mac') {
						$OS_count = $userurl_info->OS_mac + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('OS_mac' => $OS_count));
					} 
					elseif($platform == 'ipod') {
						$OS_count = $userurl_info->OS_ipod + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('OS_ipod' => $OS_count));
					}
					elseif($platform == 'ipad') {
						$OS_count = $userurl_info->OS_ipad + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('OS_ipad' => $OS_count));
					} 
					elseif($platform == 'iphone') {
						$OS_count = $userurl_info->OS_iphone + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('OS_iphone' => $OS_count));
					} 
					elseif ($platform == 'android') {
						$OS_count = $userurl_info->OS_android + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('OS_android' => $OS_count));
					} 
					elseif($platform ==  'unix') {
						$OS_count = $userurl_info->OS_unix + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('OS_unix' => $OS_count));
					} 
					elseif($platform == 'linux') {
						$OS_count = $userurl_info->OS_linux + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('OS_linux' => $OS_count));
					} 
					elseif($platform == 'windows_phone') {
						$OS_count = $userurl_info->OS_windows_phone + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('OS_windows_phone' => $OS_count));
					} 
					elseif($platform == 'other') {
						$OS_count = $userurl_info->OS_other + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('OS_other' => $OS_count));
					}

					//	更新访问地址省份
					$ip = $this->getIP();
					$province = $this->getProvince($ip);
					if( $province == "hebei" ) {
						$province_count = $userurl_info->province_hebei + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_hebei' => $province_count));
					}
					elseif ( $province == "shanxi" ) {
						$province_count = $userurl_info->province_shanxi + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_shanxi' => $province_count));
					}
					elseif ( $province == "liaoning" ) {
						$province_count = $userurl_info->province_liaoning + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_liaoning' => $province_count));
					}
					elseif ( $province == "jilin" ) {
						$province_count = $userurl_info->province_jilin + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_jilin' => $province_count));
					}
					elseif ( $province == "heilongjiang" ) {
						$province_count = $userurl_info->province_heilongjiang + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_heilongjiang' => $province_count));
					}
					elseif ( $province == "jiangsu" ) {
						$province_count = $userurl_info->province_jiangsu + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_jiangsu' => $province_count));
					}
					elseif ( $province == "zhejiang" ) {
						$province_count = $userurl_info->province_zhejiang + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_zhejiang' => $province_count));
					}
					elseif ( $province == "anhui" ) {
						$province_count = $userurl_info->province_anhui + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_anhui' => $province_count));
					}
					elseif ( $province == "fujian" ) {
						$province_count = $userurl_info->province_fujian + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_fujian' => $province_count));
					}
					elseif ( $province == "jiangxi" ) {
						$province_count = $userurl_info->province_jiangxi + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_jiangxi' => $province_count));
					}
					elseif ( $province == "shandong" ) {
						$province_count = $userurl_info->province_shandong + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_shandong' => $province_count));
					}
					elseif ( $province == "henan" ) {
						$province_count = $userurl_info->province_henan + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_henan' => $province_count));
					}
					elseif ( $province == "hubei" ) {
						$province_count = $userurl_info->province_hubei + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_hubei' => $province_count));
					}
					elseif ( $province == "hunan" ) {
						$province_count = $userurl_info->province_hunan + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_hunan' => $province_count));
					}
					elseif ( $province == "guangdong" ) {
						$province_count = $userurl_info->province_guangdong + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_guangdong' => $province_count));
					}
					elseif ( $province == "guangxi" ) {
						$province_count = $userurl_info->province_guangxi + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_guangxi' => $province_count));
					}
					elseif ( $province == "hainan" ) {
						$province_count = $userurl_info->province_hainan + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_hainan' => $province_count));
					}
					elseif ( $province == "sichuan" ) {
						$province_count = $userurl_info->province_sichuan + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_sichuan' => $province_count));
					}
					elseif ( $province == "guizhou" ) {
						$province_count = $userurl_info->province_guizhou + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_guizhou' => $province_count));
					}
					elseif ( $province == "yunnan" ) {
						$province_count = $userurl_info->province_yunnan + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_yunnan' => $province_count));
					}
					elseif ( $province == "gansu" ) {
						$province_count = $userurl_info->province_gansu + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_gansu' => $province_count));
					}
					elseif ( $province == "ningxia" ) {
						$province_count = $userurl_info->province_ningxia + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_ningxia' => $province_count));
					}
					elseif ( $province == "xinjiang" ) {
						$province_count = $userurl_info->province_xinjiang + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_xinjiang' => $province_count));
					}
					elseif ( $province == "shaanxi" ) {
						$province_count = $userurl_info->province_shaanxi + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_shaanxi' => $province_count));
					}
					elseif ( $province == "qinghai" ) {
						$province_count = $userurl_info->province_qinghai + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_qinghai' => $province_count));
					}
					elseif ( $province == "taiwan" ) {
						$province_count = $userurl_info->province_taiwan + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_taiwan' => $province_count));
					}
					elseif ( $province == "neimenggu" ) {
						$province_count = $userurl_info->province_neimenggu + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_neimenggu' => $province_count));
					}
					elseif ( $province == "xizang" ) {
						$province_count = $userurl_info->province_xizang + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_xizang' => $province_count));
					}
					elseif ( $province == "beijing" ) {
						$province_count = $userurl_info->province_beijing + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_beijing' => $province_count));
					}
					elseif ( $province == "shanghai" ) {
						$province_count = $userurl_info->province_shanghai + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_shanghai' => $province_count));
					}
					elseif ( $province == "tianjin" ) {
						$province_count = $userurl_info->province_tianjin + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_tianjin' => $province_count));
					}
					elseif ( $province == "chongqing" ) {
						$province_count = $userurl_info->province_chongqing + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_chongqing' => $province_count));
					}
					elseif ( $province == "xianggang" ) {
						$province_count = $userurl_info->province_xianggang + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_xianggang' => $province_count));
					}
					elseif ( $province == "aomen" ) {
						$province_count = $userurl_info->province_aomen + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_aomen' => $province_count));
					}
					else {
						$province_count = $userurl_info->province_other + 1;
						Bu_userurl::where('short_url', $gotoUrl)->update(array('province_other' => $province_count));
					}

					//	统计单日访问量
					$today_year = date('Y');
					$today_month = date('m');
					$today_day = date('d');
					$created_at = $updated_at = date('Y-m-d H:i:s');
					$today_visit_info = Bu_urlvisit::where('short_url', $gotoUrl)
												->where('visit_year', $today_year)
												->where('visit_month', $today_month)
												->where('visit_day', $today_day)
												->first();
					//	今日无访问量 新建
					if($today_visit_info == '')
					{
						$id = Bu_urlvisit::insertGetId(
							array(
								'short_url' => $gotoUrl, 
								'visit_year' => $today_year,
								'visit_month' => $today_month,
								'visit_day' => $today_day,
								'url_pv' => 1,
								'created_at'=> $created_at,
								'updated_at'=> $updated_at
							)
						);
					}
					//	有 更新
					else
					{
						$url_pv = $today_visit_info->url_pv + 1;
						Bu_urlvisit::where('short_url', $gotoUrl)
									->where('visit_year', $today_year)
									->where('visit_month', $today_month)
									->where('visit_day', $today_day)
									->update(
										array(
											'url_pv' => $url_pv,
											'updated_at' => $updated_at
										)
						);
					}

					//	更新访问表
					$user_name = Session::get('user_name');
					if($user_name != "")
					{
						$created_at = $updated_at = date('Y-m-d H:i:s');
						$userurl_id = Bu_userurlvisit::insertGetId(
							array(  'short_url'	=> $gotoUrl,
									'user_name'	=> $user_name,
									'user_ip'	=> $ip,
									'state'		=> 1,
									'created_at'=> $created_at,
									'updated_at'=> $updated_at)
						);
					}

					echo "<p>相关网址信息已更新！</p>";
					echo "<p>正在跳转...</p>";
					//	跳转
					echo "<script>";
					echo "window.location.href='$long_url'";
					echo "</script>";
					//return view('goto.gotoHome')->with("info", $long_url);
				}
				//	url信息暂停分享
				elseif($Url->url_status == 1)
				{
					return view('errors.404_pagegone');
				}
			}
			//	网址已无效
			else
			{
				echo "<p>验证目标网址有效性完成，目标网址无效！</p>";
				echo "<p>正在删除相关网址信息...</p>";
				$short_url = $gotoUrl;

				//	bu_users user_url_count 减1
				$user_name = Session::get('user_name');
				$user_info = Bu_user::where('name', $user_name)->first();
				$user_url_count = $user_info->user_url_count - 1;
				Bu_user::where('name', $user_name)->update(array('user_url_count' => $user_url_count));

				//	bu_userurls 删除 url信息 索引:short_url 得到 此url的id:short_url_id
				$userurl_info = Bu_userurl::where('short_url', $short_url)->first();
				$short_url_id = $userurl_info->id;
				Bu_userurl::where('short_url', $short_url)->delete();

				//	bu_userurlvisits 删除 索引:short_url 访问信息
				Bu_userurlvisit::where('short_url', $short_url)->delete();

				//	bu_userurl_topics 删除 索引:short_url_id 话题信息 得到 话题的id:topic_id
				$url_topics = Bu_userurl_topic::where('bu_userurls_id', $short_url_id)->get();
				foreach($url_topics as $url_topic)
				{
					//	bu_topics urlCount 减1 索引:topic_id
					$bu_topics_id = $url_topic->bu_topics_id;
					$topic_info = Bu_topic::where('id', $bu_topics_id)->first();
					$topic_url_count = $topic_info->urlCount - 1;
					Bu_topic::where('id', $bu_topics_id)->update(array('urlCount' => $topic_url_count));
				}

				Bu_userurl_topic::where('bu_userurls_id', $short_url_id)->delete();
				echo "<p>相关网址信息已删除！</p>";
				echo "<p>正在跳转...</p>";
				return view('errors.404_pagegone');
			}
		}
		//	检索不存在
		else
		{
			return view('errors.404_pagegone');
		}
	}

	/**
	 * 获取访问者ip地址
	 *
	 * @return Response
	 */
	public function getIP()
	{
		static $realip;

		if (isset($_SERVER)){
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} 
			else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$realip = $_SERVER["HTTP_CLIENT_IP"];
			} 
			else {
				$realip = $_SERVER["REMOTE_ADDR"];
			}
		} 
		else {
		if (getenv("HTTP_X_FORWARDED_FOR")){
				$realip = getenv("HTTP_X_FORWARDED_FOR");
			} 
			else if (getenv("HTTP_CLIENT_IP")) {
				$realip = getenv("HTTP_CLIENT_IP");
			} 
			else {
				$realip = getenv("REMOTE_ADDR");
			}
		}

		if($realip == "::1")
			$realip = "127.0.0.1";

		return $realip;
	}
	
	/**
	 * 判断访问者操作系统
	 *
	 * @return Response
	 */
	public function getOS()
	{
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		
		if(strpos($agent, 'windows nt')) {
			$platform = 'windows';
		}
		elseif(strpos($agent, 'macintosh')) {
			$platform = 'mac';
		} 
		elseif(strpos($agent, 'ipod')) {
			$platform = 'ipod';
		}
		elseif(strpos($agent, 'ipad')) {
			$platform = 'ipad';
		} 
		elseif(strpos($agent, 'iphone')) {
			$platform = 'iphone';
		} 
		elseif (strpos($agent, 'android')) {
			$platform = 'android';
		} 
		elseif(strpos($agent, 'unix')) {
			$platform = 'unix';
		} 
		elseif(strpos($agent, 'linux')) {
			$platform = 'linux';
		} 
		elseif(strpos($agent, 'windows phone')) {
			$platform = 'windows_phone';
		} 
		else {
			$platform = 'other';
		}
		return $platform;
	}

	
	/**
	 * 判断访问者ip所在省份
	 *
	 * @return Response
	 */
	public function getProvince($ip)
	{
		$json = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip);

		$arr = json_decode($json);

		//echo $arr->data->country;			//国家
		//echo $arr->data->area;			//区域
		$province = $arr->data->region;		//省份
		//echo $arr->data->city;			//城市
		//echo $arr->data->isp;				//运营商

		$province = "省份".$arr->data->region;

		$region = "";
		if(strpos($province, "河北")) {
			$region =  "hebei";
		}
		elseif (strpos($province, "山西")) {
			$region =  "shanxi";
		}
		elseif (strpos($province, "辽宁")) {
			$region = "liaoning";
		}
		elseif (strpos($province, "吉林")) {
			$region = "jilin";
		}
		elseif (strpos($province, "黑龙江")) {
			$region = "heilongjiang";
		}
		elseif (strpos($province, "江苏")) {
			$region = "jiangsu";
		}
		elseif (strpos($province, "浙江")) {
			$region = "zhejiang";
		}
		elseif (strpos($province, "安徽")) {
			$region = "anhui";
		}
		elseif (strpos($province, "福建")) {
			$region = "fujian";
		}
		elseif (strpos($province, "江西")) {
			$region = "jiangxi";
		}
		elseif (strpos($province, "山东")) {
			$region = "shandong";
		}
		elseif (strpos($province, "河南")) {
			$region = "henan";
		}
		elseif (strpos($province, "湖北")) {
			$region = "hubei";
		}
		elseif (strpos($province, "湖南")) {
			$region = "hunan";
		}
		elseif (strpos($province, "广东")) {
			$region = "guangdong";
		}
		elseif (strpos($province, "广西")) {
			$region = "guangxi";
		}
		elseif (strpos($province, "海南")) {
			$region = "hainan";
		}
		elseif (strpos($province, "四川")) {
			$region = "sichuan";
		}
		elseif (strpos($province, "贵州")) {
			$region = "guizhou";
		}
		elseif (strpos($province, "云南")) {
			$region = "yunnan";
		}
		elseif (strpos($province, "甘肃")) {
			$region = "gansu";
		}
		elseif (strpos($province, "宁夏")) {
			$region = "ningxia";
		}
		elseif (strpos($province, "新疆")) {
			$region = "xinjiang";
		}
		elseif (strpos($province, "陕西")) {
			$region = "shaanxi";
		}
		elseif (strpos($province, "青海")) {
			$region = "qinghai";
		}
		elseif (strpos($province, "台湾")) {
			$region = "taiwan";
		}
		elseif (strpos($province, "内蒙古")) {
			$region = "neimenggu";
		}
		elseif (strpos($province, "西藏")) {
			$region = "xizang";
		}
		elseif (strpos($province, "北京")) {
			$region = "beijing";
		}
		elseif (strpos($province, "上海")) {
			$region = "shanghai";
		}
		elseif (strpos($province, "天津")) {
			$region = "tianjin";
		}
		elseif (strpos($province, "重庆")) {
			$region = "chongqing";
		}
		elseif (strpos($province, "香港")) {
			$region = "xianggang";
		}
		elseif (strpos($province, "澳门")) {
			$region = "aomen";
		}
		else {
			$region = "other";
		}
		return $region;
	}
}
